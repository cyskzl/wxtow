<?php
class wxModel
{
    /*
     * 接口配置信息，此信息需要你有自己的服务器资源，填写的URL需要正确响应微信发送的Token验证*/
    public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }

    /*
     * 微信发送消息，开发者服务器接收xml格式数据，然后进行业务的逻辑处理*/
    public function responseMsg()
    {
        // < 5.6       $GLOBALS
        // PHP > 7.0   file_get_contents()
        // file_put_contents('data.txt', $postStr);
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];          // POST数据

        // 使用 Medoo 类 把xml数据写入数据库
        include './db.php';
        $data = array(
            'xml' => $postStr,
        );
        $database->insert('test', $data);

        if (!empty($postStr)) {
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            // Disable the ability to load external entities
            libxml_disable_entity_loader(true);

            // 接收到微信服务器发送过来的xml数据：分为：时间、消息，按照 msgType 分，转换为对象
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

            $tousername = $postObj->ToUserName;
            $fromusername = $postObj->FromUserName;
            $msgtype = $postObj->MsgType;
            $keyword = trim($postObj->Content);

            // 图文  -》 返回图文列表    其他任何关键   默认
            if ($msgtype == 'text') {
                // 判断关键字，根据关键字来自定义回复的消息
                if ($keyword == "图文") {
                    // php + mysql    读取数据库 拿到文章列表的数据
                    $arr = array(
                        array(
                            'title' => "套路太深！唯品会对清空微博作出解释 网友：这广告6到飞",
                            'date' => "2017-6-2",
                            'url' => "http://www.chinaz.com/news/quka/2017/0602/715449.shtml",
                            'description' => '日前，唯品会清空了官方微博，成功的引起了众人的注意。',
                            'picUrl' => "http://upload.chinaz.com/2017/0602/6363201407728157524057839.jpeg"
                        ),
                        array(
                            'title' => "刘强东章泽天向中国人民大学捐赠3亿 设人大京东基金",
                            'date' => "2017-6-2",
                            'url' => "http://www.chinaz.com/news/2017/0602/715434.shtml",
                            'description' => '京东集团创始人、董事局主席兼首席执行官及京东集团今天下午在中国人民大学宣布',
                            'picUrl' => "http://upload.chinaz.com/2017/0602/6363201407728157524057839.jpeg"
                        ),
                        array(
                            'title' => "高通发布 QC 4+ 快充技术，让努比亚 Z17 当了一次“业界领先”",
                            'date' => "2017-6-2",
                            'url' => "http://www.chinaz.com/mobile/2017/0602/715429.shtml",
                            'description' => '充电 5 分钟，通话 2 小时这句广告词',
                            'picUrl' => "http://upload.chinaz.com/2017/0602/6363201407728157524057839.jpeg"
                        )
                    );
                    $textTpl = <<<EOT
                                <xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <ArticleCount>%s</ArticleCount>
                                <Articles>
EOT;

                    $str = "";
                    foreach ($arr as $v) {
                        $str .= "<item>";
                        $str .= "<Title><![CDATA[" . $v['title'] . "]]></Title>";
                        $str .= "<Description><![CDATA[" . $v['description'] . "]]></Description>";
                        $str .= "<PicUrl><![CDATA[" . $v['picUrl'] . "]]></PicUrl>";
                        $str .= "<Url><![CDATA[" . $v['url'] . "]]></Url>";
                        $str .= "</item>";
                    }

                    $textTpl .= $str;
                    $textTpl .= "</Articles></xml>";

                    $time = time();
                    $msgtype = 'news';
                    $nums = count($arr);

                    // Return a formatted string
                    $retStr = sprintf($textTpl, $fromusername, $tousername, $time, $msgtype, $nums);
                    echo $retStr;
                }

                // 接收到的关键字：美女，返回美图图片
                if ($keyword == "美女") {
                    $textTpl = <<<EOT
                                <xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Image>
                                <MediaId><![CDATA[%s]]></MediaId>
                                </Image>
                                </xml>
EOT;
                    $time = time();
                    $msgtype = 'image';
                    $mediaid = '51GEMtBhYpTjwh3iD-vvQS9l0kDhhEdOE_wF6T2NFwyS0wLmZvNhBZPZSMdhqFlV';

                    $retStr = sprintf($textTpl, $fromusername, $tousername, $time, $msgtype, $mediaid);
                    echo $retStr;
                }
            }

            // 判断是否发生了事件推送
            if ($msgtype == 'event') {
                $event = $postObj->Event;
                // 订阅事件
                if ($event == 'subscribe')
                {
                    // 订阅后，发送的文本消息
                    $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <FuncFlag>0</FuncFlag>
                            </xml>";
                    $time = time();
                    $msgtype = 'text';
                    $content = "欢迎来到PHP27，请输入美女，查看图片(有效期仅限今天)";

                    $retStr = sprintf($textTpl, $fromusername, $tousername, $time, $msgtype, $content);
                    echo $retStr;
                }
            }

            $time = time();
            $msgtype = $postObj->MsgType;
            $content = "欢迎来到微信公众号的开发世界！__GZPHP27";

            /*
            <xml>
            <ToUserName><![CDATA[toUser]]></ToUserName>
            <FromUserName><![CDATA[fromUser]]></FromUserName>
            <CreateTime>12345678</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[你好]]></Content>
            </xml>
            */
            // 发送消息的xml模板：文本消息
            $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <FuncFlag>0</FuncFlag>
                            </xml>";

            $time = time();
            $msgtype = 'text';
            $content = "欢迎来到微信公众号的开发世界！__GZPHP27";

            // Return a formatted string
            $retStr = sprintf($textTpl, $fromusername, $tousername, $time, $msgtype, $content);
            echo $retStr;

        } else {
            echo "";
            exit;
        }
    }

    /*
     * 验证服务器地址的有效性*/
    private function checkSignature()
    {
        /*
        1）将token、timestamp、nonce三个参数进行字典序排序
        2）将三个参数字符串拼接成一个字符串进行sha1加密
        3）开发者获得加密后的字符串可与signature对比，标识该请求来源于微信
         */
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }

        $signature = $_GET["signature"];

        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;

        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * curl请求，获取返回的数据
     * */
    public function getData($url)
    {
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

        return $ret;
    }

    /*
     * JSON 转化为数组
     * */
    public function jsonToArray($json)
    {
        $arr = json_decode($json, 1);
        return $arr;
    }

    public function getAccessToken(){
        // redis  memcache SESSION
        session_start();

        if ($_SESSION['access_token'] && (time()-$_SESSION['expire_time']) < 7000 )
        {
            return $_SESSION['access_token'];
        } else {
            $appid = "wx542c11817c22d123";
            $appsecret = "8b2d7aac7d5dc87173bc62a429545e18";

            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
            $access_token = $this->jsonToArray($this->getData($url))['access_token'];

            // 写入SESSION
            $_SESSION['access_token'] = $access_token;
            $_SESSION['expire_time'] = time();
            return $access_token;
        }
    }
}