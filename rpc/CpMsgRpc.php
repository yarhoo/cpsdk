<?php

/**
 * @author maxinjian
 * @copyright 2010-8-20 9:40
 */




class CpMsgRpc
{
    protected $strSocktesAddress = '123.125.219.115';
    protected $strSocketsPort = '10123';
    private $setplen = 7168;
    // public function __construct()
    // {
    //     $this->strSocktesAddress = $arrSysConfig['INTERFACE']['HOST'];
    //     $this->strSocketsPort = $arrSysConfig['INTERFACE']['PORT'];
    // }
    
   
    
    /**
     * 登陆
     * 返回 token
     */
    public function Login($corpname,$stamp,$authstr)
    {
        $objLoginRequest = new LoginRequest();
        // String authstr = this.getMD5((time1 + corpname + passwd).getBytes());
        $authstr = md5($stamp.$corpname.$authstr);
        $objLoginRequest->set_corpname($corpname);
        $objLoginRequest->set_stamp($stamp);
        $objLoginRequest->set_authstr($authstr);

        $strResponse = '';
        // var_dump($strResponse);
        // 发送请求
        try{
            $strResponse = $this->sendRequest('LoginRequest', $objLoginRequest->SerializeToString());
        }catch(Exception $e){
            // var_dump($e);
            return -1;
        }

        // 解析结果
        $LoginResponse = new LoginResponse();
        $LoginResponse->ParseFromString($strResponse);
        //var_dump($LoginResponse->retcode_string());
        if($LoginResponse->retcode() == 0)
            return $LoginResponse->identify();
        return $this->error($LoginResponse);
    }

    /**
    *   发送短信
    *   返回 trackid
    **/
    public function SmsSend($identify, $contentID, $umobile, $service) {
//        $a = new SmsCreateRequest()
        $smsSendRequest = new SmsSendRequest();
        $smsSendRequest->set_contentid($contentID);
        $smsSendRequest->set_identify($identify);
        $smsSendRequest->set_fee_type(1);
        $smsSendRequest->set_serivce($service);
        $smsSendRequest->set_usernumber($umobile);
        $smsSendRequest->set_fee_type(0);
        $smsSendRequest->set_fee_vlaue(0);
        $smsSendRequest->set_agent_flag(1);
        $smsSendRequest->set_mo_flag(1);
        $smsSendRequest->set_link_id(123);
        // request.setContentid(contentID);
        // request.setIdentify(identify);
        // request.setFeeType(1);
        // request.setSerivce("BYYL02");
        // request.setUsernumber("8618667045278");
        // request.setFeeType(0);
        // request.setFeeVlaue(0);
        // request.setAgentFlag(1);
        // request.setMoFlag(1);

        try{
            $strResponse = $this->sendRequest('SmsSendRequestMetaData',$smsSendRequest->SerializeToString());
        }catch(Exception $e) {
            return $e;
        }
        $response = new SmsSendResponse($strResponse);
        $response->ParseFromString($strResponse);

        if($response->retcode() == 0)
            return $response->trackid();
        return $this->error($response);
        // $a = $response->retcode_string();
        //var_dump($a);
		// return $respone->trackid();

    }

    /**
    *   创建短信
    *   返回短信 id
    **/
    public function SmsCreate($identify,$islongsms,$content)
    {
//        $content
        $smsCreateRequest = new SmsCreateRequest();
        $smsCreateRequest->set_identify($identify);
        $smsCreateRequest->set_content(iconv('utf-8','gbk',$content));
		//if ()
        $smsCreateRequest->set_islongmsg($islongsms);
        try{
            $strResponse = $this->sendRequest('SmsCreateRequest',$smsCreateRequest->SerializeToString());
        }catch(Exception $e) {
            return $e;
        }
        $response = new SmsCreateResponse();
        $response->ParseFromString($strResponse);


        if($response->retcode() == 0)
            return $response->contentid();
        return $this->error($response);
        //var_dump($response->retcode_string());

        // return $response->contentid();
    }

    /**
    *   创建彩信
    *   返回id
    **/
    public function MmsCreate($identify,$subject,$productid)
    {
        //        $content
        $mmsCreateRequest = new MmsCreateRequest();
        $mmsCreateRequest->set_identify($identify);
        $mmsCreateRequest->set_subject(iconv('utf-8','gbk',$subject));
        $mmsCreateRequest->set_productID($productid);
        try{
            $strResponse = $this->sendRequest('MmsCreateRequest',$mmsCreateRequest->SerializeToString());
        }catch(Exception $e) {
            return $e;
        }
        $response = new MmsCreateResponse();
        $response->ParseFromString($strResponse);

        if($response->retcode() == 0)
            return $response->messageid();
        return $this->error($response);
        //var_dump($response->retcode_string());

        // return $response->messageid();
    }

    /**
    *   添加附件或者文字
    *   当attname 为空时传入的$content为文字内容，反之则为附件路径
    *   返回 附件id
    **/
    public function MmsApend($identify,$content,$attName,$msgId)
    {
        $request = new MmsAppendRequest();
        $request->set_identify($identify);
        $request->set_messageid($msgId);
        if (strlen($attName) > 0) {
        // var_dump($attName);
            $request->set_attfname($attName);
        }
        $request->set_attache(iconv('utf-8','gbk',$content));
        try{
            $strResponse = $this->sendRequest('MmsAppendRequest',$request->SerializeToString());
        }catch(Exception $e) {
            return $e;
        }
        $response = new MmsAppendResponse();
        $response->ParseFromString($strResponse);
        return $response->attacheid();

        if($response->retcode() == 0){
            return $response->attacheid();
        }
        return $this->error($response);
        //var_dump($response->retcode_string());
        // return $response->attacheid();
    }

    // public function MmsApend($identify,$file,$attName,$msgId)
    // {
    //     $request = new MmsAppendRequest();
    //     $request->set_identify($identify);
    //     $request->set_messageid($msgId);
    //     if (strlen($attName) > 0) {
    //         $request->set_attfname($attName);
    //     }
    //     $request->set_attache($file);

    //     $strResponse = $this->sendRequest('MmsAppendRequest',$request->SerializeToString());
    //     $response = new MmsAppendResponse();
    //     $response->ParseFromString($strResponse);

    //     return $response->attacheid();
    // }
    /**
    *   上传附件
    *   返回成功
    **/
    public function MmsApdFile($identify,$file,$msgId,$attId)
    {
        $size = filesize($file);
        // 控制传入文件的大小
        if($size > 150 * 1024) {
            throw new Exception('exceed max attache size !!');
        }
        $content = file_get_contents($file);
        // 计算总共传多少次
        $len = ceil($size/$this->setplen);

        $offset = 0;
            // $r = $this->MmsApdBin($content,$identify,$attId,$msgId);
        // 循环传入文件
        for($i = 0;$i<$len;$i++) {
            $r = $this->MmsApdBin(substr($content,$offset,$this->setplen),$identify,$attId,$msgId);
            if($r != 0) {
                return array('state'=>false,'attId'=>$attId);
                // 如果传输失败则删除该信息
                $this->MmsDelete($identify,$msgId,$attId);

                // throw new Exception('Error');
            }

            $offset = (int)$offset+(int)$len;
        }
        return array('state'=>true,'attId'=>$attId);
    }

    /**
    *   发送文件片段
    *   返回状态码
    **/
    function MmsApdBin($buff, $identify, $attId, $msgId)
    {
        $request = new MmsApdAttcheRequest();
        $request->set_attacheid($attId);
        $request->set_messageid($msgId);
        $request->set_identify($identify);
        $request->set_bincontent($buff);
        try{
            $strResponse = $this->sendRequest('MmsApdAttcheRequest', $request->SerializeToString());
        }catch(Exception $e) {
            return $e;
        }
        $response = new MmsApdAttcheResponse();
        $response->ParseFromString($strResponse);

        return $response->retcode();
    }

    /**
    *   删除彩信
    *   返回删除状态
    **/
    function MmsDelete($identify, $msgId, $attId)
    {
        $request = new MmsDeleteRequest();
        $request->set_identify($identify);
        $request->set_messageid($msgId);
        if($attId){
            $request->attacheID($attId);
        }
        try{
            $strResponse = $this->sendRequest('MmsDeleteRequest', $request->SerializeToString());
        }catch(Exception $e) {
            return $e;
        }
        $response = new MmsApdAttcheResponse();
        $response->ParseFromString($strResponse);
        //var_dump($response->retcode_string());
        if($response->retcode() == 0)
            return $response->retcode();
        return $this->error($response);
        // return $response->retcode();
    }

    /**
    *   发送彩信
    *   返回trackid
    **/
    function MmsSend($identify, $msgId, $recption, $chargepartid, $linkid, $priority, $readreply, $replycharing)
    {
        $request = new MmsSendRequest();
        $request->set_identify($identify);
        $request->set_messageid($msgId);
        $request->set_ChargedPartyID($chargepartid);
        $request->set_recipent($recption);
        $request->set_Priority($priority);
        $request->set_linkid($linkid);
        $request->set_ReadReply($readreply);
        $request->set_ReplyCharging($replycharing);
        try{
            $strResponse = $this->sendRequest('MmsSendRequest', $request->SerializeToString());
        }catch(Exception $e) {
            return $e;
        }
        $response = new MmsSendResponse();
        $response->ParseFromString($strResponse);
        //var_dump($response->retcode_string());
        if($response->retcode() == 0)
            return $response->trackid();
        return $this->error($response);

		// response->trackid();
    }

    /**
     * 打包Meta,加上head,并取出结果，并从meta中取出结果字符串
     * @param $strServiceIdentify		服务标识  package名 + . + service名 + . + 服务方法名
     * @param $strResponseIdentify		回应标识  package名 + . + Response名
     * @param $strContent				用相应Request打包后的字符串
     * @return 返回相应Response打包后的字符串。
     */
    private function sendRequest($methodname,$strContent,$bAutoCloseConn=TRUE)
    {

        //打包入Meta结构
        $map = array(
            "LoginRequest"=> -1596740182688449530,
            "SmsCreateRequest"=> 331284391979094322,
            "SmsSendRequestMetaData"=> -6870956872586397611,
            "LogoutRequestMetaData"=> 5812766924937041777,
            "MmsCreateRequest"=> 4086778866926967400,
            "MmsAppendRequest"=> -479872875416469944,
            "MmsSendRequest"=> -3995384566764698155,
            "MmsApdAttcheRequest"=> -296392515338512069,
            "MmsDeleteRequest"=> 3007141226173946739,
        );

        $objMetaData = new MetaData();
        $objMetaData->set_type(MetaData_Type::REQUEST);
        $objMetaData->set_identify($map[$methodname]);
		$responseid=rand(0,10000);
        $objMetaData->set_response_identify($responseid);
        $objMetaData->set_content($strContent);
        //var_dump($strServiceIdentify.':'.hash8($strServiceIdentify).'--'.$strResponseIdentify.':'.hash8($strResponseIdentify));
        //加上head
        $strToSend = $objMetaData->SerializeToString();
        $strToSend = strlen($strToSend).':'.$strToSend;
        //echo $strToSend.'\r\n';
        //发送
        try{
            $strResponse = $this->socketSend($strToSend,$bAutoCloseConn);
        }catch(Exception $e){
            throw $e;
        }
        
        //解析结果
        $objMetaData->ParseFromString($strResponse);

        $strContent = $objMetaData->content();
// var_dump($strContent);exit;
        //解释循环引用
        // unset($objMetaData);
        // $arrSysConfig = unserialize(SYS_CONFIG);
        // $memLimit = intval($arrSysConfig['MemThreshold'])*1024*1024;
        // if(memory_get_usage()>$memLimit)
        //     gc_collect_cycles();
        
        return $strContent;
    }
    
    public function test()
    {
        $objEchoRequest = new EchoRequest();
        $objEchoRequest->set_question("hello");
        $strEchoRequest = $objEchoRequest->SerializeToString();

        $objMetaData = new MetaData();
        $objMetaData->set_type(MetaData_Type::REQUEST);
        $objMetaData->set_identify(hash8("Hello.EchoService2.Echo1"));
        $objMetaData->set_response_identify(hash8("Hello.EchoResponse"));
        $objMetaData->set_content($strEchoRequest);
        
        $strToSend = $objMetaData->SerializeToString();
        
        $strToSend = strlen($strToSend).':'.$strToSend;
        
        try{
        $strResponse = $this->socketSend($strToSend);
        }catch(Exception $e){
            echo "服务器连接出错！";
        }
        
        $objMetaData->ParseFromString($strResponse);
        $strContent = $objMetaData->content();
        
        $objEchoRequest = new EchoResponse();
        $objEchoRequest->ParseFromString($strContent);
        
        echo $objEchoRequest->text();
    }

    /**
     * 解析.proto文件，生成类文件
     * 
     * @param string $file 文件位置 
     * @return void
     */
    public function parseFile()
    {
        require_once (__DIR__ . '/ProtocolBuffer/parser/pb_parser.php');
        $objPb = new PBParser();
        $objPb->parse(__DIR__.'/mail.proto');
    }

    /**
     * 
     * 
     * @param mixed $cmd 命令 send或receive
     * @param mixed $mfid 
     * @param mixed $content
     * @return 
     */
    public function operateData($cmd, $mfid = 0, $content = '')
    {
        
        $objTf = new transfersFile();      
        
        $objTfData = $objTf->add_myData();                
        $objTfData->set_cmd($cmd);
        $objTfData->set_mfid($mfid);
        $objTfData->set_content($content);
        $strSerializeConten = $objTf->SerializeToString();

       
        //socket 传送数据
        $objTf->parseFromString($this->socketSend($strSerializeConten)); 
        //$objTf->parseFromString($string);       
        $objData=$objTf->myData(0);
        return $objData->content();
      
    }


    /**
     * 
     * 
     * @param mixed $data
     * @return 
     */
    /*public function socketSend($data)
    {
        $fp = fsockopen($this->strSocktesAddress, $this->strSocketsPort, $errno, $errstr, 5);
        if (!$fp)
        {
            die("$errstr ($errno)<br />\n");
        } else
        {
            fwrite($fp, $data);
            $returnValue='';
            echo 'begin'.time();
            while (!feof($fp))
            {
                $returnValue .= fgets($fp, 128);
            }
            echo 'end'.time();
            fclose($fp);
            return $returnValue;
        }
    }*/
    
    private $socket = FALSE;
    
    /**
     * 得到一个socket连接
     */
    private function CreateConn()
    {
        // Create the socket and connect 
        //如果连接被关，新建一个连接
        if($this->socket==FALSE)
        {
            $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP); 
            if ($this->socket === FALSE) {
                throw new Exception("socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n");
    		}
    		//echo "Attempting to connect to '$this->strSocktesAddress' on port '$this->strSocketsPort'...";

            $result = socket_connect($this->socket, $this->strSocktesAddress, $this->strSocketsPort);

    		if ($result === false) {
    		    throw new Exception("socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($this->socket)) . "\n");
    		}
        }

    }
    
    /**
     * 关闭socket连接
     */
    public function CloseConn()
    {
        if($this->socket!=FALSE)
        {
            socket_close($this->socket);
            $this->socket = FALSE;
        }
    }
    
    /**
     * 通过socket发送数据，并得到返回内容
     * @param $data
     * @param $bAutoCloseConn
     */
    public function socketSend($data,$bAutoCloseConn=TRUE)
    {
        //连接Socket
        $this->CreateConn();

		//socket_write($this->socket, $data, strlen($data));
        $iTotalBytes = strlen($data);
        $iSendedBytes = 0;

		while($iSendedBytes<$iTotalBytes)
        {
        	$strLeaveData = substr($data,$iSendedBytes);
        	$iSendedBytesCur = socket_write($this->socket, $strLeaveData, strlen($strLeaveData));

    		if($iSendedBytesCur===false){
    			//echo "reason:".socket_strerror(socket_last_error($this->socket));
    		    $this->CloseConn();
    		    throw new Exception('读取错误3');
    		}else{
    			$iSendedBytes+=$iSendedBytesCur;
    		}
        }
        
		//找冒号前面的数字
		$strResponseLength = '';
		$iReadCount = 0;


		while(socket_recv($this->socket, $buf, 1, MSG_WAITALL))
		{
		    if($buf==':')
		    {
		        break;
		    }
		    else 
		    {
		        $strResponseLength .= $buf;
		    }
		    $iReadCount += 1;
		    if($iReadCount>50)
		    {
		        $this->CloseConn();
		        throw new Exception('读取错误');
		    }
		}
		if($strResponseLength=='')
		{
		    $this->CloseConn();
		    throw new Exception('读取错误');
		}
		
		//$iRecv = socket_recv($this->socket, $buf, , MSG_WAITALL);
		
		//读取指定字节
		$iTotalRecvBytes = intval($strResponseLength);

		$iRecvBytes = 0;
		$strTotalBuf = '';
        while($iRecvBytes<$iTotalRecvBytes)
        {
        	$iRecvBytesCur = socket_recv($this->socket, $buf, $iTotalRecvBytes, MSG_WAITALL);

    		if($iRecvBytesCur===false){
    		    $this->CloseConn();
    		    throw new Exception('读取错误4');
    		}else{
    		    $strTotalBuf .= $buf;
    			$iRecvBytes+=$iRecvBytesCur;
    		}
        }
		
		//如果要自动关闭连接，则关闭
		if($bAutoCloseConn)
		    $this->CloseConn();

		return $strTotalBuf;
    }

    public function __destruct()
    {

    }

    private function error($response)
    {
        // if($response->trackid() == 0) {
        //     return array('state'=> true, 'response'=> $response);
        // }
        throw new Exception($response->retcode_string());

        // return array('state'=>false,'error'=>$response->retcode_string());
    }

}


?>