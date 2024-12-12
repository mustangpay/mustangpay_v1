<?php

namespace app\models;

/**
 * Interface BaseResultEnum
 * @package app\enums
 * Basic response enumeration
 */
interface BaseResultEnum
{
    /**
     * Get the response code
     *
     * @return string
     */
    public function getCode();

    /**
     * Get the response message
     *
     * @return string
     */
    public function getMessage();
}
