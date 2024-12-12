<?php

namespace app\models\member;

use Yii;
use app\models\User;
use app\models\UserLog;
use yii\web\UserEvent;
use yii\helpers\ArrayHelper;
use app\extensions\czIp\IpLocation;

class UserObject extends User
{
    protected static function checkIdentity($identity)
    {
        if ($identity && in_array($identity->group, [self::GROUP_MEMBER])) {
            return $identity;
        }

        return null;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->generateAccessToken();
            }
            return true;
        }

        return false;
    }

    /**
     * @param $event UserEvent
     */
    public static function afterLogin($event)
    {
        /* @var $identity User */
        $identity = $event->identity;
        UserLog::log(UserLog::TYPE_MEMBER, '登录', $identity->id);
        $identity->last_login_ip = Yii::$app->request->userIP;
        $identity->last_login_at = time();
        $identity->count_login++;
        $identity->last_login_location = ArrayHelper::getValue(IpLocation::getLocation(Yii::$app->request->userIP), 'area', '本地');
        $identity->save();

    }
}