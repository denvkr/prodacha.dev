<?php

/**
 * Добавление кнопки резервирования
 */
function send_to_order_mod_supplierdate_hook($obj, $val, $rout) {
    if ($rout == 'END') {
        $reserv_key = 'mI' . base64_encode($_POST['ouid']);
        $obj->set('reserv_id', $reserv_key);
        $obj->set('orderMesage', parseTemplateReturn('phpshop/modules/supplierdate/templates/order_button_reserv.tpl', true), true);
    }
}

/**
 * Отправка сообщения с дополнительными данными
 */
function mail_mod_supplierdate_hook($obj, $content, $rout) {

    if ($rout == 'END' and !empty($_POST['user_type'])) {
        $content.='
Факс: ' . $_POST['dop_pole_5'] . '
Юридический адрес: ' . $_POST['dop_pole_1'] . '
Фактический адрес: ' . $_POST['dop_pole_2'] . '
Код ОКПО: ' . $_POST['dop_pole_4'] . '
';
        // Заголовок письма администратору
        $title_adm = $obj->PHPShopSystem->getName() . ' - ' . $obj->lang('mail_title_adm') . $_POST['ouid'] . "/" . date("d-m-y");

        // Сообщение администратору
        if (!empty($_FILES['dop_pole_6']['tmp_name']))
            new PHPShopMailFile($obj->PHPShopSystem->getParam('adminmail2'), $_POST['mail'], $title_adm, $content, $_FILES['dop_pole_6']['name'], $_FILES['dop_pole_6']['tmp_name']);
        else
            new PHPShopMail($obj->PHPShopSystem->getParam('adminmail2'), $_POST['mail'], $title_adm, $content);
    }

    if ($rout == 'END') {

        // Сообщение пользователю
        $obj->set('mod_user', $_POST['name_person']);
        $reserv_key = 'mI' . base64_encode($_POST['ouid']);
        $obj->set('reserv_key', $reserv_key);
        $user_content = parseTemplateReturn('phpshop/modules/supplierdate/templates/user_mail.tpl', true);
        $user_title = $obj->lang('reserv_mail_title') . ' ' . $_POST['ouid'] . "/" . date("d-m-y");
        new PHPShopMail($_POST['mail'], $obj->PHPShopSystem->getParam('adminmail2'), $user_title, $user_content);

        return true;
    }
}

/**
 * Запись в БД дополнительных данных
 */
function write_mod_supplierdate_hook($obj, $value, $rout) {

    if ($rout == 'END' and !empty($_POST['user_type'])) {
        echo $_POST['mod_supplier_info_new'];
        $_POST['mod_supplier_info_new'] = '
Факс: ' . $_POST['dop_pole_5'] . '
Юридический адрес: ' . $_POST['dop_pole_1'] . '
Фактический адрес: ' . $_POST['dop_pole_2'] . '
Код ОКПО: ' . $_POST['dop_pole_4'] . '
';
    }
}

$addHandler = array
    (
    'write' => 'write_mod_supplierdate_hook',
    'mail' => 'mail_mod_supplierdate_hook',
    'send_to_order' => 'send_to_order_mod_supplierdate_hook'
);
?>