<?php

$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("file");
PHPShopObj::loadClass("date");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("string");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();
$PHPShopBase->setLocale();

$PHPShopSystem = new PHPShopSystem();

$option['sklad_status'] = $PHPShopSystem->getSerilizeParam('admoption.sklad_status');

function updateCatalog($parent_id, $charID) { //Функция привязывает текущую характеристику к каталогу
    global $SysValue; //Глобальные переменные
    $sql2_3 = 'select sort from ' . $SysValue['base']['table_name'] . ' WHERE id="' . $parent_id . '"'; //Получаем названия хар-к
    $result2_3 = mysql_query($sql2_3);
    $num2_3 = mysql_num_rows(@$result2_3);
    if (!$num2_3)
        return false; //Если категория отсутствует, сделать возврат
    $row2_3 = mysql_fetch_array($result2_3);
    $sorts = unserialize($row2_3['sort']);
    $sel = ""; //Обнуляем привязчик
    if (is_array($sorts)) {
        foreach ($sorts as $k => $v) {
            if ($charID == $v)
                $sel = "selected";
        }
    }
    //Проверяем не привязан ли к каталогу такой ID
    if ($sel != "selected") {
        $sorts[] = trim($charID);
        $ss = addslashes(serialize($sorts));
        $sql2_4 = 'UPDATE ' . $SysValue['base']['table_name'] . ' SET sort="' . $ss . '" WHERE id="' . $parent_id . '"'; //обновляем кат-г
        $result2_4 = mysql_query($sql2_4);
    } //Если не привязан, привязываем
    return true;
}

//Конец Функция привязывает текущую характеристику к каталогу

function charsGenerator($parent_id, $CsvToArray, $addcats) {//Функция генерирует новые характеристики, значения характеристик, создает группы характеристик, и связывает из с каталогами. Отдает полученный массив характеристик для товара
    global $_SESSION, $_REQUEST, $SysValue, $testValue; //Глобальные переменные


    $addcats = split("#", $addcats); //Готовим массив дополнительных каталогов
    if (is_array($addcats)) {
        foreach ($addcats as $id => $addcat) {
            $addcat = trim($addcat);
            if ($addcat == "")
                unset($addcats[$id]);
        }
    }

    for ($i = 16; $i < count($CsvToArray); $i = $i + 2) { //Начинаем обрабатывать все ячейки после дополнительного каталога
        $charName = trim($CsvToArray[$i]);
        $charValues = trim($CsvToArray[$i + 1]); //Получаем значения
        $charValues = split("&&", $charValues); //Разбиваем && список в массив
        //получаем идентификатор характеристики
        $sql2 = 'select id,name from ' . $SysValue['base']['table_name20'] . ' WHERE name like "' . $charName . '"'; //Получаем названия хар-к
        $result2 = mysql_query($sql2);
        $row2 = mysql_fetch_array($result2);
        $charID = $row2['id'];

        //ПРОПУЩЕН БАГ! если в результате какой-либо махинации не была создана группа характеристики или разрушена привязка к этой группе, она не будет создана. Хотя хар-ки будут работать
        if ((strlen($charName)) && ($parent_id != "1000002")) { //Если имя характеристики имеет длинну И категория НЕ равна временной
            //Исправляем БАГ! проверяем до создания характеристик и присвоения, что юзер пришлет хотя бы одно не пустое значение
            $go = false;
            foreach ($charValues as $charValue) { //Работаем с каждым значением
                $charValue = trim($charValue);
                if (strlen($charValue)) { //Если полученное значение имеет длину
                    $go = true;
                }
            }
            unset($charValue); //Удаляем переменную.
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
                } else {//Если характеристика найдена, просто пробуем привязать ее к  каталогу товаров
                    if (!(updateCatalog($parent_id, $charID))) {
                        $charID = false;
                    }//Если при попытке привязке к каталогу тот не был найден, прекратить присвоение характеристик
                }//Конец если  характеристика найдена
            }//Конец Если есть хотя бы одно не пустое значение
        } else { //Если нет, то прекратить присвоение
            $charID = false;
        }//Конец Если НЕ (имя характеристики имеет длинну И категория НЕ равна временной)

        if ($charID) { //Если удалось получить  id характеристики (или создать) - работаем дальше над значениями
            if (count($addcats) > 0) { //Если прислали дополнительные категории (и не обнулили их при анализе чекбоксов)
                foreach ($addcats as $addcat) {
                    updateCatalog($addcat, $charID);
                }//Привязываем полученную характеристику к  каждой дополнительной категории товаров
            }

            foreach ($charValues as $charValue) { //Работаем с каждым значением
                $charValue = trim($charValue);
                if (strlen($charValue)) { //Если полученное значение имеет длину
                    $sql3 = 'select id,name from ' . $SysValue['base']['table_name21'] . ' WHERE (name like "' . $charValue . '") AND (category="' . $charID . '")'; //Получаем названия хар-к
                    //					$sql3='select id,name from '.$SysValue['base']['table_name21'].' WHERE (name like "'.$charValue.'")'; //Получаем названия хар-к

                    $result3 = mysql_query($sql3);
                    $row3 = mysql_fetch_array($result3);
                    $id = $row3['id'];
                    if (!$id) { //Если НЕ удалось получить id искомого значения, значит надо добавить новое
                        $sql4 = 'INSERT INTO ' . $SysValue['base']['table_name21'] . ' (name,category) VALUES("' . $charValue . '","' . $charID . '")'; //Получаем назв. хар-к
                        $result4 = mysql_query($sql4);
                        $id = mysql_insert_id(); //Получаем последний добавленный id и он будет id привязанный к товару
                    }//КОНЕЦ Если НЕ удалось получить id искомого значения, значит надо добавить новое
                    //$testValue2.='('.$id.')';	//DEBUG!!
                    //Формируем массив из id
                    if ($id) {
                        $resCharsArray[$charID][] = $id;
                    }
                    //$ress=print_r($resCharsArray,1); //DEBUG!!
                    //$testValue2.='('.$ress.')';	//DEBUG!!
                }//КОНЕЦ Если полученное значение имеет длину
            } //Конец перебора Значений характеристик
        } //Конец если удалось получить id характеристики
    } //Конец обработки всех ячеек после старых характеристик
    //*/
    return $resCharsArray;
}

//Конец Функция генерирует новые характеристики...

class ReadCsv1C {

    var $CsvContent;
    var $ReadCsvRow;
    var $TableName;
    var $Sklad_status;
    var $ImagePath = ""; // "/UserFiles/Image/";
    var $TotalUpdate = 0;
    var $TotalCreate = 0;
    var $fp;
    var $CsvToArray;

    function CsvToArray() {
        $fstat = fstat($this->fp);
        while ($data = fgetcsv($this->fp, $fstat['size'], ";", '"')) {
            $OutArray[] = $data;
        }

        array_shift($OutArray);
        return $OutArray;
    }

    function ReadCsv1C($CsvContent, $table_name2, $sklad_status) {
        $this->fp = $CsvContent;
        $this->CsvToArray = $this->CsvToArray();
        $this->TableName = $table_name2;
        $this->Sklad_status = $sklad_status;
    }

    function DoUpdatebase1() {
        $return = null;
        foreach ($this->CsvToArray as $v)
            $return.=$this->PrintResultDo($v);
        return $return;
    }

    function DoUpdatebase2() {
        foreach ($this->CsvToArray as $v)
            $this->UpdateBase($v);
    }

    function Zero($a) {
        if ($a != 0 or !empty($a))
            return 1;
        else
            return 0;
    }

    function CheckBase($uid) {// Проверяем есть ли товар
        $sql = "select id from " . $this->TableName . " where id='$uid'";
        $result = mysql_query($sql);
        @$num = mysql_num_rows(@$result);
        return @$num;
    }

    function ImagePlus($img) {// Путь к картинке
        $dis = $this->ImagePath . $img;
        return $dis;
    }

    function UpdateBase($CsvToArray) {
        global $_SESSION, $_REQUEST;

        $CheckBase = $this->CheckBase($CsvToArray[0]);


        if (!empty($CheckBase) and $CsvToArray[0] != "") {// Обновляем
            $sql = "UPDATE " . $this->TableName . " SET ";

            // Отсеиваем поля
            if ($_REQUEST['tip'][1] == 1)
                $sql.="name='" . str_replace("|", ";", trim($CsvToArray[1])) . "', ";
            if ($_REQUEST['tip'][14] == 1) {

                // Категория
                if (!empty($CsvToArray[14]))
                    $parent_id = $CsvToArray[14];
                else
                    $parent_id = "1000002";
                $sql.="category='" . $parent_id . "', "; // категория
            }
// описание краткое
            if ($_REQUEST['tip'][2] == 1)
                $sql.="description='" . addslashes($CsvToArray[2]) . "', ";
            if ($_REQUEST['tip'][3] == 1)
                $sql.="pic_small='" . $this->ImagePlus($CsvToArray[3]) . "', "; // маленькая картинка
// подробное описание
            if ($_REQUEST['tip'][4] == 1)
                $sql.="content='" . addslashes($CsvToArray[4]) . "', ";
            if ($_REQUEST['tip'][5] == 1)
                $sql.="pic_big='" . $this->ImagePlus($CsvToArray[5]) . "', "; // большая картинка
            if ($_REQUEST['tip'][6] == 1)
                $sql.="price='" . PHPShopString::toFloat($CsvToArray[7], true) . "', "; // цена 1
            if ($_REQUEST['tip'][17] == 1) {
                $sql.="dop_cat='" . $CsvToArray[15] . "', "; //  дополнительные каталоги
                $addcats = $CsvToArray[15];
            } else {
                $addcats = false;
            }
// Склад
            if ($_REQUEST['tip'][11] == 1) {
                switch ($this->Sklad_status) {

                    case(3):
                        if ($CsvToArray[6] < 1)
                            $sql.="sklad='1', ";
                        else
                            $sql.="sklad='0', ";
                        break;

                    case(2):
                        if ($CsvToArray[6] < 1)
                            $sql.="enabled='0', ";
                        else
                            $sql.="enabled='1', ";
                        break;

                    default: $sql.="";
                }
            }

            if ($_REQUEST['tip'][13] == 1)
                $sql.="uid='" . trim($CsvToArray[13]) . "', "; // артикул
            if ($_REQUEST['tip'][7] == 1)
                $sql.="price2='" . PHPShopString::toFloat($CsvToArray[8], true) . "', "; // цена 2
            if ($_REQUEST['tip'][8] == 1)
                $sql.="price3='" . PHPShopString::toFloat($CsvToArray[9], true) . "', "; // цена 3
            if ($_REQUEST['tip'][9] == 1)
                $sql.="price4='" . PHPShopString::toFloat($CsvToArray[10], true) . "', "; // цена 4
            if ($_REQUEST['tip'][10] == 1)
                $sql.="price5='" . PHPShopString::toFloat($CsvToArray[11], true) . "', "; // цена 5
            if ($_REQUEST['tip'][11] == 1)
                $sql.="items='" . $CsvToArray[6] . "', "; // склад
            if ($_REQUEST['tip'][12] == 1)
                $sql.="weight='" . $CsvToArray[12] . "', "; // вес

            $sql.=" datas ='" . date("U") . "' ";

            $sql.=" where id='" . $CsvToArray[0] . "'";
            $result = mysql_query($sql); //Обработка товара!!
            $this->TotalUpdate++;

// $testValue2='start';
            if ($_REQUEST['tip'][15] == 1) {// 16 характеристики 2.0
                $resCharsArray = ''; //Опустошаем массив
                $resCharsArray = charsGenerator($parent_id, $CsvToArray, $addcats); //Вызываем генератор характеристик
                //Обрабатываем получившийся массив
                $resSerialized = serialize($resCharsArray);
                $vendor = '';
                if (is_array($resCharsArray)) {
                    foreach ($resCharsArray as $k => $v) {
                        if (is_array($v)) {
                            foreach ($v as $o => $p) {
                                @$vendor.="i" . $k . "-" . $p . "i";
                            }
                        } else {
                            @$vendor.="i" . $k . "-" . $v . "i";
                        }
                    }
                }
                $sql = "UPDATE " . $this->TableName . " SET ";
                $sql.="vendor='" . $vendor . "', ";
                $sql.="vendor_array='" . $resSerialized . "' ";
                $sql.=" where id='" . $CsvToArray[0] . "'";
                $result = mysql_query($sql); //Обработка товара!!
            }//Конец если Характеристики 2.0
        } else {
            //// Создаем новый товар
            // Склад
            if ($_REQUEST['tip'][11] == 1) {
                switch ($this->Sklad_status) {

                    case(3):
                        if ($CsvToArray[6] < 1)
                            $sklad = 1;
                        else
                            $sklad = 0;
                        break;

                    case(2):
                        if ($CsvToArray[6] < 1)
                            $enabled = 0;
                        else
                            $enabled = 1;
                        break;

                    default:
                        $sklad = 0;
                        $enabled = 1;
                        break;
                }
            }

// Отсеиваем поля
            if ($_REQUEST['tip'][2] != 1)
                $CsvToArray[2] = ""; // описание краткое
            if ($_REQUEST['tip'][3] != 1)
                $CsvToArray[3] = ""; // маленькая картинка
            if ($_REQUEST['tip'][4] != 1)
                $CsvToArray[4] = ""; // подробное описание
            if ($_REQUEST['tip'][5] != 1)
                $CsvToArray[5] = ""; // большая картинка
            if ($_REQUEST['tip'][6] != 1)
                $CsvToArray[7] = ""; // цена 1
            if ($_REQUEST['tip'][7] != 1)
                $CsvToArray[8] = ""; // цена 2
            if ($_REQUEST['tip'][8] != 1)
                $CsvToArray[9] = ""; // цена 3
            if ($_REQUEST['tip'][9] != 1)
                $CsvToArray[10] = ""; // цена 4
            if ($_REQUEST['tip'][10] != 1)
                $CsvToArray[11] = ""; // цена 5
            if ($_REQUEST['tip'][11] != 1)
                $CsvToArray[6] = ""; // склад
            if ($_REQUEST['tip'][12] != 1)
                $CsvToArray[12] = ""; // вес
            if ($_REQUEST['tip'][13] != 1)
                $CsvToArray[13] = ""; // 13 артикул
//if($_REQUEST['tip'][17] != 1) $CsvToArray[15]="";// дополнительные каталоги
            if ($_REQUEST['tip'][17] == 1) {
                $addcats = $CsvToArray[15];
            } else {
                $addcats = "";
                $CsvToArray[15] = ""; // дополнительные каталоги
            }
            if ($_REQUEST['tip'][14] == 1) { // 14 категория
                // Категория
                if (!empty($CsvToArray[14]))
                    $parent_id = $CsvToArray[14];
                else
                    $parent_id = "1000002";
            }else
                $parent_id = "1000002";

            if ($_REQUEST['tip'][15] == 1) {// 16 характеристики 2.0
                $resCharsArray = ''; //Опустошаем массив
                $resCharsArray = charsGenerator($parent_id, $CsvToArray, $addcats); //Вызываем генератор характеристик
                //Обрабатываем получившийся массив
                $resSerialized = serialize($resCharsArray);
                $vendor = '';
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
            }//Конец если Характеристики 2.0


            $sql = "INSERT INTO " . $this->TableName . " SET 
            category='" . $parent_id . "',
            name='" . trim($CsvToArray[1]) . "',
            description='" . $CsvToArray[2] . "',
            content='" . $CsvToArray[4] . "',
            price='" . PHPShopString::toFloat($CsvToArray[7], true) . "',
            sklad='" . $sklad . "',
            p_enabled='" . $this->Zero($CsvToArray[9]) . "',
            enabled='" . $enabled . "',
            uid='" . $CsvToArray[13] . "',
            yml='1',
            datas='" . date("U") . "',
            vendor='" . $vendor . "',
            vendor_array='" . $vendor_array . "',
            pic_small='" . $this->ImagePlus($CsvToArray[3]) . "',
            pic_big='" . $this->ImagePlus($CsvToArray[5]) . "',
            items='" . $CsvToArray[6] . "',
            weight='" . $CsvToArray[12] . "',
            price2='" . PHPShopString::toFloat($CsvToArray[8], true) . "',
            price3='" . PHPShopString::toFloat($CsvToArray[9], true) . "',
            price4='" . PHPShopString::toFloat($CsvToArray[10], true) . "',
            price5='" . PHPShopString::toFloat($CsvToArray[11], true) . "',
            baseinputvaluta='" . $_REQUEST['tip'][16] . "',
            dop_cat='" . $CsvToArray[15] . "'";

            $result = mysql_query($sql);
            $this->TotalCreate++;
        }
    }

    function PrintResultDo($CsvToArray) {
        static $n;

        // Выделение четных строк
        if ($n % 2 == 0) {
            $style_r = null;
        } else {
            $style_r = ' line2';
        }

        $disp = '<tr class="row ' . $style_r . '" id="r' . $n . '" onmouseover="PHPShopJS.rowshow_on(this)" onmouseout="PHPShopJS.rowshow_out(this,\'' . $style_r . '\')">';

        $disp.= "
 <td align=center>" . (@$n + 1) . "</td>
	 <td align=center>" . $CsvToArray[0] . "</td>
	 <td >" . $CsvToArray[1] . "</td>
	 <td align=center>" . $CsvToArray[7] . "</td>
	 <td align=center>" . $CsvToArray[8] . "</td>
	 <td align=center>" . $CsvToArray[9] . "</td>
	 <td align=center>" . $CsvToArray[10] . "</td>
	 <td align=center>" . $CsvToArray[11] . "</td>
	 <td align=center>" . $CsvToArray[6] . "</td>
	 <td align=center>" . $CsvToArray[12] . "</td>
	</tr>
	";
        $n++;
        return $disp;
    }

}

//Класс

function Ors($n) {
    if ($n == 0)
        return "<font color=FF0000>нет</font>";
    elseif ($n == 1)
        return "да";
    else
        return "non";
}

// Проверка расширения файла
function getExt($sFileName) {//ffilter
    $sTmp = $sFileName;
    while ($sTmp != "") {
        $sTmp = strstr($sTmp, ".");
        if ($sTmp != "") {
            $sTmp = substr($sTmp, 1);
            $sExt = $sTmp;
        }
    }
    $pos = stristr($sFileName, "php");
    $pos2 = stristr($sFileName, "phtm");
    if ($pos === false and $pos2 === false)
        return strtolower($sExt);
}

require_once "../../lib/JsHttpRequest/JsHttpRequest.php";
$JsHttpRequest = new JsHttpRequest("windows-1251");

// Расширение
$_FILES['file']['ext'] = getExt($_FILES['file']['name']);

if ($_REQUEST['page'] == "predload" and $_FILES['file']['ext'] == "csv") {

// Загружаем
    $copy_file = "../csv/" . @$_FILES['file']['name'];
    if (move_uploaded_file(@$_FILES['file']['tmp_name'], $copy_file))
        if (is_file($copy_file)) {
            $CsvContent = fopen($copy_file, "r");
            $ReadCsv = new ReadCsv1C($CsvContent, $PHPShopBase->getParam('base.products'), $option['sklad_status']);
            fclose($CsvContent);
            $interface.='
<div id=interfacesWin name=interfacesWin align="left" style="width:100%;height:580;overflow:auto"> 
<TABLE  cellSpacing=0 cellPadding=0 width="100%"><TBODY>
<TR>
<TD vAlign=top>
<table width="100%" cellpadding="0" cellspacing="1" class="sortable" id="sort">
<tr>
    <td id="pane" width="50">№</td>
	<td id="pane"><span name=txtLangs id=txtLangs>ID товара</span></td>
	<td id="pane"><span name=txtLangs id=txtLangs>Название товара</span></td>
	<td id="pane"><span name=txtLangs id=txtLangs>Цена 1</span></td>
	<td id="pane"><span name=txtLangs id=txtLangs>Цена 2</span></td>
	<td id="pane"><span name=txtLangs id=txtLangs>Цена 3</span></td>
	<td id="pane"><span name=txtLangs id=txtLangs>Цена 4</span></td>
	<td id="pane"><span name=txtLangs id=txtLangs>Цена 5</span></td>
	<td id="pane"><span name=txtLangs id=txtLangs>Склад</span></td>
	<td id="pane"><span name=txtLangs id=txtLangs>Вес</span></td>
</tr>
' . $ReadCsv->DoUpdatebase1() . '
</table>
</TD></TR><form method=post action="" encType=multipart/form-data name=forma2></TBODY></TABLE>
</div>
<div align="center" style="padding-top:20">
<button style="WIDTH: 17em; HEIGHT: 2.3em" onclick="DoReload(\'csv_base\')">
<img src="img/icon-setup2.gif" alt="" width="16" height="16" border="0" align="absmiddle" hspace="5">
<span name=txtLangs id=txtLangs>Выбрать другой файл</span></button>
&nbsp;&nbsp;
<button style="WIDTH: 17em; HEIGHT: 2.3em" onclick="DoLoadBase(null,\'load\',\'' . $_FILES['file']['name'] . '\')">
<img src="img/icon-setup.gif" alt="" width="16" height="16" border="0" align="absmiddle" hspace="5">
<span name=txtLangs id=txtLangs>Принять изменения</span></button>
<input type="hidden" id="tip_1" value="' . $_REQUEST['tip'][1] . '">
<input type="hidden" id="tip_2" value="' . $_REQUEST['tip'][2] . '">
<input type="hidden" id="tip_3" value="' . $_REQUEST['tip'][3] . '">
<input type="hidden" id="tip_4" value="' . $_REQUEST['tip'][4] . '">
<input type="hidden" id="tip_5" value="' . $_REQUEST['tip'][5] . '">
<input type="hidden" id="tip_6" value="' . $_REQUEST['tip'][6] . '">
<input type="hidden" id="tip_7" value="' . $_REQUEST['tip'][7] . '">
<input type="hidden" id="tip_8" value="' . $_REQUEST['tip'][8] . '">
<input type="hidden" id="tip_9" value="' . $_REQUEST['tip'][9] . '">
<input type="hidden" id="tip_10" value="' . $_REQUEST['tip'][10] . '">
<input type="hidden" id="tip_11" value="' . $_REQUEST['tip'][11] . '">
<input type="hidden" id="tip_12" value="' . $_REQUEST['tip'][12] . '">
<input type="hidden" id="tip_13" value="' . $_REQUEST['tip'][13] . '">
<input type="hidden" id="tip_14" value="' . $_REQUEST['tip'][14] . '">
<input type="hidden" id="tip_15" value="' . $_REQUEST['tip'][15] . '">
<input type="hidden" id="tip_16" value="' . $_REQUEST['tip'][16] . '">
<input type="hidden" id="tip_17" value="' . $_REQUEST['tip'][17] . '">
</form></div>
    ';
        }
} elseif ($_REQUEST['page'] == "load") {
    $copy_file = "../csv/" . $_REQUEST['name'];
    if (is_file($copy_file)) {
        $CsvContent = fopen($copy_file, "r");
        $ReadCsv = new ReadCsv1C($CsvContent, $PHPShopBase->getParam('base.products'), $option['sklad_status']);
        fclose($CsvContent);
        $Done2 = $ReadCsv->DoUpdatebase2();
        $interface.='

<TABLE cellSpacing=0 cellPadding=0 width="50%" align="center">
<TR>

<TD vAlign=top style="padding-top:25">
<div align="center"><h4><span name=txtLang2 id=txtLang2>Загрузка товарной базы Excel выполнена!</span></h4></div>
<FIELDSET id=fldLayout style="width: 60em;">
<table style="border: 1px;border-style: inset;background-color: White;" cellpadding="10" width="100%">
<tr>
	<td width="50%" ><h4><span name=txtLang2 id=txtLang2>Ход операции</span></h4>
<ol>
	<li><span name=txtLang2 id=txtLang2><strong>Шаг 1</strong> - перейти в раздел <a href="javascript:DoReload(\'cat_prod\')"><img src="img/i_eraser[1].gif" alt="" width="16" height="16" border="0" hspace="3" align="absmiddle">"Каталог</a> - Выгруженные товары - Excel  База"</span>
    <li><span name=txtLang2 id=txtLang2><strong>Шаг 2</strong> - выделите флажком товары и выберите папку для переноса опцией "С отмеченными - Перенести в каталог". Если требуется,  составьте соответствующие каталоги.</span></span>
</ol></td>
</tr>
<tr>
	<td width="50%" ><h4><span name=txtLang2 id=txtLang2>Отчет:</span></h4>
<ol>
	<li>Создано новых позиций: ' . $ReadCsv->TotalCreate . '
	<li>Обновлено позиций: ' . $ReadCsv->TotalUpdate . '
</ol></td>
</tr>
</table>
</FIELDSET>
</TD></TR></TABLE>
    ';
    }
}
else
    @$interface.=$disp = '
	  <table width="100%" height="100%" style="Z-INDEX:2;">
<tr>
	<td valign="middle" align="center">
		<div style="width:400px;height:100px;BACKGROUND: #C0D2EC;padding:10px;border: solid;border-width: 1px; border-color:#4D88C8;FILTER: alpha(opacity=80);" align="left">
<table width="100%" height="100%">
<tr>
	<td width="35" vAlign=center ><IMG 
            hspace=0 src="img/i_support_med[1].gif" align="absmiddle" 
            border=0 ></td>
	<td ><b>Внимание, выберите файл с расширением *.csv.
	Повторите  <a href="javascript:DoReload(\'csv1c\')" style="color:red">загрузку файла</a>.</b></td>
</tr>
</table>

		</div>
</td>
</tr>
</table>
	  
	  ';
//$interface.='test value:<BR>'.$testValue; //DEBUG
$_RESULT = array(
    "name" => @$_FILES['file']['name'],
    "content" => @$interface,
    "size" => @filesize($_FILES['file']['tmp_name']),
);
?>
