<?
$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("inwords");
PHPShopObj::loadClass("delivery");
PHPShopObj::loadClass("date");
PHPShopObj::loadClass("valuta");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();

$PHPShopSystem = new PHPShopSystem();
$LoadItems['System'] = $PHPShopSystem->getArray();

$PHPShopOrder = new PHPShopOrderFunction($_GET['orderID']);


// ���������� ���������
$SysValue['bank'] = unserialize($LoadItems['System']['bank']);
$pathTemplate = $SysValue['dir']['templates'] . chr(47) . $_SESSION['skin'];


$sql = "select * from " . $SysValue['base']['table_name1'] . " where id='$_GET[orderID]'";
$n = 1;
@$result = mysql_query($sql) or die($sql);
$row = mysql_fetch_array(@$result);
$id = $row['id'];
$datas = $row['datas'];
$ouid = $row['uid'];
$order = unserialize($row['orders']);
$status = unserialize($row['status']);
$nds = $LoadItems['System']['nds'];
foreach ($order['Cart']['cart'] as $val) {
    $this_price = ($PHPShopOrder->returnSumma(number_format($val['price'], "2", ".", ""), $order['Person']['discount']));
    $this_nds = number_format($this_price * $nds / (100 + $nds), "2", ".", "");
    $this_price_bez_nds = ($this_price - $this_nds) * $val['num'];
    $this_price_c_nds = number_format($this_price * $val['num'], "2", ".", "");
    @$this_nds_summa+=$this_nds * $val['num'];

    @$dis.="
  <tr>
    <td >" . $val['name'] . "</td>
    <td align=\"center\">" . $val['ed_izm'] . "</td>
    <td align=\"right\">" . $val['num'] . "</td>
    <td align=\"right\">" . $this_price . "</td>
    <td align=\"right\">" . $this_price_bez_nds . "</td>
    <td align=\"right\">--</td>
    <td align=\"right\">" . $LoadItems['System']['nds'] . "%</td>
    <td align=\"right\">" . $this_nds * $val['num'] . "</td>
    <td align=\"right\">" . $this_price_c_nds . "</td>
    <td align=\"center\">---</td>
    <td align=\"center\">---</td>
  </tr>
  ";
    @$total_summa_nds+=$summa_nds;
    @$total_summa+=$PHPShopOrder->returnSumma(($val['price'] * $val['num']), $order['Person']['discount']);

//����������� � ������������ ����
    $goodid = $val['id'];
    $goodnum = $val['num'];
    $wsql = 'select weight from ' . $SysValue['base']['table_name2'] . ' where id=\'' . $goodid . '\'';
    $wresult = mysql_query($wsql);
    $wrow = mysql_fetch_array($wresult);
    $cweight = $wrow['weight'] * $goodnum;
    if (!$cweight) {
        $zeroweight = 1;
    } //���� �� ������� ����� ������� ���!
    $weight+=$cweight;


    @$sum+=$val['price'] * $val['num'];
    @$num+=$val['num'];
    $n++;
}
//�������� ��� �������, ���� ���� �� ���� ����� ��� ��� ����
if ($zeroweight) {
    $weight = 0;
}


$PHPShopDelivery = new PHPShopDelivery($order['Person']['dostavka_metod']);
$deliveryPrice = $PHPShopDelivery->getPrice($sum, $weight);

$summa_nds_dos = number_format($deliveryPrice * $nds / (100 + $nds), "2", ".", "");

@$dis.="
  <tr>
    <td >�������� " . $PHPShopDelivery->getCity() . "</td>
    <td align=\"center\">��.</td>
    <td align=\"right\">1</td>
    <td align=\"right\">" . $deliveryPrice . "</td>
    <td align=\"right\">" . $deliveryPrice . "</td>
    <td align=\"right\">--</td>
    <td align=\"right\">" . $LoadItems['System']['nds'] . "%</td>
    <td align=\"right\">" . $summa_nds_dos . "</td>
    <td align=\"right\">" . $deliveryPrice . "</td>
    <td align=\"center\">---</td>
    <td align=\"center\">---</td>
  </tr>
  ";

if ($LoadItems['System']['nds_enabled']) {
    $nds = $LoadItems['System']['nds'];
    @$nds = number_format($sum * ($nds / (100 + $nds)), "2", ".", "");
}
@$sum = number_format($sum, "2", ".", "");

$name_person = $order['Person']['name_person'];
$org_name = $order['Person']['org_name'];
$datas = PHPShopDate::dataV($datas, "false");


// ������� ����� ��������� ����
$chek_num = substr(abs(crc32(uniqid(rand(), true))), 0, 5);
$LoadBanc = unserialize($LoadItems['System']['bank']);
?>
<head>
    <title>���� - ������� �<?= @$ouid ?></title>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
    <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
    <link href="style.css" type=text/css rel=stylesheet>
    <style media="screen" type="text/css">
        a.save{
            display: none;
        }

        * HTML a.save{ /* ������ ��� �������� IE */
            display: inline;
        }
    </style>
    <style media="print" type="text/css">
        <!-- 
        .nonprint {
            display: none;
        }
        -->
    </style>
</head>
<body onload="window.focus()" bgcolor="#FFFFFF" text="#000000" marginwidth=5 leftmargin=5 style="padding: 2px;">
    <div align="right" class="nonprint"><a href="#" onclick="window.print();return false;" ><img border=0 align=absmiddle hspace=3 vspace=3 src="http://<?= $_SERVER['SERVER_NAME'] . $SysValue['dir']['dir'] ?>/phpshop/admpanel/img/action_print.gif">�����������</a> | <a href="#" class="save" onclick="document.execCommand('SaveAs');return false;">��������� �� ����<img border=0 align=absmiddle hspace=3 vspace=3 src="http://<?= $_SERVER['SERVER_NAME'] . $SysValue['dir']['dir'] ?>/phpshop/admpanel/img/action_save.gif"></a><br><br></div>
    <table align="center" width="1000" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td valign="top" align="right">
                <?
                $GetIsoValutaOrder = $PHPShopOrder->default_valuta_code;
                if (preg_match("/���/", $GetIsoValutaOrder)) {
                    echo '
	<div id="d1">���������� �1<br />
� �������� ������� �������� ����� ���������� � ������������ ������-������,<br />
 ���� ������� � ���� ������ ��� �������� �� ������ �� ����������� ���������,<br />

 ������������ �������������� ������������� ���������� ���������  �� 2 ������� 2000 �. N 9</div>
';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td valign="top" id="d2">����-������� �<?= @$ouid ?> �� <?= $datas ?></td>
        </tr>
        <tr>
            <td valign="top" >��������: <?= $LoadItems['System']['company'] ?><br />					
                �����: <?= $LoadBanc['org_adres'] ?>, <?= $LoadItems['System']['tel'] ?> <br />							
                ����������������� ����� �������� (���) <?= $LoadBanc['org_inn'] ?>\<?= $LoadBanc['org_kpp'] ?> <br />							
                ���������������� � ��� �����: �� ��	<br />						
                ��������������� � ��� �����:  <?= @$order['Person']['adr_name'] ?>	<br />						
                � ��������-���������� ���������       <br />							
                ����������: <?= @$order['Person']['org_name'] ?>	<br />						
                �����: <?= @$order['Person']['adr_name'] ?> <br />							
                ����������������� ����� ���������� (���) <?= @$order['Person']['org_inn'] ?>/<?= @$order['Person']['org_kpp'] ?> <br />							
            </td>
        </tr>
        <tr>
            <td align="right"><b>������: <?= $PHPShopOrder->default_valuta_code ?></b></td>
        </tr>
        <tr>
            <td valign="top" ><table style="margin-top:10px;" bordercolor="#000000"  width="998" border="1" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="122" align="center">������������ ������ (�������� ����������� �����, ��������� �����)</td>
                        <td width="68" align="center">������� ����-
                            �����</td>
                        <td width="55" align="center">����-
                            ������</td>
                        <td width="113" align="center">���� (�����) �� ������� ���������</td>
                        <td width="92" align="center">��������� ������� (�����, �����), ����� ��� ������</td>
                        <td width="98" align="center">� ���
                            �����
                            �����</td>
                        <td width="97" align="center">��������� ������</td>
                        <td width="80" align="center">����� ������</td>
                        <td width="97" align="center">��������� ������� (�����, �����), ����� � ������ ������</td>
                        <td width="76" align="center">������
                            ��������-
                            �����</td>
                        <td width="76" align="center">�����
                            ��������
                            ����������
                            ����������</td>
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
                    <? echo @$dis; ?>
                    <tr>
                        <td colspan="7"  ><b>����� � ������</b></td>

                        <td align="right"><? echo $this_nds_summa + $summa_nds_dos; ?></td>
                        <td align="right"><? echo $total_summa + $deliveryPrice; ?></td>
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
                                    <td width="417">������������ ����������� ____________________ </td>
                                </tr>
                                <tr>
                                    <td height="29">(�������������� ���������������)</td>
                                </tr>
                                <tr>
                                    <td height="27" align="right"> �.�.</td>
                                </tr>
                                <tr>
                                    <td height="30">�����</td>
                                </tr>
                                <tr>
                                    <td id="d3">����������. ������  ���������  -  ����������,  ������   ��������� - ��������</td>
                                </tr>
                            </table></td>
                        <td width="513" valign="top" align="right"><table width="374" border="0" cellspacing="3" cellpadding="0">
                                <tr>
                                    <td>������� ��������� ____________________ </td>
                                </tr>
                                <tr>
                                    <td height="29">(��������� ������������� � ���������������<br /> 
                                        ����������� ��������������� ���������������)</td>
                                </tr>
                                <tr>
                                    <td height="59" valign="bottom">________________________________________</td>
                                </tr>
                                <tr>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td valign="top">(������� �������������� ���� �� ��������)</td>
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