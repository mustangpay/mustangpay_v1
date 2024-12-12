package com.mustangpay.api.pojo;

import com.mustangpay.api.enums.CurrencyEnum;
import lombok.AllArgsConstructor;
import lombok.Data;
import lombok.NoArgsConstructor;
import lombok.experimental.SuperBuilder;

import java.io.Serializable;
import java.math.BigDecimal;
import java.math.RoundingMode;

@Data
@SuperBuilder
@NoArgsConstructor
@AllArgsConstructor
public class Amount implements Serializable {
    private static final long serialVersionUID = 1L;

    private Long value;

    private CurrencyEnum currency;

    public Amount(Long amount){
        this(amount,CurrencyEnum.ZAR);
    }
    public Amount(Long amount, String currencyCode){
        this(amount,CurrencyEnum.getByCode(currencyCode));
    }

    /**
     * Determine whether the principal amount is greater than a certain amount
     *
     * @param amount
     * @return
     */
    public final boolean isGreaterThan(Amount amount) {
        return this.value.compareTo(amount.getValue()) > 0;
    }

    /**
     * Determine whether the principal amount is greater than or equal to a certain amount
     *
     * @param amount
     * @return
     */
    public final boolean isGreaterOrEqualThan(Amount amount) {
        return this.value.compareTo(amount.getValue()) >= 0;
    }


    /**
     * Determine whether the principal amount is equal to a certain amount
     *
     * @param amount
     * @return
     */
    public final boolean isEqualTo(Amount amount) {
        return this.value.compareTo(amount.getValue()) == 0;
    }

    /**
     * Determine whether the principal amount is less than a certain amount
     *
     * @param amount
     * @return
     */
    public final boolean isLesserThan(Amount amount) {
        return this.value.compareTo(amount.getValue()) < 0;
    }

    /**
     * Determine whether the principal amount is less than or equal to a certain amount
     *
     * @param amount
     * @return
     */
    public final boolean isLesserOrEqualThan(Amount amount) {
        return this.value.compareTo(amount.getValue()) <= 0;
    }

    /**
     * Determine whether the principal amount is positive
     *
     * @return
     */
    public final boolean checkPositive() {
        return this.value.compareTo(0L) > 0;
    }

    /**
     * Determine whether the principal amount is 0
     *
     * @return
     */
    public final boolean checkZero() {
        return this.value.compareTo(0L) == 0;
    }

    /**
     * Determine whether the principal amount is negative
     *
     * @return
     */
    public final boolean checkNegative() {
        return this.value.compareTo(0L) < 0;
    }

    /**
     * The amounts are added
     *
     * @param amount The amount to be added
     * @return
     */

    public final Amount add(Amount amount) {
        BigDecimal amountValue = new BigDecimal(this.value);
        BigDecimal addAmountValue = new BigDecimal(amount.getValue());
        this.value = amountValue.add(addAmountValue).longValue();
        return this;
    }

    public final Amount subtract(Amount amount) {
        BigDecimal amountValue = new BigDecimal(this.value);
        BigDecimal subtractAmountValue = new BigDecimal(amount.getValue());
        this.value = amountValue.subtract(subtractAmountValue).longValue();
        return this;
    }

    public static String formatAmountFromCents(Amount amount) {
        if (amount == null || amount.getValue() == null) {
            throw new IllegalArgumentException("Amount object and its value must not be null.");
        }
        BigDecimal centsValue = new BigDecimal(amount.getValue());
        // Divide to get the value in units and set scale to 2 decimal places with rounding up
        BigDecimal unitsValue = centsValue.divide(new BigDecimal(100), 2, RoundingMode.HALF_UP);
        // Always format to two decimal places
        return unitsValue.setScale(2, RoundingMode.HALF_UP).toPlainString();
    }

    public static Long getAmountValue(Amount amount) {
        if (amount == null) {
            return null;
        }
        return amount.getValue();
    }
    public static Amount getAmount(Long amount,String currency){
        return new Amount(amount,currency);
    }

}
