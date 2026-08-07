<?php

namespace App\Listeners;

use App\Events\DownloadCompleted;
use App\Models\User;
use App\Notifications\DownloadCompletedNotification;

class SendDownloadNotification
{
    public function handle(DownloadCompleted $event): void
    {
        $user = $event->download->user;

        if ($user instanceof User) {
            $user->notify(new DownloadCompletedNotification(
                downloadId: $event->download->id,
                title: $event->download->title ?? 'Untitled media',
            ));
        }
    }
}
