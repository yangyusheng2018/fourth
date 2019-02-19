<?php

class UserController extends Yaf_Controller_Abstract {

    public function registvAction(){
       return true;
    }
	public function RegistAction() {
        $name=$this->getRequest()->getPost("name");
        $password=$this->getRequest()->getPost("password");

		$usermodel=new UserModel();
		$id=$usermodel->adduser($name,$password);
        if($usermodel->errno==1){
            session_start();
            $_SESSION["name"]=$name;
            $_SESSION["id"]=$id;
        }
        echo json_encode(['errno'=>$usermodel->errno,'errmsg'=>$usermodel->errmsg]);
        return false;
	}
    public function loginvAction(){
        session_start();
        if(isset($_SESSION["id"])){
            echo '<script language="JavaScript">;alert("已经登录");location.href="/user/index";</script>;';
        }else{
            return true;
        }
    }
    public function loginAction() {
        $name=$this->getRequest()->getPost("name");
        $password=$this->getRequest()->getPost("password");
        $usermodel=new UserModel();
        $id=$usermodel->check($name,$password);
        if($usermodel->errno==1){
            session_start();
            $_SESSION["name"]=$name;
            $_SESSION["id"]=$id;
        }
        echo json_encode(['errno'=>$usermodel->errno,'errmsg'=>$usermodel->errmsg]);
        return false;
    }
    public function indexAction(){
        session_start();
        if(!isset($_SESSION["id"])){
            echo '<script language="JavaScript">;alert("请先登录");location.href="/user/loginv";</script>;';
        }else{
            return true;
        }

    }
    public function logoutAction(){
        session_start();
        session_unset();//free all session variable
        session_destroy();//销毁一个会话中的全部数据

        header("location:/user/loginv");
    }
    public function editpasswordvAction(){
        return true;
    }
    public function editpasswordAction(){
        $password1=$this->getRequest()->getPost("password1");
        $password=$this->getRequest()->getPost("password");
        $usermodel=new UserModel();
        $usermodel->checkPassword($password,$password1);
        echo json_encode(['errno'=>$usermodel->errno,'errmsg'=>$usermodel->errmsg]);
        return false;
    }





}
