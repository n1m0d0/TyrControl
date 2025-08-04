<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMINISTRATOR = 'administrator';
    case SUPERVISOR = 'supervisor';
    case SELLER = 'seller';
    case CHECKER = 'checker';

    public function label(): string
    {
        return match($this) {
            self::ADMINISTRATOR => 'Administrador',
            self::SUPERVISOR => 'Supervisor',
            self::SELLER => 'Vendedor',
            self::CHECKER => 'Verificador',
        };
    }
}