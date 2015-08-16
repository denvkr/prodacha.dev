<?php

/**
 * Обработчик прайс-листов
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopPrice
 * @version 1.2
 * @package PHPShopShopCore
 */
class PHPShopPrice extends PHPShopShopCore {

    /**
     * цвет фона таблицы
     * @var string  
     */
    var $color_product = '#ffffff';
    var $debug = false;
    var $memory = true;
    var $category;

    function PHPShopPrice() {

        // Список экшенов
        $this->action = array("nav" => array("CAT"));
        parent::PHPShopShopCore();

        $this->title = $this->lang('price_title') . ' - ' . $this->PHPShopSystem->getValue("title");
    }

    /**
     * Экшен ошибки
     */
    function index() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        // Выбор категории для поиска
        $this->category_select();

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, false, 'END');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.price_page_list'));
    }

    /**
     * Составление реверсивного списка подкаталогов
     * @param int $cat ИД каталога
     * @param string $parent_name цепочка имен родителей
     * @return bool
     */
    function subcategory($cat, $parent_name = false) {
        if (!empty($this->ParentArray[$cat]) and is_array($this->ParentArray[$cat]) ) {
            foreach ($this->ParentArray[$cat] as $val) {

                $name = $this->PHPShopCategoryArray->getParam($val . '.name');
                $sup = $this->subcategory($val, $parent_name . ' / ' . $name);
                if (empty($sup)) {

                    // Запоминаем параметр каталога
                    if ($_REQUEST['cat'] == $val)
                        $sel = 'selected';
                    else
                        $sel = false;

                    $this->value[] = array($parent_name . ' / ' . $name, $val, $sel);

                    // Массив для вывода всех товаров
                    $this->category_array[$cat] = $parent_name . " / " . $name;
                }
            }
            return true;
        }
        else {
            //Запоминаем параметр каталога
            if (!empty($_REQUEST['cat']) and $_REQUEST['cat'] == $cat)
                $sel = 'selected';
            else
                $sel = false;

            if(!$this->errorMultibase($cat))
            $this->value[] = array($parent_name, $cat, $sel);

            // Массив для вывода всех товаров
            $this->category_array[$cat] = $parent_name;

            return true;
        }
    }

    /**
     * Вывод категорий для выбора
     */
    function category_select() {

 
            // Блокировка вывода всех позиций при большой базе
            if ($_SESSION['max_item'] < 1000)
                $this->value[] = array($this->lang('search_all_cat'), 'ALL', false);

            $this->PHPShopCategoryArray = new PHPShopCategoryArray();
            $this->ParentArray = $this->PHPShopCategoryArray->getKey('parent_to.id', true);
            if (is_array($this->ParentArray[0])) {
                foreach ($this->ParentArray[0] as $val) {
                    if ($this->PHPShopCategoryArray->getParam($val . '.vid') != 1 and !$this->errorMultibase($val)) {
                        $name = $this->PHPShopCategoryArray->getParam($val . '.name');
                        $this->subcategory($val, $name);
                    }
                }
            }
        
        
        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $this->ParentArray);
        $this->set('searchPageCategory', PHPShopText::select('catId', $this->value, $width = null, $float = "none"));
    }

    /**
     * Шаблон сетки товаров
     * @return string
     */
    function tr() {
        $Arg = func_get_args();
        $colspan = null;

        if (empty($Arg[0])) {
            $style = 'class="bgprice"';
            $colspan = 3;
        }
        else
            $style = 'bgcolor="' . $Arg[0] . '"';

        $tr = '<tr ' . $style . '>';
        foreach ($Arg as $key => $val) {
            if ($key > 0) {
                if ($key == 2)
                    $width = 70;
                elseif ($key == 3)
                    $width = 20;
                else
                    $width = false;
                $tr.=$this->td($val, $width, $colspan);
            }
        }
        $tr.='</tr>';
        return $tr;
    }

    /**
     * Шаблон сетки товаров
     * @return string
     */
    function td($string, $width, $colspan = false) {
        return '<td width="' . $width . '" colspan="' . $colspan . '">' . $string . '</td>';
    }

    /**
     * Обертка для СЕО ссылок
     * @param array $row массив данных
     * @return string
     */
    function seourl($row) {

        // Перехват модуля, занесение в память наличия модуля для оптимизации цикличности
        if ($this->memory_get(__CLASS__ . '.' . __FUNCTION__, true)) {
            $hook = $this->setHook(__CLASS__, __FUNCTION__, $row);
            if ($hook) {
                return $hook;
            } else
                $this->memory_set(__CLASS__ . '.' . __FUNCTION__, 0);
        }

        return '/shop/UID_' . $row['id'] . '.html';
    }

    /**
     * Вывод товаров из категории
     */
    function product($category) {
        $dis = null;

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $category, 'START'))
            return true;

        // 404
        if (!PHPShopSecurity::true_num($category))
            return $this->setError404();

        // Выборка данных
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
        $PHPShopOrm->debug = $this->debug;
        $data = $PHPShopOrm->select(array('*'), array('category' => '=' . $category, 'enabled' => "='1'", 'parent_enabled' => "='0'"), array('order' => 'num'), array('limit' => 500));

        if (!empty($this->category_name))
            $dis = $this->tr(false, $this->category_name);

        if ($this->PHPShopSystem->getSerilizeParam('admoption.user_price_activate') == 1 and empty($_SESSION['UsersId']))
            $user_price_activate = true;


        // Добавляем в дизайн ячейки с товарами
        if (is_array($data))
            foreach ($data as $row) {
                $name = PHPShopText::a($this->seourl($row), $row['name']);
                if (empty($row['sklad']) and empty($user_price_activate))
                    $cart = PHPShopText::a('javascript:AddToCart(' . $row['id'] . ')', PHPShopText::img('images/shop/basket_put.gif', false, 'absMiddle'), $this->lang('product_sale'));
                else
                    $cart = PHPShopText::a('../users/notice.html?productId=' . $row['id'], PHPShopText::img('images/shop/date.gif', false, 'absMiddle'), $this->lang('product_notice'));

                if (empty($user_price_activate))
                    $price = $this->price($row) . ' ' . $this->currency();
                else
                    $price = PHPShopText::a('../users/register.html', PHPShopText::img('images/shop/icon_user.gif', false, 'absMiddle'), $this->lang('user_register_title'));


                $dis.=$this->tr('#ffffff', $name, $price, $cart);
            }

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $data, 'END');
        if ($hook)
            $dis = $hook;

        $this->add(PHPShopText::table($dis, 3, 1, 'center', '98%', '#D2D2D2'), true);
    }

    /**
     * Экшен вывода товаров при выборе категории
     */
    function CAT() {

        $this->category = $GLOBALS['SysValue']['nav']['page'];

        // Выбор каталога
        $this->category_select();


        // Если выбрана опция вывести все
        if ($this->category == 'ALL' or $GLOBALS['SysValue']['nav']['id'] == 'ALL') {

            foreach ($this->category_array as $key => $val) {
                $dis = $this->tr(false, $val);
                $this->add(PHPShopText::table($dis, 3, 1, 'center', '98%', '#D2D2D2'), true);
                $this->product($key);
            }
        } else {

            $this->product($this->category);
        }

        $this->set('PageCategory', $this->category);

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $this->category_array);

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.price_page_list'));
    }

}

?>