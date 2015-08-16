<?php

class PHPShopPartnerOrder {
    var $option=null;

    /**
     * Конструктор
     */
    function PHPShopPartnerOrder() {
        $this->setPartner();
        $this->objBase = $GLOBALS['SysValue']['base']['partner']['partner_log'];
        $this->partner = $_SESSION['partner_id'];
        $this->path = $_SESSION['partner_path'];
        $this->option = $this->option();
        $this->debug = false;
    }

    /**
     * Настройки модуля
     * @return array
     */
    function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['partner']['partner_system']);
        $PHPShopOrm->debug=$this->debug;
        return $PHPShopOrm->select();
    }

    /**
     * Проверка статуса предыдущего заказа партнера
     */
    function checkLog() {
        $PHPShopOrm = new PHPShopOrm();
        $PHPShopOrm->debug=$this->debug;

        $result = $PHPShopOrm->query('SELECT a.*, b.uid, b.statusi, b.orders, c.money FROM '.$this->objBase.' AS a
            JOIN '.$GLOBALS['SysValue']['base']['table_name1'].' AS b ON a.order_id = b.uid
            JOIN '.$GLOBALS['SysValue']['base']['partner']['partner_users'].' AS c ON c.id =  a.partner_id
            WHERE a.partner_id='.$this->partner.' and a.enabled="0"');

        while($row = mysql_fetch_array($result)) {

            // Если заказ выполнен, заносим эти данные в лог партнера, начисляем % партнеру
            if($row['statusi'] == $this->option['order_status']) {
                $PHPShopOrm = new PHPShopOrm($this->objBase);
                $PHPShopOrm->debug=$this->debug;

                // Изменяем статус лога заказов партнера на проведенный
                $PHPShopOrm->update(array('enabled_new'=>'1'),array('order_id'=>'="'.$row['uid'].'"'));

                // Начисляем бонус партнеру
                $this->addBonus($row['orders'],$row['money']);
            }

        }
    }

    /**
     * Начисляем бонус партнеру
     * @param array $order сериализованный массив
     * @param float $money баланс партнера
     */
    function addBonus($order,$money) {

        $order=unserialize($order);
        $total=0;

        // Подсчитываем сумму заказа
        if(is_array($order['Cart']['cart']))
            foreach($order['Cart']['cart'] as $val)
                $total+=$val['num']*$val['price'];

        $total=$total*$this->option['percent']/100;

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['partner']['partner_users']);
        $PHPShopOrm->debug=$this->debug;

        // Бонус за предыдущий проведенный заказ
        $bonus=$money+$total;

        // Обновляем баланс партнера
        $PHPShopOrm->update(array('money_new'=>$bonus),array('id'=>'="'.$this->partner.'"'));
    }


    /**
     * Запись заказа в лог партнеров
     */
    function writeLog() {
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->insert(array('date_new'=>date("U"),'order_id_new'=>$_POST['ouid'],
                'partner_id_new'=>$this->partner,'path_new'=>$this->path,'order_user_new'=>$_POST['name_person'],
                'percent_new'=>$this->option['percent']));
    }

    /**
     * Запись ID партнера
     */
    function setPartner() {
        if(!empty($_GET['partner'])) {
            $_SESSION['partner_id']=$_GET['partner'];
            $_SESSION['partner_path']=$_SERVER["HTTP_REFERER"];
        }
    }
}

$PHPShopPartnerOrder = new PHPShopPartnerOrder();

 /*
if(PHPShopSecurity::true_param($_SESSION['partner_id'],$_SESSION['cart'],$_POST['send_to_order'],$_POST['mail'],$_POST['name_person'])) {

    // Модуль включен
    if($PHPShopPartnerOrder->option['enabled'] == 1) {
        $PHPShopPartnerOrder->writeLog();
        $PHPShopPartnerOrder->checkLog();
    }
}
 */
?>