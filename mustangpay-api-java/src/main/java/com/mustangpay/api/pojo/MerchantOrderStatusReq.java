package com.mustangpay.api.pojo;

import lombok.Data;
import lombok.ToString;

import java.io.Serializable;

/**
 * @Author: hyssop
 * @Date: 08/12/2024
 */
@Data
@ToString
public class MerchantOrderStatusReq implements Serializable {

    private String merchantId;

    private String merchantOrderNo;
}
