<?php

/**
 * Вывод изображений для подробного описания
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopCoreFunction
 * @param obj $obj объект класса
 * @param row $row масив данных
 * @return mixed
 */
function image_gallery($obj, $row) {

    if (!empty($row['pic_big'])) {
        $n = $row['id'];
        $pic_big = $row['pic_big'];
        $name_foto = $row['name'];
        $disp = null;
        $PHPShopOrm = new PHPShopOrm($obj->getValue('base.foto'));
        $data = $PHPShopOrm->select(array('*'), array('parent' => '=' . $row['id']), array('order' => 'num'), array('limit' => 100));
        if (is_array($data)) {
            foreach ($data as $row) {

                $name = $row['name'];
                $name_s = str_replace(".", "s.", $name);
                $name_bigstr = str_replace(".", "_big.", $pic_big);
                $name_big = "http://" . $_SERVER['HTTP_HOST'] . $name_bigstr;

                // Подбор исходного изображения
                if (@fopen($name_big, "r"))
                    $name_b = str_replace(".", "_big.", $pic_big);
                else
                    $name_b = str_replace(".", ".", $pic_big);

                $id = $row['id'];
                $info = $row['info'];
                $FotoArray[] = array(
                    "id" => $id,
                    "name" => $obj->checkMultibase($name, true),
                    "name_s" => $obj->checkMultibase($name_s, true),
                    "name_b" => $obj->checkMultibase($name_b, true),
                    "info" => $info
                );
            }


            if (is_array($FotoArray)){
                if(!empty($row['info']))
                $alt=$row['info'];
                else $alt=$name_foto;
                $dBig = '<div align="center" id="IMGloader" style="padding-bottom: 10px">
<a class=highslide onclick="return hs.expand(this)" href="' . $obj->checkMultibase($name_b, true) . '" target=_blank getParams="null"><img id="currentBigPic" src="' . $obj->checkMultibase($pic_big, true) . '" border="1" class="imgOn" alt="' . $alt . '"
    onerror="NoFoto2(this)"></a><div class="highslide-caption">' . $name_foto . '</div><br>' . $FotoArray[0]["info"] . '
</div>';
            }
            if (is_array($FotoArray[0]) and count($FotoArray) > 1)
                $disp.='<td align="center">
  <a href="javascript:fotoload(' . $n . ',0);"><img src="' . $FotoArray[0]["name_s"] . '" alt="' . $FotoArray[0]["info"] . '" border="1" class="imgOn" onerror="NoFoto2(this)"></a></td>';

            if (is_array($FotoArray[1]))
                $disp.='<td align="center">
    <a href="javascript:fotoload(' . $n . ',1);"><img src="' . $FotoArray[1]["name_s"] . '" alt="' . $FotoArray[1]["info"] . '" border="1" class="imgOff" onmouseover="ButOn(this)" onmouseout="ButOff(this)" onerror="NoFoto2(this)"></a></td>';

            if (is_array($FotoArray[2]))
                $disp.='<td align="center">
     <a href="javascript:fotoload(' . $n . ',2);"><img src="' . $FotoArray[2]["name_s"] . '" alt="' . $FotoArray[2]["info"] . '" border="1" class="imgOff" onmouseover="ButOn(this)" onmouseout="ButOff(this)" onerror="NoFoto2(this)"></td><td>
<a href="javascript:fotoload(' . $n . ',2);" title="' . __('Далее') . '"><img src="../phpshop/lib/templates/icon/next.png" alt="' . __('Далее') . '" border="0"></a></td>';

            $d = $dBig;
            if (count($data) > 1)
                $d.='<table class="foto">
<tr>
' . $disp . '</tr>
</table>
<div>' . __('Доступно изображений') . ': <strong>' . count($data) . '</strong> </div>
';
        }
        else {
            $d = '<div align="center" id="IMGloader" style="padding-bottom: 10px">
 <img src="' . $obj->checkMultibase($pic_big, true) . '" border="1" class="imgOn" onerror="NoFoto2(this)"></a></div>';
        }

        // Результат
        $obj->set('productFotoList', $d);
    }
}

?>