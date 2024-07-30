<?php

namespace App\Enum;

enum UserRole: int
{
    case ADMIN = 1;
    case AGENT = 2;
    case USER = 3;

    public static function fromId(int $id): ?self
    {
        return array_search($id, array_column(self::cases(), 'value'), true) ?: null;
    }
}
