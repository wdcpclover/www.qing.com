<?php
//配置文件
return [
    // 文件上传默认驱动
    //'UPLOAD_DRIVER' => 'Qiniu', //设置七牛上传驱动
    'UPLOAD_DRIVER' => 'Local',
    // 七牛上传驱动配置说明
    'UPLOAD_Qiniu_CONFIG' => array(
        'secretKey' => '8PYu2CcWljEJXhUctf4x6DANrGc560wX9kqyJkz5', //七牛服务器
        'accessKey' => 'Nrt8-LL1mAPSMgP4MGHXnuNCcaIrGcS2q42xa-HN', //七牛用户
        'domain'    => 'http://p3nmmazmo.bkt.clouddn.com/', //七牛域名
        'bucket'    => 'qing1', //空间名称
        'timeout'   => 300, //超时时间
    ),
    'LOGIN_SALT'=>'qing654',


];