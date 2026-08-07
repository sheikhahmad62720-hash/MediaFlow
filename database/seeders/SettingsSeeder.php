<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'app_name' => ['value' => config('app.name', 'MediaFlow'), 'type' => 'string', 'public' => true, 'label' => 'Application name', 'group' => 'general'],
            'app_tagline' => ['value' => 'Download media from public sources — fast, private, simple.', 'type' => 'string', 'public' => true, 'label' => 'Tagline', 'group' => 'general'],
            'app_description' => ['value' => 'MediaFlow is a Universal Media Downloader Platform.', 'type' => 'string', 'public' => true, 'label' => 'Meta description', 'group' => 'general'],
            'contact_email' => ['value' => 'hello@mediaflow.app', 'type' => 'string', 'public' => true, 'label' => 'Contact email', 'group' => 'general'],
            'support_email' => ['value' => 'support@mediaflow.app', 'type' => 'string', 'public' => false, 'label' => 'Support email', 'group' => 'general'],
            'max_file_size_bytes' => ['value' => 2199023255552, 'type' => 'integer', 'public' => true, 'label' => 'Max file size (bytes)', 'group' => 'limits'],
            'max_concurrent_downloads' => ['value' => 3, 'type' => 'integer', 'public' => false, 'label' => 'Max concurrent downloads', 'group' => 'limits'],
            'retention_days' => ['value' => 7, 'type' => 'integer', 'public' => false, 'label' => 'Download retention (days)', 'group' => 'storage'],
            'maintenance_mode' => ['value' => false, 'type' => 'boolean', 'public' => true, 'label' => 'Maintenance mode', 'group' => 'general'],
            'registration_enabled' => ['value' => true, 'type' => 'boolean', 'public' => true, 'label' => 'Registration enabled', 'group' => 'general'],
            'supported_formats' => ['value' => ['mp4', 'webm', 'mp3', 'm4a', 'jpg', 'png', 'webp'], 'type' => 'json', 'public' => true, 'label' => 'Supported formats', 'group' => 'limits'],
            'supported_platforms' => ['value' => ['youtube', 'vimeo', 'soundcloud', 'spotify', 'tiktok', 'instagram', 'dailymotion', 'twitter', 'pexels', 'pixabay'], 'type' => 'json', 'public' => true, 'label' => 'Supported platforms', 'group' => 'general'],
        ];

        foreach ($settings as $key => $payload) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'group' => $payload['group'],
                    'value' => $payload['value'],
                    'type' => $payload['type'],
                    'label' => $payload['label'],
                    'is_public' => $payload['public'],
                ]
            );
        }
    }
}
