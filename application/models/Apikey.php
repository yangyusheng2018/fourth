<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/17
 * Time: 20:35
 */

class ApikeyModel{

    public $errno=0;
    public $errmsg="123";
    public function __construct() {
        $this->pdo=new PDO("sqlite:".$_SERVER['DOCUMENT_ROOT']."/user.db");
    }
    public function apikeyList(){
        session_start();
        $query=$this->pdo->prepare("select * from apikey WHERE user_id=?");
        $query->execute([$_SESSION["id"]]);
        $res=$query->fetchAll();
        return $res;
    }
    public function checkkey($keys,$password){
        $ch = curl_init();
        $url = "https://api.godaddy.com/v1/domains/available?domain=1.com&checkType=FAST&forTransfer=false";
        $header=[
            'accept: application/json',
            'Authorization: sso-key '.$keys.':'.$password,
        ];
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//绕过ssl验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output,true);
    }

    public function check($keys,$password){
        $result["domain"]="";
       $result=$this->checkkey($keys,$password);
        if($result["code"]=="UNABLE_TO_AUTHENTICATE"||$result=null|| $result["domain"]=""){
            $this->errno=0;
            $this->errmsg="无效apikey";
        }else{
            session_start();
            $query=$this->pdo->prepare("insert into apikey (keys,password,user_id) values (?,?,?)");
            if($query->execute([$keys,$password,$_SESSION["id"]])){
                $this->errno=1;
                $this->errmsg="添加成功";
            }else{
                $this->errno=0;
                $this->errmsg="添加失败";
            }

        }
    }

    public function seleteByKeys($id){
        $query=$this->pdo->prepare("select * from apikey where id=?");
        $query->execute([$id]);
        if($res=$query->fetchAll()){
            $this->errno=1;
            $this->errmsg="查询成功";
            return $res[0];
        }else{
            $this->errno=0;
            $this->errmsg="查询失败";
        }
    }

    public function updatekey($keys,$password,$id){
        $result["domain"]="";
        $result=$this->checkkey($keys,$password);
        if($result["code"]=="UNABLE_TO_AUTHENTICATE"||$result=null|| $result["domain"]=""){
            $this->errno=0;
            $this->errmsg="无效apikey";
        }else{
            $query=$this->pdo->prepare("update apikey set keys=?,password=? where id=? and user_id=?");
            session_start();
            if ($query->execute([$keys,$password,$id,$_SESSION["id"]])){
                $this->errno=1;
                $this->errmsg="修改成功";
            }else{
                $this->errno=0;
                $this->errmsg="修改失败";
            }
        }
    }

    public function deletekey($id){
        session_start();
        $query=$this->pdo->prepare("delete from apikey where id=? and user_id=?");
        if($query->execute([$id,$_SESSION["id"]])){
            $this->errno=1;
            $this->errmsg="删除成功";
        }else{
            $this->errno=0;
            $this->errmsg="删除失败";
        }
    }



}