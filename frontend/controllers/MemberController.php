<?php
namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\City;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\captcha\CaptchaAction;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class MemberController extends Controller{
       public $layout=false;
       //关闭csrf验证
       public $enableCsrfValidation=false;
       //用户注册
        public function actionRegister(){
            //实例化模型
            $model=new Member();
            //调用视图
            return $this->render('register',['model'=>$model]);
        }
    //AJAX表单验证注册
    public function actionAjaxRegister()
    {
        $model = new Member();
        $model->scenario = Member::SCENARIO_REGISTER;
        if($model->load(\Yii::$app->request->post()) && $model->validate() ){
            $code=\Yii::$app->session->get('code_'.$model->tel);
            if($code && $code==$model->smsCode){

                $model->auth_key=\Yii::$app->security->generateRandomString();
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
                $model->status=1;
                $model->created_at=time();
                $model->save(false);
                //保存数据，提示保存成功
                return Json::encode(['status'=>true,'msg'=>'注册成功']);
            }else{
                $model->addError('smsCode','短信验证码错误');
            }
        }else{
            //验证失败，提示错误信息

            return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
        }
    }
    //登录
    public function actionLogin(){
        //实例化模型
        $model=new LoginForm();
        //调用视图
        return $this->render('login',['model'=>$model]);
    }
    //AJAX表单验证登录
    public function actionAjaxLogin()
    {
        //实例化表单数据模型
        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $member = Member::findOne(['username' => $model->username]);
//            return Json::encode(['sss'=>$member]);
            if ($member) {
                //用户存在
                //对比密码
                $result = \Yii::$app->security->validatePassword($model->password, $member->password_hash);
                if ($result) {
                    //密码正确
                    $member->last_login_time = time();
                    $member->last_login_ip = ip2long(\Yii::$app->request->userIP);//保存登录的ip
                    //登录(保存登录信息到session)
                    \Yii::$app->user->login($member, $model->rememberMe ? 3600 * 24 * 30 : 0);
                    $member->save(false);
//                    //成功，提示
//                    \Yii::$app->session->setFlash('success','登录成功');
//                    //跳转到首页
//                    return $this->redirect(['member/index']);
                    //保存数据，提示保存成功
                    return Json::encode(['status' => true, 'msg' => '登录成功']);

                } else {
                    //登录失败，密码错误，提示错误信息
                    $this->addError('password', '密码错误');
                }
            }else{
                //用户名不存在，提示错误信息
                $this->addError('member', '用户名不存在');
            }
            return false;
        }else{
            //验证失败，提示错误信息
            return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
        }
    }
    //获取三级联动地址
    public function actionCity($id){
        //实例化模型
        $model=new City();
        return $model->getCity($id);
    }
    //添加收货地址
    public function actionAddress(){
        //实例化模型
        $model=new Address();
        $member_id=\Yii::$app->user->id;
        $addresses=Address::find()->where(['member_id'=>$member_id])->all();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->province=Address::getName($model->province)->name;
//            var_dump($model->province);exit;
            $model->city=Address::getName($model->city)->name;
            $model->area=Address::getName($model->area)->name;
            $model->address=($model->province).($model->city).($model->area).($model->address);
//            var_dump($model->address);exit;
            if($model->status){
                $model->status=1;
            }else{
                $model->status=0;
            }
            $model->member_id=\Yii::$app->user->id;

            $model->save();
            //成功，提示
            \Yii::$app->session->setFlash('success','收货地址添加成功');
            //跳转
            return $this->redirect(['member/address']);
        }
        return $this->render('address',['model'=>$model,'addresses'=>$addresses]);
    }

    //修改收货地址
    public function actionAddEdit($id){

        $model=Address::findOne(['id'=>$id]);
        $member_id=\Yii::$app->user->id;
        $addresses=Address::find()->where(['member_id'=>$member_id])->all();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->province=Address::getName($model->province)->name;
//            var_dump($model->province);exit;
            $model->city=Address::getName($model->city)->name;
            $model->area=Address::getName($model->area)->name;
            $model->address=($model->province).($model->city).($model->area).($model->address);
//            var_dump($model->address);exit;
            if($model->status){
                $model->status=1;
            }else{
                $model->status=0;
            }
            $model->member_id=\Yii::$app->user->id;

            $model->save();
            //成功，提示
            \Yii::$app->session->setFlash('success','收货地址添加成功');
            //跳转
            return $this->redirect(['member/address']);
        }
        return $this->render('address',['model'=>$model,'addresses'=>$addresses]);
    }
    // 删除收货地址
    public function actionAddDel($id){
        $model=Address::findOne(['id'=>$id]);
        if(!$model){
            throw new NotFoundHttpException('不存在该地址');
        }
        $model->delete();
        return $this->redirect(['member/address']);

    }
    //设置为默认收货地址
    public function actionAddStatus($id){
        $model=Address::findOne(['id'=>$id]);
        if($model->status==0){
            $model->status=1;
        }
        $model->save();
        //提示
        \Yii::$app->session->setFlash('success','默认地址设置成功');
        return $this->redirect(['member/address']);
    }
    //定义验证码
    public function actions()
    {
        return[
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'minLength'=>3,
                'maxLength'=>3,
            ]
        ];
    }
    //测试短信
    public function actionMessage(){
        $code = rand(1000,9999);
        $tel = '18190112031';
        $res = \Yii::$app->sms->setPhoneNumbers($tel)->setTemplateParam(['code'=>$code])->send();
//        var_dump($res);exit;
        //将短信验证码保存redis（session，mysql）
        \Yii::$app->session->set('code_'.$tel,$code);
        //验证
       // $code2 = \Yii::$app->session->get('code_'.$tel);
    }
}