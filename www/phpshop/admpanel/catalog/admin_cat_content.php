<?
require("../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");
include("admin_catalog.php");

$GetSystems=GetSystems();
$option=unserialize($GetSystems['admoption']);

// Подключение языков
$Lang=$option['lang'];
require("../language/".$Lang."/language.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$SysValue['Lang']['System']['charset']?>">
<LINK href="../skins/<?=$_SESSION['theme']?>/texts.css" type=text/css rel=stylesheet>
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript1.2" src="../java/sorttable.js"></script>
<SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
</head>
<body  topmargin="0" rightmargin="3" leftmargin="3">
	<?
if(isset($pid))
	{
	echo CategoryID($_REQUEST['pid']);
	}
elseif(!empty($_REQUEST['words']))
	{
	echo CategorySearch($_REQUEST['words']);
	}
?>
	
</body>
</html>