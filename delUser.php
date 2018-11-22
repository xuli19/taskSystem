<?php
    include_once('./ConnectDB.php');
    if (isset($_GET['at'])) {
        //对传过来的加密参数进行解密        
        $id = (int)base64_decode($_GET['at']);
        $id=$id-31415926;
        $query_del = 'delete from users where id ='.$id;
        if ($con = connectDB()) {
            if (delTable($con,$query_del)) {
                echo '<script> alert("😋删除成功！"); window.location.href="./ManageUser.php"</script>';
            }else {
                echo '<script> alert("😭对不起，删除失败！"); window.location.href="./ManageUser.php"</script>';
            }
        }
    }else {
        echo '<script> alert("非法访问！"); window.location.href="./ManageUser.php"</script>';
    }