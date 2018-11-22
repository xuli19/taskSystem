<?php
header("content-type:text/html;charset=utf-8");
/**
 * @abstract
 * 定义数据库连接
 */
define('HOST', 'localhost');
define('USER', 'root');
define('PASSWORD', "5610060");
define('DB', 'jxyx');

/**
 * 连接数据库
 *
 * @param string $host
 * @param string $user
 * @param string $password
 * @param string $db
 * @return $con 返回数据库连接
 */
function  connectDB(string $host=HOST,string $user=USER, string $password=PASSWORD, string $db=DB) {
    $con = mysqli_connect($host, $user, $password, $db) or die("数据库连接失败！" . mysqli_connect_error());
    return $con;
    // var_dump($con);
}

/**
 * 查询数据表
 *
 * @param [type] $query
 * @return void
 */
function queryTable($con,string $query) {
    mysqli_query($con,"set names 'utf8'");  //防止连接数据库查询结果返回乱码问题；
    $result = mysqli_query($con, $query);
    //获取结果对象中的数据信息
    $dbContent = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $dbContent;
}

/**
 * 执行数据插入数据操作
 *
 * @param [resource] $con
 * @param [string] $query
 * @return boolean
 * ! 返回插入结果，Boolean值
 * TODO 完成相关方法
 */
function insertTable($con,$query) {
    mysqli_query($con,"set names 'utf8'");  //防止连接数据库查询结果返回乱码问题；
    if (mysqli_query($con,$query)) {
        return true;
    }else{
        echo mysqli_errno($con); //正式上线后，关闭此行，不能提示错误代码！
        exit;
    }

}

/**
 * 数据库删除操作
 *
 * @param [resource] $con
 * @param [string] $query
 * @return boolean
 */
function delTable($con,$query) {
    mysqli_query($con,"set names 'utf8'");  //防止连接数据库查询结果返回乱码问题；
    if (mysqli_query($con,$query)) {
        return true;
    }else{
        echo mysqli_errno($con);
        exit;
    }
}
/**
 * 关闭数据库连接
 *
 * @param [type] $con
 * @return boolean
 */
function closeConnectDB($con) {
    if (mysqli_close($con)) {
        exit;
    }else {
        echo "关闭错误";
        return false;
    }
}



