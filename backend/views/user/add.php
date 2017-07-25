<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($user,'username');
echo $form->field($user,'password')->passwordInput();
echo $form->field($user,'email');
echo $form->field($user,'status',['inline'=>'true'])->radioList(\backend\models\User::$status_options);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
