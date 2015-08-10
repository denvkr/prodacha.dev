<?php
/**
 * Обработчик полезных ссылок
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopCore
 */

class PHPShopLinks extends PHPShopCore {

    /**
     * Конструктор
     */
    function PHPShopLinks() {
        // Имя Бд
        $this->objBase=$GLOBALS['SysValue']['base']['table_name17'];

        // Путь для навигации
        $this->objPath="/links/links_";

        // Отладка
        $this->debug=false;

        // список экшенов
        $this->action=array("nav"=>"index","get"=>"add_forma","post"=>"send_gb");
        parent::PHPShopCore();
    }


    /**
     * Экшен по умолчанию
     */
    function index() {

        // Перехват модуля
        if($this->setHook(__CLASS__,__FUNCTION__,false,'START'))
            return true;

        // Выборка данных
        $this->dataArray=parent::getListInfoItem(array('*'),array('enabled'=>"='1'"),array('order'=>'num DESC'));

        // 404
        if(!isset($this->dataArray)) return $this->setError404();


        if(is_array($this->dataArray))
            foreach($this->dataArray as $row) {

                // Определяем переменые
                $this->set('linksImage',$row['image']);
                $this->set('linksName',$row['name']);
                $this->set('linksOpis',$row['opis']);
                $this->set('linksLink',$row['link']);

                // Перехват модуля
                $this->setHook(__CLASS__,__FUNCTION__,$row,'MIDDLE');

                // Подключаем шаблон
                $this->addToTemplate($this->getValue('templates.main_links_forma'));
            }

        // Пагинатор
        $this->setPaginator();

        // Мета
        $this->title="Полезные ссылки - ".$this->PHPShopSystem->getValue("name");
        
        // Перехват модуля
        $this->setHook(__CLASS__,__FUNCTION__,$this->dataArray,'END');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.links_page_list'));
    }

}
?>