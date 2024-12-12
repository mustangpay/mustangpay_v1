<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;

class Amount extends ActiveRecord
{
    const CURRENCY_ZAR = 'ZAR';  // Example for ZAR, you can define more currencies

    public $value;
    public $currency;

    // Constants for the various currency codes (you can add more as needed)
    const CURRENCY_CODES = [
        'ZAR' => 'South African Rand',
        // Add more currencies here
    ];

    // Constructor can initialize the value and currency
    public function __construct($value = null, $currency = self::CURRENCY_ZAR, $config = [])
    {
        parent::__construct($config);
        $this->value = $value;
        $this->currency = $currency;
    }

    /**
     * Check if the amount is greater than a given amount.
     *
     * @param Amount $amount
     * @return bool
     */
    public function isGreaterThan(Amount $amount)
    {
        return $this->value > $amount->value;
    }

    /**
     * Check if the amount is greater than or equal to a given amount.
     *
     * @param Amount $amount
     * @return bool
     */
    public function isGreaterOrEqualThan(Amount $amount)
    {
        return $this->value >= $amount->value;
    }

    /**
     * Check if the amount is equal to a given amount.
     *
     * @param Amount $amount
     * @return bool
     */
    public function isEqualTo(Amount $amount)
    {
        return $this->value == $amount->value;
    }

    /**
     * Check if the amount is less than a given amount.
     *
     * @param Amount $amount
     * @return bool
     */
    public function isLesserThan(Amount $amount)
    {
        return $this->value < $amount->value;
    }

    /**
     * Check if the amount is less than or equal to a given amount.
     *
     * @param Amount $amount
     * @return bool
     */
    public function isLesserOrEqualThan(Amount $amount)
    {
        return $this->value <= $amount->value;
    }

    /**
     * Check if the amount is positive.
     *
     * @return bool
     */
    public function checkPositive()
    {
        return $this->value > 0;
    }

    /**
     * Check if the amount is zero.
     *
     * @return bool
     */
    public function checkZero()
    {
        return $this->value == 0;
    }

    /**
     * Check if the amount is negative.
     *
     * @return bool
     */
    public function checkNegative()
    {
        return $this->value < 0;
    }

    /**
     * Add an amount to the current amount.
     *
     * @param Amount $amount
     * @return Amount
     */
    public function add(Amount $amount)
    {
        $this->value += $amount->value;
        return $this;
    }

    /**
     * Subtract an amount from the current amount.
     *
     * @param Amount $amount
     * @return Amount
     */
    public function subtract(Amount $amount)
    {
        $this->value -= $amount->value;
        return $this;
    }

    /**
     * Format the amount from cents to currency units.
     *
     * @param Amount $amount
     * @return string
     */
    public static function formatAmountFromCents(Amount $amount)
    {
        if ($amount === null || $amount->value === null) {
            throw new \InvalidArgumentException("Amount object and its value must not be null.");
        }

        $unitsValue = $amount->value / 100;
        return number_format($unitsValue, 2, '.', '');
    }

    /**
     * Get the value of an amount.
     *
     * @param Amount $amount
     * @return int|null
     */
    public static function getAmountValue(Amount $amount)
    {
        return $amount === null ? null : $amount->value;
    }

    /**
     * Create an amount instance.
     *
     * @param int $amount
     * @param string $currency
     * @return Amount
     */
    public static function getAmount($amount, $currency)
    {
        return new self($amount, $currency);
    }

    /**
     * Get the list of currency codes.
     *
     * @return array
     */
    public static function getCurrencyCodes()
    {
        return self::CURRENCY_CODES;
    }
}
