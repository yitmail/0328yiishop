<?php
namespace frontend\models;

use yii\db\ActiveRecord;
use yii\helpers\Json;

class City extends ActiveRecord{
    //获取城市数据
    public static function getCity($id){
        $city=self::find()->where(['parent_id'=>$id])->asArray()->all();
        return Json::encode($city);
    }
}