<?php

use yii\db\Migration;

class m170803_083748_alter_goods_table extends Migration
{
    public function safeUp()
    {
        $this->execute('alter table goods engine=innodb');
    }

    public function safeDown()
    {
        echo "m170803_083748_alter_goods_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170803_083748_alter_goods_table cannot be reverted.\n";

        return false;
    }
    */
}
