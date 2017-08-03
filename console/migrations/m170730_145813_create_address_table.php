<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170730_145813_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),

            'name'=>$this->string(50)->comment('收货人'),
            'province'=>$this->integer()->comment('省'),
            'city'=>$this->integer()->comment('城市'),
            'area'=>$this->integer()->comment('区县'),
            'address'=>$this->string()->comment('详细地址'),
            'tel'=>$this->integer()->comment('手机'),
            'status'=>$this->integer()->comment('状态'),
            'member_id'=>$this->integer()->comment('用户id'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
