package com.mustangpay.api.utils.mustangpay;

import com.alibaba.fastjson.JSON;
import com.mustangpay.api.pojo.CreateCashierReq;
import com.mustangpay.api.pojo.GatewayEncryptReq;
import lombok.extern.slf4j.Slf4j;

import java.io.*;

/**
 * @Author: hyssop
 * @Date: 09/18/2024
 */
@Slf4j
public class verifyKeyApiUtilsV1 extends AbstractBaseKeyApiUtils{
    /**
     * 该方法是mustangPay创建加签报文的方法
     * @param mustangPayPrivateKey
     * @param merchantPublicKey
     */
    public static void mustangPayCreateVerifyMessage(String mustangPayPrivateKey, String merchantPublicKey) {
      try{
          CreateCashierReq oneOrder = createOrder();
          String srcBody = JSON.toJSONString(oneOrder);
          log.info("request->srcBody：{}", srcBody);
          //this will send to mustangPay
          GatewayEncryptReq body = encryptToObject(srcBody,mustangPayPrivateKey,merchantPublicKey);
          //mustangpay receive the body and decrypt to original data
          log.info("response->destBody：{}", JSON.toJSONString(body));
          // 获取文件路径
          String filePath = getMustangPayFilePath();

          // 创建文件对象
          File file = new File(filePath);

          // 写入数据
          try (BufferedWriter writer = new BufferedWriter(new FileWriter(file))) {
              writer.write(JSON.toJSONString(body));
          }
          log.info("Data written to file: " + filePath);
      }catch (Exception e){
          log.error("mustangpay create verify message error",e);
      }

    }

    /**
     * 商户验证mustangPay提供的报文是否能够验签并解密成功
     * @param merchantPrivateKey
     * @param mustangPayPublicKey
     */
    public static void merchantCreateVerifyMessage(String merchantPrivateKey, String mustangPayPublicKey) {
        try{
            CreateCashierReq oneOrder = createOrder();
            String srcBody = JSON.toJSONString(oneOrder);
            log.info("request->srcBody：{}", srcBody);
            //this will send to mustangPay
            GatewayEncryptReq body = encryptToObject(srcBody,merchantPrivateKey,mustangPayPublicKey);
            //mustangpay receive the body and decrypt to original data
            log.info("response->destBody：{}", JSON.toJSONString(body));
            // 获取文件路径
            String filePath = getMustangPayFilePath();

            // 创建文件对象
            File file = new File(filePath);

            // 写入数据
            try (BufferedWriter writer = new BufferedWriter(new FileWriter(file))) {
                writer.write(JSON.toJSONString(body));
            }
            log.info("Data written to file: " + filePath);
        }catch (Exception e){
            log.error("mustangpay create verify message error",e);
        }
    }

    /**
     * 商户验证mustangPay给过来的带签名的报文
     * @param merchantPrivateKey
     * @param mustangPayPublicKey
     */
    public static void merchantVerifyMustangMessage(String merchantPrivateKey, String mustangPayPublicKey) {
        try{
            String filePath = getMustangPayFilePath();

            // 创建文件对象
            File file = new File(filePath);
            //get file info
            long fileSize = file.length();
            log.info("file size:{}",fileSize);
            try (BufferedReader reader = new BufferedReader(new FileReader(file))) {
                StringBuilder contentBuilder = new StringBuilder();
                String line;
                while ((line = reader.readLine()) != null) {
                    contentBuilder.append(line);
                }
                String content = contentBuilder.toString();
                log.info("content:{}",content);
                GatewayEncryptReq body = JSON.parseObject(content, GatewayEncryptReq.class);
                log.info("body:{}",JSON.toJSONString(body));
                String srcBody = decrypt(merchantPrivateKey, mustangPayPublicKey, body);
                log.info("srcBody:{}",srcBody);
            }
        }catch (Exception e){
            log.error("mustangpay verify message error",e);
        }

    }

    /**
     * mustangPay验证商户给过来的带签名的报文
     * @param mustangPayPrivateKey
     * @param merchantPublicKey
     */
    public static void mustangPayVerifyMustangMessage(String mustangPayPrivateKey, String merchantPublicKey) {
        try{
            String filePath = getMerchantFilePath();

            // 创建文件对象
            File file = new File(filePath);
            //get file info
            long fileSize = file.length();
            log.info("file size:{}",fileSize);
            try (BufferedReader reader = new BufferedReader(new FileReader(file))) {
                StringBuilder contentBuilder = new StringBuilder();
                String line;
                while ((line = reader.readLine()) != null) {
                    contentBuilder.append(line);
                }
                String content = contentBuilder.toString();
                log.info("content:{}",content);
                GatewayEncryptReq body = JSON.parseObject(content, GatewayEncryptReq.class);
                log.info("body:{}",JSON.toJSONString(body));
                String srcBody = decrypt(mustangPayPrivateKey, merchantPublicKey, body);
                log.info("srcBody:{}",srcBody);
            }
        }catch (Exception e){
            log.error("mustangpay verify message error",e);
        }
    }
}
