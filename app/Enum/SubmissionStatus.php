<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum SubmissionStatus: int
{
    use EnumValues, EnumOptions;

    case OPEN = 1;
    case CLOSED = 2;
    case FINALLY_CLOSED = 3;

    
    public function getName(): string
    {
        return match ($this) {
            self::OPEN => __('Open'),
            self::CLOSED => __('Closed'),
            self::FINALLY_CLOSED => __('Finally Closed'),
        };
    }
}
