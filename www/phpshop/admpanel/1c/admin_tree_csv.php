<?

$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("readcsv");
PHPShopObj::loadClass("security");



$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();

class ReadCsv1C extends PHPShopReadCsv {

    var $CsvContent;
    var $ReadCsvRow;
    var $TableName;
    var $CsvToArray;
    var $TotalUpdate = 0;
    var $TotalCreate = 0;
    var $n = 1;
    var $Debug;

    function ReadCsv1C($file) {
        $this->CsvContent = parent::readFile($file);
        $this->TableName = $GLOBALS['SysValue']['base']['table_name'];
        parent::PHPShopReadCsv();
    }

    function DoUpdatebase1() {
        $CsvToArray = $this->CsvToArray;
        foreach ($CsvToArray as $v)
            @$return.=$this->PrintResultDo($v);
        return $return;
    }

    function DoUpdatebase2() {
        $CsvToArray = $this->CsvToArray;
        foreach ($CsvToArray as $v)
            $this->UpdateBase($v);
    }

    function UpdateBase($CsvToArray) {

        $CheckBase = parent::CheckId($CsvToArray[0]);



        if (!empty($CheckBase) and $CsvToArray[0] != "") {// Обновляем  
            $sql = "UPDATE " . $this->TableName . " SET 
id = '" . trim($CsvToArray[0]) . "',
name = '" . parent::CleanStr($CsvToArray[1]) . "', 
parent_to = '" . trim($CsvToArray[2]) . "'
where id='" . $CsvToArray[0] . "'";
            $result = mysql_query($sql);
            $this->TotalUpdate++;
//$this->Debug=$sql;
        } else {// Создаем новый каталог
            $sql = "INSERT INTO " . $this->TableName . " SET 
id = '" . trim($CsvToArray[0]) . "',
name = '" . parent::CleanStr($CsvToArray[1]) . "', 
parent_to = '" . trim($CsvToArray[2]) . "' 
";
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
	<td align=center>" . $this->n . "</td>
	 <td align=center>" . $CsvToArray[0] . "</td>
	 <td >" . $CsvToArray[1] . "</td>
	 <td align=center>" . $CsvToArray[2] . "</td>
	</tr>
	";
        $this->n++;
        return $disp;
    }

}

require_once "../../lib/JsHttpRequest/JsHttpRequest.php";
$JsHttpRequest =  new JsHttpRequest("windows-1251");

// Расширение
$_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);

if ($_REQUEST['page'] == "predload" and $_FILES['file']['ext'] == "csv") {

// Загружаем
    if (move_uploaded_file(@$_FILES['file']['tmp_name'], "../csv/" . @$_FILES['file']['name'])) {

        $ReadCsv = new ReadCsv1C("../csv/" . @$_FILES['file']['name']);
        $interface.='
<div id=interfacesWin name=interfacesWin align="left" style="width:100%;height:580;overflow:auto"> 
<TABLE cellSpacing=0 cellPadding=0 width="100%"><TBODY>
<TR>
<TD vAlign=top>
<table width="100%" cellpadding="0" cellspacing="1" class="sortable" id="sort">
<tr>
    <td id="pane" width="50">№</td>
	<td id="pane" width="100">Код категории</td>
	<td id="pane">Название категории</td>
	<td id="pane" width="100">Код родителя</td>
</tr>
' . $ReadCsv->DoUpdatebase1() . '
</table>
</TD></TR><form method=post action="" encType=multipart/form-data name=forma2></TBODY></TABLE>
</div>
<div align="center" style="padding-top:20">
<button style="WIDTH: 17em; HEIGHT: 2.3em" onclick="DoReload(\'csv1c\')">
<img src="img/icon-setup2.gif" alt="" width="16" height="16" border="0" align="absmiddle" hspace="5">
Выбрать другой файл</button>
&nbsp;&nbsp;
<button style="WIDTH: 17em; HEIGHT: 2.3em" onclick="DoLoadBase1C(null,\'load\',\'' . $_FILES['file']['name'] . '\')">
<img src="img/icon-setup.gif" alt="" width="16" height="16" border="0" align="absmiddle" hspace="5">
Принять изменения</button>';

        foreach ($_REQUEST['tip'] as $key => $val)
            $interface.='<input type="hidden" id="tip_' . $key . '" value="' . $_REQUEST['tip'][$key] . '">';

        $interface.='
<input type="hidden" id="1c_target_check" value="1">
<input type="hidden" id="1c_tree_check" value="1">
</form></div>
    ';
    }
} elseif ($_REQUEST['page'] == "load") {
    $ReadCsv = new ReadCsv1C("../csv/" . @$_REQUEST['name']);
    $ReadCsv->DoUpdatebase2();
    $interface.='
<TABLE cellSpacing=0 cellPadding=0 width="50%" align="center">
<TR>

<TD vAlign=top style="padding-top:25">
<div align="center"><h4><span name=txtLang2 id=txtLang2>Загрузка каталога товарной базы 1C:Предприятие выполнена!</span></h4></div>
<FIELDSET id=fldLayout style="width: 60em; height: 8em;">
<table style="border: 1px;border-style: inset;background-color: White;" cellpadding="10" width="100%">
<tr>
	<td width="50%" ><h4><span name=txtLang2 id=txtLang2>Отчет:</span></h4>
<ol>
	<li>Создано новых каталогов: ' . $ReadCsv->TotalCreate . '
	<li>Обновлено каталогов: ' . $ReadCsv->TotalUpdate . '
</ol>
' . $ReadCsv->Debug . '
</td>
</tr>
</table>
</FIELDSET>
</TD></TR></TABLE>
    ';
}else
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

$_RESULT = array(
    "name" => @$_FILES['file']['name'],
    "content" => @$interface,
    "size" => @filesize($_FILES['file']['tmp_name']),
);
?>
