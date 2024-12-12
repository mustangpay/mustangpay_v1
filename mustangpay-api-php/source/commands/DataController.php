<?php

namespace app\commands;

use app\controllers\SiteController;
use app\helpers\Helper;
use app\models\Param;
use app\models\Seo;
use yii\console\Controller;
use yii\db\Migration;
use Yii;

class DataController extends Controller
{
    public $defaultAction = 'all';
    /** @var Migration $m */
    public $m;

    public function init()
    {
        $this->m = $this->m ?: new Migration();
        parent::init();
    }

    public function actionAll()
    {
        $this->actionUser();
        $this->actionParam();
        $this->actionSeo();
    }

    public function actionUser()
    {
        $timestamp = time();
        $this->m->insert('{{%user}}', [
            'username' => 'admin',
            'auth_key' => Yii::$app->getSecurity()->generateRandomString(),
            'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('112233'),
            'nickname' => '管理员',
            'group' => 9,
            'created_at' => $timestamp,
            'status' => STATUS_ACTIVE,
        ]);
    }

    public function actionParam()
    {
        $this->m->truncateTable(Param::tableName());
        $data = require_once Yii::getAlias('@runtime/data/param.php');
        $model = new Param();
        foreach ($data as $item) {
            $_model = clone $model;
            $_model->setAttributes($item);
            $_model->save();
        }
    }

    public function actionSeo()
    {
        $ctl = new SiteController($this->id, $this->module);
        $class = new \ReflectionClass($ctl);

        $this->m->truncateTable(Seo::tableName());
        $model = new Seo();
        foreach ($class->getMethods() as $method) {
            $name = $method->getName();
            if ($name === 'actions') {
                continue;
            }
            if ($method->isPublic() && !$method->isStatic() && strncmp($name, 'action', 6) === 0) {
                $name = strtolower(substr(Helper::transName($name), 7));
                if (in_array($name, ['index', 'stop', 'login', 'logout'])) continue;
                $_model = clone $model;
                $_model->attributes = [
                    'action' => 'site/' . $name,
                    'name' => $name,
                    'title' => '{title}'
                ];
                $_model->save();
            }
        }
    }

}
