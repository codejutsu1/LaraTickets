<?php

namespace App\Enum;

enum UserRole: int
{
    case ADMIN = 1;
    case AGENT = 2;
    case USER = 3;

    public static function fromId(int $id): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $id) {
                return $case;
            }
        }
        
        return null;
    }
}
