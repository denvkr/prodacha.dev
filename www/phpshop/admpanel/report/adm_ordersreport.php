<?
require("../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

// Языки
$GetSystems=GetSystems();
$option=unserialize($GetSystems['admoption']);
$Lang=$option['lang'];
require("../language/".$Lang."/language.php");

function getOrdersStatuses($checkedvals = array()) {
    global $SysValue;
    $query = 'SELECT * FROM '.$SysValue['base']['table_name32'].' ORDER by name';
    $result = mysql_query($query);
    while ($status = mysql_fetch_assoc($result)){
        $checked = '';
        if (in_array($status['id'], $checkedvals)) $checked = ' checked="checked" ';
        $stauses.='<span style="color:'.$status['color'].'"><input type="checkbox" name="status['.$status['id'].']" '.$checked.'>'.$status['name'].'</span>';
    }
    $nullchecked = '';
    if (in_array(0, $checkedvals)) $nullchecked = ' checked="checked" ';
    $return = '<span><input type="checkbox" name="status[0]" '.$nullchecked.'>Новый заказ</span>'.$stauses;
    return $return;
}

function ReturnSumma($sum,$disc){
    $kurs=GetKursOrder();
    $sum*=$kurs;
    $sum=$sum-($sum*$disc/100);
    return number_format($sum,"2",".","");
}

$fsdate = date("d-m-Y");
$fedate = date("d-m-Y");
if (isset($_REQUEST['fromdate'])){
    $fsdate = $_REQUEST['fromdate'];
}
if (isset($_REQUEST['todate'])){
    $fedate = $_REQUEST['todate'];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>Отчет по продажам</title>
        <META http-equiv=Content-Type content="text/html; charset=<?=$SysValue['Lang']['System']['charset']?>">
        <LINK href="../css/texts.css" type="text/css" rel=stylesheet>
              <LINK href="../css/dateselector.css" type="text/css" rel=stylesheet>
              <LINK href="../css/tab.winclassic.css" type="text/css" rel=stylesheet>
              <?
//Check user's Browser
              if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE"))
                  echo "<script language=JavaScript src='../editor3/scripts/editor.js'></script>";
              else
                  echo "<script language=JavaScript src='../editor3/scripts/moz/editor.js'></script>";
              ?>
        <SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
        <SCRIPT language=JavaScript src="../java/popup_lib.js"></SCRIPT>
        <SCRIPT language=JavaScript src="../java/dateselector.js"></SCRIPT>
        <script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
        <script type="text/javascript" src="../java/tabpane.js"></script>
        <script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_windows.js"></script>
        <script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_interface.js"></script>
        <script>
            DoResize(<? echo $GetSystems['width_icon']?>,950,650);
        </script>
    </head>
    <body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0" onload="DoCheckLang(location.pathname,<?=$SysValue['lang']['lang_enabled']?>);preloader(0)">
        <table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
            <tr bgcolor="#ffffff">
                <td style="padding:10">
                    <b><span name=txtLang id=txtLang>Отчет по продажам</span> </b><br>
                    &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Выберите необходимые параметры</span>.
                </td>
                <td align="right">
                    <img src="../img/i_balance_med[1].gif" border="0" hspace="10">
                </td>
            </tr>
        </table>
        <div style="width:96%; margin: 20px;">
            <form method="POST" name="report">
                <div>
                    <h2>Учитывать статусы:</h2>
                    <?php
                        $chkarr = array();
                        if (isset($_REQUEST['status'])) $chkarr = array_keys($_REQUEST['status']);
                        echo(getOrdersStatuses($chkarr));
                    ?>
                </div>
                <div>
                    <h2>Диапазон дат:</h2>
                    <span>
                        С даты:<input type="text" style="width:80" name="fromdate" value="<?php echo($fsdate)?>">
                        <IMG onclick="popUpCalendar(this, report.fromdate, 'dd-mm-yyyy');" height=16 hspace=3 src="../icon/date.gif" width=16 border=0 align="absmiddle">
                    </span>
                    <span>
                        По дату:<input type="text" style="width:80" name="todate" value="<?php echo($fedate)?>">
                        <IMG onclick="popUpCalendar(this, report.todate, 'dd-mm-yyyy');" height=16 hspace=3 src="../icon/date.gif" width=16 border=0 align="absmiddle">
                    </span>
                </div>
                <div style="margin-top: 20px;">
                    <input type="hidden" name="submit" value="1">
                    <input type="submit" value="Сгенерировать отчет" style="width:100%;height: 25px;">
                </div>
            </form>
            <hr>
            <?php if (isset($_REQUEST['submit'])): ?>
                <?php
                    $datefrom = strtotime($_REQUEST['fromdate']);
                    $dateto = strtotime($_REQUEST['todate']);
                    $datefilter = ' datas BETWEEN '.mktime('0', '0', '0', date('m', $datefrom), date('d', $datefrom), date('Y', $datefrom)).' AND '.mktime('23', '59', '59', date('m', $dateto), date('d', $dateto), date('Y', $dateto)).' ';
                    if (isset($_REQUEST['status'])){
                        $stids = array_keys($_REQUEST['status']);
                        $statusfilter = 'statusi IN ('.implode(',', $stids).')';
                    }else{
                        $statusfilter = ' 1=2 ';
                    }
                    $query = 'SELECT * FROM '.$SysValue['base']['table_name1'].' WHERE '.$statusfilter.' AND '.$datefilter.' ORDER BY datas ASC';
                    //echo ($query);
                    $result = mysql_query($query);
                ?>
                <?php if(mysql_num_rows($result)>0): ?>
                <?php
                    $sum = 0;
                    $num = 0;
                ?>
                <div style="height:350px; overflow: auto;">
                <table width="100%" bgcolor="#808080">
                    <tr><td width="33%">Дата</td><td  width="33%">Кол-во</td><td  width="33%">Сумма(без учета доставки)</td></tr>
                    <?php while($row = mysql_fetch_assoc($result)): ?>
                    <?php
                        $id=$row['id'];
                        $uid=$row['uid'];
                        $datas=$row['datas'];
                        $order=unserialize($row['orders']);
                    ?>
                    <tr bgcolor="ffffff"  onmouseover="show_on('r<?php echo $row['id'] ?>')" id="r<?php echo $row['id'] ?>" onmouseout="show_out('r<?php echo $row['id'] ?>')" class=row onclick="miniWin('../order/adm_visitorID.php?visitorID=<?php echo $row['id'] ?>',650,500)">
                        <td class=forma >
                        <?php echo(dataV($datas,"shot"))?>
                        </td>
                        <td class=forma>
                        <?php echo($order['Cart']['num'])?> шт.
                        </td>
                        <td class=forma >
                        <?php echo(ReturnSumma($order['Cart']['sum'],$order['Person']['discount']));//print_r($order['Cart']);?>
                        </td>
                    </tr>
                    <?php
                        $sum += ReturnSumma($order['Cart']['sum'],$order['Person']['discount']);
                        $num += $order['Cart']['num'];
                    ?>
                    <?php endwhile; ?>
                    <tr><td width="33%">Всего</td><td  width="33%"><?php echo($num); ?></td><td  width="33%"><?php echo($sum); ?></td></tr>
                </table>
                </div>
                <?php else: ?>
                    <h1>Ничего не найденно</h1>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </body>
</html>




