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
        // Initialize a key generator object, specifying the key length as 2048 bits.
        KeyPairGenerator kpg = KeyPairGenerator.getInstance("RSA");
        kpg.initialize(2048);

        // Generate a key pair.
        KeyPair keyPair = kpg.generateKeyPair();

        // Get the public key.
        PublicKey publicKey = keyPair.getPublic();
        // Get the private key.
        PrivateKey privateKey = keyPair.getPrivate();

        // Convert the public key and private key to string format.
        String publicKeyString = Base64.getEncoder().encodeToString(publicKey.getEncoded());
        String privateKeyString = Base64.getEncoder().encodeToString(privateKey.getEncoded());

        log.info("Public Key:{}", publicKeyString);
        log.info("Private Key:{}", privateKeyString);

    }
}
