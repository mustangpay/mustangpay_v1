package com.mustangpay.api.demo.v1.h2h.createkey;


import lombok.extern.slf4j.Slf4j;

import java.security.KeyPair;
import java.security.KeyPairGenerator;
import java.security.PrivateKey;
import java.security.PublicKey;
import java.util.Base64;

/**
 * @Author: hyssop
 * @Date: 08/28/2024
 * 该类主要是方便商户获取一个自己的公私钥，公钥提供给mustangpay，私钥用于商户自己解密mustangpay返回的加密数据：验签和解密
 * !!! 发送公钥到邮箱  张杰 <hyssop.zhang@mustangcash.com>
 *     ！！！ 注明公司名称，公钥请用附件传输
 */
@Slf4j
public class MerchantRsaKeyCreateTest {
    public static void main(String[] args) throws Exception {
        // 初始化密钥生成器对象，指定密钥长度为2048位
        KeyPairGenerator kpg = KeyPairGenerator.getInstance("RSA");
        kpg.initialize(2048);

        // 生成密钥对
        KeyPair keyPair = kpg.generateKeyPair();

        // 获取公钥
        PublicKey publicKey = keyPair.getPublic();
        // 获取私钥
        PrivateKey privateKey = keyPair.getPrivate();

        // 将公钥和私钥转换为字符串形式
        String publicKeyString = Base64.getEncoder().encodeToString(publicKey.getEncoded());
        String privateKeyString = Base64.getEncoder().encodeToString(privateKey.getEncoded());

        log.info("Public Key:{}", publicKeyString);
        log.info("Private Key:{}", privateKeyString);

    }
}
