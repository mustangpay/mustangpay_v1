<?php
/**
 * Created by zsz
 * User zsz
 * Time: 2024/1/25 9:53
 */

namespace app\models\forms;

use app\helpers\ArrayHelper;
use app\helpers\Helper;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * 文件上传表单
 * Class UploadForm
 * @package app\models\forms
 */
class UploadForm extends Model
{
    public $file;
    public $name;
    public $url;
    public $size;
    const SCENARIO_IMAGES = 'images';
    const SCENARIO_FILES = 'files';

    public function rules()
    {
        return [
            ['size', 'string'],
            [['file'], 'image', 'extensions' => Yii::$app->params['uploadConfig']['images']['extensions'], 'checkExtensionByMimeType' => false, 'on' => self::SCENARIO_IMAGES],
            [['file'], 'file', 'extensions' => Yii::$app->params['uploadConfig']['files']['extensions'], 'checkExtensionByMimeType' => false, 'on' => self::SCENARIO_FILES],
        ];
    }

    public function beforeValidate()
    {
        $this->attributes = Yii::$app->request->post();
        $this->file = UploadedFile::getInstanceByName('file');
        return parent::beforeValidate();
    }

    public function doSave()
    {
        if (!$this->validate()) {
            return false;
        }
        $blackList = ArrayHelper::getValue(Yii::$app->params, 'uploadConfig.files.blacklist', []);
        if(in_array($this->file->extension, $blackList)) {
            return $this->addError('file', '不支持该类型！');
        }
        Helper::modelUpload($this, $this->file, 'url', 'upload', $this->size);
        $arr = explode('/', $this->url);
        $this->name = array_pop($arr);
        return true;
    }

    public function result()
    {
        return [
            'url' => $this->url,
            'name' => $this->name
        ];
    }
}