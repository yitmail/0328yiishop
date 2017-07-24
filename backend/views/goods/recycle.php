<form action="" method="get" style="float: right">
    <input type="text" name="keywords" placeholder="输入商品名" class=""/>
    <input type="submit" value="搜索" class="btn btn-sm btn-primary">
</form>
<table class="table table-bordered table-responsive">
    <tr>
        <td>ID</td>
        <td>商品名</td>
        <td>货号</td>
        <td>LOGO图片</td>
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
            <?=\yii\bootstrap\Html::a('恢复',['goods/cancel','id'=>$model->id],['class'=>'btn btn-sm btn-warning'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
