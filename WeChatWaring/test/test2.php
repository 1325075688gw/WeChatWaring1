<?php


$arr = [
    'name'  => "D",
    'age'   => "17"
];

$flag = empty($arr['name']);
if ($flag){
    echo "我吃饭了";
}
if ($arr['name']){
    echo "我吃饭了";
}


