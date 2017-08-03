<?php
namespace backend\filters;

use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;

class RbacFilter extends ActionFilter{
    //执行操作之前
    public function beforeAction($action)
    {
        //如果用户没有登录，引导用户登录
        if(\Yii::$app->user->isGuest){
            return $action->controller->redirect(\Yii::$app->user->loginUrl);
        }
        //$action->uniqueId 路由
        if(!\Yii::$app->user->can($action->uniqueId)){
//            return false;
            throw new ForbiddenHttpException('对不起，您没有该执行权限');
        }
        //return false 拦截
        return parent::beforeAction($action); //相当于return true; //放行
    }
}