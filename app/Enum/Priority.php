<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;

enum Priority: string implements HasColor
{
    case Low = 'low';

    case Medium = 'medium';

    case High = 'high';

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Low => 'success',
            self::Medium => 'info',
            self::High => 'danger',
        };
    }

}
