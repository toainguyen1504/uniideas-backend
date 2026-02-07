<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum ReactEnum: int
{
    use EnumValues, EnumOptions;

    case UNKNOWN = 0;
    case LIKE = 1;
    case DISLIKE = 2;

    public function getName(): string
    {
        return match ($this) {
            self::UNKNOWN => __('Unknown'),
            self::LIKE => __('Like'),
            self::DISLIKE => __('Dislike'),
        };
    }
}
