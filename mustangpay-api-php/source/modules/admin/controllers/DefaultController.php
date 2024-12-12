<?php

namespace app\modules\admin\controllers;

use app\helpers\Helper;
use app\models\BaseActiveRecord;
use app\models\Visit;
use app\modules\admin\models\ChangePwdForm;
use app\modules\admin\models\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * Class DefaultController
 * @package app\modules\admin\controllers
 */
class DefaultController extends BaseController
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'user' => 'user',
                'except' => ['login', 'captcha'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ]);
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'upload' => ['class' => 'app\widgets\ZKindEditor\UploadAction'],
            'manage' => ['class' => 'app\widgets\ZKindEditor\ManageAction'],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'padding' => 0,
                'width' => 110,
                'height' => 38,
                'minLength' => 4,
                'maxLength' => 4,
                'foreColor' => 0x009DDA,
//                'fixedVerifyCode' => YII_DEBUG ? 'test' : '',
            ],
        ];
    }

    public function actionIndex()
    {
        $start = date('Y-m-d', strtotime('-30 days'));
        $end = date('Y-m-d');
        $pvData = Visit::getPVByRange($start, $end, false);
        $uvData = Visit::getUVByRange($start, $end, false);

        return $this->render('index', [
            'mysql_size' => Helper::getDefaultDbSize(),
            'dateArr' => json_encode(array_keys($pvData)),
            'pvArr' => json_encode(array_values($pvData)),
            'uvArr' => json_encode(array_values($uvData)),
            'countArr' => [
                'pv' => array_sum($pvData),
                'uv' => array_sum($uvData),
            ],
            'pvData' => $pvData,
            'uvData' => $uvData,
        ]);
    }

    //删除图片
    public function actionCoverDelete($id, $table, $field)
    {
        /** @var  $modelClass BaseActiveRecord */
        $modelClass = 'app\models\\' . ucfirst($table);
        $model = $modelClass::findOne(['id' => $id]);
        $url = Yii::$app->request->referrer;

        if (!$model) {
            return $this->error('资料不存在！', $url);
        }
        if (Helper::imageDelete($id, $field, $model)) {
            return $this->success('删除成功', $url);
        }
        return $this->error('没有该图片', $url);
    }

    //删除多图
    public function actionPhotoDelete($id, $table, $field, $k = 0)
    {
        /** @var  $modelClass BaseActiveRecord */
        $modelClass = 'app\models\\' . ucfirst($table);
        $model = $modelClass::findOne(['id' => $id]);
        $url = Yii::$app->request->referrer;
        if (!$model) {
            return $this->error('资料不存在！', $url);
        }

        $photoArr = explode(';', $model->$field);
        if (!isset($photoArr[$k])) {
            return $this->error('图片不存在', $url);
        }
        Helper::unlink($photoArr[$k]);
        unset($photoArr[$k]);
        $model->$field = implode(';', $photoArr);
        if ($model->save()) {
            return $this->success('删除成功', $url);
        } else {
            return $this->error('删除失败:' . implode('|', $model->firstErrors), $url);
        }
    }

    //修改密码
    public function actionResetPwd()
    {
        $model = new ChangePwdForm();
        $model->user = $this->user;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->doSave()) {
                return $this->success('密码修改成功！', ['logout']);
            } else {
                Yii::error($model->errors);
                return $this->error('密码修改失败！');
            }
        }
        return $this->render('reset-pwd', [
            'model' => $model,
        ]);
    }


    //登录
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->cache->set('autoLogin', 1);
            return $this->redirect(['default/index']);
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    //退出
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['default/index']);
    }

    //清理缓存
    public function actionClearCache()
    {
        if (Yii::$app->cache->flush()) {
            return $this->success('缓存清理成功！', Yii::$app->request->referrer);
        } else {
            return $this->error('缓存清理失败！', Yii::$app->request->referrer);
        }
    }

}
