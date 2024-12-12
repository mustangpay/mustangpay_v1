package com.mustangpay.api.enums;

import lombok.AllArgsConstructor;
import lombok.Getter;
import lombok.NoArgsConstructor;

@Getter
@NoArgsConstructor
@AllArgsConstructor
public enum CurrencyEnum {

    ZAR("ZAR", "ZAF" ,"Rand");

    private String code;

    private String countryCode;

    private String desc;

    public static CurrencyEnum getByCode(String code) {

        for (CurrencyEnum currEnum : CurrencyEnum.values()) {
            if (currEnum.getCode().equals(code)) {
                return currEnum;
            }
        }

        throw new IllegalArgumentException("Enum: getByCode(" + code + ") not exist.");
    }

}
