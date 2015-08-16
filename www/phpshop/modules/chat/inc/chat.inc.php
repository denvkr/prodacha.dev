<?php

/**
 * Элемент формы входа в чат
 */
class AddToTemplateChatElement extends PHPShopElements {

    var $debug = false;

    /**
     * Конструктор
     */
    function AddToTemplateChatElement() {
        parent::PHPShopElements();
        $this->option();
    }

    /**
     * Настройки
     */
    function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['chat']['chat_system']);
        $PHPShopOrm->debug = $this->debug;
        $this->option = $PHPShopOrm->select();
    }

    /**
     * Вывод формы
     */
    function display() {

        $forma = parseTemplateReturn($GLOBALS['SysValue']['templates']['chat']['chat_forma'], true);
        $this->set('leftMenuContent', $forma);
        $this->set('leftMenuName', $this->option['title']);

        // Подключаем шаблон
        $dis = $this->parseTemplate($this->getValue('templates.left_menu'));


        // Назначаем переменную шаблона
        //if ($this->option['operator'] == 1)
            switch ($this->option['enabled']) {

                case 1:
                    $this->set('leftMenu', $dis, true);
                    break;

                case 2:
                    $this->set('rightMenu', $dis, true);
                    break;

                default: $this->set('chat', $dis);
            }
    }

}

// Добавляем в шаблон элемент
$AddToTemplateChatElement = new AddToTemplateChatElement();
$AddToTemplateChatElement->display();
?>