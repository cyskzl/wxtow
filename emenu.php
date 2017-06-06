<?php
//$appid = 'wx782abb2f658c7d1f';  
//$appsecret = '5c5a61dae21666a66ae19d796a76a710';  
//$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret=$appsecret";  
//$output = https_request($url);  
//$jsoninfo = json_decode($output,true);  
//$access_token = $jsoninfo["access_token"];  
$jsonmenu = '{  
     "button":[  
        {  
          "name":"学校系统",  
          "sub_button":[  
                {  
                    "type":"click",  
                    "name":"课程表",  
                    "key":"课程表"  
                },  
                {  
                    "type":"click",  
                    "name":"个人信息",  
                    "key":"个人信息"  
                },  
                {  
                    "type":"click",  
                    "name":"平时成绩",  
                    "key":"平时成绩"  
                },  
                {  
                    "type":"click",  
                    "name":"奖惩记录",  
                    "key":"奖惩记录"  
                }  
          ]  
        },  
        {  
            "name":"技术分享",  
          "sub_button":[  
                {  
                    "type":"click",  
                    "name":"移动WEB开发",  
                    "key":"移动WEB开发"  
                },  
                {  
                    "type":"click",  
                    "name":"J2EE框架",  
                    "key":"J2EE框架"  
                },  
                {  
                    "type":"click",  
                    "name":"Android开发",  
                    "key":"Android开发"  
                },  
                {  
                    "type":"click",  
                    "name":"PHP框架",  
                    "key":"PHP框架"  
                }  
          ]  
        },{  
            "name":"技术支持",  
          "sub_button":[  
                {  
                    "type":"click",  
                    "name":"在线客服",  
                    "key":"在线客服"  
                },  
                {  
                    "type":"click",  
                    "name":"技术分享",  
                    "key":"技术分享"  
                },  
                {  
                    "type":"view",  
                    "name":"天气预报",  
                    "url":"http://m.hao123.com/a/tianqi"  
                }  
          ]  
        }  
  
    ]  
    }';  
  
  
  //创建菜单实现  
  //$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;

  $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=x9OiTrVqe-Xu-yA0-RycVqXmuKqxFqCBKzoc5-5ZTIIzXx-nRR9XBuEnaeGe7wuxYAvWIS3UBTNUgg7xLgY_xRTGR_DAdU4hQQn9kZp2stm-FiZSeCWoHs4hXo5Z21zcYVZeACAFCG';
	
	
  $result = https_request($url,$jsonmenu);  
  var_dump($result);  
  function https_request($url,$data = null){  
      $curl = curl_init();  
      curl_setopt($curl,CURLOPT_URL,$url);  
      curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);  
      curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);  
      if(!empty($data)){  
          curl_setopt($curl,CURLOPT_POST,1);  
          curl_setopt($curl,CURLOPT_POSTFIELDS,$data);  
      }  
      curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);  
      $output = curl_exec($curl);  
      curl_close($curl);  
      return $output;  
  }  
