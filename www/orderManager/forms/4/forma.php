<?php
/**
 * Печатная форма Счет-фактуры для Order Agent
 * @package PHPShopExchange
 * @author PHPShop Software
 * @version 1.1
 */

$_classPath="../../../phpshop/";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("delivery");
PHPShopObj::loadClass("date");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("security");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");

$PHPShopSystem = new PHPShopSystem();
$LoadItems['System']=$PHPShopSystem->getArray();

// Подключаем реквизиты
$SysValue['bank']=unserialize($LoadItems['System']['bank']);
$pathTemplate=$SysValue['dir']['templates'].chr(47).$_SESSION['skin'];

$orderID=PHPShopSecurity::TotalClean($_GET['orderID'],5);
$datas=PHPShopSecurity::TotalClean($_GET['datas'],1);

$PHPShopOrder = new PHPShopOrderFunction($orderID);

$sql="select * from ".$SysValue['base']['table_name1']." where id='$orderID' and datas='$datas'";

$n=1;
$this_nds_summa=0;
$dis=null;
$total_summa_nds=0;
$total_summa=0;
$sum=0;
$num=0;

@$result=mysql_query($sql) or die($sql);
$row = mysql_fetch_array(@$result);
$id=$row['id'];
$datas=$row['datas'];
$ouid=$row['uid'];
$order=unserialize($row['orders']);
$status=unserialize($row['status']);
$nds=$LoadItems['System']['nds'];

if(is_array($order['Cart']['cart']))
    foreach($order['Cart']['cart'] as $val) {
        $this_price=$PHPShopOrder->returnSumma($val['price'],$order['Person']['discount']);
        $this_nds=number_format($this_price*$nds/(100+$nds),"2",".","");
        $this_price_bez_nds=($this_price-$this_nds)*$val['num'];
        $this_price_c_nds=number_format($this_price*$val['num'],"2",".","");
        $this_nds_summa+=$this_nds*$val['num'];

        $dis.="<tr>
    <td >".$val['name']."</td>
    <td align=\"center\">шт</td>
    <td align=\"right\">".$val['num']."</td>
    <td align=\"right\">".$this_price."</td>
    <td align=\"right\">".$this_price_bez_nds."</td>
    <td align=\"right\">--</td>
    <td align=\"right\">".$LoadItems['System']['nds']."%</td>
    <td align=\"right\">".$this_nds*$val['num']."</td>
    <td align=\"right\">".$this_price_c_nds."</td>
    <td align=\"center\">---</td>
    <td align=\"center\">---</td></tr>";
        $total_summa_nds+=$summa_nds;
        $total_summa+=$PHPShopOrder->returnSumma(($val['price']*$val['num']),$order['Person']['discount']);
        $sum+=$val['price']*$val['num'];
        $num+=$val['num'];

        //Определение и суммирование веса
        $goodid=$val['id'];
        $goodnum=$val['num'];
        $wsql='select weight from '.$SysValue['base']['table_name2'].' where id=\''.$goodid.'\'';
        $wresult=mysql_query($wsql);
        $wrow=mysql_fetch_array($wresult);
        $cweight=$wrow['weight']*$goodnum;
        if (!$cweight) {
            $zeroweight=1;
        } //Один из товаров имеет нулевой вес!
        $weight+=$cweight;
        $n++;
    }

//Обнуляем вес товаров, если хотя бы один товар был без веса
if ($zeroweight) {
    $weight=0;
}

$PHPShopDelivery = new PHPShopDelivery($order['Person']['dostavka_metod']);
$deliveryPrice=$PHPShopDelivery->getPrice($sum,$weight);
$summa_nds_dos=number_format($deliveryPrice*$nds/(100+$nds),"2",".","");

$dis.="<tr>
    <td >Доставка ".$PHPShopDelivery->getCity()."</td>
    <td align=\"center\">шт</td>
    <td align=\"right\">1</td>
    <td align=\"right\">".$deliveryPrice."</td>
    <td align=\"right\">".$deliveryPrice."</td>
    <td align=\"right\">--</td>
    <td align=\"right\">".$LoadItems['System']['nds']."%</td>
    <td align=\"right\">".$summa_nds_dos."</td>
    <td align=\"right\">".$deliveryPrice."</td>
    <td align=\"center\">---</td>
    <td align=\"center\">---</td></tr>";

if($LoadItems['System']['nds_enabled']) {
    $nds=$LoadItems['System']['nds'];
    $nds=number_format($sum*($nds/(100+$nds)),"2",".","");
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
    <title>Счет - Фактура №<?=$ouid?></title>
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
    <div align="right" class="nonprint"><a href="#" onclick="window.print();return false;" ><img border=0 align=absmiddle hspace=3 vspace=3 src="http://<?=$_SERVER['SERVER_NAME'].$SysValue['dir']['dir']?>/phpshop/admpanel/img/action_print.gif">Распечатать</a> | <a href="#" onclick="document.execCommand('SaveAs');return false;" style="color: #0078BD;">Сохранить на диск<img border=0 align=absmiddle hspace=3 vspace=3 src="http://<?=$_SERVER['SERVER_NAME'].$SysValue['dir']['dir']?>/phpshop/admpanel/img/action_save.gif"></a><br><br></div>
    <table align="center" width="1000" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td valign="top" align="right">
                <?
                $GetIsoValutaOrder=$PHPShopOrder->default_valuta_code;
                if(preg_match("/руб/",$GetIsoValutaOrder)) {
                    echo '
	<div id="d1">Приложение №1<br />
к Правилам ведения журналов учета полученных и выставленных счетов-фактур,<br />
 книг покупок и книг продаж при расчетах по налогу на добавленную стоимость,<br />

 утвержденным постановлением Правительства Российской Федерации  от 2 декабря 2000 г. N 9</div>
';
}?>
            </td>
        </tr>
        <tr>
            <td valign="top" id="d2">СЧЕТ-ФАКТУРА №<?=@$ouid?> от <?=$datas?></td>
        </tr>
        <tr>
            <td valign="top" >Продавец: <?=$LoadItems['System']['company']?><br />
                Адрес: <?=$LoadBanc['org_adres']?>, <?=$LoadItems['System']['tel']?> <br />
                Идентификационный номер продавца (ИНН) <?=$LoadBanc['org_inn']?>\<?=$LoadBanc['org_kpp']?> <br />
                Грузоотправитель и его адрес: Он же	<br />
                Грузополучатель и его адрес:  <?=@$order['Person']['adr_name']?>	<br />
                К платежно-расчетному документу       <br />
                Покупатель: <?=@$order['Person']['org_name']?>	<br />
                Адрес: <?=@$order['Person']['adr_name']?> <br />
                Идентификационный номер покупателя (ИНН) <?=@$order['Person']['org_inn']?>/<?=@$order['Person']['org_kpp']?> <br />
            </td>
        </tr>
        <tr>
            <td align="right"><b>Валюта: <?=$GetIsoValutaOrder?></b></td>
        </tr>
        <tr>
            <td valign="top" ><table style="margin-top:10px;" bordercolor="#000000"  width="998" border="1" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="122" align="center">Наименование товара (описание выполненных работ, оказанных услуг)</td>
                        <td width="68" align="center">Единица изме-
	рения</td>
                        <td width="55" align="center">Коли-
                            чество</td>
                        <td width="113" align="center">Цена (тариф) за единицу измерения</td>
                        <td width="92" align="center">Стоимость товаров (работ, услуг), всего без налога</td>
                        <td width="98" align="center">В том
                            числе
                            акциз</td>
                        <td width="97" align="center">Налоговая ставка</td>
                        <td width="80" align="center">Сумма налога</td>
                        <td width="97" align="center">Стоимость товаров (работ, услуг), всего с учетом налога</td>
                        <td width="76" align="center">Страна
                            происхож-
                            дения</td>
                        <td width="76" align="center">Номер
                            грузовой
                            таможенной
                            декларации</td>
                    </tr>
                    <tr>
                        <td align="center">1</td>
                        <td align="center">2</td>
                        <td align="center">3</td>
                        <td align="center">4</td>
                        <td align="center">5</td>
                        <td align="center">6</td>
                        <td align="center">7</td>
                        <td align="center">8</td>
                        <td align="center">9</td>
                        <td align="center">10</td>
                        <td align="center">11</td>
                    </tr>
<? echo $dis;?>
                    <tr>
                        <td colspan="7"  ><b>Всего к оплате</b></td>

                        <td align="right"><? echo $this_nds_summa+$summa_nds_dos; ?></td>
                        <td align="right"><? echo $total_summa+$deliveryPrice; ?></td>
                        <td colspan="2">&nbsp;</td>

                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td valign="top" >&nbsp;</td>


        </tr>
        <tr>
            <td valign="top" >&nbsp;</td>


        </tr>
        <tr>
            <td valign="top" ><table width="1000" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="487"><table width="423" border="0" cellspacing="3" cellpadding="0">
                                <tr>
                                    <td width="417">Руководитель организации ____________________ </td>
                                </tr>
                                <tr>
                                    <td height="29">(индивидуальный предприниматель)</td>
                                </tr>
                                <tr>
                                    <td height="27" align="right"> М.П.</td>
                                </tr>
                                <tr>
                                    <td height="30">ВЫДАЛ</td>
                                </tr>
                                <tr>
                                    <td id="d3">Примечание. Первый  экземпляр  -  покупателю,  второй   экземпляр - продавцу</td>
                                </tr>
                            </table></td>
                        <td width="513" valign="top" align="right"><table width="374" border="0" cellspacing="3" cellpadding="0">
                                <tr>
                                    <td>Главный бухгалтер ____________________ </td>
                                </tr>
                                <tr>
                                    <td height="29">(реквизиты свидетельства о государственной<br />
                                        регистрации индивидуального предпринимателя)</td>
                                </tr>
                                <tr>
                                    <td height="59" valign="bottom">________________________________________</td>
                                </tr>
                                <tr>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td valign="top">(подпись ответственного лица от продавца)</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>