<?php
/**
 * ��������� ����������� ������������
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopCoreFunction
 * @param obj $obj ������ ������
 */
function message_activation($obj) {

    $obj->set('user_key',$obj->user_status);
    $obj->set('user_mail',$_POST['mail_new']);
    $obj->set('user_name',$_POST['name_new']);
    $obj->set('user_login',$_POST['login_new']);
    $obj->set('user_password',$_POST['password_new']);
    
    // ����� ��� ��������� � �����������
    $admin_mail=$obj->PHPShopSystem->getParam('adminmail2');

    if($obj->PHPShopSystem->ifSerilizeParam('admoption.user_mail_activate')) {

        // ��������� e-mail ������������
        $title=$obj->PHPShopSystem->getName()." - ".$obj->locale['activation_title']." ".$_POST['name_new'];

        // ���������� e-mail ������������
        $content=ParseTemplateReturn('./phpshop/lib/templates/users/mail_user_activation.tpl',true);

        // �������� e-mail ������������
        $PHPShopMail= new PHPShopMail($_POST['mail_new'],$admin_mail,$title,$content);

        $obj->set('formaContent',ParseTemplateReturn('phpshop/lib/templates/users/message_activation.tpl',true));
    }

    elseif($obj->PHPShopSystem->ifSerilizeParam('admoption.user_mail_activate_pre')) {
        
        // ��������� e-mail ��������������
        $title=$obj->PHPShopSystem->getName()." - ".$obj->locale['activation_admin_title']." ".$_POST['name_new'];

        // ���������� e-mail  ��������������
        $content=ParseTemplateReturn('./phpshop/lib/templates/users/mail_admin_activation.tpl',true);

        // �������� e-mail ��������������
        $PHPShopMail= new PHPShopMail($admin_mail,$_POST['mail_new'],$title,$content);

        $obj->set('formaContent',ParseTemplateReturn('phpshop/lib/templates/users/message_admin_activation.tpl',true),true);
    }

    
    $obj->set('formaTitle',$obj->lang('user_register_title'));
    $obj->ParseTemplate($obj->getValue('templates.users_page_list'));
}
?>