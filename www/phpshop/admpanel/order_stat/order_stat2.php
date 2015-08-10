<?php

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
    global $table_name1;
    
    $sec=md5(date('y-m-d').$_SESSION['pasPHPSHOP']);

    if (empty($pole1))
        $pole1 = date("U") - 86400;
    else
        $pole1 = GetUnicTime($pole1) - 86400;
    if (empty($pole2))
        $pole2 = date("U") + 86400;
    else
        $pole2 = GetUnicTime($pole2) + 86400;


    /////// получаем массив наименований категорий.
    $sql = "select id, name, parent_to from phpshop_categories WHERE 1";
    $result = mysql_query($sql);
    while (@$row = mysql_fetch_array(@$result)) {
        $massCatData[$row['id']]['name'] = $row['name'];
        $massCatData[$row['id']]['parent_to'] = $row['parent_to'];
    }
    //////////////////////
    /////// получаем массив клиентов .
    $sql = "select id, name from phpshop_shopusers WHERE 1";
    $result = mysql_query($sql);
    while (@$row = mysql_fetch_array(@$result)) {
        $massShopUsersData[$row['id']] = $row['name'];
    }
    //////////////////////
    /////// получаем массив менеджеров .
    $sql = "select id, name from phpshop_users WHERE 1";
    $result = mysql_query($sql);
    while (@$row = mysql_fetch_array(@$result)) {
        $massAdminData[$row['id']] = $row['name'];
    }
    //////////////////////
    /////// получаем массив принадлежности товара к категории
    $sql = "select id, category from phpshop_products WHERE 1";
    $result = mysql_query($sql);
    while (@$row = mysql_fetch_array(@$result)) {
        $massProdCats[$row['id']] = $row['category'];
    }
    //////////////////////
    /////// получаем массив видов оплат
    $sql = "select id, name from phpshop_payment_systems WHERE 1";
    $result = mysql_query($sql);
    while (@$row = mysql_fetch_array(@$result)) {
        $massPayments[$row['id']] = $row['name'];
    }
    //////////////////////


    $sql = "select * from $table_name1 where datas<'$pole2' and datas>'$pole1' and statusi>0 order by id desc";


    @$result = mysql_query($sql);
    while (@$row = mysql_fetch_array(@$result)) {
        $id = $row['id'];
        $datas = $row['datas'];
        $uid = $row['uid'];
        $user = $row['user'];
        $admin = $row['admin'];
        $order = unserialize($row['orders']);
        $status = unserialize($row['status']);
        $statusi = $row['statusi'];

        $order['Person']['discount'];


        foreach ($order[Cart][cart] as $id => $data) {
            if ($words AND $words != $massProdCats[$id])
                continue;

            //прибыль
            $table[$massAdminData[$admin]][$massShopUsersData[$user]][$massPayments[$order['Person']['order_metod']]][1] += ($data['price'] * $data['num'] * 0.3);
            // кол-во
            $table[$massAdminData[$admin]][$massShopUsersData[$user]][$massPayments[$order['Person']['order_metod']]][2] += $data['num'];
            // выручка
            $table[$massAdminData[$admin]][$massShopUsersData[$user]][$massPayments[$order['Person']['order_metod']]][3] += ($data['price'] * $data['num']);
        }


        @$i++;
    }

    /// строим тело таблицы для вывода в магазин и тело csv файла

    foreach ($table as $admin => $adminV) {
        foreach ($adminV as $user => $userV) {
            foreach ($userV as $oplata => $value) {
                @$disp.='
	<tr class=row >
	
    <td>' . $admin . '</td>
	<td>
	' . $user . ' </td>
	<td>
	' . $oplata . ' </td>
	<td>
	' . $value[3] . ' </td>
	<td>
	' . $value[2] . ' </td>
	<td>
	' . $value[1] . ' </td>

    </tr>
	';
                if ($admin)
                    $colNames[] = cp1251_to_utf8($admin);

                $csvDisp .= "$admin;$user;$oplata;{$value[3]};{$value[2]};{$value[1]}\n";
                $user = "";
                $admin = "";
                $oplata1sum += $value[1];
                $oplata2sum += $value[2];
                $oplata3sum += $value[3];


                $oplata1sum2 += $value[1];
                $oplata2sum2 += $value[2];
                $oplata3sum2 += $value[3];
            }
            @$disp.='
	<tr class="row padding">
	
    <td>' . $admin . '</td>
	<td>
	' . $user . ' </td>
	<td>
	<b>Итого:</b> </td>
	<td>
	<b>' . $oplata3sum . '</b> </td>
	<td>
	<b>' . $oplata2sum . '</b> </td>
	<td>
	<b>' . $oplata1sum . '</b> </td>

    </tr>
	';
            $csvDisp .= ";;Итого:;$oplata3sum;$oplata2sum;$oplata1sum\n";

            $oplata1sum = 0;
            $oplata2sum = 0;
            $oplata3sum = 0;
           
        }

        $bar_1data[] = $oplata1sum2;
        $bar_2data[] = $oplata3sum2;
        $bar_3data[] = $oplata2sum2 * 1000;

        if ($oplata1sum2 > $yMax)
            $yMax = $oplata1sum2;
        if ($oplata2sum2 > $yMax)
            $yMax = $oplata2sum2;
        if ($oplata3sum2 > $yMax)
            $yMax = $oplata3sum2;

        $oplata1sum2 = 0;
        $oplata2sum2 = 0;
        $oplata3sum2 = 0;

        
    }


    // формируем содержание csv файла

    $csv = "Менеджеры;Пользователи;Вид оплаты;Выручка;Количество единиц товара;Прибыль\n$csvDisp";

    // создаём файл
    $file = "orders_stat2_".$sec.".csv";
    @$fp = fopen("../csv/" . $file, "w+");
    if ($fp) {
        //stream_set_write_buffer($fp, 0);
        fputs($fp, $csv);
        fclose($fp);
    }



    // формируем данные для графика
    require_once '../../lib/chart/open-flash-chart.php';


    $bar_1 = new bar(50, '#' . gencolor());
    $bar_1->key(cp1251_to_utf8('Прибыль'), 10);

    $bar_2 = new bar(50, '#' . gencolor());
    $bar_2->key(cp1251_to_utf8('Выручка'), 10);

    $bar_3 = new bar(50, '#' . gencolor());
    $bar_3->key(cp1251_to_utf8('Количество (x1000)'), 10);


    $bar_1->data = $bar_1data;
    $bar_2->data = $bar_2data;
    $bar_3->data = $bar_3data;

    // create the chart:
    $g = new graph();
    $g->title(cp1251_to_utf8("Отчёт по сотрудникам"), '{font-size: 26px;}');

// add the 3 bar charts to it:
    $g->data_sets[] = $bar_2;
    $g->data_sets[] = $bar_1;
    $g->data_sets[] = $bar_3;


    //
    $g->set_x_labels($colNames);
// set the X axis to show every 2nd label:
    $g->set_x_label_style(10, '#9933CC', 0, 1);
// and tick every second value:
    $g->set_x_axis_steps(2);
//

    $g->set_y_max($yMax * 1.2);
    $g->y_label_steps(10);
    $g->set_y_legend(cp1251_to_utf8('RUR / RUR / quantity'), 12, '0x736AFF');
    $graphContent = $g->render();

    // создаём файл
    $file = "orders_stat2_graph_".$sec.".csv";
    @$fp = fopen("../csv/" . $file, "w+");
    if ($fp) {
        //stream_set_write_buffer($fp, 0);
        fputs($fp, $graphContent);
        fclose($fp);
    }
    ////////////////////////





    require_once '../../lib/chart/open_flash_chart_object.php';
    $baseURL = "/phpshop/lib/chart/";

    if ($i > 30)
        $razmer = "height:600;";
    
    if(empty($_COOKIE['stat_graph']))
        $stat_graph_style='display:none;';
    else $stat_graph_style='display:block;';
    
    $_Return = ('
        
    <div id="graph"  style="'.$stat_graph_style.'width:100%; text-align:center;padding-left:5px;"> 
        ' . open_flash_chart_object('100%', 350, './csv/orders_stat2_graph_'.$sec.'.csv', false, $baseURL) . ' 
    </div>
        
<div align="left" id="interfacesWin" name="interfacesWin"  style="width:100%;' . @$razmer . ';overflow:auto"> 


<table width="100%"  cellpadding="0" cellspacing="0" style="border: 1px;">
<tr>
	<td valign="top">

<table cellpadding="0" cellspacing="1" width="100%" border="0">
<tr>
	<td width="100" id="pane" align="center"><img  src="icon/blank.gif"  width="1" height="1" border="0" onLoad="starter(\'visiter\');" align="left"><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Менеджеры</span></td>
	<td id="pane" width="130" align="center"><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Клиенты</span></td>
	<td id="pane" width="130" align="center"><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Вид оплаты</span></td>
	<td id="pane" width="130" align="center"><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Выручка</span></td>
	<td id="pane" width="130" align="center"><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Количество единиц товара</span></td>
	<td id="pane" width="130" align="center"><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Прибыль</span></td>
</tr>
' . @$disp . '
</table>
    </td>
</tr>
</table>
</form>
</div>
 ' . '
 ');
    return $_Return;
}

function gencolor() { //Функция генерации рандомных цветов
    for ($i = 0; $i <= 6 - 1; $i++) {
        $randint = rand(0, 1);
        if ($randint == 0) {
            $gencodint = chr(rand(48, 57));
            $pass = $pass . $gencodint;
        }
        if ($randint == 1) {
            $gencodstrn = chr(rand(97, 102));
            $pass = $pass . $gencodstrn;
        }
    }

    return $pass;
}

?>