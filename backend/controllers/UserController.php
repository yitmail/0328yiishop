<?php

namespace backend\controllers;

use backend\models\LoginForm;
use backend\models\ModifyPasswordForm;
use backend\models\User;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\web\Request;

class UserController extends \yii\web\Controller
{
    //展示用户列表
    public function actionIndex()
    {
        //获取所有用户数据
        $query=User::find()->where(['=','status',10]);
        //统计条数
        $total=$query->count();
        //每页显示条数
        $perPage=3;
        //分页工具类
        $pager=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$perPage,
        ]);
        $users=$query->limit($pager->limit)->offset($pager->offset)->orderBy('id')->all();
        //调用视图，并传值
        return $this->render('index',['users'=>$users,'pager'=>$pager]);
    }
    //添加用户
    public function actionAdd(){
        //实例化用户模型
        $user=new User();
        //接收表单提交数据
        $request=new Request();
        //判断提交方式
        if($request->isPost){
            //加载表单提交的数据，并保存到数据表
            $user->load($request->post());
            if($user->validate()){
                //使用hash密码加密
                $user->password_hash=\Yii::$app->security->generatePasswordHash($user->password);
                $user->status=10;
                $user->created_at=time();
                $user->save();
                //添加成功
                \Yii::$app->session->setFlash('success','注册成功');
                //跳转到列表页
                return $this->redirect(['user/index']);
            }else{
                //验证失败，打印错误信息
                var_dump($user->getErrors());exit;
            }
        }
        //调用视图，并传值
        return $this->render('add',['user'=>$user]);
    }
    //修改用户
    public function actionEdit($id){
        //实例化用户模型
        $user=User::findOne(['id'=>$id]);
        //接收表单提交数据
        $request=new Request();
        //判断提交方式
        if($request->isPost){
            //加载表单提交的数据，并保存到数据表
            $user->load($request->post());
            if($user->validate()){
                //使用hash密码加密
                $user->password_hash=\Yii::$app->security->generatePasswordHash($user->password);
                $user->updated_at=time();
                $user->save();
                //添加成功
                \Yii::$app->session->setFlash('success','修改成功');
                //跳转到列表页
                return $this->redirect(['user/index']);
            }else{
                //验证失败，打印错误信息
                var_dump($user->getErrors());exit;
            }
        }
        //调用视图，并传值
        return $this->render('add',['user'=>$user]);
    }
    //删除用户
    public function actionDelete($id){
        $user=User::findOne(['id'=>$id]);
        //更改状态为0
        $user->status=0;
        $user->save(false);
        //删除成功，提示
        \Yii::$app->session->setFlash('success','删除成功');
        //跳转到列表页
        return $this->redirect(['user/index']);
    }
    //登录
    public function actionLogin(){
        //认证(检查用户的账号和密码是否正确)
        $model=new LoginForm();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate() && $model->Login()){
                $user=User::findOne(['username'=>$model->username]);
                $user->last_login_time=time();
//                $user->last_login_ip=$_SERVER["REMOTE_ADDR"];//保存登录的ip
                $user->last_login_ip=ip2long(\Yii::$app->request->userIP);//保存登录的ip
                $user->save(false);//默认情况下，保存时会调用validate方法，有验证码时，需要关闭验证
                //登录成功，提示
                \Yii::$app->session->setFlash('success','登录成功');
                //跳转到列表
                return $this->redirect(['user/index']);
            }
        }
        //调用视图，并传值
        return $this->render('login',['model'=>$model]);
    }
    //获取用户登录状态
    public function actionUser(){
        var_dump(\Yii::$app->user->isGuest);
        $user=\Yii::$app->user->identity;
        var_dump(long2ip($user->last_login_ip));

    }
    //注销
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['user/login']);
    }
    //定义验证码操作
    public function actions()
    {
        return[
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'minLength'=>3,
                'maxLength'=>3,
            ]
        ];
    }
    //修改个人密码
    public function actionModifyPassword(){
        $guest=\Yii::$app->user->isGuest;
//        var_dump($guest);exit;
        if(!$guest){
            //实例化密码修改模型
            $model=new ModifyPasswordForm();
            //判断请求方式 ，验证数据
            if($model->load(\Yii::$app->request->post()) && $model->validate()){
                $id=\Yii::$app->user->id;
                $user=User::findOne(['id'=>$id]);
//                var_dump($user);exit;
                if(\Yii::$app->security->validatePassword($model->old_password,$user->password_hash)){
                    if(\Yii::$app->security->validatePassword($model->new_password,$user->password_hash)){
                        //新密码与初始密码一样
                        \Yii::$app->session->setFlash('warning','新密码不能与初始密码一样');
                        //跳转
                        return $this->redirect(['user/modify-password']);
                    }else{
                        $user->password_hash=\Yii::$app->security->generatePasswordHash($model->new_password);
                        $user->save(false);
                       \Yii::$app->user->logout();
                        //跳转
                        return $this->redirect(['user/login']);
                    }
                }else{
                    //旧密码不正确，请重新输入
                    \Yii::$app->session->setFlash('warning','旧密码不正确');
//                    $model->addError('old_password','密码错误');
                    //跳转
                    return $this->redirect(['user/modify-password']);
                }
            }
        }else{
            //用户未登录提示
            \Yii::$app->session->setFlash('warning','请先登录');
            //跳转到登录
            return $this->redirect(['user/login']);
        }
        //调用视图
        return $this->render('modifyPassword',['model'=>$model]);

    }
}
