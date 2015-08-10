<?
require("../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

$sql="select id from $table_name1 where statusi=0";
$result=mysql_query($sql);
$num=mysql_numrows($result);

$GetSystems=GetSystems();
$option=unserialize($GetSystems['admoption']);

// Load JsHttpRequest backend.
require_once "../../lib/JsHttpRequest/JsHttpRequest.php";
$JsHttpRequest = new JsHttpRequest("windows-1251");

if($option['message_enabled']==1 and $num>0) $order=1;
 else  $order=0;

$_RESULT = array(
  "order"=> $order
  ); 
?>