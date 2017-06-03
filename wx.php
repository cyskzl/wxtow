<?php
include './wxModel.php';
//define your token
define("TOKEN", "xdl2017");
$wechatObj = new wxModel();

if (isset($_GET['echostr'])) {
    $wechatObj->valid();
} else {
    // 接收微信服务器发送过来的xml
    $wechatObj->responseMsg();
}

