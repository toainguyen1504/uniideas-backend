<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum IdeaStatus: string
{
    use EnumValues, EnumOptions;

    case PENDING  = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function getName(): string
    {
        return match ($this) {
            self::PENDING  => __('Pending'),
            self::APPROVED => __('Approved'),
            self::REJECTED => __('Rejected'),
        };
    }
}
