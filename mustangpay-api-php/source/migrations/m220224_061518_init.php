<?php

use yii\db\Migration;

/**
 * Class m220224_061518_init
 */
class m220224_061518_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer()->defaultValue(0)->comment('父级ID'),
            'level' => $this->smallInteger(1)->defaultValue(1)->comment('等级'),
            'order_by' => $this->integer()->defaultValue(100)->comment('排序'),
            'type' => $this->integer()->comment('类别'),
            'name' => $this->string()->comment('别名'),
            'title' => $this->string()->comment('标题'),
            'cover' => $this->string()->comment('图片'),
            'summary' => $this->text()->comment('描述'),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(1)->comment('状态'),
            'created_at' => $this->integer()->comment('创建时间'),
            'updated_at' => $this->integer()->comment('编辑时间'),
        ], $tableOptions . " COMMENT='分类表'");
        $this->createTable('{{%article}}', [
            'id' => $this->primaryKey(),
            'type' => $this->integer()->defaultValue(1)->comment('类型'),
            'category_id' => $this->integer()->defaultValue(0)->comment('分类'),
            'order_by' => $this->integer()->defaultValue(100)->comment('排序'),
            'title' => $this->string()->comment('标题'),
            'subtitle' => $this->string()->comment('副标题'),
            'publisher' => $this->string()->comment('作者'),
            'cover' => $this->string()->comment('图片'),
            'covers' => $this->text()->comment('多图'),
            'summary' => $this->text()->comment('描述'),
            'content' => $this->text()->comment('内容'),
            'tags' => $this->text()->comment('标签'),
            'views' => $this->integer()->defaultValue(0)->comment('查看数'),
            'url' => $this->string()->comment('链接'),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(1)->comment('状态'),
            'created_at' => $this->integer()->comment('创建时间'),
            'updated_at' => $this->integer()->comment('编辑时间'),
        ], $tableOptions . " COMMENT='内容表'");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%category}}');
        $this->dropTable('{{%article}}');
    }
}
