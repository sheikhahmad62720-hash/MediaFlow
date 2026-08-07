<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlatformResource;
use App\Models\SupportedPlatform;
use App\Repositories\PlatformRepository;
use Illuminate\Http\JsonResponse;

class PlatformController extends Controller
{
    public function __construct(
        protected PlatformRepository $platforms,
    ) {}

    public function index(): JsonResponse
    {
        $platforms = $this->platforms->allActive()->map(fn (SupportedPlatform $p) => (new PlatformResource($p))->resolve());

        return response()->json($platforms);
    }

    public function show(string $slug): PlatformResource
    {
        $platform = SupportedPlatform::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return new PlatformResource($platform);
    }
}
