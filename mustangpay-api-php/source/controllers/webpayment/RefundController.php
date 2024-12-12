<?php

namespace app\controllers\webpayment;

use app\constants\MustangpayApiConstantsV1;
use app\controllers\BaseController;
use app\enums\OperationEnum;
use app\extensions\preorder\PreOrderWithManyPayMethod;
use app\extensions\preorder\PreOrderWithOutPayMethod;
use app\extensions\preorder\PreOrderWithPayMethodCard;
use app\extensions\preorder\PreOrderWithPayMethodEft;
use app\models\Amount;
use app\models\CreateRefundReq;
use app\models\OrderStatusRep;
use app\models\RefundStatusReq;
use app\utils\MustangpayApiUtilsV1;
use Yii;

class RefundController extends BaseController
{

    /*
     *   /webpayment/refund/refund-query-test
     */
    public function actionRefundQueryTest()
    {
        Yii::$app->response->format = 'json';
        $amount = new Amount(1000, 'ZAR'); // 1000 ZAR
        $createCashierReq = new RefundStatusReq();
        $createCashierReq->setMerchantOrderNo("a8f03d54-22ce-4093-aa2d-17d74932b5f2");
        $createCashierReq->setMerchantId("4449999220"); // 或者使用 MustangpayApiConstantsV1.merchantId

        // 调用 API
        $operationCode = "RefundQuery"; // 根据实际情况调整
        return MustangpayApiUtilsV1::callTest("RefundQueryTest", $createCashierReq, $operationCode);

    }

    /*
     *
     * /webpayment/refund/refund-test
     */
    public function actionRefundTest()
    {
        Yii::$app->response->format = 'json';
        $amount = new Amount(1000, 'ZAR');
        $uniqueReference = Yii::$app->security->generateRandomString();
        // 创建 CreateRefundReq 对象
        $createRefundReq = new CreateRefundReq();
        $createRefundReq->merchantId = MustangpayApiConstantsV1::MERCHANT_ID;
        $createRefundReq->merchantName = 'Merchant Name';
        $createRefundReq->merchantOrderNo = $uniqueReference;
        $createRefundReq->originalMerchantOrderNo = "3076878416142605";
        $createRefundReq->country = 'ZAF';
        $createRefundReq->businessType = 'Refund';
        $createRefundReq->remark = 'remark_83c200fa64ff';
        $createRefundReq->callbackUrl = 'callback.url';
        $createRefundReq->amount = $amount;

        $operationCode =  OperationEnum::REFUND_CREATE;
        return MustangpayApiUtilsV1::callTest("RefundTest", $createRefundReq, $operationCode);
    }


}

