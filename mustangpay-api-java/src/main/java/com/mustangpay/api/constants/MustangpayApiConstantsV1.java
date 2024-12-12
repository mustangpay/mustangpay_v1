package com.mustangpay.api.constants;
/**
 * @Author: hyssop
 * @Date: 08/31/2024
 */
public interface MustangpayApiConstantsV1 {

  //  String baseUrl = "https://openapi-dev.mstpay-inc.com";
  String baseUrl="https://openapi.mustangpay.co.za";
  String testBaseUrl="https://openapi-dev.mstpay-inc.com";
  //String testBaseUrl="https://openapi-test.mstpay-inc.com";
    String merchantId = "4449999220";
    String merchentV2="21251000800976235";
    String merchantReturnUrl = baseUrl + "/interface/ykMerApi/merchantReturnUrl";
    String preCreateUrl = baseUrl + "/openApi/merchant_direct/cashier/preorder";
    String checkOrderUrl = baseUrl + "/openApi/merchant_direct/cashier/getOrderStatusByMerchantOrderNo";
    String h2hPreCreateUrl = baseUrl + "/openApi/merchant_h2h/preorder";
  String testPreCreateUrl = testBaseUrl + "/openApi/merchant_direct/cashier/preorder";
  String testCheckOrderUrl = testBaseUrl + "/openApi/merchant_direct/cashier/getOrderStatusByMerchantOrderNo";
  String testH2hPreCreateUrl = testBaseUrl + "/openApi/merchant_h2h/preorder";
  String testRefundCreateUrl = testBaseUrl + "/openApi/refund/createRefund";
  String testRefundQueryUrl = testBaseUrl + "/openApi/refund/refundStatus";

}
