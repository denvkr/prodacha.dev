<?php
session_start();
$SysValue = parse_ini_file("../../../../phpshop/inc/config.ini", 1);

// Определяем переменные
$host = $SysValue['connect']['host'];
$user_db = $SysValue['connect']['user_db'];
$pass_db = $SysValue['connect']['pass_db'];
$dbase = $SysValue['connect']['dbase'];
$table_name19 = $SysValue['base']['table_name19'];

@mysql_connect("$host", "$user_db", "$pass_db") or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase") or @die("Невозможно подсоединиться к базе");

require("../../watermark/watermarkFunc.php");
require("../../connect.php");
require("../../enter_to_admin.php");
include("settings.php");

if (CheckedRules($UserStatus["cat_prod"], 2) == 1) {

    function SaveImg2($img, $option) {
        $font = "../../../../phpshop/lib/font/norobot_font.ttf";
        if ((!$img) or (!$img[utmpname])) {
            $err = 1;
            $errinfo[] = "Нет данных";
            $type = false;
        }

        if ($img[ufiletyle] == "image/gif") {
            $ftype = "gif";
            $f = $img[path] . $img[name] . ".gif";
            $b = $img[path] . $img[name_big] . ".gif";
            $t = $img[path] . $img[tname] . ".gif";
            $type = true;
        } elseif (($img[ufiletyle] == "image/pjpeg") OR ($img[ufiletyle] == "image/jpeg")) {
            $ftype = "jpeg";
            $f = $img[path] . $img[name] . ".jpg";
            $b = $img[path] . $img[name_big] . ".jpg";
            $t = $img[path] . $img[tname] . ".jpg";
            $type = true;
        } elseif (!$err) {
            $err = 1;
            $errinfo[] = "Недопустимый формат файла (Разрешено: GIF и JPEG)";
            $type = false;
        }


        // Если есть данные по тумбнейлу - он нужен :)	
        if (($img[tpath]) && ($img[tw]) && ($img[th])) {
            $tumb = 1; // Нужен тумбнейл: 1/0
        }

        // Если картинка
        if ($type) {
            if (move_uploaded_file($img[utmpname], $f)) {
                //----------------------------------------- Начало --------------------
                $realsize = getimagesize($f);

                // ------------------------------------------------------------ РАСЧЕТ РАЗМЕРОВ КАРТИНКИ И ТУМБЫ ---------------------------------		
                // Картинка
                if ((($realsize[0] <= $img[w]) && ($realsize[1] <= $img[h])) OR ($img[realsize])) {
                    $fsize = 1;
                    $new_w = $realsize[0];
                    $new_h = $realsize[1];
                } else {
                    // Уменьшаем размеры оригинала если они больше нужного
                    // Если ширина больше
                    if ($realsize[0] >= $realsize[1]) {
                        // расчитываем новую высоту
                        $new_h = (int) $new_h = $realsize[1] / $realsize[0] * $img[w];
                        $new_w = (int) $new_w = $img[w];
                        // Если получившаяся высота больше разрешенной, пересчитываем 
                        if ($new_h > $img[h]) {
                            $new_w = (int) $new_w = $realsize[0] / $realsize[1] * $img[h];
                            $new_h = (int) $new_h = $img[h];
                        }
                    }

                    if ($realsize[0] < $realsize[1]) {
                        $new_w = (int) $new_w = $realsize[0] / $realsize[1] * $img[h];
                        $new_h = (int) $new_h = $img[h];
                        // Если получившаяся ширина больше разрешенной, пересчитываем
                        if ($new_w > $img[w]) {
                            $new_h = (int) $new_h = $realsize[1] / $realsize[0] * $img[w];
                            $new_w = (int) $new_w = $img[w];
                        }
                    }
                }

                if ($tumb) {
                    // тумбнейл (если нужен)	
                    if (($realsize[0] <= $img[tw]) && ($realsize[1] <= $img[th])) {
                        $tsize = 1;
                        $new_t_w = $realsize[0];
                        $new_t_h = $realsize[1];
                    } else {
                        // Уменьшаем размеры табнейла  если картинка больше максимальных значений больше нужного
                        // Если ширина больше
                        if ($realsize[0] >= $realsize[1]) {
                            // расчитываем новую высоту
                            $new_t_h = (int) $new_t_h = $realsize[1] / $realsize[0] * $img[tw];
                            $new_t_w = (int) $new_t_w = $img[tw];
                            // Если получившаяся высота больше разрешенной, пересчитываем 
                            if ($new_t_h > $img[th]) {
                                $new_t_w = (int) $new_t_w = $realsize[0] / $realsize[1] * $img[th];
                                $new_t_h = (int) $new_t_h = $img[th];
                            }
                        }

                        if ($realsize[0] < $realsize[1]) {
                            $new_t_w = (int) $new_t_w = $realsize[0] / $realsize[1] * $img[th];
                            $new_t_h = (int) $new_t_h = $img[th];
                            // Если получившаяся ширина больше разрешенной, пересчитываем
                            if ($new_t_w > $img[tw]) {
                                $new_t_h = (int) $new_t_h = $realsize[1] / $realsize[0] * $img[tw];
                                $new_t_w = (int) $new_t_w = $img[tw];
                            }
                        }
                    }
                } else {
                    //$errinfo[] = "тумбнейл не нужен";
                }

                // В зависимости от типа файла создаем img
                // GIF ======= GIF ======= GIF ======= GIF ======= GIF ======= GIF ======= GIF ======= GIF 
                if ($ftype == "gif") {
                    $src_img = imagecreatefromgif("$f");

                    // копируем исходную картинку

                    if ($option['image_save_source'] == 1) {
                        $src_img1 = imagecreatefromgif("$f");
                        $dst_img1 = WatermarkFactory($src_img1, $option, 3);
                        imagegif($dst_img1, "$b");
                        @chmod("$b", 0644);
                        imagedestroy($src_img1);
                    }

                    $dst_img = imagecreatetruecolor($new_w, $new_h);

                    imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, imagesx($src_img), imagesy($src_img));


                    $dst_img = WatermarkFactory($dst_img, $option, 1);

                    imagegif($dst_img, "$f");
                    @chmod("$f", 0644);
                    if ($tumb) {
                        $dst_imgt = imagecreatetruecolor($new_t_w, $new_t_h);
                        imagecopyresampled($dst_imgt, $src_img, 0, 0, 0, 0, $new_t_w, $new_t_h, imagesx($src_img), imagesy($src_img));

                        $dst_imgt = WatermarkFactory($dst_imgt, $option, 2);

                        imagegif($dst_imgt, "$t");
                        imagedestroy($dst_imgt);
                    }
                    imagedestroy($src_img);
                    imagedestroy($dst_img);
                }
                // JPEG ======= JPEG ======= JPEG ======= JPEG ======= JPEG ======= JPEG ======= JPEG ======= JPEG 
                if ($ftype == "jpeg") {

                    $src_img = imagecreatefromjpeg("$f");

                    // копируем исходную картинку

                    if ($option['image_save_source'] == 1) {
                        $src_img1 = imagecreatefromjpeg("$f");
                        $dst_img1 = WatermarkFactory($src_img1, $option, 3);
                        imagejpeg($dst_img1, "$b", 80); // 100 - качество оригинала, можно заменить на 80-70 для уменьшения размера
                        @chmod("$b", 0644);
                        imagedestroy($src_img1);
                    }

                    $dst_img = imagecreatetruecolor($new_w, $new_h);

                    imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, imagesx($src_img), imagesy($src_img));


                    $dst_img = WatermarkFactory($dst_img, $option, 1);

                    imagejpeg($dst_img, "$f", $img[q]);
                    @chmod("$f", 0644);
                    if ($tumb) {
                        $dst_imgt = imagecreatetruecolor($new_t_w, $new_t_h);
                        imagecopyresampled($dst_imgt, $src_img, 0, 0, 0, 0, $new_t_w, $new_t_h, imagesx($src_img), imagesy($src_img));

                        $dst_imgt = WatermarkFactory($dst_imgt, $option, 2);

                        imagejpeg($dst_imgt, "$t", $img[tq]);
                        imagedestroy($dst_imgt);
                    }
                    imagedestroy($src_img);
                    imagedestroy($dst_img);
                }

// На выходе нужно будет проверить размер созданных файлов, не больше ли нужного?

                $fsize = filesize($f);
                if ($fsize > $img[maxsize]) {
                    @unlink($f);
                    @unlink($t);
                    $err = 1;
                    $errinfo[] = "Размер файла превышает допустимый";
                }


                //----------------------------------------- Ошибки --------------------							
            } else {
                // Если файл не UPLOAD
                unlink($img[utmpname]);
                $err = 1;
                $errinfo[] = "Ошибка сервера";
            }
        }

        // Собираем массив на выход
        $out[err] = $errinfo;
        $out[filename] = $img[name] . "." . $ftype;
        $out[fullfile] = $f;
        $out[fulltumb] = $t;
        $out[type] = $ftype;
        $out[fsize] = $fsize;
        $out[w] = $new_w;
        $out[h] = $new_h;


        return ($out);
    }

    Function writeFolderSelections() {
        global $sBase0;
        global $sBase1;
        global $sBase2;
        global $sBase3;
        global $sName0;
        global $sName1;
        global $sName2;
        global $sName3;
        global $currFolder;

        echo "<select name='selCurrFolder' id='selCurrFolder' class='inpSel'>";
        recursive($sBase0, $sBase0, $sName0);
        if ($sBase1 != "")
            recursive($sBase1, $sBase1, $sName1);
        if ($sBase2 != "")
            recursive($sBase2, $sBase2, $sName2);
        if ($sBase3 != "")
            recursive($sBase3, $sBase3, $sName3);
        echo "</select>";
    }

    Function recursive($sPath, $sPath_base, $sName) {
        global $sBase0;
        global $sBase1;
        global $sBase2;
        global $sBase3;
        global $currFolder;

        if ($sPath == $sBase0 || $sPath == $sBase1 || $sPath == $sBase2 || $sPath == $sBase3) {
            if ($currFolder == $sPath)
                echo "<option value='$sPath' selected>$sName</option>";
            else
                echo "<option value='$sPath'>$sName</option>";
        }

        $oItem = opendir($sPath);
        while ($sItem = readdir($oItem)) {
            if ($sItem == "." || $sItem == "..") {
                
            } else {
                $sCurrent = $sPath . "/" . $sItem;
                $fIsDirectory = is_dir($sCurrent);

                $sDisplayed = str_replace($sBase0, "", $sCurrent);
                if ($sBase1 <> "")
                    $sDisplayed = str_replace($sBase1, "", $sDisplayed);
                if ($sBase2 <> "")
                    $sDisplayed = str_replace($sBase2, "", $sDisplayed);
                if ($sBase3 <> "")
                    $sDisplayed = str_replace($sBase3, "", $sDisplayed);

                $sDisplayed = $sName . $sDisplayed;

                if ($fIsDirectory) {
                    if ($currFolder == $sCurrent)
                        echo "<option value='$sCurrent' selected>$sDisplayed</option>";
                    else
                        echo "<option value='$sCurrent'>$sDisplayed</option>";

                    recursive($sCurrent, $sPath, $sName);
                }
            }
        }
        closedir($oItem);
    }

    $GetSystems = GetSystems();
    ?>
    <base target="_self">
    <html>
        <head>
            <title>Управ. файлами</title>
            <meta http-equiv="Content-Type" content="text-html; charset=Windows-1251">
            <LINK href="../../css/texts.css" type=text/css rel=stylesheet>
            <script language="JavaScript" src="../../java/javaMG.js" type="text/javascript"></script>
            <SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
            <script>
                function userfile_check() {
                    if (document.getElementById('userfile').value == "")
                        return false;
                }
            </script>    
        <body style="margin:0px;">
            <?
            if (!isset($_POST['load'])) {
                ?>


                <FORM name="upload" id="upload" method="post" encType="multipart/form-data" onsubmit="return userfile_check();">
                    <table width="100%"  align=center cellpadding=5 cellspacing=5 border=0>
                        <tr>
                            <td>
                                <span>Размещение</span><br>
        <? writeFolderSelections() ?>
                            </td>
                        </tr>
                        <tr>
                            <td valign=top>
                                <span>Выберите файл с расширением</span> *.gif, *.jpg<br>
                                <INPUT type=file name="userfile" id="userfile" style="width: 300px"><br>
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <table width="100%" align=center cellpadding=0 cellspacing=0 border=0>
                        <tr>
                            <td valign=top align="right" style="padding:10px">
                                <input type="submit" value="Загрузить" class="but">
                                <INPUT class=but name="btnLang" onclick="self.close()" type=reset value=Отмена> 
                                <input type="hidden" name="load" value="<?= $_REQUEST['id'] ?>">
                            </td>
                        </tr>
                    </table>
                </FORM>
                <?
            } else {


                $Admoption = unserialize($GetSystems['admoption']);

                $mycF = $_POST["selCurrFolder"];
                $mycReturn = str_replace($_SERVER['DOCUMENT_ROOT'], "", $_POST["selCurrFolder"]);

                // Соль
                $myRName = substr(abs(crc32(time())), 0, 5);

                $img[ufiletyle] = $_FILES['userfile']['type']; // Тип файла
                $img[utmpname] = $_FILES['userfile']['tmp_name'];   // Временный файл
                $img[path] = $mycF . "/";  // Куда сохранять
                $img[tpath] = $mycF . "/"; // Куда сохранять тумбнейл
                $img[name] = "img" . $_REQUEST['id'] . "_$myRName"; // Имя под которым сохранить (без расширения)
                $img[name_big] = "img" . $_REQUEST['id'] . "_" . $myRName . "_big"; // Имя под которым сохранить исходную картинку (без расширения)
                $img[tname] = "img" . $_REQUEST['id'] . "_" . $myRName . "s";   // Имя тумбнейла (без расширения)
                $img[q] = $Admoption[width_podrobno]; // Качество
                $img[tq] = $Admoption[width_kratko]; // Качество тумбы
                $img[wm] = $Admoption[img_wm]; // Поставить копирайт (!!! Только английский !!!)
                $img[size] = 13; // Размер шрифта
                $img[sizet] = 10; // Размер шрифта тумбнейл
                $img[wmargin] = 20; // отступ
                $img[twm] = 1; // Ставить watermark на тумбнейл
                $img[twmargin] = 10; // отступ на тумбе
                $img[minwater] = 50;  // Минимальный размер сторон для копирайта на картинке
                $img[tminwater] = 50; // Минимальный размер сторон для копирайта на тумбе
                $img[w] = $Admoption[img_w];      // Макс. ширина оригинала
                $img[h] = $Admoption[img_h];      // Макс. высота оригинала
                $img[realsize] = false;     // Сохранить оригинальный размер
                $img[tw] = $Admoption[img_tw];      // Макс. ширина тумбнейла
                $img[th] = $Admoption[img_th];       // Макс. высота тумбнейла
                $img[maxsize] = 1024000;  // Макс. размер в Кб


                $SaveImg = SaveImg2($img, $Admoption);


                if ($img[ufiletyle] == "image/gif")
                    $ftype = "gif";
                elseif (($img[ufiletyle] == "image/pjpeg") OR ($img[ufiletyle] == "image/jpeg"))
                    $ftype = "jpg";



                mysql_query("INSERT INTO " . $SysValue['base']['table_name35'] . " VALUES ('','" . $_REQUEST['id'] . "','$mycReturn/" . $img[name] . "." . $ftype . "','','')");



                echo "
<script>


function DoUpdateFotoList(xid) {
var req = new Subsys_JsHttpRequest_Js();
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
					window.opener.document.getElementById('fotolist').innerHTML = req.responseJS.interfaces;
// Стандартную форму обновляем
window.opener.document.getElementById('pic_small_new').value='" . $mycReturn . "/" . $img[name] . "s." . $ftype . "';
window.opener.document.getElementById('pic_big_new').value='" . $mycReturn . "/" . $img[name] . "." . $ftype . "';
			}
		}
		// Подготваливаем объект.
		req.open(null, '" . $SysValue['dir']['dir'] . "/phpshop/admpanel/product/action.php?do=update', true);
		req.send( {  xid: xid } );
}






DoUpdateFotoList(" . $_REQUEST['id'] . ");
setTimeout('self.close()',1000);
</script>
";
            }
        }
        else
            $UserChek->BadUserFormaWindow();
        ?>

    </body></html>