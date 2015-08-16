<?php
/**
 * ������ ������ ����������� �� ������������
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopCoreFunction
 * @param obj $obj ������ ������
 */
function notice_add($obj) {
    $PHPShopOrm = new PHPShopOrm($obj->getValue('base.notice'));

    // �������� �� ������������
    $row=$PHPShopOrm->select(array('id'),array('user_id'=>'='.$obj->UsersId,'product_id'=>'='.$_POST['productId'],'enabled'=>"='0'"),false,array('limit'=>1));

    // ������ � ��
    if(empty($row)) {

        $PHPShopOrm->debug=$obj->debug;
        $PHPShopOrm->insert(array('user_id_new'=>$obj->UsersId,'product_id_new'=>$_POST['productId'],'datas_new'=>time()+($_POST['date']*60*60*24*30),
                'datas_start_new'=>time(),'enabled_new'=>'0'));

        // ���������
        notice_mail($obj);
    }
}

/**
 * ����������� �� ����� ������ ����������� �� ������������
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopCoreFunction
 * @param obj $obj ������ ������
 */
function notice_mail($obj) {

    $PHPShopUser = new PHPShopUser($obj->UsersId);
    if(PHPShopSecurity::true_num($_POST['productId'])) {

        $PHPShopProduct = new PHPShopProduct($_POST['productId']);

        $obj->set('product_name',$PHPShopProduct->getName());
        $obj->set('product_art',$PHPShopProduct->getParam('uid'));
        $obj->set('product_id',$_POST['productId']);
        $obj->set('date',date("d-m-y H:i a"));
        $active=date("U")+($_POST['date']*60*60*24*30);
        $obj->set('date_active',PHPShopDate::dataV($active));
        $obj->set('user_ip',$_SERVER['REMOTE_ADDR']);
        $obj->set('user_mail',$PHPShopUser->getValue('mail'));
        $obj->set('user_name',$PHPShopUser->getValue('name'));
        $obj->set('user_company',$PHPShopUser->getValue('company'));
        $obj->set('user_tel',$PHPShopUser->getValue('tel'));
        $obj->set('user_message',$_POST['message']);

        // ����� ��� ��������� � �����������
        $admin_mail=$obj->PHPShopSystem->getParam('adminmail2');

        // ��������� e-mail
        $title=$obj->PHPShopSystem->getName()." - ".__('��������� ������ �� ����������� � ������')." ".$PHPShopProduct->getName();

        // ���������� e-mail
        $content=ParseTemplateReturn('./phpshop/lib/templates/users/mail_notice_add.tpl',true);

        // �������� e-mail ������������
        $PHPShopMail= new PHPShopMail($admin_mail,$obj->getValue('mail'),$title,$content);
    }
    else $obj->setError404();

}
?>