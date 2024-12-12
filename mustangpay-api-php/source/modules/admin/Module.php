<?php

namespace app\modules\admin;

use app\helpers\ArrayHelper;
use Yii;
use app\modules\admin\models\UserObject;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        Yii::$app->set('request', array_merge(ArrayHelper::getValue(Yii::$app, 'components.request', []), [
            'csrfParam' => '_csrf-admin',
        ]));
        Yii::$app->set('user', array_merge(ArrayHelper::getValue(Yii::$app, 'components.user', []), [
            'identityClass' => UserObject::class,
            'identityCookie' => [
                'name' => 'admin_user_identity',
                'httpOnly' => true,
            ],
            'idParam' => '_admin',
            'enableAutoLogin' => true,
            'loginUrl' => ['/admin/default/login'],
            'on afterLogin' => ['app\modules\admin\models\UserObject', 'afterLogin'],
        ]));
        // custom initialization code goes here
    }
}
