<?php

PHPShopObj::loadClass('order');
PHPShopObj::loadClass('mail');
$PHPShopOrder = new PHPShopOrderFunction();

/**
 * Обработчик записи заказа
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopDone
 * @version 1.3
 * @package PHPShopCore
 */
class PHPShopDone extends PHPShopCore {

    /**
     * Конструктор
     */
    function PHPShopDone() {
        global $PHPShopOrder;

        // Отладка
        $this->debug = false;

        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['orders'];

        // Список экшенов
        $this->action = array('nav' => 'index', "post" => 'send_to_order');
        parent::PHPShopCore();

        PHPShopObj::loadClass('cart');
        $this->PHPShopCart = new PHPShopCart();

        $this->PHPShopOrder = $PHPShopOrder;

        // Библиотека доставки
        if (PHPShopSecurity::true_num($_POST['d'])) {
            PHPShopObj::loadClass('delivery');
            $this->PHPShopDelivery = new PHPShopDelivery($_POST['d']);
        }

        // Библиотека платежных систем
        if (PHPShopSecurity::true_num($_POST['order_metod'])) {
            PHPShopObj::loadClass('payment');
            $this->PHPShopPayment = new PHPShopPayment($_POST['order_metod']);
        }
    }

    /**
     * Экшен по умочанию
     */
    function index() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;
		
        $this->set('mesageText', $this->message($this->lang('bad_cart_1'), $this->lang('bad_order_mesage_2')));
        $disp = ParseTemplateReturn($this->getValue('templates.order_forma_mesage'));
        $this->set('orderMesage', $disp);

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, false, 'END');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.order_forma_mesage_main'));
    }

    /**
     * Сообщение
     * @param string $title заголовок
     * @param string $content содержание
     * @return string
     */
    function message($title, $content) {

        // Перехват модуля
        $Arg = func_get_args();
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $Arg);
        if ($hook)
            return $hook;

        $message = PHPShopText::b(PHPShopText::notice($title, false, '14px')) . PHPShopText::br();
        $message.=PHPShopText::message($content, false, '12px', 'black');

        return $message;
    }

    /**
     * Экшен записи заказа
     */
    function send_to_order() {
        global $SysValue;

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST, 'START'))
            return true;

        if ($this->PHPShopCart->getNum() > 0) {
            if (PHPShopSecurity::true_param($_POST['mail'], $_POST['name_person'], $_POST['tel_name'])) { //, $_POST['adr_name']

                $this->ouid = $_POST['ouid'];

                $order_metod = PHPShopSecurity::TotalClean($_POST['order_metod'], 1);
                $PHPShopOrm = new PHPShopOrm($this->getValue('base.payment_systems'));
                $row = $PHPShopOrm->select(array('path'), array('id' => '=' . $order_metod, 'enabled' => "='1'"), false, array('limit' => 1));
                $path = $row['path'];

                // Поддержка старого API
                $LoadItems['System'] = $this->PHPShopSystem->getArray();

                $this->sum = $this->PHPShopCart->getSum(false);
                $this->num = $this->PHPShopCart->getNum();
                $this->weight = $this->PHPShopCart->getWeight();

                // Валюта
                $this->currency = $this->PHPShopOrder->default_valuta_code;

                // Стоимость доставки
                $this->delivery = $this->PHPShopDelivery->getPrice($this->PHPShopCart->getSum(false), $this->PHPShopCart->getWeight());

                // Скидка
                $this->discount = $this->PHPShopOrder->ChekDiscount($this->PHPShopCart->getSum());

                // Итого
                $this->total = $this->PHPShopOrder->returnSumma($this->sum, $this->discount) + $this->delivery;

                // Сообщения на e-mail
                $this->mail();

                // Перехат модуля в середине функции
                $this->setHook(__CLASS__, __FUNCTION__, $_POST, 'MIDDLE');

                // Подключение логики оплаты из файлов
                if (file_exists("./payment/$path/order.php"))
                    include_once("./payment/$path/order.php");
                elseif ($order_metod < 1000)
                    exit("Нет файла ./payment/$path/order.php");

                // Данные от способа оплаты
                if (!empty($disp))
                    $this->set('orderMesage', $disp);

                // Запись заказа в БД
                $this->write();

                // SMS администратору
                $this->sms();

                // Обнуление элемента корзины
                $PHPShopCartElement = new PHPShopCartElement(true);
                $PHPShopCartElement->init('miniCart');
				
				//$this->set('mesageText', $this->message('<span style="font-size:18px">'.$this->lang('good_order_mesage_1').'</span><BR>','<span style="font-size:14px"><b> На Вашу почту: '.$_POST['mail'].' отправлено письмо с подтверждением заказа № '.$_POST['ouid'].'</b><BR><BR> В ближайшее время с Вами свяжется наш менеджер для уточнения деталей заказа.</span>'));
				//$disp = ParseTemplateReturn($this->getValue('templates.order_forma_mesage'));
				//$this->set('orderMesage', $disp);
				
            }
            else {
                $this->set('mesageText', $this->message($this->lang('bad_order_mesage_1'), $this->lang('bad_order_mesage_2')));

                // Подключаем шаблон
                $disp = ParseTemplateReturn($this->getValue('templates.order_forma_mesage'));
                $disp.=PHPShopText::notice(PHPShopText::a('javascript:history.back(1)', $this->lang('order_return')), 'images/shop/icon-setup.gif');
                $this->set('orderMesage', $disp);
            }
        } else {

            $this->set('mesageText', $this->message($this->lang('bad_cart_1'), $this->lang('bad_order_mesage_2')));
            $disp = ParseTemplateReturn($this->getValue('templates.order_forma_mesage'));
            $this->set('orderMesage', $disp);
        }

        // Перехат модуля в конце функции
        $this->setHook(__CLASS__, __FUNCTION__, $_POST, 'END');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.order_forma_mesage_main'));
    }

    /**
     *  Сообщение об успешном заказе
     */
    function mail() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST, 'START'))
            return true;

        $this->set('cart', $this->PHPShopCart->display('mailcartforma', array('currency' => $this->currency)));
        $this->set('sum', $this->sum);
        $this->set('currency', $this->currency);
        $this->set('discount', $this->discount);
        $this->set('deliveryPrice', $this->delivery);
        $this->set('total', $this->total);
        $this->set('shop_name', $this->PHPShopSystem->getName());
        $this->set('ouid', $this->ouid);
        $this->set('date', date("d-m-y"));
        $this->set('name_person', $_POST['name_person']);
        $this->set('tel', @$_POST['tel_code'] . "-" . @$_POST['tel_name']);
        $this->set('adr_name', PHPShopSecurity::CleanStr(@$_POST['adr_name']));
        $this->set('dos_ot', @$_POST['dos_ot']);
        $this->set('dos_do', @$_POST['dos_do']);
        $this->set('deliveryCity', $this->PHPShopDelivery->getCity());
        $this->set('mail', $_POST['mail']);
        $this->set('payment', $this->PHPShopPayment->getName());
        $this->set('company', $this->PHPShopSystem->getParam('company'));
        $content = ParseTemplateReturn('./phpshop/lib/templates/order/usermail.tpl', true);

        // Заголовок письма покупателю
        $title = $this->PHPShopSystem->getName() . $this->lang('mail_title_user_start') . $_POST['ouid'] . $this->lang('mail_title_user_end');

        // Перехват модуля в середине функции
        if ($this->setHook(__CLASS__, __FUNCTION__, $content, 'MIDDLE'))
            return true;

        // Отсылаем письмо покупателю
        $PHPShopMail = new PHPShopMail($_POST['mail'], $this->PHPShopSystem->getParam('adminmail2'), $title, $content);

        $this->set('shop_admin', "http://" . $_SERVER['SERVER_NAME'] . $this->getValue('dir.dir') . "/phpshop/admpanel/");
        $this->set('time', date("d-m-y H:i a"));
        $this->set('ip', $_SERVER['REMOTE_ADDR']);
        $content_adm = ParseTemplateReturn('./phpshop/lib/templates/order/adminmail.tpl', true);

        // Заголовок письма администратору
        $title_adm = $this->PHPShopSystem->getName() . ' - ' . $this->lang('mail_title_adm') . $_POST['ouid'] . "/" . date("d-m-y");

        // Перехват модуля в конце функции
        if ($this->setHook(__CLASS__, __FUNCTION__, $content_adm, 'END'))
            return true;

        // Отсылаем письмо администратору -nah~
		if ($_COOKIE['sincity']=="sp")
			$PHPShopMail= new PHPShopMail("spb@prodacha.ru",$_POST['mail'],$title_adm,$content_adm);
		else
        	$PHPShopMail= new PHPShopMail($this->PHPShopSystem->getParam('adminmail2'),$_POST['mail'],$title_adm,$content_adm);
		
		
    }

    /**
     * SMS оповещение
     */
    function sms() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        if ($this->PHPShopSystem->ifSerilizeParam('admoption.sms_enabled')) {

            $msg = $this->lang('mail_title_adm') . $this->ouid . " - " . $this->sum . $this->currency;
            $phone = $this->getValue('sms.phone');

            include_once($this->getValue('file.sms'));
            SendSMS($msg, $phone);
        }
    }

    /**
     * Запись заказа в БД
     */
    function write() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST, 'START'))
            return true;

        // Данные покупателя
        $person = array(
            "ouid" => $this->ouid,
            "data" => date("U"),
            "time" => date("H:s a"),
            "mail" => $_POST['mail'],
            "name_person" => PHPShopSecurity::CleanStr(@$_POST['name_person']),
            "org_name" => PHPShopSecurity::CleanStr(@$_POST['org_name']),
            "org_inn" => PHPShopSecurity::CleanStr(@$_POST['org_inn']),
            "org_kpp" => PHPShopSecurity::CleanStr(@$_POST['org_kpp']),
            "tel_code" => PHPShopSecurity::CleanStr(@$_POST['tel_code']),
            "tel_name" => PHPShopSecurity::CleanStr(@$_POST['tel_name']),
            "adr_name" => PHPShopSecurity::CleanStr(@$_POST['adr_name']),
            "dostavka_metod" => @$_POST['dostavka_metod'],
            "discount" => $this->discount,
            "user_id" => $_SESSION['UsersId'],
            "dos_ot" => PHPShopSecurity::CleanStr(@$_POST['dos_ot']),
            "dos_do" => PHPShopSecurity::CleanStr(@$_POST['dos_do']),
            "order_metod" => @$_POST['order_metod']);

        // Данные по корзине
        $cart = array(
            "cart" => $this->PHPShopCart->getArray(),
            "num" => $this->num,
            "sum" => $this->sum,
            "weight" => $this->weight,
            "dostavka" => $this->delivery);

        // Статус заказа
        $this->status = array(
            "maneger" => "",
            "time" => "");

        // Серелиазованный массив заказа
        $this->order = serialize(array("Cart" => $cart, "Person" => $person));

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST, 'END'))
            return true;

        // Данные для записи
        $insert = $_POST;
        $insert['datas_new'] = time();
        $insert['uid_new'] = $this->ouid;
        $insert['orders_new'] = $this->order;
        $insert['status_new'] = serialize($this->status);
        $insert['user_new'] = $_SESSION['UsersId'];

        // Запись заказа в БД
        $result = $this->PHPShopOrm->insert($insert);

        // Проверка ошибок при записи заказа
        $this->error_report($result, array("Cart" => $cart, "Person" => $person, 'insert' => $insert));
        
        // Принудительная очистка корзины
        $this->PHPShopCart->clean();
    }

    /**
     * Отчет администратору об ошибке
     * @param mixed $result результат выполнения записи данных в БД
     * @param array $var массив данных
     * @return boolean 
     */
    function error_report($result, $var) {

        if (!is_bool($result)) {

            // Заголовок письма администратору
            $title = 'Ошибка записи заказа №' . $_POST['ouid'] . ' на ' . $this->PHPShopSystem->getName() . "/" . date("d-m-y");

            $content='Причина отказа записи: '.$result.'
Дамп:
';
            ob_start();
            print_r($var);
            $content.= ob_get_clean();

            // Перехват модуля в конце функции
            if ($this->setHook(__CLASS__, __FUNCTION__, $content))
                return true;

            // Отсылаем письмо с ошибкой администратору
            new PHPShopMail($this->PHPShopSystem->getParam('adminmail2'), $_POST['mail'], $title, $content);
        }
    }

}

/**
 * Шаблон вывода таблицы корзины
 */
function mailcartforma($val, $option) {
    global $PHPShopModules;

    // Перехват модуля
    $hook = $PHPShopModules->setHookHandler(__FUNCTION__, __FUNCTION__, $val, $option);
    if ($hook)
        return $hook;

    $dis =
            $val['uid'] . "  " . $val['name'] . " (" . $val['num'] . " " . $val['ed_izm'] . " * " . $val['price'] . ") -- " . ($val['price'] * $val['num']) . " " . $option['currency'] . "
";
    return $dis;
}

?>