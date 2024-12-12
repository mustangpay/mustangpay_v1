<?php

namespace app\models;

use yii\base\Model;

/**
 * Product represents the product information.
 */
class Product extends Model
{
    public $name;
    public $shortName;
    public $description;

    /**
     * Constructor for the Product model
     * @param string $name
     * @param string|null $shortName
     * @param string $description
     */
    public function __construct($name = null, $shortName = null, $description = null, $config = [])
    {
        parent::__construct($config);

        if ($name !== null) {
            $this->name = $name;
        }

        if ($shortName !== null) {
            $this->shortName = $shortName;
        }

        if ($description !== null) {
            $this->description = $description;
        }
    }

    /**
     * Define validation rules for the model
     */
    public function rules()
    {
        return [
            [['name'], 'required', 'message' => 'product.name is blank'], // Equivalent to @NotBlank in Java
            [['name', 'shortName', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return array the list of attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Product Name',
            'shortName' => 'Product Short Name',
            'description' => 'Product Description',
        ];
    }
}
