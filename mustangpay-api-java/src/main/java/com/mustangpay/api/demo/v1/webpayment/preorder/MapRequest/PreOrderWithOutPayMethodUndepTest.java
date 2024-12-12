package com.mustangpay.api.demo.v1.webpayment.preorder.MapRequest;


import com.mustangpay.api.constants.MustangpayApiConstantsV1;
import com.mustangpay.api.enums.OperationEnum;
import com.mustangpay.api.utils.mustangpay.MustangpayApiUtilsV1;
import lombok.extern.slf4j.Slf4j;

import java.util.*;
import java.math.BigDecimal;
import java.math.RoundingMode;

/**
 * @Author: hyssop
 * @Date: 08/27/2024
 */
@Slf4j
public class PreOrderWithOutPayMethodUndepTest {
     public static void main(String[] args) {
          // Create a unique reference for each run
          String uniqueReference = UUID.randomUUID().toString();

          // Create TreeMap object for amount
          TreeMap<String, Object> amountParams = new TreeMap<>();
          amountParams.put("value", 1000L);
          amountParams.put("currency", "ZAR");

          // Create TreeMap object for product
          TreeMap<String, Object> productParams = new TreeMap<>();
          productParams.put("name", "productname");
          productParams.put("short", "short");
          productParams.put("description", "productDesc_b74f45d43c9c");

          // Create TreeMap object for request parameters
          TreeMap<String, Object> params = new TreeMap<>();
          params.put("merchantName", "Merchant Name"); // Replace with actual merchant name if needed
          params.put("country", "ZAF");
          params.put("currency", "ZAR");
          params.put("reference", uniqueReference); // Set unique reference
          params.put("amount", amountParams);
          params.put("businessType", "MerchantAcquiring");
          params.put("remark", "remark_83c200fa64ff");
          params.put("callbackUrl", "callbackUrl_08941d02454c");
          params.put("returnUrl", "returnUrl_86a75a09e6b8");
          params.put("cancelUrl", ""); // Cancel URL is an empty string as per the JSON data
          params.put("ip", "ip_2841df759b91");
          params.put("product", productParams);
          params.put("productList", new ArrayList<>()); // Assuming the product list is empty
          params.put("expireAt", 30);
          params.put("vat", amountParams); // Assuming VAT is also of type Amount
          params.put("vatNumber", "vatNumber_d98853c8c10c");
          params.put("metadata", new HashMap<>());
          params.put("merchantId", MustangpayApiConstantsV1.merchantId);

          Map<String, Object> result = MustangpayApiUtilsV1.callTest("PreOrderTest", params, OperationEnum.PRECREATE.getCode());
          log.info("PreOrderTest result ->{}", result);
     }
}