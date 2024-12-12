package com.mustangpay.api.impl.pro;

import com.mustangpay.api.utils.mustangpay.RSAUtils;

/**
 * @Author: hyssop
 * @Date: 09/18/2024
 */
public class ProKeyConfigV1 implements ProKeyConfig{
    @Override
    public String getMustangPayPublicKey() {
        try{
            return  RSAUtils.getKeyPem(getClass().getClassLoader().getResource("").getPath()+"mustangpay/pro/mustangpay.pro.pub.key");
        }catch (Exception e){
            return null;
        }
    }

    @Override
    public String getMustangPayPrivateKey() {
        try{
            return  RSAUtils.getKeyPem(getClass().getClassLoader().getResource("").getPath()+"mustangpay/pro/mustangpay.pro.pri.key");
        }catch (Exception e){
            return null;
        }
    }

    @Override
    public String getMerchantPublicKey() {
        try{
            return  RSAUtils.getKeyPem(getClass().getClassLoader().getResource("").getPath()+"mustangpay/pro/merchant.pro.pub.key");
        }catch (Exception e){
            return null;
        }
    }

    @Override
    public String getMerchantPrivateKey() {
        try{
            return  RSAUtils.getKeyPem(getClass().getClassLoader().getResource("").getPath()+"mustangpay/pro/merchant.pro.pri.key");
        }catch (Exception e){
            return null;
        }
    }
}
