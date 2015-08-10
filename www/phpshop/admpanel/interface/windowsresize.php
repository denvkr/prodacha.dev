<?
require("../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db");
mysql_select_db("$dbase");
require("../enter_to_admin.php");

// Load JsHttpRequest backend.
require_once "../../lib/JsHttpRequest/JsHttpRequest.php";
$JsHttpRequest =& new JsHttpRequest("windows-1251");

$w = @$_REQUEST['w'];


$GetSystems=GetSystems();
$option=unserialize($GetSystems['admoption']);
$option['calibrated']=1;
$option_new=serialize($option);

$sql="UPDATE $table_name3 SET admoption='$option_new',width_icon='$w'";
$result=mysql_query($sql);

$_RESULT = array(
  "w"=> $w
  ); 
?>