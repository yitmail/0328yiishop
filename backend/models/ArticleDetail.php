<?php
namespace backend\models;



use yii\db\ActiveRecord;

class ArticleDetail extends ActiveRecord{

    public function rules()
    {
        return[
            ['content','required'],//内容不能为空
        ];
    }
    public function attributeLabels()
    {
        return[
            'content'=>'内容'
        ];
    }
}