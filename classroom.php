<?php
    header("content-type:text/html;charset=utf-8");
    include_once('./ConnectDB.php');
    include_once('./public/public.php');
    include_once('./public/sessionControl.php');
    include_once('../jxyx/public/phpqrcode/qrlib.php');//生成二维码

    function Code()
    {
        $code=<<<CODE
设备名称  型号 设备编号
中控：HY-6000 20149905
计算机：同方I5 20181009
CODE;
        QRcode::png($code, 'class.png');
        $qpng = "class.png";
        echo '<td><img src="class.png"></td>';
    }

    
    
    $query ="select * from classroominfo";

    if ($con=connectDB()) {
        $result = queryTable($con, $query);
    }

    // 分页实现
    // 从url检查传送过来的page参数
     $page = isset($_GET['page'])?intval($_GET['page']):1;
     $page = max($page, 1);

    //  设置每一页显示的数目
    $pageSize=20;
    $offset=($page-1)*$pageSize;
    $sql = "select * from classroominfo limit {$offset}, {$pageSize}";
    $obj = queryTable($con, $sql);


    // var_dump($obj);
    $query3 = 'select id from classroominfo';
    $totalPages =mysqli_num_rows(mysqli_query($con, $query3));



?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
        crossorigin="anonymous">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.bootcss.com/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://cdn.bootcss.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU"
        crossorigin="anonymous">

    <link rel="stylesheet" href="./styles/dist/css/lightbox.min.css">
    <script src="./styles/dist/js/lightbox-plus-jquery.min.js"></script>
    <title>多媒体教室信息</title>
</head>

<body>

    <div class="jumbotron" style="background-color:#7fb4ff">
        <div class="container">
            <h1>多媒体教室信息一览表(成都校区)</h1>
            <p>为了教学、服务教学</p>
            <?php include_once('./public/nav.php');?>
        </div>

    </div>
    <div class="container">
        <table class="table table-hover table-border">
            <thead>
                <tr>
                    <th>序号</th>
                    <th>楼宇</th>
                    <th>区域</th>
                    <th>教室号</th>
                    <th>IP电话</th>
                    <th>座位数</th>
                    <th>建设年份</th>
                    <th>教室二维码</th>
                    <th>查看二维码</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($obj as $key => $value) {
                        echo "<tr>";
                        echo '<td>' . $value['id'] . '</td>';
                        echo '<td>' . $value['building'] . '</td>';
                        echo '<td>' . $value['district'] . '</td>';
                        echo '<td>' . $value['roomNO'] . '</td>';
                        echo '<td>' . $value['IPNO'] . '</td>';
                        echo '<td>' . $value['seats'] . '</td>';
                        echo '<td>' . date_format(date_create($value['updateTime']), 'Y年m月d日'). '</td>';
                        echo '<td><a class="btn btn-info" href="./QRcode.PHP?id='.$value['id'].'"'.'role="button">生成二维码</a></td>';
                        // echo '<td><i class="fas fa-stroopwafel"><a href="./QRcode.PHP">生成二维码</a></i></td>';
                        // echo '<td><a class="swipebox" href="./qr/232.png"><img src="./static/qr.png" alt="qr" width="20px"></a></td>';
                        echo '<td><a class="example-image-link" href="./qr/'.$value['id'].'.png" data-lightbox="example-1"><img class="example-image" src="./static/qr.png" alt="image-1" width=20px /></a></td>';
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
    <div class="container">
        <?php
                echo pagination($totalPages, $page, $pageSize, 5);
        ?>
    </div>
</body>

</html>