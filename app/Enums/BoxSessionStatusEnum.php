<?php

namespace App\Enums;

enum BoxSessionStatusEnum: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Abierta',
            self::CLOSED => 'Cerrada',
        };
    }

    public static function random(): self
    {
        $values = self::cases();
        return $values[array_rand($values)];
    }
}
