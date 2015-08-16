<?
require("../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

// Языки
$GetSystems=GetSystems();
$systems=$GetSystems;
$option=unserialize($GetSystems['admoption']);
$Lang=$option['lang'];
require("../language/".$Lang."/language.php");


//////////////////////// Для больших картинок ////////////////////////
if ($option['watermark_big']['big_type'] == 'text') {
	$big_tipe_checked2 = "checked";
	$big_tipe_checked11 = "none";
	$big_tipe_checked22 = "block";
}
else {
	$big_tipe_checked1 = "checked";
	$big_tipe_checked11 = "block";
	$big_tipe_checked22 = "none";
}
	
if ($option['watermark_big']['big_enabled'] == 1)
	$big_enabled_checked = "checked";
else
	$big_enabled_checked = "";
	
if ($option['watermark_big']['big_copyFlag'] == 1)
	$big_copyFlag_checked = "checked";
else
	$big_copyFlag_checked1 = "checked";
	
if ($option['watermark_big']['big_positionFlag'] > 0){
	$str = "big_positionFlag_checked".$option['watermark_big']['big_positionFlag'];
	$$str = "checked";}
else
	$big_positionFlag_checked1 = "checked";
	
	
	
	$str = "big_text_positionFlag_checked".intval($option['watermark_big']['big_text_positionFlag']);
	$$str = "checked";
	
if ($option['watermark_big']['big_mergeLevel'] == "") $option['watermark_big']['big_mergeLevel'] = 100;

////////////////////////////////////////////////////////////////////////////////
/////////////////////////////// Для маленьких картинок ////////////////////////////////
if ($option['watermark_small']['small_type'] == 'text') {
	$small_tipe_checked2 = "checked";
	$small_tipe_checked11 = "none";
	$small_tipe_checked22 = "block";
}
else {
	$small_tipe_checked1 = "checked";
	$small_tipe_checked11 = "block";
	$small_tipe_checked22 = "none";
}
	
if ($option['watermark_small']['small_enabled'] == 1)
	$small_enabled_checked = "checked";
else
	$small_enabled_checked = "";
	
if ($option['watermark_small']['small_copyFlag'] == 1)
	$small_copyFlag_checked = "checked";
else
	$small_copyFlag_checked1 = "checked";
	
if ($option['watermark_small']['small_positionFlag'] > 0){
	$str = "small_positionFlag_checked".$option['watermark_small']['small_positionFlag'];
	$$str = "checked";}
else
	$small_positionFlag_checked1 = "checked";
	
	
	$str = "small_text_positionFlag_checked".intval($option['watermark_small']['small_text_positionFlag']);
	$$str = "checked";
	
if ($option['watermark_small']['small_mergeLevel'] == "") $option['watermark_small']['small_mergeLevel'] = 100;
///////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////// Для исходных картинок ////////////////////////////////
if ($option['watermark_ishod']['ishod_type'] == 'text') {
	$ishod_tipe_checked2 = "checked";
	$ishod_tipe_checked11 = "none";
	$ishod_tipe_checked22 = "block";
}
else {
	$ishod_tipe_checked1 = "checked";
	$ishod_tipe_checked11 = "block";
	$ishod_tipe_checked22 = "none";
}
	
if ($option['watermark_ishod']['ishod_enabled'] == 1)
	$ishod_enabled_checked = "checked";
else
	$ishod_enabled_checked = "";
	
if ($option['watermark_ishod']['ishod_copyFlag'] == 1)
	$ishod_copyFlag_checked = "checked";
else
	$ishod_copyFlag_checked1 = "checked";
	
if ($option['watermark_ishod']['ishod_positionFlag'] > 0){
	$str = "ishod_positionFlag_checked".$option['watermark_ishod']['ishod_positionFlag'];
	$$str = "checked";}
else
	$ishod_positionFlag_checked1 = "checked";
	
	
	$str = "ishod_text_positionFlag_checked".intval($option['watermark_ishod']['ishod_text_positionFlag']);
	$$str = "checked";
	
if ($option['watermark_ishod']['ishod_mergeLevel'] == "") $option['watermark_ishod']['ishod_mergeLevel'] = 100;
///////////////////////////////////////////////////////////////////////////////////////

$handle=opendir("../watermark/fonts");
while($file=readdir($handle)) {  
	if($file != "." && $file != "..")	{  
		
		if($file == $option['watermark_big']['big_font'])
			$big_font_check = "selected";
		else
			$big_font_check = "";
			
		if($file == $option['watermark_small']['small_font'])
			$small_font_check = "selected";
		else
			$small_font_check = "";
			
		if($file == $option['watermark_ishod']['ishod_font'])
			$ishod_font_check = "selected";
		else
			$ishod_font_check = "";
			
		$big_disp .= "
		<option value=\"$file\" $big_font_check>$file</option>
		"; 
		$small_disp .= "
		<option value=\"$file\" $small_font_check>$file</option>
		"; 
		$ishod_disp .= "
		<option value=\"$file\" $ishod_font_check>$file</option>
		"; 
	};
};
closedir($handle);


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Настройка Watermark</title>
<META http-equiv=Content-Type content="text/html; charset=<?=$SysValue['Lang']['System']['charset']?>">
<meta http-equiv="MSThemeCompatible" content="Yes">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<LINK href="../css/tab.winclassic.css" type=text/css rel=stylesheet>
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" src="../java/tabpane.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_windows.js"></script>
<script type="text/javascript">
DoResize(<? echo $GetSystems['width_icon']?>,500,650);
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

	 
	
echo"
<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"50\" id=\"title\">
<tr bgcolor=\"#ffffff\">
	<td style=\"padding:10\">
	<b><span name=txtLang id=txtLang>Watermark</span></b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Настройки для интернет-магазина</span>.
	</td>
	<td align=\"right\">
	<img src=\"../img/i_display_settings_med[1].gif\" border=\"0\" hspace=\"10\">
	</td>
</tr>
</table>
<form name=product_edit  method=post>
<!-- begin tab pane -->
<div class=\"tab-pane\" id=\"article-tab\" style=\"margin-top:5px;height:480px\">

<script type=\"text/javascript\">
tabPane = new WebFXTabPane( document.getElementById( \"article-tab\" ), true );
</script>



<!-- begin intro page -->
<div class=\"tab-page\" id=\"intro-page\">
<h2 class=\"tab\"><span name=txtLang id=txtLang>Большие изображения</span></h2>

<script type=\"text/javascript\">
tabPane.addTabPage( document.getElementById( \"intro-page\" ) );
</script>

<table width=\"100%\">

<tr>
	<td colspan=3>
	<FIELDSET id=fldLayout>
	<legend>Настройка опций watermark для больших изображений товара</legend>
<div style=\"padding:10\">
	<input type=checkbox name=big_enabled $big_enabled_checked value=1> Включить модуль watermark для больших изображений товара <br>
	<input type=radio name=big_type value=png $big_tipe_checked1 onclick=\"document.getElementById('pngBlock').style.display = 'block';document.getElementById('textBlock').style.display = 'none';\"> Использовать <b>png</b> файл с альфа каналом<br>
		<div id=pngBlock style=\"display:".$big_tipe_checked11.";padding-left:30\">			
			PNG файл: <br>
			<input type=\"text\" name=\"big_png_file_new\" id=\"big_png_file_new\" style=\"width: 300\" value=\"".$option['watermark_big']['big_png_file']."\">
			<BUTTON style=\"width: 3em; height: 2.2em; margin-left:5\"  onclick=\"ReturnPic('big_png_file_new');return false;\"><img src=\"../img/icon-move-banner.gif\"  width=\"16\" height=\"16\" border=\"0\"></BUTTON><br>
			
			<table>
<tr>
	<td>Маcштаб исходного вотермарка: <input type=\"text\" name=\"big_mergeLevel_new\" id=\"big_mergeLevel_new\"  value=\"".$option['watermark_big']['big_mergeLevel']."\" size=4>%</td>
	<td>Альфа канал (от 1 до 100): <input type=text name=big_alpha id=big_alpha  value=\"".intval($option['watermark_big']['big_alpha'])."\" size=4></td>
</tr>
</table>


			<br>
			Геометрия размещения watermark на исходном изображении:<br>
			<input type=radio name=big_copyFlag value=1 $big_copyFlag_checked> размножить watermark по всему изображению<br>
				<div style=\"padding-left:30; padding-top:10px; padding-bottom:10px;\">
					<input type=\"text\" name=\"big_sm_new\" id=\"big_sm_new\" style=\"width: 30\" value=\"".intval($option['watermark_big']['big_sm'])."\">px - смещение чётных строк размножения относительно нечётных влево (в пикселях)
				</div>
			<input type=radio name=big_copyFlag value=0 $big_copyFlag_checked1> не размножать<br>
				<div style=\"padding-left:30; padding-top:0px; padding-bottom:10px;\">
				<table>
<tr>
	<td>1.<input type=radio name=big_positionFlag value=1 $big_positionFlag_checked1> Центр </td>
	<td>4.<input type=radio name=big_positionFlag value=4 $big_positionFlag_checked4> Нижний правый угол</td>
</tr>
<tr>
	<td>2.<input type=radio name=big_positionFlag value=2 $big_positionFlag_checked2> Левый верхний угол</td>
	<td>5.<input type=radio name=big_positionFlag value=5 $big_positionFlag_checked5> Нижний левый угол</td>
</tr>
<tr>
	<td>3.<input type=radio name=big_positionFlag value=3 $big_positionFlag_checked3> Правый верхний угол</td>
	<td></td>
</tr>
</table>
<br>
					
					Смещение watermark по осям X и Y в пикселях для пунктов 2-5, относительно углов (указанных в пунктах):<br>
					X<input type=text name=big_positionX id=big_positionX size=4 value=\"".intval($option['watermark_big']['big_positionX'])."\">px 
					Y<input type=text name=big_positionY id=big_positionY size=4 value=\"".intval($option['watermark_big']['big_positionY'])."\">px
				</div>
		</div>
	<input type=radio name=big_type value=text $big_tipe_checked2 onclick=\"document.getElementById('pngBlock').style.display = 'none';document.getElementById('textBlock').style.display = 'block';\"> Использовать текстовую строку<br>
		<div id=\"textBlock\" style=\"display:".$big_tipe_checked22.";padding-left:30\" >
		Текст строки watermark:<br>
		<input type=text name=big_text_new id=big_text_new size=50 value=\"".$option['watermark_big']['big_text']."\"> <br><br>
		Цвет текста в формате RGB:<br>
		R<input type=text name=big_colorR id=big_colorR  value=\"".intval($option['watermark_big']['big_colorR'])."\" size=7>
		G<input type=text name=big_colorG id=big_colorG  value=\"".intval($option['watermark_big']['big_colorG'])."\" size=7>
		B<input type=text name=big_colorB id=big_colorG  value=\"".intval($option['watermark_big']['big_colorB'])."\" size=7>
		Альфа канал (от 1 до 100): 
		<input type=text name=big_text_alpha id=big_text_alpha  value=\"".intval($option['watermark_big']['big_text_alpha'])."\" size=4><br>
		
		Шрифт: <select id=big_font name=big_font>
		$big_disp
		</select>
		<br><br>
				
		Положение текстового вотермарка на исходном изображении:<br>
			<input type=radio name=big_text_positionFlag value=0   $big_text_positionFlag_checked0> Расположить текстовый вотермарк в диагональ изображения. Угол и размер шрифта расчитать алгоритмически. <br>
		
		<table cellpadding=0 cellspacing=0>
<tr>
	<td>1.<input type=radio name=big_text_positionFlag value=1 $big_text_positionFlag_checked1> Центр</td>
	<td>4.<input type=radio name=big_text_positionFlag value=4 $big_text_positionFlag_checked4> Нижний правый угол</td>
</tr>
<tr>
	<td>2.<input type=radio name=big_text_positionFlag value=2 $big_text_positionFlag_checked2> Левый верхний угол</td>
	<td>5.<input type=radio name=big_text_positionFlag value=5 $big_text_positionFlag_checked5> Нижний левый угол</td>
</tr>
<tr>
	<td>3.<input type=radio name=big_text_positionFlag value=3 $big_text_positionFlag_checked3> Правый верхний угол</td>
	<td></td>
</tr>
</table>

<br>
					Параметры настройки для пунктов 1-5:<br>
					<input type=text name=big_size_new id=big_size_new size=4 value=\"".intval($option['watermark_big']['big_size'])."\"> px - размер шрфта (в пикселях) <br>
					<input type=text name=big_angle_new id=big_angle_new size=4 value=\"".intval($option['watermark_big']['big_angle'])."\"> град. - угол наклона (в градусах) <br><br>
					Смещение watermark по осям X и Y в пикселях для пунктов 2-5, относительно углов (указанных в пунктах):<br>
					X<input type=text name=big_text_positionX id=big_text_positionX size=4 value=\"".intval($option['watermark_big']['big_text_positionX'])."\"> px 
					Y<input type=text name=big_text_positionY id=big_text_positionY size=4 value=\"".intval($option['watermark_big']['big_text_positionY'])."\"> px
					
		
		
		</div>
</div>
</FIELDSET>
	</td>
</tr>

</table>

</div>


<!-- begin intro page -->
<div class=\"tab-page\" id=\"intro-page\" >
<h2 class=\"tab\"><span name=txtLang id=txtLang>Маленькие изображения</span></h2>

<script type=\"text/javascript\">
tabPane.addTabPage( document.getElementById( \"intro-page\" ) );
</script>

<table width=\"100%\">

<tr>
	<td colspan=3>
	<FIELDSET id=fldLayout>
	<legend>Настройка опций watermark для больших изображений товара</legend>
<div style=\"padding:10\">
	<input type=checkbox name=small_enabled $small_enabled_checked value=1> Включить модуль watermark для больших изображений товара <br>
	<input type=radio name=small_type value=png $small_tipe_checked1 onclick=\"document.getElementById('pngBlock1').style.display = 'block';document.getElementById('textBlock1').style.display = 'none';\"> Использовать <b>png</b> файл с альфа каналом<br>
		<div id=pngBlock1 style=\"display:".$small_tipe_checked11.";padding-left:30\">			
			PNG файл: <br>
			<input type=\"text\" name=\"small_png_file_new\" id=\"small_png_file_new\" style=\"width: 300\" value=\"".$option['watermark_small']['small_png_file']."\">
			<BUTTON style=\"width: 3em; height: 2.2em; margin-left:5\"  onclick=\"ReturnPic('small_png_file_new');return false;\"><img src=\"../img/icon-move-banner.gif\"  width=\"16\" height=\"16\" border=\"0\"></BUTTON><br>

<table>
<tr>
	<td>Маcштаб исходного вотермарка: <input type=\"text\" name=\"small_mergeLevel_new\" id=\"small_mergeLevel_new\"  value=\"".$option['watermark_small']['small_mergeLevel']."\" size=4>%</td>
	<td>Альфа канал (от 1 до 100): <input type=text name=small_alpha id=small_alpha  value=\"".intval($option['watermark_small']['small_alpha'])."\" size=4>
	</td>
</tr>
</table>
<br>
			Геометрия размещения watermark на исходном изображении:<br>
			<input type=radio name=small_copyFlag value=1 $small_copyFlag_checked> размножить watermark по всему изображению<br>
				<div style=\"padding-left:30; padding-top:10px; padding-bottom:10px;\">
					<input type=\"text\" name=\"small_sm_new\" id=\"small_sm_new\" style=\"width: 30\" value=\"".intval($option['watermark_small']['small_sm'])."\">px - смещение чётных строк размножения относительно нечётных влево (в пикселях)
				</div>
			<input type=radio name=small_copyFlag value=0 $small_copyFlag_checked1> не размножать<br>
				<div style=\"padding-left:30; padding-top:0px; padding-bottom:10px;\">
				
				<table>
<tr>
	<td>1.<input type=radio name=small_positionFlag value=1 $small_positionFlag_checked1> Центр</td>
	<td>4.<input type=radio name=small_positionFlag value=4 $small_positionFlag_checked4> Нижний правый угол </td>
</tr>
<tr>
	<td>2.<input type=radio name=small_positionFlag value=2 $small_positionFlag_checked2> Левый верхний угол</td>
	<td>5.<input type=radio name=small_positionFlag value=5 $small_positionFlag_checked5> Нижний левый угол</td>
</tr>
<tr>
	<td>3.<input type=radio name=small_positionFlag value=3 $small_positionFlag_checked3> Правый верхний угол</td>
	<td></td>
</tr>
</table>

				
				
					 <br>

					
					Смещение watermark по осям X и Y в пикселях для пунктов 2-5, относительно углов (указанных в пунктах):<br>
					X<input type=text name=small_positionX id=small_positionX  value=\"".intval($option['watermark_small']['small_positionX'])."\" size=4>px 
					Y<input type=text name=small_positionY id=small_positionY  value=\"".intval($option['watermark_small']['small_positionY'])."\" size=4>px
				</div>
		</div>
	<input type=radio name=small_type value=text $small_tipe_checked2 onclick=\"document.getElementById('pngBlock1').style.display = 'none';document.getElementById('textBlock1').style.display = 'block';\"> Использовать текстовую строку<br>
		<div id=\"textBlock1\" style=\"display:".$small_tipe_checked22.";padding-left:30\" >
		Текст строки watermark:<br>
		<input type=text name=small_text_new id=small_text_new size=60 value=\"".$option['watermark_small']['small_text']."\"> <br> <br>
		Цвет текста в формате RGB:<br>
		R<input type=text name=small_colorR id=small_colorR size=7 value=\"".intval($option['watermark_small']['small_colorR'])."\">
		G<input type=text name=small_colorG id=small_colorG size=7 value=\"".intval($option['watermark_small']['small_colorG'])."\">
		B<input type=text name=small_colorB id=small_colorG size=7 value=\"".intval($option['watermark_small']['small_colorB'])."\">
		Альфа канал (от 1 до 100): <input type=text name=small_text_alpha id=small_text_alpha size=4 value=\"".intval($option['watermark_small']['small_text_alpha'])."\"><br>
				
		Шрифт: <select id=small_font name=small_font>
		$small_disp
		</select>
		<br> <br>		

		Положение текстового вотермарка на исходном изображении:<br>
					<input type=radio name=small_text_positionFlag value=0   $small_text_positionFlag_checked0> Расположить текстовый вотермарк в диагональ изображения. Угол и размер шрифта расчитать алгоритмически. <br>
<table cellpadding=0 cellspacing=0>
<tr>
	<td>1.<input type=radio name=small_text_positionFlag value=1 $small_text_positionFlag_checked1> Центр </td>
	<td>4.<input type=radio name=small_text_positionFlag value=4 $small_text_positionFlag_checked4> Нижний правый угол</td>
</tr>
<tr>
	<td>2.<input type=radio name=small_text_positionFlag value=2 $small_text_positionFlag_checked2> Левый верхний угол</td>
	<td>5.<input type=radio name=small_text_positionFlag value=5 $small_text_positionFlag_checked5> Нижний левый угол</td>
</tr>
<tr>
	<td>3.<input type=radio name=small_text_positionFlag value=3 $small_text_positionFlag_checked3> Правый верхний угол</td>
	<td></td>
</tr>
</table>

					 <br>
					Параметры настройки для пунктов 1-5:<br>
					<input type=text name=small_size_new id=small_size_new size=4 value=\"".intval($option['watermark_small']['small_size'])."\"> px - размер шрфта (в пикселях) <br>
					<input type=text name=small_angle_new id=small_angle_new size=4 value=\"".intval($option['watermark_small']['small_angle'])."\"> град. - угол наклона (в градусах) <br><br>
					Смещение watermark по осям X и Y в пикселях для пунктов 2-5, относительно углов (указанных в пунктах):<br>
					X<input type=text name=small_text_positionX id=small_text_positionX size=4 value=\"".intval($option['watermark_small']['small_text_positionX'])."\"> px 
					Y<input type=text name=small_text_positionY id=small_text_positionY size=4 value=\"".intval($option['watermark_small']['small_text_positionY'])."\"> px
					
		
		
		</div>
</div>
</FIELDSET>
	</td>
</tr>

</table>

</div>


<!-- begin intro page -->
<div class=\"tab-page\" id=\"intro-page\">
<h2 class=\"tab\"><span name=txtLang id=txtLang>Исходные изображения</span></h2>

<script type=\"text/javascript\">
tabPane.addTabPage( document.getElementById( \"intro-page\" ) );
</script>

<table width=\"100%\">

<tr>
	<td colspan=3>
	<FIELDSET id=fldLayout>
	<legend>Настройка опций watermark для больших изображений товара</legend>
<div style=\"padding:10\">
	<input type=checkbox name=ishod_enabled $ishod_enabled_checked value=1> Включить модуль watermark для больших изображений товара <br>
	<input type=radio name=ishod_type value=png $ishod_tipe_checked1 onclick=\"document.getElementById('pngBlock2').style.display = 'block';document.getElementById('textBlock2').style.display = 'none';\"> Использовать <b>png</b> файл с альфа каналом<br>
		<div id=pngBlock2 style=\"display:".$ishod_tipe_checked11.";padding-left:30\">			
			PNG файл: <br>
			<input type=\"text\" name=\"ishod_png_file_new\" id=\"ishod_png_file_new\" style=\"width: 300\" value=\"".$option['watermark_ishod']['ishod_png_file']."\">
			<BUTTON style=\"width: 3em; height: 2.2em; margin-left:5\"  onclick=\"ReturnPic('ishod_png_file_new');return false;\"><img src=\"../img/icon-move-banner.gif\"  width=\"16\" height=\"16\" border=\"0\"></BUTTON><br>

<table>
<tr>
	<td>Маcштаб исходного вотермарка: <input type=\"text\" name=\"ishod_mergeLevel_new\" id=\"ishod_mergeLevel_new\"  value=\"".$option['watermark_ishod']['ishod_mergeLevel']."\" size=4>%</td>
	<td>Альфа канал (от 1 до 100): <input type=text name=ishod_alpha id=ishod_alpha size=4  value=\"".intval($option['watermark_ishod']['ishod_alpha'])."\"></td>
</tr>
</table>

<br>
			Геометрия размещения watermark на исходном изображении:<br>
			<input type=radio name=ishod_copyFlag value=1 $ishod_copyFlag_checked> размножить watermark по всему изображению<br>
				<div style=\"padding-left:30; padding-top:10px; padding-bottom:10px;\">
					<input type=\"text\" name=\"ishod_sm_new\" id=\"ishod_sm_new\" style=\"width: 30\" value=\"".intval($option['watermark_ishod']['ishod_sm'])."\">px - смещение чётных строк размножения относительно нечётных влево (в пикселях)
				</div>
			<input type=radio name=ishod_copyFlag value=0 $ishod_copyFlag_checked1> не размножать<br>
				<div style=\"padding-left:30; padding-top:0px; padding-bottom:10px;\">
				
				
				<table >
<tr>
	<td>1.<input type=radio name=ishod_positionFlag value=1 $ishod_positionFlag_checked1> Центр</td>
	<td>4.<input type=radio name=ishod_positionFlag value=4 $ishod_positionFlag_checked4> Нижний правый угол</td>
</tr>
<tr>
	<td>2.<input type=radio name=ishod_positionFlag value=2 $ishod_positionFlag_checked2> Левый верхний угол</td>
	<td>5.<input type=radio name=ishod_positionFlag value=5 $ishod_positionFlag_checked5> Нижний левый угол</td>
</tr>
<tr>
	<td>3.<input type=radio name=ishod_positionFlag value=3 $ishod_positionFlag_checked3> Правый верхний угол</td>
	<td></td>
</tr>
</table>
<br>
					
					Смещение watermark по осям X и Y в пикселях для пунктов 2-5, относительно углов (указанных в пунктах):<br>
					X<input type=text name=ishod_positionX id=ishod_positionX size=4 value=\"".intval($option['watermark_ishod']['ishod_positionX'])."\">px 
					Y<input type=text name=ishod_positionY id=ishod_positionY size=4 value=\"".intval($option['watermark_ishod']['ishod_positionY'])."\">px
				</div>
		</div>
	<input type=radio name=ishod_type value=text $ishod_tipe_checked2 onclick=\"document.getElementById('pngBlock2').style.display = 'none';document.getElementById('textBlock2').style.display = 'block';\"> Использовать текстовую строку<br>
		<div id=\"textBlock2\" style=\"display:".$ishod_tipe_checked22.";padding-left:30\" >
		Текст строки watermark:<br>
		<input type=text name=ishod_text_new id=ishod_text_new size=60 value=\"".$option['watermark_ishod']['ishod_text']."\"> <br><br>
		Цвет текста в формате RGB:<br>
		R<input type=text name=ishod_colorR id=ishod_colorR size=7 value=\"".intval($option['watermark_ishod']['ishod_colorR'])."\">
		G<input type=text name=ishod_colorG id=ishod_colorG size=7 value=\"".intval($option['watermark_ishod']['ishod_colorG'])."\">
		B<input type=text name=ishod_colorB id=ishod_colorG size=7 value=\"".intval($option['watermark_ishod']['ishod_colorB'])."\">
		Альфа канал (от 1 до 100): 
		<input type=text name=ishod_text_alpha id=ishod_text_alpha size=4 value=\"".intval($option['watermark_ishod']['ishod_text_alpha'])."\"><br>
				
		Шрифт: <select id=ishod_font name=ishod_font>
		$ishod_disp
		</select>
		<br>	<br>	

		Положение текстового вотермарка на исходном изображении:<br>
					<input type=radio name=ishod_text_positionFlag value=0   $ishod_text_positionFlag_checked0> Расположить текстовый вотермарк в диагональ изображения. Угол и размер шрифта расчитать алгоритмически. <br>

<table cellpadding=0 cellspacing=0>
<tr>
	<td>1.<input type=radio name=ishod_text_positionFlag value=1 $ishod_text_positionFlag_checked1> Центр</td>
	<td>4.<input type=radio name=ishod_text_positionFlag value=4 $ishod_text_positionFlag_checked4> Нижний правый угол</td>
</tr>
<tr>
	<td>2.<input type=radio name=ishod_text_positionFlag value=2 $ishod_text_positionFlag_checked2> Левый верхний угол</td>
	<td>5.<input type=radio name=ishod_text_positionFlag value=5 $ishod_text_positionFlag_checked5> Нижний левый угол</td>
</tr>
<tr>
	<td>3.<input type=radio name=ishod_text_positionFlag value=3 $ishod_text_positionFlag_checked3> Правый верхний угол</td>
	<td></td>
</tr>
</table>

<br>
					Параметры настройки для пунктов 1-5:<br>
					<input type=text name=ishod_size_new id=ishod_size_new size=4 value=\"".intval($option['watermark_ishod']['ishod_size'])."\"> px - размер шрфта (в пикселях) <br>
					<input type=text name=ishod_angle_new id=ishod_angle_new size=4 value=\"".intval($option['watermark_ishod']['ishod_angle'])."\"> град. - угол наклона (в градусах) <br><br>
					Смещение watermark по осям X и Y в пикселях для пунктов 2-5, относительно углов (указанных в пунктах):<br>
					X<input type=text name=ishod_text_positionX id=ishod_text_positionX size=4 value=\"".intval($option['watermark_ishod']['ishod_text_positionX'])."\"> px 
					Y<input type=text name=ishod_text_positionY id=ishod_text_positionY size=4 value=\"".intval($option['watermark_ishod']['ishod_text_positionY'])."\"> px
					
		
		
		</div>
</div>
</FIELDSET>
	</td>
</tr>

</table>

</div>




</div>


<hr>
<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"50\" >
<tr>
	<td align=\"right\" style=\"padding:10\">
<input type=submit value=ОК class=but name=optionsSAVE>
	<input type=submit name=btnLang value=Отмена class=but onClick=\"return onCancel();\">
	</td>
</tr>
</table>
</form>
";



if(isset($optionsSAVE))
{
if(CheckedRules($UserStatus["option"],1) == 1){
	
	////////////// Для больших картинок /////////////////////////////
	// параметры png
	$option['watermark_big']['big_enabled'] = $big_enabled;
	$option['watermark_big']['big_type'] = $big_type;	
	$option['watermark_big']['big_png_file'] = mysql_real_escape_string($big_png_file_new);
	$option['watermark_big']['big_mergeLevel'] = intval($big_mergeLevel_new);
	$option['watermark_big']['big_copyFlag'] = $big_copyFlag;
	$option['watermark_big']['big_sm'] = intval($big_sm_new);
	$option['watermark_big']['big_positionFlag'] = $big_positionFlag;
	$option['watermark_big']['big_positionX'] = intval($big_positionX);
	$option['watermark_big']['big_positionY'] = intval($big_positionY);
	$option['watermark_big']['big_alpha'] = intval($big_alpha) % 101;
	
	//параметры текстового вотермарка
	$option['watermark_big']['big_text'] = mysql_real_escape_string($big_text_new);
	$option['watermark_big']['big_text_positionFlag'] = $big_text_positionFlag;
	$option['watermark_big']['big_size'] = intval($big_size_new);
	$option['watermark_big']['big_angle'] = intval($big_angle_new);
	$option['watermark_big']['big_text_positionFlag'] = intval($big_text_positionFlag);
	$option['watermark_big']['big_text_positionX'] = intval($big_text_positionX);
	$option['watermark_big']['big_text_positionY'] = intval($big_text_positionY);
	$option['watermark_big']['big_colorR'] = intval($big_colorR) % 256;
	$option['watermark_big']['big_colorG'] = intval($big_colorG) % 256;
	$option['watermark_big']['big_colorB'] = intval($big_colorB) % 256;
	$option['watermark_big']['big_text_alpha'] = intval($big_text_alpha) % 101;
	$option['watermark_big']['big_font'] = $big_font;
	////////////////////////////////////////////////////////////////////////////
	
	////////////////////// Для маленьких картинок //////////////////////////
		// параметры png
	$option['watermark_small']['small_enabled'] = $small_enabled;
	$option['watermark_small']['small_type'] = $small_type;	
	$option['watermark_small']['small_png_file'] = mysql_real_escape_string($small_png_file_new);
	$option['watermark_small']['small_mergeLevel'] = intval($small_mergeLevel_new);
	$option['watermark_small']['small_copyFlag'] = $small_copyFlag;
	$option['watermark_small']['small_sm'] = intval($small_sm_new);
	$option['watermark_small']['small_positionFlag'] = $small_positionFlag;
	$option['watermark_small']['small_positionX'] = intval($small_positionX);
	$option['watermark_small']['small_positionY'] = intval($small_positionY);
	$option['watermark_small']['small_alpha'] = intval($small_alpha) % 101;
	
	//параметры текстового вотермарка
	$option['watermark_small']['small_text'] = mysql_real_escape_string($small_text_new);
	$option['watermark_small']['small_text_positionFlag'] = $small_text_positionFlag;
	$option['watermark_small']['small_size'] = intval($small_size_new);
	$option['watermark_small']['small_angle'] = intval($small_angle_new);
	$option['watermark_small']['small_text_positionFlag'] = intval($small_text_positionFlag);
	$option['watermark_small']['small_text_positionX'] = intval($small_text_positionX);
	$option['watermark_small']['small_text_positionY'] = intval($small_text_positionY);
	$option['watermark_small']['small_colorR'] = intval($small_colorR) % 256;
	$option['watermark_small']['small_colorG'] = intval($small_colorG) % 256;
	$option['watermark_small']['small_colorB'] = intval($small_colorB) % 256;
	$option['watermark_small']['small_text_alpha'] = intval($small_text_alpha) % 101;
	$option['watermark_small']['small_font'] = $small_font;
	////////////////////////////////////////////////////////////////////////
	
	////////////////////// Для исходных картинок //////////////////////////
		// параметры png
	$option['watermark_ishod']['ishod_enabled'] = $ishod_enabled;
	$option['watermark_ishod']['ishod_type'] = $ishod_type;	
	$option['watermark_ishod']['ishod_png_file'] = mysql_real_escape_string($ishod_png_file_new);
	$option['watermark_ishod']['ishod_mergeLevel'] = intval($ishod_mergeLevel_new);
	$option['watermark_ishod']['ishod_copyFlag'] = $ishod_copyFlag;
	$option['watermark_ishod']['ishod_sm'] = intval($ishod_sm_new);
	$option['watermark_ishod']['ishod_positionFlag'] = $ishod_positionFlag;
	$option['watermark_ishod']['ishod_positionX'] = intval($ishod_positionX);
	$option['watermark_ishod']['ishod_positionY'] = intval($ishod_positionY);
	$option['watermark_ishod']['ishod_alpha'] = intval($ishod_alpha) % 101;
	
	//параметры текстового вотермарка
	$option['watermark_ishod']['ishod_text'] = mysql_real_escape_string($ishod_text_new);
	$option['watermark_ishod']['ishod_text_positionFlag'] = $ishod_text_positionFlag;
	$option['watermark_ishod']['ishod_size'] = intval($ishod_size_new);
	$option['watermark_ishod']['ishod_angle'] = intval($ishod_angle_new);
	$option['watermark_ishod']['ishod_text_positionFlag'] = intval($ishod_text_positionFlag);
	$option['watermark_ishod']['ishod_text_positionX'] = intval($ishod_text_positionX);
	$option['watermark_ishod']['ishod_text_positionY'] = intval($ishod_text_positionY);
	$option['watermark_ishod']['ishod_colorR'] = intval($ishod_colorR) % 256;
	$option['watermark_ishod']['ishod_colorG'] = intval($ishod_colorG) % 256;
	$option['watermark_ishod']['ishod_colorB'] = intval($ishod_colorB) % 256;
	$option['watermark_ishod']['ishod_text_alpha'] = intval($ishod_text_alpha) % 101;
	$option['watermark_ishod']['ishod_font'] = $ishod_font;
	////////////////////////////////////////////////////////////////////////

	
	$option_new = serialize($option);

$sql="UPDATE $table_name3
SET
admoption = '$option_new'
";
$result=mysql_query($sql)or @die("Невозможно изменить запись".$sql.mysql_error());
echo"
	 <script>
	 CL();
	 </script>
	   ";
}else $UserChek->BadUserFormaWindow();
  }
   
?>


