package com.mustangpay.api.utils.mustangpay;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;
import com.alibaba.fastjson.serializer.SerializerFeature;
import com.mustangpay.api.constants.MustangpayApiConstantsV1;
import com.mustangpay.api.enums.CurrencyEnum;
import com.mustangpay.api.enums.PayMethodEnum;
import com.mustangpay.api.pojo.Amount;
import com.mustangpay.api.pojo.CreateCashierReq;
import com.mustangpay.api.pojo.GatewayEncryptReq;
import com.mustangpay.api.pojo.Product;
import lombok.extern.slf4j.Slf4j;
import org.apache.commons.lang3.StringUtils;
import org.springframework.core.io.ResourceLoader;

import javax.crypto.NoSuchPaddingException;
import java.net.URL;
import java.security.InvalidKeyException;
import java.security.NoSuchAlgorithmException;
import java.security.SignatureException;
import java.security.spec.InvalidKeySpecException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.UUID;

/**
 * @Author: hyssop
 * @Date: 09/18/2024
 */
@Slf4j
public abstract class AbstractBaseKeyApiUtils {
    //加签名
    public static GatewayEncryptReq encryptToObject(String srcBody, String privateKey, String thirdPartPublicKey) {

        if (StringUtils.isBlank(srcBody)) {
            log.error("request encrypt error, app public key error");
        }

        JSONObject jsonObject = JSONObject.parseObject(srcBody);


        String originalParamJsonStr = JSON.toJSONString(jsonObject, SerializerFeature.MapSortField);

        String sign = null;
        try {
            //merchant sign with merchant private key.
            sign = RSAUtils.sign(originalParamJsonStr, privateKey);
        } catch (NoSuchAlgorithmException | SignatureException | InvalidKeyException | InvalidKeySpecException e) {
            log.error("response RSA sign error：{}", e.getMessage());
        } catch (Exception e) {
            throw new RuntimeException(e);
        }

        //The generated signature 'sign' is added to the original parameters.
        jsonObject.put("sign", sign);

        //Convert the parameters with the appended signature to a JSON string.
        String originalParamSignJsonStr = JSON.toJSONString(jsonObject, SerializerFeature.MapSortField);

        // Parameters are encrypted with AES after being signed.
        //Randomly generate an AES password.
        String aesKey = UUID.randomUUID().toString();
        log.info("aesKey:{}", aesKey);
        //The parameters are encrypted with AES after being signed.
        String encryptData = AESUtil.encrypt(originalParamSignJsonStr, aesKey);

        // The random AES password is encrypted using RSA.
        String encryptKey = null;
        try {
            encryptKey = RSAUtils.publicEncrypt(aesKey, thirdPartPublicKey);
        } catch (NoSuchAlgorithmException | InvalidKeyException | NoSuchPaddingException | InvalidKeySpecException e) {
            log.error("response RSA Encryption failed：{}", e.getMessage());
        }
        // Finally generate the parameters after encryption.
        GatewayEncryptReq getewayParamEncryptReq = new GatewayEncryptReq();
        getewayParamEncryptReq.setEncryptKey(encryptKey);
        getewayParamEncryptReq.setEncryptData(encryptData);
        getewayParamEncryptReq.setMerchantId(MustangpayApiConstantsV1.merchantId);
        return getewayParamEncryptReq;
    }

    //验签
    public static String decrypt(String ownPrivateKey, String thirdPartPublicKey, GatewayEncryptReq body) {
        long parseStartTime = System.nanoTime();
        String merchantId = body.getMerchantId();
        if (StringUtils.isBlank(merchantId)) {
        }
        // encrypt the body
        String toEncryptBodyStr = JSON.toJSONString(body, SerializerFeature.MapSortField);
        long beginEncryptNano = System.nanoTime();
        JSONObject toEncryptBody = JSON.parseObject(toEncryptBodyStr);

        String encryptKey = toEncryptBody.getString("encryptKey");
        String encryptData = toEncryptBody.getString("encryptData");
        String aesKey;
        try {
            //get merchant public key
            //use our private key to decrypt the decrypt key
            // encrypt the aes key
            aesKey = RSAUtils.privateDecrypt(encryptKey, ownPrivateKey);

            //use encrypt aes key to encrypt the data
            String originalData = AESUtil.decrypt(encryptData, aesKey);
            JSONObject originalDataObj = JSON.parseObject(originalData);


            long parseEndTime = System.nanoTime();
            log.info("parse merchant info,time:{}", parseEndTime - parseStartTime);
            //get sign from original data
            String sign = originalDataObj.getString("sign");
            if (StringUtils.isBlank(sign)) {
                log.error("originRequest:{}, request RSA sign is empty.", originalData);
            }
            originalDataObj.remove("sign");
            boolean signVerify = false;
            long verifyBeginNano = System.nanoTime();
            String originalDataObjNoSignStr = JSON.toJSONString(originalDataObj, SerializerFeature.MapSortField);
            signVerify = RSAUtils.verify(originalDataObjNoSignStr, thirdPartPublicKey, sign);
            long verifyDoneNano = System.nanoTime();
            log.info("verify time:{}", verifyDoneNano - verifyBeginNano);
            if (signVerify) {
                log.info("sign verify success!");
                return originalData;
            } else {
                log.error("originRequest:{}, request RSA sign result: false.", originalData);
            }

        } catch (NoSuchAlgorithmException | NoSuchPaddingException | InvalidKeyException | InvalidKeySpecException e) {
            log.error("request RSA decrypt error：{}", e.getMessage());
        } catch (Exception e) {
            log.error("request RSA decrypt error：{}", e.getMessage());
        }
        return null;
    }

    public static CreateCashierReq createOrder() {
        Amount amount = new Amount(1000L, CurrencyEnum.ZAR.getCode());
        Product product = new Product("productname", "short", "productDesc_b74f45d43c9c");

        // Create a unique reference for each run
        String uniqueReference = UUID.randomUUID().toString();

        // Create CreateCashierReq object
        CreateCashierReq createCashierReq = new CreateCashierReq();
        createCashierReq.setMerchantName("Merchant Name");
        createCashierReq.setCountry("ZAF");
        createCashierReq.setCurrency("ZAR");
        createCashierReq.setReference(uniqueReference);
        createCashierReq.setAmount(amount);
        createCashierReq.setBusinessType("MerchantAcquiring");
        createCashierReq.setRemark("remark_83c200fa64ff");
        createCashierReq.setCallbackUrl("callbackUrl_08941d02454c");
        createCashierReq.setReturnUrl("returnUrl_86a75a09e6b8");
        createCashierReq.setCancelUrl("");
        createCashierReq.setIp("ip_2841df759b91");
        createCashierReq.setProduct(product);
        createCashierReq.setProductList(new ArrayList<>());
        createCashierReq.setExpireAt(30);
        createCashierReq.setVat(new Amount(10L, CurrencyEnum.ZAR.getCode()));
        createCashierReq.setVatNumber("vatNumber_d98853c8c10c");
        createCashierReq.setMetadata(new HashMap<>());
        createCashierReq.setMerchantId(MustangpayApiConstantsV1.merchantId);
        List<String> payMethods = new ArrayList<>();
        payMethods.add(PayMethodEnum.INSTANT_EFT.getCode());
        createCashierReq.setPayMethods(payMethods);
        return createCashierReq;
    }

    public static String getMustangPayFilePath() {
        // 获取当前类的 ClassLoader
        ClassLoader classLoader = ResourceLoader.class.getClassLoader();

        // 尝试获取资源文件路径
        URL resourceUrl = classLoader.getResource("mustangpay/pro/MustangPayEncryptData");

        if (resourceUrl == null) {
            throw new RuntimeException("Resource not found: mustangpay/pro/MustangPayEncryptData");
        }

        // 获取资源文件的绝对路径
        String filePath = resourceUrl.getPath();

        return filePath;
    }
    public static String getMerchantFilePath() {
        // 获取当前类的 ClassLoader
        ClassLoader classLoader = ResourceLoader.class.getClassLoader();

        // 尝试获取资源文件路径
        URL resourceUrl = classLoader.getResource("mustangpay/pro/MerchantEncryptData");

        if (resourceUrl == null) {
            throw new RuntimeException("Resource not found: mustangpay/pro/MerchantEncryptData");
        }

        // 获取资源文件的绝对路径
        String filePath = resourceUrl.getPath();

        return filePath;
    }
}
