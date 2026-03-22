<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum IdeaStatus: int
{
    use EnumValues, EnumOptions;

    case PENDING  = 1;
    case APPROVED = 2;
    case REJECTED = 3;

    public function getName(): string
    {
        return match ($this) {
            self::PENDING  => __('Pending'),
            self::APPROVED => __('Approved'),
            self::REJECTED => __('Rejected'),
        };
    }
}
