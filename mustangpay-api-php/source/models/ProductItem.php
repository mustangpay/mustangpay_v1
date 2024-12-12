<?php

namespace app\models;

use yii\base\Model;

/**
 * ProductItem represents an individual product item in the product list.
 */
class ProductItem extends Model
{
    public $name;
    public $productId;
    public $description;
    public $price;
    public $quantity = 1; // Default value is 1
    public $imageUrl;
    public $weight;

    /**
     * Define validation rules for the model
     */
    public function rules()
    {
        return [
            [['name', 'productId', 'description'], 'required', 'message' => 'This field cannot be blank'], // Equivalent to @NotBlank in Java
            [['price'], 'required', 'message' => 'productList.price is null'],
            [['price', 'quantity'], 'integer'], // price and quantity should be integers
            [['name', 'productId', 'description', 'imageUrl', 'weight'], 'string', 'max' => 255], // Max length for string fields
        ];
    }

    /**
     * @return array the list of attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Product Name',
            'productId' => 'Product ID',
            'description' => 'Product Description',
            'price' => 'Price',
            'quantity' => 'Quantity',
            'imageUrl' => 'Image URL',
            'weight' => 'Weight',
        ];
    }
}
