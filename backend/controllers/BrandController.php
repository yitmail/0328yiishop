<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    //展示列表
    public function actionIndex()
    {   //分页 总条数 每页显示条数 当前第几条
        $query=Brand::find();
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
        $brands=$query->where(['>','status',-1])->limit($pager->limit)->offset($pager->offset)->orderBy('id')->all();
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
            $brand->imgFile=UploadedFile::getInstance($brand,'imgFile');
            //验证数据
            if($brand->validate()){
                //处理图片
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
                }
                //验证成功
                $brand->save();
                //跳转到列表页
                return $this->redirect(['brand/index']);
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
            $brand->imgFile=UploadedFile::getInstance($brand,'imgFile');
            //验证数据
            if($brand->validate()){
                //处理图片
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
                }
                //验证成功
                $brand->save();
                //跳转到列表页
                return $this->redirect(['brand/index']);
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
        //跳转到列表页
        return $this->redirect(['brand/index']);
    }
}
