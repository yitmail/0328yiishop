<?php
namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\web\Controller;

class GoodsIntroController extends Controller{
    public $layout=false;
    public function actionGoods($id){
        $goods=Goods::findOne(['id'=>$id]);
        $photos=GoodsGallery::find()->where(['goods_id'=>$id])->all();
//        var_dump($photos);exit;
        $intros=GoodsIntro::find()->where(['goods_id'=>$id])->all();
//        var_dump($model);exit;
//        var_dump($photos);exit;
        return $this->render('goods',['goods'=>$goods,'photos'=>$photos,'intros'=>$intros]);

    }
}