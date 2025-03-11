<?php

use yii\db\Migration;

/**
 * Class m210320_074122_base
 */
class m210320_074122_base extends Migration
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

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(50)->notNull()->unique()->comment('用户名'),
            'auth_key' => $this->string(32)->comment('cookie key'),
            'password_hash' => $this->string(128)->comment('登录密码'),
            'password_reset_token' => $this->string(128)->unique()->comment('重置token'),
            'access_token' => $this->string(128)->unique()->comment('api token'),
            'access_invalid_at' => $this->integer()->comment('api有效时间'),
            'mobile' => $this->string(15)->unique()->comment('手机'),
            'email' => $this->string(50)->unique()->comment('邮箱'),
            'avatar' => $this->string()->comment('头像'),
            'summary' => $this->string(1000)->comment('简介'),
            'nickname' => $this->string(50)->comment('昵称'),

            'open_id' => $this->string(128)->comment('OPEN ID'),
            'union_id' => $this->string(128)->comment('UNION ID'),
            'group' => $this->tinyInteger(1)->notNull()->comment('群组'),

            'status' => $this->tinyInteger(1)->notNull()->defaultValue(1)->comment('状态'),
            'is_deleted' => $this->tinyInteger(1)->notNull()->defaultValue(0)->comment('已删除'),
            'created_ip' => $this->string(45)->comment('注册IP'),
            'last_login_ip' => $this->string(45)->comment('最后登录IP'),
            'last_login_at' => $this->integer()->comment('最后登录时间'),
            'last_login_location' => $this->string(128)->comment('最后登录地点'),
            'count_login' => $this->integer()->defaultValue(0)->comment('登录次数'),
            'created_at' => $this->integer()->comment('创建时间'),
            'updated_at' => $this->integer()->comment('编辑时间'),
        ], $tableOptions . " COMMENT='用户表'");
        $this->createTable('{{%user_log}}', [
            'id' => $this->primaryKey(),
            'type' => $this->tinyInteger(1)->defaultValue(0)->comment('类别'),
            'user_id' => $this->integer()->notNull()->comment('用户ID'),
            'url' => $this->string()->comment('路由'),
            'content' => $this->text()->comment('内容'),
            'location' => $this->string()->comment('ip地址'),
            'created_ip' => $this->string(45)->comment('IP'),
            'created_at' => $this->integer()->comment('操作时间'),
        ], $tableOptions . " COMMENT='用户日志表'");
        $this->createTable('{{%visit}}', [
            'id' => $this->primaryKey(),
            'ip' => $this->string(15)->comment('IP'),
            'action' => $this->string(50)->comment('方法'),
            'query_string' => $this->string()->comment('query参数'),
            'post_data' => $this->text()->comment('POST数据'),
            'day' => $this->date()->comment('日期'),
            'created_at' => $this->integer(11)->comment('操作时间'),
        ], $tableOptions . " COMMENT='网站日志表'");
        $this->createTable('{{%param_category}}', [
            'id' => $this->primaryKey(),
            'order_by' => $this->integer()->defaultValue(100)->comment('排序'),
            'title' => $this->string()->comment('标题'),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(1)->comment('状态'),
            'created_at' => $this->integer()->comment('创建时间'),
            'updated_at' => $this->integer()->comment('编辑时间'),
        ], $tableOptions . " COMMENT='系统配置-分类表'");
        $this->createTable('{{%param}}', [
            'id' => $this->primaryKey(),
            'order_by' => $this->integer()->defaultValue(100)->comment('排序'),
            'code' => $this->string(50)->notNull()->comment('唯一标识')->unique(),
            'cate_id' => $this->tinyInteger(1)->comment('类别'),
            'name' => $this->string(50)->notNull()->comment('名称'),
            'hint' => $this->string()->comment('提示'),
            'value' => $this->string()->comment('内容'),
            'default_value' => $this->string()->comment('默认值'),
            'type' => $this->string(20)->comment('input类别'),
            'extra' => $this->string()->comment('额外配置'),
            'data' => $this->text()->comment('数据'),
            'status' => $this->tinyInteger(1)->defaultValue(1)->comment('状态'),
            'created_at' => $this->integer()->comment('创建时间'),
            'updated_at' => $this->integer()->comment('编辑时间'),
            'updated_by' => $this->integer()->comment('编辑者'),
        ], $tableOptions . " COMMENT='系统配置表'");
        $this->createTable('{{%page}}', [
            'id' => $this->primaryKey(),
            'order_by' => $this->integer()->defaultValue(100)->comment('排序'),
            'type' => $this->tinyInteger(1)->comment('类别'),
            'code' => $this->string(20)->notNull()->comment('唯一标识'),
            'title' => $this->string()->notNull()->comment('名称'),
            'summary' => $this->text()->comment('名称'),
            'content' => $this->text()->comment('名称'),
            'cover' => $this->string()->comment('图片'),
            'status' => $this->tinyInteger(1)->defaultValue(1)->comment('状态'),
            'created_at' => $this->integer(11)->comment('创建时间'),
            'updated_at' => $this->integer(11)->comment('编辑时间'),
        ], $tableOptions . " COMMENT='单页表'");
        $this->createTable('{{%seo}}', [
            'id' => $this->primaryKey(),
            'action' => $this->string()->comment('方法'),
            'order_by' => $this->integer()->defaultValue(100)->comment('排序'),
            'name' => $this->string()->comment('别名'),
            'title' => $this->string()->comment('标题'),
            'keywords' => $this->string()->comment('关键词'),
            'description' => $this->text()->comment('描述'),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(1)->comment('状态'),
            'created_at' => $this->integer()->comment('创建时间'),
            'updated_at' => $this->integer()->comment('编辑时间'),
        ], $tableOptions . " COMMENT='SEO表'");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%user_log}}');
        $this->dropTable('{{%visit}}');
        $this->dropTable('{{%param_category}}');
        $this->dropTable('{{%param}}');
        $this->dropTable('{{%page}}');
        $this->dropTable('{{%seo}}');
    }

}
