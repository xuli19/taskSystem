<?php
include_once('./ConnectDB.php');
include_once('./public/sessionControl.php');

$query = "select * from maintaininfo ORDER BY status DESC";
$query2 ="select id from maintaininfo where status='未受理'";
$query3 ="select id from maintaininfo where status='已完成'";
$query4 ="select id from maintaininfo where status='解决中'";

$query_total = "select id from maintaininfo";


/**
 * 做分页功能
 * todo 从数据库按照页面size，查出数据 主要通过select * from table_name limit offset,pagesize; 首先需要从url中传一个page变量
 */
$page = isset($_GET['page'])?intval($_GET['page']):1;  //取到当前页数
$page = max($page, 1); //如果页面小于等于0怎么办，页面必须至少为1；

$pageSize= 13; //设定每页显示的条数；

// page=1  limit 0,9;
// page=2 limit 10,19;
// page=3 limit 20,29;
// 发现上述规律 （page-1）*pageSize = offset

$offset= ($page-1)*$pageSize;

$sql = sprintf("select * from maintaininfo ORDER BY status DESC LIMIT %s, %s", $offset, $pageSize);
// var_dump($sql);exit;


if ($con=connectDB()) {
    $results = queryTable($con, $sql);
    $unsolved = mysqli_num_rows(mysqli_query($con, $query2));
    $solved = mysqli_num_rows(mysqli_query($con, $query3));
    $solving = mysqli_num_rows(mysqli_query($con, $query4));
    $total = mysqli_num_rows(mysqli_query($con, $query_total));

    // var_dump($_SESSION['userName']);
    if (!empty($_POST)) {
        // var_dump($_POST);
        $id= $_POST['id'];
        if ($_POST['receive'] == '受理故障') {
            // echo "12";exit;
            $query_receive = "UPDATE maintaininfo SET maintaininfo.recipient='{$_SESSION['userName']}', maintaininfo.status='解决中',acceptTime=now() WHERE maintaininfo.id='".$id."'";
            mysqli_query($con, $query_receive);
            echo '<script> window.location.href="./maintaininfo.php"</script>';
        }
        if ($_POST['receive'] == '标记完成') {
            // echo "121";exit;
            $query_finished = "UPDATE maintaininfo SET  maintaininfo.status='已完成', acceptTime=now() WHERE maintaininfo.id='".$id."'";
            mysqli_query($con, $query_finished);
            echo '<script> window.location.href="./maintaininfo.php"</script>';
        }
    }
}


/**
 * todo 分页函数封装
 * ! 首先取得分页的总条数
 * ! 获取当前页数，第几页
 * ! 每页显示的条目数
 * ! 显示分页按钮的数目
 */
function pagination($total, $currentPage, $pageSize, $show=6)
{
    $paginationStr = ""; //函数最终返回的是一个html分页器;

    //写死翻页路径，后续可以更改
    $url = (string)parse_url($_SERVER['REQUEST_URI'])['path'].'?page=';

    //仅当总条数大于每页条数才有分页的必要
    if ($total>$pageSize) {
        $totalPages = ceil($total/$pageSize);

        //如果page传的参数大于总页数怎么办？大于，那么就应把总页数赋值给当前页；小于的话那就默认使用page传过来的值；
        $currentPage = $currentPage>$totalPages?$totalPages:$currentPage;

        //html分页器的样式前缀
        $paginationStr .='<div class="Page pagination pagination-sm example">';
        $paginationStr .='<ul class="pagination" style="margin:0 auto">';

        //分页起始页
        $from = max(($currentPage-intval($show/2)), 1);
        //分页结束页
        $to = $from+$show-1;

        //当to结束页大于总页数的时候
        if ($to>$totalPages) {
            $to = $totalPages;
            $from = max(1, ($to-$show+1));
        }

        
        //需要判断当前页与第一页、最后页的关系，
        if ($currentPage>1) {
            //只有当currentpage大于1的时候才有显示首页、第一页的必要；
            $paginationStr .='<li class="page-item"><a class="page-link" href='.$url.'1'.'>首页</a></li>';
            $paginationStr .="<li class='page-item'><a class='page-link' href='".$url.($currentPage-1)."'>上一页</a></li>";
        }

        if ($from>1) {
            //分页器左边就可以显示...
            $paginationStr .= '<li class="page-item page-link" >...</li>';
        }

        for ($i=$from; $i<=$to; $i++) {
            if ($i != $currentPage) {
                $paginationStr .="<li class='page-item' ><a class='page-link' href='".$url.$i."'>{$i}</a></li>";
            } else {
                $paginationStr .= "<li  class='page-item'><span class='page-link' style='background-color:#7fb4ff' >{$i}</span></li>";
            }
        }
        if ($to<$totalPages) {
            //分页器右边就可以显示...
            $paginationStr .= '<li class="page-item page-link" >...</li>';
        }
       

        if ($currentPage<$totalPages) {
            //只有当前页小于尾页的时候，才有必要显示后一页、尾页；
            $paginationStr .="<li class='page-item'><a class='page-link' href='".$url.($currentPage+1)."'>下一页</a></li>";
            $paginationStr .="<li class='page-item'><a class='page-link' href=".$url.$totalPages.">尾页</a></li>";
        }

        //html分页器的样式后缀
        $paginationStr .='</ul>';
        $paginationStr .='</div>';
    } else {
        echo "fefe";
    }

    return $paginationStr;
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
    <title>维修信息</title>
    <style>
        .unsolved {
            background-color: #FF6633;
        }

        .solved {
            background-color: #CCCCCC;
        }

        .solving {
            background-color: #33CC99;
        }

        .size {
            width: 24px;
            height: 24px;
        }

        .thead {
            background-color: #00CCFF;
        }

        /* .table tbody tr td {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        } */
    </style>
</head>

<body>
    <div class="jumbotron" style="background-color:#7fb4ff">
        <h1 class="font-weight-bold">维修信息</h1>
        <p>为了教学、服务教学</p>
        <?php include_once('./public/nav.php');?>
    </div>
    <div class="container-fluided">
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>未受理<img src="./static/unsolved.png" alt="" srcset="" class="size"></b>

                <span class="badge badge-primary badge-pill">
                    <?php echo $unsolved ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>解决中<img src="./static/solving.png" alt="" srcset="" class="size"></b>

                <span class="badge badge-primary badge-pill">
                    <?php echo $solving ?></span>
            </li>

            <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>已完成<img src="./static/solved.png" alt="" srcset="" class="size"></b>

                <span class="badge badge-primary badge-pill">
                    <?php echo $solved ?></span>
            </li>
        </ul>
    </div>
    <div class="container-fluided">
        <div class="table-responsive-lg">
            <table class="table table-hover">
                <thead>
                    <tr class="thead text-white">
                        <th>操 作</th>
                        <th>序 号</th>
                        <th>区 域</th>
                        <th>设备名称</th>
                        <th>故障描述</th>
                        <th>申报人</th>
                        <th>申报时间</th>
                        <th>故障状态</th>
                        <th>受理人</th>
                        <th>受理时间</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 状态：已完成是灰色；未受理是红色；解决中是绿色 -->
                    <?php
                    foreach ($results as $key => $value) {
                        // 如果状态是未受理，那么就可以受理故障，并将状态标记为：解决中
                        if ($value['status']=='未受理') {
                            echo "<tr class='unsolved'>";
                            echo '<td>'.'<form method="POST" action="#"><input class="btn btn-secondary"  type="submit" name="receive" value="受理故障"/> <input class="btn btn-secondary"  type="hidden" name="id" value="'.$value['id'].'"/></form></td>';
                        } elseif ($value['status']=='解决中') {
                            // 如果状态是解决中，那么就只可以标记为：完成；
                            echo "<tr class='solving'>";
                            echo '<td>'.'<form method="POST" action="#"><input class="btn btn-secondary"  type="submit" name="receive" value="标记完成"/> <input class="btn btn-secondary"  type="hidden" name="id" value="'.$value['id'].'"/></form></td>';
                        } else {
                            echo "<tr class='solved'>";
                            echo '<td>'.'<form ><input class="btn btn-secondary"  type="reset" name="submit" value="已完成   "/> </form>'.'</td>';
                        }
                        
                        echo '<td>' . $value['id']. '</td>';
                        echo '<td>' . ($value['building'].$value['district'].$value['roomNO']) . '</td>';
                        echo '<td>' . ($value['deviceName']) . '</td>';
                        echo '<td>' . ($value['descProblem']) . '</td>';
                        echo '<td>' . ($value['applicant']) . '</td>';
                        echo '<td>' . ($value['applyTime']) . '</td>';
                        echo '<td>' . ($value['status']) . '</td>';
                        echo '<td>' . ($value['recipient']) . '</td>';
                        echo '<td>' . ($value['acceptTime']) . '</td>';
                        // echo '<td>'.'<form method="POST" action="#"><input class="btn btn-secondary"  type="submit" name="submit" value="受理故障"/> </form>'.'</td>';
                        echo '<tr/>';
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="container-fluided">
        <div class="row">
            <div class="col align-self-center">
                <?php
                    echo(pagination($total, $page, $pageSize, $show=6));
                ?>
            </div>           
        </div>

    </div>

</body>

</html>