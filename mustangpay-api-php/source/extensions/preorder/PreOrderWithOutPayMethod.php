<?php

namespace app\extensions\preorder;

use app\enums\OperationEnum;
use app\enums\PayMethodEnum;
use app\utils\MustangpayApiUtilsV1;

class PreOrderWithOutPayMethod extends BasePreorder
{
    public function run()
    {
        $createCashierReq = $this->fillPreOrderReq();
        return MustangpayApiUtilsV1::callTest("PreOrderWithOutPayMethodTest", $createCashierReq, OperationEnum::PRECREATE);
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
