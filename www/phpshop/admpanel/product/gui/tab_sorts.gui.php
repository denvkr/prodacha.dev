<?php

/**
 * Шаблон вывода характеристик
 * @param int $value значение характеристики
 * @param int $n ИД характеристики
 * @param int $title заголовок характеристики
 * @param array $vendor массив характеристик
 */
function sorttemplate($value, $n, $title, $vendor) {
    global $PHPShopGUI;
    $i = 1;
    $value_new[0]=array(__('Нет данных'),false, 'none');
    if (is_array($value)) {
        foreach ($value as $p) {
            $sel = null;
            if (is_array($vendor[$n])) {
                foreach ($vendor[$n] as $value) {

                    if ($value == $p[1])
                        $sel = "selected";
                }
            }elseif ($vendor[$n] == $p[1])
                $sel = "selected";

            $value_new[$i] = array($p[0], $p[1], $sel);
            $i++;
        }
    }

    $value = $PHPShopGUI->setSelect('vendor_array_new[' . $n . '][]', $value_new, '100%', false, false, false, false, false, $n,'list'.$n);
    
    $disp=$PHPShopGUI->setLine().$PHPShopGUI->setField($title, $value,'left').
            $PHPShopGUI->setField(__('Добавить и активировать новое значение характеристики'), $PHPShopGUI->setInputText(false, 'addval'.$n, null, $size = 300).$PHPShopGUI->setInput("button", "", "Добавить", "right", 70, "return enterchar($n);", "but").'<B id="sta'.$n.'"></B>','left');
    
    return $disp;
}

/**
 * Панель хактеристик товара
 * @param array $row массив данных
 * @return string 
 */
function tab_sorts($data){
    global $PHPShopGUI;
    PHPShopObj::loadClass("sort");
    $PHPShopGUI->addJSFiles('gui/tab_sorts.gui.js');
    $PHPShopSort = new PHPShopSort($data['category'], false, false, 'sorttemplate', unserialize($data['vendor_array']), false, true);
    return $PHPShopGUI->setDiv('left', $PHPShopSort->disp, 'height:450px;overflow:auto;');
}
?>
