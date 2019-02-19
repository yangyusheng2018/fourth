<?php
/**
 * @name IndexController
 * @author pc-201707241653\administrator
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class DomainsController extends Yaf_Controller_Abstract {
    public function listvAction(){
        $domainmodel=new DomainsModel();
        $res=$domainmodel->alllist();
        $tempmodel=new TemplateModel();
        $tempmodels=$tempmodel->alllist();
        $this->_view->assign( ["res"=>$res,"tempmodels"=>$tempmodels]);
        return true;
    }
    public function addvAction(){
        $id=$this->getRequest()->Get('id');
        $tempmodel=new TemplateModel();
        $res=$tempmodel->seleteByKeys($id);
        $this->_view->assign( ["res"=>$res]);
        return true;
    }
    public function addAction(){
        $data=$this->getRequest()->getPost();
        $domainmodel=new DomainsModel();
        $domainmodel->add($data);
        echo json_encode(['errno'=>$domainmodel->errno,'errmsg'=>$domainmodel->errmsg]);
        return false;
    }
//    加载编辑模板
    public function editvAction(){
        $id=$this->getRequest()->Get('id');
        $domainmodel=new DomainsModel();
        $res=$domainmodel->seleteByKeys($id);
        $this->_view->assign( ["res"=>$res]);
        return true;
    }
//    编辑
    public function editAction(){
        $data=$this->getRequest()->getPost();
        $domainmodel=new DomainsModel();
        $domainmodel->update($data);
        echo json_encode(['errno'=>$domainmodel->errno,'errmsg'=>$domainmodel->errmsg]);
        return false;
    }

    public function deleteAction(){
        $id=$this->getRequest()->Get("id");
        $domainmodel=new DomainsModel();
        $domainmodel->delete($id);
        echo json_encode(['errno'=>$domainmodel->errno,'errmsg'=>$domainmodel->errmsg]);
        return false;
    }


}
