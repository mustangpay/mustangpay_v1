<?php
/**
 * Create By: zsz
 * File: OperationEnum.php
 * Time: 2024/12/2 14:32
 */

namespace app\enums;

class OperationEnum
{
    const PRECREATE = 'preCreate';
    const CHECKORDER = 'checkOrder';
    const H2H_PRECREATE = 'h2hPreCreate';
    const REFUND_CREATE = 'refundCreate';
    const REFUND_QUERY = 'refundQuery';

    // all operation
    public static function list()
    {
        return [
            self::PRECREATE => '商户预下单',
            self::CHECKORDER => '商户查询订单状态',
            self::H2H_PRECREATE => '商户h2h预下单',
            self::REFUND_CREATE => '商户退款',
            self::REFUND_QUERY => '商户退款查询',
        ];
    }

    // Descriptions for each operation
    public static function getDescription($code)
    {
        $list = self::list();

        return isset($list[$code]) ? $list[$code] : null;
    }

    public static function getOperationCode($operationName)
    {
        $operationCode = null;
        switch ($operationName) {
            case self::PRECREATE:
                $operationCode = 'preCreate';
                break;
            case self::CHECKORDER:
                $operationCode = 'checkOrder';
                break;
            case self::H2H_PRECREATE:
                $operationCode = 'h2hPreCreate';
                break;
            case self::REFUND_CREATE:
                $operationCode = 'refundCreate';
                break;
           case self::REFUND_QUERY:
        }
    }
}
