<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum UserStatus: int
{
    use EnumValues, EnumOptions;

    case ACTIVE = 1;
    case DISABLED = 2;

    public function getName(): string
    {
        return match ($this) {
            self::ACTIVE => __('Active'),
            self::DISABLED => __('Disabled'),
        };
    }
}
