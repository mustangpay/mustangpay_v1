<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\searchs\UserSearch;
use yii\helpers\Url;

/**
 * Class MemberController
 * @package app\modules\admin\controllers
 *
 * 用户管理
 */
class MemberController extends BaseController
{
    // 用户管理  Model  -- User
    public function actionMember()
    {
        Url::remember();
        $model = new UserSearch();
//        $model->group = [UserSearch::GROUP_MEMBER];
        return $this->render($this->action->id, [
            'model' => $model,
            'dataProvider' => $model->search(Yii::$app->request->queryParams),
        ]);
    }

    public function actionMemberCreate()
    {
        return $this->create(UserSearch::class);
    }

    public function actionMemberUpdate($id)
    {
        return $this->update(UserSearch::class, $id);
    }

    public function actionMemberDelete($id)
    {
        $model = UserSearch::findOne($id);
        if (!$model) {
            return $this->error('资料不存在');
        }
        if ($model->group == UserSearch::GROUP_ADMIN) {
            return $this->error('管理员用户不能删除', Url::previous());
        }

        if ($model->delete()) {
            return $this->redirect(Url::previous());
        } else {
            return $this->error('删除失败:' . implode('|', $model->firstErrors));
        }
    }

}