export type MediaType = 'video' | 'audio' | 'image';
export type MediaFormat = 'mp4' | 'webm' | 'mp3' | 'm4a' | 'jpg' | 'png' | 'webp';
export type DownloadStatus = 'queued' | 'processing' | 'completed' | 'failed';
export type PlatformCategory = 'video' | 'music' | 'social' | 'image' | 'direct';

export interface DownloadOption {
    format: MediaFormat;
    quality?: string | null;
    file_size?: number | null;
    resolution?: string | null;
    mime_type?: string | null;
    download_url?: string | null;
}

export interface MediaMetadata {
    title: string;
    thumbnail_url: string | null;
    duration: number | null;
    resolution: string | null;
    media_type: MediaType;
    platform: string | null;
    formats: DownloadOption[];
    raw: Record<string, unknown>;
}

export interface Platform {
    id: number;
    name: string;
    slug: string;
    domain: string;
    color: string;
    icon: string | null;
    category: PlatformCategory;
    category_label: string;
    description: string | null;
    formats: MediaFormat[];
    is_active: boolean;
    visit_count: number;
    download_count: number;
}

export interface Download {
    id: string;
    title: string | null;
    source_url: string;
    thumbnail_url: string | null;
    duration: number | null;
    duration_human: string | null;
    resolution: string | null;
    file_size: number | null;
    file_size_human: string | null;
    media_type: MediaType;
    format: MediaFormat;
    quality: string | null;
    status: DownloadStatus;
    platform: Platform | null;
    file_url: string | null;
    metadata: Record<string, unknown> | null;
    created_at: string;
    updated_at: string;
}

export interface Setting {
    key: string;
    group: string;
    value: unknown;
    type: 'string' | 'integer' | 'boolean' | 'json';
    is_public: boolean;
}

export interface ActivityLog {
    id: string;
    type: string;
    type_label: string;
    description: string;
    metadata: Record<string, unknown> | null;
    ip_address: string | null;
    user_agent: string | null;
    user: { id: number; name: string; email: string } | null;
    created_at: string;
}

export interface PaginationMeta {
    current_page: number;
    from: number | null;
    last_page: number;
    links: Array<{ label: string; url: string | null; active: boolean }>;
    path: string;
    per_page: number;
    to: number | null;
    total: number;
}

export interface PaginatedResponse<T> {
    data: T[];
    links?: Array<{ label: string; url: string | null; active: boolean }>;
    meta?: PaginationMeta;
}

export interface AuthenticatedUser {
    id: number;
    name: string;
    email: string;
    is_admin: boolean;
    avatar_url: string | null;
}
