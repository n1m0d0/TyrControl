<?php

namespace App\Enums;

enum DocumentTypeEnum: string
{
    case CI = 'ci';
    case NIT = 'nit';

    public function label(): string
    {
        return match ($this) {
            self::CI => 'CI',
            self::NIT => 'NIT',
        };
    }

    public static function random(): self
    {
        $values = self::cases();
        return $values[array_rand($values)];
    }
}
