<?php

namespace app\extensions\preorder;

use app\enums\OperationEnum;
use app\enums\PayMethodEnum;
use app\utils\MustangpayApiUtilsV1;

class PreOrderWithPayMethodEft extends BasePreorder
{
    public function run()
    {
        $createCashierReq = $this->fillPreOrderReq();
        return MustangpayApiUtilsV1::callTest("PreOrderWithPayMethodEftTest", $createCashierReq, OperationEnum::PRECREATE);
    }

    protected function fillPayMethodInfo()
    {
        $payMethods = [
            PayMethodEnum::INSTANT_EFT,
        ];
        return $payMethods;
    }

}
