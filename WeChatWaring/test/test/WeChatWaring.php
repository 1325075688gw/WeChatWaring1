<?php

////namespace apps\spcenter\spcashpool;
////include_once '/Users/gongwei/ProjectCode/PHP/spcenter/src/spcashpool/facade/WeChatWaringApi.php';
////
////$aParams = array(
////    'alert_title'   =>  '资金池核销率大于80%',
////    'alert_time'    =>  date('Y-m-d H:i:s',time()),
////    'alert_source'  =>  '个人资金池',
////    'alert_level'   =>  '严重',
////    'alert_status'  =>  '未处理',
////    'alert_info'    =>   '个人资金池核销率过高，请相关人员赶快处理！',
////);
////
////
////$sResult = MFacade_WeChatWaringApi::aSendWaringMessage('WeChatWaring', ['@all'], $aParams);
////print_r($sResult);
//$arr = [
//    'name'=> '张三',
//    'age'=>23
//];
//$fileName = __DIR__.'/gwData.txt';
//$foption = fopen($fileName, 'w');
//$content = json_encode($arr, true);
//fwrite($foption, $content);
//fclose($foption);


$ids = [1,2,3,4,5];
$results = [
    ['name'=>'张三','id'=>1],
    ['name'=>'李四','id'=>2],
    ['name'=>'王五','id'=>3],
    ['name'=>'黑七','id'=>5],
];
$res = [];

//echo array_values($results['id']);

//foreach ($ids as $id){
//    echo $results;
//}R

//
//foreach ($results as $key => $value) {
//    $arr2[] = $value['name'];
//}
//
//
//print_r($arr2);
//$arr3 = array_map('end',$results);
//print_r($arr3);

$arr4 = array_column($results, 'id');
print_r($arr4);


foreach ($ids as $id){
//    echo $id;
//    if (array_key_exists($id, $arr4)){
//        echo $id;
//    }
    if(in_array($id, $arr4)){
        echo $id;
    }
}

//$arr5 = [];
foreach ($results as $result){
    $arr5[$result['id']] = $result;
}
foreach ($ids as $id){
    if (array_key_exists($id, $arr5)){
        echo $arr5[$id]['name'],"\n";
    }
}
//print_r($arr5);
echo "dev开发";
