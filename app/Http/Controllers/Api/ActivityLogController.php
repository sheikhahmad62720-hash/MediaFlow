<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityLogIndexRequest;
use App\Http\Resources\ActivityLogResource;
use App\Repositories\ActivityLogRepository;
use Illuminate\Http\JsonResponse;

class ActivityLogController extends Controller
{
    public function __construct(
        protected ActivityLogRepository $activities,
    ) {}

    public function index(ActivityLogIndexRequest $request): JsonResponse
    {
        $filters = [
            'type' => $request->input('type'),
            'search' => $request->input('search'),
        ];

        $logs = $this->activities->paginate($request->integer('per_page', 15), $filters);

        return ActivityLogResource::collection($logs)->response()->setStatusCode(200);
    }
}
