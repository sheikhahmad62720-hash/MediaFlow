<?php

namespace App\Enums;

enum PlatformCategory: string
{
    case VIDEO = 'video';
    case MUSIC = 'music';
    case SOCIAL = 'social';
    case IMAGE = 'image';
    case DIRECT = 'direct';

    public function label(): string
    {
        return match ($this) {
            self::VIDEO => 'Video',
            self::MUSIC => 'Music',
            self::SOCIAL => 'Social',
            self::IMAGE => 'Image',
            self::DIRECT => 'Direct host',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::VIDEO => 'video',
            self::MUSIC => 'music-note',
            self::SOCIAL => 'globe-alt',
            self::IMAGE => 'photo',
            self::DIRECT => 'link',
        };
    }
}
