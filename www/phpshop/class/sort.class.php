<?php

if (!defined("OBJENABLED")) {
    require_once(dirname(__FILE__) . "/array.class.php");
    require_once(dirname(__FILE__) . "/category.class.php");
}

/**
 * Сортировки и фильтры товаров
 * @author PHPShop Software
 * @version 1.6
 * @package PHPShopClass
 */
class PHPShopSort {

    /**
     * Отладка
     * @var bool
     */
    var $debug = false;
    var $disp;

    /**
     * Вывод фильтра характеристик
     * @param int $category ИД категории характеристики
     * @param string $sort сериализованный массив характеристик
     * @param bool $direct опция учета направления сортировки
     * @param string $template Имя функции шаблона вывода
     * @param array $vendor массив данных характеристик у товара
     * @param bool $filter опция учета выборки с учетом флага фильтра в характеристики
     * @param bool $goodoption опция учета выборки с учетом отсутствия флага опции товара в характеристики
     */
    function PHPShopSort($category = null, $sort = null, $direct = true, $template = null, $vendor = false, $filter = true, $goodoption = false) {

        $sql_add = null;

        // Направление сортировки
        if ($direct)
            $this->direct();

        if (!empty($sort))
            $sort = unserialize($sort);
        elseif (!empty($category)) {
            $PHPShopCategory = new PHPShopCategory($category);
            $sort = $PHPShopCategory->unserializeParam('sort');
        }

        // Учет фильтров
        if ($filter)
            $sql_add.=" and filtr='1' ";

        // Учет опций
        if (empty($goodoption))
            $sql_add.=" and goodoption!='1' ";

        // Список для выборки
        $sortList=null;
        if (is_array($sort)) {
            foreach ($sort as $value) {
                $sortList.=' id=' . trim($value) . ' OR';
            }
            $sortList = substr($sortList, 0, strlen($sortList) - 2);

            $PHPShopOrm = new PHPShopOrm();
            $PHPShopOrm->debug = $this->debug;
            $PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
            $result = $PHPShopOrm->query("select * from " . $GLOBALS['SysValue']['base']['sort_categories'] . " where ($sortList) " . $sql_add . " order by num");
            while (@$row = mysql_fetch_array($result)) {
                $id = $row['id'];
                $name = $row['name'];
                $this->disp.=$this->value($id, $name, true, $template, $vendor);
            }
        }
    }

    /**
     * Направление сортировки товара в каталоге
     */
    function direct() {
        global $SysValue;

        // Направление сортировки пользователем
        if(!empty($_GET['f']))
        switch ($_GET['f']) {
            case(1):
                $SysValue['other']['productSortNext'] = 2;
                $SysValue['other']['productSortImg'] = 1;
                $SysValue['other']['productSortTo'] = 1;
                break;
            case(2):
                $SysValue['other']['productSortNext'] = 1;
                $SysValue['other']['productSortImg'] = 2;
                $SysValue['other']['productSortTo'] = 2;
                break;
            default:
                $SysValue['other']['productSortNext'] = 2;
                //$SysValue['other']['productSortImg']=1;
                $SysValue['other']['productSortTo'] = 1;
        }

        // Сортировка пользователем
        if(!empty($_GET['s']))
        switch ($_GET['s']) {
            case(1):
                $SysValue['other']['productSortA'] = "sortActiv";
                $SysValue['other']['productSort'] = 1;
                break;
            case(2):
                $SysValue['other']['productSortB'] = "sortActiv";
                $SysValue['other']['productSort'] = 2;
                break;
            case(3):
                $SysValue['other']['productSortC'] = "sortActiv";
                $SysValue['other']['productSort'] = 3;
                break;
            case(4):
                $SysValue['other']['productSortD'] = "sortActiv";
                $SysValue['other']['productSort'] = 4;
                break;
            default:
                $SysValue['other']['productSort'] = 1;
        }


        if (empty($_GET['v'])) {
            $SysValue['other']['productVendor'] = "";
        } else {
            $productVendor = null;
            if (is_array($_GET['v'])) {
                foreach ($_GET['v'] as $k => $v)
                    $productVendor.='v[' . intval($k) . ']=' . intval($v) . '&';
                $productVendor = substr($productVendor, 0, strlen($productVendor) - 1);
            }
            $SysValue['other']['productVendor'] = $productVendor;
        }

        // Сортировка по цене
        $SysValue['other']['productRriceOT'] = PHPShopSecurity::TotalClean(@$_POST['priceOT'], 1);
        $SysValue['other']['productRriceDO'] = PHPShopSecurity::TotalClean(@$_POST['priceDO'], 1);
    }

    /**
     * Вывод значений характеристик
     * @param int $n ИД характеристики
     * @param string $title Название
     * @param bool $all Показывать опцию выбрать все
     * @param string $template Имя функции шаблона вывода
     * @return string
     */
    function value($n, $title, $all = false, $template = null, $vendor = false) {
        global $SysValue;

        $disp = null;
        $i = 1;
        if (empty($vendor)) {
            if (empty($_POST['v']))
                $vendor = @$SysValue['nav']['query']['v'];
            else
                $vendor = $_POST['v'];
        }


        $all_sel = 'selected';
        $PHPShopOrm = new PHPShopOrm();
        $PHPShopOrm->debug = $this->debug;
        $PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
        $result = $PHPShopOrm->query("select * from " . $SysValue['base']['sort'] . " where category=$n order by num");
        while ($row = mysql_fetch_array($result)) {
            $id = $row['id'];
            $name = substr($row['name'], 0, 35);
            $sel = null;
            if (is_array($vendor))
                foreach ($vendor as $v) {
                    if ($id == $v) {
                        $sel = "selected";
                        $all_sel = null;
                    }
                }

            $value[$i] = array($name, $id, $sel);
            $i++;
        }


        $SysValue['sort'][] = $n;

        // Показать выбрать все
        if (!empty($all) and empty($template)) {
            $value[] = array('-- все ' . $title . ' --', '', $all_sel);
        }


        if (empty($template)) {
            $size = (strlen($title) + 10) * 7;
            $disp = PHPShopText::select('v[' . $n . ']', $value, $size, false, false, false, false, false, $n);
        } elseif (function_exists($template)) {
            $disp = call_user_func_array($template, array($value, $n, $title, $vendor));
        }

        // Массив характеристик для использования в модулях
        $this->value_array = $value;

        return $disp;
    }

    /**
     * Вывод блока сортировки по характеристикам
     */
    function display() {
        global $SysValue;

        $v_ids = null;
        if (!empty($this->disp)) {
            if (is_array($SysValue['sort']))
                foreach ($SysValue['sort'] as $value)
                    $v_ids.=$value . ",";
            $len = strlen($v_ids);
            $v_ids = substr($v_ids, 0, $len - 1);

            // Кнопка применить и сбросить фильтра
            $SysValue['other']['vendorSelectDisp'] = PHPShopText::button($SysValue['lang']['sort_apply'], $onclick = 'GetSortAll(' . $SysValue['nav']['id'] . ',' . $v_ids . ')');
            $SysValue['other']['vendorSelectDisp'].=PHPShopText::button($SysValue['lang']['sort_reset'], $onclick = 'window.location.replace(\'?\')');
            $SysValue['other']['vendorDispTitle'] = PHPShopText::div(PHPShopText::b($SysValue['lang']['sort_title']));
        }

        return PHPShopText::td($this->disp);
    }

    /**
     * Вывод текущего содержимого
     * @return string 
     */
    function getContent() {
        return $this->disp;
    }

}

/**
 * Шаблон вывода характеристик
 * @param array $value массив значений $value[]=array('моя цифра 1',123,'selected');
 * @param int $n integer
 * @param string $title название характеристики
 * @param array $vendor массив значений характеристик товара
 * @return string 
 */
function sorttemplateexample($value, $n, $title, $vendor) {
    $disp = null;

    if (is_array($value)) {
        foreach ($value as $p) {
            if (is_array($vendor[$n])) {
                foreach ($vendor[$n] as $value) {

                    if ($value == $p[1])
                        $text = PHPShopText::b($p[0]);
                    else
                        $text = $p[0];

                    $disp.=PHPShopText::br() . PHPShopText::a('?v[' . $n . ']=' . $p[1], $text, $p[0], $color = false, $size = false, $target = false, $class = false);
                }
            }else {
                if ($vendor[$n] == $p[1])
                    $text = PHPShopText::b($p[0]);
                else
                    $text = $p[0];

                $disp.=PHPShopText::br() . PHPShopText::a('?v[' . $n . ']=' . $p[1], $text, $p[0], $color = false, $size = false, $target = false, $class = false);
            }
        }
    }
    return $disp;
}

/**
 * Массив с характеристиками товаров
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopArray
 */
class PHPShopSortArray extends PHPShopArray {

    /**
     * Конструктор
     * @param array $sql SQL условие выборки
     * @param bull $debug отладка
     */
    function PHPShopSortArray($sql = false, $debug = false) {
        $this->objSQL = $sql;
        $this->debug = $debug;
        $this->objBase = $GLOBALS['SysValue']['base']['table_name21'];
        parent::PHPShopArray('id', 'name', 'page');
    }

}

/**
 * Массив с характеристиками категорий товаров
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopArray
 */
class PHPShopSortCategoryArray extends PHPShopArray {

    /**
     * Конструктор
     * @param array $sql SQL условие выборки
     * @param bull $debug отладка
     */
    function PHPShopSortCategoryArray($sql = false, $debug = false) {
        $this->objSQL = $sql;
        $this->debug = $debug;
        $this->objBase = $GLOBALS['SysValue']['base']['table_name20'];
        parent::PHPShopArray('id', 'name', 'category', 'filtr', 'page', 'flag', 'goodoption');
    }

}

/**
 *  Вывод характеристик по имени
 *  @example $search=PHPShopSortSearch('Бренд'); $search->search($vendor_array);
 */
class PHPShopSortSearch {

    /**
     * Выборка характеритик по имени
     * @param string $name имя характеристики
     */
    function PHPShopSortSearch($name) {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name20']);
        $PHPShopOrm->debug = false;
        $data = $PHPShopOrm->select(array('id'), array('name' => '="' . $name . '"'), false, array('limit' => 1));
        if (is_array($data)) {

            $sort_category = $data['id'];
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name21']);
            $PHPShopOrm->debug = false;
            $data = $PHPShopOrm->select(array('id,name'), array('category' => '=' . $sort_category), false, array('limit' => 100));
            if (is_array($data)) {
                foreach ($data as $val)
                    $this->sort_array[$val['id']] = $val['name'];
            }
        }
    }

    /**
     * Поиск в массиве характеритик товара нужной характеристики
     * @param array $row массив характеристик товара
     * @return string имя характеристики в тэге
     */
    function search($row) {
        if (is_array($row))
            foreach ($row as $val) {
                if (!empty($this->sort_array[$val[0]])) {
                    return $this->sort_array[$val[0]];
                }
            }
    }

}

?>