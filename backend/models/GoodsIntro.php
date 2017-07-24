<?php
namespace backend\models;

use yii\db\ActiveRecord;

class GoodsIntro extends ActiveRecord{
    public function rules()
    {
        return[
            ['content','required'],//商品详情不能为空
        ];
    }
    public function attributeLabels()
    {
        return[
            'content'=>'商品详情'
        ];
    }
}