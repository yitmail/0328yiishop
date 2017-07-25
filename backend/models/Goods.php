<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $goods_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property integer $is_on_sale
 * @property integer $status
 * @property integer $sort
 * @property integer $create_time
 * @property integer $view_times
 */
class Goods extends \yii\db\ActiveRecord
{
    /*public static function getStatusOptions($hidden_del=true){
        $options=[
            1=>'正常',
            0=>'回收站',
        ];
        if($hidden_del){
            unset($options[0]);
        }
        return $options;
    }*/
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort', 'create_time', 'view_times'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name', 'sn'], 'string', 'max' => 20],
            [['logo'], 'string', 'max' => 255],
            [['name','brand_id','stock','market_price','shop_price','is_on_sale','sort'],'required'],//必填
          [['market_price','shop_price'],'match','pattern'=>'/^\d+\.\d{2}$/','message'=>'价格必须为有两位小数，如10.10'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '货号',
            'logo' => 'LOGO',
            'goods_category_id'=>'商品分类',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'status' => '状态(1正常 0回收站)',
            'sort' => '排序',
            'create_time' => '添加时间',
            'view_times' => '浏览次数',
        ];
    }
  /*  //嵌套集合行为
    public function behaviors()
    {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'depthAttribute' => 'depth',
            ],
        ];
    }*/
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }

    public static function getZtreeNodes()
    {
        $nodes=self::find()->select(['id','parent_id','name'])->asArray()->all();
        $nodes[]=['id'=>0,'parent_id'=>0,'name'=>'顶级分类','open'=>1];
        return $nodes;
    }
    //异常提示信息
    public static function exceptionInfo($msg)
    {
        $infos = [
            'Can not move a node when the target node is same.'=>'不能修改到自己节点下面',
            'Can not move a node when the target node is child.'=>'不能修改到自己的子孙节点下面',
        ];
        return isset($infos[$msg])?$infos[$msg]:$msg;
    }
    //建立商品与品牌分类的关联
    public function getBrand(){
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }
    //获取品牌分类选项
    public static function getBrandOptions(){
        return ArrayHelper::map(Brand::find()->all(),'id','name');
    }

    //商品与商品分类建立一对一关系
    public function getGoodsCategory(){
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }
    //商品与商品详情
   public function getGalleries(){
        return $this->hasMany(GoodsGallery::className(),['goods_id'=>'id']);
   }
}
