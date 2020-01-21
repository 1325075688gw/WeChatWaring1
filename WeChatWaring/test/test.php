<?php

namespace apps\spcenter\wechatwaring;
include '/Users/gongwei/ProjectCode/PHP/WeChatWaring1/WeChatWaring/facade/WeChatWaringApi.php';

class WeChatWaringTest
{
    public static function testSendWaringByText()
    {
        $sToUser = '@all';
        $sToParty = '';
        $sMessage = "你吃f饭了吗";
        return MFacade_WeChatWaringApi::aSendWaringByText($sToUser, $sToParty, $sMessage);
    }

    public static function testGetEmployeeByUid()
    {
        return MFacade_WeChatWaringApi::aGetEmployeeByUid("GongWei");
    }

    public static function testCreateEmployee()
    {
        $aParams = array(
            "userid"     => "ZhangSan",
            "name"       => "张三",
            "department" => [2]
        );
        return MFacade_WeChatWaringApi::aCreateEmployee($aParams);
    }
}

//$arr = WeChatWaringTest::testSendWaringByText();
//print_r($arr);

//$arr2 = WeChatWaringTest::testGetEmployeeByUid();
//print_r($arr2);

$arr3 = WeChatWaringTest::testCreateEmployee();
print_r($arr3);