<?php

namespace App\Enum;

enum IdeaFilter: string
{
    case POPULAR = 'popular';
    case VIEWED  = 'viewed';
    case LATEST  = 'latest';

    public function getName(): string
    {
        return match ($this) {
            self::POPULAR => __('Popular'),
            self::VIEWED  => __('Viewed'),
            self::LATEST  => __('Latest'),
        };
    }
}
