<?php
session_start();

//设置session会话过期 
define('LIFETIME', 600*2);
// $lifeTime =600*2; //设置过期时间长度
setcookie(session_name(),session_id(),time()+LIFETIME,"/"); //过期设置

if (!isset($_SESSION['user']) || empty($_SESSION['user']) || empty($_SESSION['level'])) {
    header('Location:login.php');
    exit;
}