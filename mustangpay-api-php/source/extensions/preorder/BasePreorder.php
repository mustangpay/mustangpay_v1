<?php

namespace app\extensions\preorder;

use app\constants\MustangpayApiConstantsV1;
use app\enums\CurrencyEnum;
use app\models\Amount;
use app\models\Product;
use app\models\CreateCashierReq;
use Yii;

abstract class BasePreorder
{
    public function fillPreOrderReq()
    {
        $createCashierReq = self::wrapperCreateCashierReq();
        $createCashierReq->payMethods = $this->fillPayMethodInfo();
        return $createCashierReq;
    }

    public static function wrapperCreateCashierReq()
    {
        $amount = new Amount(100, CurrencyEnum::getByCode('ZAR')); // Assuming constructor accepts amount and currency code
        $product = new Product("productname", "short", "productDesc_b74f45d43c9c");

        $uniqueReference = Yii::$app->security->generateRandomString();

        // Create CreateCashierReq object
        $createCashierReq = new CreateCashierReq();

        $createCashierReq->country = "ZAF";
        $createCashierReq->currency = "ZAR";
        $createCashierReq->reference = $uniqueReference;
        $createCashierReq->amount = [
            'currency' => $createCashierReq->currency,
            'value' => $amount->value,
        ];
        $createCashierReq->businessType = "MerchantAcquiring";
        $createCashierReq->remark = "remark_83c200fa64ff";
        $createCashierReq->callbackUrl = "callbackUrl_08941d02454c";
        $createCashierReq->returnUrl = "returnUrl_86a75a09e6b8";
        $createCashierReq->cancelUrl = "";
        $createCashierReq->ip = "ip_2841df759b91";
        $createCashierReq->product = [
            'description' => $product->description,
            'name' => $product->name,
            'shortName' => $product->shortName,
        ];
        $createCashierReq->productList = []; // Empty product list
        $createCashierReq->expireAt = 30;  // Expiration time
        $vatAmount = new Amount(10, CurrencyEnum::ZAR);
        $createCashierReq->vat = [
            'currency' => $vatAmount->currency,
            'value' => $vatAmount->value,
        ];  // Tax amount
        $createCashierReq->vatNumber = "vatNumber_d98853c8c10c";  // VAT number
        $createCashierReq->merchantId = MustangpayApiConstantsV1::MERCHANT_ID;  // Merchant ID
        return $createCashierReq;
    }

    abstract public function run();

    abstract protected function fillPayMethodInfo();
}
