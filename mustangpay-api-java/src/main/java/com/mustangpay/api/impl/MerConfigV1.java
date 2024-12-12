package com.mustangpay.api.impl;

import com.mustangpay.api.constants.MustangpayApiConstantsV1;

/**
 * @Author: hyssop
 * @Date: 08/27/2024
 */
public class MerConfigV1 implements KeyConfig {

    @Override
    public String getRsaPrivateKeyPath() {
        try{
            return getClass().getClassLoader().getResource("").getPath()+ "mustangpay/order/test/4449999220-test.pri.key";
        }catch (Exception e){
            return null;
        }
    }

    @Override
    public String getMustangPayPublicKeyPath() {
        try{
            return getClass().getClassLoader().getResource("").getPath()+ "mustangpay/order/test/mustangpay-test.pub.key";
        }catch (Exception e){
            return null;
        }
    }

    @Override
    public String getRsaPublicKeyPath() {
        try{
            return getClass().getClassLoader().getResource("").getPath()+ "mustangpay/order/test/4449999220-test.pub.key";
        }catch (Exception e){
            return null;
        }
    }

    @Override
    public String getMustangPayApiUrl(String name) {
        if(name.equals("preCreate")){
            return MustangpayApiConstantsV1.preCreateUrl;
        }
        if(name.equals("checkOrder")){
            return MustangpayApiConstantsV1.checkOrderUrl;
        }
        if(name.equals("h2hPreCreate")){
            return MustangpayApiConstantsV1.h2hPreCreateUrl;
        }
     return null;

    }

    @Override
    public String getVersion() {
        return "1.0";
    }

    @Override
    public String geTestMustangPayApiUrl(String name) {
        if(name.equals("preCreate")){
            return MustangpayApiConstantsV1.testPreCreateUrl;
        }
        if(name.equals("checkOrder")){
            return MustangpayApiConstantsV1.testCheckOrderUrl;
        }
        if(name.equals("h2hPreCreate")){
            return MustangpayApiConstantsV1.testH2hPreCreateUrl;
        }
        if(name.equals("refundCreate")){
            return MustangpayApiConstantsV1.testRefundCreateUrl;
        }
        if(name.equals("refundQuery")){
            return MustangpayApiConstantsV1.testRefundQueryUrl;
        }
        return null;
    }

    @Override
    public String getProRsaPublicKeyPath() {
        try{
            return getClass().getClassLoader().getResource("").getPath()+ "mustangpay/order/pro/4449999220.pub.key";
        }catch (Exception e){
            return null;
        }
    }

    @Override
    public String getProRsaPrivateKeyPath() {
        try{
            return getClass().getClassLoader().getResource("").getPath()+ "mustangpay/order/pro/4449999220.pri.key";
        }catch (Exception e){
            return null;
        }
    }

    @Override
    public String getProMustangPayPublicKeyPath() {
        try{
            return getClass().getClassLoader().getResource("").getPath()+ "mustangpay/order/pro/mustangpay.pub.key";
        }catch (Exception e){
            return null;
        }
    }
}
