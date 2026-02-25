<?php

namespace App\Enum;

enum CategoryStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'Success',
            self::INACTIVE => 'Danger',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}