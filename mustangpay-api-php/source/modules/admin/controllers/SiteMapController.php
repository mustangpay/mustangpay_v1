<?php

namespace app\modules\admin\controllers;

use app\models\Category;
use app\models\Article;
use app\models\Seo;
use Yii;
use yii\helpers\Url;

/**
 * Class SiteMapController
 * @package app\modules\admin\controllers
 * @desc 站点地图
 */
class SiteMapController extends BaseController
{
    //站点地图
    public function actionIndex()
    {
        Url::remember();
        if (Yii::$app->request->isPost) {
            $urls = ['site/news-detail'];
            $urlArr = $this->generateLinks($urls);
            //分类
            $categories = Category::find()
                ->select('id')
                ->where(['status' => STATUS_ACTIVE, 'name' => 'news'])
                ->column();
            foreach ($categories as $category) {
                $urlArr[] = [Url::toRoute(['/site/news', 'type' => $category], true), 0.9, 'time' => time()];
            }
            $categories = Category::find()
                ->select('id')
                ->where(['name' => 'product', 'status' => STATUS_ACTIVE])
                ->column();
            foreach ($categories as $category) {
                $urlArr[] = [Url::toRoute(['/site/product', 'type' => $category], true), 0.9, 'time' => time()];
            }


            $key = 'controller.site.sitemap';
            $cache = Yii::$app->cache;
//            if ($cache->get($key) === false) {
            $urlArr = array_merge(self::fetchRules(), $urlArr);
            $this->generateSitemapTxt($urlArr);
            $this->generateSitemapXml($urlArr);
            $cache->set($key, 1, 3600);
//            }
            return $this->success('更新成功');
        }
        return $this->render('index');
    }

    private function generateLinks($ignoreUrls = [])
    {
        $links = [];
        //单页链接
        $linksObj = Seo::find()
            ->select('action')
            ->andFilterWhere(['NOT IN', 'action', $ignoreUrls])
            ->orderBy('order_by DESC')
            ->all();

        foreach ($linksObj as $linkObj) {
            $links[] = [Url::toRoute('/' . $linkObj->action, true), '0.9', 'time' => date('Y-m-d', time())];
        }

        return $links;
    }

    //产品 新闻 详情路由
    private static function fetchRules()
    {
        $result = [];

        $models = Article::find()
            ->select('id,created_at')
            ->where(['status' => 1])
            ->all();
        foreach ($models as $model) {
            $result[] = [Url::toRoute(['/site/news-detail', 'id' => $model->id], true), 'time' => $model->created_at];
        }
        //为了首页排最前
        $res = [
            [Url::toRoute('/site/index', true), '1.0', 'time' => date('Y-m-d', time())]
        ];
        foreach ($result as $item) {
            if (isset($item['time'])) {
                $res[] = [$item[0], '0.8', 'time' => $item['time']];
            } else {
                $res[] = [$item, '0.8', 'time' => date('Y-m-d', time())];
            }
        }

        return $res;
    }

    private function generateSitemapTxt($urls)
    {
        $file = Yii::getAlias('@webroot/sitemap.txt');
        file_put_contents($file, '');
        foreach ($urls as $v) {
            file_put_contents($file, $v[0] . PHP_EOL, FILE_APPEND);
        }
    }

    private function generateSitemapXml($urls)
    {
        $file = Yii::getAlias('@webroot/sitemap.xml');
        $content = $this->renderPartial('template', [
            'urls' => $urls,
        ]);
        file_put_contents($file, $content);
    }
}

?>
