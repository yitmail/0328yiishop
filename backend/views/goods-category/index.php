<?=\yii\bootstrap\Html::a('添加',['goods-category/add'],['class'=>'btn btn-sm btn-primary'])?>
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
        <td><?=$model->name?></td>
        <td><?=$model->parent_id?></td>
        <td><?=$model->intro?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$model->id],['class'=>'btn btn-sm btn-warning'])?>
            <?=\yii\bootstrap\Html::a('删除',['goods-category/delete','id'=>$model->id],['class'=>'btn btn-sm btn-danger'])?>
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