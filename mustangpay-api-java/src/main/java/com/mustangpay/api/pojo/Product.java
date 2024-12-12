package com.mustangpay.api.pojo;

import lombok.Data;

import javax.validation.constraints.NotBlank;

/**
 * @Author: hyssop
 * @Date: 07/08/2024
 */
@Data
public class Product {

    @NotBlank(message = "product.name is blank")
    private String name;
    private String shortName;
    private String description;

    public Product(String name, String description) {
        this.name = name;
        this.description = description;
    }

    public Product(String name, String shortName, String description) {
        this.name = name;
        this.shortName = shortName;
        this.description = description;
    }
    public Product() {
        super();
    }
}
