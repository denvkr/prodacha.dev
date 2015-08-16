<?php

// JS функция записи редактирования
$onsubmit = 'document.forms.product_edit.submit();';

class Editor {

    var $InstanceName;
    var $BasePath;
    var $Width;
    var $Height;
    var $ToolbarSet;
    var $Value;
    var $Config;

    function Editor($instanceName, $mod_enabled=false) {
        
        if($mod_enabled)
            $this->BasePath = '../../../admpanel/editor3/';
            else $this->BasePath='../editor3/';
        
        $this->InstanceName = $instanceName;
        $this->Width = '100%';
        $this->Height = '150';
        $this->Value = '';
        $this->Config = array();
    }

    function getBrowser() {
        PHPShopObj::loadClass('string');
        $getBrowser = PHPShopString::getBrowser();
        $version = explode(" ", $getBrowser);
        return $version[1];
    }

    function Create() {
        echo $this->CreateHtml();
    }

    function AddGUI() {
        return $this->CreateHtml();
    }

    function IsCompatible() {
        static $b;
        $b++;
        if ($b == 1) {
            if (strpos($_SERVER["HTTP_USER_AGENT"], "MSIE")) {
                // IE 10 Fix
                if ($this->getBrowser() < 10)
                    return '<script language=JavaScript src=" ' . $this->BasePath . 'scripts/editor.js"></script>';
                else
                    return '<script language=JavaScript src="' . $this->BasePath . 'scripts/moz/editor.js"></script>';
            }
            else
                return '<script language=JavaScript src="' . $this->BasePath . 'scripts/moz/editor.js"></script>';
        }
    }

    function CreateHtml() {
        static $i;
        global $PHPShopGUI;
        $i++;
        $HtmlValue = $this->Value;
        $Html = $this->IsCompatible() . '<pre id="idTemporary' . $i . '" name="idTemporary' . $i . '" style="display:none">' . $HtmlValue . '</pre>
	<script>
function Save' . $i . '(){
    document.getElementById("' . $this->InstanceName . '").value = oEdit' . $i . '.getHTMLBody();
    //document.forms.product_edit.elements.' . $this->InstanceName . '.value = oEdit' . $i . '.getHTMLBody();
    //document.forms.product_edit.submit();
}
';
        $PHPShopGUI->onsubmit = 'Save' . $i . '();' . $PHPShopGUI->onsubmit;

        $Html.='
	var oEdit' . $i . ' = new InnovaEditor("oEdit' . $i . '");
	oEdit' . $i . '.cmdAssetManager="modalDialogShow(\'' . $GLOBALS['SysValue']['dir']['dir'] . '/phpshop/admpanel/editor3/assetmanager/assetmanager.php\',640,500)";
	oEdit' . $i . '.width="' . $this->Width . '";
	oEdit' . $i . '.height="' . $this->Height . 'px";
	oEdit' . $i . '.btnStyles=true;
        oEdit' . $i . '.css="' . $this->Config['EditorAreaCSS'] . '";
	oEdit' . $i . '.RENDER(document.getElementById("idTemporary' . $i . '").innerHTML);
	</script>
	<input type="hidden" name="' . $this->InstanceName . '" id="' . $this->InstanceName . '">';
        return $Html;
    }

}

?>
