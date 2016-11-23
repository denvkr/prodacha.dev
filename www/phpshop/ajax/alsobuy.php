<?php
/**
 * Блок товаров довесков
 * @package PHPShopAjaxElements
 */
$_classPath = "../";
include_once($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
//require_once $PHPShopBase->getParam('file.shopelements');
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("string");
PHPShopObj::loadClass("text");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass('product');
PHPShopObj::loadClass("elements");
PHPShopObj::loadClass("shopelements");
PHPShopObj::loadClass("nav");
PHPShopObj::loadClass("obj");
//include_once($_classPath . "core/order.core.php");

// Подключаем библиотеку поддержки.
require_once $_classPath . "/lib/Subsys/JsHttpRequest/Php.php";

$JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");
$PHPShopOrm = new PHPShopOrm();
$PHPShopSystem=new PHPShopSystem();
//$PHPShopArray=new PHPShopArray();
$PHPShopNav=new PHPShopNav();
$PHPShopElements=new PHPShopElements();
$PHPShopProductElements=new PHPShopProductElements();
//$PHPShopProductIconElements=new PHPShopProductIconElements();
$odnotip_setka_num = 1;
$line = false;
$template_odnotip = 'main_spec_forma_icon';

$grid = false;

/**
 * @var bool запоминание параметов выполнения функций и модулей
 * При проектировании модулей и хуков следует отключить память [false]
 */
$memory = true;

/**
 * Имя изображения заглушки
 * @var string 
 */
$no_photo = 'images/shop/no_photo.gif';
$total = 0;
$product_grid;
$cell;
$setka_footer;
$dengi = $PHPShopSystem->getDefaultValutaId();
$grid;

//$PHPShopProductIconElements = new PHPShopProductIconElements();
//if(PHPShopSecurity::true_param($_REQUEST['region'],$_REQUEST['delivery']))

//читаем данные по продукту на странице
$result = $PHPShopOrm->query("select * from " . $PHPShopBase->getParam('base.products') . " where id=" . $_REQUEST['xid'] . " and  enabled='1' and parent_enabled='0' and sklad!='1' order by num");
$row = mysql_fetch_assoc($result);
//var_dump($row);
//$disp=1;

$disp = null;
$odnotipList = null;
if (!empty($row['odnotip'])) {
    if (strpos($row['odnotip'], ','))
        $odnotip = explode(",", $row['odnotip']);
    elseif (is_numeric(trim($row['odnotip'])))
        $odnotip[] = trim($row['odnotip']);
}

// Список для выборки
if (is_array($odnotip))
    foreach ($odnotip as $value) {
        if (!empty($value))
            $odnotipList.=' id=' . trim($value) . ' OR';
    }

$odnotipList = substr($odnotipList, 0, strlen($odnotipList) - 2);

// Режим проверки остатков на складе
if ($PHPShopSystem->getSerilizeParam('admoption.sklad_status') == 2)
    $chek_items = ' and items>0';
else
    $chek_items = null;

if (!empty($odnotipList)) {
    
$result = $PHPShopOrm->query("select * from " . $PHPShopBase->getParam('base.products') . " where (" . $odnotipList . ") " . $chek_items . " and  enabled='1' and parent_enabled='0' and sklad!='1' order by num");
//echo "select * from " . $PHPShopBase->getParam('base.products') . " where (" . $odnotipList . ") " . $chek_items . " and  enabled='1' and parent_enabled='0' and sklad!='1' order by num";
//По рекомендации кокоса уменьшаем кол-во товаров для индексирования в разделе Для работы с товаром <...> Вы можете приобрести
$cnt=0;
while ($row = mysql_fetch_assoc($result)) {
    if ($cnt==3) break;
    $data[] = $row;
    $cnt++;
}
//var_dump($data);
// Сетка товаров
if (!empty($data) and is_array($data))
    $disp = product_grid($data, $odnotip_setka_num, $template_odnotip, $line);
}

    function product_grid($dataArray, $l_cell, $template = false, $line = true) {
        global $grid,$total,$PHPShopNav,$PHPShopElements,$PHPShopSystem;
        if (!empty($line))
            $grid = true;
        else
            $grid = false;

        if (empty($l_cell))
            $cell = 2;
        $cell = $l_cell;
        $setka_footer = true;

        $table = null;
        $j = 1;
        $item = 1;

        $PHPShopElements->set('productInfo', lang('productInfo'));
        $PHPShopElements->set('productSale', lang('product_sale'));
        $PHPShopElements->set('productValutaName', currency());

        $d1 = $d2 = $d3 = $d4 = $d5 = $d6 = $d7 = null;
        if (is_array($dataArray)) {
            $total = count($dataArray);
            foreach ($dataArray as $row) {

                // Определяем переменные
                $PHPShopElements->set('productName', $row['name']);
                $PHPShopElements->set('productArt', $row['uid']);
                $PHPShopElements->set('productDes', $row['description']);
                $PHPShopElements->set('productPageThis', $PHPShopNav->getPage());

                // Пустая картинка
                if (empty($row['pic_small']))
                    $PHPShopElements->set('productImg', $this->no_photo);
                else
                    $PHPShopElements->set('productImg', $row['pic_small']);

                // Проверка режима Multibase
                //$this->checkMultibase($row['pic_small']);

                $PHPShopElements->set('productImgBigFoto', $row['pic_big']);
                $PHPShopElements->set('productPriceMoney', $PHPShopSystem->getDefaultValutaId());

                $PHPShopElements->set('productUid', $row['id']);
                $PHPShopElements->set('catalog', lang('catalog'));

                // Опции склада
                //checkStore($row);

                // Шаблон ячейки товара
                if (empty($template))
                    $template = 'main_product_forma_' . $cell;

                // Подключаем шаблон ячейки товара
                $dis = ParseTemplateReturn($PHPShopElements->getValue('templates.' . $template));

                // Убераем последний разделитель в сетке
                if ($item == $total)
                    $setka_footer = false;

                $cell_name = 'd' . $j;
                $$cell_name = $dis;

                if ($j == $cell) {
                    $table.=setCell($d1, $d2, $d3, $d4, $d5, $d6, $d7);
                    $d1 = $d2 = $d3 = $d4 = $d5 = $d6 = $d7 = null;
                    $j = 0;
                } elseif ($item == $total) {
                    $table.=setCell($d1, $d2, $d3, $d4, $d5, $d6, $d7);
                }

                $j++;
                $item++;
            }
        }

        $product_grid = $table;

        return $table;
    }
    /**
     * Проверка дополнительных данных товара по складу
     * @param array $row масив данных по товару
     */
    function checkStore($row) {

        // Показывать состояние склада
        if ($this->PHPShopSystem->getSerilizeParam('admoption.sklad_enabled') == 1 and $row['items'] > 0)
            $this->set('productSklad', $this->lang('product_on_sklad') . " " . $row['items'] . " " . $this->lang('product_on_sklad_i'));
        else
            $this->set('productSklad', '');

        // Если товар на складе
        if (empty($row['sklad'])) {

            $this->set('Notice', '');

            // Если нет новой цены
            if (empty($row['price_n'])) {

            	if ( ($_COOKIE['sincity']=="sp") AND ($row['price2']!=0) ) {
            		$price=$row['price2'];
            	} else if( ($_COOKIE['sincity']=="chb") AND ($row['price3']!=0) ) {
            		$price=$row['price3'];
            	}
            	else {
            		$price=$row['price'];
            	}
            	
            	$price=strval($price);
            	$mod_price=parent::add_space_to_price($price);
            	$this->set('productPrice',$mod_price);            	
                $this->set('productPriceRub', '');
            }

            // Если есть новая цена
            else {
            	if ( ($_COOKIE['sincity']=="sp") AND ($row['price2']!=0) ) {
            		$price=$row['price2'];
            	} else if( ($_COOKIE['sincity']=="chb") AND ($row['price3']!=0) ) {
            		$price=$row['price3'];
            	}
            	else {
            		$price=$row['price'];
            	}            	
                //$productPrice = $this->price($row);
                $productPriceNew = $this->price($row, true);
                $price=strval($price);
            	//echo $mod_price.'<br>';
            	$mod_price=parent::add_space_to_price($price);
            	//echo $mod_price.'<br>';
                $this->set('productPrice',$mod_price);                
                //$this->set('productPrice', $productPrice);
                $this->set('productPriceRub', PHPShopText::strike($productPriceNew));
            }
        }

        // Товар под заказ
        else {
        	$this->set('collaboration','lostandfound');

        	if ( ($_COOKIE['sincity']=="sp") AND ($row['price2']!=0) ) {
        		$price=$row['price2'];
        	} else if( ($_COOKIE['sincity']=="chb") AND ($row['price3']!=0) ) {
            		$price=$row['price3'];
            } 
            else {
        		$price=$row['price'];
        	}
        	 
        	$price=strval($price);
            	$mod_price=parent::add_space_to_price($price);
        	$this->set('productPrice',$mod_price);
                $this->set('productPriceRub', '');

        }

        // Если цены показывать только после авторизации
        if ($this->PHPShopSystem->getSerilizeParam('admoption.user_price_activate') == 1 and empty($_SESSION['UsersId'])) {
            $this->set('productPrice', PHPShopText::comment('<'));
            $this->set('productValutaName', PHPShopText::comment('>'));
        }
	
    }
    /**
     * Форма ячеек с товарами
     * @return string
     */
    function setCell() {

        // Оформление разделителя ячеек
        if ($grid)
            $grid_style = 'class="setka"';
        else
            $grid_style = '';

        $Arg = func_get_args();
        $item = 1;
        $tr = '<tr>';

        foreach ($Arg as $key => $value)
            if ($key < $cell and $total >= $cell)
                $args[] = $value;
            elseif (!empty($value))
                $args[] = $value;

        $num = count($args);

        // Расчет CSS стилей сетки товара
        switch ($num) {

            // Сетка в 1 ячейку
            case 1:
                $panel = array('panel_l panel_1_1');
                break;

            // Сетка в 2 ячейки
            case 2:
                $panel = array('panel_l panel_2_1', 'panel_r panel_2_2');
                break;

            // Сетка в 3 ячейки
            case 3:
                $panel = array('panel_l panel_3_1', 'panel_r panel_3_2', 'panel_l panel_3_2');
                break;

            // Сетка в 4 ячейки
            case 4:
                $panel = array('panel_l panel_4_1', 'panel_r panel_4_2', 'panel_l panel_4_3', 'panel_r panel_4_4',);
                break;

            // Сетка в 5 ячейки
            case 5:
                $panel = array('panel_l panel_5_1', 'panel_r panel_5_2', 'panel_l panel_5_3', 'panel_l panel_5_4', 'panel_l panel_5_5');
                break;

            default: $panel = array('panel_l', 'panel_r', 'panel_l', 'panel_r', 'panel_l', 'panel_r', 'panel_l');
        }

        if (is_array($args))
            foreach ($args as $key => $val) {
                $tr.='<td class="' . $panel[$key] . '" valign="top">' . $val . '</td>';

                if ($item < $num and $num <= $this->cell)
                    $tr.='<td ' . $grid_style . '><img src="images/spacer.gif" width="1"></td>';

                $item++;
            }
        $tr.='</tr>';

        if (!empty($setka_footer))
            $tr.='<tr><td ' . $grid_style . ' colspan="' . ($cell * 2) . '" height="1"><img height="1" src="images/spacer.gif"></td></tr>';

        return $tr;
    }
    
    /**
     * Валюта
     * @return string
     */
    function currency($name = 'code') {
        global $dengi,$PHPShopOrm,$PHPShopBase;
        if (isset($_SESSION['valuta']))
            $currency = $_SESSION['valuta'];
        else
            $currency = $dengi;
        $PHPShopOrm2=new $PHPShopOrm($PHPShopBase->getParam('base.currency'));
        $row = $PHPShopOrm2->select(array($name), array('id' => '=' . intval($currency)), false, array('limit' => 1), __FUNCTION__, array('base' => $PHPShopBase->getParam('base.currency'), 'cache' => 'true'));

        return $row[$name];
    }
    /**
     * Вывод языкового параметра по ключу [config.ini]
     * @param string $str ключ языкового массива
     * @return string
     */
    function lang($str) {
        global $PHPShopBase;
        if ($PHPShopBase->getParam('lang'.[$str]))
            return $PHPShopBase->getParam('lang'.[$str]);
        else
            return 'Не определено';
    }
    
function ParseTemplateReturn($TemplateName, $mod = false)
{
    global $SysValue,$_classPath;
    //echo $TemplateName;
    if ($mod)
        $file = newGetFile($TemplateName);
    else
        $file = newGetFile($SysValue['dir']['templates']. $_SESSION['skin'] . chr(47) . $TemplateName);
    $dis = newParser($file);
    return $dis;
}
function newGetFile($path)
{
    if (strpos($path, '.tpl')) {
        $file = @file_get_contents($path);
        if (! $file)
            return false;
        return $file;
    } else
        return false;
}
function newParser($string)
{
    global $SysValue;
    $newstring = @preg_replace_callback("/(@php)(.*)(php@)/sU", "evalstr", $string);
    $newstring = @preg_replace("/@([a-zA-Z0-9_]+)@/e", '$SysValue["other"]["\1"]', $newstring);
    return $newstring;
}
// Формируем результат
$_RESULT = array(
    "sameproduct" => $disp
);