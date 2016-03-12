<?php

/**
 * Библиотека данных по методам оплаты
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopObj
 */
class PHPShopPayment extends PHPShopObj {

    var $debug = false;

    /**
     * Конструктор
     * @param int $objID ИД метода оплаты
     */
    function PHPShopPayment($objID) {
        $this->objID = $objID;
        $this->order = array('order' => 'num');
        $this->objBase = $GLOBALS['SysValue']['base']['payment_systems'];
        parent::PHPShopObj();
    }

    /**
     * Имя метода оплаты
     * @return string
     */
    function getName() {
        return parent::getParam("name");
    }

    function getPath() {
        return parent::getParam("path");
    }

    /**
     * ИД метода
     * @return int
     */
    function getId() {
        return parent::getParam("id");
    }

}

/**
 * Массив способов оплат
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopArray
 */
class PHPShopPaymentArray extends PHPShopArray {

    function PHPShopPaymentArray() {
        $this->order = array('order' => 'num');
        $this->objBase = $GLOBALS['SysValue']['base']['payment_systems'];
        parent::PHPShopArray('id', "name", 'path', 'enabled');
    }

    function payment_output($delivery=0,$usertype='phys'){
        $result_array=array();
        $delivery_m_others_array=array(3,11,22,23,24,25,27,28,29,30,31,32,33,34,35,36,37,38,40,42,68);
        $delivery_chb_array=array(12,13);
        $oplata_m_minka_samo_array=array(3,25,26);
        $oplata_m_dynamo_samo_array=array(9,10,11);
        $oplata_m_others_array = array(9,11);
        //делаем видимой оплату москвы и другого региона
        if ( $_COOKIE['sincity']=="m" || $_COOKIE['sincity']=="other") {
            foreach ($this->getArray() as $val) {
                //оплата для Минки
                if ( in_array($val['id'],$oplata_m_minka_samo_array) && $delivery==13 ){
                    $result_array[]=$val['id'];
                    //'<div id="order_metod_div" style="display:table-cell;"><input type="radio" name="order_metod" value="'.$val['id'].'" onClick="document.getElementById(\'bin\').style.display=\'block\';document.getElementById(\'bic\').style.display=\'none\';"><label>Оплата в магазине картой VISA, Mastercard</label><br></div>';
                    //$this->set('order_metod_div','<div id="order_metod_div" style="display:table-cell;"><input type="radio" name="order_metod" value="'.$val[1].'" onClick="document.getElementById(\'bin\').style.display=\'block\';document.getElementById(\'bic\').style.display=\'none\';"><label>'.$val[0].'</label><br></div>');
                    //echo '------------------------';
                }
                //оплата для Динамо
                if ( in_array($val['id'],$oplata_m_dynamo_samo_array) && $delivery==10){
                    $result_array[]=$val['id'];
                    //'<div id="order_metod_div" style="display:table-cell;"><input type="radio" name="order_metod" value="'.$val['id'].'" onClick="document.getElementById(\'bin\').style.display=\'block\';document.getElementById(\'bic\').style.display=\'none\';"><label>Оплата в магазине картой VISA, Mastercard</label><br></div>';
                    //$this->set('order_metod_div','<div id="order_metod_div" style="display:table-cell;"><input type="radio" name="order_metod" value="'.$val[1].'" onClick="document.getElementById(\'bin\').style.display=\'block\';document.getElementById(\'bic\').style.display=\'none\';"><label>'.$val[0].'</label><br></div>');
                    //echo '------------------------';
                }
                //оплата для МО
                if ( in_array($val['id'],$oplata_m_others_array) && (in_array($delivery, $delivery_m_others_array)) ){
                    $result_array[]=$val['id'];
                }
            }
            //оплата в чебоксарах
        } else if ( $_COOKIE['sincity']=="chb") {
            foreach ($this->getArray() as $val) {
                if ( in_array($val['id'],$delivery_chb_array) && $delivery==43 ){
                    $result_array[]=$val['id'];
                }
            }
        } else {
            foreach ($this->getArray() as $val) {
                if ($val['id']==26){
                    return $val['id'];//'<div id="order_metod_div" style="display:none;"><input type="radio" name="order_metod" value="'.$val['id'].'" onClick="document.getElementById(\'bin\').style.display=\'block\';document.getElementById(\'bic\').style.display=\'none\';"><label>'.$val['name'].'</label><br></div>';
                    //$this->set('order_metod_div','<div id="order_metod_div" style="display:none;"><input type="radio" name="order_metod" value="'.$val['id'].'" onClick="document.getElementById(\'bin\').style.display=\'block\';document.getElementById(\'bic\').style.display=\'none\';"><label>'.$val['name'].'</label><br></div>');
                    //echo '------------------------';
                }                
            }
        }
        return $result_array;
    }

}

/**
 * Библиотека приема оплат
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopClass
 */
class PHPShopPaymentResult {

    /**
     * Отладка
     * @var bool 
     */
    var $debug = false;
    /**
     * Запись журнала оплаты
     * @var bool 
     */
    var $log = false;

    function PHPShopPaymentResult() {
        global $_classPath;

        // Адрес лог файла
        $this->log_file = dirname(__FILE__) . $_classPath . 'payment/paymentlog.log';

        // Настройки
        //$this->option();

        $this->updateorder();
    }

    /**
     * Настройка модуля 
     */
    function option() {
        $this->payment_name = 'Liqpay';
        include_once(dirname(__FILE__) . '/../hook/mod_option.hook.php');
        $this->option = new PHPShopLiqpayArray();
    }

    /**
     * Проверка подписи
     * @return boolean 
     */
    function check() {
        $xml = base64_decode($_REQUEST['operation_xml']);
        $this->result_var = readDatabase($xml, 'response', false);

        // Платеж выполнен
        if ($this->result_var['status'] == 'success') {
            $this->out_summ = $this->result_var['amount'];
            $this->inv_id = $this->true_num($this->result_var['order_id']);
            $this->crc = $_REQUEST['signature'];
            $this->my_crc = base64_encode(sha1($this->option['merchant_sign'] . $_REQUEST['operation_xml'] . $this->option['merchant_sign'], 1));
            return true;
        }
    }

    /**
     * Удачное завершение поверки 
     */
    function done() {
        echo "OK" . $this->inv_id . "\n";
        $this->log();
    }

    /**
     * Ошибка 
     */
    function error($type = 1) {
        if ($type == 1)
            echo "bad order num\n";
        else
            echo "bad sign\n";
        $this->log();
    }

    /**
     * Проверка статуса оплаченного заказа через платежные шлюзы
     */
    function set_order_status_101() {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['order_status']);
        $PHPShopOrm->debug = $this->debug;
        $data = $PHPShopOrm->select(array('id'), array('id' => '=101'), false, array('limit' => 1));

        if (!is_array($data)) {
            $PHPShopOrm->clean();
            $PHPShopOrm->insert(array('id_new' => 101, 'name_new' => __('Оплачено платежными системами'), 'color_new' => '#ccff00'));
        }
        return 101;
    }

    /**
     * Обновление данных по заказу 
     */
    function updateorder() {

        if ($this->check()) {

            // Приверяем сущ. заказа
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
            $PHPShopOrm->debug = true;
            $row = $PHPShopOrm->select(array('uid'), array('uid' => "='" . $this->inv_id . "'"), false, array('limit' => 1));
            if (!empty($row['uid'])) {

                // Лог оплат
                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['payment']);
                $PHPShopOrm->insert(array('uid_new' => $this->result_var['order_id'], 'name_new' => $this->payment_name,
                    'sum_new' => $this->out_summ, 'datas_new' => time()));

                // Изменение статуса платежа
                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
                $PHPShopOrm->debug = $this->debug;
                $PHPShopOrm->update(array('statusi_new' => $this->set_order_status_101()), array('uid' => '="' . $this->true_num($this->inv_id) . '"'));

                // Сообщение ОК
                $this->done();
            }
            else
                $this->error();
        }
        else
            $this->error(2);
    }

    /**
     * Запись лога в файл 
     */
    function log() {

        if (empty($this->log))
            return 'Запись журнала оплат отключена';

        if (!empty($this->inv_id)) {
            $content = "
  " . $this->payment_name . " Payment Start ------------------
  date=" . date("F j, Y, g:i a") . "
  out_summ=" . $this->out_summ . "
  inv_id=" . $this->inv_id . "
  crc=" . $this->crc . "
  my_crc=" . $this->my_crc . "
  REQUEST_URI=" . $_SERVER['REQUEST_URI'] . "
  IP=" . $_SERVER['REMOTE_ADDR'] . "
  " . $this->payment_name . " Payment End --------------------
  ";
        } else {
            // Запись отладки

            if (is_array($_REQUEST))
                foreach ($_REQUEST as $k => $v) {
                    $content.=$k . '=' . $v . '
';
                }

            $content = "
  " . $this->payment_name . " Payment Start ------------------
  " . $content . " 
  " . $this->payment_name . " Payment End --------------------
  ";
        }

        PHPShopFile::chmod($this->log_file, false);
        PHPShopFile::write($this->log_file, $content, 'a+', true);
    }

    /**
     * Преобразование номера заказа
     * @param Int $uid
     * @return string 
     */
    function true_num($uid) {
        $last_num = substr($uid, -2);
        $total = strlen($uid);
        $first_num = substr($uid, 0, ($total - 2));
        return $first_num . "-" . $last_num;
    }

}

?>