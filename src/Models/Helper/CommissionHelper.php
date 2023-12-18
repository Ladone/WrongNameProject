<?php

declare(strict_types=1);

namespace App\Models\Helper;

class CommissionHelper
{
    public const FOR_EU_COMMISSION = 0.01;
    public const FOR_NON_EU_COMMISSION = 0.02;

    public const COUNTRY_CODES = [
        'AT', 'BE', 'BG', 'CY', 'CZ',
        'DE', 'DK', 'EE', 'ES', 'FI',
        'FR', 'GR', 'HR', 'HU', 'IE',
        'IT', 'LT', 'LU', 'LV', 'MT',
        'NL', 'PO', 'PT', 'RO', 'SE',
        'SI', 'SK',
    ];

    public static function getRate(?string $countryCode): float
    {
        return $countryCode === null || !self::isEuCountry($countryCode)
            ? self::FOR_NON_EU_COMMISSION : self::FOR_EU_COMMISSION;
    }

    public static function isEuCountry(string $countryCode): bool
    {
        return in_array(strtoupper($countryCode), self::COUNTRY_CODES);
    }
}
