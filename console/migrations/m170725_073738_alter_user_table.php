<?php

use yii\db\Migration;

class m170725_073738_alter_user_table extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('user','last_login_ip','integer');
    }

    public function safeDown()
    {
        echo "m170725_073738_alter_user_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170725_073738_alter_user_table cannot be reverted.\n";

        return false;
    }
    */
}
