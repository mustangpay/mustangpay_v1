<?php

namespace app\widgets\ZKindEditor;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;


class ManageAction extends UploadAction
{
	
	public function run(){
		$dir = empty($_GET['dir']) ? '' : trim($_GET['dir']);
		
		if (!in_array($dir, ArrayHelper::merge([''], array_keys($this->uploadExtension)))) {
			return "Invalid Directory name.";
		}
		$uploadPath = $this->uploadPath;
		$uploadUrl = $this->uploadUrl;
		if ($dir !== '') {
			$uploadPath .= DIRECTORY_SEPARATOR . $dir;
			$uploadUrl .= '/' . $dir;
			if (!is_dir($uploadPath)) {
				FileHelper::createDirectory($uploadPath);
			}
		}
		
		//根据path参数，设置各路径和URL
		if (empty($_GET['path'])) {
			$current_path = $uploadPath . DIRECTORY_SEPARATOR;
			$current_url = $uploadUrl . '/';
			$current_dir_path = '';
			$moveup_dir_path = '';
		} else {
			$current_path = $uploadPath  . DIRECTORY_SEPARATOR . $_GET['path'] . DIRECTORY_SEPARATOR;
			$current_url = $uploadUrl . '/' . $_GET['path'] . '/';
			$current_dir_path = $_GET['path'];
			$moveup_dir_path = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
		}
		//排序形式，name or size or type
		$order = empty($_GET['order']) ? 'name' : strtolower($_GET['order']);
		
		//不允许使用..移动到上一级目录
		if (preg_match('/\.\./', $current_path)) {
			return 'Access is not allowed.';
		}
		//最后一个字符不是/
		if (!preg_match('/\\' . DIRECTORY_SEPARATOR . '$/', $current_path)) {
			return 'Parameter is not valid.';
		}
		//目录不存在或不是目录
		if (!file_exists($current_path) || !is_dir($current_path)) {
			return 'Directory does not exist.';
		}
		
		//遍历目录取得文件信息
		$file_list = array();
		
		$handle = new \DirectoryIterator($current_path);
		$i=0;
		foreach($handle as $file){
			if($file->isDot()) continue;
			if($file->isDir()){
				$file_list[$i]['is_dir'] = true; //是否文件夹
				$file_list[$i]['has_file'] = (count(scandir($file->getPath())) > 2); //文件夹是否包含文件
				$file_list[$i]['filesize'] = 0; //文件大小
				$file_list[$i]['is_photo'] = false; //是否图片
				$file_list[$i]['filetype'] = ''; //文件类别，用扩展名判断
			}else{
				$file_list[$i]['is_dir'] = false;
				$file_list[$i]['has_file'] = false;
				$file_list[$i]['filesize'] = $file->getSize();
				$file_list[$i]['dir_path'] = '';
				$file_ext = $file->getExtension();
				$file_list[$i]['is_photo'] = in_array($file_ext, $this->uploadExtension['image']);
				$file_list[$i]['filetype'] = $file_ext;
			}
			$file_list[$i]['filename'] = $file->getFilename(); //文件名，包含扩展名
			$file_list[$i]['datetime'] = date('Y-m-d H:i:s', $file->getMTime());
			$i++;
		}
		
		usort($file_list, array($this,'cmp_func'));

		$result = array();
		//相对于根目录的上一级目录
		$result['moveup_dir_path'] = $moveup_dir_path;
		//相对于根目录的当前目录
		$result['current_dir_path'] = $current_dir_path;
		//当前目录的URL
		$result['current_url'] = $current_url;
		//文件数
		$result['total_count'] = count($file_list);
		//文件列表数组
		$result['file_list'] = $file_list;
		
		//输出JSON字符串
		header('Content-type: application/json; charset=UTF-8');
		return Json::encode($result);
	}
	
	//排序
	public function cmp_func($a, $b) {
		global $order;
		if ($a['is_dir'] && !$b['is_dir']) {
			return -1;
		} else if (!$a['is_dir'] && $b['is_dir']) {
			return 1;
		} else {
			if ($order == 'size') {
				if ($a['filesize'] > $b['filesize']) {
					return 1;
				} else if ($a['filesize'] < $b['filesize']) {
					return -1;
				} else {
					return 0;
				}
			} else if ($order == 'type') {
				return strcmp($a['filetype'], $b['filetype']);
			} else {
				return strcmp($a['filename'], $b['filename']);
			}
		}
	}
}
?>