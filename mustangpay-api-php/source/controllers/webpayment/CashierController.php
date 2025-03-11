<?php

namespace app\controllers\webpayment;

use app\constants\MustangpayApiConstantsV1;
use app\controllers\BaseController;
use app\enums\OperationEnum;
use app\extensions\preorder\PreOrderWithManyPayMethod;
use app\extensions\preorder\PreOrderWithOutPayMethod;
use app\extensions\preorder\PreOrderWithPayMethodCard;
use app\extensions\preorder\PreOrderWithPayMethodEft;
use app\models\OrderStatusRep;
use app\utils\MustangpayApiUtilsV1;
use Yii;

class CashierController extends BaseController
{

    public function actionPreorder()
    {
        Yii::$app->response->format = 'json';
        $test = new PreOrderWithManyPayMethod();
        return $test->run();
    }

    public function actionPreorder2()
    {
        Yii::$app->response->format = 'json';
        $test = new PreOrderWithOutPayMethod();
        return $test->run();
    }

    public function actionPreorder3()
    {
        Yii::$app->response->format = 'json';
        $test = new PreOrderWithPayMethodCard();
        return $test->run();
    }

    public function actionPreorder4()
    {
        Yii::$app->response->format = 'json';
        $test = new PreOrderWithPayMethodEft();
        return $test->run();
    }

    public function actionStatus()
    {
        Yii::$app->response->format = 'json';

        $req = new OrderStatusRep();
        $req->merchantId = MustangpayApiConstantsV1::MERCHANT_ID;
        $req->merchantOrderNo = "a3470b0d-436b-443e-8ebf-997e7a46150a";
        return MustangpayApiUtilsV1::callTest('GetOrderStatusByMerchantOrderNoTest', $req, OperationEnum::CHECKORDER);
    }
}

