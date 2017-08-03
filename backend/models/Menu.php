<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property string $url
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    public $permissions=[];//权限
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'sort'], 'integer'],
            [['label', 'url'], 'string', 'max' => 255],
            [['label','parent_id','sort'],'required'],
         //   ['permission','safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => '名称',
            'parent_id' => '上级菜单',
            'url' => '地址/路由',
            'sort' => '排序',
            'permissions' => '权限',
        ];
    }
    //获取菜单选项
    public static function getMenuOptions(){
        $menu=[0=>'顶级菜单'];
        $menus=ArrayHelper::map(self::find()->where(['parent_id'=>0])->asArray()->all(),'id','label');
        return ArrayHelper::merge($menu,$menus);
    }
    //获取子菜单  Menu[]
    public function getChildren()
    {
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
}
