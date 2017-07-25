<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title>商品内容</title>
</head>
<body>

    <h1 style="text-align: center"><?=$goods->name?></h1>
    <div style="text-align: center">
        分类：<?=$goodsCategory->name?>&emsp;
        发布时间：<?=date('Y-m-d H:i:s',$goods->create_time)?>

    </div>

    <div style="text-indent: 2em">
        <?=$goodsIntro->content?>
    </div>

</body>
</html>


