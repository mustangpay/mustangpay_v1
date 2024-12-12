<?php

namespace app\commands;

use app\components\BackUp;
use yii\console\Controller;
use yii\console\ExitCode;
use Yii;
use yii\console\widgets\Table;

class BackUpController extends Controller
{
    public $defaultAction = 'list';
    /** @var BackUp */
    public $backUp;

    public function init()
    {
        parent::init();
        $this->backUp = BackUp::getInstance();
    }

    private function getData()
    {
        $data = $this->backUp->list();
        $k = 1;
        foreach ($data as &$item) {
            unset($item['fullName']);
            $item = [
                'key' => $k++,
                'filename' => $item['filename'],
                'size' => Yii::$app->formatter->asShortSize($item['size']),
                'fileTime' => Yii::$app->formatter->asTime($item['fileTime']),
            ];
        }
        $data = count($data) > 0 ? array_combine(range(1, count($data)), $data) : $data;
        return $data;
    }

    public function actionList()
    {
        $table = new Table();
        echo $table->setHeaders(['#', '文件名', '文件大小', '建立时间'])->setRows($this->getData())->run();
        return ExitCode::OK;
    }

    public function actionCreate()
    {
        if ($this->backUp->create()) {
            echo ' >>> 数据库备份成功' . PHP_EOL;
            return ExitCode::OK;
        }
        echo ' >>> 数据库备份失败' . PHP_EOL;
        return ExitCode::OK;
    }

    public function actionDelete($id)
    {
        $data = $this->getData();
        $count = count($data);
        if ($id > $count || $id <= 0) {
            echo "id 参数有误";
            return ExitCode::OK;
        }
        $fileName = $data[$id]['filename'];
        if ($this->backUp->delete($fileName)) {
            echo "$fileName 备份删除成功";
            return ExitCode::OK;
        }
        echo "$fileName 备份删除失败";
        return ExitCode::OK;
    }
}
