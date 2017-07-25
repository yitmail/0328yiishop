<?php
namespace backend\controllers;

use backend\models\GoodsGallery;
use yii\web\Controller;

class PhotoController extends Controller{

    public function actionAdd(){
        $model=new GoodsGallery();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            return $this->redirect(['photo/add']);
        }
        return $this->render('add',['model'=>$model]);

    }
    public function actionIndex(){
        $models=GoodsGallery::find()->all();
        return $this->render('add',['models'=>$models]);
    }
}