<?php

namespace app\enums;

class PayMethodEnum
{
    const CARD_PAYMENT = 'CardPayment';
    const INSTANT_EFT = 'InstantEFT';
    const OFFLINE_TRANSFER = 'OfflineTransfer';
    const BALANCE = 'Balance';

    private static $descriptions = [
        self::CARD_PAYMENT => 'Card Payment',
        self::INSTANT_EFT => 'Instant EFT',
        self::OFFLINE_TRANSFER => 'Offline Transfer',
        self::BALANCE => 'Balance',
    ];

    // Check if the code exists in the enum
    public static function contains($code)
    {
        return in_array($code, self::getAll(), true);
    }

    // Check if all codes exist in the enum
    public static function containsAll(array $codes)
    {
        foreach ($codes as $code) {
            if (!self::contains($code)) {
                return false;
            }
        }
        return true;
    }

    // Get description by type/code
    public static function getNameByType($type)
    {
        return self::$descriptions[$type] ?? null;
    }

    // Get all values of the enum (codes)
    public static function getAll()
    {
        return [
            self::CARD_PAYMENT,
            self::INSTANT_EFT,
            self::OFFLINE_TRANSFER,
            self::BALANCE,
        ];
    }
}
