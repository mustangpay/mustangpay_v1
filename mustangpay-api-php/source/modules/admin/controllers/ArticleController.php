<?php

namespace app\modules\admin\controllers;

use app\models\Article;
use app\models\Category;
use app\models\Page;

/**
 * Class ArticleController
 * @package app\modules\admin\controllers
 * @desc 内容管理
 */
class ArticleController extends BaseController
{
    public function actionCategory()
    {
        return $this->index(Category::class);
    }

    public function actionCategoryCreate()
    {
        return $this->create(Category::class);
    }

    public function actionCategoryUpdate($id)
    {
        return $this->update(Category::class, $id);
    }

    public function actionCategoryAjaxUpdate($id)
    {
        return $this->ajaxUpdate(Category::class, $id);
    }

    public function actionCategoryView($id)
    {
        return $this->view(Category::class, $id);
    }

    public function actionCategoryDelete($id)
    {
        return $this->delete(Category::class, $id);
    }

    public function actionNews()
    {
        $sort = ['type' => Article::TYPE_NEWS];
        return $this->index(Article::class, $sort);
    }

    public function actionNewsCreate()
    {
        return $this->create(Article::class, ['type' => Article::TYPE_NEWS]);
    }

    public function actionNewsUpdate($id)
    {
        return $this->update(Article::class, $id);
    }

    public function actionNewsDelete($id)
    {
        return $this->delete(Article::class, $id);
    }

    public function actionPage()
    {
        return $this->index(Page::class);
    }

    public function actionPageCreate()
    {
        return $this->create(Page::class);
    }

    public function actionPageUpdate($id)
    {
        return $this->update(Page::class, $id);
    }

    public function actionPageDelete($id)
    {
        return $this->delete(Page::class, $id);
    }


}

?>
