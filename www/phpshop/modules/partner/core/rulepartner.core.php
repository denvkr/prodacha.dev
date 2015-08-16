<?php


class PHPShopRulepartner extends PHPShopCore {

    /**
     * Конструктор
     */
    function PHPShopRulepartner() {

        // Имя Бд
        $this->objBase=$GLOBALS['SysValue']['base']['partner']['partner_system'];
        $this->debug=false;
        parent::PHPShopCore();
    }

    function index(){

        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $data=$PHPShopOrm->select(array('percent,rule'));

        // Определяем переменные
        $this->set('partnerPercent',$data['percent']);
        $this->set('pageContent',Parser($data['rule']));
        $this->set('pageTitle',$GLOBALS['SysValue']['lang']['partner_rule_title']);

        // Мета
        $this->title="Кабинет партнера - Напоминание пароля - ".$this->PHPShopSystem->getValue("name");

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

}
?>
