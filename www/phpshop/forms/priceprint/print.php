<?php
session_start();

// Библиотеки
include("../../class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("category");
PHPShopObj::loadClass("product");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("text");
PHPShopObj::loadClass("parser");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("core");

// Подключение к БД
$PHPShopBase = new PHPShopBase("../../inc/config.ini");

$PHPShopValutaArray = new PHPShopValutaArray();
$PHPShopSystem = new PHPShopSystem();

class PHPShopPricePrint {
    var $print;

    function PHPShopPricePrint() {
        global $PHPShopSystem;
        $this->debug=false;
        $this->objBase=$GLOBALS['SysValue']['base']['products'];
        $this->PHPShopSystem = $PHPShopSystem;
    }

    function product($category) {
        global $PHPShopValutaArray;

        if(is_numeric($category)) $str=" (category=$category or dop_cat LIKE '%#$category#%') and ";
        else $str="";

        $ValutaArray=$PHPShopValutaArray->getArray();
        $valuta=$ValutaArray[$this->PHPShopSystem->getValue('dengi')]['code'];

        $PHPShopOrm = new PHPShopOrm();
        $PHPShopOrm->sql="select * from ". $this->objBase." where ".$str." enabled='1' and parent_enabled='0'";
        $PHPShopOrm->debug=$this->debug;
        $dataArray=$PHPShopOrm->select();

        if(is_array($dataArray)) {

            // Категория
            $this->print.=PHPShopText::tr(PHPShopText::b($this->category_array[$category]),'');

            foreach($dataArray as $row) {
                $price_array=array($row['price'],$row['price2'],$row['price3'],$row['price4'],$row['price5']);
                $price=PHPShopProductFunction::GetPriceValuta($row['id'],$price_array,$row['baseinputvaluta']);

                // Если цены показывать только после аторизации
                if(!empty($user_price_activate) and !$_SESSION['UsersId']) {
                    $price="~";
                }

                // Товар
                $this->print.=PHPShopText::tr($row['name'],$price." ".$valuta);
            }
        }
    }


    function category_array() {

        $PHPShopCategoryArray = new PHPShopCategoryArray();
        $Catalog=$PHPShopCategoryArray->getArray();
        $CatalogKeys=$PHPShopCategoryArray->getKey('id.parent_to');

        if(is_array($CatalogKeys))
            foreach($CatalogKeys as $cat=>$val) {
                $podcatalog_id = array_keys($CatalogKeys,$cat);
                if(count($podcatalog_id)==0) {
                    $parent=$Catalog[$cat]['parent_to'];
                    if ($this->category==$cat) {
                        $this->category_name=@$Catalog[$parent]['name']." / ".@$Catalog[$cat]['name'];
                    }

                    // Массив для вывода всех товаров
                    $this->category_array[$cat]=@$Catalog[$parent]['name']." / ".@$Catalog[$cat]['name'];
                }
            }
    }

    function category() {

        $this->category=$_GET['catId'];
        $this->category_array();

        // Безопасность
        if(!is_numeric($this->category)) {

            foreach($this->category_array as $key=>$val) {
                $this->product($key);
            }
        }
        else {
            $this->product($this->category);
        }
    }

    // Вывод результата
    function compile() {
        global $PHPShopSystem;

        if(!empty($this->print)) {

            PHPShopParser::set('name',$this->PHPShopSystem->getName());
            PHPShopParser::set('price',$this->print);
            PHPShopParser::set('date',date("d-m-y"));
            PHPShopParser::file('../../lib/templates/print/price.tpl');
        }
        else  $this->setError404();
    }

    /**
     * Генерация ошибки 404
     */
    function setError404() {
        header("HTTP/1.0 404 Not Found");
        header("Status: 404 Not Found");
    }

}

$PHPShopPricePrint = new PHPShopPricePrint();
$PHPShopPricePrint->category();
$PHPShopPricePrint->compile();
?>