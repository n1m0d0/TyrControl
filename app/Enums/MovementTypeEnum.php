<?php

namespace App\Enums;

enum MovementTypeEnum: string
{
    case ENTRY = 'entrada';
    case EXIT = 'salida';

    public function label(): string
    {
        return match ($this) {
            self::ENTRY => 'Entrada',
            self::EXIT => 'Salida',
        };
    }

    public static function random(): self
    {
        $values = self::cases();
        return $values[array_rand($values)];
    }
}
