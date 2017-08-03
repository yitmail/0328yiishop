<?=\yii\bootstrap\Html::a('添加',['menu/add'],['class'=>'btn btn-sm btn-info glyphicon glyphicon-plus'])?>
<table   class="table table-responsive">
    <thead>
    <tr>
        <td>ID</td>
        <td>名称</td>
        <td>路由</td>
        <td>排序</td>
        <td>操作</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->label?></td>
        <td><?=$model->url?></td>
        <td><?=$model->sort?></td>
        <td>
            <?php
            if(Yii::$app->user->can('menu/edit')){?>
            <?=\yii\bootstrap\Html::a('编辑',['menu/edit','id'=>$model->id],['class'=>'btn btn-sm btn-warning glyphicon glyphicon-edit'])?>
            <?php } ?>
             <?php
            if(Yii::$app->user->can('menu/delete')){?>
            <?=\yii\bootstrap\Html::a('删除',['menu/delete','id'=>$model->id],['class'=>'btn btn-sm btn-danger glyphicon glyphicon-trash'])?>
            <?php } ?>
        </td>
    </tr>
        <?php foreach ($model->children as $child):?>
            <tr>
                <td><?=$child->id?></td>
                <td>---<?=$child->label?></td>
                <td><?=$child->url?></td>
                <td><?=$child->sort?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('编辑',['menu/edit','id'=>$child->id],['class'=>'btn btn-sm btn-warning glyphicon glyphicon-edit'])?>
                    <?=\yii\bootstrap\Html::a('删除',['menu/delete','id'=>$child->id],['class'=>'btn btn-sm btn-danger glyphicon glyphicon-trash'])?>
                </td>
            </tr>
        <?php endforeach;?>
    <?php endforeach;?>
    </tbody>
</table>
