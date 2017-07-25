<?php
namespace backend\models;

use yii\base\Model;

class ModifyPasswordForm extends Model
{
    public $old_password;
    public $new_password;
    public $repeat_password;
    public function rules()
    {
        return[
            [['old_password','new_password','repeat_password'],'required','message'=>'{attribute}必填'],
            ['repeat_password','compare','compareAttribute'=>'new_password','message'=>'两次输入密码不一致']
        ];
    }
    public function attributeLabels()
    {
        return[
            'old_password'=>'旧密码',
            'new_password'=>'新密码',
            'repeat_password'=>'确认密码',
        ];
    }
}