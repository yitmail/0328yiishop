<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'rememberMe')->checkbox();
echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),
    ['captchaAction'=>'user/captcha',
        'template'=>'<div class="row"><div class="col-lg-1">{image}</div><div class="col-lg-1">{input}</div></div>']);
echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();