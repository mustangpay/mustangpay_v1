package com.mustangpay.api.pojo;

import lombok.Data;

import javax.validation.constraints.NotBlank;
import java.io.Serializable;

@Data
public class CreateRefundReq implements Serializable {
    private static final long serialVersionUID = -570423455129220482L;
    @NotBlank(message = "merchantId is empty")
    private String merchantId;

    private String merchantName;

    @NotBlank(message = "merchantName is empty")
    private String merchantOrderNo;

    @NotBlank(message = "originalMerchantOrderNo is empty")
    private String originalMerchantOrderNo;

    private Amount amount;

    @NotBlank(message = "country is empty")
    private String country;

    private String remark;

    @NotBlank(message = "businessType is empty")
    private String businessType;

    @NotBlank(message = "callbackUrl is empty")
    private String callbackUrl;


}
