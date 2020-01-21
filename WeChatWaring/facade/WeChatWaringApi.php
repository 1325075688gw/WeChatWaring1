<?php

namespace apps\spcenter\wechatwaring;

include '/Users/gongwei/ProjectCode/PHP/WeChatWaring1/WeChatWaring/control/WeCharWaring.php';


class MFacade_WeChatWaringApi
{

    /**
     * 发送文本消息
     * @param $sToUser
     * @param $sToParty
     * @param $sMessage
     */
    public static function aSendWaringByText($sToUser, $sToParty, $sMessage)
    {
        echo "我还会回来的";
        return WeChatWaring::instance()->aSendWaringByText($sToUser, $sToParty, $sMessage);
    }

    /**
     * 发送卡片消息
     * @param $sToUser
     * @param $sToParty
     * @param $sTitle
     * @param $sDescription
     * @param $sUrl
     */
    public static function aSendWaringByCard($sToUser, $sToParty, $sTitle, $sDescription, $sUrl)
    {
        return WeChatWaring::instance()->aSendWaringByCard($sToUser, $sToParty, $sTitle, $sDescription, $sUrl);
    }

    public static function func1()
    {
        echo "调用方法";
        return 1;
    }

    public static function aGetEmployeeByUid($iUid)
    {
        return WeChatWaring::instance()->aGetEmployeeByUid($iUid);
    }


    public static function aCreateEmployee($aParams)
    {
        return WeChatWaring::instance()->aCreateEmployee($aParams);
    }
}

MFacade_WeChatWaringApi::aSendWaringByText(1,2,3);
