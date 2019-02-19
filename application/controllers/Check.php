<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/14
 * Time: 11:34
 */
class CheckController extends Yaf_Controller_Abstract
{

    public function gocheckAction()
    {
        $checkmodel=new CheckModel();
        $results=$checkmodel->getUsers();
        if($results!=[]){
            foreach ($results as $result){
                $this->test("域名可注册","域名可注册".$result["Domain"],$result["con_email"]);
                $domainmodel=new DomainsModel();
                $domainmodel->toIsSend($result["Domain"]);
            }
        }

//        print_r($results);
        return false;
    }
    public function redistestAction(){
        $redismodel=new redis();
        $redismodel->connect("127.0.0.1","6379");
        $redismodel->set("test","str1");
        return false;
    }

}