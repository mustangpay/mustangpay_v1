package com.mustangpay.api.demo.v1.webpayment.refund;

import com.mustangpay.api.constants.MustangpayApiConstantsV1;
import com.mustangpay.api.enums.CurrencyEnum;
import com.mustangpay.api.enums.OperationEnum;
import com.mustangpay.api.enums.PayMethodEnum;
import com.mustangpay.api.pojo.Amount;
import com.mustangpay.api.pojo.CreateCashierReq;
import com.mustangpay.api.pojo.CreateRefundReq;
import com.mustangpay.api.pojo.Product;
import com.mustangpay.api.utils.mustangpay.MustangpayApiUtilsV1;
import lombok.extern.slf4j.Slf4j;

import java.util.*;

/**
 * @Author: hyssop
 * @Date: 08/28/2024
 */
@Slf4j
public class RefundTest {
    public static void main(String[] args) {
        // Assuming the Amount and Product classes are defined elsewhere with constructors as needed
        Amount amount = new Amount(1000L, CurrencyEnum.ZAR.getCode()); // Assuming constructor accepts amount and currency code
        CreateRefundReq createCashierReq = new CreateRefundReq();
        String uniqueReference = UUID.randomUUID().toString();
        createCashierReq.setMerchantName("Merchant Name"); // Replace with actual merchant name if needed
        createCashierReq.setCountry("ZAF");
        createCashierReq.setAmount(amount);
        createCashierReq.setBusinessType("Refund");
        createCashierReq.setRemark("remark_83c200fa64ff");
        createCashierReq.setCallbackUrl("callback.url");
        createCashierReq.setMerchantOrderNo(uniqueReference);
        createCashierReq.setOriginalMerchantOrderNo("bf45e7b802ed4f3396a8acd9b5a3b2e1");
        createCashierReq.setMerchantId(MustangpayApiConstantsV1.merchantId);

        Map<String, Object> result =  MustangpayApiUtilsV1.callTest("RefundTest", createCashierReq, OperationEnum.RefundCreate.getCode());
        //{result={"code":"000000","data":{"cashierUrl":"http://110.238.76.97:90/pre-cashier?orderNo=qDsST5U5zPzdKre7N9ueEXLV7ECYTNw0HVM9v0a4Owc=","merchantId":"4449999220","orderNo":"2408281010032775693","orderStatus":"Initial","reference":"993eb0b4-cae7-49b6-a34c-6369317f08cb"},"msg":"ok","sign":"PJfGlnoNNWLUt9gHFptGIvpHm0uEEbHcHMdoc57vWYhp2VfmMUrbub3APsMqTEZ-UfggQPi7-GNWkWjgccjy486zTPiQgCdqmNNSZp_wiwA3x28ubx0HUuKs8q8uytLzWNVmrtn8DDK0xWMyJGoFMA2kcFAvONmoLwS56w-mt1sEb1MNBsFcq88V6V_168vIPWWXO3cO8Nk5hqbPaUs5Pgh6sSnbbJ4buQy-Tdr_utzLY4WWO3BQ7Amrm2teHSC8zkn5u00_9MwMUY4kwRwUMMGR8P0XCvYrAYVuJbDNKshgEHwi-uEmXH0gzu4AEcDdoL7SqTZo9qDmMKLDsoMKUw"}, code=S}
        log.info("RefundTest result ->{}", result);
    }
}
