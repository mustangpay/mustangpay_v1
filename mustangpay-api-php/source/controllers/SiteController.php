<?php

namespace app\controllers;


use Yii;
use yii\data\Pagination;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use app\models\Visit;
use app\models\Param;

class SiteController extends BaseController
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionLogin()
    {
        return $this->render('login');
    }

    public function actionStop()
    {
        return $this->render('stop');
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            // 系统关闭处理
            if ($action->id != 'stop' && ArrayHelper::getValue($this->params, 'system_status', 1) == 0) {
                $this->redirect(['/site/stop']);
            }

            return true;
        } else {
            return false;
        }
    }

    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);
        //Visit::log($action->id);

        return $result;
    }

}

