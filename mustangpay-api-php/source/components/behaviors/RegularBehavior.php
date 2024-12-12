<?php
namespace app\components\behaviors;

use yii\base\Behavior;
use yii\db\BaseActiveRecord;
/**
 * 去除文本中的 内容 标签
 *
 */

class RegularBehavior extends Behavior
{
    public $attributes = [
        'content',
        'summary',
    ];

	private $commonRules = [
	    '/<script[\s\S]*?<\/script>/i' => '',
    ];

    public function events()
	{
		return [
			BaseActiveRecord::EVENT_BEFORE_UPDATE => 'replace',
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'replace',
		];
	}
	
	public function replace($event)
	{
	    foreach ($this->attributes as $k => $v){
	        if(gettype($k) == 'integer'){
                $patters = array_keys($this->commonRules);
                $replace = array_values($this->commonRules);
                $attribute = $v;
            }else{
                $patters = array_keys($v);
                $replace = array_values($v);
                $attribute = $v;
            }

            $this->owner->{$attribute} =  preg_replace($patters , $replace , $this->owner->{$attribute}) ;
        }
	}
	
}