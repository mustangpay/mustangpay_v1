package com.mustangpay.api.demo.v1.webpayment.refund;

import com.mustangpay.api.constants.MustangpayApiConstantsV1;
import com.mustangpay.api.enums.CurrencyEnum;
import com.mustangpay.api.enums.OperationEnum;
import com.mustangpay.api.pojo.Amount;
import com.mustangpay.api.pojo.CreateRefundReq;
import com.mustangpay.api.pojo.RefundStatusReq;
import com.mustangpay.api.utils.mustangpay.MustangpayApiUtilsV1;
import lombok.extern.slf4j.Slf4j;

import java.util.Map;
import java.util.UUID;
@Slf4j
public class RefundQueryTest {
    public static void main(String[] args) {
        // Assuming the Amount and Product classes are defined elsewhere with constructors as needed
        Amount amount = new Amount(1000L, CurrencyEnum.ZAR.getCode()); // Assuming constructor accepts amount and currency code
        RefundStatusReq createCashierReq = new RefundStatusReq();
        createCashierReq.setMerchantOrderNo("a8f03d54-22ce-4093-aa2d-17d74932b5f2");
        createCashierReq.setMerchantId("4449999220");
        createCashierReq.setMerchantId(MustangpayApiConstantsV1.merchantId);

        Map<String, Object> result =  MustangpayApiUtilsV1.callTest("RefundQueryTest", createCashierReq, OperationEnum.RefundQuery.getCode());
        //{result={"code":"000000","data":{"cashierUrl":"http://110.238.76.97:90/pre-cashier?orderNo=qDsST5U5zPzdKre7N9ueEXLV7ECYTNw0HVM9v0a4Owc=","merchantId":"4449999220","orderNo":"2408281010032775693","orderStatus":"Initial","reference":"993eb0b4-cae7-49b6-a34c-6369317f08cb"},"msg":"ok","sign":"PJfGlnoNNWLUt9gHFptGIvpHm0uEEbHcHMdoc57vWYhp2VfmMUrbub3APsMqTEZ-UfggQPi7-GNWkWjgccjy486zTPiQgCdqmNNSZp_wiwA3x28ubx0HUuKs8q8uytLzWNVmrtn8DDK0xWMyJGoFMA2kcFAvONmoLwS56w-mt1sEb1MNBsFcq88V6V_168vIPWWXO3cO8Nk5hqbPaUs5Pgh6sSnbbJ4buQy-Tdr_utzLY4WWO3BQ7Amrm2teHSC8zkn5u00_9MwMUY4kwRwUMMGR8P0XCvYrAYVuJbDNKshgEHwi-uEmXH0gzu4AEcDdoL7SqTZo9qDmMKLDsoMKUw"}, code=S}
        log.info("RefundQueryTest result ->{}", result);
    }
}
