<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title>文章内容</title>
</head>
<body>

    <h1 style="text-align: center"><?=$article->name?></h1>
    <div style="text-align: center">
        分类：<?=$article->articleCategory->name?>&emsp;
        发布时间：<?=date('Y-m-d H:i:s',$article->create_time)?>

    </div>

    <div style="text-indent: 2em">
        <?=$articleDetail->content?>
    </div>

</body>
</html>


