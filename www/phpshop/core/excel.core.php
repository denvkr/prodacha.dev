<?php
/**
 * ���������� �������� � �������������� ������
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopCore
 */
class PHPShopExcel extends PHPShopCore {

    /**
     * �����������
     */
    function PHPShopExcel() {

        // ��� ��
        $this->objBase=$GLOBALS['SysValue']['base']['products'];

        // ������ �������
        $this->action=array("get"=>"UID",'nav'=>'index');

        parent::PHPShopCore();
    }
    
    function decode($str){
        return base64_decode($str);
    }

     /**
     * ����� �������� � ������
     */
    function UID() {
        $product_uid=PHPShopSecurity::TotalClean($this->decode($_GET['UID']),2);
        $row=$this->PHPShopOrm->select(array('id'),array('uid'=>'="'.$product_uid.'"'),false,array('limit'=>1));
        if(is_array($row))
            if(!empty($row['id'])){
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$this->getValue('dir.dir')."/shop/UID_".$row['id'].".html");
            exit();
        }
    }


    /**
     * �����
     */
    function index() {
        $this->setError404();

    }
}

?>