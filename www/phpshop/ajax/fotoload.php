<?php

/**
 * Фото-галерея
 * @package PHPShopAjaxElements
 */
$_classPath = "../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("system");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopSystem = new PHPShopSystem();

// Подключаем библиотеку поддержки.
require_once $_classPath . "lib/Subsys/JsHttpRequest/Php.php";
$JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");

function checkMultibase($img) {
    global $PHPShopSystem;
    $base_host = $PHPShopSystem->getSerilizeParam('admoption.base_host');
    if ($PHPShopSystem->getSerilizeParam('admoption.base_enabled') == 1 and !empty($base_host)) {
        $source_img = eregi_replace("/UserFiles/", "http://" . $base_host . "/UserFiles/", $img);
        return $source_img;
    }
    else
        return $img;
}

function getFotoIconPodrobno($n, $f) {
    global $SysValue;

    $fRComSatrt = null;
    $sql = "select * from " . $SysValue['base']['table_name35'] . " where parent=$n order by num";
    $result = mysql_query($sql);
    $num = mysql_num_rows($result);
    while (@$row = mysql_fetch_array(@$result)) {
        $name = $row['name'];
        $name_s = str_replace(".", "s.", $name);
        $name_b = str_replace(".", "_big.", $name);

        // Подбор исходного изображения
        if (!fopen( "http://" . $_SERVER['HTTP_HOST'] .$name_b, "r"))
            $name_b = $name;

        $id = $row['id'];
        $info = $row['info'];

        $FotoArray[] = array(
            "id" => $id,
            "name" => checkMultibase($name, true),
            "name_s" => checkMultibase($name_s, true),
            "name_b" => checkMultibase($name_b, true),
            "info" => $info
        );
    }

    $dBig = '<div align="center" id="IMGloader" style="padding-bottom: 10px">
<a class=highslide onclick="return hs.expand(this)" href="' . $FotoArray[$f]["name_b"] . '" target=_blank getParams="null">
 <img id="currentBigPic" src="' . $FotoArray[$f]["name"] . '" border="1" class="imgOn" alt="' . $info . '"  align="middle"></a><br>' . $FotoArray[$f]["info"] . '
</div>';

    foreach ($FotoArray as $k => $v) {

        $fL = $f - 1;
        $fR = $f + 1;

        if ($f == 0) {
            $fL = 0;
            $f = 1;
            $fR = 2;
        }

        if ($fR == $num) {

            $fRComSatrt = "<!-- ";
            $fRComEnd = " -->";
        }

        $disp = '<tr>
            <td><a href="javascript:fotoload(' . $n . ',' . $fL . ');" ><img src="../phpshop/lib/templates/icon/prev.png" border="0"></a></td>
  <td align="center">
  <a href="javascript:fotoload(' . $n . ',' . $fL . ');" title=' . $FotoArray[$fL]["info"] . '><img src="' . $FotoArray[$fL]["name_s"] . '" alt="' . $FotoArray[$fL]["info"] . '" border="1" class="imgOff" onmouseover="ButOn(this)" onmouseout="ButOff(this)" style="display: block;max-width:110px;max-height:115px;width: auto;height: auto;"></a>
  </td>
  <td align="center">
    <a href="javascript:fotoload(' . $n . ',' . $f . ');" title="' . $FotoArray[$f]["info"] . '"><img src="' . $FotoArray[$f]["name_s"] . '" alt="' . $FotoArray[$f]["info"] . '" border="1" class="imgOn" style="display: block;max-width:110px;max-height:115px;width: auto;height: auto;"></a>
  </td>
  <td align="center">' . $fRComSatrt . '
    <a href="javascript:fotoload(' . $n . ',' . $fR . ');" title="' . $FotoArray[$fR]["info"] . '"><img src="' . $FotoArray[$fR]["name_s"] . '" alt="' . $FotoArray[$fR]["info"] . '" border="1" class="imgOff" onmouseover="ButOn(this)" onmouseout="ButOff(this)" style="display: block;max-width:110px;max-height:115px;width: auto;height: auto;">
</a>' . $fRComEnd . '
  </td>
  <td>' . $fRComSatrt . '
  <a href="javascript:fotoload(' . $n . ',' . $fR . ');" ><img src="../phpshop/lib/templates/icon/next.png" border="0"></a>
      ' . $fRComEnd . '
</td>
</tr>';
    }

    $d = $dBig;
    if ($num > 1)
        $d.='<table class="foto">' . $disp . '</table>';
    return $d;
}

if (PHPShopSecurity::true_num($_REQUEST['xid'])) {

    $_RESULT = array(
        'foto' => getFotoIconPodrobno($_REQUEST['xid'], $_REQUEST['fid'])
    );
}
?>