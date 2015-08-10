<?php

$TitlePage = __("Новости");

// Подключить JS библиотеку
$addJS = true;

function actionStart() {
    global $PHPShopInterface;
    $PHPShopInterface->size = "670,550";
    $PHPShopInterface->link = "news/adm_newsID.php";
    $PHPShopInterface->setCaption(array("Дата", "10%"), array("Заголовок", "45%"), array("Краткая информация", "45%"), array('', "3%"));

    // Поиск
    if ($_REQUEST['var1'] == 'search') {

        $where = array(
            'zag' => " LIKE '%" . $_REQUEST['var2'] . "%'",
            'podrob' => " LIKE '%" . $_REQUEST['var2'] . "%'",
            'id' => "='" . $_REQUEST['var2'] . "'",
            'datas' => "='" . $_REQUEST['var2'] . "'"
        );
        
        $search_text=$_REQUEST['var2'];
        
    } 
    elseif($_REQUEST['var1'] == 'all'){
        $where=null;
    }
    else {
        // Сортировка по дате
        if (empty($_REQUEST['var1']))
            $pole1 = date("U") - 86400;
        else
            $pole1 = PHPShopDate::GetUnixTime($_REQUEST['var1']) - 86400;

        if (empty($_REQUEST['var2']))
            $pole2 = date("U");
        else
            $pole2 = PHPShopDate::GetUnixTime($_REQUEST['var2']) + 86400;

        $where['datau'] = ' BETWEEN ' . $pole1 . ' AND ' . $pole2;
        $search_text=null;
    }


    // Таблица с данными
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['news']);
    $PHPShopOrm->Option['where'] = ' or ';
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), $where, array('order' => 'id DESC'), array('limit' => 1000));
    if (is_array($data))
        foreach ($data as $row) {
            $PHPShopInterface->setRow($row['id'], $row['datas'], $row['zag'], substr(strip_tags($row['kratko']), 0, 150) . "...", array($PHPShopInterface->setCheckbox($row['id'], $row['id'], false, false), $link = false));
        }

    $PHPShopIcon = new PHPShopIcon($start = 100);
    $PHPShopIcon->padding = 0;
    $PHPShopIcon->margin = 0;

    if (empty($_REQUEST['var1']) or $_REQUEST['var1'] == 'search' or $_REQUEST['var1'] == 'all')
        $pole1 = PHPShopDate::get(date("U") - (86400 * 10), false);
        
    else
        $pole1 = $_REQUEST['var1'];

    if (empty($_REQUEST['var2']) or $_REQUEST['var1'] == 'search')
        $pole2 = PHPShopDate::get(date("U") + 86400, false);
    else
        $pole2 = $_REQUEST['var2'];

    // Календарь
    $Calendar = $PHPShopIcon->setForm(
            $PHPShopIcon->setInputText(false, 'pole1', $pole1, $size = 70, $description = false, $float = "left") .
            $PHPShopIcon->setImage("icon/date.gif", 16, 16, 'absmiddle', "5", $style = 'float:left', $onclick = "popUpCalendar(this, calendar.pole1, 'dd-mm-yyyy');") .
            $PHPShopIcon->setInputText(false, 'pole2', $pole2, $size = 70, $description = false, $float = "left") .
            $PHPShopIcon->setImage("icon/date.gif", 16, 16, 'absmiddle', "5", $style = 'float:left', $onclick = "popUpCalendar(this, calendar.pole2, 'dd-mm-yyyy');") .
            $PHPShopIcon->setInput("button", "date_but", "Показать", "right", 70, "DoReload('news',calendar.pole1.value, calendar.pole2.value,'core')")
            , $action = false, $name = "calendar", 'get');

    $Tab1.= $PHPShopIcon->add($Calendar, 270, 10, 5) .
            $PHPShopIcon->setBorder();

    // Форма поиска
    $Search = $PHPShopIcon->setForm(
            $PHPShopIcon->setInputText(__('Поиск: '), 'words', $search_text, $size = 180, $description = false, $float = "left", false,  __('Поиск по id, дате или содержанию')) .
            $PHPShopIcon->setInput("button", "search_but", "Искать", "right", 70, "DoReload('news','search',search.words.value, 'core');"), $action = false, $name = "search", 'get');

    $Tab1.= $PHPShopIcon->add($Search, 300, 5) .
            $PHPShopIcon->setBorder();

    $Tab1.= $PHPShopIcon->setIcon("icon/page_new.gif", __('Новая новость'), "PHPShopJS.news.addnew();") .
            $PHPShopIcon->setIcon("icon/layout_content.gif", __('Вывод всех новостей'), "DoReload('news','all',null, 'core');") .
            $PHPShopIcon->setBorder();

    // Заполнение селектора
    $selact_value[] = array(__('С отмеченными'), 0, 'selected');
    $selact_value[] = array(__('Удалить'), 46, false);
    $selact_value[] = array(__('Разослать пользователям'), 47, false);
    $Select = $PHPShopIcon->setSelect('action', $selact_value, 200, 'none', false, $onchange = 'PHPShopJS.action(this.value)', $height = false, $size = 1, $multiple = false, $id = 'actionSelect');

    $Tab1.=$PHPShopIcon->add($Select, 100);

    $Tab1.=$PHPShopIcon->setIcon("icon/chart_organisation_add.gif", __('Отметить все'), "PHPShopJS.selectall(1)") .
            $PHPShopIcon->setBorder() .
            $PHPShopIcon->setIcon("icon/chart_organisation_delete.gif", __('Снять отметку'), "PHPShopJS.selectall(2)");

    $PHPShopIcon->setTab($Tab1);
    $PHPShopInterface->addTop($PHPShopIcon->Compile(true));
    $PHPShopInterface->Compile('interfaces','flag_form',null);
}

?>