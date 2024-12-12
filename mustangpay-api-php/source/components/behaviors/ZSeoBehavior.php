<?php
namespace app\components\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\View;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use \app\models\Seo;

class ZSeoBehavior extends Behavior
{
	public $seo;
	public $title;
	public $keywords;
	public $description;
	
	public function events()
	{
		return [
			View::EVENT_BEGIN_PAGE => 'setSeo',
		];
	}
	
	public function setSeo($event)
	{
        $view = $this->owner;
        $controller = $this->owner->context;

        if (!($controller instanceof Controller)) {
            return false;
        }
		if(ArrayHelper::getValue($view->params, 'seoOk') == true) return false;
		$models = Yii::$app->cache->get('seoModels');
		if($models === false) {
			$models = Seo::find()->where(['status' => STATUS_ACTIVE])->asArray()->all();
			Yii::$app->cache->set('seoModels', $models, 60);
		}

		foreach($models as $model) {
			$this->seo[$model['action']] = $model;
		}

		$route = ArrayHelper::getValue($this->owner, 'context.route');
		if(!ArrayHelper::getValue($controller , 'params.site_title' , false)){
		    return false;
        }
        if (!$this->title) {
            $this->title = $controller->params['site_title'];
        }
		$this->keywords = $controller->params['site_keywords'];
		$this->description = $controller->params['site_description'];

		if(isset($this->seo[$route])) {
			if ($this->seo[$route]['title']) {
                $this->title = $this->seo[$route]['title'];
            }
            if (isset($controller->params['seo_title']) && $controller->params['seo_title']) {
                $this->title = $controller->params['seo_title'];
            }

            if ($this->seo[$route]['keywords']) {
                $this->keywords = $this->seo[$route]['keywords'];
            }
            if (isset($controller->params['seo_keywords']) && $controller->params['seo_keywords']) {
                $this->keywords = $controller->params['seo_keywords'];
            }

            if ($this->seo[$route]['description']) {
                $this->description = $this->seo[$route]['description'];
            }
            if (isset($controller->params['seo_description']) && $controller->params['seo_description']) {
                $this->description = $controller->params['seo_description'];
            }
		}
		
		$seoTrans = [
			'{siteName}'=>$controller->params['site_name'],
			'{siteTitle}'=>$controller->params['site_title'],
			'{siteKeywords}'=>$controller->params['site_keywords'],
			'{siteDescription}'=>$controller->params['site_description'],
		];

        $seoTrans = array_merge($seoTrans, ArrayHelper::getValue($controller, 'params.seoTrans', []));
		
		$view->title = strtr($this->title, $seoTrans);
		$view->registerMetaTag([
			'name' => 'keywords',
			'content' => strtr($this->keywords, $seoTrans),
		], 'keywords');
		$view->registerMetaTag([
			'name' => 'description',
			'content' => strtr($this->description, $seoTrans),
		], 'description');
		$view->params['seoOK'] = true;
		return true;
	}	
	
}