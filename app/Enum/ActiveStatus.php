<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum ActiveStatus: int
{
    use EnumValues, EnumOptions;

    case ACTIVE = 1;
    case INACTIVE = 2;

    public static function getBadge( $statusValue){
        return match($statusValue) {
            self::ACTIVE->value => 'success',
            self::INACTIVE->value => 'danger',
            default => '',
        };
    }
}
