<?

function PacMetod($tip) {
    global $SysValue;
    if ($tip == 1)
        return $SysValue['oplata']['pac'];
    else
        return 0;
}

function OplataMetod($tip) {
    global $SysValue;
    return $SysValue['Lang']['Order'][$tip];
}

function ReturnSumma($sum, $disc) {
    $kurs = GetKursOrder();
    $sum*=$kurs;
    $sum = $sum - ($sum * $disc / 100);
    return number_format($sum, "2", ".", "");
}

// Номер заказа
function UpdateNumOrder($uid) {
    $all_num = explode("-", $uid);
    $ferst_num = $all_num[0];
    $last_num = $all_num[1];
    return $ferst_num . $last_num;
}

// Проверка электронного платежа
function CheckPayment($id) {
    global $SysValue;
    $id = UpdateNumOrder($id);
    $sql = "select * from " . $SysValue['base']['table_name33'] . " where uid=" . $id;
    @$result = mysql_query($sql);
    $num = mysql_numrows(@$result);
    return $num;
}

function Visitor($pole1, $pole2, $words, $list) {// вывод покупателей
    global $table_name1, $UserStatus, $_SESSION, $SysValue;

    if (empty($pole1))
        $pole1 = date("U") - 86400;
    else
        $pole1 = GetUnicTime($pole1) - 86400;
    if (empty($pole2))
        $pole2 = date("U") + 86400;
    else
        $pole2 = GetUnicTime($pole2) + 86400;

    $disp = null;
    $numRows = 0;

    if ($list == "all" or !$list)
        $sort = "";
    elseif ($list == "new")
        $sort = "and statusi=0";
    else
        $sort = "and statusi=" . $list;


    $GetOrderStatusArray = GetOrderStatusArray();

    if (!empty($words)) {
        if (is_numeric($words))
            $sql = "select * from $table_name1 where uid=" . $words . " ";
        else
            $sql = "select * from $table_name1 where orders REGEXP '" . $words . "' or orders REGEXP '" . ucfirst($words) . "'";
    }
    else
        $sql = "select * from $table_name1 where datas<'$pole2' and datas>'$pole1' $sort order by id desc";
    @$result = mysql_query($sql);
    while (@$row = mysql_fetch_array(@$result)) {
        $id = $row['id'];
        $datas = $row['datas'];
        $uid = $row['uid'];
        $user = $row['user'];
        $order = unserialize($row['orders']);
        $status = unserialize($row['status']);
        $statusi = $row['statusi'];
        if ($statusi == 0) {
            $bg = "C0D2EC";
            $status_name = "Новый заказ";
        } else {
            $bg = $GetOrderStatusArray[$statusi]['color'];
            $status_name = $GetOrderStatusArray[$statusi]['name'];
        }

        $DeliveryPrice = GetDeliveryPrice($order['Person']['dostavka_metod'], $order['Cart']['sum'], $order['Cart']['weight']);

        if ($user > 0)
            $UserId = "<img src=\"img/icon_user.gif\" alt=\"" . $SysValue['Lang']['Order']['5'] . "\" border=\"0\" align=\"absmiddle\" hspace=\"3\">";
        else
            $UserId = "<img src=\"img/icon_world.gif\" alt=\"" . $SysValue['Lang']['Order']['6'] . "\" border=\"0\" align=\"absmiddle\" hspace=\"3\">";


        // Проверка платежей
        $CheckPayment = CheckPayment($uid);
        if ($CheckPayment == 1 and $statusi == 0)
            $PaymentId = "<img src=\"icon/coins_anim.gif\" alt=\"Оплачено Электронным платежом\" border=\"0\" align=\"absmiddle\" hspace=\"3\" title=\"Оплачено Электронным платежом\">";
        else if ($CheckPayment == 1 and $statusi != 0)
            $PaymentId = "<img src=\"icon/coins.gif\" alt=\"Оплачено Электронным платежом\" border=\"0\" align=\"absmiddle\" hspace=\"3\" title=\"Оплачено Электронным платежом\">";
        else
            $PaymentId = "";

        // Выделение четных строк
        $numRows++;
        if ($numRows % 2 == 0) {
            $style_r = ' line2';
        } else {
            $style_r = null;
        }

        $disp.='
<tr class="row ' . $style_r . '" id="r' . $id . '">
	 
	
    <td valign="middle" align="center" onmouseover="show_on(\'r' . $id . '\')" onmouseout="show_out(\'r' . $id . '\')" onclick="miniWin(\'order/adm_visitorID.php?visitorID=' . $id . '&pole1=' . $pole1 . '&pole2=' . $pole2 . '\',650,550)">' . $uid . '</td>
	<td valign="middle" align="center"  onmouseover="show_on(\'r' . $id . '\')" onmouseout="show_out(\'r' . $id . '\')" onclick="miniWin(\'order/adm_visitorID.php?visitorID=' . $id . '&pole1=' . $pole1 . '&pole2=' . $pole2 . '\',650,550)">
	' . dataV($datas, "shot") . ' </td>
	<td class=forma onmouseover="show_on(\'r' . $id . '\')" onmouseout="show_out(\'r' . $id . '\')" onclick="miniWin(\'order/adm_visitorID.php?visitorID=' . $id . '&pole1=' . $pole1 . '&pole2=' . $pole2 . '\',650,550)">
	' . $PaymentId . $UserId . $order['Person']['name_person'] . '
	</td>
	<td align="center" onmouseover="show_on(\'r' . $id . '\')" onmouseout="show_out(\'r' . $id . '\')" onclick="miniWin(\'order/adm_visitorID.php?visitorID=' . $id . '&pole1=' . $pole1 . '&pole2=' . $pole2 . '\',650,550)">
	' . $order['Cart']['num'] . '
	</td>
	<td align="center" onmouseover="show_on(\'r' . $id . '\')" onmouseout="show_out(\'r' . $id . '\')" onclick="miniWin(\'order/adm_visitorID.php?visitorID=' . $id . '&pole1=' . $pole1 . '&pole2=' . $pole2 . '\',650,550)">
	' . $order['Person']['discount'] . '
	</td>
	<td align="center" onmouseover="show_on(\'r' . $id . '\')" onmouseout="show_out(\'r' . $id . '\')" onclick="miniWin(\'order/adm_visitorID.php?visitorID=' . $id . '&pole1=' . $pole1 . '&pole2=' . $pole2 . '\',650,550)">
	' . (ReturnSumma($order['Cart']['sum'], $order['Person']['discount']) + $DeliveryPrice) . '
	</td>
	<td align="center" onmouseover="show_on(\'r' . $id . '\')" onmouseout="show_out(\'r' . $id . '\')" onclick="miniWin(\'order/adm_visitorID.php?visitorID=' . $id . '&pole1=' . $pole1 . '&pole2=' . $pole2 . '\',650,550)">
	' . $status['time'] . '
	</td>
	<td  align="center" bgcolor="' . $bg . '" onclick="miniWin(\'order/adm_visitorID.php?visitorID=' . $id . '&pole1=' . $pole1 . '&pole2=' . $pole2 . '\',650,550)">' . $status_name . '
	</td>
	<td class=forma style="padding:1px" align="center" onmouseover="show_on(\'r' . $id . '\')" onmouseout="show_out(\'r' . $id . '\')">
	<input type=checkbox name="c' . $id . '" value="' . $id . '">
	</td>
</tr>
	';
        @$i++;
    }
    if ($i > 30)
        $razmer = "height:600;";
    $_Return = ('
<div align="left" id="interfacesWin" name="interfacesWin"  style="width:100%;' . @$razmer . ';overflow:auto"> 


<form name="form_flag">
<table width="100%"  cellpadding="0" cellspacing="0" border="0">
<tr>
	<td valign="top">

<table cellpadding="0" cellspacing="1" width="100%" border="0" class="sortable" id="sort">
<tr>
	<td width="100" id="pane" align="center"><img  src="icon/blank.gif"  width="1" height="1" border="0" align="left"><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>№</span></td>
	<td id="pane" width="130" align="center"><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Поступление</span></td>
	<td width="300" id="pane" align="center"><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Покупатель</span></td>
<td width="100" id="pane" align="center"><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Кол-во</span></td>
<td width="100" id="pane" align="center"><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Скидка</span> %</td>
<td width="100" id="pane" align="center"><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Сумма</span> ' . GetIsoValutaOrder() . '</td>
<td width="130" id="pane" align="center"><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Обработан</span></td>
<td width="200" id="pane" align="center"><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Статус</span></td>
<td width="25" id=pane align=center style="padding:0px"><input type=checkbox value=1 name=DoAll onclick="SelectAllBox(this,form_flag)"></td>
</tr>
' . @$disp . '
</table>
    </td>
</tr>
</table>
</form>
</div>
 ' . '
<div class=cMenu id=cMenuNws> 
	<TABLE style="width:260px;"  border="0" cellspacing="0" cellpadding="0">
	<TR><TD id="txtLang" STYLE="background: #C0D2EC;"><B>Действия</B></TD></TR>
	<TR><TD id="txtLang" STYLE="background: #fff"><A name="tarurl" id=nameNews36>Удалить из базы</A></TD></TR>
	<TR><TD id="txtLang" STYLE="background: #fff"><A name="tarurl" id=nameNews37>Изменить статус</A></TD></TR>	
	<TR><TD id="txtLang" STYLE="background: #fff"><A name="tarurl" id=nameNews38>Создать новый</A></TD></TR>		
	<TR><TD id="txtLang" STYLE="background: #fff"><A name="tarurl" id=nameNews45>Выгрузить в 1С:Предприятие</A></TD></TR>			
	</TABLE>
</div>
 ');
    return $_Return;
}

?>