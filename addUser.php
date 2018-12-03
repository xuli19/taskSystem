<?php
    include_once('./ConnectDB.php');
        // if ($_POST['username']!=null && $_POST['password']!=null && $_POST['carNO']!=null) {
            if (isset($_POST['username'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $userNO = $_POST['cardNO'];
                switch ($_POST['gender']) {
                    case '2':
                        $sex ='保密';
                        break;
                    case '1':
                        $sex ='男';
                        break;

                    case '0':
                        $sex ='女';
                        break;
                    default:
                        break;
                }
        
                // $userquery = sprintf('INSERT INTO users(userID,username,`PASSWORD`,sex,regtime) VALUES(%s,"%s",PASSWORD("%s"),"%s",%s)', $userNO, $username, $password, $sex, 'NOW()');   MYSQL 版本以后不再使用
                $userquery = sprintf('INSERT INTO users(userID,username,`PASSWORD`,sex,regtime) VALUES(%s,"%s",SHA1("%s"),"%s",%s)', $userNO, $username, $password, $sex, 'NOW()');
                // var_dump($userquery);exit;
                if ($con=connectDB()) {                    ;
                    if ($result = insertTable($con, $userquery)) {
                        echo '<script> alert("😋注册成功！"); window.location.href="./ManageUser.php"</script>';
                    } else {
                        echo '<script> alert("😭遇到错误！"); window.location.href="./addUser.php"</script>';
                    }
                }
            }
    
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css">
    <link rel="stylesheet" href="./styles/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.js"></script>
    <title>添加用户</title>
</head>

<body>
    <div class="jumbotron">
        <h2>添加用户</h2>
    </div>
    <div class="container">
        <form class="ui form" method='POST'>
            <div class="required field">
                <label>姓名</label>
                <input type="text" name="username" placeholder="姓名" required oninvalid="setCustomValidity('请输入姓名')"
                    oninput="setCustomValidity('')">
            </div>
            <div class="required field">
                <label>一卡通账号</label>
                <input type="number" name="cardNO" placeholder="一卡通账号，数字类型" required oninvalid="setCustomValidity('请检查一卡通账号')"
                    oninput="setCustomValidity('')">
            </div>
            <div class="required field">
                <label>密码</label>
                <input type="password" name="password" placeholder="密码" required oninvalid="setCustomValidity('请输入密码')"
                    oninput="setCustomValidity('')">
            </div>
            <div class="required field">
                <label>性别</label>
                <select name="gender">
                    <option value="2" default>保密</option>
                    <option value="1">男</option>
                    <option value="0">女</option>
                </select>
            </div>
            <div class="required field">
                <div class="ui checkbox">
                    <input type="checkbox" required oninvalid="setCustomValidity('请确认是否同意遵守本网站要求')" oninput="setCustomValidity('')">
                    <label>同意遵守本网站规定的相关原则</label>
                </div>
            </div>
            <button class="ui button" type="submit">提交</button>
            <button class="ui button" type="reset">重置</button>
        </form>

    </div>

</body>

</html>