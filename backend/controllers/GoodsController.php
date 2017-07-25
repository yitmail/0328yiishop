<?php

namespace backend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class GoodsController extends \yii\web\Controller
{
    //展示列表
    public function actionIndex($name='',$sn='',$shop_price='')
    {
        //查询所有数据
        $query=Goods::find()->andWhere(['=','status',1]);
        if($name){
            $query->andWhere(['like','name',$name]);
        }
        if($sn){
            $query->andWhere(['like','sn',$sn]);
        }
        if($shop_price){
            $query->andWhere(['like','shop_price',$shop_price]);
        }
        //统计条数
        $total=$query->count();
        //每页显示3条
        $perPage=3;
        //分页工具类
        $pager=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$perPage,
        ]);
        $models=$query->limit($pager->limit)->offset($pager->offset)->orderBy('id')->all();
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }
    //添加商品
    public function actionAdd(){
         //实例化商品模型
        $goods=new Goods();
        //实例化商品详情模型
        $goodsIntro=new GoodsIntro();
       //实例化商品分类模型
        $goodsCategory=new GoodsCategory(['parent_id'=>0]);
        //接收表单提交的数据，并保存到数据表
        $request=new Request();
        //判断提交方式
        if($request->isPost){
            //加载表单数据
           $goods->load($request->post());
//           var_dump($goods);exit;
           $goodsIntro->load($request->post());
//            var_dump($goodsIntro);exit;
            //验证数据
            if($goods->validate() && $goodsIntro->validate()){
                //查询商品每日添加数表
                $day=date('Y-m-d');
                $row=GoodsDayCount::findOne(['day'=>$day]);
                if($row==null){
                    $goodsCount=new GoodsDayCount();
                    $goodsCount->day=$day;
                    $goodsCount->count=1;
                    $goodsCount->save();
                    //新增商品自动生成sn,规则为年月日+今天的第几个商品,比如2016053000001
                    $goods->sn=date('Ymd').str_pad($goodsCount->count,5,0,STR_PAD_LEFT);
                }else{
//                echo 111;exit;
                    $row->count++;
                    $row->save();
                    //新增商品自动生成sn,规则为年月日+今天的第几个商品,比如2016053000001
                    $goods->sn=date('Ymd').str_pad($row->count,5,0,STR_PAD_LEFT);
                }
                $goods->status=1;
                $goods->create_time=time();
//                var_dump($goods);exit;
                $goods->save();
                $goodsIntro->goods_id=$goods->id;
                $goodsIntro->save();
                //添加成功，提示
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转到列表页
                return $this->redirect(['goods/index']);
            }else{
                //验证失败，打印错误信息
                var_dump($goods->getErrors() && $goodsIntro->getErrors());exit;
            }

        }
        //获取所有分类数据
        $categories=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        //调用视图，并传值
        return $this->render('add',
            ['goods'=>$goods,'goodsCategory'=>$goodsCategory,'goodsIntro'=>$goodsIntro,'categories'=>$categories]
        );
    }
    //修改商品
    public function actionEdit($id){
        //根据id获取一条商品数据
        $goods=Goods::findOne(['id'=>$id]);
        //根据id获取一条商品详情数据
        $goodsIntro=GoodsIntro::findOne(['id'=>$id]);
        //根据id获取一条商品分类数据
        $goodsCategory=GoodsCategory::findOne(['id'=>$id]);
        //接收表单提交的数据，并保存到数据表
        $request=new Request();
        //判断提交方式
        if($request->isPost){
            //加载表单数据
            $goods->load($request->post());
//           var_dump($goods);exit;
            $goodsIntro->load($request->post());
//            var_dump($goodsIntro);exit;
            //查询商品每日添加数表
            $day=date('Ymd');
            $row=GoodsDayCount::findOne(['day'=>$day]);
            if($row==null){
                $goodsCount=new GoodsDayCount();
                $goodsCount->day=date('Ymd');
                $goodsCount->count=1;
                $goodsCount->save();
                //新增商品自动生成sn,规则为年月日+今天的第几个商品,比如2016053000001
                $goods->sn=date('Ymd').str_pad($goodsCount->count,5,0,STR_PAD_LEFT);
            }else{
//                echo 111;exit;
                $row->count=($row->count)+1;
                $row->save();
                //新增商品自动生成sn,规则为年月日+今天的第几个商品,比如2016053000001
                $goods->sn=date('Ymd').str_pad($row->count,5,0,STR_PAD_LEFT);
            }
            //验证数据
            if($goods->validate() && $goodsIntro->validate()){
                $goods->status=1;
                $goods->create_time=time();
//                var_dump($goods);exit;
                $goods->save();
                $goodsIntro->goods_id=$goods->id;
                $goodsIntro->save();
                //添加成功，提示
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转到列表页
                return $this->redirect(['goods/index']);
            }else{
                //验证失败，打印错误信息
                var_dump($goods->getErrors() && $goodsIntro->getErrors());exit;
            }

        }
        //获取所有分类数据
        $categories=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        //调用视图，并传值
        return $this->render('add',
            ['goods'=>$goods,'goodsCategory'=>$goodsCategory,'goodsIntro'=>$goodsIntro,'categories'=>$categories]
        );
    }
    //删除商品
    public function actionDelete($id){
        //根据id查询一条数据
        $goods=Goods::findOne(['id'=>$id]);
        //将查询到的数据状态改为0
        $goods->status=0;
        $goods->save();
        //删除成功，提示
        \Yii::$app->session->setFlash('success','商品删除成功');
        //跳转到列表页
        return $this->redirect(['goods/index']);
    }
    //回收站
    public function actionRecycle($keywords='')
    {
        //查询所有删除的数据
        $query=Goods::find()->where(['and','status=0',"name like '%{$keywords}%'"]);
        //统计条数
        $total=$query->count();
        //每页显示3条
        $perPage=3;
        //分页工具类
        $pager=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$perPage,
        ]);
        $models=$query->limit($pager->limit)->offset($pager->offset)->orderBy('id')->all();
        return $this->render('recycle',['models'=>$models,'pager'=>$pager]);
    }
    //撤销删除的商品
    public function actionCancel($id){
        //根据id查找需要恢复的数据
        $goods=Goods::findOne(['id'=>$id]);
        //将状态改为1
        $goods->status=1;
        $goods->save();
        //恢复成功，提示
        \Yii::$app->session->setFlash('success','商品恢复成功');
        //跳转到列表页
        return $this->redirect(['goods/index']);
    }

    //查看商品详细内容
    public function actionShow($id){
        //根据id查询一条文章数据
        $goods=Goods::findOne(['id'=>$id]);
        //根据id查询一条文章详情
        $goodsIntro=GoodsIntro::findOne(['goods_id'=>$id]);
        //根据goods_category_id
        $goodsCategory=GoodsCategory::findOne(['id'=>$goods->goods_category_id]);
        //调用视图，并传值
        return $this->render('show',['goods'=>$goods,'goodsCategory'=>$goodsCategory,'goodsIntro'=>$goodsIntro]);
    }
    /*
    * 商品相册
    */
    public function actionGallery($id)
    {
        $goods = Goods::findOne(['id'=>$id]);
        if($goods == null){
            throw new NotFoundHttpException('商品不存在');
        }


        return $this->render('gallery',['goods'=>$goods]);

    }

    /*
     * AJAX删除图片
     */
    public function actionDelGallery(){
        $id = \Yii::$app->request->post('id');
        $model = GoodsGallery::findOne(['id'=>$id]);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }

    }
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                // 'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,//如果文件已存在，是否覆盖
                /*                'format' => function (UploadAction $action) {
                                    $fileext = $action->uploadfile->getExtension();
                                    $filename = sha1_file($action->uploadfile->tempName);
                                    return "{$filename}.{$fileext}";
                                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },//文件的保存方式
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $goods_id = \Yii::$app->request->post('goods_id');
                    if($goods_id){
                        $model = new GoodsGallery();
                        $model->goods_id = $goods_id;
                        $model->path = $action->getWebUrl();
                        $model->save();
                        $action->output['fileUrl'] = $model->path;
                        $action->output['id'] = $model->id;
                    }else{
                        $action->output['fileUrl'] = $action->getWebUrl();//输出文件的相对路径
                    }
                    //图片保存为本地相对路径
//                    $action->output['fileUrl'] = $action->getWebUrl();//输出文件的相对路径
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"

                    //将图片上传到七牛云
//                    $qiniu = new Qiniu(\Yii::$app->params['qiniu']);
//                    $qiniu->uploadFile(
//                        $action->getSavePath(), $action->getWebUrl()
//                    );
//                    $url = $qiniu->getLink($action->getWebUrl());
//                    $action->output['fileUrl'] = $url;

                    //商品相册保存到七牛云
//                    $qiniu = new Qiniu(\Yii::$app->params['qiniu']);
//                    $qiniu->uploadFile(
//                        $action->getSavePath(), $action->getWebUrl()
//                    );
//                    $url = $qiniu->getLink($action->getWebUrl());
//                    $goods_id=\yii::$app->request->post('goods_id');
//                    if($goods_id){
//                        $model=new GoodsGallery();
//                        $model->goods_id=$goods_id;
//                        $model->path=$url;
//                        $model->save();
//                        $action->output['fileUrl'] = $model->path;
//                        $action->output['id'] = $model->id;
//                    }else{
//                        $action->output['fileUrl']  = $url;//输出文件的相对路径
//                    }
////

                }
            ],
        ];
    }
}
