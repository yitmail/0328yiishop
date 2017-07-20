
<form action="" method="get" style="float: right">
    <input type="text" name="keywords" class=""/>
    <input type="submit" value="搜索" class="btn btn-sm btn-primary">
</form>
<?=\yii\bootstrap\Html::a('添加',['article/add'],['class'=>'btn btn-sm btn-primary'])?>
<table class="table table-bordered table-responsive">
    <tr>
        <td>ID</td>
        <td>文章名</td>
        <td>简介</td>
        <td>文章分类ID</td>
        <td>排序</td>
        <td>状态</td>
        <td>创建时间</td>
        <td>操作</td>
    </tr>
    <?php foreach($articles as $article):?>
    <tr>
        <td><?=$article->id?></td>
        <td><?=$article->name?></td>
        <td><?=$article->intro?></td>
        <td><?=$article->articleCategory->name?></td>
        <td><?=$article->sort?></td>
        <td><?=\backend\models\Article::getStatusOptions()[$article->status]?></td>
        <td><?=date('Y-m-d H:i:s',$article->create_time)?></td>
        <td>
            <?=\yii\bootstrap\Html::a('查看',['article/show','id'=>$article->id],['class'=>'btn btn-sm btn-info'])?>
            <?=\yii\bootstrap\Html::a('修改',['article/edit','id'=>$article->id],['class'=>'btn btn-sm btn-warning'])?>
            <?=\yii\bootstrap\Html::a('删除',['article/delete','id'=>$article->id],['class'=>'btn btn-sm btn-danger'])?>
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