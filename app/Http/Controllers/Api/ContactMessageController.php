<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactMessageRequest;
use App\Http\Resources\ContactMessageResource;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ContactMessageController extends Controller
{
    public function store(StoreContactMessageRequest $request): JsonResponse
    {
        $message = ContactMessage::create([
            'id' => (string) \Str::orderedUuid(),
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'subject' => $request->validated('subject'),
            'message' => $request->validated('message'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'data' => new ContactMessageResource($message),
        ], 201);
    }

    public function index(): AnonymousResourceCollection
    {
        $messages = ContactMessage::query()
            ->orderByRaw('is_read asc')
            ->orderByDesc('created_at')
            ->paginate(15);

        return ContactMessageResource::collection($messages);
    }

    public function markRead(ContactMessage $message): ContactMessageResource
    {
        $message->update(['is_read' => true]);

        return new ContactMessageResource($message->fresh());
    }

    public function destroy(ContactMessage $message): JsonResponse
    {
        $message->delete();

        return response()->json(['message' => 'Message deleted.'], 200);
    }
}