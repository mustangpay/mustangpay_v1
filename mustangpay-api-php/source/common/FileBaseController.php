<?php
/**
 * Created by zsz
 * User zsz
 * Time: 2024/1/25 9:53
 */

namespace app\common;

use app\helpers\Helper;
use app\models\forms\UploadForm;
use yii\web\Controller;
use app\helpers\ResultHelper;

class FileBaseController extends Controller
{
    /**
     * 关闭Csrf验证
     *
     * @var bool
     */
    public $enableCsrfValidation = false;

    /**
     * 图片上传
     *
     * @return array
     */
    public function actionImages()
    {
        $model = new UploadForm();
        $model->scenario = UploadForm::SCENARIO_IMAGES;
        if ($model->doSave()) {
            return ResultHelper::json(200, '上传成功', $model->result());
        }
        return ResultHelper::json(422, Helper::analysisErr($model->getFirstErrors()));
    }

    /**
     * 文件上传
     *
     * @return array
     */
    public function actionFiles()
    {
        $model = new UploadForm();
        $model->scenario = UploadForm::SCENARIO_FILES;
        if ($model->doSave()) {
            return ResultHelper::json(200, '上传成功', $model->result());
        }
        return ResultHelper::json(422, Helper::analysisErr($model->getFirstErrors()));
    }
}
