<?php

use think\facade\Db;
use sms\Alisms;
use think\facade\Env;
use think\facade\Cache;


/**

 * $msg 待提示的消息

 * $url 待跳转的链接

 * $icon 这里主要有两个，5和6，代表两种表情（哭和笑）

 * $time 弹出维持时间（单位秒）

 */


function alert($msg='',$url='',$icon='',$time=3){ 

    $str='<script type="text/javascript" src="/public/static/index/js/jquery-3.4.1.min.js"></script><script type="text/javascript" src="/public/static/admin2/layer/2.4/layer.js"></script>';//加载jquery和layer

    $str.='<script>$(function(){layer.msg("'.$msg.'",{icon:'.$icon.',time:'.($time*1000).'});setTimeout(function(){self.location.href="'.$url.'"},2000)});</script>';//主要方法

    return $str;

}


//手机端弹出框 aui框架
function mobile_alert($msg='',$url='',$icon='',$time=3){ 

    $str='<meta name="viewport" content="initial-scale=1, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta http-equiv="Access-Control-Allow-Origin" content="*" />
    <meta http-equiv="pragma" content="no-cache" />
    <style>.layui-layer-msg{ left: 28%!important;}</style>
    <script type="text/javascript" src="/public/static/index/Scripts/jquery-1.7.1.min.js"></script><script type="text/javascript" src="/public/static/admin/hui/lib/layer/2.1/layer.js"></script>';//加载jquery和layer

    $str.='<script>$(function(){layer.msg("'.$msg.'",{icon:'.$icon.',time:'.($time*1000).'});setTimeout(function(){self.location.href="'.$url.'"},2000)});</script>';//主要方法

    return $str;

}
 //$(function(){dialog.alert({msg:"'.$msg.'",buttons:"确定"});setTimeout(function(){self.location.href="'.$url.'"},2000)});</script>
// dialog.alert({
        //     msg:"'.$msg.'",
        //     buttons:"确定"
        // })
        // 


//API数据返回接口，统一规定，-1：未登录，0：普通错误，1：返回成功
function ajaxmsg($msg = "", $status = 1, $data = '', $errcode = '')
{
    $json=[

        'msg'=>$msg,
        'status'=>$status,
        'data'=>$data,
    ];

    if ($errcode) {
        $json['errcode'] = $errcode;
    }
    echo json_encode($json, true);
    exit;
}



//二维数组根据某个元素去重复
function second_array_unique_bykey($arr, $key){  
    $tmp_arr = array();  
    foreach($arr as $k => $v)  
    {  
        if(in_array($v[$key], $tmp_arr))   //搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true  
        {  
            unset($arr[$k]); //销毁一个变量  如果$tmp_arr中已存在相同的值就删除该值  
        }  
        else {  
            $tmp_arr[$k] = $v[$key];  //将不同的值放在该数组中保存  
        }  
   }  
   //ksort($arr); //ksort函数对数组进行排序(保留原键值key)  sort为不保留key值  
    return $arr;  
   } 



// 应用公共文件

function status($status) {


    if($status == 1) {

        $str = "<span class='label label-success radius'>正常</span>";

    }elseif($status ==0) {

        $str = "<span class='label label-danger radius'>停用</span>";

    }else {

        $str = "<span class='label label-danger radius'>已删除</span>";

    }

    return $str;

}






//商品修改，对新的属性和旧的属性数据进行重新排序

function attr_id_sort($a, $b)

{   

    if ($a['attr_id'] == $b['attr_id'])

        return 0;

    return ($a['attr_id'] < $b['attr_id']) ? -1 : 1;

}







//二维数组去掉重复值 并保留键值

function assoc_unique(&$arr, $key)

{

    $rAr=array();

    for($i=0;$i<count($arr);$i++)

    {

        if(!isset($rAr[$arr[$i][$key]]))

        {

            $rAr[$arr[$i][$key]]=$arr[$i];

        }

    }

    $arr=array_values($rAr);

    return $arr;

} 





/*阿里大于短信验证码*/
function qingsend($tel){
            /* 发送手机验证码 */
            require_once (Env::get('root_path') . 'sms/Alisms.class.php');
            header('Content-Type: text/plain; charset=utf-8');
            $demo = new Alisms(
                "LTAIU69S3KAZ2Tb2",  // AccessKeyID
                "S1GnOtEP4EGlcCFE3nVZ6TiT1b0l8t"   //AccessKeySecret
            );

            $mobile_code =rand(100000,999999); //验证码
            Cache::set($tel, $mobile_code, 12000000);

            $response = $demo->sendSms(
                "王晴儿商城", // 短信签名
                "SMS_85400065", // 短信模板编号
                $tel, // 短信接收者,多个手机号用英文逗号隔开
                Array(  // 短信模板中字段的值
                 "code"=>$mobile_code,
                  ),
                "123"
             );

            $result = $response->Code;
            return $result;
       }
       

//多维数组转字符串
function arrayToString($arr) { 
    if (is_array($arr)){ 
        return implode(',', array_map('arrayToString', $arr)); 
    } 
    return $arr; 
}


//功能：计算两个时间戳之间相差的日时分秒  
//$begin_time  开始时间戳  
//$end_time 结束时间戳  
function timediff($begin_time,$end_time)  
{  
      if($begin_time < $end_time){  
         $starttime = $begin_time;  
         $endtime = $end_time;  
      }else{  
         $starttime = $end_time;  
         $endtime = $begin_time;  
      }  
  
      //计算天数  
      $timediff = $endtime-$starttime;  
      $days = intval($timediff/86400);  
      //计算小时数  
      $remain = $timediff%86400;  
      $hours = intval($remain/3600);  
      //计算分钟数  
      $remain = $remain%3600;  
      $mins = intval($remain/60);  
      //计算秒数  
      $secs = $remain%60;  
      $res = array("day" => $days,"hour" => $hours,"min" => $mins,"sec" => $secs);  
      return $res;  
}  



// 自定义字段，多个ueditor
function get_ueditor($name,$value='',$width=1000,$height=400){
  $str=<<<HTML
  <textarea name='$name' id='$name'>$value</textarea>
  <script type="text/javascript">
    UE.getEditor('$name',{toolbars:[
        [
            'fullscreen', 'source', '|', 'undo', 'redo', '|',
            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
            'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
            'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
            'directionalityltr', 'directionalityrtl', 'indent', '|',
            'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
            'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
            'simpleupload', 'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment'
        ]

        ],initialFrameWidth:$width,initialFrameHeight:$height});
  </script>
HTML;
  return $str;
}


//删除目录及文件，传入目录
function delFileByDir($dir) {
   $dh = opendir($dir);
   while ($file = readdir($dh)) {
      if ($file != "." && $file != "..") {

         $fullpath = $dir . "/" . $file;
         if (is_dir($fullpath)) {
            delFileByDir($fullpath);
         } else {
            unlink($fullpath);
         }
      }
   }
   closedir($dh);
}



//删除文件,传入文件地址
function delFile($image){
    if(!empty($image)){
      $path=root_path().$image;
    }else{
      return true;
    }
    if(file_exists($path)){
      unlink($path);
    }
}




//加水印操作
function waterImg($path){
     
  $image = \think\Image::open($path);
  $image->water(ROOT_PATH.DS.'public'.DS.'water.png',\think\Image::WATER_SOUTHEAST,50)->save($path);
}



//字符串截取并且超出显示省略号

function subtext($text,$length)

{

  if(mb_strlen($text,'utf8') > $length)

  return mb_substr($text,0,$length,'utf8').'…';

  return $text;

}



//对象转数组
function object_to_array($obj)
{
    $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
    foreach ($_arr as $key => $val) {
        $val = (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
        $arr[$key] = $val;
    }
    return $arr;
}




//获取随机字符
function random($length = 6 , $numeric = 0) {
    PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
    if($numeric) {
        $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
    } else {
        $hash = '';
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
        $max = strlen($chars) - 1;
        for($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
    }
    return $hash;
}


//判断是不是手机浏览器访问
function is_mobile_request(){
    $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';
    $mobile_browser = '0';
    if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
        $mobile_browser++;
    if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))
        $mobile_browser++;
    if(isset($_SERVER['HTTP_X_WAP_PROFILE']))
        $mobile_browser++;
    if(isset($_SERVER['HTTP_PROFILE']))
        $mobile_browser++;
    $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
    $mobile_agents = array(
        'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
        'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
        'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
        'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
        'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
        'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
        'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
        'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
        'wapr','webc','winw','winw','xda','xda-'
    );
    if(in_array($mobile_ua, $mobile_agents))
        $mobile_browser++;
    if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
        $mobile_browser++;
    // Pre-final check to reset everything if the user is on Windows
    if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)
        $mobile_browser=0;
    // But WP7 is also Windows, with a slightly different characteristic
    if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)
        $mobile_browser++;
    if($mobile_browser>0)
        return true;
    else
        return false;
}


//获取当前ip地址
function get_client_ip($type = 0) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if($_SERVER['HTTP_X_REAL_IP']){//nginx 代理模式下，获取客户端真实IP
        $ip=$_SERVER['HTTP_X_REAL_IP'];     
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {//客户端的ip
        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {//浏览当前页面的用户计算机的网关
        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];//浏览当前页面的用户计算机的ip地址
    }else{
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}


function getModelName($id){
    return Db::name("model")->where('id',$id)->value('model_name');
}