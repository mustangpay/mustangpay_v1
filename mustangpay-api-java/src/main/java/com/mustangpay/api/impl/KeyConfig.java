package com.mustangpay.api.impl;
/**
 * @Author: hyssop
 * @Date: 08/27/2024
 */
public interface KeyConfig {

    String getRsaPublicKeyPath();

    String getRsaPrivateKeyPath();

    String getMustangPayPublicKeyPath();
    String getMustangPayApiUrl(String name);

    String getVersion();
    String geTestMustangPayApiUrl(String name);


    String getProRsaPublicKeyPath();

    String getProRsaPrivateKeyPath();

    String getProMustangPayPublicKeyPath();

}
