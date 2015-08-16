<?php

/**
 * Панель заголовков каталога
 * @param array $row массив данных
 * @return string 
 */
function tab_headers($row) {

    $title = $row['title'];
    $title_enabled = $row['title_enabled'];
    $title_shablon = $row['title_shablon'];
    $descrip = $row['descrip'];
    $descrip_enabled = $row['descrip_enabled'];
    $descrip_shablon = $row['descrip_shablon'];
    $keywords = $row['keywords'];
    $keywords_enabled = $row['keywords_enabled'];
    $keywords_shablon = $row['keywords_shablon'];

    if (empty($row['skin']))
        $skin = $GetSystems['skin'];
    else
        $skin = $row['skin'];

    if ($title_enabled == 0) {
        $t1 = "checked";
        $t2_enabled = "none";
        $t3_enabled = "none";
    } elseif ($title_enabled == 1) {
        $t2 = "checked";
        $t2_enabled = "block";
        $t3_enabled = "none";
    } elseif ($title_enabled == 2) {
        $t3 = "checked";
        $t3_enabled = "block";
        $t2_enabled = "none";
    }

    if ($descrip_enabled == 0) {
        $d1 = "checked";
        $d2_enabled = "none";
        $d3_enabled = "none";
    } elseif ($descrip_enabled == 1) {
        $d2 = "checked";
        $d2_enabled = "block";
        $d3_enabled = "none";
    } elseif ($descrip_enabled == 2) {
        $d3 = "checked";
        $d3_enabled = "block";
        $d2_enabled = "none";
    }

    if ($keywords_enabled == 0) {
        $k1 = "checked";
        $k2_enabled = "none";
        $k3_enabled = "none";
    } elseif ($keywords_enabled == 1) {
        $k2 = "checked";
        $k2_enabled = "block";
        $k3_enabled = "none";
    } elseif ($keywords_enabled == 2) {
        $k3 = "checked";
        $k3_enabled = "block";
        $k2_enabled = "none";
    }


    $disp = '
<script src="gui/tab_headers.gui.js" type="text/javascript"></script>
<table width="100%">
<tr>
  <td width="100%">
  <FIELDSET id=fldLayout >
<LEGEND id=lgdLayout><u>T</u>itle:</LEGEND>
<div style="padding:10;width: 100%">
<input type="radio" value="0" name="title_enabled_new" onclick="document.getElementById(\'titleForma\').style.display=\'none\';document.getElementById(\'titleShablon\').style.display=\'none\'" ' . $t1 . '> <span name=txtLang id=txtLang>Автоматическая генерация</span>&nbsp;&nbsp;&nbsp;
<input type="radio" value="2" name="title_enabled_new" onclick="document.getElementById(\'titleShablon\').style.display=\'block\';document.getElementById(\'titleForma\').style.display=\'none\'" ' . $t3 . '> <span name=txtLang id=txtLang>Мой шаблон</span> &nbsp;&nbsp;&nbsp;
<input type="radio" value="1" name="title_enabled_new"  onclick="document.getElementById(\'titleForma\').style.display=\'block\';document.getElementById(\'titleShablon\').style.display=\'none\'" ' . $t2 . '> <span name=txtLang id=txtLang>Ручная настройка</span><br>
<div id="titleShablon" style="display:' . $t3_enabled . '">
<textarea style="width: 100%; height: 5em;" name="title_shablon_new" id="Shablon">' . $title_shablon . '</textarea>
<input name="btnLang" type="button" value="Каталог" onclick="ShablonAdd(\'@Catalog@\',\'Shablon\')" class="buttonSh">
<input name="btnLang" type="button" value="Подкаталог" onclick="ShablonAdd(\'@Podcatalog@\',\'Shablon\')" class="buttonSh">
<input type="button" name="btnLang"  value="Общий" onclick="ShablonAdd(\'@System@\',\'Shablon\')" class="buttonSh">
<input type="button" value="," onclick="ShablonAdd(\',\',\'Shablon\')" class="buttonSh">
<input type="button" value="-" onclick="ShablonAdd(\'-\',\'Shablon\')" class="buttonSh">
<input type="button" value="/" onclick="ShablonAdd(\'/\',\'Shablon\')" class="buttonSh">
<input type="button" name="btnLang" value="Пробел" onclick="ShablonAdd(\' \',\'Shablon\')" class="buttonSh">
<input type="button" name="btnLang" value="Ввести слово" onclick="ShablonPromt(\'Shablon\')" class="buttonSh">
<input type="button" name="btnLang" value="Сбросить" onclick="ShablonDell(\'Shablon\')" class="buttonSh">
</div>
<div id="titleForma" style="display:' . $t2_enabled . '">
<textarea style="width: 100%; height: 5em;" name="title_new">' . $title . '</textarea>
</div>
</div>
</FIELDSET>
  </td>
</tr>
<tr>
  <td width="100%">
  <FIELDSET id=fldLayout >
<LEGEND id=lgdLayout><u>D</u>escription:</LEGEND>
<div style="padding:10;width: 100%">
<input type="radio" value="0" name="descrip_enabled_new" onclick="document.getElementById(\'titleFormaD\').style.display=\'none\';document.getElementById(\'titleShablonD\').style.display=\'none\'" ' . $d1 . '> <span name=txtLang id=txtLang>Автоматическая генерация</span>&nbsp;&nbsp;&nbsp;
<input type="radio" value="2" name="descrip_enabled_new" onclick="document.getElementById(\'titleShablonD\').style.display=\'block\';document.getElementById(\'titleFormaD\').style.display=\'none\'" ' . $d3 . '> <span name=txtLang id=txtLang>Мой шаблон</span>&nbsp;&nbsp;&nbsp;
<input type="radio" value="1" name="descrip_enabled_new"  onclick="document.getElementById(\'titleFormaD\').style.display=\'block\';document.getElementById(\'titleShablonD\').style.display=\'none\'" ' . $d2 . '> <span name=txtLang id=txtLang>Ручная настройка</span><br>
<div id="titleShablonD" style="display:' . $d3_enabled . '">
<textarea style="width: 100%; height: 5em;" name="descrip_shablon_new" id="ShablonD">' . $descrip_shablon . '</textarea>
<input type="button" name="btnLang" value="Каталог" onclick="ShablonAdd(\'@Catalog@\',\'ShablonD\')" class="buttonSh">
<input type="button"  name="btnLang" value="Подкаталог" onclick="ShablonAdd(\'@Podcatalog@\',\'ShablonD\')" class="buttonSh">
<input type="button" name="btnLang" value="Общий" onclick="ShablonAdd(\'@System@\',\'ShablonD\')" class="buttonSh">
<input type="button" value="," onclick="ShablonAdd(\',\',\'ShablonD\')" class="buttonSh">
<input type="button" value="-" onclick="ShablonAdd(\'-\',\'ShablonD\')" class="buttonSh">
<input type="button" value="/" onclick="ShablonAdd(\'/\',\'ShablonD\')" class="buttonSh">
<input type="button" name="btnLang" value="Пробел" onclick="ShablonAdd(\' \',\'ShablonD\')" class="buttonSh">
<input type="button" name="btnLang" value="Ввести слово" onclick="ShablonPromt(\'ShablonD\')" class="buttonSh">
<input type="button" name="btnLang" value="Сбросить" onclick="ShablonDell(\'ShablonD\')" class="buttonSh">
</div>
<div id="titleFormaD" style="display:' . $d2_enabled . '">
<textarea style="width: 100%; height: 5em;" name="descrip_new">' . $descrip . '</textarea>
</div>
</div>
</FIELDSET>
  </td>
</tr>
<tr>
  <td width="100%">
  <FIELDSET id=fldLayout >
<LEGEND id=lgdLayout><u>K</u>eywords:</LEGEND>
<div style="padding:10;width: 100%">
<input type="radio" value="0" name="keywords_enabled_new" onclick="document.getElementById(\'titleFormaK\').style.display=\'none\';document.getElementById(\'titleShablonK\').style.display=\'none\'" ' . $k1 . '> <span name=txtLang id=txtLang>Автоматическая генерация</span>&nbsp;&nbsp;&nbsp;
<input type="radio" value="2" name="keywords_enabled_new" onclick="document.getElementById(\'titleShablonK\').style.display=\'block\';document.getElementById(\'titleFormaK\').style.display=\'none\'" ' . $k3 . '> <span name=txtLang id=txtLang>Мой шаблон</span> &nbsp;&nbsp;&nbsp;
<input type="radio" value="1" name="keywords_enabled_new"  onclick="document.getElementById(\'titleFormaK\').style.display=\'block\';document.getElementById(\'titleShablonK\').style.display=\'none\'" ' . $k2 . '> <span name=txtLang id=txtLang>Ручная настройка</span><br>
<div id="titleShablonK" style="display:' . $k3_enabled . '">
<textarea style="width: 100%; height: 5em;" name="keywords_shablon_new" id="ShablonK">' . $keywords_shablon . '</textarea>
<input type="button" name="btnLang" value="Каталог" onclick="ShablonAdd(\'@Catalog@\',\'ShablonK\')" class="buttonSh">
<input type="button" name="btnLang" value="Подкаталог" onclick="ShablonAdd(\'@Podcatalog@\',\'ShablonK\')" class="buttonSh">
<input type="button" name="btnLang" value="Общий" onclick="ShablonAdd(\'@System@\',\'ShablonK\')" class="buttonSh">
<input type="button" name="btnLang" value="Автопобор" onclick="ShablonAdd(\'@Generator@\',\'ShablonK\')" class="buttonSh">
<input type="button" value="," onclick="ShablonAdd(\',\',\'ShablonK\')" class="buttonSh">
<input type="button" value="-" onclick="ShablonAdd(\'-\',\'ShablonK\')" class="buttonSh">
<input type="button" value="/" onclick="ShablonAdd(\'/\',\'ShablonK\')" class="buttonSh">
<input type="button" name="btnLang" value="Пробел" onclick="ShablonAdd(\' \',\'ShablonK\')" class="buttonSh">
<input type="button" name="btnLang" value="Слово" onclick="ShablonPromt(\'ShablonK\')" class="buttonSh">
<input type="button" name="btnLang" value="Сбросить" onclick="ShablonDell(\'ShablonK\')" class="buttonSh">
</div>
<div id="titleFormaK" style="display:' . $k2_enabled . '">
<textarea style="width: 100%; height: 5em;" name="keywords_new">' . $keywords . '</textarea>
</div>
</div>
</FIELDSET>
  </td>
</tr>
</table>';
    return $disp;
}

?>