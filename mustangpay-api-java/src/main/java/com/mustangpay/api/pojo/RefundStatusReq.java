package com.mustangpay.api.pojo;

import lombok.Data;

import javax.validation.constraints.NotBlank;
import java.io.Serializable;

@Data
public class RefundStatusReq implements Serializable {
    private static final long serialVersionUID = -4575352252235520625L;

    @NotBlank(message = "merchantId is empty")
    private String merchantId;

    @NotBlank(message = "merchantOrderNo is empty")
    private String merchantOrderNo;
}
