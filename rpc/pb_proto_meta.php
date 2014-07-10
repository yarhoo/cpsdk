<?php
class MetaData_Type extends PBEnum
{
  const REQUEST  = 1;
  const RESPONSE  = 2;

  public function __construct($reader=null)
  {
   	parent::__construct($reader);
 	$this->names = array(
			1 => "REQUEST",
			2 => "RESPONSE");
   }
}
class MetaData extends PBMessage
{
  var $wired_type = PBMessage::WIRED_LENGTH_DELIMITED;
  public function __construct($reader=null)
  {
    parent::__construct($reader);
    self::$fields["MetaData"]["1"] = "MetaData_Type";
    $this->values["1"] = "";
    self::$fieldNames["MetaData"]["1"] = "type";
    self::$fields["MetaData"]["2"] = "PBInt";
    $this->values["2"] = "";
    self::$fieldNames["MetaData"]["2"] = "identify";
    self::$fields["MetaData"]["3"] = "PBInt";
    $this->values["3"] = "";
    self::$fieldNames["MetaData"]["3"] = "response_identify";
    self::$fields["MetaData"]["4"] = "PBBytes";
    $this->values["4"] = "";
    self::$fieldNames["MetaData"]["4"] = "content";
  }
  function type()
  {
    return $this->_get_value("1");
  }
  function set_type($value)
  {
    return $this->_set_value("1", $value);
  }
  function type_string()
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
  function response_identify()
  {
    return $this->_get_value("3");
  }
  function set_response_identify($value)
  {
    return $this->_set_value("3", $value);
  }
  function content()
  {
    return $this->_get_value("4");
  }
  function set_content($value)
  {
    return $this->_set_value("4", $value);
  }
}
?>