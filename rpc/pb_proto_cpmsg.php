<?php

require_once ( __DIR__.'/../message/pb_message.php');
require_once ( __DIR__.'/../message/type/pb_enum.php');

class ErrorCode extends \PBEnum
{
  const SUCCESS  = 0;
  const AUTH_ERR  = 1;
  const OUT_OF_FUND  = 2;
  const INVAILD_PARAMETER  = 3;
  const INVAILD_IDENTIFY  = 4;
  const EXCEED_SESSION_LIMIT  = 5;
  const TRY_LATER  = 6;
  const ERR_OCCURED  = 7;
  const EXCEED_MSG_CAPACITY  = 8;

  public function __construct($reader=null)
  {
   	parent::__construct($reader);
 	$this->names = array(
			0 => "SUCCESS",
			1 => "AUTH_ERR",
			2 => "OUT_OF_FUND",
			3 => "INVAILD_PARAMETER",
			4 => "INVAILD_IDENTIFY",
			5 => "EXCEED_SESSION_LIMIT",
			6 => "TRY_LATER",
			7 => "ERR_OCCURED",
			8 => "EXCEED_MSG_CAPACITY");
   }
}
class OpType extends \PBEnum
{
  const SMS  = 1;
  const MMS  = 2;

  public function __construct($reader=null)
  {
   	parent::__construct($reader);
 	$this->names = array(
			1 => "SMS",
			2 => "MMS");
   }
}
class MMS_Endflag extends \PBEnum
{
  const BEGIN  = 0;
  const RESUME  = 1;
  const END  = 2;

  public function __construct($reader=null)
  {
   	parent::__construct($reader);
 	$this->names = array(
			0 => "BEGIN",
			1 => "RESUME",
			2 => "END");
   }
}
class NetType extends \PBEnum
{
  const cmcc  = 1;
  const ctcc  = 2;
  const cucc  = 3;

  public function __construct($reader=null)
  {
   	parent::__construct($reader);
 	$this->names = array(
			1 => "cmcc",
			2 => "ctcc",
			3 => "cucc");
   }
}
class LoginRequest extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["LoginRequest"]["1"] = "\\PBString";
    $this->values["1"] = "";
    self::$fieldNames["LoginRequest"]["1"] = "corpname";
    self::$fields["LoginRequest"]["2"] = "\\PBInt";
    $this->values["2"] = "";
    self::$fieldNames["LoginRequest"]["2"] = "stamp";
    self::$fields["LoginRequest"]["3"] = "\\PBString";
    $this->values["3"] = "";
    self::$fieldNames["LoginRequest"]["3"] = "authstr";
  }
  function corpname()
  {
    return $this->_get_value("1");
  }
  function set_corpname($value)
  {
    return $this->_set_value("1", $value);
  }
  function stamp()
  {
    return $this->_get_value("2");
  }
  function set_stamp($value)
  {
    return $this->_set_value("2", $value);
  }
  function authstr()
  {
    return $this->_get_value("3");
  }
  function set_authstr($value)
  {
    return $this->_set_value("3", $value);
  }
}
class LoginResponse extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["LoginResponse"]["1"] = "\\cpmsg\\ErrorCode";
    $this->values["1"] = "";
    self::$fieldNames["LoginResponse"]["1"] = "retcode";
    self::$fields["LoginResponse"]["2"] = "\\PBInt";
    $this->values["2"] = "";
    self::$fieldNames["LoginResponse"]["2"] = "identify";
  }
  function retcode()
  {
    return $this->_get_value("1");
  }
  function set_retcode($value)
  {
    return $this->_set_value("1", $value);
  }
  function retcode_string()
  {
    return $this->values["1"]->get_description();
  }
  function identify()
  {
    return $this->_get_value("2");
  }
  function set_identify($value)
  {
    return $this->_set_value("2", $value);
  }
}
class LogoutResponse extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["LogoutResponse"]["1"] = "\\cpmsg\\ErrorCode";
    $this->values["1"] = "";
    self::$fieldNames["LogoutResponse"]["1"] = "retcode";
  }
  function retcode()
  {
    return $this->_get_value("1");
  }
  function set_retcode($value)
  {
    return $this->_set_value("1", $value);
  }
  function retcode_string()
  {
    return $this->values["1"]->get_description();
  }
}
class SmsCreateRequest extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["SmsCreateRequest"]["1"] = "\\PBBytes";
    $this->values["1"] = "";
    self::$fieldNames["SmsCreateRequest"]["1"] = "content";
    self::$fields["SmsCreateRequest"]["2"] = "\\PBInt";
    $this->values["2"] = "";
    self::$fieldNames["SmsCreateRequest"]["2"] = "identify";
    self::$fields["SmsCreateRequest"]["3"] = "\\PBBool";
    $this->values["3"] = "";
    self::$fieldNames["SmsCreateRequest"]["3"] = "islongmsg";
  }
  function content()
  {
    return $this->_get_value("1");
  }
  function set_content($value)
  {
    return $this->_set_value("1", $value);
  }
  function identify()
  {
    return $this->_get_value("2");
  }
  function set_identify($value)
  {
    return $this->_set_value("2", $value);
  }
  function islongmsg()
  {
    return $this->_get_value("3");
  }
  function set_islongmsg($value)
  {
    return $this->_set_value("3", $value);
  }
}
class SmsCreateResponse extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["SmsCreateResponse"]["1"] = "ErrorCode";
    $this->values["1"] = "";
    self::$fieldNames["SmsCreateResponse"]["1"] = "retcode";
    self::$fields["SmsCreateResponse"]["2"] = "PBInt";
    $this->values["2"] = "";
    self::$fieldNames["SmsCreateResponse"]["2"] = "contentid";
  }
  function retcode()
  {
    return $this->_get_value("1");
  }
  function set_retcode($value)
  {
    return $this->_set_value("1", $value);
  }
  function retcode_string()
  {
    return $this->values["1"]->get_description();
  }
  function contentid()
  {
    return $this->_get_value("2");
  }
  function set_contentid($value)
  {
    return $this->_set_value("2", $value);
  }
}
class SmsSendRequest extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["SmsSendRequest"]["1"] = "\\PBInt";
    $this->values["1"] = "";
    self::$fieldNames["SmsSendRequest"]["1"] = "identify";
    self::$fields["SmsSendRequest"]["2"] = "\\PBString";
    $this->values["2"] = "";
    self::$fieldNames["SmsSendRequest"]["2"] = "usernumber";
    self::$fields["SmsSendRequest"]["4"] = "\\PBInt";
    $this->values["4"] = "";
    self::$fieldNames["SmsSendRequest"]["4"] = "contentid";
    self::$fields["SmsSendRequest"]["5"] = "\\PBInt";
    $this->values["5"] = "";
    $this->values["5"] = new \PBInt();
    $this->values["5"]->value = 1;
    self::$fieldNames["SmsSendRequest"]["5"] = "splits";
    self::$fields["SmsSendRequest"]["6"] = "\\PBString";
    $this->values["6"] = "";
    self::$fieldNames["SmsSendRequest"]["6"] = "serivce";
    self::$fields["SmsSendRequest"]["7"] = "\\PBInt";
    $this->values["7"] = "";
    self::$fieldNames["SmsSendRequest"]["7"] = "mo_flag";
    self::$fields["SmsSendRequest"]["8"] = "\\PBInt";
    $this->values["8"] = "";
    self::$fieldNames["SmsSendRequest"]["8"] = "fee_type";
    self::$fields["SmsSendRequest"]["9"] = "\\PBInt";
    $this->values["9"] = "";
    self::$fieldNames["SmsSendRequest"]["9"] = "fee_vlaue";
    self::$fields["SmsSendRequest"]["10"] = "\\PBInt";
    $this->values["10"] = "";
    self::$fieldNames["SmsSendRequest"]["10"] = "agent_flag";
    self::$fields["SmsSendRequest"]["11"] = "\\PBInt";
    $this->values["11"] = "";
    self::$fieldNames["SmsSendRequest"]["11"] = "give_fee";
    self::$fields["SmsSendRequest"]["12"] = "\\PBInt";
    $this->values["12"] = "";
    self::$fieldNames["SmsSendRequest"]["12"] = "report_flag";
    self::$fields["SmsSendRequest"]["13"] = "\\PBInt";
    $this->values["13"] = "";
    self::$fieldNames["SmsSendRequest"]["13"] = "priority";
    self::$fields["SmsSendRequest"]["14"] = "\\PBString";
    $this->values["14"] = "";
    self::$fieldNames["SmsSendRequest"]["14"] = "chargemobile";
    self::$fields["SmsSendRequest"]["15"] = "\\PBString";
    $this->values["15"] = "";
    self::$fieldNames["SmsSendRequest"]["15"] = "link_id";
    self::$fields["SmsSendRequest"]["16"] = "\\PBString";
    $this->values["16"] = "";
    self::$fieldNames["SmsSendRequest"]["16"] = "scheduletime";
    self::$fields["SmsSendRequest"]["17"] = "\\PBString";
    $this->values["17"] = "";
    self::$fieldNames["SmsSendRequest"]["17"] = "expiretime";
  }
  function identify()
  {
    return $this->_get_value("1");
  }
  function set_identify($value)
  {
    return $this->_set_value("1", $value);
  }
  function usernumber()
  {
    return $this->_get_value("2");
  }
  function set_usernumber($value)
  {
    return $this->_set_value("2", $value);
  }
  function contentid()
  {
    return $this->_get_value("4");
  }
  function set_contentid($value)
  {
    return $this->_set_value("4", $value);
  }
  function splits()
  {
    return $this->_get_value("5");
  }
  function set_splits($value)
  {
    return $this->_set_value("5", $value);
  }
  function serivce()
  {
    return $this->_get_value("6");
  }
  function set_serivce($value)
  {
    return $this->_set_value("6", $value);
  }
  function mo_flag()
  {
    return $this->_get_value("7");
  }
  function set_mo_flag($value)
  {
    return $this->_set_value("7", $value);
  }
  function fee_type()
  {
    return $this->_get_value("8");
  }
  function set_fee_type($value)
  {
    return $this->_set_value("8", $value);
  }
  function fee_vlaue()
  {
    return $this->_get_value("9");
  }
  function set_fee_vlaue($value)
  {
    return $this->_set_value("9", $value);
  }
  function agent_flag()
  {
    return $this->_get_value("10");
  }
  function set_agent_flag($value)
  {
    return $this->_set_value("10", $value);
  }
  function give_fee()
  {
    return $this->_get_value("11");
  }
  function set_give_fee($value)
  {
    return $this->_set_value("11", $value);
  }
  function report_flag()
  {
    return $this->_get_value("12");
  }
  function set_report_flag($value)
  {
    return $this->_set_value("12", $value);
  }
  function priority()
  {
    return $this->_get_value("13");
  }
  function set_priority($value)
  {
    return $this->_set_value("13", $value);
  }
  function chargemobile()
  {
    return $this->_get_value("14");
  }
  function set_chargemobile($value)
  {
    return $this->_set_value("14", $value);
  }
  function link_id()
  {
    return $this->_get_value("15");
  }
  function set_link_id($value)
  {
    return $this->_set_value("15", $value);
  }
  function scheduletime()
  {
    return $this->_get_value("16");
  }
  function set_scheduletime($value)
  {
    return $this->_set_value("16", $value);
  }
  function expiretime()
  {
    return $this->_get_value("17");
  }
  function set_expiretime($value)
  {
    return $this->_set_value("17", $value);
  }
}
class SmsSendResponse extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["SmsSendResponse"]["1"] = "\\cpmsg\\ErrorCode";
    $this->values["1"] = "";
    self::$fieldNames["SmsSendResponse"]["1"] = "retcode";
    self::$fields["SmsSendResponse"]["2"] = "\\PBString";
    $this->values["2"] = "";
    self::$fieldNames["SmsSendResponse"]["2"] = "trackid";
  }
  function retcode()
  {
    return $this->_get_value("1");
  }
  function set_retcode($value)
  {
    return $this->_set_value("1", $value);
  }
  function retcode_string()
  {
    return $this->values["1"]->get_description();
  }
  function trackid()
  {
    return $this->_get_value("2");
  }
  function set_trackid($value)
  {
    return $this->_set_value("2", $value);
  }
}
class MmsCreateRequest extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["MmsCreateRequest"]["1"] = "\\PBInt";
    $this->values["1"] = "";
    self::$fieldNames["MmsCreateRequest"]["1"] = "identify";
    self::$fields["MmsCreateRequest"]["2"] = "\\PBBytes";
    $this->values["2"] = "";
    self::$fieldNames["MmsCreateRequest"]["2"] = "subject";
    self::$fields["MmsCreateRequest"]["3"] = "\\PBString";
    $this->values["3"] = "";
    self::$fieldNames["MmsCreateRequest"]["3"] = "productID";
  }
  function identify()
  {
    return $this->_get_value("1");
  }
  function set_identify($value)
  {
    return $this->_set_value("1", $value);
  }
  function subject()
  {
    return $this->_get_value("2");
  }
  function set_subject($value)
  {
    return $this->_set_value("2", $value);
  }
  function productID()
  {
    return $this->_get_value("3");
  }
  function set_productID($value)
  {
    return $this->_set_value("3", $value);
  }
}
class MmsCreateResponse extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["MmsCreateResponse"]["1"] = "\\cpmsg\\ErrorCode";
    $this->values["1"] = "";
    self::$fieldNames["MmsCreateResponse"]["1"] = "retcode";
    self::$fields["MmsCreateResponse"]["2"] = "\\PBString";
    $this->values["2"] = "";
    self::$fieldNames["MmsCreateResponse"]["2"] = "messageid";
  }
  function retcode()
  {
    return $this->_get_value("1");
  }
  function set_retcode($value)
  {
    return $this->_set_value("1", $value);
  }
  function retcode_string()
  {
    return $this->values["1"]->get_description();
  }
  function messageid()
  {
    return $this->_get_value("2");
  }
  function set_messageid($value)
  {
    return $this->_set_value("2", $value);
  }
}
class MmsAppendRequest extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["MmsAppendRequest"]["1"] = "\\PBInt";
    $this->values["1"] = "";
    self::$fieldNames["MmsAppendRequest"]["1"] = "identify";
    self::$fields["MmsAppendRequest"]["2"] = "\\PBBytes";
    $this->values["2"] = "";
    self::$fieldNames["MmsAppendRequest"]["2"] = "attache";
    self::$fields["MmsAppendRequest"]["3"] = "\\PBString";
    $this->values["3"] = "";
    self::$fieldNames["MmsAppendRequest"]["3"] = "attfname";
    self::$fields["MmsAppendRequest"]["4"] = "\\PBString";
    $this->values["4"] = "";
    self::$fieldNames["MmsAppendRequest"]["4"] = "messageid";
  }
  function identify()
  {
    return $this->_get_value("1");
  }
  function set_identify($value)
  {
    return $this->_set_value("1", $value);
  }
  function attache()
  {
    return $this->_get_value("2");
  }
  function set_attache($value)
  {
    return $this->_set_value("2", $value);
  }
  function attfname()
  {
    return $this->_get_value("3");
  }
  function set_attfname($value)
  {
    return $this->_set_value("3", $value);
  }
  function messageid()
  {
    return $this->_get_value("4");
  }
  function set_messageid($value)
  {
    return $this->_set_value("4", $value);
  }
}
class MmsAppendResponse extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["MmsAppendResponse"]["1"] = "\\cpmsg\\ErrorCode";
    $this->values["1"] = "";
    self::$fieldNames["MmsAppendResponse"]["1"] = "retcode";
    self::$fields["MmsAppendResponse"]["2"] = "\\PBString";
    $this->values["2"] = "";
    self::$fieldNames["MmsAppendResponse"]["2"] = "attacheid";
  }
  function retcode()
  {
    return $this->_get_value("1");
  }
  function set_retcode($value)
  {
    return $this->_set_value("1", $value);
  }
  function retcode_string()
  {
    return $this->values["1"]->get_description();
  }
  function attacheid()
  {
    return $this->_get_value("2");
  }
  function set_attacheid($value)
  {
    return $this->_set_value("2", $value);
  }
}
class MmsApdAttcheRequest extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["MmsApdAttcheRequest"]["1"] = "\\PBInt";
    $this->values["1"] = "";
    self::$fieldNames["MmsApdAttcheRequest"]["1"] = "identify";
    self::$fields["MmsApdAttcheRequest"]["2"] = "\\PBBytes";
    $this->values["2"] = "";
    self::$fieldNames["MmsApdAttcheRequest"]["2"] = "bincontent";
    self::$fields["MmsApdAttcheRequest"]["3"] = "\\PBString";
    $this->values["3"] = "";
    self::$fieldNames["MmsApdAttcheRequest"]["3"] = "attacheid";
    self::$fields["MmsApdAttcheRequest"]["4"] = "\\PBString";
    $this->values["4"] = "";
    self::$fieldNames["MmsApdAttcheRequest"]["4"] = "messageid";
  }
  function identify()
  {
    return $this->_get_value("1");
  }
  function set_identify($value)
  {
    return $this->_set_value("1", $value);
  }
  function bincontent()
  {
    return $this->_get_value("2");
  }
  function set_bincontent($value)
  {
    return $this->_set_value("2", $value);
  }
  function attacheid()
  {
    return $this->_get_value("3");
  }
  function set_attacheid($value)
  {
    return $this->_set_value("3", $value);
  }
  function messageid()
  {
    return $this->_get_value("4");
  }
  function set_messageid($value)
  {
    return $this->_set_value("4", $value);
  }
}
class MmsApdAttcheResponse extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["MmsApdAttcheResponse"]["1"] = "\\cpmsg\\ErrorCode";
    $this->values["1"] = "";
    self::$fieldNames["MmsApdAttcheResponse"]["1"] = "retcode";
  }
  function retcode()
  {
    return $this->_get_value("1");
  }
  function set_retcode($value)
  {
    return $this->_set_value("1", $value);
  }
  function retcode_string()
  {
    return $this->values["1"]->get_description();
  }
}
class MmsDeleteRequest extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["MmsDeleteRequest"]["1"] = "\\PBInt";
    $this->values["1"] = "";
    self::$fieldNames["MmsDeleteRequest"]["1"] = "identify";
    self::$fields["MmsDeleteRequest"]["2"] = "\\PBString";
    $this->values["2"] = "";
    self::$fieldNames["MmsDeleteRequest"]["2"] = "messageid";
    self::$fields["MmsDeleteRequest"]["3"] = "\\PBString";
    $this->values["3"] = "";
    self::$fieldNames["MmsDeleteRequest"]["3"] = "attacheID";
  }
  function identify()
  {
    return $this->_get_value("1");
  }
  function set_identify($value)
  {
    return $this->_set_value("1", $value);
  }
  function messageid()
  {
    return $this->_get_value("2");
  }
  function set_messageid($value)
  {
    return $this->_set_value("2", $value);
  }
  function attacheID()
  {
    return $this->_get_value("3");
  }
  function set_attacheID($value)
  {
    return $this->_set_value("3", $value);
  }
}
class MmsDeleteResponse extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["MmsDeleteResponse"]["1"] = "\\cpmsg\\ErrorCode";
    $this->values["1"] = "";
    self::$fieldNames["MmsDeleteResponse"]["1"] = "retcode";
  }
  function retcode()
  {
    return $this->_get_value("1");
  }
  function set_retcode($value)
  {
    return $this->_set_value("1", $value);
  }
  function retcode_string()
  {
    return $this->values["1"]->get_description();
  }
}
class MmsSendRequest extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["MmsSendRequest"]["1"] = "\\PBInt";
    $this->values["1"] = "";
    self::$fieldNames["MmsSendRequest"]["1"] = "identify";
    self::$fields["MmsSendRequest"]["2"] = "\\PBString";
    $this->values["2"] = "";
    self::$fieldNames["MmsSendRequest"]["2"] = "messageid";
    self::$fields["MmsSendRequest"]["3"] = "\\PBString";
    $this->values["3"] = "";
    self::$fieldNames["MmsSendRequest"]["3"] = "ChargedPartyID";
    self::$fields["MmsSendRequest"]["4"] = "\\PBString";
    $this->values["4"] = "";
    self::$fieldNames["MmsSendRequest"]["4"] = "recipent";
    self::$fields["MmsSendRequest"]["5"] = "\\PBString";
    $this->values["5"] = "";
    self::$fieldNames["MmsSendRequest"]["5"] = "linkid";
    self::$fields["MmsSendRequest"]["6"] = "\\PBInt";
    $this->values["6"] = "";
    self::$fieldNames["MmsSendRequest"]["6"] = "Priority";
    self::$fields["MmsSendRequest"]["7"] = "\\PBBool";
    $this->values["7"] = "";
    self::$fieldNames["MmsSendRequest"]["7"] = "ReadReply";
    self::$fields["MmsSendRequest"]["8"] = "\\PBBool";
    $this->values["8"] = "";
    self::$fieldNames["MmsSendRequest"]["8"] = "ReplyCharging";
    self::$fields["MmsSendRequest"]["9"] = "\\PBBool";
    $this->values["9"] = "";
    self::$fieldNames["MmsSendRequest"]["9"] = "DeliveryReport";
  }
  function identify()
  {
    return $this->_get_value("1");
  }
  function set_identify($value)
  {
    return $this->_set_value("1", $value);
  }
  function messageid()
  {
    return $this->_get_value("2");
  }
  function set_messageid($value)
  {
    return $this->_set_value("2", $value);
  }
  function ChargedPartyID()
  {
    return $this->_get_value("3");
  }
  function set_ChargedPartyID($value)
  {
    return $this->_set_value("3", $value);
  }
  function recipent()
  {
    return $this->_get_value("4");
  }
  function set_recipent($value)
  {
    return $this->_set_value("4", $value);
  }
  function linkid()
  {
    return $this->_get_value("5");
  }
  function set_linkid($value)
  {
    return $this->_set_value("5", $value);
  }
  function Priority()
  {
    return $this->_get_value("6");
  }
  function set_Priority($value)
  {
    return $this->_set_value("6", $value);
  }
  function ReadReply()
  {
    return $this->_get_value("7");
  }
  function set_ReadReply($value)
  {
    return $this->_set_value("7", $value);
  }
  function ReplyCharging()
  {
    return $this->_get_value("8");
  }
  function set_ReplyCharging($value)
  {
    return $this->_set_value("8", $value);
  }
  function DeliveryReport()
  {
    return $this->_get_value("9");
  }
  function set_DeliveryReport($value)
  {
    return $this->_set_value("9", $value);
  }
}
class MmsSendResponse extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["MmsSendResponse"]["1"] = "\\cpmsg\\ErrorCode";
    $this->values["1"] = "";
    self::$fieldNames["MmsSendResponse"]["1"] = "retcode";
    self::$fields["MmsSendResponse"]["2"] = "\\PBString";
    $this->values["2"] = "";
    self::$fieldNames["MmsSendResponse"]["2"] = "trackid";
  }
  function retcode()
  {
    return $this->_get_value("1");
  }
  function set_retcode($value)
  {
    return $this->_set_value("1", $value);
  }
  function retcode_string()
  {
    return $this->values["1"]->get_description();
  }
  function trackid()
  {
    return $this->_get_value("2");
  }
  function set_trackid($value)
  {
    return $this->_set_value("2", $value);
  }
}
class TrackingRequest extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["TrackingRequest"]["1"] = "\\PBInt";
    $this->values["1"] = "";
    self::$fieldNames["TrackingRequest"]["1"] = "identify";
    self::$fields["TrackingRequest"]["2"] = "\\PBString";
    $this->values["2"] = "";
    self::$fieldNames["TrackingRequest"]["2"] = "trackid";
  }
  function identify()
  {
    return $this->_get_value("1");
  }
  function set_identify($value)
  {
    return $this->_set_value("1", $value);
  }
  function trackid()
  {
    return $this->_get_value("2");
  }
  function set_trackid($value)
  {
    return $this->_set_value("2", $value);
  }
}
class TrackingResponse extends \PBMessage
{
  var $wired_type = \PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["TrackingResponse"]["1"] = "\\cpmsg\\ErrorCode";
    $this->values["1"] = "";
    self::$fieldNames["TrackingResponse"]["1"] = "retcode";
    self::$fields["TrackingResponse"]["2"] = "\\PBInt";
    $this->values["2"] = "";
    self::$fieldNames["TrackingResponse"]["2"] = "error";
    self::$fields["TrackingResponse"]["3"] = "\\PBInt";
    $this->values["3"] = "";
    self::$fieldNames["TrackingResponse"]["3"] = "state";
  }
  function retcode()
  {
    return $this->_get_value("1");
  }
  function set_retcode($value)
  {
    return $this->_set_value("1", $value);
  }
  function retcode_string()
  {
    return $this->values["1"]->get_description();
  }
  function error()
  {
    return $this->_get_value("2");
  }
  function set_error($value)
  {
    return $this->_set_value("2", $value);
  }
  function state()
  {
    return $this->_get_value("3");
  }
  function set_state($value)
  {
    return $this->_set_value("3", $value);
  }
}
?>