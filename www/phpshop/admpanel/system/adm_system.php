<?
$_classPath = "../../";

include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();

PHPShopObj::loadClass("system");
$PHPShopSystem = new PHPShopSystem();

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// Временная поддержка API 2.X
extract($_POST);
extract($_GET);
$SysValue = $PHPShopBase->getSysValue();
$GetSystems = $PHPShopSystem->getArray();


$option = unserialize($GetSystems['admoption']);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>Системные Настройки</title>
        <META http-equiv=Content-Type content="text/html; charset=<?= $SysValue['Lang']['System']['charset'] ?>">
        <LINK href="../skins/<?= $_SESSION['theme'] ?>/texts.css" type=text/css rel=stylesheet>
        <LINK href="../skins/<?= $_SESSION['theme'] ?>/tab.css" type=text/css rel=stylesheet>
        <script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
        <script type="text/javascript" src="../java/tabpane.js"></script>

    </head>
    <body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0">

        <table id="loader">
            <tr>
                <td valign="middle" align="center">
                    <div id="loadmes" onclick="preloader(0)">
                        <table width="100%" height="100%">
                            <tr>
                                <td id="loadimg"></td>
                                <td ><b><?= $SysValue['Lang']['System']['loading'] ?></b><br><?= $SysValue['Lang']['System']['loading2'] ?></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        <?

        function Editors($editor) {
            global $SysValue, $PHPShopGUI;
            $dir = "../editors/";
            if (is_dir($dir)) {
                if (@$dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {

                        if ($editor == $file)
                            $sel = "selected";
                        else
                            $sel = "";

                        if ($file != "." and $file != ".." and $file != "index.html")
                            $value[] = array($file, $file, $sel);
                    }
                    closedir($dh);
                }
            }

            return $PHPShopGUI->setSelect('editor_new', $value, 100);
        }

// Выбор языка
        function GetLang($skin) {
            global $SysValue;
            $dir = "../language";
            if (is_dir($dir)) {
                if ($dh = @opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {

                        if ($skin == $file)
                            $sel = "selected";
                        else
                            $sel = "";

                        if ($file != "." and $file != ".." and $file != "index.html")
                            @$name.= "<option value=\"$file\" $sel>$file</option>";
                    }
                    closedir($dh);
                }
            }
            $disp = "
<select name=\"lang_new\">
" . @$name . "
</select>
";
            return @$disp;
        }

// Выбор дизайна административной части
        function GetTheme($skin) {

            if (empty($skin))
                $skin = 'default';

            $dir = "../skins";
            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {

                        if ($skin == $file)
                            $sel = "selected";
                        else
                            $sel = "";

                        if ($file != "." and $file != ".." and $file != "index.html")
                            @$name.= "<option value=\"$file\" $sel>$file</option>";
                    }
                    closedir($dh);
                }
            }
            $disp = "
<select name=\"theme_new\">
" . @$name . "
</select>
";
            return @$disp;
        }

// Выбор дизайна внешней части
        function GetSkins($skin) {
            $dir = "../../templates";
            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {

                        if ($skin == $file)
                            $sel = "selected";
                        else
                            $sel = "";

                        if ($file != "." and $file != ".." and $file != "index.html")
                            @$name.= "<option value=\"$file\" $sel>$file</option>";
                    }
                    closedir($dh);
                }
            }
            $disp = "
<select name=\"skin_new\" style=\"height:200px;width:280px\" size=5 onchange=\"GetSkinIcon(this.value)\">
" . @$name . "
</select>
";
            return @$disp;
        }

// Выбор иконки шкуры
        function GetSkinsIcon($skin) {
            global $SysValue;
            $dir = "../../templates";
            $filename = $dir . '/' . $skin . '/icon/icon.gif';
            if (file_exists($filename))
                $disp = '<img src="' . $filename . '" alt="' . $skin . '" width="150" height="120" border="1" id="icon">';
            else
                $disp = '<img src="../img/icon_non.gif" alt="Изображение не доступно" width="150" height="120" border="1" id="icon">';
            return @$disp;
        }

        /**
         * Кодировка XML
         */
        function getXmlCode($code) {
            $array = array(
                'default' => '',
                'ISO-8859-1' => 'ISO-8859-1',
                'UTF-8' => 'UTF-8'
            );
            $dis = null;

            foreach ($array as $key => $val) {
                if ($val == $code)
                    $sel = 'selected';
                else
                    $sel = null;
                $dis.='<option value="' . $val . '" ' . $sel . '>' . $key . '</option>';
            }

            return '<select name="xmlencode_new" id="xmlencode_new" size="1">' . $dis . '</select>';
        }

        function GetValuta($n, $tip) { // вывод валюты
            global $SysValue;
            $sql = "select * from " . $SysValue['base']['table_name24'] . " where enabled='1' order by num";
            $result = mysql_query($sql);
            while ($row = mysql_fetch_array($result)) {
                $id = $row['id'];
                $name = $row['name'];
                $sel = "";
                if ($id == $n)
                    $sel = "selected";
                @$dis.="<option value=" . $id . " " . $sel . " >" . $name . "</option>\n";
            }
            @$disp = "
<select name='$tip' size=1>
                    $dis
</select>
                    ";
            return @$disp;
        }

        function GetUsersStatus($n) {
            global $SysValue;
            $sql = "select * from " . $SysValue['base']['table_name28'] . " order by discount";
            $result = mysql_query($sql);
            while ($row = mysql_fetch_array($result)) {
                $id = $row['id'];
                $name = $row['name'];
                $discount = $row['discount'];
                $sel = "";
                if ($n == $id)
                    $sel = "selected";
                @$dis.="<option value=" . $id . " " . $sel . " >" . $name . " - " . $discount . "%</option>\n";
            }
            @$disp = "
<select name=user_status_new size=1>
<option value=0 id=txtLang>Авторизованный пользователь</option>
                    $dis
</select>
                    ";
            return @$disp;
        }

        $sql = "select * from " . $PHPShopBase->getParam('base.system');
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result);
        $num_row = $row['num_row'];
        $dengi = $row['dengi'];
        $percent = $row['percent'];
        $title = $row['title'];
        $keywords = $row['keywords'];
        $skin = $row['skin'];
        $kurs = $row['kurs'];
        $kurs_beznal = $row['kurs_beznal'];
        $spec_num = $row['spec_num'];
        $new_num = $row['new_num'];
        $dostavka = $row['dostavka'];
        $nds = $row['nds'];
        $logo = $row['logo'];
        if ($row['nds_enabled'] == 1)
            $nds_enabled = "checked";
        else
            $nds_enabled = "";

        switch ($row['num_vitrina']) {
            case(1): $rowl = "selected";
                break;
            case(2): $row2 = "selected";
                break;
            case(3): $row3 = "selected";
                break;
        }

        switch ($row['num_row_adm']) {
            case(1): $cowl = "selected";
                break;
            case(2): $cow2 = "selected";
                break;
            case(3): $cow3 = "selected";
                break;
            case(4): $cow4 = "selected";
                break;
        }



        $width_icon = $row['width_icon'];
        $option = unserialize($row['admoption']);

        switch ($option['sklad_status']) {
            case(1): $sklad_statusl = "selected";
                break;
            case(2): $sklad_status2 = "selected";
                break;
            case(3): $sklad_status3 = "selected";
                break;
        }

        switch ($option['nowbuy_enabled']) {
            case(0): $nowbuy_enabled0 = "selected";
                break;
            case(1): $nowbuy_enabled1 = "selected";
                break;
            case(2): $nowbuy_enabled2 = "selected";
                break;
            default: $nowbuy_enabled0 = "selected";
        }

        if ($option['message_enabled'] == 1)
            $message_enabled = "checked";
        else
            $message_enabled = "";

        if ($option['desktop_enabled'] == 1)
            $desktop_enabled = "checked";
        else
            $desktop_enabled = "";

        if ($option['base_enabled'] == 1)
            $base_enabled = "checked";
        else
            $base_enabled = "";

        if ($option['sklad_enabled'] == 1)
            $sklad_enabled = "checked";
        else
            $sklad_enabled = "";

        if ($option['sms_enabled'] == 1)
            $sms_enabled = "checked";
        else
            $sms_enabled = "";
        
        if ($option['sms_status_order_enabled'] == 1)
            $sms_status_order_enabled = "checked";
        else
            $sms_status_order_enabled = "";

        if ($option['notice_enabled'] == 1)
            $notice_enabled = "checked";
        else
            $notice_enabled = "";

        if ($option['update_enabled'] == 1)
            $update_enabled = "checked";
        else
            $update_enabled = "";


        if ($option['seller_enabled'] == 1)
            $seller_enabled = "checked";
        if ($option['user_mail_activate'] == 1)
            $user_mail_activate = "checked";
        if ($option['user_skin'] == 1)
            $user_skin = "checked";
        if ($option['rss_graber_enabled'] == 1)
            $rss_graber_enabled = "checked";
        if ($option['user_mail_activate_pre'] == 1)
            $user_mail_activate_pre = "checked";
        if ($option['user_price_activate'] == 1)
            $user_price_activate = "checked";
        if ($option['user_calendar'] == 1)
            $user_calendar = "checked";
        if ($option['digital_product_enabled'] == 1)
            $digital_product_enabled = "checked";
        if ($option['helper_enabled'] == 1)
            $helper_enabled = "checked";
        if ($option['cloud_enabled'] == 1)
            $cloud_enabled = "checked";
        if ($option['image_save_source'] == 1)
            $image_save_source = "checked";
        if ($option['prevpanel_enabled'] == 1)
            $prevpanel_enabled = "checked";
        if ($option['calibrated'] == 1)
            $calibrated = "checked";

        echo"
<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"50\" id=\"title\">
<tr bgcolor=\"#ffffff\">
	<td style=\"padding:10\">
	<b><span name=txtLang id=txtLang>Системные Настройки</span></b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Настройки для интернет-магазина</span>.
	</td>
	<td align=\"right\">
	<img src=\"../img/i_display_settings_med[1].gif\" border=\"0\" hspace=\"10\">
	</td>
</tr>
</table>

<!-- begin tab pane -->
<div class=\"tab-pane\" id=\"article-tab\" style=\"margin-top:5px;height:250px\">

<script type=\"text/javascript\">
tabPane = new WebFXTabPane( document.getElementById( \"article-tab\" ), true );
</script>



<!-- begin intro page -->
<div class=\"tab-page\" id=\"intro-page\" style=\"height:250px\">
<h2 class=\"tab\"><span name=txtLang id=txtLang>Внешний вид</span></h2>

<script type=\"text/javascript\">
tabPane.addTabPage( document.getElementById( \"intro-page\" ) );
</script>

	<table >
	<tr class=adm2>
<form  method=\"post\" name=\"system_forma\">
	  <td align=left>
                " . GetSkins($skin) . "
	  </td>
	  <td style=\"padding-left:5px\" valign=top>
	  <FIELDSET >
	  <LEGEND ><span name=txtLang id=txtLang><u>С</u>криншот</span></LEGEND>
	  <div align=\"center\" style=\"padding:10px\">" . GetSkinsIcon($skin) . "</div>
        
	  </FIELDSET>
        <p><img src=\"../img/icon-filetype-jpg.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\" >
        <a href=\"http://wiki.phpshop.ru/index.php/Template_Edit_Manual\" target=\"_blank\" title=\"База  шаблонов\">База дополнительных шаблонов</a>
</p>
	  </td>
	</tr>

</table>

</div>
<div class=\"tab-page\" id=\"vetrina\" style=\"height:250px\">
<h2 class=\"tab\"><span name=txtLang id=txtLang>Витрина</span></h2>

<script type=\"text/javascript\">
tabPane.addTabPage( document.getElementById( \"vetrina\" ) );
</script>
<table>


	<tr>
	  <td align=right>
	   <span name=txtLang id=txtLang>Количество позиций на<br>
	  странице в магазине</span>:
	  </td>
	   <td align=left >
	  <input type=text name=num_row_new size=9 value=\"$num_row\" >
	  </td>
	</tr>
	<tr class=adm2>
	  <td align=right>
	  <span name=txtLang id=txtLang>Количество позиций<br>
	  в спецпредложении</span>:
	  </td>
	  <td align=left>
	  <input type=text name=spec_num_new size=9 value=\"$spec_num\" maxlength=9>
	  </td>
	</tr>
	<tr class=adm2>
	  <td align=right>
	 <span name=txtLang id=txtLang> Количество позиций <br>
	  в новинках</span>:
	  </td>
	  <td align=left>
	  <input type=text name=new_num_new size=9 value=\"$new_num\" maxlength=9>
	  </td>
	</tr>
	<tr>
	  <td align=right>
	  <span name=txtLang id=txtLang>Товаров в длину<br>
	  для витрины главной страницы</span>:
	  </td>
	  <td align=left>
	  <select name=num_vitrina_new>
			<option value=1 $rowl>1</option>
			<option value=2 $row2>2</option>
			<option value=3 $row3>3</option>
</select> шт.
	  </td>
	</tr>
	<tr>
	  <td align=right>
	  <span name=txtLang id=txtLang>Каталогов в длину<br>
	  для витрины главной страницы</span>:
	  </td>
	  <td align=left>
	  <select name=num_cow_new>
			<option value=1 $cowl>1</option>
			<option value=2 $cow2>2</option>
			<option value=3 $cow3>3</option>
			<option value=4 $cow4>4</option>
</select> шт.
	  </td>
	</tr>
        <tr>
	<td align=right>
	Логотип:
        </td>
        <td align=left>
	<input type=\"text\" name=\"logo_new\" id=\"logo\" style=\"width: 350\" value=\"$logo\">
	<BUTTON style=\"width: 3em; height: 2.2em; margin-left:5\"  onclick=\"ReturnPic('logo');return false;\"><img src=\"../img/icon-move-banner.gif\"  width=\"16\" height=\"16\" border=\"0\"></BUTTON>
	</td>
</tr>
	        <tr>
	<td align=right>Сейчас покупают:</td>
	<td>
        <select name=nowbuy_enabled_new>
	        <option value=0 $nowbuy_enabled0>не показывать</option>
			<option value=1 $nowbuy_enabled1>компактный вид</option>
			<option value=2 $nowbuy_enabled2>расширенный вид</option>

        </select> * Влияет на время генерации первой страницы
        </td>
</tr>	
</table>
</div>
<!-- begin usage page -->
<div class=\"tab-page\" id=\"usage-page\" style=\"height:250px\">
<h2 class=\"tab\"><span name=txtLang id=txtLang>Цены</span></h2>

<script type=\"text/javascript\">
tabPane.addTabPage( document.getElementById( \"usage-page\" ) );
</script>

<table>
<tr>
	<td>


<table>
<tr class=adm2>
	  <td align=right>
	  <span name=txtLang id=txtLang>Валюта по умолчанию</span>:
	  </td>
	  <td align=left>
                " . GetValuta($dengi, "dengi_new") . "
	  </td>
	</tr>
	<tr class=adm2>
	  <td align=right>
	  <span name=txtLang id=txtLang>Валюта в счете</span>:
	  </td>
	  <td align=left>
	  " . GetValuta($kurs, "kurs_new") . "
	  </td>
	</tr> 
	<tr class=adm2>
	  <td align=right>
	  <span name=txtLang id=txtLang>Валюта для безналичного<br>
	   расчета</span>:
	  </td>
	  <td align=left>
	  " . GetValuta($kurs_beznal, "kurs_beznal_new") . "
	  </td>
	</tr> 
	<tr class=adm2>
	  <td align=right>
	 <span name=txtLang id=txtLang> Накрутка цены</span> %:
	  </td>
	  <td align=left>
	  <input type=text name=percent_new size=9 value=\"$percent\" maxlength=9>
	  </td>
	</tr>
   <tr class=adm2>
	  <td align=right>
	<span name=txtLang id=txtLang>Учитывать НДС</span>
	  </td>
	  <td align=left>
	  <input type=\"checkbox\" value=\"1\" name=\"nds_enabled_new\" $nds_enabled>
	  </td>
	</tr>
    <tr>
	  <td align=right>
	<span name=txtLang id=txtLang>НДС</span>
	  </td>
	  <td align=left>
	  <input type=text name=nds_new size=3 value=\"$nds\"> %
	  </td>
	</tr>
		<tr class=adm2>
	  <td align=right>
	<span name=txtLang id=txtLang>Показывать состояние<br>
	склада</span>:
	  </td>
	  <td align=left>
	  <input type=\"checkbox\" value=\"1\" name=\"sklad_enabled_new\" $sklad_enabled>
	  </td>
	</tr> 
	<tr class=adm2>
	  <td align=right>
	<span name=txtLang id=txtLang>
	Кол-во знаков после<br> запятой
	 в цене
	</span>:
	  </td>
	  <td align=left>
	  <input type=text name=price_znak_new size=3 value=\"" . $option['price_znak'] . "\">
	  </td>
	</tr> 
</table>

</td>
    <td width=30></td>
	<td valign=top>
	<table>
	  <tr>
	  <td align=right>
	<span name=txtLang id=txtLang>
	Минимальная сумма заказа
	</span>:
	  </td>
	  <td align=left>
	  <input type=text name=cart_minimum_new size=10 value=\"" . $option['cart_minimum'] . "\">
	  </td>
	</tr> 
	 <tr>
	  <td align=right>
	<span name=txtLang id=txtLang>
	Статус товара при нулевом<br>
	 кол-ве на складе
	</span>:
	  </td>
	  <td align=left>
	  <select name=sklad_status_new>
	        <option value=1 $sklad_status1>игнорировать</option>
			<option value=2 $sklad_status2>убирается с продаж</option>
			<option value=3 $sklad_status3>нет в наличии</option>
			
</select>
	  </td>
	</tr> 
	</table>
	</td>
</tr>
</table>
</div>
<div class=\"tab-page\" id=\"message\" style=\"height:250px\">
<h2 class=\"tab\"><span name=txtLang id=txtLang>Сообщения</span></h2>

<script type=\"text/javascript\">
tabPane.addTabPage( document.getElementById( \"message\" ) );
</script>
<table>
<tr class=adm2>
	  <td align=right>
	<span name=txtLang id=txtLang>Всплывающие уведомления</span>
	  </td>
	  <td align=left>
	  <input type=\"checkbox\" value=\"1\" name=\"message_enabled_new\" $message_enabled>
	  <span name=txtLang id=txtLang style=\"border: 1px;border-style: inset; padding: 3px\">Может приводить к замедлению работы администрирования</span>
	  </td>
	</tr>
	<tr class=adm2>
	  <td align=right>
	<span name=txtLang id=txtLang>Время обновления<br>
	уведомления</span>
	  </td>
	  <td align=left>
	  <input type=text name=message_time_new size=3 value=\"" . $option['message_time'] . "\"> сек.
	  </td>
	</tr>
	<!-- <tr class=adm2>
	  <td align=right>
<span name=txtLang id=txtLang>Контроль заказов на<br>
	рабочем столе</span>
	  </td>
	  <td align=left>
	  <input type=\"checkbox\" value=\"1\" name=\"desktop_enabled_new\" $desktop_enabled>
	  </td>
	</tr>
	<tr class=adm2>
	  <td align=right>
	<span name=txtLang id=txtLang>Время обновления<br>
	контроля заказов</span>
	  </td>
	  <td align=left>
	  <input type=text name=desktop_time_new size=3 value=\"" . $option['desktop_time'] . "\"> сек.
	  </td>
	</tr> -->
	<tr class=adm2>
	  <td align=right>
	<span name=txtLang id=txtLang>SMS уведомление<br> о заказе администратору</span>
	  </td>
	  <td align=left>
	  <input type=\"checkbox\" value=\"1\" name=\"sms_enabled_new\" $sms_enabled>
	  </td>
	</tr>
        <tr class=adm2>
	  <td align=right>
	<span name=txtLang id=txtLang>SMS уведомление<br> о статусе заказа пользователю</span>
	  </td>
	  <td align=left>
	  <input type=\"checkbox\" value=\"1\" name=\"sms_status_order_enabled_new\" $sms_status_order_enabled>
	  </td>
	</tr>
	<tr class=adm2>
	  <td align=right>
	<span name=txtLang id=txtLang>SMS уведомление<br>
	 о наличии товара пользователям</span>
	  </td>
	  <td align=left>
	  <input type=\"checkbox\" value=\"1\" name=\"notice_enabled_new\" $notice_enabled>
	   <span name=txtLang id=txtLang style=\"border: 1px;border-style: inset; padding: 3px\">Только для авторизованных пользователей</span>
	  </td>
	</tr> 
	<tr class=adm2>
	  <td align=right>
	<span name=txtLang id=txtLang>Автоматическая проверка<br>
	обновлений</span>
	  </td>
	  <td align=left>
	  <input type=\"checkbox\" value=\"1\" name=\"update_enabled_new\" $update_enabled>

	  </td>
	</tr> 
	<tr class=adm2>
	  <td align=right>
	<span name=txtLang id=txtLang>Иконка товара
	 в навигации</span>
	  </td>
	  <td align=left>
	  <input type=\"checkbox\" value=\"1\" name=\"prevpanel_enabled_new\" $prevpanel_enabled>
Отображать иконку и описание в списке товаров
	  </td>
	</tr> 
</table>
</div>

<div class=\"tab-page\" id=\"regim\" style=\"height:250px\">
<h2 class=\"tab\"><span name=txtLang id=txtLang>Режимы</span></h2>

<script type=\"text/javascript\">
tabPane.addTabPage( document.getElementById( \"regim\" ) );
</script>
<table>
    <tr>
	  <td align=right>
	<span name=txtLang id=txtLang>Цветовая тема</span>:
	  </td>
	  <td align=left>" . GetTheme($option['theme']) . "
 <span name=txtLang id=txtLang>* Внешний вид панели управления</span>
	  </td>
	</tr>
    <tr>
	  <td align=right>
	<span name=txtLang id=txtLang>Визуальный редактор</span>:
	  </td>
	  <td align=left>" . Editors($option['editor']) . "
              	<BUTTON style=\"width: 170\" onclick=\"window.open('http://wiki.phpshop.ru/index.php/Modules#WISWIG_Load');return false;\">
	<img src=\"../icon/page_code.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\"> Установить редакторы</BUTTON>
 </td>
	</tr>
	<tr>
	  <td align=right>
	<span name=txtLang id=txtLang>Режим Multibase</span>:
	  </td>
	  <td align=left>
	  <input type=\"checkbox\" value=\"1\" name=\"base_enabled_new\" $base_enabled> 
	  </td>
	</tr>
	<tr>
	  <td align=right>
	Multibase ID:
	  </td>
	  <td align=left>
	  <input type=text name=base_id_new size=5 value=\"" . $option['base_id'] . "\">
	  * <span name=txtLang id=txtLang>Идентификатор в системе Multibase магазина донора</span>.
	  </td>
	</tr>
	<tr>
	  <td align=right>
	Multibase Host:
	  </td>
	  <td align=left>
	  <input type=text value=\"http://\" readonly size=3 disabled>
	  <input type=text name=\"base_host_new\" size=30 value=\"" . $option['base_host'] . "\">
	  </td>
	</tr>
	<tr>
	  <td align=right>
	RSS Graber:
	  </td>
	  <td align=left>
	 <input type=\"checkbox\" value=\"1\" name=\"rss_graber_enabled_new\" $rss_graber_enabled>
	 * Копирование RSS каналов в новости
	  </td>
	</tr>
		

		<tr>
	  <td align=right>
	Интерактивная справка:
	  </td>
	  <td align=left>
	 <input type=\"checkbox\" value=\"1\" name=\"helper_enabled_new\" $helper_enabled>
	 * Интерактивная справка в панели админстратора
	  </td>
	</tr>


	<tr>
	<td align=right>Календарь новостей:</td>
	<td><input type=\"checkbox\" value=\"1\" name=\"user_calendar_new\" $user_calendar> * Опция сортировки новостей по датам</td>
</tr>
<tr>
	<td align=right>Облако тегов:</td>
	<td><input type=\"checkbox\" value=\"1\" name=\"cloud_enabled_new\" $cloud_enabled> * Опция сортировки товаров по ключевым тегам</td>
</tr>
         <tr>
	<td align=right>
	XML кодировка:
        </td>
        <td align=left>
        " . getXmlCode($option['xmlencode']) . " <span name=txtLang id=txtLang>* Настройка отображения модулей</span>
	</td>
</tr>
</table>
</div>
<div class=\"tab-page\" id=\"user\" style=\"height:250px\">
<h2 class=\"tab\"><span name=txtLang id=txtLang>Пользователи</span></h2>

<script type=\"text/javascript\">
tabPane.addTabPage( document.getElementById( \"user\" ) );
</script>
<table>
<tr>
	<td><span name=txtLang id=txtLang>Активация через e-mail</span>:</td>
	<td><input type=\"checkbox\" value=\"1\" name=\"user_mail_activate_new\" $user_mail_activate> * Требуется предварительное подтверждение через e-mail</td>
</tr>
<tr>
	<td>Ручная активация:</td>
	<td><input type=\"checkbox\" value=\"1\" name=\"user_mail_activate_pre_new\" $user_mail_activate_pre> * Активация администратором в ручном режиме</td>
</tr>
<tr>
	<td>Регистрация для просмора цен:</td>
	<td><input type=\"checkbox\" value=\"1\" name=\"user_price_activate_new\" $user_price_activate> * Незарегистрированные пользователи не смогут видеть цен</td>
</tr>
<tr>
	<td><span name=txtLang id=txtLang>Статус пользователя <br>после активации</span>:</td>
	<td>" . GetUsersStatus($option['user_status']) . " </td>
</tr>
<tr>
	<td><span name=txtLang id=txtLang>Смена дизайна</span>:</td>
	<td><input type=\"checkbox\" value=\"1\" name=\"user_skin_new\" $user_skin> * Опция смены дизайна на сайте</td>
</tr>
<tr>
	  <td >
	Цифровые товары:
	  </td>
	  <td align=left>
	 <input type=\"checkbox\" value=\"1\" name=\"digital_product_enabled_new\" $digital_product_enabled>
	 * Поддержка продажи цифровых товаров (файлов)
	  </td>
	</tr>
</table>

</div>
<div class=\"tab-page\" id=\"img\" style=\"height:250px\">
<h2 class=\"tab\"><span name=txtLang id=txtLang>Изображения</span></h2>

<script type=\"text/javascript\">
tabPane.addTabPage( document.getElementById( \"img\" ) );
</script>


	<FIELDSET id=fldLayout>
	<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>А</u>втоматическая нарезка изображений (Ресайзинг)</span>: </LEGEND>
	<div style=\"padding:10\">
	
	<table>
<tr>
	<td>
	
	<table>
<tr class=adm2>
	  <td align=right>
	  <span name=txtLang id=txtLang>Макс. ширина оригинала</span>:
	  </td>
	  <td align=left>
	  <input type=text name=img_w size=3 value=\"" . $option['img_w'] . "\"> px
	  </td>
	</tr>
	<tr class=adm2>
	  <td align=right>
	  <span name=txtLang id=txtLang>Макс. высота оригинала</span>:
	  </td>
	  <td align=left>
	  <input type=text name=img_h size=3 value=\"" . $option['img_h'] . "\"> px
	  </td>
	</tr>
	<tr class=adm2>
	  <td align=right>
	  <span name=txtLang id=txtLang>Качество оригинала</span>:
	  </td>
	  <td align=left>
	  <input type=text name=width_podrobno size=3 value=\"" . $option['width_podrobno'] . "\"> %
	  </td>
	</tr>
</table>
	
	</td>
	<td>
	
	<table>
<tr class=adm2>
	  <td align=right>
	  <span name=txtLang id=txtLang>Макс. ширина тумбнейла</span>:
	  </td>
	  <td align=left>
	  <input type=text name=img_tw size=3 value=\"" . $option['img_tw'] . "\"> px
	  </td>
	</tr>
	<tr class=adm2>
	  <td align=right>
	  <span name=txtLang id=txtLang>Макс. высота тумбнейла</span>:
	  </td>
	  <td align=left>
	  <input type=text name=img_th size=3 value=\"" . $option['img_th'] . "\"> px
	  </td>
	</tr>
	<tr class=adm2>
	  <td align=right>
	  <span name=txtLang id=txtLang>Качество тумбнейла</span>:
	  </td>
	  <td align=left>
	  <input type=text name=width_kratko size=3 value=\"" . $option['width_kratko'] . "\"> %
	  </td>
	</tr>
</table>
	
	</td>
<tr>

  <td colspan=2>
  <input type=checkbox name=image_save_source_new value=1 $image_save_source> Сохранять исходное изображение при ресайзинге
  </td>
</tr>
</tr>
</table>

	
	
	
	
</div>
</FIELDSET>
<br>
<FIELDSET id=fldLayout>
	<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Н</u>астройка параметров защиты изображений от копирования</span>: </LEGEND>
	<div style=\"padding:10\">
	
	<BUTTON style=\"width: 200\" onclick=\"miniWin('adm_system_watermark.php',500,640);return false;\">
	<img src=\"../icon/photo_album.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\">
	Настроить Watermark</BUTTON>
	
</div>
</FIELDSET>
</div>
<hr>
<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"50\" >
<tr>
    <td align=\"left\" style=\"padding:10\">
    <BUTTON class=\"help\" onclick=\"helpWinParent('system')\">Справка</BUTTON>
	</td>
	<td align=\"right\" style=\"padding:10\">
<input type=submit value=ОК class=but name=optionsSAVE>
	<input type=submit name=btnLang value=Отмена class=but onClick=\"return onCancel();\">
	</td>
</tr>
</form>
</table>
                ";



        if (isset($_POST['optionsSAVE'])) {

            if (CheckedRules($UserStatus["option"], 1) == 1) {

                if ($_SESSION['skin'] != $skin_new) {
                    $skin = $skin_new;
                    $_SESSION['skin'] = $skin;
                }

                $option = unserialize($GetSystems['admoption']);


                $option["prevpanel_enabled"] = $prevpanel_enabled_new;
                $option["sklad_status"] = $sklad_status_new;
                $option["helper_enabled"] = $helper_enabled_new;
                $option["cloud_enabled"] = $cloud_enabled_new;
                $option["digital_product_enabled"] = $digital_product_enabled_new;
                $option["user_calendar"] = $user_calendar_new;
                $option["user_price_activate"] = $user_price_activate_new;
                $option["user_mail_activate_pre"] = $user_mail_activate_pre_new;
                $option["rss_graber_enabled"] = $rss_graber_enabled_new;
                $option["image_save_source"] = $image_save_source_new;
                $option["img_wm"] = $img_wm;
                $option["img_w"] = $img_w;
                $option["img_h"] = $img_h;
                $option["img_tw"] = $img_tw;
                $option["img_th"] = $img_th;
                $option["width_podrobno"] = $width_podrobno;
                $option["width_kratko"] = $width_kratko;
                $option["message_enabled"] = $message_enabled_new;
                $option["message_time"] = $message_time_new;
                $option["desktop_enabled"] = $desktop_enabled_new;
                $option["desktop_time"] = $desktop_time_new;
                $option["seller_enabled"] = $seller_enabled_new;
                $option["base_enabled"] = $base_enabled_new;
                $option["sms_enabled"] = $sms_enabled_new;
                $option["sms_status_order_enabled"] = $sms_status_order_enabled_new;
                $option["notice_enabled"] = $notice_enabled_new;
                $option["update_enabled"] = $update_enabled_new;
                $option["base_id"] = $base_id_new;
                $option["base_host"] = $base_host_new;
                $option["lang"] = $lang_new;
                $option["sklad_enabled"] = $sklad_enabled_new;
                $option["price_znak"] = $price_znak_new;
                $option["user_mail_activate"] = $user_mail_activate_new;
                $option["user_status"] = $user_status_new;
                $option["user_skin"] = $user_skin_new;
                $option["cart_minimum"] = $cart_minimum_new;
                $option["editor"] = $editor_new;
                $option["calibrated"] = $calibrated_new;
                $option["nowbuy_enabled"] = $nowbuy_enabled_new;
                $option["xmlencode"] = $xmlencode_new;
                $option["theme"] = $theme_new;
                $option_new = serialize($option);

                $sql = "UPDATE " . $PHPShopBase->getParam('base.system') . "
SET
num_row='$num_row_new',
num_row_adm='$num_cow_new',
dengi='$dengi_new',
percent='$percent_new',
skin='$skin_new',
kurs='$kurs_new',
new_num='$new_num_new',
spec_num ='$spec_num_new',
num_vitrina='$num_vitrina_new',
width_icon='$width_icon_new',
nds='$nds_new',
nds_enabled='$nds_enabled_new',
admoption='$option_new',
logo='$logo_new',
kurs_beznal='$kurs_beznal_new' ";
                $result = mysql_query($sql) or @die("Невозможно изменить запись" . $sql . mysql_error());

                echo"
	 <script>
	 CL();
	 </script>
	   ";
            } else {
                $UserChek->BadUserFormaWindow();
            }
        }
        ?>


