<?php 


// first include pb_message
require_once ( 'message/pb_message.php');
require_once ( 'message/type/pb_enum.php');

// include the generated file
//require_once (__DIR__ . '/transfile.php');

require_once 'rpc/pb_proto_meta.php';
//require_once __DIR__ . '/pb_proto_hello.php';
require_once 'rpc/pb_proto_cpmsg.php';

require_once 'rpc/CpMsgRpc.php';


$c = new CpMsgRpc(’123.125.219.115‘，10123);

$time = number_format(microtime(true),32,'','');
$time = time();


 //登陆成功 将登到认证token
$token = $c->Login("trasintest", $time,"trasin123");
var_dump($token);
// var_dump($token);exit;
// 创建短信	  param1  token ，parm2 bool 长短信标识，param3 短信内容  ，返回 该条短信的msgid
// $msgId = $c->SmsCreate($token,1,'llllll');
// var_dump($msgId);
 //发送短信  param1 token，
 //具体看里面的实现， 参数 还有很多，都写在实现里面了 不妥 ，请根据业务完善吧
 //若成功返回 trackid ，作为该条信息的 查询序号。
$feeType=1;
$feeValue=1;
$agentFlag=1;
$moFlag=1;
$linkId=34;
// $c->smsSend($token, $msgId,'8613012345678', "BYYL02", $feeType, $feeValue, $agentFlag, $moFlag, $linkId);
//  //
// exit;
// 发送彩信

$path = 'C:\Users\Administrator\smsClient\\';
// $name = 'nginx.conf';
$name = 'index.php';
//  创建彩信      parm1 token，param2 主题 ，param3 彩信的服务代码 返回 msgid
$msgId = $c->MmsCreate($token,'subjecttxt','mmstest0');
var_dump($msgId);
//增加文本
$c->MmsApend($token,'彩信里添加的文字','',$msgId);

//增加彩信附件  增加文件附件  返回 改msgid 下面的 attache id 附件id。

$attId = $c->MmsApend($token,$path.$name,$name,$msgId);

var_dump($attId);
//var_dump($attId);

// 上传大附件 ，如果文件大于7k ，则会分批上传， 参看具体实现代码，整条彩信不能大于180k
$r = $c->MmsApdFile($token,$path.$name,$msgId,$attId);
// var_dump($r);
// 出错后删除原来的信息
// 传入正确的attId为删除单个附件
// 如果没有attId则删除整个彩信
if(!$r['state'])
    $c->MmsDelete($token, $msgId, $r['attId']);


//发送短信		，param2 收件人 ，param3 扣费方式	，linkid		 ，优先级 ，阅读报告，计费报告
// 返回trackid 作为该彩信的跟踪序号
//$identify, $msgId, $recption, $chargepartid, $linkid, $priority, $readreply, $replycharing
$r = $c->MmsSend($token, $msgId, "8613212345678", "8613012345678", "000000000000", 1, true, true);
var_dump($r);

 ?>
