<?php

namespace app\modules\admin\controllers;

use app\helpers\Helper;
use app\helpers\ResultHelper;
use app\modules\admin\services\AdminMenu;
use app\traits\msg\SessionMsg;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;

/**
 * Class BaseController
 * @package app\modules\admin\controllers
 * @desc 基类控制器
 */
class BaseController extends \app\controllers\BaseController
{
    public $user;
    public $userId;
    public $layout = 'admin';
    use SessionMsg;

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'user' => 'user',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ]);
    }

    protected function index($modelClass, $sort = [], $view = null)
    {
        Url::remember();
        /** @var  $query Query */
        $query = $modelClass::find();
        if ($sort) {
            $query = $query->andWhere($sort);
        }
        $query = $query->orderBy('order_by DESC,id DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
            'sort' => false,  //关闭筛选
        ]);
        return $this->render($view ?: $this->action->id, [
            'dataProvider' => $dataProvider,
        ]);
    }

    protected function create($modelClass, $extend = [], $view = null)
    {
        $model = new $modelClass;
        $model->loadDefaultValues();
        if (!empty($extend)) {
            $model->setAttributes($extend);
        }
        if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->success(Yii::t('app.admin', '创建成功'), Url::previous());
            }
        }
        $file = str_replace('create', 'edit', $this->action->id);
        return $this->render($view ?: $file, [
            'model' => $model,
        ]);
    }

    protected function update($modelClass, $id, $view = null)
    {
        $model = $modelClass::findOne($id);
        if (!$model) {
            return $this->error(Yii::t('app.admin', '记录不存在！'));
        }
        if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->success(Yii::t('app.admin', '编辑成功'), Url::previous());
            }
        }
        $file = str_replace('update', 'edit', $this->action->id);
        return $this->render($view ?: $file, [
            'model' => $model,
        ]);
    }

    protected function ajaxUpdate($modelClass, $id, $view = null)
    {
        $model = $modelClass::findOne($id);
        if (!$model) {
            return $this->error(Yii::t('app.admin', '记录不存在！'), $this->redirect(Yii::$app->request->referrer));
        }
        if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->success(Yii::t('app.admin', '编辑成功'), $this->redirect(Yii::$app->request->referrer));
            }
            return $this->error(Helper::analysisErr($model->getFirstErrors()), $this->redirect(Yii::$app->request->referrer));
        }
        $file = str_replace('update', 'edit', $this->action->id);
        return $this->renderAjax($view ?: $file, [
            'model' => $model,
        ]);
    }

    protected function view($modelClass, $id, $view = null)
    {
        $model = $modelClass::findOne($id);
        if (!$model) {
            return $this->error(Yii::t('app.admin', '记录不存在！'));
        }
        return $this->render($view ?: $this->action->id, [
            'model' => $model,
        ]);
    }


    protected function delete($modelClass, $id)
    {
        $model = $modelClass::findOne($id);
        if (!$model) {
            return $this->error(Yii::t('app.admin', '资料不存在！'));
        }
        if ($model->delete()) {
            return $this->success(Yii::t('app.admin', '删除成功'));
        } else {
            return $this->error(Yii::t('app.admin', '删除失败:{error}', ['error' => Helper::analysisErr($model->firstErrors)]));
        }
    }


    public function beforeAction($action)
    {
        if ($action->id == 'upload' && Yii::$app->user->isGuest) {
            Yii::$app->request->enableCsrfValidation = false;
        }

        Yii::$container->set('yii\grid\GridView', [
            'layout' => "{items}\n{pager}",
        ]);

        Yii::$container->set('yii\grid\DataColumn', [
            'filterInputOptions' => ['class' => 'form-control input-sm', 'id' => null, 'prompt' => '全部'],
        ]);

        Yii::$container->set('app\components\ListColumn', [
            'filterInputOptions' => ['class' => 'form-control input-sm', 'id' => null, 'prompt' => '全部'],
        ]);

        Yii::$container->set('app\components\ActiveField', [
            'options' => ['tag' => 'tr'],
            'template' => "<th>{label}</th>\n<td>{input}\n{hint}\n{error}</td>"
        ]);

        Yii::$container->set('yii\widgets\ActiveField', [
            'class' => 'app\components\ActiveField',
        ]);

        Yii::$container->set('yii\grid\GridView', [
            'layout' => "{items}\n<div class='pages'>{pager}\n<span>{summary}</span></div>",
            'summary' => Yii::t('app.admin', '共有 {totalCount} 条记录'),
            'pager' => [
                'firstPageLabel' => Yii::t('app.admin', '第一页'),
                'prevPageLabel' => Yii::t('app.admin', '上一页'),
                'nextPageLabel' => Yii::t('app.admin', '下一页'),
                'lastPageLabel' => Yii::t('app.admin', '最后一页'),
                'disabledPageCssClass' => 'dn',
            ],
        ]);

        Yii::$container->set('yii\i18n\Formatter', [
            'nullDisplay' => '',
        ]);
        if (!parent::beforeAction($action)) {
            return false;
        }
        $this->user = Yii::$app->user->identity;
        $this->userId = Yii::$app->user->id;
        Yii::$app->params['leftMenus'] = AdminMenu::listMenuByUser();

        $urls = [
            'admin/param/param-category',
            'admin/param/param-category-create',
            'admin/param/param-category-update',
            'admin/param/param-category-delete',
            'admin/seo/seo',
            'admin/seo/seo-create',
            'admin/seo/seo-update',
            'admin/seo/seo-delete',
            'admin/backup/backup',
            'admin/backup/backup-tables',
            'admin/backup/backup-create',
            'admin/backup/backup-delete',
            'admin/backup/backup-download',
            'admin/backup/backup-restore',
            'admin/backup/backup-optimize',
            'admin/backup/backup-repair',
            'admin/backup/backup-dictionary',
        ];
        if (!YII_DEBUG && in_array($this->route, $urls)) {
            throw new ForbiddenHttpException('非开发模式不可用！');
        }
        return true;
    }

}
