<?php
namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\components\SphinxClient;
use yii\helpers\ArrayHelper;
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
    //测试coreseek搜索
    public function actionTest(){

        $cl = new SphinxClient();
        $cl->SetServer ( '127.0.0.1', 9312);
//$cl->SetServer ( '10.6.0.6', 9312);
//$cl->SetServer ( '10.6.0.22', 9312);
//$cl->SetServer ( '10.8.8.2', 9312);
        // $cl->SetConnectTimeout ( 10 );
        $cl->SetArrayResult ( true );
// $cl->SetMatchMode ( SPH_MATCH_ANY);
        $cl->SetMatchMode ( SPH_MATCH_ALL);
        $cl->SetLimits(0, 1000);
        $info = '小米电视';
        $res = $cl->Query($info, 'goods');//shopstore_search
//print_r($cl);
        print_r($res);

    }
    //首页搜索
    public function actionSearch($keys){
//        $keys=\Yii::$app->request->get('keys');
        $cl = new SphinxClient();
        $cl->SetServer ( '127.0.0.1', 9312);
//$cl->SetServer ( '10.6.0.6', 9312);
//$cl->SetServer ( '10.6.0.22', 9312);
//$cl->SetServer ( '10.8.8.2', 9312);
        // $cl->SetConnectTimeout ( 10 );
        $cl->SetArrayResult ( true );
// $cl->SetMatchMode ( SPH_MATCH_ANY);
        $cl->SetMatchMode ( SPH_MATCH_ALL);
        $cl->SetLimits(0, 1000);
//      $info = '小米电视';
        $res = $cl->Query($keys, 'goods');//shopstore_search
//print_r($cl);
//        print_r($res);
        if(isset($res['matches'])){

            $ids=ArrayHelper::getColumn($res['matches'],'id');
            $model=Goods::find()->where(['in','id',$ids])->all();
        }else{
            $model=Goods::find()->where(['id'=>0])->all();
        }
//        var_dump($query);exit;
        return $this->render('/goods-list/list',['model'=>$model]);
    }

}