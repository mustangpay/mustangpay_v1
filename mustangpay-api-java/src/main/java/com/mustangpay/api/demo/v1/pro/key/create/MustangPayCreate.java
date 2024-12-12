package com.mustangpay.api.demo.v1.pro.key.create;

import com.mustangpay.api.impl.pro.ProKeyConfig;
import com.mustangpay.api.impl.pro.ProKeyConfigV1;
import com.mustangpay.api.utils.mustangpay.verifyKeyApiUtilsV1;

/**
 * @Author: hyssop
 * @Date: 09/18/2024
 */
public class MustangPayCreate {
    public static ProKeyConfig proKeyConfig = new ProKeyConfigV1();
    public static void main(String[] args) {
        verifyKeyApiUtilsV1.mustangPayCreateVerifyMessage(proKeyConfig.getMustangPayPrivateKey(),proKeyConfig.getMerchantPublicKey());

    }
}
