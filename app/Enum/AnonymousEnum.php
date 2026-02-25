<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum AnonymousEnum: int
{
    use EnumValues, EnumOptions;

    case ANONYMOUS     = 1;
    case NOT_ANONYMOUS = 2;

    public function getName(): string
    {
        return match ($this) {
            self::ANONYMOUS     => __('Anonymous'),
            self::NOT_ANONYMOUS => __('Not Anonymous'),
        };
    }
}
