<?php

namespace app\traits\msg;

use yii\helpers\Url;
use Yii;

/**
 * example
 * 在页面布局中加
 * \app\widgets\notify\Notify::widget()
 * 或
 * \app\widgets\toastr\Toastr::widget()
 *
 * @desc 使用session记录 跳转信息
 *
 * Trait SessionMsg
 * @package app\traits\msg
 */
trait SessionMsg
{
    public function msg($message, $url = null, $msgType = null)
    {
        if (!$msgType || !in_array($msgType, ['success', 'error', 'info', 'warning'])) {
            $msgType = 'success';
        }
        if (!$url) {
            $url = Url::previous();
        }

        Yii::$app->getSession()->setFlash($msgType, $message);
        return $this->redirect($url);
    }

    public function success($message, $url = null)
    {
        return $this->msg($message, $url, 'success');
    }

    public function error($message, $url = null)
    {
        return $this->msg($message, $url, 'error');
    }
}
