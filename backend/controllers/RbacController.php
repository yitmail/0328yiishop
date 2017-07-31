<?php
namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RbacController extends Controller
{
    //权限列表
    public function actionPermissionIndex()
    {
        //获取所有权限
        $authManager=\Yii::$app->authManager;
        $models=$authManager->getPermissions();
//        var_dump($models);exit;
        //调用视图，并传值
        return $this->render('permission-index',['models'=>$models]);
    }
    //添加权限
    public function actionAddPermission()
    {
        //实例化权限表单模型
        $model=new PermissionForm();
        $model->scenario=PermissionForm::SCENARIO_ADD;
        //加载表单提交的数据，并验证
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $authManager=\Yii::$app->authManager;
            //创建权限
            $permission=$authManager->createPermission($model->name);
            $permission->description=$model->description;
            //保存到数据表
            $authManager->add($permission);
            //提示
            \Yii::$app->session->setFlash('success','权限添加成功');
            //跳转到权限列表
             return $this->redirect(['rbac/permission-index']);
        }
        //调用视图，并传值
        return $this->render('add-permission',['model'=>$model]);

    }
    //修改权限
    public function actionEditPermission($name){
        //检查权限是否存在
        $authManager=\Yii::$app->authManager;
        $permission=$authManager->getPermission($name);
        if($permission==null){
            throw new NotFoundHttpException('权限不存在');
        }
        //实例化表单数据模型
        $model=new PermissionForm();
        //判断提交方式
        if(\Yii::$app->request->isPost){
            if($model->load(\Yii::$app->request->post()) && $model->validate()){
                //将表单数据赋值给权限
                $permission->name=$model->name;
                $permission->description=$model->description;
                //更新权限
                $authManager->update($name,$permission);
                //提示
                \Yii::$app->session->setFlash('success','权限修改成功');
                //跳转到权限列表
                return $this->redirect(['rbac/permission-index']);
            }
        }else{
            //回显权限数据到表单
            $model->name=$permission->name;
            $model->description=$permission->description;
        }
        //调用视图
        return $this->render('add-permission',['model'=>$model]);
    }
    //删除权限
    public function actionDeletePermission($name){
        $authManager=\Yii::$app->authManager;
        $permission=$authManager->getPermission($name);
        $authManager->remove($permission);
        //提示
        \Yii::$app->session->setFlash('success','权限删除成功');
        //跳转到权限列表
        return $this->redirect(['rbac/permission-index']);
    }
    //角色列表
    public function actionRoleIndex(){
        //获取所有角色
        $authManager=\Yii::$app->authManager;
        $models=$authManager->getRoles();
        //调用视图，并传值
        return $this->render('role-index',['models'=>$models]);
    }
    //添加角色
    public function actionAddRole(){
        //实例化角色表单模型
        $model=new RoleForm();
        $model->scenario=RoleForm::SCENARIO_ADD;
        //加载表单提交的数据，验证数据
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $authManager=\Yii::$app->authManager;
            //创建角色
            $role=$authManager->createRole($model->name);
            $role->description=$model->description;
            //保存到数据表
            $authManager->add($role);
            //给角色赋予权限
            if(is_array($model->permissions)){
                foreach ($model->permissions as $permissionName){
                    $permission=$authManager->getPermission($permissionName);
                    if($permission){
                        $authManager->addChild($role,$permission);
                    }
                }
            }
            //提示
            \Yii::$app->session->setFlash('success','角色添加成功');
            //跳转到角色列表
            return $this->redirect(['rbac/role-index']);
        }
        //调用视图，并传值
        return $this->render('add-role',['model'=>$model]);
    }
    //修改角色
    public function actionEditRole($name){
        //检查角色是否存在
        $authManager=\Yii::$app->authManager;
        $role=$authManager->getRole($name);
        if($role==null){
            throw new NotFoundHttpException('角色不存在');
        }
        //实例化角色表单模型
        $model=new RoleForm();
        //判断提交
        if(\Yii::$app->request->isPost){
            if($model->load(\Yii::$app->request->post()) && $model->validate()){
                //取消角色和权限的关联
                $authManager->removeChildren($role);
                //将表单数据赋值给角色
                $role->name=$model->name;
                $role->description=$model->description;
                //更新角色
                $authManager->update($name,$role);
                //给角色赋予权限
                if(is_array($model->permissions)){
                    foreach ($model->permissions as $permissionName){
                        $permission=$authManager->getPermission($permissionName);
                        if($permission){
                            $authManager->addChild($role,$permission);
                        }
                    }
                }
                //提示
                \Yii::$app->session->setFlash('success','角色修改成功');
                //跳转到角色列表
                return $this->redirect(['rbac/role-index']);
            }
        }else{
            //回显角色
            //表单权限多选回显
            //获取角色权限
            $model->name=$role->name;
            $model->description=$role->description;
            $permissions=$authManager->getPermissionsByRole($name);
            $model->permissions=ArrayHelper::map($permissions,'name','name');

        }
        //调用视图
        return $this->render('add-role',['model'=>$model]);
    }
    //删除角色
    public function actionDeleteRole($name){
        $authManager=\Yii::$app->authManager;
        $role=$authManager->getRole($name);
        $authManager->remove($role);
        $authManager->removeChildren($role);
        //提示
        \Yii::$app->session->setFlash('success','角色删除成功');
        //跳转到角色列表
        return $this->redirect(['rbac/role-index']);
    }
    public function behaviors()
    {
        return[
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'only'=>[
                    'add-permission', 'edit-permission','permission-index','delete-permission',
                    'add-role', 'edit-role','role-index','delete-role',
                ],
            ]
        ];
    }
}