package com.mustangpay.api.pojo;

import lombok.Data;
import lombok.ToString;

import java.io.Serializable;
import java.util.Map;

/**
 * @Author: hyssop
 * @Date: 07/08/2024
 */
@Data
@ToString
public class CashierCreateResp implements Serializable {

    /**
     * merchant order no
     */
    private String reference;

    /**
     * trade order no
     */
    private String orderNo;

    /**
     * order status
     */
    private String orderStatus;

    /**
     * order amount (without vat)
     */
    private Amount amount;
    /**
     * vat amount
     */
    private Amount vat;

    private String errorCode;
    private String errorMessage;

    private String cashierUrl;

    private String merchantId;
    /**
     * redirect pay url for 3ds and non 3ds
     */
    private String redirectPayUrl;

    private String redirectType;
    /**
     * redirect pay param for 3ds and non 3ds
     */
    private Map<String, Object> redirectPayParam;

}
