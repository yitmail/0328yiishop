<?php
namespace backend\models;

use yii\base\Model;

class RoleForm extends Model
{
    const SCENARIO_ADD = 'add';//定义添加角色的场景
    public $name;//角色名称
    public $description;//角色的描述
    public $permissions=[];//权限
    public function rules()
    {
        return[
            [['name','description'],'required'],
            ['permissions','safe'],
            ['name','validateName','on'=>self::SCENARIO_ADD],
        ];
    }
    public function attributeLabels()
    {
        return[
            'name'=>'名称',
            'description'=>'描述',
            'permissions'=>'权限',
        ];
    }
    public function validateName(){
        //该规则用来判断出错的情况
        $authManager=\Yii::$app->authManager;
        if($authManager->getRole($this->name)){
            $this->addError('name','角色已存在');
        }
    }

}