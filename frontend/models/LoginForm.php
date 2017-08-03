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
            [['username','password','code'],'required'],
            //验证规则
            ['code','captcha','captchaAction'=>'member/captcha'],
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
//    public function Login(){
//        //通过用户名查找用户
//            $member=Member::findOne(['username'=>$this->username]);
//       // var_dump($member);exit;
//        if($member){
//            //用户存在
//            //对比密码
//            $result=\Yii::$app->security->validatePassword($this->password,$member->pass);
//            if($result){
//                //密码正确
//                //登录(保存登录信息到session)
//                \Yii::$app->member->login($member,$this->rememberMe ? 3600*24*30 : 0);
//                return true;
//            }else{
//                //登录失败，密码错误，提示错误信息
//                $this->addError('password','密码错误');
//            }
//        }else{
//            //用户名不存在，提示错误信息
//            $this->addError('member','用户名不存在');
//        }
//        return false;
//    }
}