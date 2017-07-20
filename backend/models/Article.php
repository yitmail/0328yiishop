<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $article_category_id
 * @property integer $sort
 * @property integer $status
 * @property integer $create_time
 */
class Article extends \yii\db\ActiveRecord
{
    public static function getStatusOptions($hidden_del=true){
        $options=[
            '-1'=>'删除',
            '0'=>'隐藏',
            '1'=>'正常',
        ];
        if($hidden_del){
            unset($options[-1]);
        }
        return $options;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['name','intro','article_category_id', 'sort', 'status'], 'required'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '文章名',
            'intro' => '简介',
            'article_category_id' => '文章分类id',
            'sort' => '排序',
            'status' => '状态',
            'create_time' => '创建时间',
        ];
    }
    //建立文章和文章分类的关联
    public function getArticleCategory(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }
    //获取文章分类选项
    public static function getArticleCategoryOptions(){
        return ArrayHelper::map(ArticleCategory::find()->all(),'id','name');
    }

}
