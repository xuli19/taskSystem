<?php

/**
 * Undocumented function
 *
 * @param int $min
 * @param int $max
 * @return float 返回随机小数
 */
function randFloat($min=0,$max=1) {
    return number_format($min + mt_rand()/mt_getrandmax() * ($max-$min),1);
}

/**
 * Undocumented function
 *
 * @param int $colorLength 生成随机颜色的数组长度
 * @return array $colorArray 返回数组随机颜色
 */
function randomColor(int $colorLength,int $min=0,int $max=1) {
    $colorArray=[];
    for($i=1;$i<=$colorLength;$i++) {
        $colorArray[]='rgba('.mt_rand(0, 255).','.mt_rand(0,255).','.mt_rand(0, 255).','.randFloat($min,$max).')';
    }

    return $colorArray;
}