<?php
namespace app\components\behaviors;

use yii\base\Behavior;
use yii\base\InvalidArgumentException;
use yii\db\ActiveRecord;
use Yii;
use yii\db\Migration;


/**
 * public function behaviors()
 * {
 *      return array_merge(parent::behaviors() , [
 *          'language' => [
 *              'class' => LanguageBehavior::class,
 *              'table' => 'article',
 *              'fields' => ['type' , 'title' , 'status'],
 *          ]
 *      ]);
 * }
 *
 *
 * Class LanguageBehavior
 * @package app\components\behaviors
 */
class LanguageBehavior extends Behavior
{
    public $table;
    public $fields;
    public $defaultFields = ['order_by' ,  'title' , 'summary'];
    public function init()
    {
        if(empty($this->table)){
            throw new InvalidArgumentException("table not set");
        }
        if(empty($this->fields)){
            throw new InvalidArgumentException("fields not set");
        }
        if(!is_array($this->fields)){
            throw new InvalidArgumentException("fields must be array");
        }
        $this->fields = array_merge($this->defaultFields , $this->fields);
    }

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'insert',
            ActiveRecord::EVENT_AFTER_DELETE => 'delete',
        ];
    }
    public function insert($event)
    {
        $tablePrefixAll = ['tsj_','tsj_en_'];
        $tablePrefix = array_diff($tablePrefixAll , [Yii::$app->db->tablePrefix]);
        $model = $event->sender;
        $tmp = Yii::$app->db->tablePrefix;
        foreach ($tablePrefix as $v){
            Yii::$app->db->tablePrefix = $v;

            $m = new Migration();
            $data = array_intersect_key($model->attributes , array_flip($this->fields));
            $data['status'] = STATUS_INACTIVE;
            $data['id'] = $model->id;
            $m->insert($v . $this->table , $data);
        }
        Yii::$app->db->tablePrefix = $tmp;
    }
    public function delete($event)
    {
        $model = $event->sender;
        $tmp = Yii::$app->db->tablePrefix;
        $tablePrefixAll = ['tsj_','tsj_en_'];

        $tablePrefix = array_diff($tablePrefixAll , [Yii::$app->db->tablePrefix]);

        foreach ($tablePrefix as $v){
            Yii::$app->db->tablePrefix = $v;
            $m = new Migration();
            $m->delete($v . $this->table , ['id' => $model->id]);
        }

        Yii::$app->db->tablePrefix = $tmp;
    }
}