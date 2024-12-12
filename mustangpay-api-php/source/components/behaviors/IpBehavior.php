<?php
namespace app\components\behaviors;

use app\extensions\czIp\IpLocation;
use Yii;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;

class IpBehavior extends Behavior
{
	public $ipAttribute = 'created_ip';
	public $locationAttribute = 'ip_location';
	
	public function events()
	{
		return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'insertAttach',
		];
	}
	
	public function insertAttach($event)
	{
        try {
            $this->locationAttribute ? $this->owner->{$this->ipAttribute} = Yii::$app->request->userIP : false;
            $this->locationAttribute ? $this->owner->{$this->locationAttribute} = IpLocation::getLocation(Yii::$app->request->userIP) : false;
        } catch (\Exception $e) {

        }
	}

	
}


?>