<?php

namespace App\Http\Controllers\Api;

use App\Actions\Media\AnalyzeMedia;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnalyzeMediaRequest;
use App\Http\Resources\MediaResource;
use Illuminate\Http\JsonResponse;

class MediaController extends Controller
{
    public function __construct(
        protected AnalyzeMedia $analyzeMedia,
    ) {}

    public function analyze(AnalyzeMediaRequest $request): MediaResource|JsonResponse
    {
        $metadata = $this->analyzeMedia->handle($request->string('url'));

        return new MediaResource($metadata);
    }
}
