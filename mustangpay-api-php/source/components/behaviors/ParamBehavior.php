<?php
namespace app\components\behaviors;

use app\controllers\BaseController;
use Yii;
use yii\base\ActionFilter;
use app\models\Param;
use app\models\Category;
class ParamBehavior extends ActionFilter
{
	public $cache_name = 'params';
    public $cache_time = 60;

	public function beforeAction($action)
	{
        $cache = Yii::$app->cache;
        /** @var  $owner BaseController */
        $owner = $this->owner;
		if (isset($owner->params)) {
			$owner->params = $cache->get($this->cache_time);
			if ($owner->params === false) {
                $owner->params = Param::listMapData();
                $owner->params['categories'] = Category::find()
                    ->where(['status'=> STATUS_ACTIVE])
                    ->orderBy('order_by DESC , created_at DESC')
                    ->all();
				$cache->set($this->cache_name , $owner->params, $this->cache_time);
			}
		}
		return true;
	}
	
}


?>