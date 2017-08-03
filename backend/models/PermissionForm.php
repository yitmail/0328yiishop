<?php
namespace backend\models;

use yii\base\Model;

class PermissionForm extends Model
{
    const SCENARIO_ADD ='add';//定义添加权限的场景
    public $name;//权限名称
    public $description;//权限的描述
    public function rules()
    {
        return[
            [['name','description'],'required'],
            //权限名称不能重复
            ['name','validateName','on'=>self::SCENARIO_ADD],

        ];
    }
    public function attributeLabels()
    {
        return[
            'name'=>'名称(路由)',
            'description'=>'描述',
        ];
    }
    public function validateName()
    {
        //该规则只用来判断出错的情况
        $authManager=\Yii::$app->authManager;
        if($authManager->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }
    }
}