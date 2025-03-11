<?php

namespace app\models;

/**
 * Common result enum class implementing BaseResultEnum
 * Provides response codes and messages
 */
class CommonResultEnum implements BaseResultEnum
{
    // Define constants for each result type
    const SUCCESS = '000000';
    const FAIL = '999999';
    const ERROR = '999998';
    const UNKNOWN = '999997';

    private $code;
    private $message;

    /**
     * CommonResultEnum constructor.
     * @param string $code
     * @param string $message
     */
    private function __construct($code, $message)
    {
        $this->code = $code;
        $this->message = $message;
    }

    /**
     * Get the response code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get the response message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Return the enum instance based on the code
     * @param string $code
     * @return CommonResultEnum|null
     */
    public static function getByCode($code)
    {
        switch ($code) {
            case self::SUCCESS:
                return new self(self::SUCCESS, 'success');
            case self::FAIL:
                return new self(self::FAIL, 'fail');
            case self::ERROR:
                return new self(self::ERROR, 'error');
            case self::UNKNOWN:
                return new self(self::UNKNOWN, 'unknown');
            default:
                return null;
        }
    }
}
