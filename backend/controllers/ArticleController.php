<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Request;

class ArticleController extends \yii\web\Controller
{
    //展示文章列表
    public function actionIndex($keywords='')
    {
        //分页 总条数 每页显示条数  当前第几条
        $query=Article::find()->where(['and','status>-1',"name like '%{$keywords}%'"]);
        //统计条数
        $total=$query->count();
        //每页显示3条
        $perPage=3;
        //实例化分页工具类
        $pager=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$perPage
        ]);
        //获取符合条件的文章
        $articles=$query->limit($pager->limit)->offset($pager->offset)->orderBy('sort desc')->all();
        return $this->render('index',['articles'=>$articles,'pager'=>$pager]);
    }
    //添加文章
    public function actionAdd(){
        //实例化文章模型
        $article=new Article();
        //实例化文章详情模型
        $articleDetail=new ArticleDetail();
        //接收表单提交的数据，并保存到数据表
        $request=new Request();
        //判断提交方式
        if($request->isPost){
            //加载表单数据，并保存
            $article->load($request->post());
            $articleDetail->load($request->post());
            //验证数据
            if($article->validate() && $articleDetail->validate()){
                //验证成功
                //保存
                $article->create_time=time();
                $article->save();
                $articleDetail->article_id=$article->id;
                $articleDetail->save();
                //添加成功，提示
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转列表
                return $this->redirect(['article/index']);
            }else{
                //验证失败，打印错误信息
                var_dump($article->getErrors() && $articleDetail->getErrors());exit;
            }

        }
        //调用视图，并传值
        return $this->render('add',['article'=>$article,'articleDetail'=>$articleDetail]);
    }
    public function actionEdit($id){
        //根据id获取一条文章数据
        $article=Article::findOne(['id'=>$id]);
        //根据id获取一条文章详情数据
        $articleDetail=ArticleDetail::findOne(['article_id'=>$id]);
        //接收表单提交的数据，并保存到数据表
        $request=new Request();
        //判断提交方式
        if($request->isPost){
            //加载表单数据，并保存
            $article->load($request->post());
            $articleDetail->load($request->post());
            //验证数据
            if($article->validate() && $articleDetail->validate()){
                //验证成功
                //保存
                $article->create_time=time();
                $article->save();
                $articleDetail->article_id=$article->id;
                $articleDetail->save();
                //修改成功，提示
                \Yii::$app->session->setFlash('success','修改成功');
                //跳转列表
                return $this->redirect(['article/index']);
            }else{
                //验证失败，打印错误信息
                var_dump($article->getErrors() && $articleDetail->getErrors());exit;
            }

        }
        //调用视图，并传值
        return $this->render('add',['article'=>$article,'articleDetail'=>$articleDetail]);
    }
    //删除列表
    public function actionDelete($id){
        //根据id查询一条数据
        $article=Article::findOne(['id'=>$id]);
        //将查询状态改为－1
        $article->status=-1;
        $article->save();
        //删除成功，提示
        \Yii::$app->session->setFlash('success','删除成功');
        //跳转到列表页
        return $this->redirect(['article/index']);
    }
    //查看文章详细内容
    public function actionShow($id){
        //根据id查询一条文章数据
        $article=Article::findOne(['id'=>$id]);
        //根据id查询一条文章详情
        $articleDetail=ArticleDetail::findOne(['article_id'=>$id]);
        //调用视图，并传值
        return $this->render('show',['article'=>$article,'articleDetail'=>$articleDetail]);
    }
    //百度UEditor
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }

    public function behaviors()
    {
        return[
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'only'=>[
                    'add', 'edit','index','delete','upload'
                ],

            ]
        ];
    }
}
