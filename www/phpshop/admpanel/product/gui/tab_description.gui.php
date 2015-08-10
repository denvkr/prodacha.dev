<?php
/**
 * ѕанель краткого описани€ товара
 * @param array $row массив данных
 * @return string 
 */
function tab_description($data) {
    global $PHPShopGUI,$PHPShopSystem,$PHPShopBase;
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('description_new');
    $oFCKeditor->Height = '450';
    $oFCKeditor->Config['EditorAreaCSS'] = chr(47)."phpshop".chr(47)."templates".chr(47).$PHPShopSystem->getValue('skin').chr(47).$PHPShopBase->getParam('css.default');
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = $data['description'];
    $Tab2 = $oFCKeditor->AddGUI();

    return $Tab2;
}

?>
