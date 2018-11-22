<?php
include_once('./login.php');

    $con = connectDB(HOST, USER, PASSWORD, DB);
    $query = 'select * from classroominfo';
    $dataset = queryTable($con, $query);
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
    <title>教室管理</title>
</head>

<body>
    <div class="jumbotron"  style="background-color:#7fb4ff">
        <div class="p-2 text-white text-center">
            <h1 class="font-weight-bold">多媒体教室管理</h2>
            <p class=" p-3 mb-2 text-white">
                管理人员可以在该页面查看全校多媒体教室信息
            </p>
        </div>

    </div>
    <div class="container">
        <table class="table ">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">序号</th>
                    <th scope="col">楼宇</th>
                    <th scope="col">区域</th>
                    <th scope="col">教室号</th>
                    <th scope="col">座位数</th>
                    <th scope="col">改造时间</th>
                    <th scope="col">设备信息</th>
                </tr>
            </thead>
            <tbody class="border border-secondary">
                <?php
                    foreach ($dataset as $value) {
                        echo '<tr>';
                        echo '<td>' . $value['id'] . '</td>';
                        echo '<td>' . ($value['building']) . '</td>';
                        echo '<td>' . $value['district'] . '</td>';
                        echo '<td>' . ($value['roomNO']) . '</td>';
                        echo '<td>' . ($value['seats']) . '</td>';
                        echo '<td>' . ($value['updateTime']) . '</td>';
                        echo '<td>' . '<a href="">查看信息</a>' . '</td>';
                        echo '<tr/>';
                    }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>