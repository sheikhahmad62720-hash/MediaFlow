<?php

namespace App\Enums;

enum MediaType: string
{
    case VIDEO = 'video';
    case AUDIO = 'audio';
    case IMAGE = 'image';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
