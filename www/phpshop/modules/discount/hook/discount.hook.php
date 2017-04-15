<?php


/**
 * ������� ����� ������ �� �������
 */
class AddToTemplateDiscountElement extends PHPShopElements {

    var $debug = false;

    /**
     * �����������
     */
    function AddToTemplateDiscountElement() {
        parent::PHPShopElements();
        $this->option();
    }

    /**
     * ���������
     */
    function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['discount']['discount_system']);
        $PHPShopOrm->debug = $this->debug;
        $this->option = $PHPShopOrm->select();
    }

    /**
     * ����� �����
     */
    function display() {
        $forma = parseTemplateReturn($GLOBALS['SysValue']['templates']['discount']['discount_forma'], true);
        $this->set('leftMenuContent', $forma);
        $this->set('leftMenuName', $this->option['title']);

        // ���������� ������
        if (empty($this->option['windows']))
            $dis = $this->parseTemplate($this->getValue('templates.left_menu'));
        else {
            if (empty($this->option['enabled']))
                $dis = parseTemplateReturn($GLOBALS['SysValue']['templates']['discount']['discount_window_forma'], true);
            else {
                $this->set('leftMenuContent', parseTemplateReturn($GLOBALS['SysValue']['templates']['discount']['discount_window_forma'], true));
                $dis = $this->parseTemplate($this->getValue('templates.left_menu'));
            }
        }




        // ��������� ���������� �������
        switch ($this->option['enabled']) {

            case 1:
                $this->set('leftMenu', $dis, true);
                break;

            case 2:
                $this->set('rightMenu', $dis, true);
                break;

            default: $this->set('discount', $dis);
        }
    }

}


function uid_mod_discount_hook($obj,$row,$rout) {
    if($rout == 'MIDDLE'){
    $AddToTemplateDiscountElement = new AddToTemplateDiscountElement();
    //$AddToTemplateDiscountElement->display();
    }
}


$addHandler=array
        (
        'UID'=>'uid_mod_discount_hook'
);
?>