<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Download;
use App\Models\SupportedPlatform;
use App\Models\User;
use Illuminate\Database\Seeder;

class DevelopmentDataSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local', 'testing')) {
            return;
        }

        User::factory(5)->create();

        SupportedPlatform::factory(4)->create();
        Download::factory(40)->create();
        ActivityLog::factory(60)->create();
    }
}
