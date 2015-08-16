<?php
/**
 * Обработчик ошибки оплаты
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopCore
 */
class PHPShopFail extends PHPShopCore {
    
    /**
     * Конструктор
     */
    function PHPShopFail() {
        parent::PHPShopCore();
    }
    /**
     * Экшен
     */
    function index() {
        
        $this->set('orderNum',__('Abort Payment'));

        // Перехват модуля
        $this->setHook(__CLASS__,__FUNCTION__);

        $this->parseTemplate("error/error_payment.tpl");
    }
}

?>