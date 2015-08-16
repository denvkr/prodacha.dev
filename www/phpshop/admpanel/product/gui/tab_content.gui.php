<?php
/**
 * ѕанель подробного описани€ товара
 * @param array $row массив данных
 * @return string 
 */
function tab_content($data) {
    global $PHPShopSystem,$PHPShopBase;
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '450';
    $oFCKeditor->Config['EditorAreaCSS'] = chr(47)."phpshop".chr(47)."templates".chr(47).$PHPShopSystem->getValue('skin').chr(47).$PHPShopBase->getParam('css.default');
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = $data['content'];
    $Tab3 = $oFCKeditor->AddGUI();

    return $Tab3;
}

?>
