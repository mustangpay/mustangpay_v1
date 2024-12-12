<?php

namespace app\modules\admin\controllers;

use app\components\BackUp;
use app\helpers\ResultHelper;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Markdown;
use yii\helpers\Url;

/**
 * Class BackupController
 * @package app\modules\admin\controllers
 * @desc 数据库管理
 */
class BackupController extends BaseController
{
    /** @var BackUp */
    public $backUp;

    public function init()
    {
        parent::init();
        $this->backUp = BackUp::getInstance();
    }

    public function actionTables()
    {
        Url::remember();
        // 表列表
        $tableList = array_map('array_change_key_case', Yii::$app->db->createCommand('SHOW TABLE STATUS')->queryAll());
        $data = [];
        foreach ($tableList as $item) {
            $data[$item['name']] = $item;
        }
        return $this->render($this->action->id, [
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $data,
                'pagination' => false,
            ]),
        ]);
    }

    public function actionBackup()
    {
        Url::remember();
        return $this->render('backup', [
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $this->backUp->list(),
                'sort' => [
                    'attributes' => ['filename', 'size', 'fileTime'],
                ],
                'pagination' => false,
            ]),
        ]);
    }

    public function actionBackupCreate()
    {
        $tables = Yii::$app->request->post('tables', []);
//        if (!$tables) {
//            return ResultHelper::json(404, '请指定要备份的表！');
//        }
        if ($this->backUp->create($tables)) {
            return $this->success(Yii::t('app.admin', '备份成功'), Url::previous());
        }
        return $this->error(Yii::t('app.admin', '备份失败'), Url::previous());
    }

    public function actionBackupDelete($id)
    {
        if ($this->backUp->delete($id)) {
            return $this->success(Yii::t('app.admin', '删除成功'), Url::previous());
        }
        return $this->error(Yii::t('app.admin', '删除失败'), Url::previous());
    }

    public function actionBackupDownload($id)
    {
        return Yii::$app->response->sendFile($this->backUp->view($id), date('y-m-d') . '.sql');
    }

    public function actionBackupRestore($id)
    {
        if ($this->backUp->restore($id)) {
            return $this->success(Yii::t('app.admin', '还原成功'), Url::previous());
        }
        return $this->error(Yii::t('app.admin', '还原失败'), Url::previous());
    }

    /**
     * 优化表
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionOptimize()
    {
        $tables = Yii::$app->request->post('tables', '');
        if (!$tables) {
            return ResultHelper::json(404, '请指定要修复的表！');
        }

        // 判断是否是数组
        if (is_array($tables)) {
            $tables = implode('`,`', $tables);
            if (Yii::$app->db->createCommand("OPTIMIZE TABLE `{$tables}`")->queryAll()) {
                return ResultHelper::json(200, '数据表优化完成');
            }
            return ResultHelper::json(404, '数据表优化出错请重试！');
        }
        $list = Yii::$app->db->createCommand("OPTIMIZE TABLE `{$tables}`")->queryOne();
        // 判断是否成功
        if ($list['Msg_text'] == "OK") {
            return ResultHelper::json(200, "数据表'{$tables}'优化完成！");
        }
        return ResultHelper::json(404, "数据表'{$tables}'优化出错！错误信息:" . $list['Msg_text']);
    }

    /**
     * 修复表
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionRepair()
    {
        $tables = Yii::$app->request->post('tables', '');
        if (!$tables) {
            return ResultHelper::json(404, '请指定要修复的表！');
        }

        // 判断是否是数组
        if (is_array($tables)) {
            $tables = implode('`,`', $tables);
            if (Yii::$app->db->createCommand("REPAIR TABLE `{$tables}`")->queryAll()) {
                return ResultHelper::json(200, '数据表修复化完成');
            }

            return ResultHelper::json(404, '数据表修复出错请重试！');
        }

        $list = Yii::$app->db->createCommand("REPAIR TABLE `{$tables}`")->queryOne();
        if ($list['Msg_text'] == "OK") {
            return ResultHelper::json(200, "数据表'{$tables}'修复完成！");
        }

        return ResultHelper::json(404, "数据表'{$tables}'修复出错！错误信息:" . $list['Msg_text']);
    }

    /**
     * 数据字典
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionDictionary()
    {
        // 获取全部表结构信息
        $tableSchema = Yii::$app->db->schema->getTableSchemas();
        $tableSchema = ArrayHelper::toArray($tableSchema);

        // 获取全部表信息
        $tables = Yii::$app->db->createCommand('SHOW TABLE STATUS')->queryAll();
        $tables = array_map('array_change_key_case', $tables);


        $tableSchemas = [];
        foreach ($tableSchema as $item) {
            $key = $item['name'];

            $tableSchemas[$key]['table_name'] = $key;// 表名
            $tableSchemas[$key]['item'] = [];

            foreach ($item['columns'] as $column) {
                $tmpArr = [];
                $tmpArr['name'] = $column['name']; // 字段名称
                $tmpArr['type'] = $column['dbType']; // 类型
                $tmpArr['defaultValue'] = $column['defaultValue']; // 默认值
                $tmpArr['comment'] = $column['comment']; // 注释
                $tmpArr['isPrimaryKey'] = $column['isPrimaryKey']; // 是否主键
                $tmpArr['autoIncrement'] = $column['autoIncrement']; // 是否自动增长
                $tmpArr['unsigned'] = $column['unsigned']; // 是否无符号
                $tmpArr['allowNull'] = $column['allowNull']; // 是否允许为空

                $tableSchemas[$key]['item'][] = $tmpArr;
                unset($tmpArr);
            }
        }


        /*--------------- 开始生成 --------------*/
        $str = '';
        $i = 0;
        foreach ($tableSchemas as $key => $datum) {
            $table_comment = $tables[$i]['comment'];

            $str .= "### {$key}";
            if ($table_comment) {
                $str .= " ({$table_comment})";
            }
            $str .= PHP_EOL;
            $str .= "字段 | 类型 | 允许为空 | 默认值 | 字段说明" . PHP_EOL;
            $str .= "---|---|---|---|---" . PHP_EOL;

            foreach ($datum['item'] as $item) {
                empty($item['comment']) && $item['comment'] = "无";
                $item['allowNull'] = !empty($item['allowNull']) ? "是" : '否';
                is_array($item['defaultValue']) && $item['defaultValue'] = json_encode($item['defaultValue']);
                $str .= "{$item['name']} | {$item['type']} | {$item['allowNull']} | {$item['defaultValue']} | {$item['comment']}" . PHP_EOL;
            }

            $str .= PHP_EOL;
            $i++;
        }

        $file = $this->backUp->path . 'sql.dictionary.md';
        $fileStr = '';
        if (file_exists($file)) {
            $fileStr = file_get_contents($file);
        }
        //比较内容是否发生变化
        if ($this->compareStr($fileStr, $str)) {
            file_put_contents($file, $str);
        }
        return ResultHelper::json(200, '返回成功', ['str' => Markdown::process($str, 'extra')]);
    }

    /**
     * 比较字符串的不同
     *
     * @param $a
     * @param $b
     * @return string
     */
    private function compareStr($a, $b)
    {
        $a = explode("\n", $a);
        $b = explode("\n", $b);
        $diff = new \Diff($a, $b);
        $renderer = new \Diff_Renderer_Text_Context();
        return htmlspecialchars($diff->render($renderer));
    }
}