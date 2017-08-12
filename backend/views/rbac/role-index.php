<?=\yii\bootstrap\Html::a('添加',['rbac/add-role'],['class'=>'btn btn-sm btn-info glyphicon glyphicon-plus'])?>
<table class="table">
    <tr>
        <td>名称</td>
        <td>描述</td>
        <td>创建时间</td>
        <td>操作</td>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->name?></td>
        <td><?=$model->description?></td>
        <td><?=date('Y-m-d H:i:s',$model->createdAt)?></td>
        <td>
            <?=\yii\bootstrap\Html::a('编辑',['rbac/edit-role','name'=>$model->name],['class'=>'btn btn-sm btn-warning glyphicon glyphicon-edit'])?>
            <?=\yii\bootstrap\Html::a('删除',['rbac/delete-role','name'=>$model->name],['class'=>'btn btn-sm btn-warning glyphicon glyphicon-trash'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>