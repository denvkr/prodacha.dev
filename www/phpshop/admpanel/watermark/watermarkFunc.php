<?php

error_reporting(0);

function newWatermarkPng($sourceImageObject, $watermarkImageObject, $alphaChanel = 100, $mergeLevel = 100, $mergeFlag = 0, $positionX = 0, $positionY = 0, $positionFlag = 0, $sm = 0, $copyFlag = 0) {

// вычисляем коэффициент альфа канала    	
    $alphaChanel = $alphaChanel / 100;

//вычисляем параметры исходного изображения и вотермарка			
    $sourceImageObjectWeight = imagesx($sourceImageObject);
    $sourceImageObjectHeight = imagesy($sourceImageObject);
    $watermarkImageObjectWeight = imagesx($watermarkImageObject);
    $watermarkImageObjectHeight = imagesy($watermarkImageObject);


// Изменяем размер вотермарка с учётом коэффициента
    if ($mergeFlag == 1) {
        if ($mergeLevel > 0) {
            // высчитываем коэффициент ресайзинга
            $mergeLevel /= 100;
            // высчитываем уменьшенные размеры
            $watermarkImageObjectHeight_new = round($watermarkImageObjectHeight * $mergeLevel);
            $watermarkImageObjectWeight_new = round($watermarkImageObjectWeight * $mergeLevel);
            // создаём изображение вотермарка с учётом ресайза
            $NewWatermarkImageObject = imagecreate($watermarkImageObjectWeight_new, $watermarkImageObjectHeight_new);
            // уменьшаем исходный вотермарк
            imagecopyresized($NewWatermarkImageObject, $watermarkImageObject, 0, 0, 0, 0, $watermarkImageObjectWeight_new, $watermarkImageObjectHeight_new, $watermarkImageObjectWeight, $watermarkImageObjectHeight);
            $watermarkImageObject = $NewWatermarkImageObject;
            $watermarkImageObjectHeight = $watermarkImageObjectHeight_new;
            $watermarkImageObjectWeight = $watermarkImageObjectWeight_new;
        }
    }




//проверяем флаг размножения вотермарка, если 0, то не размножаем.


    if ($copyFlag == 0) {

        //вотермарк размещаем по центру исходной картинки
        //крайние раницы размещения вотермарка на исходном изображении
        if ($positionFlag == 1) {
            $sourceImageObjectLeftGranica_po_x = floor(( $sourceImageObjectWeight / 2 ) - ( $watermarkImageObjectWeight / 2 ));
            $sourceImageObjectRightGranica_po_x = ceil(( $sourceImageObjectWeight / 2 ) + ( $watermarkImageObjectWeight / 2 ));
            $sourceImageObjectVerhGranica_po_y = floor(( $sourceImageObjectHeight / 2 ) - ( $watermarkImageObjectHeight / 2 ));
            $sourceImageObjectNijnGranica_po_y = ceil(( $sourceImageObjectHeight / 2 ) + ( $watermarkImageObjectHeight / 2 ));
        } elseif ($positionFlag == 2) {
            $sourceImageObjectLeftGranica_po_x = $positionX;
            $sourceImageObjectRightGranica_po_x = $positionX + $watermarkImageObjectWeight;
            $sourceImageObjectVerhGranica_po_y = $positionY;
            $sourceImageObjectNijnGranica_po_y = $positionY + $watermarkImageObjectHeight;
        } elseif ($positionFlag == 3) {
            $sourceImageObjectLeftGranica_po_x = $sourceImageObjectWeight - $watermarkImageObjectWeight - $positionX;
            $sourceImageObjectRightGranica_po_x = $sourceImageObjectWeight - $positionX;
            $sourceImageObjectVerhGranica_po_y = $positionY;
            $sourceImageObjectNijnGranica_po_y = $positionY + $watermarkImageObjectHeight;
        } elseif ($positionFlag == 4) {
            $sourceImageObjectLeftGranica_po_x = $sourceImageObjectWeight - $watermarkImageObjectWeight - $positionX;
            $sourceImageObjectRightGranica_po_x = $sourceImageObjectWeight - $positionX;
            $sourceImageObjectVerhGranica_po_y = $sourceImageObjectHeight - $watermarkImageObjectHeight - $positionY;
            $sourceImageObjectNijnGranica_po_y = $sourceImageObjectHeight - $positionY;
        } elseif ($positionFlag == 5) {
            $sourceImageObjectLeftGranica_po_x = $positionX;
            $sourceImageObjectRightGranica_po_x = $positionX + $watermarkImageObjectWeight;
            $sourceImageObjectVerhGranica_po_y = $sourceImageObjectHeight - $watermarkImageObjectHeight - $positionY;
            $sourceImageObjectNijnGranica_po_y = $sourceImageObjectHeight - $positionY;
        }
    } else {

        //Делаем Размножение вотермарка на всё изображение, учитывая смещение $sm для четных строк размножения/////////////////////////
        //Смещение строки относительно первой.
        $sm = 80;  // нужно вынести в параметры
        //крайние раницы размещения вотермарка на исходном изображении
        // так как тут делаем вотермарк по всему изображению границы = размерам исходного изображения
        $sourceImageObjectLeftGranica_po_x = 0;
        $sourceImageObjectRightGranica_po_x = $w2 = $sourceImageObjectWeight;
        $sourceImageObjectVerhGranica_po_y = 0;
        $sourceImageObjectNijnGranica_po_y = $h2 = $sourceImageObjectHeight;

        // новый объект вотермарка для размножения исходного по всему изображению
        $NewWatermarkImageObject = imagecreate($sourceImageObjectWeight, $sourceImageObjectHeight);
        $white = imagecolorallocate($NewWatermarkImageObject, 255, 255, 255);
        $w1 = $watermarkImageObjectWeight;
        $h1 = $watermarkImageObjectHeight;


        for ($y = 0; $y < ceil($h2 / $h1); $y++) {

            $flag = ($y + 1) % 2;

            if ($flag == 1)
                $temp_add = 1;
            else
                $temp_add = 0;

            for ($x = 0; $x < ceil($w2 / $w1) + $temp_add; $x++) {
                if ($flag == 0) {
                    imagecopy($NewWatermarkImageObject, $watermarkImageObject, $x * $w1, $y * $h1, 0, 0, $w1, $h1);
                    //imagecopymerge($sourceImageObject,$watermarkImageObject,$x*$w1,$y*$h1,0,0,$w1,$h1,100);
                } else {
                    imagecopy($NewWatermarkImageObject, $watermarkImageObject, ($x * $w1) - $sm, $y * $h1, 0, 0, $w1, $h1);
                    //imagecopymerge($sourceImageObject,$watermarkImageObject,($x*$w1)-$sm,$y*$h1,0,0,$w1,$h1,100);
                }
            }
        }


        $watermarkImageObject = $NewWatermarkImageObject;
        $watermarkImageObjectWeight = $w2;
        $watermarkImageObjectHeight = $h2;
        $sourceImageObjectLeftGranica_po_x = 0;
        $sourceImageObjectVerhGranica_po_y = 0;
        /////////////////////////////////////////////////
    }



    // проверяем "если размер вотермарка больше размера исходной картинки", то вотермарк не накладываем и возвращаем исходную картинку.
    if (($watermarkImageObjectHeight > $sourceImageObjectHeight) || ($watermarkImageObjectWeight > $sourceImageObjectWeight))
        return $sourceImageObject;

    $return_img = imagecreatetruecolor($sourceImageObjectWeight, $sourceImageObjectHeight);


    for ($y = 0; $y < $sourceImageObjectHeight; $y++) {
        for ($x = 0; $x < $sourceImageObjectWeight; $x++) {
            $return_color = NULL;


            $watermark_x = $x - $sourceImageObjectLeftGranica_po_x;
            $watermark_y = $y - $sourceImageObjectVerhGranica_po_y;


            $main_rgb = imagecolorsforindex($sourceImageObject, imagecolorat($sourceImageObject, $x, $y));


            if ($watermark_x >= 0 && $watermark_x < $watermarkImageObjectWeight &&
                    $watermark_y >= 0 && $watermark_y < $watermarkImageObjectHeight) {
                $watermark_rbg = imagecolorsforindex($watermarkImageObject, imagecolorat($watermarkImageObject, $watermark_x, $watermark_y));


                $watermark_alpha = round(( ( 127 - $watermark_rbg['alpha'] ) / 127), 2);
                $watermark_alpha = $watermark_alpha * $alphaChanel;


                $avg_red = color_find($main_rgb['red'], $watermark_rbg['red'], $watermark_alpha);
                $avg_green = color_find($main_rgb['green'], $watermark_rbg['green'], $watermark_alpha);
                $avg_blue = color_find($main_rgb['blue'], $watermark_rbg['blue'], $watermark_alpha);


                $return_color = imageColor_find($return_img, $avg_red, $avg_green, $avg_blue);
            } else {
                $return_color = imagecolorat($sourceImageObject, $x, $y);
            }


            imagesetpixel($return_img, $x, $y, $return_color);
        }
    }


    return $return_img;
}

function color_find($color_a, $color_b, $alphaChanel) {
    return round(( ( $color_a * ( 1 - $alphaChanel ) ) + ( $color_b * $alphaChanel )));
}

function imageColor_find($im, $r, $g, $b) {
    $c = imagecolorexact($im, $r, $g, $b);
    if ($c != -1)
        return $c;
    $c = imagecolorallocate($im, $r, $g, $b);
    if ($c != -1)
        return $c;
    return imagecolorclosest($im, $r, $g, $b);
}

function newWatermarkText($main_img_obj, $text, $font, $r = 128, $g = 128, $b = 128, $alpha_level = 100, $textPositionFlag = 0, $textPositionX = 0, $textPositionY = 0, $textSize = 10, $textAngle = 0) {

    $width = imagesx($main_img_obj);
    $height = imagesy($main_img_obj);

    //угол наклона надписи 
    if ($textPositionFlag > 0)
        $angle = $textAngle % 360;
    else
        $angle = -rad2deg(atan2((-$height), ($width)));

    if ($textPositionFlag == 0)
        $text = "   " . $text . "   ";

    $c = imagecolorallocatealpha($main_img_obj, $r, $g, $b, $alpha_level);

    // размер шрифта
    if ($textPositionFlag > 0)
        $size = $textSize;
    else
        $size = (($width + $height) / 2) * 2 / strlen($text);

    $box = imagettfbbox($size, $angle, $font, $text);

    // расчитываем размеры текстового блока
    $textBlockWidth = abs($box[4] - $box[0]);
    $textBlockHeight = abs($box[5] - $box[1]);

    // расчитываем положение блока текста на исходном изображении
    if (($textPositionFlag == 0) || ($textPositionFlag == 1)) {
        $x = $width / 2 - $textBlockWidth / 2;
        $y = $height / 2 + $textBlockHeight / 2;
    } elseif ($textPositionFlag == 2) {
        $x = $textPositionX;
        $y = $textPositionY + $textBlockHeight;
    } elseif ($textPositionFlag == 3) {
        $x = $width - $textBlockWidth - $textPositionX;
        $y = $textPositionY + $textBlockHeight;
    } elseif ($textPositionFlag == 4) {
        $x = $width - $textBlockWidth - $textPositionX;
        $y = $height - $textPositionY;
    } elseif ($textPositionFlag == 5) {
        $x = $textPositionX;
        $y = $height - $textPositionY;
    }


    setlocale(LC_ALL, 'ru_RU.CP1251', 'rus_RUS.CP1251', 'Russian_Russia.1251');

    imagettftext($main_img_obj, $size, $angle, $x, $y, $c, $font, $text);

    return $main_img_obj;
}

function WatermarkFactory($img, $option, $flag) {

    if (($flag == 1) && ($option['watermark_big']['big_enabled'] == 1)) {



        if ($option['watermark_big']['big_type'] == "text") {
            $img = newWatermarkText($img, $option['watermark_big']['big_text'], "../../watermark/fonts/" . $option['watermark_big']['big_font'], $option['watermark_big']['big_colorR'], $option['watermark_big']['big_colorG'], $option['watermark_big']['big_colorB'], $option['watermark_big']['big_text_alpha'], $option['watermark_big']['big_text_positionFlag'], $option['watermark_big']['big_text_positionX'], $option['watermark_big']['big_text_positionY'], $option['watermark_big']['big_size'], $option['watermark_big']['big_angle']);

            return $img;
        } elseif ($option['watermark_big']['big_type'] == "png") {

            //$watermark_img_obj = imagecreatefrompng("watermark.png");
            $watermark_img_obj = imagecreatefrompng("../../../.." . $option['watermark_big']['big_png_file']);

            $img = newWatermarkPng($img, $watermark_img_obj, $option['watermark_big']['big_alpha'], $option['watermark_big']['big_mergeLevel'], 1, $option['watermark_big']['big_positionX'], $option['watermark_big']['big_positionY'], $option['watermark_big']['big_positionFlag'], $option['watermark_big']['big_sm'], $option['watermark_big']['big_copyFlag']);


            return $img;
        }
    } elseif (($flag == 2) && ($option['watermark_small']['small_enabled'] == 1)) {
        if ($option['watermark_small']['small_type'] == "text") {


            $img = newWatermarkText($img, $option['watermark_small']['small_text'], "../../watermark/fonts/" . $option['watermark_small']['small_font'], $option['watermark_small']['small_colorR'], $option['watermark_small']['small_colorG'], $option['watermark_small']['small_colorB'], $option['watermark_small']['small_text_alpha'], $option['watermark_small']['small_text_positionFlag'], $option['watermark_small']['small_text_positionX'], $option['watermark_small']['small_text_positionY'], $option['watermark_small']['small_size'], $option['watermark_small']['small_angle']);

            return $img;
        } elseif ($option['watermark_small']['small_type'] == "png") {

            //$watermark_img_obj = imagecreatefrompng("watermark.png");
            $watermark_img_obj = imagecreatefrompng("../../../.." . $option['watermark_small']['small_png_file']);

            $img = newWatermarkPng($img, $watermark_img_obj, $option['watermark_small']['small_alpha'], $option['watermark_small']['small_mergeLevel'], 1, $option['watermark_small']['small_positionX'], $option['watermark_small']['small_positionY'], $option['watermark_small']['small_positionFlag'], $option['watermark_small']['small_sm'], $option['watermark_small']['small_copyFlag']);

            echo $option['watermark_small']['small_png_file'];
            return $img;
        }
    } elseif (($flag == 3) && ($option['watermark_ishod']['ishod_enabled'] == 1)) {
        if ($option['watermark_ishod']['ishod_type'] == "text") {


            $img = newWatermarkText($img, $option['watermark_ishod']['ishod_text'], "../../watermark/fonts/" . $option['watermark_ishod']['ishod_font'], $option['watermark_ishod']['ishod_colorR'], $option['watermark_ishod']['ishod_colorG'], $option['watermark_ishod']['ishod_colorB'], $option['watermark_ishod']['ishod_text_alpha'], $option['watermark_ishod']['ishod_text_positionFlag'], $option['watermark_ishod']['ishod_text_positionX'], $option['watermark_ishod']['ishod_text_positionY'], $option['watermark_ishod']['ishod_size'], $option['watermark_ishod']['ishod_angle']);

            return $img;
        } elseif ($option['watermark_ishod']['ishod_type'] == "png") {

            //$watermark_img_obj = imagecreatefrompng("watermark.png");
            $watermark_img_obj = imagecreatefrompng("../../../.." . $option['watermark_ishod']['ishod_png_file']);

            $img = newWatermarkPng($img, $watermark_img_obj, $option['watermark_ishod']['ishod_alpha'], $option['watermark_ishod']['ishod_mergeLevel'], 1, $option['watermark_ishod']['ishod_positionX'], $option['watermark_ishod']['ishod_positionY'], $option['watermark_ishod']['ishod_positionFlag'], $option['watermark_ishod']['ishod_sm'], $option['watermark_ishod']['ishod_copyFlag']);

            echo $option['watermark_ishod']['ishod_png_file'];
            return $img;
        }
    }
    else
        return $img;
}

?>