import axios, { AxiosInstance } from 'axios';

const baseURL = import.meta.env.VITE_API_URL || '/api';

const api: AxiosInstance = axios.create({
    baseURL,
    timeout: 300000,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
    withCredentials: false,
});

/**
 * Attach the authenticated bearer token (kept in localStorage by the auth
 * store) to every request.
 */
api.interceptors.request.use((config) => {
    const token = localStorage.getItem('mediaflow_token');

    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
});

/**
 * Normalize error responses so components receive a consistent shape.
 */
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response) {
            const { status, data } = error.response;

            return Promise.reject({
                status,
                message: data?.message ?? 'Something went wrong.',
                errors: data?.errors ?? {},
            });
        }

        return Promise.reject({
            status: 0,
            message: error.message ?? 'Network error.',
            errors: {},
        });
    },
);

export default api;
