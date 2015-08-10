<?php

// Подключаем библиотеку поддержки.
require_once "../lib/Subsys/JsHttpRequest/Php.php";
$JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");

// Получаем запрос.
$q = @$_REQUEST['q'];


$host = 'www.phpshop.ru';
$path1 = '/help/Content/';
$path2 = '';
$file = '';
$number = '';

//$q = @$_GET['q'];
$q = trim($q);


function send($host, $uri, $limit = 0) {
    $nn = "\r\n";
    $request = "GET " . $uri . " HTTP/1.0" . $nn .
            "Referer: " . $host . " " . $nn .
            "Content-Type: application/x-www-form-urlencoded" . $nn .
            "Host: " . $host . $nn . $nn;
    flush();
    $fp = fsockopen("$host", 80, $errno, $errstr, 60);
    if (!$fp) {
        echo "$errstr ($errno)<br>\n";
        exit;
    }

    fputs($fp, $request);
    if (!$limit) {
        while (!feof($fp)) {
            @$data.=fgets($fp, 4096);
        }
    } else {
        $cur = 0;
        while (($cur < $limit) && (!feof($fp))) {
            @$data.=fgets($fp, $limit);
            $cur++;
        }
    }
    fclose($fp);

    // Картинки прописываем пути
    $return = eregi_replace("../../Interface/zoom.gif", "http://phpshop.ru/help/Interface/zoom.gif", $data);
    $return = eregi_replace("../Images/", "http://phpshop.ru/help/Content/Images/", $return);
    $return = eregi_replace("../tooltip.js", "http://phpshop.ru/help/Content/tooltip.js", $return);
    return $return;
}


//Выбираем нужный файл на основании переменной
switch ($q) {

    case("docsstyle"):
        $path2 = 'control_panel/';
        $file = 'option.html';
        $number = 8;
        break;

    case("promo"):
        $path2 = 'control_panel/';
        $file = 'option.html';
        $number = 3;
        break;

    case("recvizit"):
        $path2 = 'control_panel/';
        $file = 'option.html';
        $number = 2;
        break;

    case("system"):
        $path2 = 'control_panel/';
        $file = 'option.html';
        $number = 1;
        break;

    case("sql"):
        $path2 = 'control_panel/';
        $file = 'base.html';
        $number = 5;
        break;

    case("sql_file"):
        $path2 = 'control_panel/';
        $file = 'base.html';
        $number = 6;
        break;

    case("dumper"):
        $path2 = 'control_panel/';
        $file = 'base.html';
        $number = 7;
        break;

    case("statusID"):
        $path2 = 'control_panel/';
        $file = 'orders.html';
        $number = 6;
        break;

    case("ordersID"):
        $path2 = 'control_panel/';
        $file = 'orders.html';
        $number = 2;
        break;

    case("sort_groupID"):
        $path2 = 'control_panel/';
        $file = 'catalogue.html';
        $number = 6;
        break;

    case("sortID"):
        $path2 = 'control_panel/';
        $file = 'catalogue.html';
        $number = 5;
        break;

    case("catalogID"):
        $path2 = 'control_panel/';
        $file = 'catalogue.html';
        $number = 2;
        break;

    case("cat_prod"):
        $path2 = 'control_panel/';
        $file = 'catalogue.html';
        $number = 1;
        break;
    case("productID"):
        $path2 = 'control_panel/';
        $file = 'catalogue.html';
        $number = 4;
        break;
    case("sort"):
        $path2 = 'control_panel/';
        $file = 'catalogue.html';
        $number = 5;
        break;
    case("sort_group"):
        $path2 = 'control_panel/';
        $file = 'catalogue.html';
        $number = 6;
        break;

    case("page"):
        $path2 = 'control_panel/';
        $file = 'service.html';
        $number = 1;
        break;

    case("news"):
        $path2 = 'control_panel/';
        $file = 'service.html';
        $number = 2;
        break;

    case("baner"):
        $path2 = 'control_panel/';
        $file = 'service.html';
        $number = 3;
        break;

    case("page_menu"):
        $path2 = 'control_panel/';
        $file = 'service.html';
        $number = 4;
        break;


    case("links"):
        $path2 = 'control_panel/';
        $file = 'service.html';
        $number = 5;
        break;
    case("opros"):
        $path2 = 'control_panel/';
        $file = 'service.html';
        $number = 6;
        break;
    case("rating"):
        $path2 = 'control_panel/';
        $file = 'service.html';
        $number = 7;
        break;

    case("menu"):
        $path2 = 'control_panel/';
        $file = 'service.html';
        $number = 4;
        break;

    case("gbook"):
        $path2 = 'control_panel/';
        $file = 'service.html';
        $number = 8;
        break;

    case("orders"):
        $path2 = 'control_panel/';
        $file = 'orders.html';
        $number = 1;
        break;
    case("order_payment"):
        $path2 = 'control_panel/';
        $file = 'orders.html';
        $number = 3;
        break;
    case("stats1"):
        $path2 = 'control_panel/';
        $file = 'orders.html';
        $number = 4;
        break;
    case("order_status"):
        $path2 = 'control_panel/';
        $file = 'orders.html';
        $number = 5;
        break;
    case("shopusers"):
        $path2 = 'control_panel/';
        $file = 'users.html';
        $number = 1;
        break;
    case("shopusersID"):
        $path2 = 'control_panel/';
        $file = 'users.html';
        $number = 2;
        break;
    case("shopusers_status"):
        $path2 = 'control_panel/';
        $file = 'users.html';
        $number = 4;
        break;
    case("shopusers_statusID"):
        $path2 = 'control_panel/';
        $file = 'users.html';
        $number = 5;
        break;
    case("shopusers_notice"):
        $path2 = 'control_panel/';
        $file = 'users.html';
        $number = 7;
        break;
    case("shopusers_messages"):
        $path2 = 'control_panel/';
        $file = 'users.html';
        $number = 8;
        break;
    case("comment"):
        $path2 = 'control_panel/';
        $file = 'users.html';
        $number = 9;
        break;

    case("users"):
        $path2 = 'control_panel/';
        $file = 'managers.html';
        $number = 1;
        break;


    case("usersID"):
        $path2 = 'control_panel/';
        $file = 'managers.html';
        $number = 2;
        break;

    case("users_jurnal"):
        $path2 = 'control_panel/';
        $file = 'managers.html';
        $number = 3;
        break;
    case("users_jurnal_black"):
        $path2 = 'control_panel/';
        $file = 'managers.html';
        $number = 4;
        break;

    case("discount"):
        $path2 = 'control_panel/';
        $file = 'option.html';
        $number = 4;
        break;
    case("valuta"):
        $path2 = 'control_panel/';
        $file = 'option.html';
        $number = 5;
        break;
    case("delivery"):
        $path2 = 'control_panel/';
        $file = 'option.html';
        $number = 6;
        break;
    case("servers"):
        $path2 = 'control_panel/';
        $file = 'option.html';
        $number = 7;
        break;
    case("rssgraber_chanels"):
        $path2 = 'control_panel/';
        $file = 'option.html';
        $number = 9;
        break;

    case("csv"):
        $path2 = 'control_panel/';
        $file = 'base.html';
        $number = 2;
        break;
    case("csv_base"):
        $path2 = 'control_panel/';
        $file = 'base.html';
        $number = 3;
        break;
    case("csv_base"):
        $path2 = 'control_panel/';
        $file = 'base.html';
        $number = 4;
        break;
    case("csv1c"):
        $path2 = 'control_panel/';
        $file = 'base.html';
        $number = 8;
        break;
}

if (!$file) {
    $result = '
	<h2>Ошибка</h2>
	К сожалению, по данному разделу справка не найдена. Можете обратиться в <a href="http://help.phpshop.ru" target="_blank">техническую поддержку</a> за справкой.';
    $result.='<p><input type="button" value="Учебник" onclick="javascript:window.open(\'http://' . $host . $path1 . $path2 . $file . '#' . $number . '\')"> <input type="button" value="&laquo; Свернуть справку" onclick="initSlide(0);loadhelp();"></p>
';
} else {
    $uri = $path1 . $path2 . $file;
    $result = send($host, $uri); 
    $start = '<a name=' . $number . '>';
    $startnum = strpos($result, $start) + strlen($start);
    $result = substr($result, $startnum); //Удаление до начальной метки

    if (strpos($result, '<a name=') !== false) {
        $end = '<a name=';
    } else {
        $end = '</body';
    }

    $result = substr($result, 0, strpos($result, $end));
    $result.='<p><input type="button" value="Учебник" onclick="javascript:window.open(\'http://' . $host . $path1 . $path2 . $file . '#' . $number . '\')"> <input type="button" value="&laquo; Свернуть справку" onclick="initSlide(0);loadhelp();"></p>
';
}


// Формируем результат 
$_RESULT = array(
    "text" => $result
);
?>