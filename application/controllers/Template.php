<?php
/**
 * @name IndexController
 * @author pc-201707241653\administrator
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class TemplateController extends Yaf_Controller_Abstract {
    public function listvAction(){
        $tempmodel=new TemplateModel();
        $res=$tempmodel->alllist();
        $this->_view->assign( ["res"=>$res]);
        return true;
    }
    public function addvAction(){
        return true;
    }
    public function addAction(){
         $data=$this->getRequest()->getPost();
         $tempmodel=new TemplateModel();
         $tempmodel->add($data);
        echo json_encode(['errno'=>$tempmodel->errno,'errmsg'=>$tempmodel->errmsg]);
        return false;
    }
    public function editvAction(){
        $id=$this->getRequest()->Get('id');
        $tempmodel=new TemplateModel();
        $res=$tempmodel->seleteByKeys($id);
        $this->_view->assign( ["res"=>$res]);
        return true;
    }
    public function editAction(){
        $data=$this->getRequest()->getPost();
        $tempmodel=new TemplateModel();
        $tempmodel->update($data);
        echo json_encode(['errno'=>$tempmodel->errno,'errmsg'=>$tempmodel->errmsg]);
        return false;
    }
    public function deleteAction(){
        $id=$this->getRequest()->Get("id");
        $tempmodel=new TemplateModel();
        $tempmodel->delete($id);
        echo json_encode(['errno'=>$tempmodel->errno,'errmsg'=>$tempmodel->errmsg]);
        return false;
    }


}
