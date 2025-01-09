package com.mustangpay.api.demo.v1.webpayment.createkey;


import com.alibaba.fastjson.JSONObject;
import lombok.extern.slf4j.Slf4j;

import java.security.*;
import java.util.Base64;

/**
 * @Author: hyssop
 * @Date: 08/28/2024
 * This class is mainly designed to facilitate merchants in obtaining their own public and private keys. The public key is provided to mustangpay, while the private key is used by the merchant to decrypt the encrypted data returned by mustangpay: for signature verification and decryption.
 * !!! Send the public key to the email  Zhang Jie <hyssop.zhang@mustangpay.co.za>
 *     !!! Specify the company name, and please send the public key as an attachment.
 */
@Slf4j
public class MerchantRsaKeyCreateTest {
    public static void main(String[] args) throws Exception {
        // Initialize a key generator object, specifying the key length as 2048 bits.
        KeyPairGenerator kpg = KeyPairGenerator.getInstance("RSA");
        kpg.initialize(2048);
        JSONObject json = new JSONObject();
        // Iterate through the JSON.
        for (String key : json.keySet()) {
            log.info("key:{},value:{}", key, json.get(key));
        }
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
