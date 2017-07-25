<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Photo extends ActiveRecord{

    public function rules()
    {
        return [
            ['photo','required'],
        ];
    }
}