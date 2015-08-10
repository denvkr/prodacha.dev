<?php

session_start();

$_classPath="../phpshop/";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("product");

$PHPShopValutaArray = new PHPShopValutaArray();
$PHPShopSystem = new PHPShopSystem();

class PHPShopPriceSave {
    var $csv;

    function PHPShopPriceSave() {
        $this->debug=false;
        $this->objBase=$GLOBALS['SysValue']['base']['products'];
    }

    function select() {
        global $PHPShopSystem,$PHPShopValutaArray;
        if(is_numeric(@$_GET['catId'])) $str=" (category=$_GET[catId] or dop_cat LIKE '%#$_GET[catId]#%') and ";
        else $str=null;

        // Системная валюта
        $system_currency=$PHPShopSystem->getValue('dengi');
        $ValutaArray = $PHPShopValutaArray->getArray();
        $valuta=$ValutaArray[$system_currency]['code'];

        $PHPShopOrm = new PHPShopOrm();
        $PHPShopOrm->sql="select * from ". $this->objBase." where ".$str." enabled='1'";
        $PHPShopOrm->comment=__CLASS__.'.'.__FUNCTION__;
        $PHPShopOrm->debug=$this->debug;
        $dataArray=$PHPShopOrm->select();

        if(is_array($dataArray))
            foreach($dataArray as $row) {
                $price_array=array($row['price'],$row['price2'],$row['price3'],$row['price4'],$row['price5']);
                $price=PHPShopProductFunction::GetPriceValuta($row['id'],$price_array,$row['baseinputvaluta']);

                // Если цены показывать только после аторизации
                if(!empty($user_price_activate) and !$_SESSION['UsersId']) {
                    $price="~";
                }

                $this->csv.=$row['uid'].';'.$row['name'].';'.$price.' '.$valuta.'
';
            }
    }

    // GZIP сжатие
    function gzcompressfile($source,$level=false) {
        $dest=$source.'.gz';
        $mode='wb'.$level;
        $error=false;
        if(@$fp_out=gzopen($dest,$mode)) {
            if(@$fp_in=fopen($source,'rb')) {
                while(!feof($fp_in))
                    gzwrite($fp_out,fread($fp_in,1024*512));
                fclose($fp_in);
            }
            else $error=true;
            @gzclose($fp_out);
            unlink($source);
            rename($dest, $source.'.bz2');
        }
        else $error=true;
        if($error) return false;
        else return $dest;
    }

    // Вывод результата
    function compile() {
        $file="base_".date("d_m_y_His").".csv";
        @$fp = fopen("price/".$file, "w+");
        if ($fp) {
            fputs($fp, $this->csv);
            fclose($fp);
            $sorce="price/".$file;
        }
        // Пишес  GZIP
        if(!empty($_GET['gzip'])) {
            $this->gzcompressfile($sorce);
            header("Location: price/".$file.".bz2");
        }
        else {
            header("Location: ".$sorce);
        }
    }
}

$PHPShopPriceSave = new PHPShopPriceSave();
$PHPShopPriceSave->select();
$PHPShopPriceSave->compile();
?>