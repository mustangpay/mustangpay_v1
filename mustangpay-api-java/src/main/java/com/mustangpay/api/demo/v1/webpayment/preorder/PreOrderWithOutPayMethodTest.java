package com.mustangpay.api.demo.v1.webpayment.preorder;

import com.mustangpay.api.constants.MustangpayApiConstantsV1;
import com.mustangpay.api.enums.CurrencyEnum;
import com.mustangpay.api.enums.OperationEnum;
import com.mustangpay.api.pojo.Amount;
import com.mustangpay.api.pojo.CreateCashierReq;
import com.mustangpay.api.pojo.Product;
import com.mustangpay.api.utils.mustangpay.MustangpayApiUtilsV1;
import lombok.extern.slf4j.Slf4j;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import java.util.UUID;
/**
 * @Author: hyssop
 * @Date: 08/27/2024
 */
@Slf4j
public class PreOrderWithOutPayMethodTest {
    public static void main(String[] args) {
        // Assuming the Amount and Product classes are defined elsewhere with constructors as needed
        Amount amount = new Amount(1000L, CurrencyEnum.ZAR.getCode()); // Assuming constructor accepts amount and currency code
        Product product = new Product("productname", "short", "productDesc_b74f45d43c9c");

        // Create a unique reference for each run
        String uniqueReference = UUID.randomUUID().toString();

        // Create CreateCashierReq object
        CreateCashierReq createCashierReq = new CreateCashierReq();
        createCashierReq.setMerchantName("Merchant Name"); // Replace with actual merchant name if needed
        createCashierReq.setCountry("ZAF");
        createCashierReq.setCurrency("ZAR");
        createCashierReq.setReference(uniqueReference); // Set unique reference
        createCashierReq.setAmount(amount);
        createCashierReq.setBusinessType("MerchantAcquiring");
        createCashierReq.setRemark("remark_83c200fa64ff");
        createCashierReq.setCallbackUrl("callbackUrl_08941d02454c");
        createCashierReq.setReturnUrl("returnUrl_86a75a09e6b8");
        createCashierReq.setCancelUrl(""); // Cancel URL is an empty string as per the JSON data
        createCashierReq.setIp("ip_2841df759b91");
        createCashierReq.setProduct(product);
        createCashierReq.setProductList(new ArrayList<>()); // Assuming the product list is empty
        createCashierReq.setExpireAt(30);
        createCashierReq.setVat(new Amount(10L, CurrencyEnum.ZAR.getCode())); // Assuming VAT is also of type Amount
        createCashierReq.setVatNumber("vatNumber_d98853c8c10c");
        createCashierReq.setMetadata(new HashMap<>());
        createCashierReq.setMerchantId(MustangpayApiConstantsV1.merchantId);
        Map<String, Object> result =  MustangpayApiUtilsV1.callTest("PreOrderTest", createCashierReq, OperationEnum.PRECREATE.getCode());
        log.info("PreOrderTest result ->{}", result);

    }
}
