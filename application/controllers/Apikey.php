<?php
/**
 * @name IndexController
 * @author pc-201707241653\administrator
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class ApikeyController extends Yaf_Controller_Abstract {
    public function apikeyvAction(){
        $apikeymodel=new ApikeyModel();
        $res=$apikeymodel->apikeyList();
        $this->_view->assign( ["res"=>$res]);
        return true;
    }
    public function apiAddvAction(){
        return true;
    }

    public function apiAddAction(){
        $keys=$this->getRequest()->getPost("keys");
        $password=$this->getRequest()->getPost("password");
        $usermodel=new ApikeyModel();
        $usermodel->check($keys,$password);
        echo json_encode(['errno'=>$usermodel->errno,'errmsg'=>$usermodel->errmsg]);
        return false;
    }

    public function apikeyvEditAction(){
        $id=$this->getRequest()->Get('id');
        $apikeymodel=new ApikeyModel();
        $res=$apikeymodel->seleteByKeys($id);
        $this->_view->assign( ["res"=>$res]);
        return true;
    }
    public function apikeyEditAction(){
        $keys=$this->getRequest()->getPost("keys");
        $password=$this->getRequest()->getPost("password");
        $id=$this->getRequest()->getPost("id");
        $apikeymodel=new ApikeyModel();
        $apikeymodel->updatekey($keys,$password,$id);
        echo json_encode(['errno'=>$apikeymodel->errno,'errmsg'=>$apikeymodel->errmsg]);
        return false;
    }
    public function deleteAction(){
        $id=$this->getRequest()->Get("id");
        $apikeymodel=new ApikeyModel();
        $apikeymodel->deletekey($id);
        echo json_encode(['errno'=>$apikeymodel->errno,'errmsg'=>$apikeymodel->errmsg]);
        return false;
    }
}



