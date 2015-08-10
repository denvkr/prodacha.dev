<?
require("../connect.php");
@mysql_connect("$host", "$user_db", "$pass_db") or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase") or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

// Языки
$GetSystems = GetSystems();
$option = unserialize($GetSystems['admoption']);
$Lang = $option['lang'];
require("../language/" . $Lang . "/language.php");

function DelivList($PID = 0, $lvl = 0) {
    global $SysValue;

    $numRows = 0;
    $display = null;

    $sql = 'select * from ' . $SysValue['base']['table_name30'] . ' where (PID=' . intval($PID) . ' AND is_folder="0") order by city';
    $result = mysql_query($sql);
    $lvl++;
    while (@$row = mysql_fetch_array(@$result)) {
        $id = $row['id'];
        $PID = $row['PID'];
        $city = $row['city'];
        $price = $row['price'];
        $enabled = $row['enabled'];
        if ($row['price_null_enabled'] == 1)
            $price_null = $row['price_null'] . " " . GetIsoValutaOrder();
        else
            $price_null = "";
        if (($enabled) == "1") {
            $checked = "<img src=../img/icon-activate.gif  >";
        } else {
            $checked = "<img src=../img/icon-deactivate.gif>";
        };
        $spacer = '';
        for ($ii = 1; $ii < $lvl; $ii++) {
            $spacer.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        if ($lvl > 1) {
            $pointer = '|&ndash;>&nbsp;';
        } else {
            $pointer = '';
        }

        $taxa = $row['taxa'];
        if (!$taxa) {
            $taxa = '-';
        }

        // Выделение четных строк
        $numRows++;
        if ($numRows % 2 == 0) {
            $style_r = ' line2';
        } else {
            $style_r = null;
        }

        $display.='<tr class="row ' . $style_r . '" id="r' . $id . '" onmouseover="PHPShopJS.rowshow_on(this)" onmouseout="PHPShopJS.rowshow_out(this,\'' . $style_r . '\')" onclick="miniWin(\'adm_deliveryID.php?id=' . $id . '\',600,500)">';


        $display.="
<td align=\"center\">$checked</td>
<td class=forma>
	$spacer$pointer$city 
	</td>
	<td class=forma>
	$price " . GetIsoValutaOrder() . "
	</td>
	<td class=forma>$price_null</td>
	<td class=forma>$taxa</td>
    </tr>
	";
        $display.=DelivList($id, $lvl);
    }

    return $display;
}

//Конец DelivList



$display = DelivList($id);

$sql = "select * from " . $SysValue['base']['table_name30'];
$result = mysql_query($sql);
$i = mysql_num_rows($result);


if ($i > 30)
    $razmer = "height:600;";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <META http-equiv=Content-Type content="text/html; charset=<?= $SysValue['Lang']['System']['charset'] ?>">
        <LINK href="../css/texts.css" type=text/css rel=stylesheet>
        <script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
        <script type="text/javascript" language="JavaScript1.2" src="../java/sorttable.js"></script>
    </head>
    <body style="background: threedface; color: windowtext;" topmargin="0" rightmargin="3" leftmargin="3" >
        <? if (isset($id)) { ?>


            <table cellpadding="0" cellspacing="1" width="100%" border="0"  class="sortable" id="sort">
                <tr>
                    <td width="30" id=pane align=>+/-</td>
                    <td  id=pane align=><span name=txtLang id=txtLang>Название/Город</span></td>
                    <td width="100" id=pane align=><span name=txtLang id=txtLang>Стоимость</span></td>
                    <td width="100" id=pane align=><span name=txtLang id=txtLang>Бесплатно свыше</span></td>
                    <td id=pane width="150"><span name=txtLang id=txtLang>Такса за 0.5кг</span></td>
                </tr>
                <?= $display ?>
            </table>

            <div align="right" style="padding:10"><BUTTON style="width: 15em; height: 2.2em; margin-left:5"  onclick="miniWin('adm_delivery_new.php?categoryID=<?= $id ?>', 600, 500);
                        return false;">
                    <img src="../icon/page_add.gif" width="16" height="16" border="0" align="absmiddle" hspace="5">
                    <span name=txtLang id=txtLang>Новая позиция</span>
                </BUTTON></div>
            <input type="hidden" value="<?= $id ?>" id="catal" name="catal">

        <? } ?>
    </body>
</html>
