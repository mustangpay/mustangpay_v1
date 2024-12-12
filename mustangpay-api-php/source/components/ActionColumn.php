<?php

namespace app\components;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class ActionColumn extends \yii\grid\ActionColumn
{
    public $contentOptions = ['style' => 'padding: 7px;'];
    public $template = '{delete} {update}';
    public $item;
    public $containerOptions = ['class' => 'text-center'];

    public function init()
    {
        $this->header = Yii::t('app.admin', '操作');
        parent::init();
    }

    protected function initDefaultButtons()
    {
        $this->initDefaultButton('view', 'eye-open', [
            'class' => 'btn btn-detail',
        ]);
        $this->initDefaultButton('update', 'pencil', [
            'class' => 'btn btn-edit'
        ]);
        $this->initDefaultButton('ajax-update', 'pencil', [
            'class' => 'btn btn-edit'
        ]);
        $this->initDefaultButton('delete', 'trash', [
            'class' => 'btn btn-delete item-delete',
        ]);
    }

    protected function initDefaultButton($name, $iconName, $additionalOptions = [])
    {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model, $key) use ($name, $iconName, $additionalOptions) {
                switch ($name) {
                    case 'view':
                        $title = '查看';
                        break;
                    case 'ajax-update':
                    case 'update':
                        $title = '编辑';
                        break;
                    case 'delete':
                        $title = '删除';
                        break;
                    default:
                        $title = ucfirst($name);
                }
                $options = array_merge([
                    'title' => $title,
                    'aria-label' => $title,
                ], $additionalOptions, $this->buttonOptions);

                if(stripos($name, 'ajax') !== false) {
                    $options = array_merge($options, [
                        'data-target' => '#ajaxModalLg',
                        'data-toggle' => 'modal',
                    ]);
                }

                return Html::a($title, $url, $options);
            };
        }
    }

    public function createUrl($action, $model, $key, $index)
    {
        if (is_callable($this->urlCreator)) {
            return call_user_func($this->urlCreator, $action, $model, $key, $index, $this);
        }

        $params = is_array($key) ? $key : ['id' => (string)$key];

        $action = $this->item ? $this->item . '-' . $action : $action;
        $params[0] = $this->controller ? $this->controller . '/' . $action : $action;
        return Url::toRoute($params);
    }

    /**
     * {@inheritdoc}
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        return Html::tag('div', parent::renderDataCellContent($model, $key, $index), $this->containerOptions);
    }
}
