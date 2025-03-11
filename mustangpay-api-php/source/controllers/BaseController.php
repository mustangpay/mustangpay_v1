<?php
namespace app\controllers;

class BaseController extends \yii\web\Controller
{
    public $params =[];

    public function behaviors()
    {
        header("Access-Control-Allow-Origin: *");
        return parent::behaviors();
    }

}
?>
