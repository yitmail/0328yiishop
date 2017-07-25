<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\db\Exception;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class GoodsCategoryController extends \yii\web\Controller
{
    //展示分类列表
    public function actionIndex($keywords='')
    {
              //查询所有数据
              $query=GoodsCategory::find();
      //        var_dump($query);exit;
              //统计总条数
              $total=$query->count();
              //每页显示条数
              $perPage=3;
              //分页工具类
              $pager=new Pagination([
                  'totalCount'=>$total,
                  'defaultPageSize'=>$perPage,
              ]);
              $models=$query->where("name like '%{$keywords}%'")->limit($pager->limit)->offset($pager->offset)->orderBy('tree,lft')->all();
              return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }

    //添加商品分类
    public function actionAdd(){
        //实例化商品分类模型
        $goodsCategory=new GoodsCategory(['parent_id'=>0]);
        //判断提交方式，验证数据
        if($goodsCategory->load(\Yii::$app->request->post()) && $goodsCategory->validate()){
            $name=$goodsCategory->name;
            $parent_id=$goodsCategory->parent_id;
            $category=GoodsCategory::find()->andWhere(['name'=>$name,'parent_id'=>$parent_id])->all();
//               var_dump($category);exit;
            if($category){
                \Yii::$app->session->setFlash('warning','该分类已存在');
                //跳转到添加页面
                return $this->redirect(['goods-category/add']);
            }
            //$goodsCategory->save();//因为需要判断计算节点，所以不能直接保存
            //判断是否是添加一级分类
            if($goodsCategory->parent_id){
                //非一级分类
                $category=GoodsCategory::findOne(['id'=>$goodsCategory->parent_id]);
                if($category){
                    $goodsCategory->prependTo($category);
                }else{
                    throw new HttpException('404','上级分类不存在');
                }

            }else{
                //一级分类
                $goodsCategory->makeRoot();
            }
            //添加成功后，提示
            \Yii::$app->session->setFlash('success','添加成功');
            //跳转到列表页
            return $this->redirect(['goods-category/index']);
        }
        //获取所有分类数据
        $categories=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        //调用视图，并传值
        return $this->render('add',['goodsCategory'=>$goodsCategory,'categories'=>$categories]);
    }

    //添加商品分类(ztree选择上级分类id)
    public function actionAdd2(){
        //实例化商品分类模型
        $goodsCategory=new GoodsCategory(['parent_id'=>0]);
        //判断提交方式，验证数据
        if($goodsCategory->load(\Yii::$app->request->post()) && $goodsCategory->validate()){
            //$goodsCategory->save();//因为需要判断计算节点，所以不能直接保存
            //判断是否是添加一级分类
            if($goodsCategory->parent_id){
                //非一级分类
                $category=GoodsCategory::findOne(['id'=>$goodsCategory->parent_id]);
                if($category){
                    $goodsCategory->prependTo($category);
                }else{
                    throw new HttpException('404','上级分类不存在');
                }

            }else{
                //一级分类
                $goodsCategory->makeRoot();
            }
            //添加成功后，提示
            \Yii::$app->session->setFlash('success','添加成功');
            //跳转到列表页
            return $this->redirect(['goods-category/index']);
        }
        //获取所有分类数据
        $categories=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        //调用视图，并传值
        return $this->render('add2',['goodsCategory'=>$goodsCategory,'categories'=>$categories]);
    }

    //修改商品分类
    public function actionEdit($id){
        //获取一条分类信息
        $goodsCategory=GoodsCategory::findOne(['id'=>$id]);
        if($goodsCategory==null){
            throw new NotFoundHttpException('分类不存在');
        }
        //判断提交方式，验证数据
        if($goodsCategory->load(\Yii::$app->request->post()) && $goodsCategory->validate()){
//            $name=$goodsCategory->oldAttributes['name'];
//            $parent_id=$goodsCategory->oldAttributes['parent_id'];
            $ids = GoodsCategory::find()->select(['id'])->where(['parent_id'=>$id])->column();
            $ids[] = $id;
            if(in_array($goodsCategory->parent_id,$ids)){
                \Yii::$app->session->setFlash('warning','不能移动到节点到自己节点下');
                //跳转到添加页面
                return $this->redirect(['goods-category/add']);
            }
            //不能移动节点到自己节点下
           /* if($goodsCategory->parent_id==$goodsCategory->id){
                throw new \HttpException(404,'不能移动到节点到自己节点下');
            }*/
           try{

               //$goodsCategory->save();//因为需要判断计算节点，所以不能直接保存
               //判断是否是添加一级分类
               if($goodsCategory->parent_id){
                   //非一级分类
                   $category=GoodsCategory::findOne(['id'=>$goodsCategory->parent_id]);
                   if($category){
                       $goodsCategory->appendTo($category);
                   }else{
                       throw new HttpException('404','上级分类不存在');
                   }

               }else{
                   //一级分类
                   //bug fix:修复根节点修改为根节点的bug
                   if($goodsCategory->oldAttributes['parent_id']==0){
                       $goodsCategory->save();
                   }else{

                       $goodsCategory->makeRoot();
                   }
               }
               //修改成功后，提示
               \Yii::$app->session->setFlash('success','修改成功');
               //跳转到列表页
               return $this->redirect(['goods-category/index']);
           }catch(Exception $e){
               $goodsCategory->addError('parent_id',GoodsCategory::exceptionInfo($e->getMessage()));
           }
        }
        //获取所有分类数据
        $categories=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        //调用视图，并传值
        return $this->render('add',['goodsCategory'=>$goodsCategory,'categories'=>$categories]);
    }

    //删除分类
    public function actionDelete($id){
        $goodsCategory=GoodsCategory::findOne(['id'=>$id]);
       /* //判断右值与左值的差
        if(($goodsCategory->rgt-$goodsCategory->lft)==1){
           $goodsCategory->delete();
           //删除成功，提示
            \Yii::$app->session->setFlash('success','删除成功');
        }else{
            //该分类有子类不能删除
            \Yii::$app->session->setFlash('danger','该分类有子类不能删除');
        }*/
       if($goodsCategory==null){
           throw new NotFoundHttpException('商品分类不存在');
       }
       if(!$goodsCategory->isLeaf()){//判断是否是叶子节点，非叶子节点说明有子分类
            throw new ForbiddenHttpException('该分类下有子分类，无法删除');
       }
       $goodsCategory->deleteWithChildren();
       //删除成功，提示
        \Yii::$app->session->setFlash('success','删除成功');
        //跳转到列表页
        return $this->redirect(['goods-category/index']);

    }

    //测试嵌套集合插件的用法
    public function actionTest(){
        //创建一个根节点
/*        $category=new GoodsCategory();
        $category->name='家用电器';
        $category->makeRoot();
        var_dump($category->getErrors());*/

        //创建子节点
/*        $category2 = new GoodsCategory();
        $category2->name='小家电';
        $category=GoodsCategory::findOne(['id'=>1]);
        $category2->parent_id=$category->id;
        $category2->prependTo($category);*/

        //删除节点
//        $cate=GoodsCategory::findOne(['id'=>4])->delete();
        echo'操作成功';
    }
    //测试ztree
    public function actionZtree()
    {
//        $this->layout=false;
        //不加载布局文件
        return $this->renderPartial('ztree');
    }

}
