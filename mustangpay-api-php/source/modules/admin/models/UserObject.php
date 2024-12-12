<?php

namespace app\modules\admin\models;


use app\extensions\czIp\IpLocation;
use app\models\User;
use app\models\UserLog;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UserEvent;

class UserObject extends User
{
    protected static function checkIdentity($identity)
    {
        if ($identity && in_array($identity->group, [self::GROUP_WORKER, self::GROUP_ADMIN])) {
            return $identity;
        }

        return null;
    }

    /**
     * @param $event UserEvent
     */
    public static function afterLogin($event)
    {
        /* @var $identity User */
        $identity = $event->identity;
        $title = Yii::$app->cache->get('autoLogin') ? '后台自动登录' : '后台登录';
        UserLog::log(UserLog::TYPE_ADMIN, $title, $identity->id, '/admin/default/login');
        $identity->last_login_ip = Yii::$app->request->userIP;
        $identity->last_login_at = time();
        $identity->count_login++;
        $identity->last_login_location = ArrayHelper::getValue(IpLocation::getLocation(Yii::$app->request->userIP), 'area', '本地');
        $identity->save();
    }

    public static function afterLogout($event)
    {
        UserLog::log(UserLog::TYPE_ADMIN, '后台退出!', $event->identity->id, '/admin/default/logout');
    }
}