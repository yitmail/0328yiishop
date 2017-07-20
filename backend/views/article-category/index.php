<?=\yii\bootstrap\Html::a('添加',['article-category/add'],['class'=>'btn btn-sm btn-primary'])?>
<table class="table table-bordered table-responsive">
    <tr>
        <td>ID</td>
        <td>分类名</td>
        <td>简介</td>
        <td>排序</td>
        <td>状态</td>
        <td>操作</td>
    </tr>
    <?php foreach($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=$model->intro?></td>
        <td><?=$model->sort?></td>
        <td><?=\backend\models\ArticleCategory::getStatusOptions()[$model->status]?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['article-category/edit','id'=>$model->id],['class'=>'btn btn-sm btn-warning'])?>
            <?=\yii\bootstrap\Html::a('删除',['article-category/delete','id'=>$model->id],['class'=>'btn btn-sm btn-danger'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<div style="text-align: center">
    <?php
        echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'firstPageLabel'=>'首页','lastPageLabel'=>'末页',
            'prevPageLabel'=>'上一页','nextPageLabel'=>'下一页']);
    ?>
</div>