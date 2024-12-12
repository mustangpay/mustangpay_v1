package com.mustangpay.api.pojo;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import lombok.Data;
import lombok.ToString;

import javax.validation.Valid;
import javax.validation.constraints.*;
import java.io.Serializable;
import java.util.HashMap;
import java.util.List;

/**
 * @Author: hyssop
 * @Date: 07/08/2024
 */
@Data
@JsonIgnoreProperties(ignoreUnknown = true)
@ToString
public class CreateCashierReq implements Serializable {

    /**
     * merchant name
     */
    private String merchantName;


    /**
     * country
     */
    private String country;
    /**
     * currency
     */
    private String currency;

    /**
     * merchant order no
     */
    @NotBlank(message = "Reference is empty")
    private String reference;

    /**
     * pre order amount
     */
    @NotNull(message = "Amount is empty")
    @Valid
    private Amount amount;

    /**
     * call back url
     */
    //@NotBlank(message = "Callback URL is empty")
    private String callbackUrl;

    /**
     * return url
     */
    private String returnUrl;
    /**
     *cencel url
     */
    private String cancelUrl;

    /**
     * pay user client ip
     */
    private String ip;

    /**
     * product info
     */
    @NotNull
    private Product product;

    private List<ProductItem> productList;

    private List<String> payMethods;
    /**
     * expire time (minutes)
     */
    @Min(value = 1,message = "expireAt min 1 minutes")
    @Max(value = 43200,message = "expireAt max 30 days")
    private Integer expireAt = 30;

    /**
     * vat amount
     */
    private Amount vat;

    private String vatNumber;

    private HashMap<String,String> metadata;
    private String remark;

    private String merchantId;

    private String businessType;

    private String payType;

    @Size(max = 19, message = "bankCardNo is too long")
    private String bankCardNo;

    @Size(max = 32, message = "cardExpiryDate is too long")
    private String cardExpiryDate;

    @Size(max = 4, message = "cardCvv is too long")
    private String cardCvv;

    private String email;

    private String orderNo;

    @Size(max = 50, message = "firstName is too long")
    private String firstName;

    @Size(max = 50, message = "middleName is too long")
    private String middleName;

    @Size(max = 50, message = "lastName is too long")
    private String lastName;

    @Size(max = 32, message = "mobile is too long")
    private String mobile;

    @Size(max = 32, message = "bankCode is too long")
    private String bankCode;

    @Size(max = 64, message = "bankName is too long")
    private String bankName;

}
