<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170728_023319_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            //菜单名称
            'label'=>$this->string()->comment('菜单名称'),
            //上级菜单
            'parent_id'=>$this->integer()->comment('上级菜单'),
            //地址/路由
            'url'=>$this->string()->comment('地址/路由'),
            //排序
            'sort'=>$this->integer()->comment('排序'),

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
