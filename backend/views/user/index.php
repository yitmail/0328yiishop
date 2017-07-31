<?=\yii\bootstrap\Html::a('添加',['user/add'],['class'=>'btn btn-sm btn-info'])?>
<table class="table table-bordered table-responsive">
    <tr>
        <td>ID</td>
        <td>用户名</td>
        <td>邮箱</td>
        <td>状态</td>
        <td>创建时间</td>
        <td>更新时间</td>
        <td>最后登录时间</td>
        <td>最后登录IP</td>
        <td>操作</td>
    </tr>
    <?php foreach($users as $user):?>
        <tr>
            <td><?=$user->id?></td>
            <td><?=$user->username?></td>
            <td><?=$user->email?></td>
            <td><?=\backend\models\User::$status_options[$user->status]?></td>
            <td><?=date('Y-m-d H:i:s',$user->created_at)?></td>
            <td><?=date('Y-m-d H:i:s',$user->updated_at)?></td>
            <td><?=date('Y-m-d H:i:s',$user->last_login_time)?></td>
            <td><?=long2ip($user->last_login_ip)?></td>
            <td>
                <?php
                if(Yii::$app->user->can('user/edit')){?>
                <?=\yii\bootstrap\Html::a('编辑',['user/edit','id'=>$user->id],['class'=>'btn btn-sm btn-warning'])?>
                <?php } ?>
                <?php
                if(Yii::$app->user->can('user/delete')){?>
                <?=\yii\bootstrap\Html::a('删除',['user/delete','id'=>$user->id],['class'=>'btn btn-sm btn-danger'])?>
                <?php } ?>
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