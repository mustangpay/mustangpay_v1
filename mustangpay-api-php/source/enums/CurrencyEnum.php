<?php

namespace app\enums;

class CurrencyEnum
{
    const ZAR = 'ZAR';
    const ZAF = 'ZAF';
    const RAND = 'Rand';

    private static $currencies = [
        self::ZAR => ['countryCode' => self::ZAF, 'desc' => self::RAND],
    ];

    /**
     * Get currency information by code
     *
     * @param string $code
     * @return array
     * @throws \InvalidArgumentException
     */
    public static function getByCode($code)
    {
        if (isset(self::$currencies[$code])) {
            return self::$currencies[$code];
        }

        throw new \InvalidArgumentException("CurrencyEnum: getByCode($code) does not exist.");
    }

    /**
     * Get the description of a currency by its code
     *
     * @param string $code
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function getDescription($code)
    {
        $currency = self::getByCode($code);
        return $currency['desc'];
    }

    /**
     * Get the country code for a currency
     *
     * @param string $code
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function getCountryCode($code)
    {
        $currency = self::getByCode($code);
        return $currency['countryCode'];
    }
}
