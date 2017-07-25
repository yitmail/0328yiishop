<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'goods_id');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
?>
<table class="table table-bordered table-responsive">
    <tr>
        <td>ID</td>

    </tr>
    <?php foreach($models as $m):?>
    <tr>
        <td><?=$m->id?></td>

    </tr>
<?php endforeach;?>
</table>