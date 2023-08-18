<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TransactionType: string implements HasLabel
{
    case MONEY_IN = 'in';
    case MONEY_OUT = 'out';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::MONEY_IN => 'Money in',
            self::MONEY_OUT => 'Money out',
        };
    }
}
