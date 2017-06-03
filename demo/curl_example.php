<?php
include '../vendor/autoload.php';

$appid = "wx7aeab811e9f563a5";
$appsecret = "3a12389f58f7d9c1543cf9d365dbbc47";

$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;

//$url = 'https://www.jd.com/';

// 1. cURL初始化
$ch = curl_init();

// 2. 设置cURL选项
/*
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
*/
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

// 3. 执行cURL请求
$ret = curl_exec($ch);

// 4. 关闭资源
curl_close($ch);

echo $ret;