<?php
namespace frontend\controllers;
use backend\models\Goods;
use backend\models\GoodsCategory;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
class GoodsListController extends Controller {
    public $layout=false;
    public function actionList($id)
    {
        //
        $a = GoodsCategory::find()->select(['tree', 'lft', 'rgt'])->where(['id' => $id])->one();
        $id = GoodsCategory::find()->select('id')
            ->andWhere(['tree' => $a->tree])
            ->andWhere(['>=', 'lft', $a->lft])
            ->andWhere(['<=', 'rgt', $a->rgt])
            ->all();
        $b = ArrayHelper::map($id, 'id', 'id');
//        var_dump($b); exit;
        $models=Goods::find()->where(['in','goods_category_id',$b])->all();
        return $this->render('list',['model'=>$models]);
    }
}