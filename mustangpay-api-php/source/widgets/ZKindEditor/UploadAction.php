<?php

namespace app\widgets\ZKindEditor;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\web\HttpException;

class UploadAction extends Action
{
	
	public $uploadPath = '@webroot/data/upload';
	public $uploadUrl = '@web/data/upload';
	public $uploadExtension = [
		'image' => ['gif', 'jpg', 'jpeg', 'png', 'bmp'],
		'flash' => ['swf', 'flv'],
		'media' => ['swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'],
		'file' => ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'],
	];
	public $maxSize = 12097152; //2MB
	
	public function init()
	{
		if(!isset($_POST['_csrfZ'])) throw new HttpException(500, '异常上传');
		$csrfArr = unserialize(urldecode($_POST['_csrfZ']));
		if(isset($csrfArr['key']) && isset($csrfArr['uid']) && $csrfArr['key'] == md5($csrfArr['uid'] . date('Ymd') . Yii::$app->request->cookieValidationKey)) {
			
		} else {
			throw new HttpException(500, '异常上传2:'.var_export($csrfArr, true));
		}
        $this->uploadPath = Yii::getAlias($this->uploadPath);
        if(!is_dir($this->uploadPath)){
		    FileHelper::createDirectory($this->uploadPath);
        }

        if (!is_dir($this->uploadPath)) {
            throw new InvalidConfigException("The directory does not exist: {$this->uploadPath}");
        } elseif (!is_writable($this->uploadPath)) {
            throw new InvalidConfigException("The directory is not writable by the Web process: {$this->uploadPath}");
        } else {
            $this->uploadPath = realpath($this->uploadPath);
        }
        
        $this->uploadUrl = rtrim(Yii::getAlias($this->uploadUrl), '/');
	}
	
	public function run()
	{
		$dir = isset($_GET['dir']) ? trim($_GET['dir']) : 'file';
		if(empty($this->uploadExtension[$dir])){
			return Json::encode(array('error'=>1,'message'=>'目录名不正确。'));
		}
		
		$uploadImage = UploadedFile::getInstanceByName('imgFile');
		
		$datePath=date('Ymd');
		
		$uploadPath = $this->uploadPath . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $datePath;
		$uploadUrl = $this->uploadUrl . '/' . $dir . '/' . $datePath;
		if(!is_dir($uploadPath)) {
			FileHelper::createDirectory($uploadPath);
		}
		
		if($uploadImage instanceof UploadedFile) {
			if($uploadImage->size > $this->maxSize){
				return Json::encode(array('error'=>1,'message'=>'上传文件大小超过限制。'));
			}
			if(in_array(strtolower($uploadImage->extension), $this->uploadExtension[$dir]) === false){
				return Json::encode(array('error'=>1,'message'=>"上传文件扩展名是不允许的扩展名。\n只允许".implode(',', $this->uploadExtension[$dir]).'格式。'));
			}
			//新文件名
			$filename = date("YmdHis").'_'.rand(10000, 99999);
			
			$uploadImage->saveAs($uploadPath . DIRECTORY_SEPARATOR . $filename . '.' . $uploadImage->extension);
			
			return Json::encode(array('error'=>0,'url'=>$uploadUrl . '/' . $filename . '.' . $uploadImage->extension));
		}else{
			return Json::encode(array('error'=>1,'message'=>'未知错误'));
		}
	}
}