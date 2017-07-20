<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($article,'name');
echo $form->field($article,'intro')->textarea();
echo $form->field($article,'article_category_id')->dropDownList(\backend\models\Article::getArticleCategoryOptions());
echo $form->field($article,'sort');
echo $form->field($article,'status',['inline'=>'true'])->radioList(\backend\models\Article::getStatusOptions());
//echo $form->field($articleDetail,'content')->textarea();
echo $form->field($articleDetail,'content')->widget('kucha\ueditor\UEditor',[
    'clientOptions' => [
        //编辑区域大小
        'initialFrameHeight' => '200',
        //设置语言
        'lang' =>'en', //中文为 zh-cn
        //定制菜单
        /*'toolbars' => [
            [
                'fullscreen', 'source', 'undo', 'redo', '|',
                'fontsize',
                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
                'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
                'forecolor', 'backcolor', '|',
                'lineheight', '|',
                'indent', '|'
            ],
        ]*/
    ]
]);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();