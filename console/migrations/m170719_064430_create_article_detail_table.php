<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_detail`.
 */
class m170719_064430_create_article_detail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_detail', [
//            article_id	primaryKey	文章id
            'article_id' => $this->primaryKey()->comment('文章id'),
//            content	text	简介
            'content'=>$this->text()->comment('简介'),
        ],'ENGINE=INNODB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_detail');
    }
}
