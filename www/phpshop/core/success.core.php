<?php

/**
 * Обработчик успешной оплаты
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopSuccess
 * @version 1.1
 * @package PHPShopCore
 */
class PHPShopSuccess extends PHPShopCore {

    /**
     * Отладка
     * @var bool 
     */
    var $debug = false;

    /**
     * Конструктор
     */
    function PHPShopSucces() {

        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['orders'];

        parent::PHPShopCore();
    }

    /**
     * Вывод ошибки платежа
     */
    function error() {
        $this->set('orderNum', $this->inv_id);

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__);

        $this->parseTemplate("error/error_payment.tpl");
    }

    /**
     * Форматирование номера заказа
     * @param Int $uid номер заказа
     * @return string
     */
    function true_num($uid) {
        $last_num = substr($uid, -2);
        $total = strlen($uid);
        $ferst_num = substr($uid, 0, ($total - 2));

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $uid);
        if ($hook)
            return $hook;

        return $ferst_num . "-" . $last_num;
    }

    /**
     * Проверяем существование заказа
     */
    function true_order() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
        $data = $PHPShopOrm->select(array('uid'), array('uid' => '="' . $this->true_num($this->inv_id) . '"'), false, array('limit' => 1));
        if (is_array($data))
            if (!empty($data['uid']))
                return true;
    }

    /**
     *  Сообщение об успешном платеже
     */
    function message() {

        $PHPShopOrm = new PHPShopOrm($this->getValue('base.payment_systems'));
        $PHPShopOrm->debug = $this->debug;
        $data = $PHPShopOrm->select(array('*'), array('path' => '="' . $this->order_metod . '"'), false, array('limit' => 1));
        if (is_array($data)) {

            // Сообщение пользователю об успешном платеже
            $text = PHPShopText::notice($data['message_header'] . PHPShopText::br(), $icon = false, '14px') . $data['message'];
            $this->set('mesageText', $text);
            $this->set('orderMesage', ParseTemplateReturn($this->getValue('templates.order_forma_mesage')));

            // Перехват модуля
            $this->setHook(__CLASS__, __FUNCTION__, $data);

            // Подключаем шаблон
            $this->parseTemplate($this->getValue('templates.order_forma_mesage_main'));
        }
        else
            $this->error();
    }

    /**
     * Проверка статуса оплаченного заказа через платежные шлюзы
     */
    function set_order_status_101() {

        $PHPShopOrm = new PHPShopOrm($this->getValue('base.order_status'));
        $PHPShopOrm->debug = $this->debug;
        $data = $PHPShopOrm->select(array('id'), array('id' => '=101'), false, array('limit' => 1));

        if (!is_array($data)) {
            $PHPShopOrm->clean();
            $PHPShopOrm->insert(array('id_new' => 101, 'name_new' => __('Оплачено платежными системами'), 'color_new' => '#ccff00'));
        }
        return 101;
    }

    /**
     *  Запись электронного платежа
     */
    function write_payment() {
        if ($this->out_summ > 0) {
            $PHPShopOrm = new PHPShopOrm($this->getValue('base.payment'));
            $PHPShopOrm->debug = $this->debug;
            $PHPShopOrm->insert(array('uid_new' => $this->inv_id, 'name_new' => $this->order_metod, 'sum_new' => $this->out_summ, 'datas_new' => time()));
        }
    }

    /**
     *  Изменение статуса заказа
     */
    function update_order_status() {
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.orders'));
        $PHPShopOrm->debug = $this->debug;
        $PHPShopOrm->update(array('statusi_new' => $this->set_order_status_101()), array('uid' => '="' . $this->true_num($this->inv_id) . '"'));
    }

    /**
     * Активированные платежные системы
     * @return array 
     */
    function get_payment() {
        $payment_arrray = array();
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.payment_systems'));
        $PHPShopOrm->debug = $this->debug;
        $data = $PHPShopOrm->select(array('path'), array('enabled' => "='1'"), array('order' => 'num'), array('limit' => 100));
        if (is_array($data))
            foreach ($data as $val)
                $payment_arrray[] = $val['path'];

        return $payment_arrray;
    }

    /**
     * Экшен по умолчанию
     */
    function index() {
        global $SysValue;

        // Подключаем обработчики success.php из /payment/
        $path = "payment/";

        // Активированные платежные системы
        $payment_arrray = $this->get_payment();

        if (@$dh = opendir($path)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != "..")
                    if (is_dir($path . $file) and in_array($file, $payment_arrray))
                        if (file_exists($path . $file . "/success.php"))
                            include_once($path . $file . "/success.php");
            }
            closedir($dh);
        }

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $_REQUEST);
        if(is_array($hook)) {
            extract($hook);
        }

        if (!empty($inv_id)) {

            // Номер заказа
            $this->inv_id = PHPShopSecurity::TotalClean($inv_id, 4);

            // Ошибка
            if (strtoupper($my_crc) != strtoupper($crc)) {
                $this->error();
            } else {

                $this->order_metod = $order_metod;
                $this->out_summ = $out_summ;
                $orderId = $inv_id;

                // Заказ есть в БД
                if ($this->true_order()) {

                    // Сообщение платежной системы из БД
                    $this->message();

                    // Перевод статуса заказа в оплачено
                    // $success_function берется из файла обработчика способа оплат
                    if ($success_function == true) {

                        // Вносим в БД данных об электронном платеже
                        $this->write_payment();

                        // Обновление статуса заказа на 101
                        $this->update_order_status();
                    }

                    // Очищаем корзину
                    $_SESSION['cart'] = null;
                    unset($_SESSION['cart']);
                } else {

                    $this->error();
                }
            }
        }
        else
            $this->error();
    }

}

?>