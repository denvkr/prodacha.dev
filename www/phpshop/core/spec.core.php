<?php

/**
 * Обработчик спецпредложений товаров
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopSpec
 * @version 1.2
 * @package PHPShopShopCore
 */
class PHPShopSpec extends PHPShopShopCore {

    var $debug = false;
    var $cache = true;
    var $cache_format = array('content', 'yml_bid_array');
    var $cell;

    /**
     * Конструктор
     */
    function PHPShopSpec() {

        parent::PHPShopShopCore();
        $this->PHPShopOrm->cache_format = $this->cache_format;
    }

    /**
     * Вывод списка товаров
     */
    function index() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        // Путь для навигации
        $this->objPath = './spec_';

        // Валюта
        $this->set('productValutaName', $this->currency());

        $this->set('catalogCategory', $this->lang('specprod'));

        // Количество ячеек
        if (empty($this->cell))
            $this->cell = $this->PHPShopSystem->getValue('num_vitrina');

        // Фильтр сортировки
        $order = $this->query_filter("spec='1'");

        // Простой запрос
        if (is_array($order)) {
            $this->dataArray = parent::getListInfoItem(array('*'), array('spec' => "='1'", 'enabled' => "='1'"), $order, __CLASS__, __FUNCTION__);
        } else {
            // Сложный запрос
            $this->PHPShopOrm->sql = $order;
            $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
            $this->dataArray = $this->PHPShopOrm->select();
            $this->PHPShopOrm->clean();
        }


        // Пагинатор
        if (is_array($order))
            $this->setPaginator(count($this->dataArray));

        // Добавляем в дизайн ячейки с товарами
        $grid = $this->product_grid($this->dataArray, $this->cell);
        if (empty($grid))
            $grid = PHPShopText::h2($this->lang('empty_product_list'));
        $this->add($grid, true);

        // Заголовок
        $this->title = $this->lang('specprod') . " - " . $this->PHPShopSystem->getParam('title');

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $this->dataArray, 'END');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.product_page_spec_list'));
    }

}

?>