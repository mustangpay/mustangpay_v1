<?php

namespace app\extensions\preorder;

use app\enums\OperationEnum;
use app\enums\PayMethodEnum;
use app\utils\MustangpayApiUtilsV1;

class PreOrderWithPayMethodCard extends BasePreorder
{
    public function run()
    {
        $createCashierReq = $this->fillPreOrderReq();
        return MustangpayApiUtilsV1::callTest("PreOrderWithPayMethodCardTest", $createCashierReq, OperationEnum::PRECREATE);
    }

    protected function fillPayMethodInfo()
    {
        $payMethods = [
            PayMethodEnum::CARD_PAYMENT,
        ];
        return $payMethods;
    }

}
