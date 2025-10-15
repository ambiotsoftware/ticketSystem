<?php

namespace App\Enums;

enum BillingCycleEnum: string
{
    case MONTHLY = 'monthly';
    case QUARTERLY = 'quarterly';
    case ANNUAL = 'annual';

    public function trans(): string
    {
        return match ($this) {
            self::MONTHLY => 'Mensual',
            self::QUARTERLY => 'Trimestral',
            self::ANNUAL => 'Anual',
        };
    }

    /**
     * Retorna un array asociativo con el valor y su traducción.
     *
     * @return array<string, string>
     */
    public static function pluck(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn(self $case) => [$case->value => $case->trans()])
            ->toArray();
    }

}
