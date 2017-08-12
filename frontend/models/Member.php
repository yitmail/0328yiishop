<?php
namespace frontend\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Member extends ActiveRecord implements IdentityInterface
{
    const SCENARIO_REGISTER = 'register';
    public $password;//密码
    public $repeat_password;//确认密码
    public $smsCode;//短信验证码
    public $code;//验证码


    public function rules()
    {
        return[
          [['username','email','tel'],'required'],
          //[['password','repeat_password','smsCode','code'],'required','on'=>self::SCENARIO_REGISTER],
          ['repeat_password','compare','compareAttribute'=>'password','message'=>'两次输入密码不一致'],
        //验证规则
          //['code','captcha','captchaAction'=>'member/captcha','on'=>self::SCENARIO_REGISTER],
          [['username','email','tel'],'unique'],
          ['email','email'],

        ];
    }
    public function attributeLabels()
    {
        return[
            'username'=>'用户名',
            'password'=>'密码',
            'repeat_password'=>'确认密码',
            'email'=>'邮箱',
            'tel'=>'手机号',
            'smsCode'=>'短信验证码',
            'code'=>'验证码',

        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey()===$authKey;
    }
}