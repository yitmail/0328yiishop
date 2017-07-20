<?=\yii\bootstrap\Html::a('添加',['brand/add'],['class'=>'btn btn-sm btn-primary'])?>
<table class="table table-bordered table-responsive">
    <tr>
        <td>ID</td>
        <td>品牌名</td>
        <td>简介</td>
        <td>logo</td>
        <td>排序</td>
        <td>状态</td>
        <td>操作</td>
    </tr>
    <?php foreach($brands as $brand):?>
    <tr>
        <td><?=$brand->id?></td>
        <td><?=$brand->name?></td>
        <td><?=$brand->intro?></td>
        <td><?=\yii\bootstrap\Html::img($brand->logo?$brand->logo:'/upload/default.jpg',['height'=>50])?></td>
        <td><?=$brand->sort?></td>
        <td><?=\backend\models\Brand::getStatusOptions()[$brand->status]?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['brand/edit','id'=>$brand->id],['class'=>'btn btn-sm btn-warning'])?>
            <?=\yii\bootstrap\Html::a('删除',['brand/delete','id'=>$brand->id],['class'=>'btn btn-sm btn-danger'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<div style="text-align: center">
    <?php
        //分页工具条
        echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页',
            'prevPageLabel'=>'上一页','firstPageLabel'=>'首页','lastPageLabel'=>'末页']);
    ?>
</div>