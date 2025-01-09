package com.mustangpay.api.enums;

import lombok.AllArgsConstructor;
import lombok.Getter;
import lombok.NoArgsConstructor;

/**
 * @Author: hyssop
 * @Date: 08/28/2024
 */
@Getter
@NoArgsConstructor
@AllArgsConstructor
public enum OperationEnum {
    PRECREATE("preCreate", "Merchant Pre-Order Creation"),
    CHECKORDER("checkOrder", "Merchant Order Status Inquiry"),
    H2H_PRECREATE("h2hPreCreate", "Merchant H2H Pre-Order Creation"),
    RefundCreate("refundCreate", "Merchant Refund"),
    RefundQuery("refundQuery", "Merchant Refund Inquiry"),
    ;

    private  String code;
    private  String desc;
    public void OperationEnum(String code, String desc){
        this.code = code;
        this.desc = desc;
    }

}
