<?php

include_once('./ConnectDB.php');



session_start();

//设置session会话过期
define('LIFETIME', 600*2);
// $lifeTime =60;
setcookie(session_name(), session_id(), time()+LIFETIME, "/");

/**
 * 判断用户是否已经登录
 */
if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    header('location:ManageUser.php');
    // header('Location:ManageUser.php?level='.base64_encode($level+102));
    exit;
}

/**
 *
 * 用户登录校验
 */
if (isset($_POST)) {
    if (!empty($_POST['user']) && !empty($_POST['password'])) {
        $user = trim($_POST['user']);
        $password = trim($_POST['password']);
        //数据库连接
        $con = connectDB();
        if ($con) {
            //查询用户是否存在,密码是否正确
            $query = 'select count(*) as "login" from users where userID='.$user .' and password=' . 'PASSWORD('.$password.')';
            $result = queryTable($con, $query);
            $result = (int)$result[0]['login'];
            // var_dump($result);exit;
            $query2 = "select username from users where userID='{$user}'";
            $name = queryTable($con, $query2);
           
            
           
            if (!empty($result) && $result===1 && !empty($name)) {

                //更新用户登录时间
                $updateLoginTime ="update users set lastlogin = NOW() WHERE userID ='{$user}'";
                $updateTime = mysqli_query($con, $updateLoginTime);

                //更新用户登录ip
                $query_ip = "update users set ip = '{$_SERVER["REMOTE_ADDR"]}' WHERE userID ='{$user}'";
                // var_dump($query_ip);exit;
                $result_ip = mysqli_query($con, $query_ip);

                //取出用户的权限
                $queryLevel = "select level from users where userID='{$user}'";
                $level = queryTable($con, $queryLevel);
                $level = (int)$level[0]['level'];


                $_SESSION['user']= $result;
                $_SESSION['userName']=$name[0]['username'];
                $_SESSION['level']=base64_encode($level+102);
                //判断用户权限是否足够
                //这里不适用传参的方式，而是使用session把变量传给页面；
                // header('Location:ManageUser.php?level='.base64_encode($level+102));
                header('location:ManageUser.php');
                exit;
            } else {
                echo '<script> alert("😭用户不存在或密码错误！"); window.location.href="/login.php"</script>';
                exit;
            }
        }
    }
}

// if(isset($_GET)) {
//     session_destroy();
//     header('location:login.php');
// }

?>

<!DOCTYPE html>
<html lang="en">

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
    <title>登录</title>
</head>

<body>
    <div class="jumbotron" style="background-color:#7fb4ff">
        <h1 class="text-center font-weight-bold" style="color:white">用户登录</h1>
    </div>
    <div class="container">
        <form action="" method="post">
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">用户名</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="user" placeholder="用户名" required oninvalid="setCustomValidity('请输入用户名')"
                        oninput="setCustomValidity('')">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword" class="col-sm-2 col-form-label">密码</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="password" placeholder="密码" required oninvalid="setCustomValidity('请输入密码')"
                        oninput="setCustomValidity('')">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10">
                    <input class="btn btn-info" type="submit" name='submit' value="登录">
                    <input class="btn btn-info" type="reset" name='reset' value="重置">
                </div>
            </div>
        </form>
    </div>

</body>

</html>