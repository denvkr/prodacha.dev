<?php

/**
 * Панель печатных форм
 * @param array $row массив данных
 * @return string 
 */
function tab_print($data) {
    global $PHPShopGUI, $PHPShopSystem;

    $disp = null;

    // Бланк заказа
    $disp.=$PHPShopGUI->setButton(__('Бланк заказа'), '../img/action_print.gif', 130, 30, $float = "left", $onclick = "DoPrint('forms/forma.php?orderID=" . $data['id'] . "'); return false;");

    // Товарный чек
    $disp.=$PHPShopGUI->setButton(__('Товарный чек'), '../img/action_print.gif', 130, 30, $float = "left", $onclick = "DoPrint('forms/forma2.php?orderID=" . $data['id'] . "'); return false;");

    // Гарантия
    $disp.=$PHPShopGUI->setButton(__('Гарантия'), '../img/action_print.gif', 130, 30, $float = "left", $onclick = "DoPrint('forms/forma3.php?orderID=" . $data['id'] . "'); return false;");

    // Счет
    $disp.=$PHPShopGUI->setButton(__('Счет'), '../img/action_print.gif', 130, 30, $float = "left", $onclick = "DoPrint('../../../phpshop/forms/account/forma.html?orderId=".$data['id']."&tip=2&datas=".$data['datas']."'); return false;");

    // Счет в сбербанк
    $disp.=$PHPShopGUI->setButton(__('Сбербанк'), '../img/action_print.gif', 130, 30, $float = "left", $onclick = "DoPrint('../../../phpshop/forms/receipt/forma.html?orderId=".$data['id']."&tip=2&datas=".$data['datas']."'); return false;");
    // Счет-Фактура
    $disp.=$PHPShopGUI->setButton(__('Счет-Фактура'), '../img/action_print.gif', 130, 30, $float = "left", $onclick = "DoPrint('forms/forma4.php?orderID=" . $data['id'] . "'); return false;");

    // Почта
    $order = unserialize($data['orders']);
    $mail = $order['Person']['mail'];
    $mail_title = "Re: " . $PHPShopSystem->getParam('name') . " - Заказ №" . $data['uid'];
    $disp.=$PHPShopGUI->setButton(__('E-mail'), '../img/icon_email.gif', 130, 30, $float = "left", $onclick = "window.open('mailto:" . $mail . "?subject=" . $mail_title . "'); return false;");

    return $disp;
}

?>
