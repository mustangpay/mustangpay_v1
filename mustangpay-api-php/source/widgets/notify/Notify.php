<?php
namespace app\widgets\notify;

use app\widgets\notify\assets\AppAsset;
use Yii;
use yii\base\Widget;

/**
 * Class Notify
 * @package app\widgets\notify
 */
class Notify extends Widget
{
    private $alertType = [
        'success' => 'success',
        'error' => 'danger',
        'info' => 'info',
        'warning' => 'warning',
        'danger' => 'danger',
    ];

    public function run()
    {
        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();

        foreach ($flashes as $k => $v){
            if(!isset($this->alertType[$k])){
                continue;
            }
            foreach ((array)$v as $i => $m){
                $k = $this->alertType[$k];

                $view = $this->getView();
                AppAsset::register($view);

                $view->registerJs(<<<Js
  setTimeout(function() {
                lightyear.loading('hide');
                lightyear.notify('{$m}', '{$k}', 3000);
            }, 1e3)
Js
                );

            }



            $session->removeFlash($k);

        }

    }
}