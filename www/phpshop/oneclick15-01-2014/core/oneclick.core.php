<?php

class PHPShopOneclick extends PHPShopCore {

    /**
     * Конструктор
     */
    function PHPShopOneclick() {

        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['oneclick']['oneclick_jurnal'];

        // Отладка
        $this->debug = false;

        // Настройка
        $this->system();

        // Список экшенов
        $this->action = array(
            'post' => 'oneclick_mod_product_id', 
            'name'=>'done',
            'nav'=>'index'
            );
        parent::PHPShopCore();

        // Мета
        $this->title = $this->system['title'] . " - " . $this->PHPShopSystem->getValue("name");
    }

    /**
     * Настройка
     */
    function system() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['oneclick']['oneclick_system']);
        $this->system = $PHPShopOrm->select();
    }
    
    /**
     * Сообщение об удачной заявке
     */
    function done(){
        $message=$this->system['title_end'];
        if(empty($message)) $message=$GLOBALS['SysValue']['lang']['oneclick_done'];
        $this->set('pageTitle',$this->system['title']);
        $this->set('pageContent',$message);
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * Экшен по умолчанию, вывод формы звонка
     */
    function index($message=false) {
        
        return $this->setError404();
            
    }

    /**
     * Экшен записи при получении $_POST[returncall_mod_send]
     */
    function oneclick_mod_product_id() {
        if (PHPShopSecurity::true_param($_POST['oneclick_mod_name'], $_POST['oneclick_mod_tel'])) {
            $this->write();
            header('Location: ./done.html');
            exit();
        } else {
            $message= $GLOBALS['SysValue']['lang']['oneclick_error'];
        }
        $this->index($message);
    }

    /**
     * Запись в базу
     */
    function write() {
        
        $PHPShopProduct = new PHPShopProduct(intval($_POST['oneclick_mod_product_id']));

        // Подключаем библиотеку отправки почты
        PHPShopObj::loadClass("mail");
        $insert=array();
        $insert['name_new'] = PHPShopSecurity::TotalClean($_POST['oneclick_mod_name'], 2);
        $insert['tel_new'] = PHPShopSecurity::TotalClean($_POST['oneclick_mod_tel'], 2);
        $insert['date_new'] = time();
        $insert['message_new'] = PHPShopSecurity::TotalClean($_POST['oneclick_mod_message'], 2);
        $insert['ip_new'] = $_SERVER['REMOTE_ADDR'];
        $insert['product_name_new'] = $PHPShopProduct->getName();
        $insert['product_id_new'] = intval($_POST['oneclick_mod_product_id']);
        $insert['product_price_new'] = PHPShopProductFunction::GetPriceValuta(intval($_POST['oneclick_mod_product_id']),$PHPShopProduct->getPrice(),$PHPShopProduct->getParam('baseinputvaluta'),true).' '.$this->PHPShopSystem->getDefaultValutaCode();

        // Запись в базу
        $this->PHPShopOrm->insert($insert);

        $zag = $this->PHPShopSystem->getValue('name') . " - Быстрый заказ - " . PHPShopDate::dataV($date);
        $message = "
Доброго времени!
---------------

С сайта " . $this->PHPShopSystem->getValue('name') . " пришел быстрый заказ

Данные о пользователе:
----------------------

Имя:                " . $insert['name_new'] . "
Телефон:            " . $insert['tel_new'] . "
Товар:              ".$insert['product_name_new']." / ID ".$insert['product_id_new']." / ".$insert['product_price_new']."
Сообщение:          " . $insert['message_new'] . "
Дата:               " . PHPShopDate::dataV($insert['date_new']) . "
IP:                 " . $_SERVER['REMOTE_ADDR'] . "

---------------

С уважением,
http://" . $_SERVER['SERVER_NAME'];

        new PHPShopMail($this->PHPShopSystem->getValue('adminmail2'), $this->PHPShopSystem->getValue('adminmail2'), $zag, $message);
    }

}

?>