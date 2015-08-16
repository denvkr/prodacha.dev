<?php

$TitlePage = "Журнал событий";

/*
  $addTab='<td align="right"><form name="product_edit" id="product_edit">';
  $PHPShopInterface->padding=0;

  // Календарь, расечет даты
  if($_GET['sortdate_start']) $sortdate_start = $_GET['sortdate_start'];
  else $sortdate_start = PHPShopDate::dataV(date("U")-86400,false);

  if($_GET['sortdate_end']) $sortdate_end = $_GET['sortdate_end'];
  else $sortdate_end = PHPShopDate::dataV(false,false);

  $addTab.=$PHPShopInterface->setTable(
  $PHPShopInterface->setCalendar('sortdate_start',$align='left',$icon='icon/date.gif'),
  $PHPShopInterface->setInput("text","sortdate_start",$sortdate_start,"none",70),
  $PHPShopInterface->setCalendar('sortdate_end',$align='left',$icon='icon/date.gif'),
  $PHPShopInterface->setInput("text","sortdate_end",$sortdate_end,"none",70),
  $PHPShopInterface->setInput("submit","send",'Выбрать',"none",70),
  $PHPShopInterface->setInput("hidden","plugin",'admlog',"none",70)
  );
  $addTab.='</form></td>';
  $PHPShopInterface->padding=5;
 */

function actionStart() {
    global $PHPShopInterface, $_classPath;


    $PHPShopInterface->setCaption(array("Дата", "10%"), array("Имя", "10%"), array("IP", "10%"), array("Раздел", "50%"), array("Файл", "20%"));

    // Настройки модуля
    PHPShopObj::loadClass("modules");
    $PHPShopModules = new PHPShopModules($_classPath . "modules/");

    // Настройка модуля
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.admlog.admlog_system"));
    $mod_option = $PHPShopOrm->select();

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.admlog.admlog_log"));
    $PHPShopOrm->debug = false;

    // Сортировка по дате
    if (empty($_REQUEST['var3']))
        $pole1 = date("U") - 86400;
    else
        $pole1 = PHPShopDate::GetUnixTime($_REQUEST['var3']) - 86400;

    if (empty($_REQUEST['var4']))
        $pole2 = date("U");
    else
        $pole2 = PHPShopDate::GetUnixTime($_REQUEST['var4']) + 86400;

    $where['date'] = ' BETWEEN ' . $pole1 . ' AND ' . $pole2;


    $data = $PHPShopOrm->select(array('*'), $where, array('order' => 'id DESC'), array('limit' => 100));

    if (!empty($mod_option['enabled'])) {
        $PHPShopInterface->size = "630,530";
        $PHPShopInterface->link = "../modules/admlog/admpanel/adm_admlog_back.php";
    }

    if (is_array($data))
        foreach ($data as $row) {
            extract($row);

            $PHPShopInterface->setRow($id, PHPShopDate::dataV($date), $user, $ip, $title, $file);
        }

    $PHPShopIcon = new PHPShopIcon($start = 100);
    $PHPShopIcon->padding = 0;
    $PHPShopIcon->margin = 0;

    // Сортировка по дате
    if (empty($_REQUEST['var3']))
        $pole1 = PHPShopDate::get(date("U") - (86400), false);
    else
        $pole1 = $_REQUEST['var3'];

    if (empty($_REQUEST['var4']))
        $pole2 = PHPShopDate::get(date("U") + 86400, false);
    else
        $pole2 = $_REQUEST['var4'];

    // Календарь
    $Calendar = $PHPShopIcon->setForm(
            $PHPShopIcon->setInputText(false, 'pole1', $pole1, $size = 70, $description = false, $float = "left") .
            $PHPShopIcon->setImage("icon/date.gif", 16, 16, 'absmiddle', "5", $style = 'float:left', $onclick = "popUpCalendar(this, calendar.pole1, 'dd-mm-yyyy');") .
            $PHPShopIcon->setInputText(false, 'pole2', $pole2, $size = 70, $description = false, $float = "left") .
            $PHPShopIcon->setImage("icon/date.gif", 16, 16, 'absmiddle', "5", $style = 'float:left', $onclick = "popUpCalendar(this, calendar.pole2, 'dd-mm-yyyy');") .
            $PHPShopIcon->setInput("button", "date_but", "Показать", "right", 70, "DoReload('modules','admlog','admlog',calendar.pole1.value,calendar.pole2.value)")
            , $action = false, $name = "calendar", 'get');

    $Tab1.= $PHPShopIcon->add($Calendar, 270, 10, 5) .
            $PHPShopIcon->setBorder();

    $PHPShopIcon->setTab($Tab1);
    $PHPShopInterface->addTop($PHPShopIcon->Compile(true));

    $PHPShopInterface->Compile();
}

?>