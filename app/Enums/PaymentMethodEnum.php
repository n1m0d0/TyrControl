<?php

namespace App\Enums;

enum PaymentMethodEnum: string
{
    case CASH = 'cash';
    case CARD = 'card';
    case QR = 'qr';

    public function label(): string
    {
        return match ($this) {
            self::CASH => 'Efectivo',
            self::CARD => 'Tarjeta',
            self::QR => 'QR',
        };
    }

    public static function random(): self
    {
        $values = self::cases();
        return $values[array_rand($values)];
    }
}
