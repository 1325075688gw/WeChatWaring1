<?php

namespace apps\spcenter\wechatwaring;


class MFacade_Config
{
    static $aParams = array(
        'sQYWXId'       =>  'ww7818b12a13874c1a',                           // 企业微信ID
        'sAppSecret'    =>  'DbUx7nbjGhoneHHsNlnivTAMu6y0wFEOxSSllfNEBVk',  // 应用程序对应的Secret
        'sAgentId'      =>  '1000002',                                      // 应用程序对应的ID
        'sAccessToken'  =>  '',                                             // token，我们必须通过posturl来获取，获取之后的使用方式见方法
        'sDeparament'   =>  '',

    );
}

?>