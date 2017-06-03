<?php
include './wxModel.php';
//define your token
define("TOKEN", "yangyang");
$wechatObj = new wxModel();

if (isset($_GET['echostr'])) {
    $wechatObj->valid();
} else {
    // 接收微信服务器发送过来的xml
    $wechatObj->responseMsg();
}

