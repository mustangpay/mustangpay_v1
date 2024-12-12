package com.mustangpay.api.enums;

import lombok.AllArgsConstructor;
import lombok.Getter;
import lombok.NoArgsConstructor;

import java.util.List;

@Getter
@NoArgsConstructor
@AllArgsConstructor
public enum PayMethodEnum {

    CARD_PAYMENT("CardPayment", "Card Payment"),
    INSTANT_EFT("InstantEFT", "Instant EFT"),
    OFFLINE_TRANSFER("OfflineTransfer", "Offline Transfer"),
    BALANCE("Balance", "Balance"),
    ;

    private String code;

    private String desc;

    //check if a list of  codes exists
    public static boolean contains(String code) {
        for (PayMethodEnum payMethodEnum : PayMethodEnum.values()) {
            if (payMethodEnum.getCode().equals(code)) {
                return true;
            }
        }
        return false;
    }

    //check if a list of  codes all exist if one not exists then return false else return true req is a list
    public static boolean containsAll(List<String> codes) {
        for (String code : codes) {
            if (!contains(code)) {
                return false;
            }
        }
        return true;
    }

    public static String getNameByType(String type){
        for (PayMethodEnum payMethodEnum : PayMethodEnum.values()) {
            if (payMethodEnum.getCode().equals(type)) {
                return payMethodEnum.getDesc();
            }
        }
        return null;
    }
}
