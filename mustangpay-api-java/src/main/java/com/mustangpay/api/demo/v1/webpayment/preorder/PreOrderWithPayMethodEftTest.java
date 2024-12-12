package com.mustangpay.api.demo.v1.webpayment.preorder;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;
import com.alibaba.fastjson.TypeReference;
import com.mustangpay.api.constants.MustangpayApiConstantsV1;
import com.mustangpay.api.enums.CurrencyEnum;
import com.mustangpay.api.enums.OperationEnum;
import com.mustangpay.api.enums.PayMethodEnum;
import com.mustangpay.api.pojo.*;
import com.mustangpay.api.utils.mustangpay.MustangpayApiUtilsV1;
import lombok.extern.slf4j.Slf4j;

import javax.net.ssl.*;
import javax.security.cert.X509Certificate;
import java.security.KeyManagementException;
import java.security.NoSuchAlgorithmException;
import java.security.cert.CertificateException;
import java.util.*;

/**
 * @Author: hyssop
 * @Date: 08/28/2024
 */
@Slf4j
public class PreOrderWithPayMethodEftTest {

    public static void main(String[] args) {
        for(int i=0;i<10;i++){
            // Assuming the Amount and Product classes are defined elsewhere with constructors as needed
            Amount amount = new Amount(100L, CurrencyEnum.ZAR.getCode()); // Assuming constructor accepts amount and currency code
            Product product = new Product("productname", "short", "productDesc_b74f45d43c9c");
            // 调用 disableSslVerification 方法

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
            createCashierReq.setExpireAt(30000);
            createCashierReq.setVat(new Amount(10L, CurrencyEnum.ZAR.getCode())); // Assuming VAT is also of type Amount
            createCashierReq.setVatNumber("vatNumber_d98853c8c10c");
            createCashierReq.setMetadata(new HashMap<>());
            createCashierReq.setMerchantId(MustangpayApiConstantsV1.merchantId);
            createCashierReq.setPayMethods(Collections.singletonList(PayMethodEnum.INSTANT_EFT.getCode()));
            log.info("createCashierReq:{}",createCashierReq);
            Map<String, Object> result =  MustangpayApiUtilsV1.callMustangPayPreOrderApi("PreOrderWithPayMethodEftTest", createCashierReq, OperationEnum.PRECREATE.getCode());
            Result<CashierCreateResp> parsedResult = JSONObject.parseObject(
                    (String) result.get("result"),
                    new TypeReference<Result<CashierCreateResp>>(){});
            log.info("cashier"+parsedResult.getData().getCashierUrl());
            // PreOrderWithPayMethodEftTest result ->{result={"code":"000000","data":{"amount":{"currency":"ZAR","value":1000},"cashierUrl":"http://110.238.76.97:90/pre-cashier?orderNo=ozfJuCr0uHf6vrO_MYK4a1Y6an85EYg2cK0PK30f-b0=","errorCode":"","errorMessage":"","merchantId":"4449999220","orderNo":"2408291010032979473","orderStatus":"Pending","redirectPayParam":{"CHECKSUM":"468c4203f3e234d79321d8cdda14d237","PAYGATE_ID":"1039903103954","PAY_REQUEST_ID":"4B4054B1-17B0-460C-AB57-A0B394E27EEC","REFERENCE":"24082913203771"},"redirectPayUrl":"https://secure.paygate.co.za/PayHost/process.trans","reference":"a1299a0d-c79f-49f5-b3cf-a8659577a9f3"},"msg":"ok","sign":"YFXIqFUqo3IsaS0pX7g4WcUq53pLy3GPj_MavtDfqtnjlfINJyhf59h4Pg_s6EHRSgACJpPiQNxfEEhvl4jzXWb3RUDqtNyWdgT54kBmXiqUXyPT3m81xL3hRY2XOd0QSH0cZV-XvGDFOOlvfLWN9vltJCcbLc7cABwwchVcVOIBc-AoZkFZLTU6bRPozeZNjNg
            // stqfeohVZfeHrII4I27o-R-_fxff8ISwXt8xLcHWN4r99pAeE-ksV8OImVeN1v6-CPcAxx6IKhSfr-yXCZYatgkgFzY2F79dG5XtR4pRjBA04n_uhm2i1cTj1xgmq_LJrTwihFifZujoRf5xA8g"}, code=S}
            log.info("PreOrderWithPayMethodEftTest result ->{}", result);
        }



    }


}
