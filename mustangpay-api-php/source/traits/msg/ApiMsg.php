<?php

namespace app\traits\msg;

use Yii;
use yii\helpers\Json;
use yii\web\Response;

trait ApiMsg
{
    public function msg($codeStatus, $msg = '', $data = [])
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->statusCode = $codeStatus;

        Yii::info(Json::encode(Yii::$app->request->queryParams)
            . ' | ' . Json::encode(Yii::$app->request->post()) .
            ' | ' . Json::encode($data), 'api');
        $data = ['code' => $codeStatus, 'msg' => $msg, 'data' => $data];
        return $data;
    }

    /**
     * Error return method
     * @param $codeStatus
     * @param $codeError
     * @param $msg
     * @return array
     */
    public function error($codeStatus, $msg = '')
    {
        return $this->msg($codeStatus, $msg);
    }

    /**
     * Success return method
     * @param array|null $data
     * @return array
     */
    public function success($msg = 'Success', $data = [])
    {
        return $this->msg(0, $msg, ['data' => $data]);
    }
}
