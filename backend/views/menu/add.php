<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($menu,'label')->textInput(['placeholder'=>'菜单名称']);
echo $form->field($menu,'parent_id')->dropDownList(
    \backend\models\Menu::getMenuOptions(),['prompt' => '=请选择菜单=']
   );
echo $form->field($menu,'url')->dropDownList(
    \yii\helpers\ArrayHelper::map(Yii::$app->authManager->getPermissions(),'name','name'),
    ['prompt' => '=请选择路由=']
);
echo $form->field($menu,'sort');

echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();