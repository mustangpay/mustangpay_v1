package com.mustangpay.api.utils.mustangpay;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;
import com.alibaba.fastjson.serializer.SerializerFeature;
import com.mustangpay.api.constants.MustangpayApiConstantsV1;
import com.mustangpay.api.impl.KeyConfig;
import com.mustangpay.api.impl.MerConfigV1;
import com.mustangpay.api.pojo.GatewayEncryptReq;
import lombok.extern.slf4j.Slf4j;
import org.apache.commons.lang3.StringUtils;
import org.apache.http.HttpResponse;
import org.apache.http.HttpStatus;
import org.apache.http.client.config.RequestConfig;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.conn.ssl.NoopHostnameVerifier;
import org.apache.http.conn.ssl.TrustStrategy;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.CloseableHttpClient;
import org.apache.http.impl.client.HttpClients;
import org.apache.http.ssl.SSLContextBuilder;
import org.apache.http.util.EntityUtils;

import javax.crypto.NoSuchPaddingException;
import java.nio.charset.Charset;
import java.security.InvalidKeyException;
import java.security.NoSuchAlgorithmException;
import java.security.SignatureException;
import java.security.cert.CertificateException;
import java.security.cert.X509Certificate;
import java.security.spec.InvalidKeySpecException;
import java.util.HashMap;
import java.util.Map;
import java.util.UUID;

@Slf4j
public class MustangpayApiUtilsV1 {

    private static RequestConfig requestConfig;
    private static KeyConfig keyConfig = new MerConfigV1();

    static {
        // 设置请求和传输超时时间
        requestConfig = RequestConfig
                .custom()
                .setConnectTimeout(15000)
                .setConnectionRequestTimeout(15000)
                .setSocketTimeout(90000)
                .build();
    }

    public static Map<String,Object> callTest(String logPrefix, Object data,String jumpKey){
        boolean success = false;
        String result = null;
        try {

            String srcBody = JSON.toJSONString(data);
            log.info("{}|reqeust->srcBody：{}", logPrefix, srcBody);
            //加密+加签
            String sendJson = JSONObject.toJSONString(encryptToObject(srcBody,
                    RSAUtils.getKeyPem(keyConfig.getMustangPayPublicKeyPath()), MustangpayApiConstantsV1.merchantId));
            log.info("{}|reqeust->sendJson：{}", logPrefix, sendJson);



            //  HttpClientBuilder builder = HttpClients.custom();
            SSLContextBuilder buildertt = new SSLContextBuilder();
            buildertt.loadTrustMaterial(new TrustStrategy() {
                public boolean isTrusted(X509Certificate[] chain, String authType) throws CertificateException {
                    return true;
                }
            });
            //HttpClient httpClient = builder.build();
            CloseableHttpClient httpClient = HttpClients.custom()
                    .setSslcontext(buildertt.build())
                    .setSSLHostnameVerifier(NoopHostnameVerifier.INSTANCE)
                    .build();

            HttpPost httpPost = new HttpPost(keyConfig.geTestMustangPayApiUrl(jumpKey));
            httpPost.addHeader("Content-Type", "application/json;charset=UTF-8");
            // httpPost.addHeader("merchantId", MustangpayApiConstantsV1.merchantId);
            // httpPost.addHeader("Content-Type", "21251000800976235");
            httpPost.setEntity(new StringEntity(sendJson, Charset.forName("UTF-8")));
            httpPost.setConfig(requestConfig);
            String responseStr = null;
            try {
                HttpResponse httpResponse = httpClient.execute(httpPost);
                if (httpResponse.getStatusLine().getStatusCode() == HttpStatus.SC_OK) {
                    responseStr = EntityUtils.toString(httpResponse.getEntity(), "utf-8");
                }
            } catch (Exception e) {
                throw new RuntimeException("post请求提交失败", e);
            } finally {
                httpPost.releaseConnection();
            }
            log.info("{}|repsonse->str:{}", logPrefix, responseStr);
            // 解析
            JSONObject respJsonObj = JSON.parseObject(responseStr);
            log.info("{}|repsonse->json:{}", logPrefix, respJsonObj.toString());
            GatewayEncryptReq accessBody = JSONObject.parseObject(responseStr, GatewayEncryptReq.class);
            // 验签+获得结果
            String body = merchantDecrypt(accessBody);
            if (body == null) {
                throw new RuntimeException("收到的响应：验签失败");
            }
            log.info("{}|repsonse->验签成功", logPrefix);

            result = body;
            success = true;
        } catch (Exception e) {
            log.error("Mustangpay接口失败", e);
        }
        Map<String, Object> resultMap = new HashMap<>();
        resultMap.put("code", success ? "S" : "F");
        resultMap.put("result", result);
        return resultMap;
    }

    public static Map<String, Object> callMustangPayPreOrderApi(String logPrefix, Object data,String jumpKey) {
        boolean success = false;
        String result = null;
        try {

            String srcBody = JSON.toJSONString(data);
            //加密+加签
            String sendJson = JSONObject.toJSONString(encryptProToObject(srcBody,
                    RSAUtils.getKeyPem(keyConfig.getProMustangPayPublicKeyPath()), MustangpayApiConstantsV1.merchantId));
            log.info("{}|reqeust->sendJson：{}", logPrefix, sendJson);



          //  HttpClientBuilder builder = HttpClients.custom();
            SSLContextBuilder buildertt = new SSLContextBuilder();
            buildertt.loadTrustMaterial(new TrustStrategy() {
                public boolean isTrusted(X509Certificate[] chain, String authType) throws CertificateException {
                    return true;
                }
            });
            //HttpClient httpClient = builder.build();
            CloseableHttpClient httpClient = HttpClients.custom()
                    .setSslcontext(buildertt.build())
                    .setSSLHostnameVerifier(NoopHostnameVerifier.INSTANCE)
                    .build();

            HttpPost httpPost = new HttpPost(keyConfig.getMustangPayApiUrl(jumpKey));
            httpPost.addHeader("Content-Type", "application/json;charset=UTF-8");
           // httpPost.addHeader("merchantId", MustangpayApiConstantsV1.merchantId);
           // httpPost.addHeader("Content-Type", "21251000800976235");
            httpPost.setEntity(new StringEntity(sendJson, Charset.forName("UTF-8")));
            httpPost.setConfig(requestConfig);
            String responseStr = null;
            try {
                HttpResponse httpResponse = httpClient.execute(httpPost);
                if (httpResponse.getStatusLine().getStatusCode() == HttpStatus.SC_OK) {
                    responseStr = EntityUtils.toString(httpResponse.getEntity(), "utf-8");
                }
            } catch (Exception e) {
                throw new RuntimeException("post请求提交失败", e);
            } finally {
                httpPost.releaseConnection();
            }
            log.info("{}|repsonse->str:{}", logPrefix, responseStr);
            // 解析
            JSONObject respJsonObj = JSON.parseObject(responseStr);
            log.info("{}|repsonse->json:{}", logPrefix, respJsonObj.toString());
            GatewayEncryptReq accessBody = JSONObject.parseObject(responseStr, GatewayEncryptReq.class);
            // 验签+获得结果
            String body = merchantProDecrypt(accessBody);
            if (body == null) {
                throw new RuntimeException("收到的响应：验签失败");
            }
            log.info("{}|repsonse->验签成功", logPrefix);

            result = body;
            success = true;
        } catch (Exception e) {
            log.error("Mustangpay接口失败", e);
        }
        Map<String, Object> resultMap = new HashMap<>();
        resultMap.put("code", success ? "S" : "F");
        resultMap.put("result", result);
        return resultMap;
    }

    //验签
    public static String merchantProDecrypt(GatewayEncryptReq body) {
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
            String merchantRsaPrivateKey = RSAUtils.getKeyPem(keyConfig.getProRsaPrivateKeyPath());
            // encrypt the aes key
            aesKey = RSAUtils.privateDecrypt(encryptKey, merchantRsaPrivateKey);

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
            signVerify = RSAUtils.verify(originalDataObjNoSignStr, RSAUtils.getKeyPem(keyConfig.getProMustangPayPublicKeyPath()), sign);
            long verifyDoneNano = System.nanoTime();
            log.info("verify time:{}", verifyDoneNano - verifyBeginNano);
            if (signVerify) {
                // #log# log.info("request body: {}", originalWalletResponseStr);
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


    //验签
    public static String merchantDecrypt(GatewayEncryptReq body) {
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
            String merchantRsaPrivateKey = RSAUtils.getKeyPem(keyConfig.getRsaPrivateKeyPath());
            // encrypt the aes key
            aesKey = RSAUtils.privateDecrypt(encryptKey, merchantRsaPrivateKey);

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
            signVerify = RSAUtils.verify(originalDataObjNoSignStr, RSAUtils.getKeyPem(keyConfig.getMustangPayPublicKeyPath()), sign);
            long verifyDoneNano = System.nanoTime();
            log.info("verify time:{}", verifyDoneNano - verifyBeginNano);
            if (signVerify) {
                // #log# log.info("request body: {}", originalWalletResponseStr);
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

    //加密
    public static GatewayEncryptReq encryptToObject(String response, String mustangPayPublicKey, String merchantId) {

        if (StringUtils.isBlank(mustangPayPublicKey)) {
            log.error("response encrypt error, app public key error");
        }

        JSONObject jsonObject = JSONObject.parseObject(response);


        String originalParamJsonStr = JSON.toJSONString(jsonObject, SerializerFeature.MapSortField);

        String sign = null;
        try {
            //merchant sign with merchant private key.
            String merchantRsaPrivateKey = RSAUtils.getKeyPem(keyConfig.getRsaPrivateKeyPath());
            sign = RSAUtils.sign(originalParamJsonStr, merchantRsaPrivateKey);
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
            encryptKey = RSAUtils.publicEncrypt(aesKey, mustangPayPublicKey);
        } catch (NoSuchAlgorithmException | InvalidKeyException | NoSuchPaddingException | InvalidKeySpecException e) {
            log.error("response RSA Encryption failed：{}", e.getMessage());
        }
        // Finally generate the parameters after encryption.
        GatewayEncryptReq getewayParamEncryptReq = new GatewayEncryptReq();
        getewayParamEncryptReq.setEncryptKey(encryptKey);
        getewayParamEncryptReq.setEncryptData(encryptData);
        getewayParamEncryptReq.setMerchantId(merchantId);
        return getewayParamEncryptReq;
    }

    //加密
    public static GatewayEncryptReq encryptProToObject(String response, String mustangPayPublicKey, String merchantId) {

        if (StringUtils.isBlank(mustangPayPublicKey)) {
            log.error("response encrypt error, app public key error");
        }

        JSONObject jsonObject = JSONObject.parseObject(response);


        String originalParamJsonStr = JSON.toJSONString(jsonObject, SerializerFeature.MapSortField);

        String sign = null;
        try {
            //merchant sign with merchant private key.
            String merchantRsaPrivateKey = RSAUtils.getKeyPem(keyConfig.getProRsaPrivateKeyPath());
            sign = RSAUtils.sign(originalParamJsonStr, merchantRsaPrivateKey);
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
            encryptKey = RSAUtils.publicEncrypt(aesKey, mustangPayPublicKey);
        } catch (NoSuchAlgorithmException | InvalidKeyException | NoSuchPaddingException | InvalidKeySpecException e) {
            log.error("response RSA Encryption failed：{}", e.getMessage());
        }
        // Finally generate the parameters after encryption.
        GatewayEncryptReq getewayParamEncryptReq = new GatewayEncryptReq();
        getewayParamEncryptReq.setEncryptKey(encryptKey);
        getewayParamEncryptReq.setEncryptData(encryptData);
        getewayParamEncryptReq.setMerchantId(merchantId);
        return getewayParamEncryptReq;
    }

}
