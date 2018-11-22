<?php
    header("content-type:text/html;charset=utf-8");
    include_once('./public/sessionControl.php');
    
    date_default_timezone_set('PRC');
    if (isset($_POST) && !empty($_POST)) {
        include_once('./ConnectDB.php');
        $applyTime = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
        $building =$_POST['building'];
        $district =$_POST['district'];
        $roomNO =$_POST['roomNO'];
        $deviceName =$_POST['deviceName'];
        $descProblem = $_POST['content'];

        $applicant = $_SESSION['userName']; //从session中获取登陆人的作为故障申报者
        // $applicant = '李旭'; //作为临时值
       
        $query2 = "select * from classroominfo where building='{$building}' and district='{$district}' and roomNO='{$roomNO}'";
        // var_dump($query2);
        
        if ($con=connectDB()) {
            // 首先判断该教室是否存在
            $result = queryTable($con, $query2);
            if (count($result) ===1) {
                // 如果教室存在，则写入相关故障信息
                // 插入维修信息
                $query="INSERT INTO maintaininfo(building,district,roomNO,deviceName,descProblem,applicant,applyTime) VALUES ('{$building}','{$district}','{$roomNO}','{$deviceName}','{$descProblem}','{$applicant}','{$applyTime}')";
                if (insertTable($con, $query)) {
                    echo '<script> alert("😋故障信息已登记！"); window.location.href="./applyMaintain.php"</script>';
                } else {
                    header('location:404/404-1.html');
                }
            } else {
                echo '<script> alert("😭对不起，该教室不存在！"); window.location.href="./applyMaintain.php"</script>';
            }
            closeConnectDB($con);
        }
    }
    
 ?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./styles/bootstrap.min.css">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.bootcss.com/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="./styles/bootstrap.min.js"></script>
    <title>故障申报登记</title>
    <script src="./plugins/wangEditor.min.js"></script>
</head>

<body>
    <div class="jumbotron" style="background-color:#7fb4ff">
        <div class="container">
            <h1 class="font-weight-bold">设备故障报修</h1>
            <p>为了教学、服务教学</p>
            <?php include_once('./public/nav.php');?>
        </div>

    </div>
    <div class="container">
        <form action="applyMaintain.php" method="POST">
            <div class="form-group">
                <label for="exampleFormControlSelect1">楼宇：</label>
                <select class="form-control" name="building">
                    <option>思学楼</option>
                    <option>博学楼</option>
                    <option>图书馆</option>
                    <option>明理楼</option>
                    <option>明志楼</option>
                    <option>明德楼</option>
                    <option>明辨楼</option>
                </select>
            </div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">区域：</label>
                <select class="form-control" name="district">
                    <option>A</option>
                    <option>B</option>
                    <option>C</option>
                    <option>D</option>
                    <option>E</option>
                    <option>F</option>
                </select>
            </div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">教室：</label>
                <input class="form-control" type="text" name="roomNO" required oninvalid="setCustomValidity('故障教室是必填项')"
                    oninput="setCustomValidity('')">
            </div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">设备名称：</label>
                <select class="form-control" name="deviceName">
                    <option>投影仪</option>
                    <option>中控</option>
                    <option>讲台</option>
                    <option>教师机</option>
                    <option>中控</option>
                    <option>音箱</option>
                    <option>网络设备</option>
                    <option>其它</option>
                </select>
            </div>
            <div class="form-group">
                <label>详细描述设备故障信息：</label>
                <input type="hidden" name="content" id="editor_txt">
                <div id="editor" name="richContent">

                </div>

            </div>
            <input class="btn btn-primary" type="submit" value="提交" id="btn2">
            <input class="btn btn-primary" type="reset" value="重置" id="btn3">
        </form>
    </div>
    <script type="text/javascript">
        var E = window.wangEditor
        var editor = new E('#editor')
        // 或者 var editor = new E( document.getElementById('editor') )
        editor.create()

        editor.customConfig.uploadImgShowBase64 = true // 使用 base64 保存图片
        document.getElementById('btn2').addEventListener('click', function() {
            // 读取 text
            var content = editor.txt.text();
            document.getElementById('editor_txt').value = content;
        }, false)
    </script>
</body>

</html>