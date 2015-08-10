<?php
/**
 * Модуль Partner. 
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopModules
 */

$_classPath="../../../../";
include($_classPath."phpshop/class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("xml");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("basexml");
PHPShopObj::loadClass("string");

// Подключаем БД
$PHPShopBase=&new PHPShopBase($_classPath."phpshop/inc/config.ini");

// Пример запроса
$_POST['sql_test']='<?xml version="1.0" encoding="windows-1251"?>
<phpshop><sql><from>table_name2</from>
<method>select</method>
<vars>name,id,items,price,ed_izm,pic_small,category,newtip,spec,baseinputvaluta,price2</vars>
<where>category=11 and enabled="1"</where>
<order>num</order><limit>1000</limit></sql></phpshop>';



class PHPShopHtmlCatalog extends PHPShopBaseXml {

    function PHPShopHtmlCatalog() {
        $this->debug=false;
        $this->true_method=array('select','option');
        $this->true_from=array('table_name2');
        $this->code=$_GET['code'];
        $this->postsql($_GET['cat'],$_GET['limit']);
        parent::PHPShopBaseXml();
    }
    
    /**
     * Перекодировка
     */
    function code($str){
        switch($this->code){
            case('utf-8'):
                $str=PHPShopString::win_utf8($str);
                break;
            default: 
                break;
        }
        return trim($str);
    }

    function postsql($cat=false,$limit=10) {
        if(empty($cat)) $where='spec="1" and enabled="1"';
        else $where='category='.$cat.' and enabled="1" and parent_enabled="0"';

        $_POST['sql']='<?xml version="1.0" encoding="windows-1251"?>
<phpshop><sql><from>table_name2</from>
<method>select</method>
<vars>name,id,items,price,ed_izm,pic_small,category,newtip,spec,baseinputvaluta,price2</vars>
<where>'.$where.'</where>
<order>num</order><limit>'.$limit.'</limit></sql></phpshop>';
    }

    function addvar($str) {
        return 'PHPShopXml'.$_GET['id'].'+="'.$str.'";
';
    }

    function admin() {
        return true;
    }

    function compile() {
        if(is_array($this->data)) {
            $result='
var PHPShopXml'.$_GET['id'].'=\'<phpshop>\'; ';
            foreach($this->data as $row) {
                $result.=$this->addvar('<row>');
                if(is_array($row))
                    foreach($row as $key=>$val) {

                        // Корректируем теги на наличие первой цифры
                        if(is_numeric($key{0})) {
                            $key=substr($key, 1);
                        }

                        if(preg_match("(\<(/?[^\>]+)\>)",$val) or strstr($val,'&'))
                            $result.=$this->addvar('<'.$key.'><![CDATA['.$this->code($val).']]></'.$key.'>');
                        else   $result.=$this->addvar('<'.$key.'>'.trim($this->is_serialize($val)).'</'.$key.'>');
                    }
                $result.=$this->addvar('</row>');
            }

            $result.='PHPShopXml'.$_GET['id'].'+="</phpshop>";
if(window.PHPShopXmlManager'.$_GET['obj'].'){
  PHPShopXmlManager'.$_GET['obj'].'.tmp(PHPShopXml'.$_GET['id'].');
  PHPShopXmlManager'.$_GET['obj'].'.display("'.$_GET['id'].'");
}
';
            echo $result;
        }
    }
}

$PHPShopHtmlCatalog = &new PHPShopHtmlCatalog();
?>