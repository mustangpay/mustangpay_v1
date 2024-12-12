package com.mustangpay.api.pojo;

import lombok.Data;

import javax.validation.constraints.NotBlank;
import javax.validation.constraints.NotNull;
/**
 * @Author: hyssop
 * @Date: 07/08/2024
 */
@Data
public class ProductItem {

    @NotBlank(message = "productList.name is blank")
    private String name;
    @NotBlank(message = "productList.productId is blank")
    private String productId;

    @NotBlank(message = "productList.description is blank")
    private String description;

    @NotNull(message = "productList.price is null")
    private Long price;

    private Long quantity = 1L;

    private String imageUrl;

    private String weight;
}
