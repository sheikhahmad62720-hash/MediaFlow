<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingsRequest;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use App\Repositories\SettingRepository;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    public function __construct(
        protected SettingService $settings,
        protected SettingRepository $repository,
    ) {}

    public function index(): JsonResponse
    {
        $public = $this->settings->public();

        return response()->json($public);
    }

    public function adminIndex(): JsonResponse
    {
        return response()->json(SettingResource::collection($this->repository->all()));
    }

    public function update(UpdateSettingsRequest $request, Setting $setting): SettingResource
    {
        $setting->update([
            'group' => $request->input('group', $setting->group),
            'value' => $request->input('value'),
            'type' => $request->input('type', $setting->type),
            'is_public' => $request->boolean('is_public', $setting->is_public),
        ]);

        $this->settings->flush();

        return new SettingResource($setting->fresh());
    }
}
