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
        $datas = intval($row['datas']);
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
            
            // Накрутка
            if(empty($list)) $overprice=0;
            else $overprice=$list/100;

            //прибыль
            $table[date("Y", $datas)]['month'][date("F", $datas)][1] += ($data['price'] * $data['num'] * $overprice);
            // кол-во
            $table[date("Y", $datas)]['month'][date("F", $datas)][2] += $data['num'];
            // выручка
            $table[date("Y", $datas)]['month'][date("F", $datas)][3] += ($data['price'] * $data['num']);

            $table[date("Y", $datas)]['monthNum'] = date("n", $datas);
            $rowName = "Категория товаров";
        }


        @$i++;
    }

    /// строим тело таблицы для вывода в магазин и тело csv файла

    foreach ($table as $year => $yearV) {
        foreach ($yearV['month'] as $month => $value) {
            @$disp.='
	<tr class="row padding" >
	
    <td>' . $month . ' (' . $year . ')</td>
	<td>
	' . $value[3] . ' </td>
	<td>
	' . $value[2] . ' </td>
	<td>
	' . $value[1] . ' </td>

    </tr>
	';

            $value1sum += $value[1];
            $value2sum += $value[2];
            $value3sum += $value[3];

            $csvDisp .= "$month  ($year);{$value[3]};{$value[2]};{$value[1]}\n";
        }
    }

    // выводим итого
    if ($disp)
        @$disp.='
        	
	<tr class="row padding" >
	
    <td><b>Итого:</b></td>
	<td><b>
	' . $value3sum . '</b> </td>
	<td><b>
	' . $value2sum . '</b> </td>
	<td><b>
	' . $value1sum . '</b> </td>

    </tr>
	';


    // формируем содержание csv файла

    $csv = "Месяц (Год);Выручка;Количество единиц товара;Прибыль\n$csvDisp Итого;$value1sum;$value2sum;$value3sum\n";

    // создаём файл
    $file = "orders_stat3_".$sec.".csv";
    @$fp = fopen("../csv/" . $file, "w+");
    if ($fp) {
        //stream_set_write_buffer($fp, 0);
        fputs($fp, $csv);
        fclose($fp);
    }




    // формируем данные для графика
    require_once '../../lib/chart/open-flash-chart.php';

    // create the chart:
    $g = new graph();
    $g->title(cp1251_to_utf8("Динамика прибыли"), '{font-size: 26px;}');

    foreach ($table as $year => $yearV) {


        $bar_1 = new line_hollow(2, 4, '#' . gencolor(), 'Spoon sales', 10);
        $bar_1->key(cp1251_to_utf8('Прибыль за ' . $year . ' год'), 10);

        $bar_1->data = array("January" => 0, "February" => 0,
            "March" => 0, "April" => 0, "May" => 0, "June" => 0, "July" => 0,
            "August" => 0, "September" => 0, "October" => 0, "November" => 0, "December" => 0);



        foreach ($yearV['month'] as $month => $value) {
            $bar_1->data[$month] = $value[1];
            if ($value[1] > $yMax)
                $yMax = $value[1];
        }



// add the 3 bar charts to it:
        $g->data_sets[] = $bar_1;
    }


//
    $g->set_x_labels(array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'));
// set the X axis to show every 2nd label:
    $g->set_x_label_style(10, '#9933CC', 0, 1);
// and tick every second value:
    $g->set_x_axis_steps(2);
//

    $g->set_y_max($yMax * 1.2);
    $g->y_label_steps(10);
    $g->set_y_legend(cp1251_to_utf8('RUR'), 12, '0x736AFF');
    $graphContent = $g->render();

    // создаём файл
    $file = "orders_stat3_graph_".$sec.".csv";
    @$fp = fopen("../csv/" . $file, "w+");
    if ($fp) {
        //stream_set_write_buffer($fp, 0);
        fputs($fp, $graphContent);
        fclose($fp);
    }
    ////////////////////////
    // формируем данные для графика
    require_once '../../lib/chart/open-flash-chart.php';

    // create the chart:
    $g = NULL;
    $g = new graph();
    $g->title(cp1251_to_utf8("Динамика выручки"), '{font-size: 26px;}');

    foreach ($table as $year => $yearV) {


        $bar_1 = new line_hollow(2, 4, '#' . gencolor(), 'Spoon sales', 10);
        $bar_1->key(cp1251_to_utf8('Прибыль за ' . $year . ' год'), 10);

        $bar_1->data = array("January" => 0, "February" => 0,
            "March" => 0, "April" => 0, "May" => 0, "June" => 0, "July" => 0,
            "August" => 0, "September" => 0, "October" => 0, "November" => 0, "December" => 0);



        foreach ($yearV['month'] as $month => $value) {
            $bar_1->data[$month] = $value[3];
            if ($value[3] > $yMax)
                $yMax = $value[3];
        }



// add the 3 bar charts to it:
        $g->data_sets[] = $bar_1;
    }


//
    $g->set_x_labels(array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'));
// set the X axis to show every 2nd label:
    $g->set_x_label_style(10, '#9933CC', 0, 1);
// and tick every second value:
    $g->set_x_axis_steps(2);
//

    $g->set_y_max($yMax * 1.7);
    $g->y_label_steps(10);
    $g->set_y_legend(cp1251_to_utf8('RUR'), 12, '0x736AFF');
    $graphContent = $g->render();

    // создаём файл
    $file = "orders_stat3_graph_".$sec.".csv";
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
        ' . open_flash_chart_object('100%', 350, './csv/orders_stat3_graph_'.$sec.'.csv', false, $baseURL) . ' 
    </div>
<div align="left" id="interfacesWin" name="interfacesWin"  style="width:100%;' . @$razmer . ';overflow:auto"> 


<table width="100%"  cellpadding="0" cellspacing="0" style="border: 1px;">
<tr>
	<td valign="top">

<table cellpadding="0" cellspacing="1" width="100%" border="0">
<tr>
	<td width="100" id="pane" align="center"><img  src="icon/blank.gif"  width="1" height="1" border="0" onLoad="starter(\'visiter\');" align="left"><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Месяц (Год)</span></td>
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