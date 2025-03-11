<?php

namespace app\constants;

/**
 * @Author: hyssop
 * @Date: 08/31/2024
 */
class MustangpayApiConstantsV1
{
    // Base URLs
    const BASE_URL = 'https://openapi.mustangpay.co.za';
    const TEST_BASE_URL = 'https://openapi-dev.mstpay-inc.com';

    // Merchant details
    const MERCHANT_ID = '4449999220';
    const MERCHANT_RETURN_URL = self::BASE_URL . '/interface/ykMerApi/merchantReturnUrl';
    const PRE_CREATE_URL = self::BASE_URL . '/openApi/merchant_direct/cashier/preorder';
    const CHECK_ORDER_URL = self::BASE_URL . '/openApi/merchant_direct/cashier/getOrderStatusByMerchantOrderNo';
    const H2H_PRE_CREATE_URL = self::BASE_URL . '/openApi/merchant_h2h/preorder';

    // Test URLs
    const TEST_PRE_CREATE_URL = self::TEST_BASE_URL . '/openApi/merchant_direct/cashier/preorder';
    const TEST_CHECK_ORDER_URL = self::TEST_BASE_URL . '/openApi/merchant_direct/cashier/getOrderStatusByMerchantOrderNo';
    const TEST_H2H_PRE_CREATE_URL = self::TEST_BASE_URL . '/openApi/merchant_h2h/preorder';
    //String testRefundCreateUrl = testBaseUrl + "/openApi/refund/createRefund";
    //String testRefundQueryUrl = testBaseUrl + "/openApi/refund/refundStatus";
    const TEST_REFUND_CREATE_URL = self::TEST_BASE_URL . '/openApi/refund/createRefund';
    const TEST_REFUND_QUERY_URL = self::TEST_BASE_URL . '/openApi/refund/refundStatus';

    public static function geTestMustangPayApiUrl($jumpKey)
    {
        if ($jumpKey == 'preCreate') {
            return self::TEST_PRE_CREATE_URL;
        }
        if ($jumpKey == 'checkOrder') {
            return self::TEST_CHECK_ORDER_URL;
        }
        if ($jumpKey == 'h2hPreCreate') {
            return self::TEST_H2H_PRE_CREATE_URL;
        }
        if ($jumpKey == 'refundCreate') {
            return self::TEST_REFUND_CREATE_URL;
        }
        if ($jumpKey == 'RefundQuery') {
            return self::TEST_REFUND_QUERY_URL;
        }
        return null;
    }
}
