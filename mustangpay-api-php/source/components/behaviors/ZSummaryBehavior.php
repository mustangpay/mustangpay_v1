<?php
namespace app\components\behaviors;

use yii\base\Behavior;
use yii\db\BaseActiveRecord;

class ZSummaryBehavior extends Behavior
{
	public $fromAttribute = 'content';
	public $toAttribute = 'summary';
	public $length = 150;
	
	public function events()
	{
		return [
			BaseActiveRecord::EVENT_BEFORE_UPDATE => 'updateSummary',
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'updateSummary',
		];
	}
	
	public function updateSummary($event)
	{
		if(empty($this->owner->{$this->toAttribute})) {
			$this->owner->{$this->toAttribute} = mb_substr(strip_tags($this->owner->{$this->fromAttribute}), 0, $this->length, 'utf-8');
		}
	}	
	
}