<?php

namespace app\extensions\preorder;

use app\enums\OperationEnum;
use app\enums\PayMethodEnum;
use app\utils\MustangpayApiUtilsV1;

class PreOrderWithManyPayMethod extends BasePreorder
{
    public function run()
    {
        $createCashierReq = $this->fillPreOrderReq();
        return MustangpayApiUtilsV1::callTest("PreOrderWithManyPayMethodTest", $createCashierReq, OperationEnum::PRECREATE);
    }

    protected function fillPayMethodInfo()
    {
        $payMethods = [
            PayMethodEnum::CARD_PAYMENT,
            PayMethodEnum::INSTANT_EFT
        ];
        return $payMethods;
    }
}
