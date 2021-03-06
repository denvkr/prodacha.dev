<?php

// ����������� ������ ������� ��������
PHPShopObj::importCore('users');

/**
 * ���������� �������� ������ �������������
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopUsers
 * @version 1.0
 * @package PHPShopCore
 */
class PHPShopClients extends PHPShopUsers {
    /**
     * @var int ���� ���������� � ������� ��������, ����� �����������
     */
    var $order_live=3;

    /**
     * �����������
     */
    function PHPShopClients() {

        // �������
        $this->debug=false;
        parent::PHPShopUsers();

        // ������ �������
        $this->action=array('get'=>array('order'),'post'=>array('order'),'nav'=>'index');
    }

    /**
     * ����� �� ��������
     */
    function action_index() {

        $this->set('formaContent',ParseTemplateReturn($this->getValue('templates.clients_forma')));
        $this->set('formaTitle',__('On-line �������� ��������� ������'));

        // �������� ������
        $this->setHook(__CLASS__,__FUNCTION__);

        $this->ParseTemplate($this->getValue('templates.clients_page_list'));
    }

    /**
     * �������� ���������� �����
     * @return bool
     */
    function true_user() {

        // �������� ������
        $hook=$this->setHook(__CLASS__,__FUNCTION__);
        if($hook) return $hook;

        if(PHPShopSecurity::true_order($_REQUEST['order']) and PHPShopSecurity::true_email($_REQUEST['mail'])) {
            return true;
        }
    }

    /**
     * ����� ������ ������ �� ������
     */
    function action_order() {

        // �������� ����������� �����������
        if($this->true_user()) {

            // ����������� ������� ������ ������ �� ������
            $this->doLoadFunction(__CLASS__,'action_order_info',$tip=2,'users');

            $this->set('formaTitle',__('On-line �������� ��������� ������'));

            // �������� ������
            $this->setHook(__CLASS__,__FUNCTION__);
            
            $this->ParseTemplate($this->getValue('templates.users_page_list'));
        }
        else {
            // ����� ����� ������
            $this->action_index();
        }
    }
}
?>