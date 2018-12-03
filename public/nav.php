<?php
    $links = array();
    $links[] = array('首页','index.php');
    $links[] = array('故障申报','applyMaintain.php');
    $links[] = array('故障信息','maintaininfo.php');
    $links[] = array('教室信息','classroom.php');
    $links[] = array('用户中心','ManageUser.php');

    // var_dump($links);
    $self_page=basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css">
    <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.js"></script>
    <style>
        ul .nav {
            display:inline-flex;
        }
        ul li a {
            color:white;
        }
    </style>
</head>

<body>
    <ul>
        <?php
foreach ($links as $link) {
    // printf('<li %s><a href="%s">%s</a></li>' , $self_page==$link[1]?' class="on"':'' , $link[1], $link[0]);
    printf('<ul class="nav nav-pills"><li class="nav-item"><a class="nav-link %s" href="%s">%s</a></li></ul>', $self_page==$link[1]?'active':'', $link[1], $link[0]);
    // printf('<div class="ui menu"><div class="header item"><a class="item %s" href="%s">%s</a></div></div>',$self_page==$link[1]?'on':'', $link[1], $link[0]);
    // echo "\n";
}
?>
    </ul>
</body>

</html>