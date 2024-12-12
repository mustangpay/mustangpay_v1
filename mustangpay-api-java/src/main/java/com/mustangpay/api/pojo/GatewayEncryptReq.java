package com.mustangpay.api.pojo;

import lombok.Data;
import lombok.extern.slf4j.Slf4j;

import java.io.Serializable;

/**
 * @Author: hyssop
 * @Date: 07/22/2024
 */
@Slf4j
@Data
public class GatewayEncryptReq implements Serializable {
    //merchantId
    private String merchantId;
    //data after encrypt
    private String encryptData;
    //key after key encrypt
    private String encryptKey;
}
