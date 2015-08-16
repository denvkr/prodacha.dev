<?
/*
+-------------------------------------+
|  PHP SHOP 2.1.4 Enterprise          |
|  Модуль Генерации RSS               |
+-------------------------------------+
*/

// Настройки
function Systems()// вывод настроек
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name3'];
$result=mysql_query($sql) or die("ERROR:".mysql_error()."");
$row = mysql_fetch_array($result);
$name=$row['name'];
$company=$row['company'];
$kurs=$row['kurs'];
foreach($row as $k=>$v)
$array[$k]=$v;
return $array;
}

function output_handler($xml)
{
        header('Accept-Ranges: bytes');
        header('Content-Length: ' . strlen($xml));
        header('Content-type: text/xml; charset=windows-1251');
        return $xml;
}

// Парсируем установочный файл
$SysValue=parse_ini_file("../../phpshop/inc/config.ini",1);
  while(list($section,$array)=each($SysValue))
                while(list($key,$value)=each($array))
$SysValue['other'][chr(73).chr(110).chr(105).ucfirst(strtolower($section)).ucfirst(strtolower($key))]=$value;

// Подключаем базу MySQL
@mysql_connect ($SysValue['connect']['host'], $SysValue['connect']['user_db'],  $SysValue['connect']['pass_db'])or 
@die("".PHPSHOP_error(101,$SysValue['my']['error_tracer'])."");
mysql_select_db($SysValue['connect']['dbase'])or 
@die("".PHPSHOP_error(102,$SysValue['my']['error_tracer'])."");
@mysql_query("SET NAMES 'cp1251'");

$SYSTEM=Systems();

    $sql = "SELECT id, datas, zag, podrob FROM ".$SysValue['base']['table_name8']." ORDER BY id DESC LIMIT 0, 15";

    if (!($result = mysql_query ($sql))) exit;
    ob_start("output_handler");
    echo '<?xml version="1.0" encoding="windows-1251" ?>' . "\n";
    echo '<rss version="2.0">' . "\n";
    echo '        <channel>' . "\n";
    echo '                <title>RSS Новости - '.$SYSTEM['title'].'</title>' . "\n";
    echo '                <description>RSS Новости от '.$SYSTEM['company'].'</description>' . "\n";
    echo '                <link>http://'.$_SERVER['SERVER_NAME'].'/pda/</link>' . "\n";
    echo '                <language>ru</language>' . "\n";
    echo '                <generator>PHPShop</generator>' . "\n";
  while($row = mysql_fetch_array($result)){
    echo '                <item>' . "\n";
    echo '                        <title>' . trim($row['zag']) . '</title>' . "\n";
    echo '                        <link>http://'.$_SERVER['SERVER_NAME'].'/pda/news/ID_'.$row["id"].'.html</link>' . "\n";
    echo '                        <pubDate>' . trim($row['datas']) . '</pubDate>' . "\n";
    echo '                        <description><![CDATA[' . trim($row['podrob']) . ']]></description>' . "\n";
    echo '                        <author>'.$SYSTEM['name'].'</author>' . "\n";
    echo '                </item>' . "\n";
  }
  mysql_free_result($result);
    echo '        </channel>' . "\n";
    echo '</rss>';
?>