<?php

PHPShopObj::loadClass('order');
$PHPShopOrder = new PHPShopOrderFunction();

/**
 * Обработчик оформления заказа
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopOrder
 * @version 1.1
 * @package PHPShopCore
 */
class PHPShopOrder extends PHPShopCore {

    var $format;

    /**
     * Конструктор
     */
    function PHPShopOrder() {

        // Отладка
        $this->debug = false;

        //Хранение методов оплаты
        $this->value;

        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['orders'];

        // Список экшенов
        $this->action = array("post" => array('id_edit', 'id_delete'), "get" => "cart", 'nav' => 'index');

        parent::PHPShopCore();

        // Кол-во знаков в постфиксе заказа №_XX, по умолчанию 2
        $format = $this->getValue('my.order_prefix_format');
        if (!empty($format))
            $this->format = $format;
        else
            $this->format = 2;

        PHPShopObj::loadClass('cart');
        $this->PHPShopCart = new PHPShopCart();
    }

    /**
     * Экшен по умолчанию
     */
    function index() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        // Импорт данных
        $this->import();

        // Отключение lastmodified
        $this->SysValue['cache']['last_modified'] = false;

        // Title
        $this->title = $this->lang('order_title') . ' - ' . $this->PHPShopSystem->getValue("title");

        // Если есть корзина товаров
        if ($this->PHPShopCart->getNum() > 0)
            $this->order();
        else
            $this->error();

        $PHPShopCartElement = new PHPShopCartElement(true);
        $PHPShopCartElement->init('miniCart');
        $this->set('productValutaName', $this->PHPShopSystem->getDefaultValutaCode(true));
    }

    /**
     * Экшен очистки корзины
     */
    function cart() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST))
            return true;

        $this->PHPShopCart->clean();
        $this->index();
    }

    /**
     * Экшен удаление товара в заказе
     */
    function id_delete() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST))
            return true;

        $this->PHPShopCart->del($_POST['id_delete']);
        $this->index();
    }

    /**
     * Экшен редактирования товара в заказе
     */
    function id_edit() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST))
            return true;
			$num_new_part='num_new'.$_POST['id_edit'];
		//echo 'отправлено увеличение стоимости товара xid'.$_POST['id_edit'].'num'.$_POST["$num_new_part"];
        if (PHPShopSecurity::true_num($_POST['id_edit'])==1)
            $this->PHPShopCart->edit($_POST['id_edit'], $_POST[$num_new_part]);
        $this->index();
    }

    /**
     * Список товаров в заказе
     * @return string
     */
    function product() {
        global $PHPShopOrder;

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, false, 'START');
        if ($hook)
            return $hook;

        $this->set('currency', $PHPShopOrder->default_valuta_code);
        $cart = $this->PHPShopCart->display('ordercartforma');
        $this->set('display_cart', $cart);
        $this->set('cart_num', $this->PHPShopCart->getNum());
        $this->set('cart_sum', $this->PHPShopCart->getSum(false));
        $this->set('discount', $PHPShopOrder->ChekDiscount($this->PHPShopCart->getSum()));
        $this->set('cart_weight', $this->PHPShopCart->getWeight());

        // Стоимость доставки
        PHPShopObj::loadClass('delivery');
        $this->set('delivery_price', PHPShopDelivery::getPriceDefault());

        // Итоговая стоимость
        $this->set('total', $PHPShopOrder->returnSumma($this->get('cart_sum'), $this->get('discount')) + $this->get('delivery_price'));

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, false, 'END');
        
        return ParseTemplateReturn('phpshop/lib/templates/order/cart.tpl', true);
    }

    /**
     * Вывод списка доставки
     * Функция вынесена в отдельный файл /order.core/delivery.php
     * @return mixed
     */
    function delivery() {

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__);
        if ($hook)
            return $hook;

        return $this->doLoadFunction(__CLASS__, __FUNCTION__,@$_GET['d']);
    }

    /**
     * Сообщение об ошибке, пустая корзина
     */
    function error() {
        $message = $this->message($this->lang('bad_cart_1'), $this->lang('bad_order_mesage_2'));
        $message.="<script language='JavaScript'>
					document.getElementById('num').innerHTML = '0';
					document.getElementById('sum').innerHTML = '0';
					document.getElementById('order').style.display = 'none';
				   </script>";
        $this->set('mesageText', $message);
        $this->set('orderMesage', ParseTemplateReturn($this->getValue('templates.order_forma_mesage')));

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__);

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
        $message = PHPShopText::b(PHPShopText::notice($title, false, '14px')) . PHPShopText::br();
        $message.=PHPShopText::message($content, false, '12px', 'black');
        return $message;
    }

    /**
     * Выбор способа оплаты
     */
    function payment() {
        PHPShopObj::loadClass('payment');
        $PHPShopPayment = new PHPShopPaymentArray();
        $Payment = $PHPShopPayment->getArray();
        if (is_array($Payment))
            foreach ($Payment as $val)
                if (!empty($val['enabled']))
                    $this->value[] = array($val['name'], $val['id'], false);

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $this->value);
        if ($hook)
            return $hook;

        $this->set('orderOplata', PHPShopText::select('order_metod', $this->value, 250));
    }

    /**
     * Форма заказа
     */
    function order() {
        
        // Шаблон формы заказа личных данных
        $this->template_order_forma=$this->getValue('templates.main_order_forma');
        
        // Шаблон страницы оформления заказа
        $this->template_order_list=$this->getValue('templates.main_order_list');

        // Перехват модуля в начале функции
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        // Номер заказа
        $this->setNum();

        // Корзина
        $this->set('orderContentCart', $this->product());
        $this->set('orderNum', $this->order_num);
        $this->set('orderDate', date("d-m-y"));
        
        $this->set('cart_tk_delivery_pass_msg',$this->SysValue['lang']['cart_tk_delivery_pass_msg']);
        
		//Для покупки в кредит  nah~
		$credititems="";	
		$creditdisabled="";
		$allcash=0;	
		$allcount=0;
		$bicprice=0;
		
		foreach ($_SESSION['cart'] as $key=> $array)
		{
				$bicid=$array['id'];
				$bicname=$array['name'];
				$biccount=$array['num'];
				
				if ( ($_COOKIE['sincity']=="sp") AND ($array['price2']!=0) ) {
					$bicprice=$array['price2'];
				} else if( ($_COOKIE['sincity']=="chb") AND ($array['price3']!=0) ) {
					$bicprice=$array['price3'];
				}
				else {
					$bicprice=$array['price'];
				}
				//готовим данные для кредита
				//$credititems.='{MODEL: "'.$bicname.'", COUNT:"'.$biccount.'", PRICE:"'.$bicprice*$biccount.'"},';
				$cnt=0;
				//if ($biccount>1) {
				//	while ($cnt<$biccount)	{
				//		$credititems.='{"id":'.$bicid.',"title":"'.$bicname.'","amount":'.$bicprice.'"count":1'.',"info":"'.$bicname.'"},';
				//		$cnt++;
				//	}			
				//} else
				$credititems.='{"id":'.$bicid.',"title":"'.$bicname.'","amount":'.$bicprice.',"count":'.$biccount.',"info":"'.$bicname.'"},';
				
				$allcount+=$biccount;
				$allcash+=$bicprice*$biccount;
			
		}
		$credititems.='{"id":'.$bicid.',"title":"Комиссия сервиса \'Все в кредит\' - 5%","amount":'.number_format(($allcash*0.05), 2, '.','').',"count":1,"info":"Комиссия сервиса \'Все в кредит\' - 5%"},';
		
		$credititems=substr($credititems,0,-1);
                //$vsevkredit="<div class='vkredit-button'><img src='http://vkredit24.ru/vk24-www/images/white-logo.png' /></div>";
		//$vsevkredit='<div id="VVC_BUTTON_STYLE1_COLORGREEN_ROUND_'.($allcount+1).'_'.$bicid.'" style="display:none;">';
		//if ($allcount>1) {
			$start_square='[';
			$end_square=']';
		//} else {
		//	$start_square='';
		//	$end_square='';			
		//}
		
		$vsevkredit.=$start_square.$credititems.$end_square;

		$vsevkredit=htmlentities($vsevkredit,ENT_COMPAT|ENT_HTML401,'cp1251');

		if ($allcash<2000) $creditdisabled=" disabled ";
		
		$this->set('creditdisabled',$creditdisabled);
			
		$this->set('credititems',$vsevkredit);

        $this->set('address_and_info_text','Дополнительная<br />информация:');

        // Доставка
        $this->delivery();

        // Поддержка импортируемых данных
        $this->payment();
        
        // Данные пользователя из личного кабинета
        if (!empty($_SESSION['UsersId']) and PHPShopSecurity::true_num($_SESSION['UsersId'])) {
            $PHPShopUser = new PHPShopUser($_SESSION['UsersId']);
            $this->set('UserMail', $PHPShopUser->getValue('mail'));
            $this->set('UserName', $PHPShopUser->getValue('name'));
            $this->set('UserTel', $PHPShopUser->getValue('tel'));
            $this->set('UserTelCode', $PHPShopUser->getValue('tel_code'));
            $this->set('UserAdres', $PHPShopUser->getValue('adres'));
            $this->set('UserComp', $PHPShopUser->getValue('company'));
            $this->set('UserInn', $PHPShopUser->getValue('inn'));
            $this->set('UserKpp', $PHPShopUser->getValue('kpp'));
            $this->set('formaLock', 'readonly=1');
            $this->set('ComStartReg', PHPShopText::comment());
            $this->set('ComEndReg', PHPShopText::comment('>'));
        } else {
        	$this->set('UserName',$_POST['name_person']);
        	$this->set('UserTel',$_POST['tel_name']);
        }
        

        // Форма личной информации по заказу
        $cart_min = $this->PHPShopSystem->getSerilizeParam('admoption.cart_minimum');
        if ($cart_min <= $this->PHPShopCart->getSum(false)) {
            $this->set('orderContent', parseTemplateReturn($this->template_order_forma));
        } else {
            $this->set('orderContent', $this->message($this->lang('cart_minimum') . ' ' . $cart_min, $this->lang('bad_order_mesage_2')));
        }

        // Перехват модуля в конце функции
        $this->setHook(__CLASS__, __FUNCTION__, false, 'END');

        // Подключаем шаблон страницы заказа
        $this->parseTemplate($this->template_order_list);
    }

    /**
     * Генерация номера заказа
     */
    function setNum() {
        $row = $this->PHPShopOrm->select(array('uid'), false, array('order' => 'id desc'), array('limit' => 1));
        $last = $row['uid'];
        $all_num = explode("-", $last);
        $ferst_num = $all_num[0];
        $order_num = $ferst_num + 1;
        $this->order_num = $order_num . "-" . substr(abs(crc32(uniqid(session_id()))), 0, $this->format);

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $row);
    }

    /**
     * Импорт данных
     * Функция вынесена в отдельный файл /order.core/import.php
     */
    function import() {

        $this->doLoadFunction(__CLASS__, __FUNCTION__, @$_GET['from']);

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $_GET);
    }
}

/**
 * Шаблон вывода таблицы корзины
 * Основной шаблон печатной формы расположен в phpshop/lib/templates/cart/product.tpl
 */
PHPShopObj::loadClass('parser');

function ordercartforma($val, $option) {
    global $PHPShopModules;

    // Перехват модуля в начале функции
    $hook = $PHPShopModules->setHookHandler(__FUNCTION__, __FUNCTION__,array(&$val), $option, 'START');
    if ($hook)
        return $hook;

    // Проверка подтипа товара, выдача ссылки главного товара
    if (empty($val['parent']))
        PHPShopParser::set('cart_id', $val['id']);
    else
        PHPShopParser::set('cart_id', $val['parent']);
    
	//nah
	
	if ( ($_COOKIE['sincity']=="sp") AND ($val['price2']!=0) ) {
		$realprice=$val['price2'];
	} else if( ($_COOKIE['sincity']=="chb") AND ($val['price3']!=0) ) {
		$realprice=$val['price3'];
	}
	else {
		$realprice=$val['price'];
	}
	
    PHPShopParser::set('cart_xid',$option['xid']);
    PHPShopParser::set('cart_name',$val['name']);
    PHPShopParser::set('cart_num',$val['num']);
    PHPShopParser::set('cart_price',$realprice);
    PHPShopParser::set('cart_izm',$val['ed_izm']);

    // Перехват модуля в конце функции
    $PHPShopModules->setHookHandler(__FUNCTION__, __FUNCTION__, $val, $option, 'END');

    return ParseTemplateReturn('./phpshop/lib/templates/order/product.tpl', true);
}

?>