<?php
    header("content-type:text/html;charset=utf-8");
    include_once('./ConnectDB.php');
    include_once('../jxyx/public/phpqrcode/qrlib.php');
    include_once('./public/download.php');

    $codeId = (int)$_GET["id"];
    $query ="select * from classroominfo where id = ".$codeId ." limit 1";

    if ($con = connectDB()) {
        $result = queryTable($con, $query);
    }
    foreach ($result as $key => $value) {
        $classroom = $value['building'].$value['district'].$value['roomNO'];
        $seats = $value['seats'];
        $tel = $value['IPNO'];
        $buildTime = $value['updateTime'];
    }
$code=<<<CODE
教室：{$classroom}
座位数：{$seats}
联系电话：{$tel}
建成时间：{$buildTime}
CODE;

    QRcode::png($code,'class.png');
    $qpng = "class.png";

    $temp_path = './qr/';
    $fileName = $codeId.'.png';

    $pngAbsoluteFilePath = $temp_path.$fileName;
    $urlRelativeFilePath = './qr/'.$fileName; 


    if (!file_exists($pngAbsoluteFilePath)) {
        QRcode::png($code, $pngAbsoluteFilePath);
        echo '<script> alert("😋生成成功！"); javascript:history.back()</script>';


    } else {
        echo '出现错误了！';
        echo '<hr />';
    }
    
    // echo 'Server PNG File: '.$pngAbsoluteFilePath; //显示生成文件保存的位置
    // echo '<hr />';
    
    // displaying
    echo '<img src="'.$urlRelativeFilePath.'" />'; 
?>