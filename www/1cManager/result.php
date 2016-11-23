<?php

/**
 * Автономная синхронизация номенклатуры из 1С
 * @package PHPShopExchange
 * @author PHPShop Software
 * @version 1.8
 */
// Авторизация
include_once("login.php");
PHPShopObj::loadClass("readcsv");

$F_done = null;
$GetItemCreate = 0;
$GetItemUpdate = 0;
$GetCatalogCreate = 0;

// Номер ячейки характеристик для учета сдвига массива
$GLOBALS['option']['sort'] = 17;


// Персонализация
if (function_exists('mod_option')) {
    call_user_func_array('mod_option', array(&$GLOBALS['option']));
}

// Привязывает текущую характеристику к каталогу
function updateCatalog($parent_id, $charID) {
    global $SysValue;

    $sql2_3 = 'select sort from ' . $SysValue['base']['table_name'] . ' WHERE id="' . $parent_id . '"';
    $result2_3 = mysql_query($sql2_3);
    $num2_3 = mysql_num_rows(@$result2_3);
    if (!$num2_3)
        return false;
    $row2_3 = mysql_fetch_array($result2_3);
    $sorts = unserialize($row2_3['sort']);
    $sel = "";

    if (is_array($sorts)) {
        foreach ($sorts as $k => $v) {
            if ($charID == $v)
                $sel = "selected";
        }
    }

    // Проверяем не привязан ли к каталогу такой ID
    if ($sel != "selected") {
        $sorts[] = trim($charID);
        $ss = addslashes(serialize($sorts));
        $sql2_4 = 'UPDATE ' . $SysValue['base']['table_name'] . ' SET sort="' . $ss . '" WHERE id="' . $parent_id . '"';
        $result2_4 = mysql_query($sql2_4);
    }
    return true;
}

// Функция генерирует новые характеристики
function charsGenerator($parent_id, $CsvToArray) {

    global $SysValue;
    for ($i = $GLOBALS['option']['sort']; $i < count($CsvToArray); $i = $i + 2) { //Начинаем обрабатывать все ячейки после дополнительного каталога
        $charName = trim($CsvToArray[$i]);
        $charValues = trim($CsvToArray[$i + 1]);
        $charValues = split("&&", $charValues);

        //получаем идентификатор характеристики
        $sql2 = 'select id,name from ' . $SysValue['base']['table_name20'] . ' WHERE name like "' . $charName . '"';
        $result2 = mysql_query($sql2);
        $row2 = mysql_fetch_array($result2);
        $charID = $row2['id'];

        if (strlen($charName)) {
            $go = false;
            foreach ($charValues as $charValue) {
                $charValue = trim($charValue);
                if (strlen($charValue)) {
                    $go = true;
                }
            }
            unset($charValue);
            if ($go) {//Если есть хотя бы одно не пустое значение
                if (!$charID) { //Если характеристика не найдена, надо создать группу и характеристику
                    //Создаем группу
                    $sql2_1 = 'INSERT INTO ' . $SysValue['base']['table_name20'] . ' (name,category) VALUES("Группа ' . $charName . '","0")'; //Создаем группу
                    $result2_1 = mysql_query($sql2_1);
                    $group_id = mysql_insert_id(); //Получаем последний добавленный id - id группы
                    //Создаем характеристику, привязанную к группе
                    $sql2_2 = 'INSERT INTO ' . $SysValue['base']['table_name20'] . ' (name,category) VALUES("' . $charName . '","' . $group_id . '")'; //Создаем ХАР.
                    $result2_2 = mysql_query($sql2_2);
                    $charID = mysql_insert_id(); //Получаем последний добавленный id - id созданной характеристики

                    if (!(updateCatalog($parent_id, $charID))) { //Если при попытке привязки к основному каталогу тот не был найден, прекратить присвоение характеристик и удалить созданные
                        $sql2_3 = 'DELETE FROM ' . $SysValue['base']['table_name20'] . ' WHERE id=' . $group_id;
                        $result2_3 = mysql_query($sql2_3);
                        $sql2_4 = 'DELETE FROM ' . $SysValue['base']['table_name20'] . ' WHERE id=' . $charID;
                        $result2_4 = mysql_query($sql2_4);
                        $charID = false;
                    }
                } else {//Если характеристика найдена, просто пробуем привязать ее к каталогу товаров
                    if (!(updateCatalog($parent_id, $charID))) {
                        $charID = false;
                    }
                }
            }
        } else {
            $charID = false;
        }


        if ($charID) { //Если удалось получить  id характеристики (или создать) - работаем дальше над значениями
            foreach ($charValues as $charValue) {
                $charValue = trim($charValue);
                if (strlen($charValue)) {
                    $sql3 = 'select id,name from ' . $SysValue['base']['table_name21'] . ' WHERE (name like "' . $charValue . '") AND (category="' . $charID . '")'; //Получаем названия хар-к
                    $result3 = mysql_query($sql3);
                    $row3 = mysql_fetch_array($result3);
                    $id = $row3['id'];
                    if (!$id) { //Если НЕ удалось получить id искомого значения, значит надо добавить новое
                        $sql4 = 'INSERT INTO ' . $SysValue['base']['table_name21'] . ' (name,category) VALUES("' . $charValue . '","' . $charID . '")'; //Получаем назв. хар-к
                        $result4 = mysql_query($sql4);
                        $id = mysql_insert_id(); //Получаем последний добавленный id и он будет id привязанный к товару
                    }
                    if ($id) {
                        $resCharsArray[$charID][] = $id;
                    }
                }
            }
        }
    }
    return $resCharsArray;
}

// Обработка каталогов
class ReadCsvCatalog extends PHPShopReadCsv {

    var $CsvContent;
    var $ReadCsvRow;
    var $TableName;
    var $CsvToArray;
    var $ItemCreate = 0;

    function ReadCsvCatalog($file) {
        $this->CsvContent = parent::readFile($file);
        $this->TableName = $GLOBALS['SysValue']['base']['table_name'];
        parent::PHPShopReadCsv();
    }

    // Создаем новую запись
    function CreateCatalog($id) {
        $CsvToArray = $this->CsvToArray[$id];
        if (is_array($CsvToArray)) {
            $sql = "INSERT INTO " . $this->TableName . " SET
     id = '" . trim($CsvToArray[0]) . "',
     name = '" . parent::CleanStr($CsvToArray[1]) . "', 
     parent_to = '" . trim($CsvToArray[2]) . "' ";
            $result = mysql_query($sql);
            $this->ItemCreate++;
        }
    }

    function GetItemCreate() {
        return $this->ItemCreate;
    }

    // Вложенная проверка дерева каталогов
    function ChekTree($id) {
        $row = $this->CsvToArray;
        $parent = $row[$id][2];
        $CheckId = parent::CheckId($id);
        if (empty($CheckId))
            $this->CreateCatalog($id);
        if ($parent != 0) {
            $CheckIdParent = parent::CheckId($parent);
            if (empty($CheckIdParent))
                $this->ChekTree($parent);
        }
    }

}

// Обработка товаров
class ReadCsv1C extends PHPShopReadCsvPro {

    var $CsvContent;
    var $ReadCsvRow;
    var $TableName;
    var $Sklad_status;
    var $ObjCatalog, $ObjSystem;
    var $ItemCreate = 0;
    var $ItemUpdate = 0;
    var $ImageSrc = "jpg";

    function ReadCsv1C($CsvContent, $ObjCatalog, $ObjSystem) {
        $this->ImagePath = $GLOBALS['SysValue']['dir']['dir'] . "/UserFiles/Image/";
        $this->CsvContent = $CsvContent;
        $this->TableName = $GLOBALS['SysValue']['base']['table_name2'];
        $this->TableNameFoto = $GLOBALS['SysValue']['base']['table_name35'];
        $this->Sklad_status = $ObjSystem->getSerilizeParam("admoption.sklad_status");
        $this->ObjCatalog = $ObjCatalog;
        $this->ObjSystem = $ObjSystem;
        $this->GetIdValuta = PHPShopValuta::getAll();
        parent::PHPShopReadCsvPro();
        $this->DoUpdatebase();
    }

    // Кол-во созданных товаров
    function GetItemCreate() {
        return $this->ItemCreate;
    }

    // Кол-во измененных товаров
    function GetItemUpdate() {
        return $this->ItemUpdate;
    }

    // Кол-во созданных каталогов
    function GetCatalogCreate() {
        if ($this->ObjCatalog)
            $num = $this->ObjCatalog->GetItemCreate();
        else
            $num = 0;
        return $num;
    }

    // Путь к картинке
    function ImagePlus($img) {
        if (!empty($img))
            return $this->ImagePath . $img;
    }

    // Создаем каталоги
    function DoUpdatebase() {
        $CsvToArray = $this->CsvToArray;
        if (is_array($CsvToArray)) {
            foreach ($CsvToArray as $v) {
                $this->UpdateBase($v);
                $this->UpdateBaseCatalog($v[15]);
            }
        }
    }

    // Создаем каталоги
    function UpdateBaseCatalog($category) {
        if ($this->ObjCatalog)
            $this->ObjCatalog->ChekTree($category);
    }

    // Проверка кол-ва фото
    function GetNumFoto($id) {
        $sql = "select id from " . $this->TableNameFoto . " where parent=$id";
        $result = mysql_query($sql);
        return mysql_num_rows($result);
    }

    // Получения Ид товара по артикулу
    function getIdForImages($uid) {
        $sql = "select id from " . $this->TableName . " where uid='$uid' limit 1";
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result);
        return $row['id'];
    }

    // Обновление данных
    function UpdateBase($CsvToArray) {

        // Есть ли товары в базе
        if ($_REQUEST['create'] == "true")
            $CheckBase = parent::CheckUid($CsvToArray[0]);
        else
            $CheckBase = true;

        // Обновляем
        if (!empty($CheckBase)) {

            $sql = "UPDATE " . $this->TableName . " SET ";

            // Персонализация
            if (function_exists('mod_update')) {
                $sql.=call_user_func_array('mod_update', array(&$CsvToArray, __CLASS__, __FUNCTION__));
            }

            if ($this->ObjSystem->getSerilizeParam("1c_option.update_name") == 1 and !empty($CsvToArray[1]))
                $sql.="name='" . addslashes($CsvToArray[1]) . "', "; // название

            if ($this->ObjSystem->getSerilizeParam('1c_option.update_content') == 1 and !empty($CsvToArray[4]))
                $sql.="content='" . addslashes($CsvToArray[4]) . "', "; // краткое описание

            if ($this->ObjSystem->getSerilizeParam("1c_option.update_description") == 1 and !empty($CsvToArray[2]))
                $sql.="description='" . addslashes($CsvToArray[2]) . "', "; // подробное описание

            if ($this->ObjSystem->getSerilizeParam("1c_option.update_category") == 1 and !empty($CsvToArray[15]))
                $sql.="category='" . trim($CsvToArray[15]) . "', "; // категория

            $sql.="price='" . @$CsvToArray[7] . "', "; // цена 1
            // Склад
            switch ($this->Sklad_status) {

                case(3):
                    // 0- под заказ на сайте + нет вывода на Яндекс Маркет 
                    if ((int)$CsvToArray[19]==0){
                        $sql.="sklad='1', ";
                        $sql.="p_enabled='0', ";
                        $sql.="yml='0', ";
                        //1,10,11,2,3 - в наличии на сайте (нет галки "под заказ") + вывод на Яндекс Маркете в наличии 
                    } elseif ((int)$CsvToArray[19]==1 || (int)$CsvToArray[19]==10 || (int)$CsvToArray[19]==11 || (int)$CsvToArray[19]==2) {
                        $sql.="sklad='0', ";
                        $sql.="p_enabled='1', ";
                        $sql.="yml='1', ";
                    } elseif ((int)$CsvToArray[19]==3) {
                        $sql.="sklad='0', ";
                        $sql.="p_enabled='0', ";
                        $sql.="yml='1', ";
                    }
                    $sql.="enabled='1', ";
                    break;

                case(2):
                    // 0- под заказ на сайте + нет вывода на Яндекс Маркет 
                    if ((int)$CsvToArray[19]==0){
                        $sql.="p_enabled='0', ";
                        $sql.="yml='0', ";
                        //1,10,11,2,3 - в наличии на сайте (нет галки "под заказ") + вывод на Яндекс Маркете в наличии 
                    } elseif ((int)$CsvToArray[19]==1 || (int)$CsvToArray[19]==10 || (int)$CsvToArray[19]==11 || (int)$CsvToArray[19]==2) {
                        $sql.="p_enabled='1', ";
                        $sql.="yml='1', ";
                    } elseif ((int)$CsvToArray[19]==3) {
                        $sql.="p_enabled='0', ";
                        $sql.="yml='1', ";
                    }                    
                    $sql.="enabled='1', ";                        
                    break;

                default: $sql.="";
            }

            if (!empty($CsvToArray[3])) {
                $sql.="pic_small='" . $this->ImagePlus($CsvToArray[3]) . "_1s." . $this->ImageSrc . "',";
                $sql.="pic_big='" . $this->ImagePlus($CsvToArray[3]) . "_1." . $this->ImageSrc . "',";
            }

            $sql.="price2='" . @$CsvToArray[8] . "', "; // цена 2
            $sql.="price3='" . @$CsvToArray[9] . "', "; // цена 3
            $sql.="price4='" . @$CsvToArray[10] . "', "; // цена 4
            $sql.="price5='" . @$CsvToArray[11] . "', "; // цена 5
            $sql.="items='" . @$CsvToArray[18] . "', "; // склад @$CsvToArray[6]
            $sql.="stockgroup='" . (int)@$CsvToArray[19] . "', "; // склад @$CsvToArray[6]
			
            // Подчиненные товары
            if (is_numeric($CsvToArray[16]) and $CsvToArray[16] == 1) {
                $sql.="parent_enabled='1', ";
            } else {
                $sql.="parent_enabled='0', ";
                $sql.="parent='" . $CsvToArray[16] . "', ";
            }

            //if(!empty($CsvToArray[12]))
            //$sql.="weight='" . @$CsvToArray[12] . "', "; // вес

            $sql.="datas='" . date("U") . "' "; // дата изменения

            $sql.=" where uid='" . $CsvToArray[0] . "'";
            /*
			ob_start();
			echo $sql;

			$page = ob_get_contents();
			ob_end_clean();
			$file = "/uploading.log";
			$fw = fopen($file, "w+");
			fputs($fw,$page, strlen($page));
			fclose($fw);
            */
            $result = mysql_query($sql);
            $this->ItemUpdate++;

            // Добавляем картинки в галерею
            if (!empty($CsvToArray[3])) {
                $last_id = $this->getIdForImages($CsvToArray[0]);
                $ready_num_img = $this->GetNumFoto($last_id);
                $img_num = $CsvToArray[5] - $ready_num_img;
                $img_num = +$ready_num_img;
                while ($img_num < $CsvToArray[5]) {
                    $ImgName = $this->ImagePlus($CsvToArray[3]) . "_" . ($img_num + 1) . "." . $this->ImageSrc;
                    $sql = "INSERT INTO " . $this->TableNameFoto . " VALUES ('',$last_id,'$ImgName','$img_num','')";
                    $result = mysql_query($sql);
                    $img_num++;
                }
            }
            $sql = "SELECT vendor_array,vendor FROM " . $GLOBALS['SysValue']['base']['products']. " where uid='" . $CsvToArray[0] . "'"; //id=9317";
            $result = mysql_query($sql);
            $result=mysql_fetch_array($result);
            $resultvendor=$result[1];
            $result=unserialize($result[0]);
            //var_dump ($result,$resultvendor);
            // Обновляем характеристики
            //if ($this->ObjSystem->getSerilizeParam("1c_option.update_category") == 1 and $this->ObjSystem->getSerilizeParam("1c_option.update_sort") == 1 and !empty($CsvToArray[$GLOBALS['option']['sort']])) {
                //$resCharsArray = '';

                // Генератор характеристик
                $resCharsArray = charsGenerator($CsvToArray[15], $CsvToArray);
                $vendor = null;
                if (is_array($resCharsArray)) {
                    foreach ($resCharsArray as $k => $v) {
                        if (is_array($v)) {
                            foreach ($v as $o => $p) {
                                $vendor.="i" . $k . "-" . $p . "i";
                            }
                        } else {
                            $vendor.="i" . $k . "-" . $v . "i";
                        }
                    }
                }
                echo (string) stristr ( $resultvendor,'i186' );
                //закомментировать это в случае ошибки
                //начало вставки
                //если не существует самовывоза то добавляем его в случае 1,10,11
                if ((int)$CsvToArray[19]==1 || (int)$CsvToArray[19]==10 || (int)$CsvToArray[19]==11){
                   if ((!stristr ( $resultvendor,'i186' ))) 
                       $resultvendor.="i186-1200i";
                   //добавляем саму характеристику
                   $result[186][0] = '1200';
                } else
                //если существует самовывоз то удаляем в случае 0,2,3
                if ((int)$CsvToArray[19]==0 || (int)$CsvToArray[19]==2 || (int)$CsvToArray[19]==3){
                    if (stristr ( $resultvendor,'i186' ))
                        $resultvendor=str_replace ( "i186-1200i" , "" ,$resultvendor );
                   if (array_key_exists(186,$result))
                      $result[186] = array();
                   
                }
                //конец вставки 
                //var_dump($result,$resultvendor);
                
                $resSerialized = serialize($result);

                $sql = "UPDATE " . $this->TableName . " SET ";
                $sql.="vendor='" . $resultvendor. "', ";
                $sql.="vendor_array='" . $resSerialized . "' ";
                $sql.=" where uid='" . $CsvToArray[0] . "'";
                $result = mysql_query($sql);
            //}
        } else {
            // Создаем новый товар
            // Склад
            switch ($this->Sklad_status) {

                case(3):
                    // 0- под заказ на сайте + нет вывода на Яндекс Маркет 
                    if ((int)$CsvToArray[19]==0){
                        $sklad = 1;
                        $p_enabled=0;
                        $yml=0;
                        //1,10,11,2,3 - в наличии на сайте (нет галки "под заказ") + вывод на Яндекс Маркете в наличии 
                    } elseif ((int)$CsvToArray[19]==1 || (int)$CsvToArray[19]==10 || (int)$CsvToArray[19]==11 || (int)$CsvToArray[19]==2) {
                        $sklad = 0;
                        $p_enabled=1;
                        $yml=1;
                    } elseif ((int)$CsvToArray[19]==3) {
                        $sklad = 0;
                        $p_enabled=0;
                        $yml=0;
                    }
                    $enabled=1;
                    break;

                case(2):
                    if ((int)$CsvToArray[18]==0){
                        $p_enabled=0;
                        $yml=0;
                        //1,10,11,2,3 - в наличии на сайте (нет галки "под заказ") + вывод на Яндекс Маркете в наличии 
                    } elseif ((int)$CsvToArray[18]==1 || (int)$CsvToArray[18]==10 || (int)$CsvToArray[18]==11 || (int)$CsvToArray[18]==2) {
                        $p_enabled=1;
                        $yml=1;
                    } elseif ((int)$CsvToArray[18]==3) {
                        $p_enabled=0;
                        $yml=0;
                    }
                    $enabled=1;
                    break;
                default:
                    $sklad = 0;
                    $p_enabled=1;
                    $enabled = 1;
                    $yml=1;
                    break;
            }

            // Добавляем характеристики
            $vendor = null;
            $vendor_array = nyll;
            if ($this->ObjSystem->getSerilizeParam("1c_option.update_category") == 1 and !empty($CsvToArray[$GLOBALS['option']['sort']])) {
                $resCharsArray = null;

                // Генератор характеристик
                $resCharsArray = charsGenerator($CsvToArray[15], $CsvToArray);
                $resSerialized = serialize($resCharsArray);
                if (is_array($resCharsArray)) {
                    foreach ($resCharsArray as $k => $v) {
                        if (is_array($v)) {
                            foreach ($v as $o => $p) {
                                $vendor.="i" . $k . "-" . $p . "i";
                            }
                        } else {
                            $vendor.="i" . $k . "-" . $v . "i";
                        }
                    }
                }
                
                $vendor_array = serialize($resCharsArray);
            }

            $sql = "INSERT INTO " . $this->TableName . " SET ";

            // Персонализация
            if (function_exists('mod_insert')) {
                $sql.=call_user_func_array('mod_insert', array(&$CsvToArray, __CLASS__, __FUNCTION__));
            }

            // Родительская категория
            if ($this->ObjSystem->getSerilizeParam("1c_option.update_category") == 1 and !empty($CsvToArray[15]))
                $sql.="category='" . trim($CsvToArray[15]) . "',";

            $sql.="name='" . addslashes(trim($CsvToArray[1])) . "',
            description='" . addslashes($CsvToArray[2]) . "',
            content='" . addslashes($CsvToArray[4]) . "',
            price='" . $CsvToArray[7] . "',
            sklad='" . $sklad . "',
            p_enabled='" . $p_enabled . "',
            enabled='" . $enabled . "',
            uid='" . $CsvToArray[0] . "',
            yml='".$yml."',
            datas='" . date("U") . "',
            vendor='" . $vendor . "',
            vendor_array='" . $vendor_array . "',";

            if (!empty($CsvToArray[3]))
                $sql.="pic_small='" . $this->ImagePlus($CsvToArray[3]) . "_1s." . $this->ImageSrc . "',
            pic_big='" . $this->ImagePlus($CsvToArray[3]) . "_1." . $this->ImageSrc . "',";

            // Подчиненные товары
            if (is_numeric($CsvToArray[16]) and $CsvToArray[16] == 1) {
                $sql.="parent_enabled='1', ";
            } else {
                $sql.="parent_enabled='0', ";
                $sql.="parent='" . $CsvToArray[16] . "', ";
            }

            $sql.="items='" . $CsvToArray[18] . "',
            weight='" . $CsvToArray[12] . "',
            price2='" . $CsvToArray[8] . "',
            price3='" . $CsvToArray[9] . "',
            price4='" . $CsvToArray[10] . "',
            price5='" . $CsvToArray[11] . "',
            baseinputvaluta='" . $this->GetIdValuta[$CsvToArray[14]] . "',
            ed_izm='" . $CsvToArray[13] . "'";//$CsvToArray[6]
            $result = mysql_query($sql);
            $this->ItemCreate++;

            // Добавляем картинки в галерею
            $img_num = 1;
            if (!empty($CsvToArray[3])) {
                //$last_id=$this->getIdForImages($CsvToArray[0]);
                $last_id = mysql_insert_id();
                while ($img_num <= $CsvToArray[5]) {
                    $ImgName = $this->ImagePlus($CsvToArray[3]) . "_" . $img_num . "." . $this->ImageSrc;
                    $sql = "INSERT INTO " . $this->TableNameFoto . " VALUES ('',$last_id,'$ImgName','$img_num','')";
                    $result = mysql_query($sql);
                    $img_num++;
                }
            }
        }
    }

}

// форматирование даты
if (preg_match("/[^(0-9)|(\-)]/", $_GET['date']))
    $date = "";
else
    $date = '16-11-2016-22-14';//$_GET['date'];

$path = "sklad";
$dir = $path . "/" . $date;

// Читаем категории
if ($_GET['create_category'] == "true")
    $ReadCsvCatalog = new ReadCsvCatalog($dir . "/tree.csv");
else
    $ReadCsvCatalog = false;

// Подключаем настройки магазина
$PS = new PHPShopSystem();
$upload='upload_0.csv';//$_GET['files'];//"all"
// Смотрим папку
if ($upload == "all" and is_dir($dir))
    if (@$dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {

            if ($file != "." and $file != ".." and $file != "tree.csv")
                $list_file[] = $file;
        }
        closedir($dh);
    }
if (is_file("./" . $dir . "/" . $upload)) {
    $list_file[] = $upload;
}

// Тестирование
if (isset($error)) {
    if (is_array($list_file))
        $list_file[$error] = "";
}

if (is_array($list_file))
    foreach ($list_file as $val) {

        // Включаем таймер
        $time = explode(' ', microtime());
        $start_time = $time[1] + $time[0];

        //$fp = fopen($dir."/".$val, "r");
        $fp = file($dir . "/" . $val);
        if ($fp) {
            // Читаем файл
            $ReadCsv = new ReadCsv1C($fp, $ReadCsvCatalog, $PS);
            $F_done.=$val . ";";
            $GetItemCreate+=$ReadCsv->GetItemCreate();
            $GetItemUpdate+=$ReadCsv->GetItemUpdate();
            $GetCatalogCreate+=$ReadCsv->GetCatalogCreate();

            // Результат
            if ($upload != "all")
                echo $date . ";" . $F_done . "
" . $GetItemCreate . ";" . $GetItemUpdate . ";" . $GetCatalogCreate . ";";

            // Выключаем таймер
            $time = explode(' ', microtime());
            $seconds = ($time[1] + $time[0] - $start_time);
            $seconds = substr($seconds, 0, 6);

            // Пишем лог
            mysql_query("INSERT INTO " . $PHPShopBase->getParam('base.table_name12') . " VALUES ('','" . date("U") . "','$date','$val ','$seconds')");
        }
    }
else
    exit("Не могу прочитать файл " . $dir . "/" . $val);

if ($upload == "all")
    echo $date . ";" . $F_done . "
" . $GetItemCreate . ";" . $GetItemUpdate . ";" . $GetCatalogCreate . ";";
?>