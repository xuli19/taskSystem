
<?php

function fileDown($filename){
    // header("Cache-Control: public"); 
    // header("Content-Description: File Transfer"); 
    
    // header("Content-Type: application/octet-stream"); //zip格式的  
    // header('Content-disposition: attachment; filename='.basename($filename)); //文件名  
    // // header("Content-Transfer-Encoding: binary"); //告诉浏览器，这是二进制文件  
    // header('Content-Length: '. filesize($filename)); //告诉浏览器，文件大小  
    // @readfile($filename);

    header('Content-type: image/png');
    header('Content-disposition: attachment; filename='.basename($filename)); //文件名  
}
?>