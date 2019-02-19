<?php
/**
 * @name SampleModel
 * @desc sample数据获取类, 可以访问数据库，文件，其它系统等
 * @author pc-201707241653\administrator
 */
class TemplateModel {
    public $errno=0;
    public $errmsg="123";
    public function __construct() {
        $this->pdo=new PDO("sqlite:".$_SERVER['DOCUMENT_ROOT']."/user.db");
    }
    public function insert($table, array $bind)
    {
        $cols = array();
        $vals = array();

        foreach ($bind as $k => $v) {
            $cols[] = '`' . $k . '`';
            $vals[] = ':' . $k;
            unset($bind[$k]);
            $bind[':' . $k] = $v;
        }

        $sql = 'INSERT INTO '
            . $table
            . ' (' . implode(',', $cols) . ') '
            . 'VALUES (' . implode(',', $vals) . ')';

        $stmt = $this->query($sql, $bind);
        $res = $stmt->rowCount();
        return $res;
    }


    public function add($data){
        session_start();
        $data["user_id"]=$_SESSION["id"];
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
            $query=$this->pdo->prepare("insert into template ".'('.implode(',', $datak).')'." values ".'('.implode(',', $dataprep).')');
            if($query->execute($datav)){
                $this->errno=1;
                $this->errmsg="添加成功";
            }else{
                $this->errno=0;
                $this->errmsg="添加失败";
            }
        }
    }

    public function alllist(){
        session_start();
        $query=$this->pdo->prepare("select * from template WHERE user_id=?");
        $query->execute([$_SESSION["id"]]);
        $res=$query->fetchAll();
        return $res;
    }
    public function seleteByKeys($id){
        $query=$this->pdo->prepare("select * from template where id=?");
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
            $query=$this->pdo->prepare("update template set ".implode(',', $dataprep)." where id=? and user_id=?");
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
        $query=$this->pdo->prepare("delete from template where id=? and user_id=?");
        if($query->execute([$id,$_SESSION["id"]])){
            $this->errno=1;
            $this->errmsg="删除成功";
        }else{
            $this->errno=0;
            $this->errmsg="删除失败";
        }
    }

}
