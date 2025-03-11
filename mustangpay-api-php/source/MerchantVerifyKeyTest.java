package com.mustangpay.api.demo.v1.pro.key.varify;

import com.mustangpay.api.impl.pro.ProKeyConfig;
import com.mustangpay.api.impl.pro.ProKeyConfigV1;
import com.mustangpay.api.utils.mustangpay.verifyKeyApiUtilsV1;

/**
 * @Author: hyssop
 * @Date: 09/18/2024
 */
public class MerchantVerifyKeyTest {
    public static ProKeyConfig proKeyConfig = new ProKeyConfigV1();

    public static void main(String[] args) {

        //This method verifies the message provided by mustangPay.
        verifyKeyApiUtilsV1.merchantVerifyMustangMessage(proKeyConfig.getMerchantPrivateKey(),proKeyConfig.getMustangPayPublicKey());
    }
}
