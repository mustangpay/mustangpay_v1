package com.mustangpay.api.demo.v1.pro.key.varify;

import com.mustangpay.api.impl.pro.ProKeyConfig;
import com.mustangpay.api.impl.pro.ProKeyConfigV1;
import com.mustangpay.api.utils.mustangpay.verifyKeyApiUtilsV1;

/**
 * @Author: hyssop
 * @Date: 09/18/2024
 */
public class MustangPayVerifyKeyTest {

    public static ProKeyConfig proKeyConfig = new ProKeyConfigV1();
    public static void main(String[] args) {

        verifyKeyApiUtilsV1.mustangPayVerifyMustangMessage(proKeyConfig.getMustangPayPrivateKey(),proKeyConfig.getMerchantPublicKey());

        /*  VerifyKeyApiUtilsV1.mustangPayVerifyMerchantMessage()
        VerifyKeyApiUtilsV1.merchantCreateVerifyMessage(proKeyConfig.getMerchantPrivateKey(),proKeyConfig.getMustangPayPublicKey());
        VerifyKeyApiUtilsV1.merchantVerifyMustangMessage();*/

    //    VerifyKeyApiUtilsV1.merchantVerify(proKeyConfig.getMerchantPrivateKey(), proKeyConfig.getMerchantPublicKey(),proKeyConfig.getMustangPayPublicKey());

    }

}
