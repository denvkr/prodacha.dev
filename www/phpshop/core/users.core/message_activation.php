<?php
/**
 * Сообщение регистрации пользователя
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopCoreFunction
 * @param obj $obj объект класса
 */
function message_activation($obj) {

    $obj->set('user_key',$obj->user_status);
    $obj->set('user_mail',$_POST['mail_new']);
    $obj->set('user_name',str_replace('_','',$_POST['lastname_new'].' '.$_POST['name_new']));
    $obj->set('user_login',$_POST['login_new']);
    $obj->set('user_password',$_POST['password_new']);
    
    // Адрес для сообщений о регистрации
    $admin_mail=$obj->PHPShopSystem->getParam('adminmail2');

    if($obj->PHPShopSystem->ifSerilizeParam('admoption.user_mail_activate')) {

        // Заголовок e-mail пользователю
        $title=$obj->PHPShopSystem->getName()." - ".$obj->locale['activation_title']." ".str_replace('_','',$_POST['lastname_new'].' '.$_POST['name_new']);

        // Содержание e-mail пользователю
        $content=ParseTemplateReturn('./phpshop/lib/templates/users/mail_user_activation.tpl',true);

        // Отправка e-mail пользователя
        $PHPShopMail= new PHPShopMail($_POST['mail_new'],$admin_mail,$title,$content);

        $obj->set('formaContent',ParseTemplateReturn('phpshop/lib/templates/users/message_activation.tpl',true));
    }

    elseif($obj->PHPShopSystem->ifSerilizeParam('admoption.user_mail_activate_pre')) {
        
        // Заголовок e-mail администратору
        $title=$obj->PHPShopSystem->getName()." - ".$obj->locale['activation_admin_title']." ".str_replace('_','',$_POST['lastname_new'].' '.$_POST['name_new']);

        // Содержание e-mail  администратору
        $content=ParseTemplateReturn('./phpshop/lib/templates/users/mail_admin_activation.tpl',true);

        // Отправка e-mail администратору
        $PHPShopMail= new PHPShopMail($admin_mail,$_POST['mail_new'],$title,$content);

        $obj->set('formaContent',ParseTemplateReturn('phpshop/lib/templates/users/message_admin_activation.tpl',true),true);
    }

    
    $obj->set('formaTitle',$obj->lang('user_register_title'));
    $obj->ParseTemplate($obj->getValue('templates.users_page_list'));
}
?>