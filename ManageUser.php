<?php
header("content-type:text/html;charset=utf8");
include_once('./ConnectDB.php');
include_once('./public/randomColor.php');
include_once('./public/sessionControl.php');
/**
 * 判断用户是否已经登录
 */
if (!isset($_SESSION['user']) || empty($_SESSION['user']) || empty($_SESSION['level'])) {
    header('Location:login.php');
    exit;
} else {

    //权限
    $level = base64_decode($_SESSION['level'])-102; //通过解析session带过来的变量判断权限是否足够;


    $con = connectDB(HOST, USER, PASSWORD, DB);
    $query = 'select * from users';
    $dataset = queryTable($con, $query);

    $person_query = 'select DISTINCT maintaininfo.recipient from maintaininfo '; //从维修表中查询受理故障的人员名单
    $personResults = queryTable($con, $person_query);//受理故障人员名单，数组
    $visualDataset = []; //对应人员的可视化数据集
    $person =[]; //人员数组集
    foreach ($personResults as $key => $values) {
        foreach ($values as $value) {
            if ($value != null) {
                $person[]=$value;
                $queryVisual = 'select count(maintaininfo.id) as data from maintaininfo WHERE maintaininfo.recipient="'.$value.'"';
                $visulData=queryTable($con, $queryVisual);
                $visualDataset[]=(int)$visulData[0]['data'];
            }
        }
    }
    // 查询故障总数
    $query_all_maintain = "select count(id) as all_maintainInfo from  maintaininfo";
    $results_all= queryTable($con, $query_all_maintain);
    //查询个人共计受理故障数量
    $query_personal  ='select count(id) as personal_maintainInfo from  maintaininfo WHERE maintaininfo.recipient="'.$_SESSION['userName'].'"';
    $results_personal = queryTable($con, $query_personal);
    //删除用户
    if (isset($_POST['submit'])) {
        var_dump($_POST);exit;
    }
}

/**
 * todo 依据人员数生成不同颜色的柱状图
 */

$colorBg=randomColor(count($person));
$colorBorder=randomColor(count($person), 2, 11);
// var_dump($colorBg);exit;
/**
 * 实现页面注销功能
 */
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("location:login.php");
}
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
    <title>用户管理</title>
    <style>
        #table {
            border: 1px solid #CCCCCC;
        }
    </style>
</head>

<body>
    <div class="jumbotron" style="background-color:#7fb4ff">
        <div class="container">
            <h1>用户管理平台</h1>
            <p>为了教学、服务教学</p>
            <?php include_once('./public/nav.php');?>
            
            <div class="row float-right">
            <button class='btn btn-primary' role='alert'>欢迎您，<?php echo $_SESSION['userName']; ?></button>
                <form action="" method="POST" class='from-control col-sm-1'>
                    <button class="btn btn-info" name="logout">注销</button>
                </form>

            </div>
        </div>

    </div>
    <?php if ($level === 0) {?>
    <div class="container">     
        <table class="table table-striped" id="table">
            <thead class="thead-dark" id="thead">
                <tr>
                    <th>序号</th>
                    <th>姓名</th>
                    <th>性别</th>
                    <th>一卡通号码</th>
                    <th>上一次登录时间</th>
                    <th>上一次登录IP</th>
                    <th>用户操作</th>
                </tr>
            </thead>
            <tbody>
                <tr id="add"></tr>
                <?php
                    // 读取所有用户
                foreach ($dataset as $value) {
                    // var_dump($value);
                    echo '<tr>';
                    echo '<td>' . $value['ID'] . '</td>';
                    echo '<td>' . ($value['username']) . '</td>';
                    echo '<td>' . $value['sex'] . '</td>';
                    echo '<td>' . ($value['userID']) . '</td>';
                    echo '<td>' . ($value['lastlogin']) . '</td>';
                    echo '<td>' . ($value['ip']) . '</td>';
                    echo '<td><a href="delUser.php?at='.base64_encode($value['ID']+31415926).'" class="btn btn-primary">删除用户</a></td>';
                    // echo '<td><a href="delUser.php?at='.base64_encode($value['ID']+31415926).'" class="btn btn-primary">删除用户</a>' . ' ' . '<a href="" class="btn btn-primary">编辑用户</a></td>';
                    echo '<tr/>';
                }
                ?>

            </tbody>
        </table>
        <div class="form-group">
          <label for="addUser" class="font-weight-bold">账号管理</label>
          <a name="" id="addUser" class="btn btn-primary" href="./addUser.php" role="button" aria-describedby="helpId" style="display:block">添加用户</a>
          <small id="helpId" class="text-muted">如果需要编辑用户，只需要删除该用户，重新创建即可！</small>
        </div>
    </div>
    <?php }?>

    <!-- 显示统计图 -->
    <div class="container">
        <ul class="list-group">
            <!-- 整体完成情况 -->
            <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color:#000000">
                <b class="text-white">共计受理完成故障：</b>
                <span class="badge badge-primary badge-pill">
                    <?php  print_r($results_all[0]["all_maintainInfo"]); echo '&#8194;项' ?></span>
            </li>
            <!-- 完成数量 -->
            <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color:#009999">
            <b class="text-white">您共计完成故障：</b>
                <span class="badge badge-primary badge-pill">
                <?php print_r($results_personal[0]["personal_maintainInfo"]); echo '&#8194;项' ?></span>
            </li>
            <!-- 排名 -->
            <!-- /**/**
             * todo 个人任务完成排名功能后续再开发
             */ -->
            <li class="list-group-item d-flex justify-content-between align-items-center">
            <b class="text-gray">您的任务排第：</b>
                <span class="badge badge-primary badge-pill">
                    暂未开放排名</span>
            </li>
            <!-- 统计图 -->
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <canvas id="myChart" width="400" height="200"></canvas>
            </li>
        </ul>
    </div>

    <!-- 统计图 -->
    <script>
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($person, JSON_UNESCAPED_UNICODE) ?> ,
                datasets: [{
                    label: '故障受理统计图',
                    data: <?php echo json_encode($visualDataset, JSON_UNESCAPED_UNICODE) ?> ,
                    backgroundColor: <?php echo json_encode($colorBg) ?> ,
                    borderColor: <?php echo json_encode($colorBorder) ?> ,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
</body>

</html>