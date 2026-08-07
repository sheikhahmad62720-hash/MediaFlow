<?php

namespace App\Http\Controllers\Api;

use App\Actions\Media\CreateDownload;
use App\DTOs\Filters\DownloadFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\DownloadIndexRequest;
use App\Http\Requests\DownloadRequest;
use App\Http\Resources\DownloadResource;
use App\Models\Download;
use App\Repositories\DownloadRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DownloadController extends Controller
{
    public function __construct(
        protected CreateDownload $createDownload,
        protected DownloadRepository $downloads,
    ) {}

    public function store(DownloadRequest $request): JsonResponse
    {
        $result = $this->createDownload->handle(
            $request->string('url'),
            $request->input('format'),
            $request->input('quality'),
        );

        return (new DownloadResource($result['download']))
            ->additional(['formats' => $result['options']])
            ->response()
            ->setStatusCode(201);
    }

    public function index(DownloadIndexRequest $request): JsonResponse
    {
        $filters = DownloadFilters::from($request);
        $userId = $request->user()?->getAuthIdentifier();

        $downloads = $this->downloads->paginate($filters, $userId);

        return DownloadResource::collection($downloads)->response()->setStatusCode(200);
    }

    public function show(Download $download): DownloadResource
    {
        $this->authorizeDownloadAccess($download);

        return new DownloadResource($download->load('platform'));
    }

    public function file(Download $download): Response
    {
        abort_if($download->file_path === null, 404, 'File is not available.');

        $disk = config('media.download_disk');

        if (! Storage::disk($disk)->exists($download->file_path)) {
            abort(404, 'File is no longer available.');
        }

        return Storage::disk($disk)->download($download->file_path, $download->file_name);
    }

    protected function authorizeDownloadAccess(Download $download): void
    {
        $user = request()->user();

        // Downloads created by guests are addressable only by their (random)
        // UUID, so any bearer of the identifier may inspect the status.
        if ($download->user_id === null) {
            return;
        }

        if ($user === null) {
            throw AuthorizationException::getInstance();
        }

        if ($download->user_id !== $user->getAuthIdentifier() && ! $user->isAdmin()) {
            throw new AuthorizationException('You are not authorized to view this download.');
        }
    }
}
