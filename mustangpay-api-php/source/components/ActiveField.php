<?php
/**
 * Created by zsz
 * User zsz
 * Time: 2023/12/20 10:13
 */

namespace app\components;

use app\widgets\tagsinput\TagsinputWidget;
use app\widgets\ZDateTimePicker\ZDateTimePicker;
use app\widgets\ZKindEditor\ZKindEditor;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use Yii;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\InputWidget;

class ActiveField extends \yii\widgets\ActiveField
{
    public function text($options = [])
    {
        $options = array_merge($this->inputOptions, $options);

        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);
        if (isset($options['tag'])) {
            $tag = $options['tag'];
            unset($options['tag']);
        } else {
            $tag = 'div';
        }
        $content = array_key_exists('content', $options) ? $options['content'] : $this->model->{$this->attribute};
        if (isset($options['encode'])) {
            $content = Html::encode($content);
        }
        if (!isset($options['style'])) {
            $options['style'] = 'background-color:#f1f1f1;';
        }
        $this->parts['{input}'] = $tag ? Html::tag($tag, $content, $options) : $content;

        return $this;
    }

    public function fileInput($options = [])
    {
        $this->parts['{input}'] = Html::tag('div', Html::label(Yii::t('app.admin', '文件上传')) . Html::activeFileInput($this->model, $this->attribute, $options), [
            'class' => 'file-upload'
        ]);
        $this->form->view->registerCss(<<<CSS
.file-upload{
    display: inline-block; 				/* 块元素转换为行内元素 */
    position: relative; 				/* 父元素使用相对定位, 方便子元素进行绝对定位 */
    width: 80px; 						/* 设置元素宽度, 子元素要保持一致 */
    height: 30px; 						/* 设置元素高度,子元素要保持一致 */
    line-height: 30px; 					/* 保证label标签内容垂直居中 */
    text-align: center; 				/* 保证label标签内容水平居中 */
    border-radius: 4px; 				/* 圆角样式 */
    color: skyblue; 					/* 字体颜色 */
    border: 1px solid deepskyblue; 		/* 边框 */
}
.file-upload input{
    position: absolute; 				/* 子元素绝对定位 */
    top:0;
    left:0;
    width: 80px;
    height: 30px;
    opacity: 0; 						/* 设置透明 */
}

.img-preview {
 height: 200px;
}
CSS
        );
        return $this;
    }

    public function imgInput($options = [])
    {
        $options = array_merge(['id' => 'input'], $options);
        $val = Html::getAttributeValue($this->model, $this->attribute);
        $size = '';
        if (isset($options['size'])) {
            $size = Html::tag('p', '推荐尺寸：' . $options['size'], ['id' => 'size', 'data-size' => $options['size']]);
            unset($options['size']);
        }

        $preview = '';
        $list = $val ? explode(';', $val) : [];
        foreach ($list as $k => $v) {
            $preview .= Html::tag('div', Html::img($v) . Html::a('删除', 'javascript:;', ['class' => 'btn img-delete', 'data-key' => $k]), ['class' => 'img-preview']);
        }
        $listJson = json_encode($list);
        $this->parts['{input}'] = Html::tag('div', Html::tag('div', Html::activeHiddenInput($this->model, $this->attribute, $options) . Html::button('上传图片', ['class' => 'btn btn-info upload-btn']), ['class' => 'upload-box']) . $size . Html::tag('div', $preview, ['class' => 'img-list']), ['class' => 'img-box']);
        $view = $this->form->view;
        $uploadUrl = Url::to(['/file/images']);
        $imgExts = implode('|', Yii::$app->params['uploadConfig']['images']['extensions']);
        $view->registerCss(<<<CSS
.img-preview img {
    height: 200px;
}
CSS
        );
        $view->registerJs(<<<JS
var list = $listJson;
console.log(list)
layui.use(['form', 'upload'], function() {
var upload = layui.upload;
//图片上传
upload.render({
     //绑定元素
    elem: '.upload-btn',
    //上传接口
    url: "$uploadUrl",
    data: {
      size: function() {
        return "$size";
      },
    },
    exts: "$imgExts",
    done: function(res) {
        if(res.code == 200) {
          layer.msg(res.message, {icon: 6});
          this.item.parent().find('input#input').val(res.data.url);
          // this.item.parent().parent().find('a.fancybox').attr('href', res.data.url);
          // this.item.parent().parent().find('.img-preview').remove('img');
          this.item.parent().parent().find('.img-list').append("<div class='img-preview'> <img src='" + res.data.url + "' /></div>");
          return
        }
        layer.msg(res.message, {icon: 5})
    },
    error: function() {
        layer.msg('服务器异常', {icon: 5})
    }
});

$('.img-list').on('click', '.img-delete', function() {
  console.log(222)
    var that = $(this);
    var dlg = layer.confirm('确认要删除吗？', function () {
        layer.close(dlg);
        delete list[that.data('key')];
        console.log(that.parent('.img-box').find('.upload-box'))
        that.parent('.img-box').find('input#input').val(list.join(';'));
        that.parent('.img-preview').remove();
        console.log(list.join(';'));
    });
})
})
JS
            , View::POS_END, 'imgUpload');
        return $this;
    }

    //加载 widget
    private function renderWidget($options = [])
    {
        $config['model'] = $this->model;
        $config['attribute'] = $this->attribute;
        $config['view'] = $this->form->getView();
        foreach ($this->inputOptions as $key => $value) {
            if (!isset($config['options'][$key])) {
                $config['options'][$key] = $value;
            }
        }
        $config['options'] = array_merge($config['options'], $options);

        if ($this->form->validationStateOn === ActiveForm::VALIDATION_STATE_ON_INPUT) {
            $this->addErrorClassIfNeeded($config['options']);
        }

        $this->addAriaAttributes($config['options']);
        $this->adjustLabelFor($config['options']);
        $config = array_merge($config, $options);

        $class = ArrayHelper::getValue($config, 'class', InputWidget::class);
        $this->parts['{input}'] = $class::widget($config);
        return $this;
    }

    //富文本
    public function fullEditor($options = [], $clientOptions = [])
    {
        $this->renderWidget([
            'class' => ZKindEditor::class,
            'options' => array_merge([
                'item_style' => ZKindEditor::STYLE_SIMPLE,
            ], $options),
            'clientOptions' => $clientOptions,
        ]);
        return $this;
    }

    //时间
    public function datetimeInput($options = [], $clientOptions = [])
    {
        $this->renderWidget([
            'class' => ZDateTimePicker::class,
            'options' => array_merge([
                'class' => 'form-control',
            ], $options),
            'clientOptions' => $clientOptions,
        ]);

        return $this;
    }

    //标签
    public function tagsInput($options = [], $clientOptions = [])
    {
        $this->renderWidget([
            'class' => TagsinputWidget::class,
            'field' => $this,
            'options' => $options,
            'clientOptions' => $clientOptions,
        ]);
        return $this;
    }
}
