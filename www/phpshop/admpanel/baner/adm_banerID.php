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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Редактирование Банера</title>
<META http-equiv=Content-Type content="text/html; charset=<?=$SysValue['Lang']['System']['charset']?>">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<?
//Check user's Browser
if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE"))
	echo "<script language=JavaScript src='../editor3/scripts/editor.js'></script>";
else
	echo "<script language=JavaScript src='../editor3/scripts/moz/editor.js'></script>";
?>
<SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_windows.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_interface.js"></script>
<script>
DoResize(<? echo $GetSystems['width_icon']?>,650,600);
</script>
</head>
<body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0" onload="DoCheckLang(location.pathname,<?=$SysValue['lang']['lang_enabled']?>);preloader(0)">
<table id="loader">
<tr>
	<td valign="middle" align="center">
		<div id="loadmes" onclick="preloader(0)">
<table width="100%" height="100%">
<tr>
	<td id="loadimg"></td>
	<td ><b><?=$SysValue['Lang']['System']['loading']?></b><br><?=$SysValue['Lang']['System']['loading2']?></td>
</tr>
</table>
		</div>
</td>
</tr>
</table>

<SCRIPT language=JavaScript type=text/javascript>preloader(1);</SCRIPT>
<?

// Редоктирование записей книги
	  $sql="select * from $table_name15 where id=$id";
      $result=mysql_query($sql);
	  @$row = mysql_fetch_array(@$result);
	  $id=$row['id'];
	  $name=$row['name'];
	  $content=stripslashes($row['content']);
	  $dir=$row['dir'];
	  $count_all=$row['count_all'];
	  $count_today=$row['count_today'];
	  $limit_all=$row['limit_all'];
	  if($row['flag']==1){
	  $fl="checked";
	  }else{
	  $fl2="checked";}
	  ?>
<form name="product_edit"  method=post onsubmit="Save()">
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
	<td style="padding:10">
	<b><span name=txtLang id=txtLang>Редактирование Банера</span> "<?=$name?>"</b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
	</td>
	<td align="right">
	<img src="../img/i_select_another_account_med[1].gif" border="0" hspace="10">
	</td>
</tr>
</table>
<br>
<table cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
<tr>
  <td>
  <FIELDSET>
<LEGEND><span name=txtLang id=txtLang>Банер</span></LEGEND>
<div style="padding:10">
<input type="text" name="name_new" value="<?=$name?>" style="width: 100%; "><br><br>
<input type="radio" name="flag_new" value="1" <?=@$fl?>><span name=txtLang id=txtLang>Показывать банер</span>&nbsp;&nbsp;&nbsp;
<input type="radio" name="flag_new" value="0" <?=@$fl2?>><span name=txtLang id=txtLang>Скрыть банер</span>
</div>
</FIELDSET>
  </td>
  <td>
  <FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang>Лимит</span></LEGEND>
<div style="padding:10">
<input type="text" name="limit_all_new" value="<?=$limit_all?>" style="width: 100%; "><br><br>
<input type="checkbox" name="clean_st" value="1"><span name=txtLang id=txtLang>Обнулить счетчики</span> [<?=@$count_today." / ".$count_all?>]
</div>
</FIELDSET>
  </td>
</tr>
<tr>
  <td colspan="2">
  <FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>П</u>ривязка к странице</span></LEGEND>
<div style="padding:10">
<input type="text" name="dir_new" value="<?=$dir?>" style="width:100%"><br>
<span name=txtLang id=txtLang>* Пример: page/,news/. Можно указать несколько адресов через запятую без пробелов.</span>
</FIELDSET>
  </td>
</tr>
<tr>
	<td colspan="2">
	<FIELDSET>
<LEGEND><span name=txtLang id=txtLang>Контент</span></LEGEND>
<div style="padding:10">
<?
$systems=GetSystems();
$option=unserialize($systems['admoption']);
if($option['editor_enabled']  == 1){
$MyStyle=$SysValue['dir']['dir'].chr(47)."phpshop".chr(47)."templates".chr(47).$systems['skin'].chr(47).$SysValue['css']['default'];
echo'
<pre id="idTemporary" name="idTemporary" style="display:none">
'.$content.'
</pre>
	<script>
		var oEdit1 = new InnovaEditor("oEdit1");
	oEdit1.cmdAssetManager="modalDialogShow(\''.$SysValue['dir']['dir'].'/phpshop/admpanel/editor3/assetmanager/assetmanager.php\',640,500)";
		oEdit1.width=600;
		oEdit1.height=200;
		oEdit1.btnStyles=true;
	    oEdit1.css="'.$MyStyle.'";
		oEdit1.RENDER(document.getElementById("idTemporary").innerHTML);
	</script>
	<input type="hidden" name="EditorContent" id="EditorContent">
</div>
	';
	}
else{
echo '
<textarea name="EditorContent" id="EditorContent" style="width:100%;height:200px">'.$content.'</textarea>
';
}
	?>
</div>
</FIELDSET>
	</td>
</tr>
</table>
<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="50" >
<tr>
    <td align="left" style="padding:10">
    <BUTTON class="help" onclick="helpWinParent('baner')">Справка</BUTTON></BUTTON>
	</td>
	<td align="right" style="padding:10">
	<input type="hidden" name="id" value="<?=$id?>" >
	<input type="submit" name="editID" value="OK" class=but>
	<input type="button" name="btnLang" class=but value="Удалить" onClick="PromptThis();">
    <input type="hidden" class=but  name="productDELETE" id="productDELETE">
	<input type="button" name="btnLang" value="Отмена" onClick="return onCancel();" class=but>
	</td>
</tr>
</table>
</form>
	  <?
if(isset($editID) and @$name_new!="")// Запись редактирования
{
if(CheckedRules($UserStatus["baner"],1) == 1){
if(@$clean_st==1)
  {
  $sql="UPDATE $table_name15
SET
count_all='0',
count_today='0'
where id='$id'";
  $result=mysql_query($sql)or @die("Невозможно изменить запись");
  }
$sql="UPDATE $table_name15
SET
name='$name_new',
content='".addslashes($EditorContent)."',
flag='$flag_new',
limit_all='$limit_all_new',
dir='$dir_new' 
where id='$id'";
$result=mysql_query($sql)or @die("Невозможно изменить запись");
echo"
	  <script>
DoReloadMainWindow('baner');
</script>
	   ";
}else $UserChek->BadUserFormaWindow();
}
if(@$productDELETE=="doIT")// Удаление записи
{
if(CheckedRules($UserStatus["baner"],1) == 1){
$sql="delete from $table_name15
where id='$id'";
$result=mysql_query($sql)or @die("Невозможно изменить запись");
echo"
	  <script>
DoReloadMainWindow('baner');
</script>
	   ";
}else $UserChek->BadUserFormaWindow();
}
?>



