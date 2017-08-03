<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Menu;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class MenuController extends \yii\web\Controller
{
    //菜单列表
    public function actionIndex()
    {
        $models = Menu::find()->where(['parent_id'=>0])->all();
        //调用视图
        return $this->render('index',['models'=>$models]);
    }
    //添加菜单
    public function actionAdd(){
        //实例化菜单模型
        $menu=new Menu();
        //加载表单提交的数据，并验证数据
        if($menu->load(\Yii::$app->request->post()) && $menu->validate()){
            $menu->save();
            //添加成功，提示
            \Yii::$app->session->setFlash('success','菜单添加成功');
            //跳转到菜单列表
            return $this->redirect(['menu/index']);
        }
        //调用视图
        return $this->render('add',['menu'=>$menu]);
    }
    //修改菜单
    public function actionEdit($id){
        $menu=Menu::findOne(['id'=>$id]);
        if($menu->load(\Yii::$app->request->post()) && $menu->validate()){
            //预防出现三级菜单
            if($menu->parent_id && !empty($menu->children)){
                $menu->addError('parent_id','只能为顶级菜单');
            }else{
                $menu->save();
                //修改成功，提示
                \Yii::$app->session->setFlash('success','菜单修改成功');
                //跳转到菜单列表
                return $this->redirect(['menu/index']);
            }
        }
        //调用视图
        return $this->render('add',['menu'=>$menu]);
    }
    //删除菜单
    public function actionDelete($id){
        $menu=Menu::findOne(['id'=>$id]);
        if(!empty($menu->children)){
          throw new NotFoundHttpException('该菜单有子菜单，不能删除');
        }else{
            $menu->delete();
            //跳转到菜单列表
            return $this->redirect(['menu/index']);
        }
    }
    public function behaviors()
    {
        return[
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'only'=>[
                    'add', 'edit','index','delete',
                ],
            ]
        ];
    }
}
