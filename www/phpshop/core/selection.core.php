<?php

/**
 * Обработчик подбора товаров по характеристикам
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopSelection
 * @version 1.2
 * @package PHPShopShopCore
 */
class PHPShopSelection extends PHPShopShopCore {

    /**
     * Отладка
     * @var bool
     */
    var $debug = false;
    /*
     * Кэширование
     */
    var $cache = false;

    /**
     * Лимит вывода товаров
     * @var int
     */
    var $max_item = 100;

    /**
     * Конструктор
     */
    function PHPShopSelection() {

        PHPShopObj::loadClass("sort");

        // Список экшенов
        $this->action = array("get" => "v", 'nav' => 'index');
        parent::PHPShopShopCore();
        $this->PHPShopOrm->cache_format = $this->cache_format;
    }

    function index() {
        $this->setError404();
    }

    /**
     * Вывод списка товаров
     */
    function v() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, null, 'START'))
            return true;

        // Валюта
        $this->set('productValutaName', $this->currency());

        // Количество ячеек
        $cell = $this->PHPShopSystem->getValue('num_vitrina');

        // Фильтр сортировки
        $order = $this->query_filter();

        if (!is_array($order)) {
            $this->PHPShopOrm->sql = $order;
            $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
            $this->dataArray = $this->PHPShopOrm->select();
        }

        // Добавляем в дизайн ячейки с товарами
        $grid = $this->product_grid($this->dataArray, $cell);
        if (empty($grid))
            $grid = PHPShopText::h2($this->lang('empty_product_list'));
        $this->add($grid, true);

        // ID характеристики
        foreach($_GET['v'] as $key=>$val)
            $v=intval($key);
        
        // Описание характеристики
        $PHPShopOrm = new PHPShopOrm();
        $result = $PHPShopOrm->query('SELECT a.*, b.content FROM ' . $this->getValue("base.sort_categories") . ' AS a JOIN ' . $this->getValue("base.page") . ' AS b ON a.page = b.link where a.id = '.$v.' limit 1');
        $row = mysql_fetch_array($result);
        if (is_array($row)) {

            // Описание
            $this->set('sortDes', $row['content']);

            // Название
            $this->set('sortName', $row['name']);

            // Заголовок
            $this->title = __('Производитель') . " - " . $row['name'] . " - " . $this->PHPShopSystem->getParam('title');
            $this->description = __('Производитель') . " - " . $row['name'];
            $this->keywords = $row['name'];
            
        }
        else
            $this->title = __('Поиск по производителям') . " - " . $this->PHPShopSystem->getParam('title');

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $this->dataArray, 'END');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.product_selection_list'));
    }

}

?>