<?php
/**
 * @name SampleModel
 * @desc sample数据获取类, 可以访问数据库，文件，其它系统等
 * @author pc-201707241653\administrator
 */
class DomainsModel {
    public $errno=0;
    public $errmsg="123";
    public function __construct() {

            $this->pdo=new PDO("sqlite:".$_SERVER['DOCUMENT_ROOT']."/user.db");
    }
    public function instertData($data){
        foreach ($data as $k=>$v){
            if(!$v){
                $isnull=0;
                break;
            }else{
                $datak[]=$k;
                $datav[]=$v;
                $dataprep[]="?";
                $isnull=1;
            }
        }
        if($isnull==0){
            $this->errmsg="数据不能为空";
            $this->errno="0";
        }else{
            $query=$this->pdo->prepare("insert into domains ".'('.implode(',', $datak).')'." values ".'('.implode(',', $dataprep).')');
            if($query->execute($datav)){
                $this->errno=1;
                $this->errmsg="添加成功";
            }else{
                $this->errno=0;
                $this->errmsg="添加失败";
            }
        }
        return $this->errno;
    }

    public function add($data){
        session_start();
        $data["user_id"]=$_SESSION["id"];
        $domains=explode(",",$data["domain"]);
        $data["start_time"]=strtotime($data["start_time"]);
        $data["end_time"]=strtotime($data["end_time"]);
        $data["domain"]="";
        foreach ($domains as $domainv){
            $query=$this->pdo->prepare("select * from domains WHERE user_id=? and Domain=?");
            $query->execute([$_SESSION["id"],$domainv]);
            if($query->fetchAll()){
                $this->errno=0;
                $this->errmsg="域名已存在";
            }else{
                $data["domain"]=$domainv;
                $errnos=$this->instertData($data);
                if($errnos==0){
                    $this->errno=0;
                    $this->errmsg=$domainv."添加失败";
                    break;
                }else{
                    $this->errno=1;
                    $this->errmsg="添加成功";
                }
            }
        }

    }

    public function alllist(){
        session_start();
        $query=$this->pdo->prepare("select * from domains WHERE user_id=?");
        $query->execute([$_SESSION["id"]]);
        $res=$query->fetchAll();
        return $res;
    }
    public function seleteByKeys($id){
        $query=$this->pdo->prepare("select * from domains where id=?");
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
    public function update($data){
        $data["start_time"]=strtotime($data["start_time"]);
        $data["end_time"]=strtotime($data["end_time"]);
        session_start();
        $datav[]=$data["id"];
        unset($data["id"]);
        foreach ($data as $k=>$v){
            if(!$v){
                $isnull=0;
                break;
            }else{
                $dataprep[]=$k."='".$v."'";
                $isnull=1;
            }
        }
        $datav[]=$_SESSION["id"];
        if($isnull==0){
            $this->errmsg="数据不能为空";
            $this->errno="0";
        }else{
            $query=$this->pdo->prepare("update domains set ".implode(',', $dataprep)." where id=? and user_id=?");
            if($query->execute($datav)){
                $this->errno=1;
                $this->errmsg="修改成功";
            }else{
                $this->errno=0;
                $this->errmsg="修改失败";
            }
        }
    }
    public function delete($id){
        session_start();
        $query=$this->pdo->prepare("delete from domains where id=? and user_id=?");
        if($query->execute([$id,$_SESSION["id"]])){
            $this->errno=1;
            $this->errmsg="删除成功";
        }else{
            $this->errno=0;
            $this->errmsg="删除失败";
        }
    }
    public function toIsSend($domain){
        $query=$this->pdo->prepare("update domains set is_send = 1 where domain=?");
        $query->execute([$domain]);
    }
}
