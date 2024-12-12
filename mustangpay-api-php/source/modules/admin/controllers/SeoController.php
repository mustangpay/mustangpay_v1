<?php

namespace app\modules\admin\controllers;

use app\models\Seo;
use yii\helpers\Url;
use Yii;

/**
 * Class SeoController
 * @package app\modules\admin\controllers
 * @desc SEO管理
 */
class SeoController extends BaseController
{
    public function actionSeo()
    {
        return $this->index(Seo::class);
    }

    public function actionSeoCreate()
    {
        return $this->create(Seo::class);
    }

    public function actionSeoUpdate($id)
    {
        return $this->update(Seo::class, $id);
    }

    public function actionSeoDelete($id)
    {
        return $this->delete(Seo::class, $id);
    }

    //seo设置
    public function actionSet()
    {
        Url::remember();
        $models = Seo::find()->where(['status' => STATUS_ACTIVE])->orderBy('order_by DESC, id')->all();

        if (Yii::$app->request->isPost && $seoPost = Yii::$app->request->post('Seo')) {
            $seoArr = [];
            foreach ($models as $model) {
                $seoArr[$model['action']] = $model;
            }

            $msg = [];
            foreach ($seoPost as $k => $v) {
                if (isset($seoArr[$k]) && $seoArr[$k]->load($v, '')) {
                    if ($seoArr[$k]->save()) {
                        $msg[] = 'SEO ' . $seoArr[$k]->name . ' 保存成功！';
                    } else {
                        $msg[] = 'SEO ' . $seoArr[$k]->name . ' 保存失败！';
                    }
                }
            }

            return $this->success(implode('<br>', $msg));
        }

        return $this->render($this->action->id, [
            'models' => $models
        ]);
    }
}

?>
