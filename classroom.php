<?php
    header("content-type:text/html;charset=utf-8");
    include_once('./ConnectDB.php');
    include_once('./public/public.php');
    include_once('./public/sessionControl.php');
    
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
                        echo '<td>' . date_format(date_create($value['updateTime']),'Y年m月d日'). '</td>';
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