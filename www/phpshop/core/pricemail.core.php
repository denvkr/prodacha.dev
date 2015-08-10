<?php

/**
 * Обработчик жалобы на цену
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopCore
 */
class PHPShopPricemail extends PHPShopCore {

    /**
     * Конструктор
     */
    function PHPShopPricemail() {
        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['table_name17'];

        // Отладка
        $this->debug = false;

        // список экшенов
        $this->action = array("nav" => "index", "post" => "send_price_link");
        parent::PHPShopCore();
    }

    /**
     * Экшен по умолчанию 
     */
    function index() {

        if (PHPShopSecurity::true_num($this->PHPShopNav->getId()))
            $this->forma();
        else
            $this->setError404();
    }

    /**
     * Экшен отправка формы при получении $_POST[send_price_link]
     */
    function send_price_link() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST))
            return true;

        if (!empty($_SESSION['text']) and $_POST['key'] == $_SESSION['text']) {
            $this->send();
            $this->set('Error', __("Сообщение успешно отправлено"));
        }else
            $this->set('Error', __("Ошибка ключа, повторите попытку ввода ключа"));
        $this->index();
    }

    /**
     * Отправление данных на почту и добавление в БД
     */
    function send() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST, 'START'))
            return true;

        if (PHPShopSecurity::true_param($_SESSION['text'], $_POST['mail'], $_POST['name_person'], $_POST['link_to_page']) and $_POST['key'] == $_SESSION['text']) {

            // Заголовок e-mail пользователю
            $title = $this->PHPShopSystem->getName() . " - " . __('Сообщение о меньшей цене');

            $this->set('user_name', $_POST['name_person']);
            $this->set('user_org_name', $_POST['org_name']);
            $this->set('user_tel_code', $_POST['tel_code']);
            $this->set('user_tel', $_POST['tel_name']);
            $this->set('user_info', $_POST['adr_name']);
            $this->set('user_mail', $_POST['mail']);
            $this->set('link_to_page', $_POST['link_to_page']);

            // Даннеы по товару
            $PHPShopProduct = new PHPShopProduct($this->PHPShopNav->getId());
            $this->set('product_name', $PHPShopProduct->getName());
            $this->set('product_art', $PHPShopProduct->getParam('uid'));
            $this->set('product_id', $this->PHPShopNav->getId());
            $this->set('product_link', $_SERVER['SERVER_NAME'] . "/shop/UID_" . $this->PHPShopNav->getId() . ".html");

            // Перехват модуля
            $this->setHook(__CLASS__, __FUNCTION__, $_POST);

            // Содержание e-mail пользователю
            $content = ParseTemplateReturn('./phpshop/lib/templates/users/mail_pricemail.tpl', true);

            if (PHPShopSecurity::true_email($_POST['mail'])) {
                PHPShopObj::loadClass('mail');
                $PHPShopMail = new PHPShopMail($this->PHPShopSystem->getEmail(), $_POST['mail'], $title, $content);
            }

            $this->redirect();
        }
    }

    /**
     * Переход на описание товара
     */
    function redirect() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        header("Location: ../shop/UID_" . $this->PHPShopNav->getId() . ".html?pricemail=done");
    }

    /**
     * Прорисовка формы
     */
    function forma() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        $PHPShopProduct = new PHPShopProduct($this->PHPShopNav->getId());

        $this->set('productName', $PHPShopProduct->getName());
        $this->set('productImg', $PHPShopProduct->getImage());
        $this->set('productPrice', $PHPShopProduct->getPrice());
        $this->set('productUid', $this->PHPShopNav->getId());
        $this->set('productPriceRub', '');

        // Данные пользователя
        if (PHPShopSecurity::true_num($_SESSION['UsersId'])) {
            $PHPShopUsers = new PHPShopUser($_SESSION['UsersId']);
            $this->set('UserMail', $PHPShopUsers->getParam('mail'));
            $this->set('UserName', $PHPShopUsers->getParam('name'));
            $this->set('UserTel', $PHPShopUsers->getParam('tel'));
            $this->set('UserTelCode', $PHPShopUsers->getParam('tel_code'));
            $this->set('UserAdres', $PHPShopUsers->getParam('adres'));
            $this->set('UserComp', $PHPShopUsers->getParam('company'));
            $this->set('formaLock', 'readonly=1');
        } 
        // Запоминаем данные при неудачной отправке формы
        elseif(!empty($_POST['send_price_link'])) {
            $this->set('UserMail', $_POST['mail']);
            $this->set('UserName', $_POST['name_person']);
            $this->set('UserTel', $_POST['tel_name']);
            $this->set('UserTelCode', $_POST['tel_code']);
            $this->set('UserAdres', $_POST['adr_name']);
            $this->set('UserComp', $_POST['org_name']);
            $this->set('UserLink', $_POST['link_to_page']);
        }

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $PHPShopProduct, 'END');
        
        // Вставка данных в шаблон
        $this->ParseTemplate($this->getValue('templates.pricemail_forma'));
    }
}

?>