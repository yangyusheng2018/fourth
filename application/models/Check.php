<?php
/**
 * @name SampleModel
 * @desc sample数据获取类, 可以访问数据库，文件，其它系统等
 * @author pc-201707241653\administrator
 */
class CheckModel {
    public $errno=0;
    public $errmsg="123";
    public function __construct() {
        $this->pdo=new PDO("sqlite:".$_SERVER['DOCUMENT_ROOT']."/user.db");
    }
//获取所有用户godaddy apikey
    public function getApikey() {
        $query=$this->pdo->prepare("select keys,password from apikey");
        $query->execute();
        $res=$query->fetchAll();
        foreach($res as $x_value) {
                $data[]=[$x_value["keys"],$x_value["password"]];
        }
        return $data;
    }
    //获取所有用户加入域名
    public function getDomain() {
        $nowtime=time()+3600*7;
        $query=$this->pdo->prepare("select Domain from domains where start_time<? and end_time>? and is_send=0");
        $query->execute([$nowtime,$nowtime]);
        $res=$query->fetchAll();
        foreach($res as $x=>$x_value) {
                $data[] = $x_value["Domain"];
        }
        return $data;
    }
//    检测域名注册状态
    public function check($domain,$key){
        $godaddykey=implode(":",$key);
        $ch = curl_init();
        $url = "https://api.godaddy.com/v1/domains/available?domain=".$domain."&checkType=FAST&forTransfer=false";

//        'Authorization: sso-key dLiW5qgMZd7f_MtT8PtBEpqKvoYywPW4kTJ:MtTCeRm6o8NZ3b1fz5Y8QY'
        $header=[
            'accept: application/json',
            'Authorization: sso-key '.$godaddykey
        ];
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//绕过ssl验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//执行并获取HTML文档内容
        $output = curl_exec($ch);
//释放curl句柄
        curl_close($ch);
        return json_decode($output,true);
//        return $header;
    }
//    获取可注册域名
    public function checkAll(){
        $apikeys=$this->getApikey();
        $data=$this->getDomain();
        $data[]="1.com";
        $domains=array_unique($data);

        foreach ($domains as $domain){
            $apikey=$apikeys[array_rand($apikeys)];
            $results[]=$this->check($domain,$apikey);
        }
        foreach ($results as $result){
            if($result["available"]==1){
                $ava_domain[]=$result["domain"];
            }
        }
        return $ava_domain;
    }
//    获取添加可注册域名详细信息
    public function getUsers(){
        $ava_domain=$this->checkAll();
        if(!empty($ava_domain)){
            foreach ($ava_domain as $domain){
                $query=$this->pdo->prepare("select con_email,domain from domains where domain=?");
                $query->execute([$domain]);
                $res=$query->fetchAll();
            }
        }else{
            $res=[];
        }

        return $res;
    }

    public function insertSample($arrInfo) {
        return true;
    }
}
