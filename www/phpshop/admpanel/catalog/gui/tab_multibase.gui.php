<?php
/**
 * Панель мультибазы каталога
 * @param array $data массив данных
 * @return string 
 */
function tab_multibase($option) {
    global $PHPShopGUI;

    $value=array();
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name31']);
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'name'), array('limit' => 100));
    if (is_array($data)) {
        foreach ($data as $row) {
            $server = preg_split('/i/', $option['servers'], -1, PREG_SPLIT_NO_EMPTY);
            $sel=false;
            if (is_array($server))
                foreach ($server as $v) {
                    if ($row['id'] == $v)
                        $sel = "selected";
                }
            $value[] = array($row['data'].' >> '.$row['host'], $row['id'], $sel);
        }
    }
    
    return  $PHPShopGUI->setSelect('servers[]', $value, '300', false, false, false, '300', false,true);
}

?>