<?php
include "../vendor/autoload.php";
$json = <<<EOT
 {
     "button":[
     {	
          "type":"click",
          "name":"今日歌曲",
          "key":"V1001_TODAY_MUSIC"
      },
      {
           "name":"菜单",
           "sub_button":[
           {	
               "type":"view",
               "name":"搜索",
               "url":"http://www.soso.com/"
            },
            {
                 "type":"miniprogram",
                 "name":"wxa",
                 "url":"http://mp.weixin.qq.com",
                 "appid":"wx286b93c14bbf93aa",
                 "pagepath":"pages/lunar/index.html"
             },
            {
               "type":"click",
               "name":"赞一下我们",
               "key":"V1001_GOOD"
            }]
       }]
 }
EOT;

$arr = json_decode($json, 1);

$arr = array(
    'button' => array(
        array(
            "type" => "click",
            "name" => "图文列表",
            "key" => "20000"
        ),
        array(
            "name" => "下拉菜单",
            "sub_button" => array(
                array(
                    "type" => "click",
                    "name" => "今日歌曲",
                    "key" => "30000"
                ),
                array(
                    "type" => "click",
                    "name" => "今日歌曲",
                    "key" => "40000"
                )
            )
        ),
        array(
            "type" => "view",
            "name" => "搜索",
            "url" => "http://www.soso.com/"
        )
    )
);

dump($arr);