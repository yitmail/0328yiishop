<?=\yii\bootstrap\Html::a('添加',['rbac/add-permission'],['class'=>'btn btn-sm btn-info glyphicon glyphicon-plus'])?>
<table id="table_id" class="table table-responsive" >
    <thead>
    <tr>
        <td>名称(路由)</td>
        <td>描述</td>
        <td>创建时间</td>
        <td>操作</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->name?></td>
        <td><?=$model->description?>
        <td><?=date('Y-m-d H:i:s',$model->createdAt)?>
        <td>
            <?php
            if(Yii::$app->user->can('rbac/edit-permission')){?>
            <?=\yii\bootstrap\Html::a('编辑',['rbac/edit-permission','name'=>$model->name],['class'=>'btn btn-sm btn-warning glyphicon glyphicon-edit'])?>
            <?php } ?>
            <?php
            if(Yii::$app->user->can('rbac/delete-permission')){?>
            <?=\yii\bootstrap\Html::a('删除',['rbac/delete-permission','name'=>$model->name],['class'=>'btn btn-sm btn-danger glyphicon glyphicon-trash'])?>
            <?php } ?>
        </td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php
/**
 * @var $this \yii\web\View
 */
#$this->registerCssFile('//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
$this->registerCssFile('//cdn.datatables.net/1.10.15/css/dataTables.bootstrap.css');
$this->registerJsFile('//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJsFile('//cdn.datatables.net/1.10.15/js/dataTables.bootstrap.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs('$("#table_id").DataTable({
language: {
        url: "//cdn.datatables.net/plug-ins/1.10.15/i18n/Chinese.json"
    }
});');