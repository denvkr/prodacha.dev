<?php

PHPShopObj::loadClass('order');
PHPShopObj::loadClass('mail');
$PHPShopOrder = new PHPShopOrderFunction();

/**
 * ���������� ������ ������
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopDone
 * @version 1.3
 * @package PHPShopCore
 */
class PHPShopDone extends PHPShopCore {

    /**
     * �����������
     */
    function PHPShopDone() {
        global $PHPShopOrder;

        $this->memory=true;
        
        // �������
        $this->debug = false;

        // ��� ��
        $this->objBase = $GLOBALS['SysValue']['base']['orders'];

        // ������ �������
        $this->action = array('nav' => 'index', "post" => 'send_to_order');
        parent::PHPShopCore();

        PHPShopObj::loadClass('cart');
        $this->PHPShopCart = new PHPShopCart();

        $this->PHPShopOrder = $PHPShopOrder;

        // ���������� ��������
        if (PHPShopSecurity::true_num($_POST['d'])) {
            PHPShopObj::loadClass('delivery');
            $this->PHPShopDelivery = new PHPShopDelivery($_POST['d']);
        }

        // ���������� ��������� ������
        if (PHPShopSecurity::true_num($_POST['order_metod'])) {
            PHPShopObj::loadClass('payment');
            $this->PHPShopPayment = new PHPShopPayment($_POST['order_metod']);
        }
        //������� �� ����������� ������ +,()-
        $this->tel_num_repl = array("+", "(", ")", "-", " ");
        
    }

    /**
     * ����� �� ��������
     */
    function index() {

        // �������� ������
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
    		return true;
		
        $this->set('mesageText', $this->message($this->lang('bad_cart_1'), $this->lang('bad_order_mesage_2')));

        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'MIDDLE'))
        	return true;
        
        $disp = ParseTemplateReturn($this->getValue('templates.order_forma_mesage'));
        $this->set('orderMesage', $disp);

        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'END'))
        	return true;
        
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

        // �������� ������
        $Arg = func_get_args();
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $Arg);
        if ($hook)
            return $hook;

        $message = PHPShopText::b(PHPShopText::notice($title, false, '14px')) . PHPShopText::br();
        $message.=PHPShopText::message($content, false, '12px', 'black');

        return $message;
    }

    /**
     * ����� ������ ������
     */
    function send_to_order() {
        global $SysValue;
        
        // �������� ������
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST, 'START'))
            return true;

        if ($this->PHPShopCart->getNum() > 0) {
            if (PHPShopSecurity::true_param(@$_POST['mail'], @$_POST['name_person'], @$_POST['tel_name'])) { //, $_POST['adr_name']

                $this->ouid = @$_POST['ouid'];

                $order_metod = PHPShopSecurity::TotalClean(@$_POST['order_metod'], 1);
                
                $bic_code=@$_POST['bic_code'];

                $PHPShopOrm = new PHPShopOrm($this->getValue('base.payment_systems'));
                $row = $PHPShopOrm->select(array('path'), array('id' => '=' . $order_metod, 'enabled' => "='1'"), false, array('limit' => 1));

                $path = $row['path'];

                // ��������� ������� API
                $LoadItems['System'] = $this->PHPShopSystem->getArray();

                $this->sum = $this->PHPShopCart->getSum(false);
                $this->num = $this->PHPShopCart->getNum();
                $this->weight = $this->PHPShopCart->getWeight();

                // ������
                $this->currency = $this->PHPShopOrder->default_valuta_code;
                //echo 'before delivery cart_sum'.$this->PHPShopCart->getSum(false).'weigth'.$this->PHPShopCart->getWeight().'delivery';
				//print_r($_POST['d']);
				//echo '1';
                // ��������� ��������
                $this->delivery = $this->PHPShopDelivery->getPrice($this->PHPShopCart->getSum(false), $this->PHPShopCart->getWeight());
                //echo 'before discount';
                // ������
                $this->discount = $this->PHPShopOrder->ChekDiscount($this->PHPShopCart->getSum());
                //echo 'before total';
                // �����
                $this->total = $this->PHPShopOrder->returnSumma($this->sum, $this->discount) + $this->delivery;

                // ��������� �� e-mail
                $this->mail();

                // ������� ������ � �������� �������
                $this->setHook(__CLASS__, __FUNCTION__, $_POST, 'MIDDLE');

                // ����������� ������ ������ �� ������
                if (file_exists("./payment/$path/order.php"))
                    include_once("./payment/$path/order.php");
                elseif ($order_metod < 1000)
					exit("��� ����� ./payment/$path/order.php");

                $this->set('orderNum',$this->ouid);

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
                	$this->set('UserName',@$_POST['name_person']);
                	$this->set('UserTel',@str_replace($this->tel_num_repl, "", $_POST['tel_name']));
                }
                
                // ������ �� ������� ������
                if (!empty($disp))
                    $this->set('orderMesage', $disp);
                	 
                // ������ ������ � ��
                $this->write();

                // SMS ��������������
                $this->sms();
                //$this->set('mesageText', $this->message('<span style="font-size:18px">'.$this->lang('good_order_mesage_1').'</span><BR>','<span style="font-size:14px"><b> �� ���� �����: '.$_POST['mail'].' ���������� ������ � �������������� ������ � '.$_POST['ouid'].'</b><BR><BR> � ��������� ����� � ���� �������� ��� �������� ��� ��������� ������� ������.</span>'));
                //$disp = ParseTemplateReturn($this->getValue('templates.order_forma_mesage'));
                //$this->set('orderMesage', $disp);
                //if ($order_metod==25) {
                //$sql="select message,message_header  from ".$SysValue['base']['table_name48']." where id=".$order_metod;
                //$result=mysql_query(@$sql);
                //$row = mysql_fetch_array(@$result);
                 
                //$message=$row['message'];
                //$message_header=$row['message_header'];
                
                //$message.="<div class='bictext'>��� ����� ������� ������! ��� ���������� �������, ����������, ��������� ������".$bic_code." �����</a></div>"; //$_POST['bic_code']
                //$SysValue['other']['mesageText']= "<font style='font-size:14px;color:red'><b>".$message_header."</b></font><br>".$message;
                
                //$this->set('mesageText',$message);
                //echo $message;
                //}
               // $this->parseTemplate($this->getValue('templates.order_forma_mesage'));
                
                // ��������� �������� �������
                $PHPShopCartElement = new PHPShopCartElement(true);
                $PHPShopCartElement->init('miniCart');
				
                //return 1;
            }
            else {
                $this->set('mesageText', $this->message($this->lang('bad_order_mesage_1'), $this->lang('bad_order_mesage_2')));

                // ���������� ������
                $disp = ParseTemplateReturn($this->getValue('templates.order_forma_mesage'));
                $disp.=PHPShopText::notice(PHPShopText::a('javascript:history.back(1)', $this->lang('order_return')), 'images/shop/icon-setup.gif');
                $this->set('orderMesage', $disp);
            }
        } else {

            $this->set('mesageText', $this->message($this->lang('bad_cart_1'), $this->lang('bad_order_mesage_2')));
            $disp = ParseTemplateReturn($this->getValue('templates.order_forma_mesage'));
            $this->set('orderMesage', $disp);
        }

        // ������� ������ � ����� �������
        $this->setHook(__CLASS__, __FUNCTION__, $_POST, 'END');

        // ���������� ������
        $this->parseTemplate($this->getValue('templates.order_forma_mesage_main'));
    }

    /**
     *  ��������� �� �������� ������
     */
    function mail() {

        // �������� ������
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST, 'START'))
            return true;
		$dop_info='';
		$tk='';
        if (isset($_POST['firstname']) &&
            isset($_POST['middlename']) &&
            isset($_POST['lastname']) &&
            isset($_POST['pass_no1']) &&
            isset($_POST['pass_no2']) &&
            isset($_POST['pass_police'])) {
            if($_POST['tk_other']!=''){
                $tk=@str_replace("_", "",$_POST['tk_other']);
            } else {
                $tk=@str_replace("_", "",$_POST['tk_list_item']);		
            }
            if($_POST['delivery_address']!=''){
                $da=@str_replace("_", "",$_POST['delivery_address']);
            } else {
                $da=@str_replace("_", "",$_POST['tk_delivery_item']);		
            }            
            $dop_info="������������ ��������:".$tk.
               " ���:".@str_replace("_", "",$_POST['firstname']).
               " ��������:".@str_replace("_", "",$_POST['middlename']).
               " �������:".@str_replace("_", "",$_POST['lastname']).
               " �����:".@str_replace("_", "",$_POST['pass_no1'].
               " �����:".$_POST['pass_no2']).
               " ���� ������:".@str_replace("_", "",$_POST['pass_police']).
               " ������� ���������������:".@str_replace($this->tel_num_repl, "",$_POST['tel2']).
               " ����� ��������:".@str_replace("_", "",$_POST['delivery_city']).
               " ����� ��������:".$da;
		}
        if (isset($_POST['firstname']) &&
            isset($_POST['middlename']) &&
            isset($_POST['lastname']) &&
            isset($_POST['postal_index']) &&
            isset($_POST['delivery_address'])) {

            $dop_info="������������ ��������: ����� ������".
               " ���:".@str_replace("_", "",$_POST['firstname']).
               " ��������:".@str_replace("_", "",$_POST['middlename']).
               " �������:".@str_replace("_", "",$_POST['lastname']).
               " ������� ���������������:".@str_replace($this->tel_num_repl, "",$_POST['tel2']).
               " ����� ��������:".@str_replace("_", "",$_POST['delivery_city']).
               " ������:".@str_replace("_", "",$_POST['postal_index']).
               " ����� ��������:".@str_replace("_", "",$_POST['delivery_address']);
		}		
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
        $this->set('tel', @$_POST['tel_code'] . "-" . @str_replace($this->tel_num_repl, "", $_POST['tel_name']));
        $this->set('adr_name', PHPShopSecurity::CleanStr(@$_POST['adr_name']).$dop_info);
        $this->set('dos_ot', @$_POST['dos_ot']);
        $this->set('dos_do', @$_POST['dos_do']);
        $this->set('deliveryCity', $this->PHPShopDelivery->getCity());
        $this->set('mail', $_POST['mail']);
        $this->set('payment', $this->PHPShopPayment->getName());
        $this->set('company', $this->PHPShopSystem->getParam('company'));
        $content = ParseTemplateReturn('./phpshop/lib/templates/order/usermail.tpl', true);

        // ��������� ������ ����������
        $title = $this->PHPShopSystem->getName() . $this->lang('mail_title_user_start') . $_POST['ouid'] . $this->lang('mail_title_user_end');

        // �������� ������ � �������� �������
        if ($this->setHook(__CLASS__, __FUNCTION__, $content, 'MIDDLE'))
            return true;

        // �������� ������ ����������
        $PHPShopMail = new PHPShopMail($_POST['mail'], $this->PHPShopSystem->getParam('adminmail2'), $title, $content);

        $this->set('shop_admin', "http://" . $_SERVER['SERVER_NAME'] . $this->getValue('dir.dir') . "/phpshop/admpanel/");
        $this->set('time', date("d-m-y H:i a"));
        $this->set('ip', $_SERVER['REMOTE_ADDR']);
        $content_adm = ParseTemplateReturn('./phpshop/lib/templates/order/adminmail.tpl', true);

        // ��������� ������ ��������������
        $title_adm = $this->PHPShopSystem->getName() . ' - ' . $this->lang('mail_title_adm') . $_POST['ouid'] . "/" . date("d-m-y");

        // �������� ������ � ����� �������
        if ($this->setHook(__CLASS__, __FUNCTION__, $content_adm, 'END'))
            return true;

            // �������� ������ �������������� -nah~
		if ($_COOKIE['sincity']=="sp") {
			$PHPShopMail= new PHPShopMail($GLOBALS['SysValue']['mail']['spb_mail'],$_POST['mail'],$title_adm,$content_adm);
		} else if ($_COOKIE['sincity']=="chb") {
			$PHPShopMail= new PHPShopMail($GLOBALS['SysValue']['mail']['chb_mail'],$_POST['mail'],$title_adm,$content_adm);		
		} else {
			if (($_COOKIE['sincity']=="m" || $_COOKIE['sincity']=="other") && strpos($this->PHPShopDelivery->getCity(),'�������')!==false) {
				$PHPShopMail= new PHPShopMail($GLOBALS['SysValue']['mail']['msc2_mail'],$_POST['mail'],$title_adm,$content_adm);
			} else
        	$PHPShopMail= new PHPShopMail($this->PHPShopSystem->getParam('adminmail2'),$_POST['mail'],$title_adm,$content_adm);
		}
        		
    }

    /**
     * SMS ����������
     */
    function sms() {

        // �������� ������
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
     * ������ ������ � ��
     */
    function write() {

        // �������� ������
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST, 'START'))
            return true;

        // ������ ����������
        $person = array(
            "ouid" => $this->ouid,
            "data" => date("U"),
            "time" => date("H:s a"),
            "mail" => $_POST['mail'],
            "name_person" => PHPShopSecurity::CleanStr(@$_POST['name_person']),
            "org_name" => PHPShopSecurity::CleanStr(@$_POST['org_name']),
            "org_inn" => PHPShopSecurity::CleanStr(@$_POST['org_inn']),
            "org_kpp" => PHPShopSecurity::CleanStr(@$_POST['org_kpp']),
            "annual_number" => PHPShopSecurity::CleanStr(@$_POST['annual_number']),
            "bic_bank_number" => PHPShopSecurity::CleanStr(@$_POST['bic_bank_number']),
            "bank_name" => PHPShopSecurity::CleanStr(@$_POST['bank_name']),
            "gen_manager_initial" => PHPShopSecurity::CleanStr(@$_POST['gen_manager_initial']),
            "tel_code" => PHPShopSecurity::CleanStr(@$_POST['tel_code']),
            "tel_name" => PHPShopSecurity::CleanStr(@str_replace($this->tel_num_repl, "", $_POST['tel_name'])),
            "adr_name" => PHPShopSecurity::CleanStr(@$_POST['adr_name']),
            "dostavka_metod" => @$_POST['dostavka_metod'],
            "discount" => $this->discount,
            "user_id" => $_SESSION['UsersId'],
            "dos_ot" => PHPShopSecurity::CleanStr(@$_POST['dos_ot']),
            "dos_do" => PHPShopSecurity::CleanStr(@$_POST['dos_do']),
            "order_metod" => @$_POST['order_metod']);
	//ob_start();
        //print_r($person);
	//$handle = fopen("phpshop/inc/sql_query.txt", "w+");
	//fwrite($handle, ob_get_contents());//ob_get_contents()
	//fclose($handle);        
	//ob_end_clean();
        // ������ �� �������
        $cart = array(
            "cart" => $this->PHPShopCart->getArray(),
            "num" => $this->num,
            "sum" => $this->sum,
            "weight" => $this->weight,
            "dostavka" => $this->delivery);
        if (isset($_POST['firstname']) &&
            isset($_POST['middlename']) &&
            isset($_POST['lastname']) &&
            isset($_POST['pass_no1']) &&
            isset($_POST['pass_no2']) &&
            isset($_POST['pass_police'])) {
            if($_POST['tk_other']!=''){
                $tk=@str_replace("_", "",$_POST['tk_other']);
            } else {
                $tk=@str_replace("_", "",$_POST['tk_list_item']);
            }
            if($_POST['delivery_address']!=''){
                $da=@str_replace("_", "",$_POST['delivery_address']);
            } else {
                $da=@str_replace("_", "",$_POST['tk_delivery_item']);		
            }             
            // ������ ������
           $this->status = array(
               "maneger" => "������������ ��������:".$tk.
               " ���:".@str_replace("_", "",$_POST['firstname']).
               " ��������:".@str_replace("_", "",$_POST['middlename']).
               " �������:".@str_replace("_", "",$_POST['lastname']).
               " �����:".@str_replace("_", "",$_POST['pass_no1'].
               " �����:".$_POST['pass_no2']).
               " ���� ������:".@str_replace("_", "",$_POST['pass_police']).
               " ������� ���������������:".@str_replace($this->tel_num_repl, "",$_POST['tel2']).
               " ����� ��������:".@str_replace("_", "",$_POST['delivery_city']).
               " ����� ��������:".$da,
               "time" => "");  
        } else if (isset($_POST['firstname']) &&
            isset($_POST['middlename']) &&
            isset($_POST['lastname']) &&
            isset($_POST['postal_index']) &&
            isset($_POST['delivery_address'])) {
            // ������ ������
           $this->status = array(
               "maneger" => "������������ ��������: ����� ������".
               " ���:".@str_replace("_", "",$_POST['firstname']).
               " ��������:".@str_replace("_", "",$_POST['middlename']).
               " �������:".@str_replace("_", "",$_POST['lastname']).
               " ������� ���������������:".@str_replace($this->tel_num_repl, "",$_POST['tel2']).
               " ����� ��������:".@str_replace("_", "",$_POST['delivery_city']).
               " ������:".@str_replace("_", "",$_POST['postal_index']).			   
               " ����� ��������:".@str_replace("_", "",$_POST['delivery_address']),
               "time" => "");  
        } else {
        // ������ ������
        $this->status = array(
            "maneger" => "",
            "time" => "");
        }
        // ��������������� ������ ������
        $this->order = serialize(array("Cart" => $cart, "Person" => $person));

        // �������� ������
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST, 'END'))
            return true;

        // ������ ��� ������
        $insert = $_POST;
        $insert['datas_new'] = time();
        $insert['uid_new'] = $this->ouid;
        $insert['orders_new'] = $this->order;
        $insert['status_new'] = serialize($this->status);
        $insert['user_new'] = $_SESSION['UsersId'];

        // ������ ������ � ��
        $result = $this->PHPShopOrm->insert($insert);

        // �������� ������ ��� ������ ������
        $this->error_report($result, array("Cart" => $cart, "Person" => $person, 'insert' => $insert));
        
        // �������������� ������� �������
        $this->PHPShopCart->clean();
    }

    /**
     * ����� �������������� �� ������
     * @param mixed $result ��������� ���������� ������ ������ � ��
     * @param array $var ������ ������
     * @return boolean 
     */
    function error_report($result, $var) {

        if (!is_bool($result)) {

            // ��������� ������ ��������������
            $title = '������ ������ ������ �' . $_POST['ouid'] . ' �� ' . $this->PHPShopSystem->getName() . "/" . date("d-m-y");

            $content='������� ������ ������: '.$result.'����:';
            ob_start();
            print_r($var);
            $content.= ob_get_clean();

            // �������� ������ � ����� �������
            if ($this->setHook(__CLASS__, __FUNCTION__, $content))
                return true;

            // �������� ������ � ������� ��������������
            new PHPShopMail($this->PHPShopSystem->getParam('adminmail2'), $_POST['mail'], $title, $content);
        }
    }

}

/**
 * ������ ������ ������� �������
 */
function mailcartforma($val, $option) {
    global $PHPShopModules;

    // �������� ������
    $hook = $PHPShopModules->setHookHandler(__FUNCTION__, __FUNCTION__, $val, $option);
    if ($hook)
        return $hook;

    if ( ($_COOKIE['sincity']=="sp") AND ($val['price2']!=0) ) {
    	$price=$val['price2'];
    } else if( ($_COOKIE['sincity']=="chb") AND ($val['price3']!=0) ) {
    	$price=$val['price3'];
    }
    else {
    	$price=$val['price'];
    }
    
    $dis =
            $val['uid'] . "  " . $val['name'] . " (" . $val['num'] . " " . $val['ed_izm'] . " * " . $price . ") -- " . ($price * $val['num']) . " " . $option['currency'] . "
";
    return $dis;
}

?>