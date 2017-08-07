<?php
namespace frontend\controllers;

use backend\models\GoodsCategory;
use yii\web\Controller;

class GoodsCategoryController extends Controller{
    public $layout=false;
    //查询所有一级分类
    public function actionIndex(){
        $first=GoodsCategory::find()->where(['parent_id'=>0])->all();
//        var_dump($first);exit;
//        return $this->render('index',['first'=>$first]);
        $contents=$this->render('index',['first'=>$first]);
        file_put_contents('index.html',$contents);
    }
}