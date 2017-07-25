<?php
namespace backend\models;

use yii\base\Model;

class LoginForm extends Model{
    public $username;
    public $password;
    public $rememberMe;
    public  $code;
    public function rules()
    {
        return[
            [['username','password'],'required'],
            ['rememberMe','boolean'],
            //验证规则
            ['code','captcha','captchaAction'=>'user/captcha']
        ];
    }
    public function attributeLabels()
    {
        return[
          'username'=>'用户名',
          'password'=>'密码',
          'rememberMe'=>'记住我',
          'code'=>'验证码',
        ];
    }
    //用户登录
    public function Login()
    {
        //通过用户名查找用户
        $user = User::findOne(['username' => $this->username]);
        if ($user) {
            //用户存在
            //对比用户密码
            $result = \Yii::$app->security->validatePassword($this->password, $user->password_hash);
            if ($result) {
                //密码正确
                //登录(保存登录信息到session)
                \Yii::$app->user->login($user,$this->rememberMe ? 3600 * 24 * 30 : 0);
                return true;
            } else {
                //登录失败，密码错误，提示错误信息
                $this->addError('password', '密码错误');
            }
        } else {
            //用户不存在，提示错误信息
            $this->addError('username', '用户不存在');
        }
        return false;

    }

}