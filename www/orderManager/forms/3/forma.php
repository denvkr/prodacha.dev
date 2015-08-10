<?php
/**
 * Печатная форма гарантии для Order Agent
 * @package PHPShopExchange
 * @author PHPShop Software
 * @version 1.1
 */

$_classPath="../../../phpshop/";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("date");
PHPShopObj::loadClass("security");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");

$PHPShopSystem = new PHPShopSystem();
$LoadItems['System']=$PHPShopSystem->getArray();

// Подключаем реквизиты
$SysValue['bank']=unserialize($LoadItems['System']['bank']);
$pathTemplate=$SysValue['dir']['templates'].chr(47).$_SESSION['skin'];

$orderID=PHPShopSecurity::TotalClean($_GET['orderID'],5);
$datas=PHPShopSecurity::TotalClean($_GET['datas'],1);

$sql="select * from ".$SysValue['base']['table_name1']." where id='$orderID' and datas='$datas'";
$n=1;
$dis=null;
$sum=0;
$num=0;

@$result=mysql_query($sql) or die($sql);
$row = mysql_fetch_array(@$result);
$id=$row['id'];
$datas=$row['datas'];
$ouid=$row['uid'];
$order=unserialize($row['orders']);
$status=unserialize($row['status']);

if(is_array($order['Cart']['cart']))
    foreach($order['Cart']['cart'] as $val) {
        $dis.="<tr class=tablerow>
		<td class=tablerow>".$n."</td>
		<td class=tablerow>".$val['name']."</td>
		<td align=right class=tablerow>".$val['num']."</td>
		<td align=right class=tablerow nowrap>".$ouid."</td>
		<td class=tableright>12</td></tr>";
        $sum+=$val['price']*$val['num'];
        $num+=$val['num'];
        $n++;
    }

if($LoadItems['System']['nds_enabled']) {
    $nds=$LoadItems['System']['nds'];
    $nds=number_format($sum*$nds/(100+$nds),"2",".","");
}
$sum=number_format($sum,"2",".","");

$name_person=$order['Person']['name_person'];
$org_name=$order['Person']['org_name'];
$datas=PHPShopDate::dataV($datas,"false");

// номер товарного чека
$chek_num=substr(abs(crc32(uniqid(rand(),true))),0,5);
$LoadBanc=unserialize($LoadItems['System']['bank']);
?>
<head>
    <title>Гарантийное обязательство №<?=$chek_num?></title>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
    <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
    <link href="../style.css" type=text/css rel=stylesheet>
    <style media="print" type="text/css">
        <!--
        .nonprint {
            display: none;
        }
        -->
    </style>
</head>
<body onload="window.focus()" bgcolor="#FFFFFF" text="#000000" marginwidth=5 leftmargin=5 style="padding: 2px;">
    <div align="right" class="nonprint"><a href="#" onclick="window.print();return false;" ><img border=0 align=absmiddle hspace=3 vspace=3 src="http://<?=$_SERVER['SERVER_NAME'].$SysValue['dir']['dir']?>/phpshop/admpanel/img/action_print.gif">Распечатать</a> | <a href="#" onclick="document.execCommand('SaveAs');return false;">Сохранить на диск<img border=0 align=absmiddle hspace=3 vspace=3 src="http://<?=$_SERVER['SERVER_NAME'].$SysValue['dir']['dir']?>/phpshop/admpanel/img/action_save.gif"></a><br><br></div>

    <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><TBODY>
            <TR>

                <TD align=right>
                    <BLOCKQUOTE>
                        <P><b>Гарантийное обязательство</b> <SPAN class=style4>№<?=@$chek_num?> от <?=$datas?></SPAN> </P></BLOCKQUOTE></TD></TR>
            <TR>
                <TD align=right>
                    <BLOCKQUOTE>
                        <P><SPAN class=style4><?=$LoadBanc['org_adres']?>, телефон <?=$LoadItems['System']['tel']?> </SPAN></P></BLOCKQUOTE></TD></TR>
            <TR>
                <TD align=right>
                    <BLOCKQUOTE>
                        <P class=style4>Поставщик: <?=$LoadItems['System']['company']?></P></BLOCKQUOTE></TD></TR></TBODY></TABLE>

    <p><br></p>
    <table width=99% cellpadding=2 cellspacing=0 align=center>
        <tr class=tablerow>
            <td class=tablerow>№</td>
            <td width=50% class=tablerow>Наименование</td>
            <td class=tablerow>Кол-во</td>
            <td class=tablerow>Серийный номер</td>
            <td class=tablerow style="border-right: 1px solid #000000;">Гарантия (мес)</td>
        </tr>
        <? echo $dis;?>

        <tr><td colspan=6 style="border: 0px; border-top: 1px solid #000000;">&nbsp;</td></tr>
    </table>


    <TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><TBODY>
            <TR>
                <TH scope=row align=middle width="50%">
                    <P>&nbsp;</P>
                    <P class=style4>Продавец: ________________ М.П. </P>
                    <P>&nbsp;</P></TH>
                <TD vAlign=center align=left><SPAN class=style5>Гарантийное обслуживание товаров осуществляется в авторизованном сервисном центре изготовителя. При отсутствии соответствующего сервисного центра гарантийное обслуживание осуществляется у продавца. </SPAN></TD></TR></TBODY></TABLE>
    <?=$LoadItems['System']['promotext']?>
</body>
</html>