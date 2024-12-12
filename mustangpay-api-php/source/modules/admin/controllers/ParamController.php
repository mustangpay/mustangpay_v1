<?php

namespace app\modules\admin\controllers;

use app\helpers\Helper;
use app\models\ParamCategory;
use app\modules\admin\models\searchs\ParamSearch;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\Param;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Class ParamController
 * @package app\modules\admin\controllers
 * @desc 参数设置
 */
class  ParamController extends BaseController
{
    public function actionParamCategory()
    {
        return $this->index(ParamCategory::class);
    }

    public function actionParamCategoryCreate()
    {
        return $this->create(ParamCategory::class);
    }

    public function actionParamCategoryUpdate($id)
    {
        return $this->update(ParamCategory::class, $id);
    }

    public function actionParamCategoryDelete($id)
    {
        return $this->delete(ParamCategory::class, $id);
    }

    //系统参数管理   model  -- Param
    protected function processParams($cate_id)
    {
        $models = Param::find()
            ->andWhere(['cate_id' => $cate_id, 'status' => STATUS_ACTIVE])
            ->orderBy('order_by DESC')
            ->indexBy('code')
            ->all();
        $fileList = [];
        foreach ($models as $v) {
            if ($v->type == 'file') {
                $fileList[$v->code] = $v->value;
            }
        }
        if (Model::loadMultiple($models, Yii::$app->request->post()) && Model::validateMultiple($models)) {

            $allSuccess = true;
            foreach ($models as $model) {
                //文件上传
                if ($model->type == 'file') {
                    $file = UploadedFile::getInstanceByName("Param[{$model->code}][value]");
                    if ($file) {
                        Helper::modelUpload($model, $file, 'value', 'param');
                    } else {
                        $model->value = ArrayHelper::getValue($fileList, $model->code);
                    }
                    $delete = ArrayHelper::getValue(Yii::$app->request->post(), "Param.{$model->code}.del", false);
                    if ($delete) {
                        Helper::unlink($model->value);
                        $model->value = null;
                    }
                }
                if (!$model->save()) {
                    Yii::error($model->errors);
                    $allSuccess = false;
                }
            }
            Yii::$app->cache->flush();
            if ($allSuccess) {
                return $this->success('设置成功');
            } else {
                return $this->error('至少一项设置失败');
            }
        }
        Url::remember();

        return $this->render('set', [
            'models' => $models,
            'cate_id' => $cate_id,
            'list' => [
                'cate' => ParamCategory::map()
            ],
        ]);
    }

    public function actionSet($cate_id = 1)
    {
        return $this->processParams($cate_id);
    }

    public function actionParam()
    {
        Url::remember();
        $searchModel = new ParamSearch();
        return $this->render($this->action->id, [
            'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
            'searchModel' => $searchModel,
        ]);
    }

    public function actionParamCreate()
    {
        return $this->create(Param::class);
    }

    public function actionParamUpdate($id)
    {
        return $this->update(Param::class, $id);
    }

    public function actionParamDelete($id)
    {
        return $this->delete(Param::class, $id);
    }
}

?>
