<?php
namespace frontend\models;

use yii\base\Model;

class LoginForm extends Model{
    public $username;//用户名
    public $password;//密码
    public $rememberMe;//记住密码
    public $code;//验证码

    public function rules()
    {
        return[
            [['username','password'],'required'],
            //验证规则
            //['code','captcha','captchaAction'=>'member/captcha'],
            ['rememberMe','boolean'],
        ];
    }
    public function attributeLabels()
    {
        return[
          'username'=>'用户名',
          'password'=>'密码',
          'rememberMe'=>'记住密码',
          'code'=>'验证码',
        ];
    }
//    //用户登录
    public function login()
    {
        // 通过用户名查找用户
        $model = Member::findOne(['username' => $this->username]);
        //判断是否存在该用户

        if ($model) {
            //验证输入的密码和数据库中的密码是否一致
            if (\Yii::$app->security->validatePassword($this->password,$model->password_hash)) {

                //密码正确.可以登录
                //2 登录(保存用户信息到session)
                \Yii::$app->user->login($model,$this->rememberMe?3600*24:0);
                return true;
            } else {
                //提示密码错误信息
                $this->addError('password', '密码错误');
//                return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
            }
        } else {
            //用户不存在,提示 用户不存在 错误信息
            $this->addError('username', '用户名不存在');
//            return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
        }
    }

}