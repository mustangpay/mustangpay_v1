<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%param}}".
 *
 * @property int $id
 * @property int|null $order_by 排序
 * @property string $code 唯一标识
 * @property int|null $cate_id 类别
 * @property string $name 名称
 * @property string|null $hint 提示
 * @property string|null $value 内容
 * @property string|null $default_value 默认值
 * @property string|null $type input类别
 * @property string|null $extra 额外配置
 * @property string|null $data 数据
 * @property int|null $status 状态
 * @property int|null $created_at 创建时间
 * @property int|null $updated_at 编辑时间
 * @property int|null $updated_by 编辑者
 *
 * @property ParamCategory $category 类型
 */
class Param extends BaseActiveRecord
{
    const CACHE_KEY = 'model.param';
    const CACHE_TIME = 120;


    public static function getInputList()
    {
        return [
            'input' => '普通文本框',
            'password' => '密码框',
            'secretKeyText' => '密钥文本框',
            'radio' => '单选',
            'file' => '文件',
            'textarea' => '多行文本框',
            'kinder' => '富文本',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%param}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['cate_id', 'status', 'order_by', 'created_at', 'updated_at', 'updated_by'], 'integer'],
            [['code', 'name'], 'string', 'max' => 50],
            [['hint', 'value', 'default_value', 'extra'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 20],
            [['code'], 'unique'],
            [['value'], 'safe'],
            [['data'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_by' => '排序',
            'code' => '唯一标识',
            'cate_id' => '类别',
            'name' => '名称',
            'hint' => '提示',
            'value' => '内容',
            'default_value' => '默认值',
            'type' => 'input类别',
            'typeName' => 'input类别',
            'extra' => '额外配置',
            'data' => '数据',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '编辑时间',
            'updated_by' => '编辑者',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(ParamCategory::class, ['id' => 'cate_id']);
    }

    public function getTypeName()
    {
        return ArrayHelper::getValue(self::getInputList(), $this->type);
    }

    public static function findValue($name)
    {
        /** @var  $model self */
        $model = Param::find()->where(['code' => $name])->one();
        if (!$model) {
            return '';
        }
        if (in_array($model->type, ['textarea', 'kinder'])) {
            return $model->data;
        }
        return $model->value;
    }

    public function beforeSave($insert)
    {
        if (in_array($this->type, ['checkboxList'])) {
            $this->data = is_array($this->data) ? Json::encode($this->data) : $this->data;
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        self::deleteCache();
    }

    public static function getValue($name, $defaultValue = null)
    {
        $params = self::listData();
        return ArrayHelper::getValue($params, $name, $defaultValue);
    }

    public static function setValue($id, $value)
    {
        $model = self::findOne(['id' => $id]);
        if ($model) {
            $model->value = strval($value);
            if (!$model->save()) {
                Yii::error($model->errors);
            }
        }
    }

    public static function listData($cate_id = null)
    {
        $models = Yii::$app->cache->get(self::CACHE_KEY);
        if ($models === false) {
            $models = self::find()
                ->select(['code', 'value', 'cate_id'])
                ->where(['status' => STATUS_ACTIVE])
                ->andFilterWhere(['cate_id' => $cate_id])
                ->asArray()
                ->all();
            Yii::$app->cache->set(self::CACHE_KEY, $models, self::CACHE_TIME);
        }
        return $models;
    }

    public static function listMapData($cate_id = null)
    {
        return ArrayHelper::map(array_filter(self::listData(), function ($row) use ($cate_id) {
            return empty($cate_id) || $row['cate_id'] == $cate_id;
        }), 'code', 'value');
    }

    public static function deleteCache()
    {
        Yii::$app->cache->delete(self::CACHE_KEY);
    }
}
