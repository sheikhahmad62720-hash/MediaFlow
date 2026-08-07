<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DownloadCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $downloadId,
        public string $title,
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your download is ready')
            ->greeting('Hi there,')
            ->line("Your download **{$this->title}** has completed and is ready to retrieve.")
            ->action('View downloads', config('app.frontend_url', config('app.url')).'/account/downloads')
            ->line('Thank you for using MediaFlow!');
    }

    public function toArray($notifiable): array
    {
        return [
            'download_id' => $this->downloadId,
            'title' => $this->title,
        ];
    }
}
