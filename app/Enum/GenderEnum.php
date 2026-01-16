<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum GenderEnum : int
{
    use EnumValues, EnumOptions;

    case MALE = 1;
    case FEMALE = 2;

    public function getName(): string
    {
        return match ($this) {
            self::MALE => __('Male'),
            self::FEMALE => __('Female'),
        };
    }
}
