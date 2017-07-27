<form action="" method="get" style="float: right">
    <input type="text" name="keywords" placeholder="输入分类名" class=""/>
    <input type="submit" value="搜索" class="btn btn-sm btn-primary">
</form>
<?=\yii\bootstrap\Html::a('添加',['goods-category/add'],['class'=>'btn btn-sm btn-primary glyphicon glyphicon-plus'])?>
<table class="table table-bordered table-responsive">
    <tr>
        <td>ID</td>
        <td>分类名</td>
        <td>上级分类</td>
        <td>简介</td>
        <td>操作</td>
    </tr>
    <?php foreach($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=str_repeat('--',$model['depth']).$model['name']?></td>
        <td><?=$model->parent_id?></td>
        <td><?=$model->intro?></td>
        <td>
            <?=\yii\bootstrap\Html::a('编辑',['goods-category/edit','id'=>$model->id],['class'=>'btn btn-sm btn-warning glyphicon glyphicon-edit'])?>
            <?=\yii\bootstrap\Html::a('删除',['goods-category/delete','id'=>$model->id],['class'=>'btn btn-sm btn-danger glyphicon glyphicon-trash'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<div style="text-align: center">
    <?php
    //分页工具条
    echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'firstPageLabel'=>'首页','lastPageLabel'=>'末页',
        'prevPageLabel'=>'上一页','nextPageLabel'=>'下一页']);
    ?>

</div>