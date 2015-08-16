<?
require("../connect.php");
@mysql_connect("$host", "$user_db", "$pass_db") or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase") or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

if (empty($_GET['id']))
    $_GET['id'] = $_GET['n'];

$sql = "select * from " . $SysValue['base']['table_name35'] . " where id=" . intval($_GET['id']);
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$id = $row['id'];
$name = $row['name'];
$s_name = str_replace(".", "s.", $name);
$num = $row['num'];

${"s_" . $num . ""} = "selected"; //echo $s_1;
$info = $row['info'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>Изображение: <?= $name ?>
        </title>
        <META http-equiv=Content-Type content="text/html; charset=windows-1251">
        <meta http-equiv="MSThemeCompatible" content="Yes">
        <LINK href="../skins/<?= $_SESSION['theme'] ?>/texts.css" type=text/css rel=stylesheet>
        <SCRIPT language="JavaScript" src="../../lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
        <script language="JavaScript" src="../java/javaMG.js" type="text/javascript"></script>
        <script>

            // Стандартную форму обновляем
            function UpdateMainForma(img, img_s) {
                window.opener.document.getElementById('pic_small_new').value = img_s;
                window.opener.document.getElementById('pic_big_new').value = img;
                self.close();
            }


            // Удаление из галереи
            function DoUpdateNumFotoList(xid, num, info) {
                var uid = window.opener.document.getElementById('productID').value;
                var req = new Subsys_JsHttpRequest_Js();
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        if (req.responseJS) {
                            window.opener.document.getElementById('fotolist').innerHTML = req.responseJS.interfaces;
                        }
                    }
                }
                req.caching = false;
                // Подготваливаем объект.
                req.open('POST', 'action.php?do=num', true);
                req.send({xid: xid, uid: uid, num: num, info: info});
            }

            // Удаление из галереи
            function DoDeleteFotoList(xid, img) {
                var uid = window.opener.document.getElementById('productID').value;
                var req = new Subsys_JsHttpRequest_Js();
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        if (req.responseJS) {
                            window.opener.document.getElementById('fotolist').innerHTML = req.responseJS.interfaces;
                            //self.close(); 
                        }
                    }
                }
                req.caching = false;
                // Подготваливаем объект.
                req.open('POST', 'action.php?do=del', true);
                req.send({xid: xid, uid: uid, img: img});
            }


            function UpdateNum(xid) {
                var nums = document.getElementById('nums').value;
                var info = document.getElementById('info').value;
                DoUpdateNumFotoList(xid, nums, info);
                setTimeout("self.close()", 500);
            }

            function PromptThisDelete(xid, img) {
                if (confirm("Внимание!\nДанная операция может привести к потери позиции.\n Вы Вы действительно хотите выполнить данную команду?")) {
                    DoDeleteFotoList(xid, img);
                    setTimeout("self.close()", 500);
                }
            }


        </script>
    </head>
    <body style="overflow:hidden;margin:0;">
        <?
        echo '
<table width="100%" >
<tr>
	<td valign="top" height="100%" width="70%"><iframe src="' . $name . '" height="400" width="100%" name="frame1" ></iframe></td>
	<td valign="top">
	<div align="center">
	Изображение для навигации:<br>
	<iframe src="' . $s_name . '" height="300" width="100%" name="frame2"></iframe>
	</div>
	<div style="padding:10px">
	Приоритет вывода: 
	<select id="nums" size="1">        
	<option value="0" >0</option>
											<option value="1" ' . $s_1 . '>1</option>
											<option value="2" ' . $s_2 . '>2</option>
											<option value="3" ' . $s_3 . '>3</option>
											<option value="4" ' . $s_4 . '>4</option>
											<option value="5" ' . $s_5 . '>5</option>
											<option value="6" ' . $s_6 . '>6</option>
											<option value="7" ' . $s_7 . '>7</option>
											<option value="8" ' . $s_8 . '>8</option>
											<option value="9" ' . $s_9 . '>9</option>
											<option value="10" ' . $s_10 . '>10</option>
</select>
<br> 
	</div>
	<div>
	Комментарий:<br>
	<textarea style="width: 200px" id="info">' . $info . '</textarea>
	</div>
	</td>
</tr>
</table>
<hr>
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
    <td align="left" style="padding-left: 10px">
	<input type=button class=but value="Основной вид" style="width: 130px"  onClick=" UpdateMainForma(\'' . $name . '\',\'' . $s_name . '\');">
	</td>
	<td align="right" style="padding:10">
<input type=button value="ОК" class=but onClick="UpdateNum(' . $id . ');">
<input type="button" class=but value="Удалить" onClick="PromptThisDelete(' . $id . ',\'' . $name . '\');return false;">
<input type="button" value="Отмена" onClick="return onCancel();" class=but>
	</td>
</tr>
</table>
';
        ?>



