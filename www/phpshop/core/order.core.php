<?php

PHPShopObj::loadClass('order');
$PHPShopOrder = new PHPShopOrderFunction();

/**
 * ���������� ���������� ������
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopOrder
 * @version 1.1
 * @package PHPShopCore
 */
class PHPShopOrder extends PHPShopCore {

    var $format;

    /**
     * �����������
     */
    function PHPShopOrder() {

        // �������
        $this->debug = false;

        //�������� ������� ������
        $this->value;

        // ��� ��
        $this->objBase = $GLOBALS['SysValue']['base']['orders'];

        // ������ �������
        $this->action = array("post" => array('id_edit', 'id_delete'), "get" => "cart", 'nav' => 'index');

        parent::PHPShopCore();

        // ���-�� ������ � ��������� ������ �_XX, �� ��������� 2
        $format = $this->getValue('my.order_prefix_format');
        if (!empty($format))
            $this->format = $format;
        else
            $this->format = 2;

        PHPShopObj::loadClass('cart');
        $this->PHPShopCart = new PHPShopCart();
    }

    /**
     * ����� �� ���������
     */
    function index() {

        // �������� ������
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        // ������ ������
        $this->import();

        // ���������� lastmodified
        $this->SysValue['cache']['last_modified'] = false;

        // Title
        $this->title = $this->lang('order_title') . ' - ' . $this->PHPShopSystem->getValue("title");

        // ���� ���� ������� �������
        if ($this->PHPShopCart->getNum() > 0)
            $this->order();
        else
            $this->error();

        $PHPShopCartElement = new PHPShopCartElement(true);
        $PHPShopCartElement->init('miniCart');
        $this->set('productValutaName', $this->PHPShopSystem->getDefaultValutaCode(true));
    }

    /**
     * ����� ������� �������
     */
    function cart() {

        // �������� ������
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST))
            return true;

        $this->PHPShopCart->clean();
        $this->index();
    }

    /**
     * ����� �������� ������ � ������
     */
    function id_delete() {

        // �������� ������
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST))
            return true;

        $this->PHPShopCart->del($_POST['id_delete']);
        $this->index();
    }

    /**
     * ����� �������������� ������ � ������
     */
    function id_edit() {

        // �������� ������
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST))
            return true;
			$num_new_part='num_new'.$_POST['id_edit'];
		//echo '���������� ���������� ��������� ������ xid'.$_POST['id_edit'].'num'.$_POST["$num_new_part"];
        if (PHPShopSecurity::true_num($_POST['id_edit'])==1)
            $this->PHPShopCart->edit($_POST['id_edit'], $_POST[$num_new_part]);
        $this->index();
    }

    /**
     * ������ ������� � ������
     * @return string
     */
    function product() {
        global $PHPShopOrder;

        // �������� ������
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

        // ��������� ��������
        PHPShopObj::loadClass('delivery');
        $this->set('delivery_price', PHPShopDelivery::getPriceDefault());

        // �������� ���������
        $this->set('total', $PHPShopOrder->returnSumma($this->get('cart_sum'), $this->get('discount')) + $this->get('delivery_price'));

        // �������� ������
        $this->setHook(__CLASS__, __FUNCTION__, false, 'END');
        
        return ParseTemplateReturn('phpshop/lib/templates/order/cart.tpl', true);
    }

    /**
     * ����� ������ ��������
     * ������� �������� � ��������� ���� /order.core/delivery.php
     * @return mixed
     */
    function delivery() {

        // �������� ������
        $hook = $this->setHook(__CLASS__, __FUNCTION__);
        if ($hook)
            return $hook;

        return $this->doLoadFunction(__CLASS__, __FUNCTION__,@$_GET['d']);
    }

    /**
     * ��������� �� ������, ������ �������
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

        // �������� ������
        $this->setHook(__CLASS__, __FUNCTION__);

        // ���������� ������
        $this->parseTemplate($this->getValue('templates.order_forma_mesage_main'));
    }

    /**
     * ���������
     * @param string $title ���������
     * @param string $content ����������
     * @return string
     */
    function message($title, $content) {
        $message = PHPShopText::b(PHPShopText::notice($title, false, '14px')) . PHPShopText::br();
        $message.=PHPShopText::message($content, false, '12px', 'black');
        return $message;
    }

    /**
     * ����� ������� ������
     */
    function payment() {
        PHPShopObj::loadClass('payment');
        $PHPShopPayment = new PHPShopPaymentArray();
        $Payment = $PHPShopPayment->getArray();
        if (is_array($Payment))
            foreach ($Payment as $val)
                if (!empty($val['enabled']))
                    $this->value[] = array($val['name'], $val['id'], false);

        // �������� ������
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $this->value);
        if ($hook)
            return $hook;

        $this->set('orderOplata', PHPShopText::select('order_metod', $this->value, 250));
    }

    /**
     * ����� ������
     */
    function order() {
        
        // ������ ����� ������ ������ ������
        $this->template_order_forma=$this->getValue('templates.main_order_forma');
        
        // ������ �������� ���������� ������
        $this->template_order_list=$this->getValue('templates.main_order_list');

        // �������� ������ � ������ �������
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        // ����� ������
        $this->setNum();

        // �������
        $this->set('orderContentCart', $this->product());
        $this->set('orderNum', $this->order_num);
        $this->set('orderDate', date("d-m-y"));
        
        $this->set('cart_tk_delivery_pass_msg',$this->SysValue['lang']['cart_tk_delivery_pass_msg']);
        
		//��� ������� � ������  nah~
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
				//������� ������ ��� �������
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
		$credititems.='{"id":'.$bicid.',"title":"�������� ������� \'��� � ������\' - 5%","amount":'.number_format(($allcash*0.05), 2, '.','').',"count":1,"info":"�������� ������� \'��� � ������\' - 5%"},';
		
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

        $this->set('address_and_info_text','��������������<br />����������:');

        // ��������
        $this->delivery();

        // ��������� ������������� ������
        $this->payment();
        
        // ������ ������������ �� ������� ��������
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
        

        // ����� ������ ���������� �� ������
        $cart_min = $this->PHPShopSystem->getSerilizeParam('admoption.cart_minimum');
        if ($cart_min <= $this->PHPShopCart->getSum(false)) {
            $this->set('orderContent', parseTemplateReturn($this->template_order_forma));
        } else {
            $this->set('orderContent', $this->message($this->lang('cart_minimum') . ' ' . $cart_min, $this->lang('bad_order_mesage_2')));
        }

        // �������� ������ � ����� �������
        $this->setHook(__CLASS__, __FUNCTION__, false, 'END');

        // ���������� ������ �������� ������
        $this->parseTemplate($this->template_order_list);
    }

    /**
     * ��������� ������ ������
     */
    function setNum() {
        $row = $this->PHPShopOrm->select(array('uid'), false, array('order' => 'id desc'), array('limit' => 1));
        $last = $row['uid'];
        $all_num = explode("-", $last);
        $ferst_num = $all_num[0];
        $order_num = $ferst_num + 1;
        $this->order_num = $order_num . "-" . substr(abs(crc32(uniqid(session_id()))), 0, $this->format);

        // �������� ������
        $this->setHook(__CLASS__, __FUNCTION__, $row);
    }

    /**
     * ������ ������
     * ������� �������� � ��������� ���� /order.core/import.php
     */
    function import() {

        $this->doLoadFunction(__CLASS__, __FUNCTION__, @$_GET['from']);

        // �������� ������
        $this->setHook(__CLASS__, __FUNCTION__, $_GET);
    }
}

/**
 * ������ ������ ������� �������
 * �������� ������ �������� ����� ���������� � phpshop/lib/templates/cart/product.tpl
 */
PHPShopObj::loadClass('parser');

function ordercartforma($val, $option) {
    global $PHPShopModules;

    // �������� ������ � ������ �������
    $hook = $PHPShopModules->setHookHandler(__FUNCTION__, __FUNCTION__,array(&$val), $option, 'START');
    if ($hook)
        return $hook;

    // �������� ������� ������, ������ ������ �������� ������
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

    // �������� ������ � ����� �������
    $PHPShopModules->setHookHandler(__FUNCTION__, __FUNCTION__, $val, $option, 'END');

    return ParseTemplateReturn('./phpshop/lib/templates/order/product.tpl', true);
}

?>