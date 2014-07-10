/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package Example;

import ProtobufLineFormat.Meta.MetaData;
import com.google.protobuf.ByteString;
import com.google.protobuf.InvalidProtocolBufferException;
import cpmsg.Cpmsg.*;
import java.io.File;
import java.io.FileInputStream;

import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.io.UnsupportedEncodingException;
import java.net.InetSocketAddress;
import java.net.Socket;
import java.util.Date;
import java.util.HashMap;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author hshao
 */
public class MyProtocol {

    private HashMap<String, Long> functionMap;
    private Socket _socket;
    private InetSocketAddress _endpoint;
    private int Sockettime = 100000;
    private int sendBufferSize = 1024;
    private boolean socket_writed = false;
    private final int setplen = 7 * 1024;

    public MyProtocol(String remoteServer, int remotePort) {
        this.functionMap = new HashMap();//moehod，identify 的hash表 
        functionMap.put("cpmsg.LoginRequest", -1596740182688449530l); //登陆函数的 meta 的indentify 值
        functionMap.put("cpmsg.SmsCreateRequest", 331284391979094322l); //创建函数的 meta 的indentify 值
        functionMap.put("cpmsg.SmsSendRequestMetaData", -6870956872586397611l);//发送短信函数的 meta 的indentify 值
        functionMap.put("cpmsg.LogoutRequestMetaData", 5812766924937041777l);//登出函数的 meta 的indentify 值
        functionMap.put("cpmsg.MmsCreateRequest", 4086778866926967400l);//
        functionMap.put("cpmsg.MmsAppendRequest", -479872875416469944l);//
        functionMap.put("cpmsg.MmsSendRequest", -3995384566764698155l);//
        functionMap.put("cpmsg.MmsApdAttcheRequest", -296392515338512069l);//
        functionMap.put("cpmsg.MmsDeleteRequest", 3007141226173946739l);


        _endpoint = new InetSocketAddress(remoteServer, remotePort); // socket 远程连接实例化
        _socket = new Socket();


    }

    /**
     * Login
     *
     * @param corpname
     * @param stamp
     * @param authstr
     * @param retry
     */
    private int Login(String corpname, String passwd, int retry) {
        /**
         * * create builder *
         */
        LoginRequest.Builder request = LoginRequest.newBuilder();
        /*  set paramete **/
        Date date = new Date();
        long time1 = date.getTime() / 1000;
        String authstr = this.getMD5((time1 + corpname + passwd).getBytes());
        request.setCorpname(corpname);
        request.setStamp((int) time1);
        request.setAuthstr(authstr);
        int Identify = 0;
        String method_name = request.getDescriptor().getFullName();
        //System.out.println("authstr=>" + authstr);

        ByteString requestbuild = request.build().toByteString(); // 类型转换  转成 bytestring类型
        LoginResponse.Builder objResponse = LoginResponse.newBuilder();
        //objResponse.setRetcode(new ErrorCode.valueOf(-1));
        int retCode = -1;

        try {
            // socket 通信
            ByteString strResponse = this.sendRequest(method_name, requestbuild);
            if (strResponse == null) {
                System.out.println("strResponse=>" + strResponse);
                System.out.println("login failed!");
            } else {
                objResponse.mergeFrom(strResponse);               //解析获取的response结果
                if (!parse_ret(objResponse.getRetcode(), method_name)) {
                    return 0;
                }
                System.out.println("login ok  identify id :" + objResponse.getIdentify());
                return objResponse.getIdentify();
            }
        } catch (Exception ex) {
            ex.printStackTrace();
            try {
                this._socket.close();
            } catch (IOException ex1) {
                //Logger.getLogger(MyProtocol.class.getName()).log(Level.SEVERE, null, ex1);
            }
            ex.printStackTrace();
            return 0;
        }
        return Identify;
    }

    private String SmsSend(int identify, int retry, long contentID) {
        /**
         * * create builder * return trackid
         */
        String trackid = null;
        //String method_name=cpmsg.Cpmsg.SmsSendRequest.getDescriptor().getFullName();
        SmsSendRequest.Builder request = SmsSendRequest.newBuilder();

        request.setContentid(contentID);
        request.setIdentify(identify);
        request.setFeeType(1);
        request.setSerivce("BYYL02");
        request.setUsernumber("8618667045278");
        request.setFeeType(0);
        request.setFeeVlaue(0);
        request.setAgentFlag(1);
        request.setMoFlag(1);

        // optional param
//        request.setPriority(0);
//        request.setChargemobile("8613013354803");//optional
//        request.setGiveFee(0); //optional
//        request.setExpiretime(""); /*//短消息定时发送的时间 “yymmddhhmmsstnnp” ，其中“tnnp”取固定值“032+”*/
//        request.setScheduletime(""); /*//短消息寿命的终止时间	“yymmddhhmmsstnnp” ，其中“tnnp”取固定值“032+”*/
//        request.setLinkId("");

        String method_name = request.getDescriptorForType().getFullName();
        ByteString requestbuild = request.build().toByteString(); // 类型转换  转成 bytestring类型
        SmsSendResponse.Builder objResponse = SmsSendResponse.newBuilder();
        //objResponse.setRetcode(new ErrorCode.valueOf(-1));
        int retCode = 0;

        try {
            // socket 通信
            ByteString strResponse = this.sendRequest("cpmsg.SmsSendRequestMetaData", requestbuild);
            //rq = null;

            if (strResponse == null) {
                System.out.println("strResponse=>" + strResponse);
                //retCode = ErrorCode.ERR_OCCURED_VALUE;
                System.out.println("sendsms failed!");
                retCode = -1;
            } else {
                // 建立response build

                objResponse.mergeFrom(strResponse);               //解析获取的response结果
                retCode = objResponse.getRetcode().getNumber();   // 得到ERROR_CODE数值
                //objResponse.getr
                if (parse_ret(objResponse.getRetcode(), method_name)) {
                    System.out.println("sendsms success,error code=>" + retCode);
                    return objResponse.getTrackid();
                } else {
                    System.out.println("sendsms failed,error code=>" + retCode);
                }
            }
        } catch (Exception ex) {
            ex.printStackTrace();
            try {
                this._socket.close();
            } catch (IOException ex1) {
                //Logger.getLogger(MyProtocol.class.getName()).log(Level.SEVERE, null, ex1);
            }
            ex.printStackTrace();
        }

        return trackid;
    }

    private long SmsCreate(int identify, int retry) {
        /**
         * * create builder *
         */
        SmsCreateRequest.Builder request = SmsCreateRequest.newBuilder();
        /*  set paramete **/
        //String authstr = this.getMD5((stamp + corpname + passwd).getBytes());
        ByteString aa = ByteString.copyFromUtf8("111111");
        long smsmsgid = 0;
        request.setContent(aa);
        request.setIdentify(identify);
        request.setIslongmsg(false);
        ByteString requestbuild = request.build().toByteString(); // 类型转换  转成 bytestring类型
        String method_name = request.getDescriptor().getFullName();
        SmsCreateResponse.Builder objResponse = SmsCreateResponse.newBuilder();
        int retCode = 0;
        try {
            // socket 通信
            ByteString strResponse = this.sendRequest(method_name, requestbuild);
            if (strResponse == null) {
                System.out.println("strResponse=>" + strResponse);
                //retCode = ErrorCode.ERR_OCCURED_VALUE;
                System.out.println("login failed!");
                retCode = -1;
            } else {
                objResponse.mergeFrom(strResponse);               //解析获取的response结果
                if (this.parse_ret(objResponse.getRetcode(), method_name)) {
                    System.out.println("createsms success,error code=>" + retCode);
                    smsmsgid = objResponse.getContentid();

                } else {
                    System.out.println("createsms failed,error code=>" + retCode);
                }
            }
        } catch (Exception ex) {
            ex.printStackTrace();
            try {
                this._socket.close();
            } catch (IOException ex1) {
                //Logger.getLogger(MyProtocol.class.getName()).log(Level.SEVERE, null, ex1);
            }
            return smsmsgid;
        }

        return smsmsgid;
    }

    /**
     * 打包Meta,加上head,并取出结果，并从meta中取出结果字符串
     *
     * @param strServiceIdentify	服务标识 package名 + . + service名 + . + 服务方法名
     * @param strResponseIdentify	回应标识 package名 + . + Response名
     * @param strContent	用相应Request打包后的字符串
     * @return 返回相应Response打包后的字符串。
     */
    private ByteString sendRequest(String strServiceIdentify, ByteString strContent) {
//        打包入Meta结构
        if (strContent == null || strServiceIdentify == null) {
            return null;
        }
        long response_indenfiy = System.currentTimeMillis();
        MetaData.Builder objMetaData = MetaData.newBuilder();
        objMetaData.setType(MetaData.Type.REQUEST);
        if (!functionMap.containsKey(strServiceIdentify)) {
            System.out.println("unknow method name");
            return null;
        }
        long method = functionMap.get(strServiceIdentify);
        objMetaData.setIdentify(method);
        objMetaData.setResponseIdentify(response_indenfiy);
        // 异步通讯 客户端配合使用的  发送的 response_indenfiy 和 服务器发回来的一样，说明是同一个会话的。 java 没测试过，填写就好了

        int len = strContent.size();
        objMetaData.setContent(strContent);
        MetaData metadataRequest = objMetaData.build();
        len = metadataRequest.toByteArray().length;
        this.getRemoteConnect(); // 获取远程连接
        try {
            OutputStream scowrite = _socket.getOutputStream();  // 建立输出流     
            metadataRequest.writeTo(scowrite);                  // 往输出流 里面写入数据包
            socket_writed = true;
            //Long t2=System.currentTimeMillis();
            // System.out.println("sendRequest socket write cost time:"+ (t2-t1));
        } catch (IOException ex) {
            ex.printStackTrace();
            err_handler();
        }

        int i = 0;
        boolean has_lenth = false;
        byte[] buf = new byte[512]; // read socket inputstream 512 byte
        try {
            while (_socket.getInputStream().read(buf, i, 1) > 0) {
                if (buf[i] == ':') {
                    has_lenth = true;
                    break;
                }
                i++;
            }
        } catch (IOException ex) {
            ex.printStackTrace();
            err_handler();
        }
        if (has_lenth && (i > 1)) {
            int responselen = Integer.parseInt(new String(buf, 0, i));
            buf = null;
            buf = new byte[responselen];
            try {
                _socket.getInputStream().read(buf, 0, responselen);  //读 取 response 数据流
                MetaData metadataResponse = MetaData.parseFrom(buf);    //  解析 response 数据流
                buf = null;
                strContent = metadataResponse.getContent();             // 获取返回的content信息
            } catch (IOException ex) {
                System.out.println("sendRequest: sokcet read body fail " + strServiceIdentify + "read socket Exception: " + ex.getMessage());
                err_handler();
            }
            return strContent;
        }
        return null;
    }

    private String MmsCreate(int token, String subject, String productid) throws InvalidProtocolBufferException {
        String msgid = null;
        int retCode = -10;
        MmsCreateRequest.Builder request = MmsCreateRequest.newBuilder();
        request.setIdentify(token);
        String method_name = request.getDescriptor().getFullName();

        byte[] bytes = null;
        try {
            bytes = ("主题测试--- " + System.currentTimeMillis()).getBytes("GBK");

        } catch (UnsupportedEncodingException ex) {
            //Logger.getLogger(MyProtocol.class.getName()).log(Level.SEVERE, null, ex);
        }
        //ByteString  subj=ByteString (bytes );
        ByteString subj = ByteString.copyFrom(bytes);
        request.setSubject(subj);
        request.setProductID("mmstest0");

        ByteString requestbuild = request.build().toByteString(); // 类型转换  转成 bytestring类型

        MmsCreateResponse.Builder response = MmsCreateResponse.newBuilder();
        ByteString strResponse = this.sendRequest(method_name, requestbuild);

        if (strResponse == null) {
            System.out.println("strResponse=>" + strResponse);
            //retCode = ErrorCode.ERR_OCCURED_VALUE;
            System.out.println("login failed!");
        } else {
            // 建立response build
            response.mergeFrom(strResponse);               //解析获取的response结果
            if (this.parse_ret(response.getRetcode(), method_name)) {
                System.out.println(method_name + " success,error code=>" + retCode);
                msgid = response.getMessageid();
            } else {
                System.out.println(method_name + " failed,error code=>" + retCode);
            }

        }
        System.out.println(method_name + " return msgid: " + msgid);
        return msgid;

    }

    private boolean MmsDelete(int token, String msgid, String attachid) throws InvalidProtocolBufferException {

        int retCode = -10;
        MmsDeleteRequest.Builder request = MmsDeleteRequest.newBuilder();
        String method_name = request.getDescriptor().getFullName();
        request.setIdentify(token);
        if (attachid != null) {
            request.setAttacheID(attachid);
        }
        request.setMessageid(msgid);


        ByteString requestbuild = request.build().toByteString(); // 类型转换  转成 bytestring类型
        MmsDeleteResponse.Builder response = MmsDeleteResponse.newBuilder();
        ByteString strResponse = this.sendRequest(method_name, requestbuild);
        if (strResponse == null) {
            System.out.println("strResponse=>" + strResponse);
            //retCode = ErrorCode.ERR_OCCURED_VALUE;
            System.out.println("call method failed!");
            return false;
        } else {
            // 建立response build
            response.mergeFrom(strResponse);               //解析获取的response结果
            if (this.parse_ret(response.getRetcode(), method_name)) {
                System.out.println(method_name + " success,error code=>" + retCode);
                return true;
                //msgid = response.getMessageid();
            } else {
                System.out.println(method_name + " failed,error code=>" + retCode);
            }
        }
        return false;
    }

    private String MmsAppend(int token, String content, String attache_name, String msgid) throws Exception {
        //boolean ret = true;
        int retCode = -1;
        MmsAppendRequest.Builder request = MmsAppendRequest.newBuilder();
        String method_name = request.getDescriptor().getFullName();
        // byte[] bytes=null;
        InputStream in = null;
        //long fileSize = 0;
        ByteString sb;
        //  byte[] buffer = null;
        // StringBuffer sb=new StringBuffer();
        if (attache_name != null) {
            File file = new File(content);
            if (!file.exists()) {
                throw new Exception("file not exist !!");
            }
            sb = ByteString.copyFrom(new byte[0]);
            request.setAttfname(attache_name);

        } else {
            sb = ByteString.copyFrom(content, "gbk");
        }
        request.setIdentify(token);
        request.setMessageid(msgid);

        //byte[] sendbuf = new byte[step];
        // if (buffer == null ) 
        request.setAttache(sb);
        ByteString requestbuild = request.build().toByteString();
        MmsAppendResponse.Builder response = MmsAppendResponse.newBuilder();
        ByteString strResponse = this.sendRequest(method_name, requestbuild);
        if (strResponse == null) {
            System.out.println("strResponse=>" + strResponse);
            //retCode = ErrorCode.ERR_OCCURED_VALUE;
            System.out.println(" failed!");
            return null;
        } else {
            response.mergeFrom(strResponse);               //解析获取的response结果
            if (this.parse_ret(response.getRetcode(), method_name)) {
                System.out.println(method_name + " success,error code=>" + retCode);
                return response.getAttacheid();
            } else {
                System.out.println(method_name + " failed,error code=>" + retCode);
                return null;
            }

        }
    }

    private boolean MmsApdFile(int token, String filepath, String msgid, String attid) throws Exception {

        if (filepath == null) {
            return false;
        }
        InputStream in = null;
        long fileSize = 0;
        byte[] buffer = null;
        File file = new File(filepath);
        if (!file.exists()) {
            throw new Exception("file not exist !!");
        }

        if ((fileSize = file.length()) > 150 * 1024) {
            throw new Exception("exceed max attache size !!");
        }
        in = new FileInputStream(file);
        buffer = new byte[(int) fileSize];
        int offset = 0;
        int numRead = 0;
        while (offset < buffer.length && (numRead = in.read(buffer, offset, buffer.length - offset)) >= 0) {
            offset += numRead;
        }
        if (offset != buffer.length) {
            throw new IOException("Could not completely read file " + file.getName());
        }

        return MmsApdBin(buffer, token, attid, msgid);

    }

    boolean MmsApdBin(byte[] buffer, int token, String attacheid, String msgid) throws InvalidProtocolBufferException {
        //final int step=7084;
        byte[] sendbuf = new byte[setplen];
        ByteString sb;

        //int sendbytes=0;
        cpmsg.Cpmsg.MmsApdAttcheRequest.Builder request = MmsApdAttcheRequest.newBuilder();
        cpmsg.Cpmsg.MmsApdAttcheResponse.Builder response = cpmsg.Cpmsg.MmsApdAttcheResponse.newBuilder();
        String method_name = request.getDescriptor().getFullName();
        request.setIdentify(token);
        request.setMessageid(msgid);
        request.setAttacheid(attacheid);

        for (int sendbytes = 0; sendbytes <= buffer.length; sendbytes += setplen) {

            System.arraycopy(buffer, sendbytes, sendbuf, 0, Math.min(setplen, buffer.length - sendbytes));
            sb = ByteString.copyFrom(sendbuf);
            request.setBincontent(sb);
            ByteString requestbuild = request.build().toByteString(); // 类型转换  转成 bytestring类型

            ByteString strResponse = this.sendRequest(method_name, requestbuild);
            if (strResponse == null) {
                System.out.println("strResponse=>" + strResponse);
                System.out.println(" failed!");
                return false;
            } else {
                response.mergeFrom(strResponse);               //解析获取的response结果
                if (this.parse_ret(response.getRetcode(), method_name)) {
                    System.out.println(method_name + " success  " + sendbytes + " has been sent !");
                } else {
                    System.out.println(method_name + " failed !!! ");
                    return false;
                }
            }
        }
        return true;
    }

    String MmsSend(int token, String msgid, String recption, String chargepartid, String linkid, int priority, boolean readreply, boolean replycharing) throws InvalidProtocolBufferException {

        String track_id = null;
        cpmsg.Cpmsg.MmsSendRequest.Builder request = MmsSendRequest.newBuilder();
        cpmsg.Cpmsg.MmsSendResponse.Builder response = cpmsg.Cpmsg.MmsSendResponse.newBuilder();
        String method_name = request.getDescriptor().getFullName();
        request.setIdentify(token);
        request.setMessageid(msgid);
        request.setRecipent(recption);
        request.setChargedPartyID(chargepartid);
        ByteString requestbuild = request.build().toByteString(); // 类型转换  转成 bytestring类型
        ByteString strResponse = this.sendRequest(method_name, requestbuild);
        if (strResponse == null) {
            System.out.println("strResponse=>" + strResponse);
            System.out.println(" failed!");
            return track_id;
        } else {
            response.mergeFrom(strResponse);               //解析获取的response结果
            if (this.parse_ret(response.getRetcode(), method_name)) {
                System.out.println("send mms sucess track id: " + response.getTrackid());
                track_id = response.getTrackid();
            } else {
                System.out.println(method_name + " failed !!!");
            }
        }
        return track_id;
    }

    boolean parse_ret(ErrorCode ret, String method) {
        switch (ret.getNumber()) {
            case cpmsg.Cpmsg.ErrorCode.SUCCESS_VALUE:
                System.out.println("call " + method + " 成功 ;");
                return true;
            case cpmsg.Cpmsg.ErrorCode.AUTH_ERR_VALUE:
                System.out.println("call " + method + "  账号密码认证错误 !!!!");
                return false;
            case cpmsg.Cpmsg.ErrorCode.ERR_OCCURED_VALUE:
                System.out.println("call " + method + " 发生异常错误  !!!!");
                return false;
            case cpmsg.Cpmsg.ErrorCode.EXCEED_MSG_CAPACITY_VALUE:
                System.out.println("call " + method + " 超出消息体容量限制   !!!!");
                return false;
            case cpmsg.Cpmsg.ErrorCode.EXCEED_SESSION_LIMIT_VALUE:
                System.out.println("call " + method + " 登陆数过多  !!!!");
                return false;
            case cpmsg.Cpmsg.ErrorCode.INVAILD_IDENTIFY_VALUE:
                System.out.println("call " + method + "  提供的链接号token错误 !!!!");
                return false;
            case cpmsg.Cpmsg.ErrorCode.INVAILD_PARAMETER_VALUE:
                System.out.println("call " + method + " 无效参数，请检查  !!!!");
                return false;
            case cpmsg.Cpmsg.ErrorCode.OUT_OF_FUND_VALUE:
                System.out.println("call " + method + "  费用不足或账号停用 !!!!");
                return false;
            case cpmsg.Cpmsg.ErrorCode.TRY_LATER_VALUE:
                System.out.println("call " + method + "  系统忙，请稍后再试 !!!!");
                return false;
            default:
                System.out.println("call " + method + " 未定义的错误变量");
                return false;
        }
    }

    /**
     * 远程 socket连接建立
     */
    private void getRemoteConnect() {
        int errcount = 0;
        boolean isConnected = false;
        while (isConnected == false) {
            isConnected = testConnect();
            if (isConnected) {
                break;
            }
            try {
                try {
                    _socket.close();
                } catch (Exception ex) {
                }
                _socket = null;
                _socket = new Socket();
                socket_writed = false;
                _socket.setSoTimeout(Sockettime);    //  超时时间
                _socket.setSendBufferSize(sendBufferSize);    // 发送数据包大小
                _socket.setKeepAlive(true);
                _socket.connect(_endpoint, Sockettime);
                continue;
            } catch (Exception ex) {
                System.out.println(" Connect remote :" + _endpoint.getHostName() + " fail");
                ex.printStackTrace();
                errcount++;
            }
            if (errcount > 1) {
                try {
                    Thread.sleep(1000);
                } catch (InterruptedException ex) {
                }
                System.out.println("connect remote server fail: pauzed retry : " + errcount);
            }
        }
    }

    /**
     * 测试远程连接是否可用
     *
     * @return
     */
    private boolean testConnect() {
        try {
            if (this._socket.isClosed() == false && this._socket.isConnected()) {
                _socket.sendUrgentData(0xff);
                return ((!this._socket.isInputShutdown() && !this._socket.isOutputShutdown()));
            }
        } catch (Exception ex) {
            ex.printStackTrace();
            return false;
        }
        return false;
    }

    public void close() {
        if (_socket == null) {
            return;
        }
        if (!_socket.isConnected()) {
            try {
                _socket.close();
            } catch (IOException ex) {
            }
        }
    }

    /**
     * MD5 码转换
     *
     * @param source
     * @return
     */
    private String getMD5(byte[] source) {
        String s = null;
        char hexDigits[] = { // 用来将字节转换成 16 进制表示的字符
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f'};
        try {
            java.security.MessageDigest md = java.security.MessageDigest.getInstance("MD5");
            md.update(source);
            byte tmp[] = md.digest();          // MD5 的计算结果是一个 128 位的长整数，
            // 用字节表示就是 16 个字节
            char str[] = new char[16 * 2];   // 每个字节用 16 进制表示的话，使用两个字符，
            // 所以表示成 16 进制需要 32 个字符
            int k = 0;                                // 表示转换结果中对应的字符位置
            for (int i = 0; i < 16; i++) {          // 从第一个字节开始，对 MD5 的每一个字节
                // 转换成 16 进制字符的转换
                byte byte0 = tmp[i];                 // 取第 i 个字节
                str[k++] = hexDigits[byte0 >>> 4 & 0xf];  // 取字节中高 4 位的数字转换, 
                str[k++] = hexDigits[byte0 & 0xf];            // 取字节中低 4 位的数字转换
            }
            s = new String(str);                                 // 换后的结果转换为字符串
        } catch (Exception e) {
            // e.printStackTrace();
        }
        return s;
    }

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) throws InterruptedException, InvalidProtocolBufferException, Exception {
        System.out.println(System.currentTimeMillis());
        int token = 0;
        //MyProtocol test = new MyProtocol("123.125.219.115", 10123);
         MyProtocol test = new MyProtocol("127.0.0.1", 10123);
        Long time1 = System.currentTimeMillis();
        token = test.Login("trasintest", "trasin123", 1);		 // 测试登陆

        if (token == 0) {
            System.out.println("login fail ");
            System.exit(1);
        }

        Long time2 = System.currentTimeMillis();
        System.out.println("\n: spent time :" + (time2 - time1) + " \n");
        /*
         time1 = System.currentTimeMillis();
         long contentId = test.createsms(token, 10);
         if ( contentId==0) {
         System.out.println("  call login request return error  ");
         System.exit(1);
         }
         */

        //彩信部分
        String mmsgid;
        mmsgid = test.MmsCreate(token, "subjetest1", "myservice");// token,subject,product 
        if (mmsgid == null) {
            System.out.println("  call createmms return error  ");
            System.exit(1);
        }

        String attname = "001.zip", attch_ID;
        String file = "d://temp//" + attname;
        /*
         *增加 二进制附件
         */
        if ((attch_ID = test.MmsAppend(token, file, attname, mmsgid)) == null) //
        /*
         * 增加文本
         * if ( (attch_ID=test.MmsAppend(token, "中文测试有意义 "+System.currentTimeMillis(), null, mmsgid))==null) 
         *
         */ {
            System.out.println("call appendmms return error ");
            System.exit(1);
        } else {
            if (test.MmsApdFile(token, file, mmsgid, attch_ID)) {
                System.out.println("附件添加成功 ");
            } else {
                System.out.println("删除未添加成功的附件 ");
                test.MmsDelete(token, mmsgid, attch_ID);
            }
        }
         test.MmsDelete(token, mmsgid, attch_ID);
         test.MmsDelete(token, mmsgid, null);
        
        String mms_track_id = null;
        mms_track_id = test.MmsSend(token, mmsgid, "8613212345678", "8613012345678", "000000000000", 1, true, true);
        if (mms_track_id != null) {
            System.exit(0);
        }
    }

    private void err_handler() {
        try {
            this._socket.close();
        } catch (Exception ex) {
        }
    }
}
