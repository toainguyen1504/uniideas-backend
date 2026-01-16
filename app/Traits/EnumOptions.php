<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait EnumOptions
{
    /**
     * Helper trait using for returning options
     */
    public static function options(bool $isObject = false): array
    {
        $cases = static::cases();
        $options = [];
        foreach ($cases as $case) {
            $label = $case->name;
            if (Str::contains($label, '_')) {
                $label = Str::replace('_', ' ', $label);
            }
            $options[] = $isObject ?
                (object) [
                    'value' => $case->value,
                    'label' => __(Str::title($label)),
                ]
                : [
                    'value' => $case->value,
                    'label' => __(Str::title($label)),
                ];
        }

        return $options;
    }
}
