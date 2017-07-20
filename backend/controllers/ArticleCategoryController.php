<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Request;

class ArticleCategoryController extends \yii\web\Controller
{
    //展示文章分类列表
    public function actionIndex()
    {
        //分页 总条数 每页显示条数  当前是第几条
        $query=ArticleCategory::find()->where(['>','status',-1]);
        //统计总条数
        $total=$query->count();
        //每页显示3条
        $perPage=3;
        //分页工具类
        $pager=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$perPage
        ]);
        //根据条件查找相应数据
        $models=$query->limit($pager->limit)->offset($pager->offset)->orderBy('sort desc')->all();
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }
    //添加文章分类
    public function actionAdd(){
        //实例化数据模型
        $model=new ArticleCategory();
        //接收表单提交的数据，并保存到数据表
        $request=new Request();
        //判断提交方式
        if($request->isPost){
            //加载表单数据
            $model->load($request->post());
            //验证数据
            if($model->validate()){
                //验证成功
                $model->save();
                //添加成功后，提示
                \Yii::$app->session->setFlash('success','文章分类添加成功');
                //跳转到列表页
                return $this->redirect(['article-category/index']);
            }else{
                //验证失败，打印错误信息
                var_dump($model->getErrors());exit;
            }

        }
        //调用视图，并传值
        return $this->render('add',['model'=>$model]);
    }
    //修改文章分类
    public function actionEdit($id){
        //根据id获取一条数据
        $model=ArticleCategory::findOne(['id'=>$id]);
        //接收表单提交的数据，并保存到数据表
        $request=new Request();
        //判断提交方式
        if($request->isPost){
            //加载表单数据
            $model->load($request->post());
            //验证数据
            if($model->validate()){
                //验证成功
                $model->save();
                //修改成功后，提示
                \Yii::$app->session->setFlash('success','文章分类修改成功');
                //跳转到列表页
                return $this->redirect(['article-category/index']);
            }else{
                //验证失败，打印错误信息
                var_dump($model->getErrors());exit;
            }

        }
        //调用视图，并传值
        return $this->render('add',['model'=>$model]);
    }
    //删除文章分类
    public function actionDelete($id){
        //根据id查询一条数据
        $model=ArticleCategory::findOne(['id'=>$id]);
        //将查询到的状态，改为-1
        $model->status=-1;
        $model->save();
        //删除成功后，提示
        \Yii::$app->session->setFlash('success','文章分类删除成功');
        //跳转到列表页
        return $this->redirect(['article-category/index']);
    }

}
