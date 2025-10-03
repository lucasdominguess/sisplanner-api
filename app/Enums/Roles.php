<?php
namespace App\Enums;


enum Roles: int
{
    case ADMIN = 1;
    case USER = 2;
}

// public function getIdForName(string $name): int
// {
//     return match ($name) {
//         'admin' => Roles::ADMIN->value,
//         'user' => Roles::USER->value,
//         default => Roles::USER->value,
//     };
// }
