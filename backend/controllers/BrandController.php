<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Request;
//use yii\web\UploadedFile;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;

class BrandController extends \yii\web\Controller
{
    //展示列表
    public function actionIndex()
    {   //分页 总条数 每页显示条数 当前第几条
        $query=Brand::find()->where(['>','status',-1]);
        //统计总条数
        $total=$query->count();
        //每页显示3条
        $perPage=3;
        //分页工具类
        $pager=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$perPage,
        ]);
        //根据指定条件找到出相应数据
        $brands=$query->limit($pager->limit)->offset($pager->offset)->orderBy('id desc')->all();
        //调用视图，并传值
        return $this->render('index',['brands'=>$brands,'pager'=>$pager]);
    }
    //添加品牌
    public function actionAdd(){
        //实例化数据模型
        $brand=new Brand();
        //接收表单提交的数据，并保存到数据表
        $request=new Request();
        //判断提交方式
        if($request->isPost){
            //加载表单数据
            $brand->load($request->post());
            //实例化文件上传对象
//            $brand->imgFile=UploadedFile::getInstance($brand,'imgFile');
            //验证数据
            if($brand->validate()){
               /* //处理图片
                //有文件上传拼接一个日期目录
                if($brand->imgFile){
                    $d=\Yii::getAlias('@webroot').'/upload/'.date('Ymd');
                    //判断目录是否存在
                    if(!is_dir($d)){
                        mkdir($d);
                    }
                    $filename='/upload/'.date('Ymd').'/'.uniqid().'.'.$brand->imgFile->extension;
                    $brand->imgFile->saveAs(\Yii::getAlias('@webroot').$filename,false);
                    //将文件保存到数据表
                    $brand->logo=$filename;
                }*/
                //验证成功
                $brand->save();
                //添加成功后，提示
                \Yii::$app->session->setFlash('success','品牌添加成功');
                //跳转到列表页
                return $this->redirect(['brand/index']);
            }else{
                //验证失败，打印错误信息
                var_dump($brand->getErrors());exit;
            }


        }
        //调用视图，并传值
        return $this->render('add',['brand'=>$brand]);
    }
    //修改品牌
    public function actionEdit($id){
        //实例化数据模型
        $brand=Brand::findOne(['id'=>$id]);
        //接收表单提交的数据，并保存到数据表
        $request=new Request();
        //判断提交方式
        if($request->isPost){
            //加载表单数据
            $brand->load($request->post());
            //实例化文件上传对象
            //$brand->imgFile=UploadedFile::getInstance($brand,'imgFile');
            //验证数据
            if($brand->validate()){
            /*    //处理图片
                //有文件上传拼接一个日期目录
                if($brand->imgFile){
                    $d=\Yii::getAlias('@webroot').'/upload/'.date('Ymd');
                    //判断目录是否存在
                    if(!is_dir($d)){
                        mkdir($d);
                    }
                    $filename='/upload/'.date('Ymd').'/'.uniqid().'.'.$brand->imgFile->extension;
                    $brand->imgFile->saveAs(\Yii::getAlias('@webroot').$filename,false);
                    //将文件保存到数据表
                    $brand->logo=$filename;
                }*/
                //验证成功
                $brand->save();
                //修改成功后，提示
                \Yii::$app->session->setFlash('success','品牌修改成功');
                //跳转到列表页
                return $this->redirect(['brand/index']);
            }else{
                //验证失败，打印错误信息
                var_dump($brand->getErrors());exit;
            }

        }
        //调用视图，并传值
        return $this->render('add',['brand'=>$brand]);
    }
    //删除品牌
    public function actionDelete($id){
        //根据id查找一条数据
        $brand=Brand::findOne(['id'=>$id]);
        //将查找到的id状态修改为-1，以便列表页，不用展示出来
        $brand->status=-1;
        $brand->save();
        //删除成功后，提示
        \Yii::$app->session->setFlash('success','品牌删除成功');
        //跳转到列表页
        return $this->redirect(['brand/index']);
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
//                    $action->output['fileUrl'] = $action->getWebUrl();//输出文件的相对路径
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                    //将图片上传到七牛云
                    $qiniu = new Qiniu(\Yii::$app->params['qiniu']);
                    $qiniu->uploadFile(
                        $action->getSavePath(), $action->getWebUrl()
                    );
                    $url = $qiniu->getLink($action->getWebUrl());
                    $action->output['fileUrl'] = $url;
                }
            ],
        ];
    }
    //测试七牛云文件上传
    public function actionQiniu(){

        $config = [
            'accessKey'=>'WuF1ZjK5SRne9rIR5u6-o35kdu0JUYzwRbg5Hvx-',
            'secretKey'=>'1V6-LTOleoC5-Xs5VXHItOt21ayP7N9346xafor-',
            'domain'=>'http://otbobgfpt.bkt.clouddn.com/',
            'bucket'=>' yiishop',
            'area'=>Qiniu::AREA_HUADONG
        ];



        $qiniu = new Qiniu($config);
        $key = 'upload/90/ff/90ffd26242a4ffd529b2f77d27a4f51a06946fd0.jpg';
        //将图片上传到七牛云
        $qiniu->uploadFile(
            \Yii::getAlias('@webroot').'/upload/90/ff/90ffd26242a4ffd529b2f77d27a4f51a06946fd0.jpg',
            $key);
        //获取该图片在七牛云上的地址
        $url = $qiniu->getLink($key);
        var_dump($url);
    }
    public function behaviors()
    {
        return[
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }
}
