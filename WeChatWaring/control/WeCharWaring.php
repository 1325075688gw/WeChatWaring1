<?php

namespace apps\spcenter\wechatwaring;
use apps\spcenter\utils\MFacade_SingletonTrait;
include "/Users/gongwei/ProjectCode/PHP/WeChatWaring1/WeChatWaring/utils/facade/SingletonTrait.php";
include "/Users/gongwei/ProjectCode/PHP/WeChatWaring1/WeChatWaring/facade/Config.php";


class WeChatWaring {

    use MFacade_SingletonTrait;

    /**
     * 获取密钥access_token,如果以前的某些操作已获取密钥并且保存到MFacade_Config::$aParams['sAccessToken']中,
     * 则我们就不需要再次获取，提高系统性能。避免重复操作
     * @return bool|string
     */
    private function sGetAccessToken()
    {
        if (empty(MFacade_Config::$aParams['sAccessToken'])) {
            $sGetUrl = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=" . MFacade_Config::$aParams['sQYWXId'] . "&corpsecret=" . MFacade_Config::$aParams['sAppSecret'];
            $sData = $this->sDoGet($sGetUrl);
            $aAccessToken = json_decode($sData, true);
            if ($aAccessToken['errcode'] != 0) {
                return $aAccessToken;
            }
            MFacade_Config::$aParams['sAccessToken'] = $aAccessToken["access_token"];
        }
    }



    /**
     * get请求
     * @param $sUrl
     * @return bool|string
     */
    private function sDoGet($sUrl)
    {
        $sCurl = curl_init();
        curl_setopt($sCurl, CURLOPT_URL, $sUrl);
        curl_setopt($sCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($sCurl, CURLOPT_SSL_VERIFYPEER, false);
        $sData = curl_exec($sCurl);
        curl_close($sCurl);
        return $sData;
    }

    /**
     * post请求
     * @param $sUrl
     * @param $aParam
     * @return bool|string
     */
    private function sDoPost($sUrl, $aParam)
    {
        $aParam = json_encode($aParam,true);
        $sCurl = curl_init($sUrl);
        curl_setopt($sCurl, CURLOPT_HEADER, 0);
        curl_setopt($sCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($sCurl, CURLOPT_POST, 1);
        curl_setopt($sCurl, CURLOPT_POSTFIELDS, $aParam);
        curl_setopt($sCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($sCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
        $sData = curl_exec($sCurl);
        return $sData;
    }

    /**
     * 通过UserId获取员工企业微信基本消息
     * 默认UserId为员工昵称对应的中文拼音，并且大驼峰命名规则
     * 比如张三的UserId为ZhangSan
     * @param $iUid
     * @return mixed
     */
    public function aGetEmployeeByUid($iUid)
    {
        $this->sGetAccessToken();
        $sGetUrl = sprintf('https://qyapi.weixin.qq.com/cgi-bin/user/get?access_token=%s&userid=%s',MFacade_Config::$aParams['sAccessToken'], $iUid);
        $sData = $this->sDoGet($sGetUrl);
        $aUserInfo = json_decode($sData, true);
        return $aUserInfo;
    }

    /**
     * 创建员工，aParams参数配置较多，详情参见接口文档
     * @param $aParams
     * @return mixed
     */
    public function aCreateEmployee($aParams)
    {
        $this->sGetAccessToken();
        $sPostUrl = 'https://qyapi.weixin.qq.com/cgi-bin/user/create?access_token=' . MFacade_Config::$aParams['sAccessToken'];
        $sData = $this->sDoPost($sPostUrl, $aParams);
        $aResult = json_decode($sData, true);
        return $aResult;
    }

    public function sGetAccessTokenInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 文本消息推送
     * @param $sToUser
     * @param $sToParty
     * @param $sMessage
     * @return array
     */
    public function aSendWaringByText($sToUser, $sToParty, $sMessage)
    {
        $this->sGetAccessToken();
        $sPushUrl = "https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=" . MFacade_Config::$aParams['sAccessToken'];
        $aParams = array(
            "touser" => $sToUser,
            "toparty" => $sToParty,
            "totag" => "",
            "msgtype" => "text",
            "agentid" => MFacade_Config::$aParams['sAgentId'],
            "text" =>
                array(
                    "content" => $sMessage
                ),
            "safe" => 0
        );
        $data = json_decode($this->sDoPost($sPushUrl, $aParams), true);
        if ($data['errcode'] == 0) {
            return $this->aSetResult();
        }
        return $data;
    }

    /**
     * 发送文本卡片消息
     * @param $sToUser
     * @param $sToParty
     * @param $sTitle
     * @param $sDescription
     * @param $sUrl
     * @return bool|mixed
     */
    public function aSendWaringByCard($sToUser, $sToParty, $sTitle, $sDescription, $sUrl)
    {
        $sAccessToken = $this->sGetAccessToken();
        $sPushUrl = "https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=" . $sAccessToken;
        $aParams = array(
            "touser" => $sToUser,
            "toparty" => $sToParty,
            "totag" => "",
            "msgtype" => "textcard",
            "agentid" => MFacade_Config::$aParams['sAgentId'],
            "textcard" =>
                array(
                    "title" => $sTitle,
                    "description" => $sDescription,
                    "url" => $sUrl,
                    "btntxt" => ""
                )
        );
        $aData = json_decode($this->sDoPost($sPushUrl, $aParams), true);
        if ($aData['errcode'] == 0) {
            return $this->aSetResult();
        }
        return $aData;
    }

    /**
     * 封装一个结果集方法
     * @param bool $bFlag
     * @param string $sMsg
     * @return array
     */
    private function aSetResult($bFlag = true, $sMsg ='操作成功')
    {
        return array(
            'flag' => $bFlag,
            'msg' => $sMsg
        );
    }


}

?>