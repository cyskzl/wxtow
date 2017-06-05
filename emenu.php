include("weixin.php");
require_once 'hyphp/config.ini.php';//调用自定义配置文件
define("ACCOUNT", "");//微信公众平台的用户名
define("PASSWORD", "");//微信公众平台的密码
define('DEBUG', true);//是否开启调试模式
$param = array('username'=>'微信公众平台的用户名', 'pwd'=>'微信公众平台的密码');
$weixin = new Weixin(TOKEN,DEBUG);
if($weixin->login($param)){
  你可以猥琐欲为了，哈哈哈哈
}
else{
  配置信息不对，别想模拟登录
}
复制代码

如果你需要自定义菜单，就生成吧，我写在这里做了注释，因为一般只需要生成一次即可，除非你老是修改菜单，生成后，不会立即显示，因为有24小时的缓存，除非你取消关注，然后重新关注即可！
/*生成自定义菜单开始*/
    /*$xjson = '{ 
     "button":[
         {
               "name":"篮球",
               "sub_button":[
                    {
                       "type":"click",
                       "name":"nba",
                       "key":"V1001_NBA"
                    },
                    {
                       "type":"click",
                       "name":"cba",
                       "key":"V1001_CBA"
                    }
                ]
           },
           {
               "name":"体育",
               "sub_button":[
                    {
                       "type":"click",
                       "name":"足球",
                       "key":"V1001_ZUQIU"
                    },
                    {
                       "type":"click",
                       "name":"排球",
                       "key":"V1001_PAIQIU"
                    },
                    {
                       "type":"click",
                       "name":"网球",
                       "key":"V1001_WANGQIU"
                    },
                    {
                       "type":"click",
                       "name":"乒乓球",
                       "key":"V1001_PPQ"
                    },
                    {
                       "type":"click",
                       "name":"台球",
                       "key":"V1001_TAIQIU"
                    }
                ]
           },
           {
               "name":"新闻",
               "sub_button":[
                    {
                       "type":"click",
                       "name":"国内新闻",
                       "key":"V1001_GNNEWS"
                    },
                    {
                       "type":"click",
                       "name":"国际新闻",
                       "key":"V1001_GJNEWS"
                    },
                    {
                       "type":"click",
                       "name":"地方新闻",
                       "key":"V1001_AREANEWS"
                    },
                    {
                       "type":"click",
                       "name":"家庭新闻",
                       "key":"V1001_HOMENEWS"
                    }
                ]
           }
       ]
    }';
    $jsonMenu = json_encode($xjson);
    $get_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=开发者模式中的AppId串&secret=开发者模式中的AppSecret串';
    $get_return = file_get_contents($get_url);
    $get_return = (array)json_decode($get_return);
    if( !isset($get_return['access_token']) ){exit( '获取access_token失败！' );}
    $post_url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$get_return['access_token'];
    $ch = curl_init($post_url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS,$xjson);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($xjson))
    );
    $respose_data = curl_exec($ch);
    echo $respose_data;exit;*/
/*生成自定义菜单结束*/
复制代码

你会看懂的，点击菜单事件回复，文本消息回复，智能语音识别
$weixin->getMsg();
$type = $weixin->msgtype; //消息类型
$username = $weixin->msg['FromUserName'];//哪个用户给你发的消息,这个$username是微信加密之后的，每个用户都是一一对应的
if ($type === 'event') {//点击菜单事件
    $eventkey = $weixin->eventkey;//获取当前菜单key
    if($eventkey=='V1001_NBA') {
        $hongye_bqq = '您点击的NBA菜单，哈哈';
    }
    if($eventkey=='V1001_CBA') {
        $hongye_bqq = '您点击的CBA菜单，哈哈';
    }
    else{
        $hongye_bqq = '欢迎关注bqq！';
    }
 }
if ($type === 'text') {//文本输入
    $kwds=$weixin->msg['Content'];
    $sql="select * from `wx_text_msg` where `question` like '%$kwds%' ";
    $res=getOne($sql);
    if($res) {
        $reply = $weixin->makeText($res['answer']);
    }
    else{
        $reply = $weixin->makeText('抱歉，根据您输入的文本，暂时未找到相关匹配信息');
    }
}
if ($type === 'voice') {//语音输入
    $kwds = substr($weixin->msg['Recognition'],0,-3);
    $sql="select * from `wx_voice_msg` where `question` like '%$kwds%' ";//根据语音输入关键词，查询数据表匹配答案
    $res=getOne($sql);
    if($res) {
        $reply = $weixin->makeText($res['answer']);
    }
    else{
        $reply = $weixin->makeText('抱歉，根据您输入的语音，暂时未找到相关匹配信息');
    }
}
