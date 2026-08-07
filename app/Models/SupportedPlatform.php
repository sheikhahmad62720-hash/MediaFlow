<?php

namespace App\Models;

use App\Enums\MediaFormat;
use App\Enums\PlatformCategory;
use Database\Factories\SupportedPlatformFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SupportedPlatform extends Model
{
    /** @use HasFactory<SupportedPlatformFactory> */
    use HasFactory;

    protected $table = 'supported_platforms';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'formats' => 'array',
            'is_active' => 'boolean',
            'category' => PlatformCategory::class,
            'analyzer' => 'string',
            'visit_count' => 'integer',
            'download_count' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(Download::class, 'platform_id');
    }

    public function matchesDomain(string $host): bool
    {
        $host = trim($host, '.');

        return $this->domain === $host
            || Str::endsWith($host, '.'.$this->domain);
    }

    /**
     * @return array<int, MediaFormat>
     */
    public function providedFormats(): array
    {
        if ($this->formats === null) {
            return [MediaFormat::MP4, MediaFormat::MP3];
        }

        return array_map(
            static fn (string $format) => MediaFormat::from($format),
            $this->formats
        );
    }

    public function supportsFormat(MediaFormat $format): bool
    {
        return in_array($format, $this->providedFormats(), true);
    }
}
