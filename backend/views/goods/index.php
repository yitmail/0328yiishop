<form action="" method="get" style="float: right">
    <input type="text" name="name" placeholder="商品名" class=""/>
    <input type="text" name="sn" placeholder="货号" class=""/>
    <input type="text" name="shop_price" placeholder="商品价" class=""/>
    <input type="submit" value="搜索" class="btn btn-sm btn-primary">
</form>
<?=\yii\bootstrap\Html::a('添加',['goods/add'],['class'=>'btn btn-sm btn-primary '])?>
<?=\yii\bootstrap\Html::a('回收站',['goods/recycle'],['class'=>'btn btn-sm btn-primary'])?>
<table class="table table-bordered table-responsive">
    <tr>
        <td>ID</td>
        <td>商品名</td>
        <td>货号</td>
        <td>LOGO</td>
        <td>商品价</td>
        <td>库存</td>
        <td>是否在售</td>
        <td>状态</td>
        <td>排序</td>
        <td>添加时间</td>
        <td>操作</td>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=$model->sn?></td>
        <td><?=\yii\bootstrap\Html::img($model->logo?$model->logo:'/upload/default.jpg',['height'=>50])?></td>
        <td><?=$model->shop_price?></td>
        <td><?=$model->stock?></td>
        <td><?=$model->is_on_sale?'上架':'下架'?></td>
        <td><?=$model->status?></td>
        <td><?=$model->sort?></td>
        <td><?=date('Y-m-d H:i:s',$model->create_time)?></td>
        <td>
            <?=\yii\bootstrap\Html::a('查看',['goods/show','id'=>$model->id],['class'=>'btn btn-sm btn-info glyphicon glyphicon-film'])?>
            <?=\yii\bootstrap\Html::a('相册',['goods/gallery','id'=>$model->id],['class'=>'btn btn-sm btn-info glyphicon glyphicon-picture'])?>
            <?=\yii\bootstrap\Html::a('编辑',['goods/edit','id'=>$model->id],['class'=>'btn btn-sm btn-warning glyphicon glyphicon-edit'])?>
            <?=\yii\bootstrap\Html::a('删除',['goods/delete','id'=>$model->id],['class'=>'btn btn-sm btn-danger glyphicon glyphicon-trash'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<div style="text-align: center">
    <?php
    //分页工具条
    echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'firstPageLabel'=>'首页',
        'lastPageLabel'=>'末页','prevPageLabel'=>'上一页','nextPageLabel'=>'下一页'
    ]);
    ?>
</div>