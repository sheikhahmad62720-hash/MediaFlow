<?php

namespace App\Models;

use App\Enums\DownloadStatus;
use App\Enums\MediaFormat;
use App\Enums\MediaType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class Download extends Model
{
    use HasEvents, HasFactory, SoftDeletes;

    protected $table = 'downloads';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'status' => DownloadStatus::class,
            'media_type' => MediaType::class,
            'format' => MediaFormat::class,
            'metadata' => 'array',
            'processed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(SupportedPlatform::class, 'platform_id');
    }

    /**
     * Full URL to the file, if stored. Signed so the endpoint stays
     * protected against replay / enumeration.
     */
    protected function downloadUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->file_path && $this->status->isTerminal() && $this->status->value === 'completed'
                ? URL::signedRoute('api.downloads.file', $this->id)
                : null,
        );
    }

    protected function humanReadableSize(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->file_size
                ? Number::fileSize($this->file_size, 2)
                : null,
        );
    }

    /**
     * Generate a UUID for a new download.
     */
    public static function generateId(): string
    {
        return (string) Str::uuid();
    }

    public function markAsCompleted(string $path, string $name): void
    {
        $this->forceFill([
            'status' => DownloadStatus::COMPLETED,
            'file_path' => $path,
            'file_name' => $name,
            'processed_at' => now(),
        ])->save();
    }

    public function markAsFailed(string $reason): void
    {
        $this->forceFill([
            'status' => DownloadStatus::FAILED,
            'error_message' => $reason,
            'processed_at' => now(),
        ])->save();
    }
}
