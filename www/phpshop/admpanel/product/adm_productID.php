<?php

$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("page");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("category");

// ����������� � ��
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();

// ��������� ���������
$PHPShopSystem = new PHPShopSystem();

// �������� GUI
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI('top',false,'addEventsToHTML()');
$PHPShopGUI->title = __("�������������� ������");
$PHPShopGUI->reload = "right";
$PHPShopGUI->addJSFiles('/phpshop/lib/Subsys/JsHttpRequest/Js.js');
$PHPShopGUI->addJSFiles('/admpanel_product_custev.js');
$PHPShopGUI->addJSFiles($_classPath.'templates'.chr(47).$_SESSION['skin'].chr(47).'javascript/lib/jquery-1.11.0.min.js');
$PHPShopGUI->addJSFiles($_classPath.'templates'.chr(47).$_SESSION['skin'].chr(47).'javascript/jquery.inputmask-3.x/js/inputmask.js');
$PHPShopGUI->addJSFiles($_classPath.'templates'.chr(47).$_SESSION['skin'].chr(47).'javascript/jquery.inputmask-3.x/js/inputmask.date.extensions.js');
$PHPShopGUI->addJSFiles($_classPath.'templates'.chr(47).$_SESSION['skin'].chr(47).'javascript/jquery.inputmask-3.x/js/inputmask.extensions.js');
$PHPShopGUI->addJSFiles($_classPath.'templates'.chr(47).$_SESSION['skin'].chr(47).'javascript/jquery.inputmask-3.x/js/jquery.inputmask.js');

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);

// ������
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $PHPShopOrm, $PHPShopSystem;
    //echo $PHPShopSystem->getSerilizeParam('admoption.sklad_status');
    // ��� ����
    if ($_COOKIE['winOpenType'] == 'default')
        $dot = ".";
    else
        $dot = false;

    // �������
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_REQUEST['productID'])));
    //������� promo
    $datapromo = $PHPShopOrm->query('select promocode,discountprice from phpshop_promocode prc join phpshop_product_promo_relation pppr on prc.id=pppr.promo_id where pppr.product_id='.intval($_REQUEST['productID']));
    $datapromo_row = mysql_fetch_assoc($datapromo);
    //ob_start();
    //echo 'select promocode,discountprice from phpshop_promocode prc join phpshop_product_promo_relation pppr on prc.id=pppr.promo_id where pppr.product_id='.intval($_REQUEST['productID']);
    //echo '--'.$datapromo_row['promocode'].'--'.$datapromo_row['discountprice'];   
    /* PERFORM COMLEX QUERY, ECHO RESULTS, ETC. */
    //$page = ob_get_contents();
    //ob_end_clean();
    //$file = "db_promocode_select.log";
    //$fw = fopen($file, "w+");
    //fputs($fw,$page, strlen($page));
    //fclose($fw);

    $PHPShopGUI->dir = "../";
    //$PHPShopGUI->size = "700,670";
    // ����������� ��������� ����
    $PHPShopGUI->setHeader(__('�������������� ������ "' . $data['name'] . '"'), __("������� ������ ��� ������ � ����."), $PHPShopGUI->dir . "img/i_actionlog_med[1].gif");


    // ��� ������
    if (!is_array($data)) {
        $PHPShopGUI->setFooter($PHPShopGUI->setInput("button", "", "�������", "center", 100, "return onCancel();", "but"));
        return true;
    }

    // ID ���� ��� ������ ��������
    $PHPShopGUI->setID(__FILE__, $data['id']);

    // ����� ��������
    $Tab1 = $PHPShopGUI->setField(__("������� <b>CID " . $data['category'] . "</b>:"), $PHPShopGUI->setInputText(false, "parent_name", getCatPath($data['category']), '480px', false, 'left') .
            $PHPShopGUI->setInput("hidden", "category_new", $data['category'], "left", 450) .
            $PHPShopGUI->setButton(__('�������'), "../img/icon-move-banner.gif", "100px", '25px', "right", "miniWin('" . $dot . "./catalog/adm_cat.php?category=" . $data['category'] . "',300,400);return false;"));

    // ������������
    $Tab1.=$PHPShopGUI->setField("������������ <b>UID " . $data['id'] . "</b>:", $PHPShopGUI->setInputText(false, 'name_new', $data['name'], '100%'));

    // �������
    $Tab1.=$PHPShopGUI->setField('�������:', $PHPShopGUI->setInputText('#', 'uid_new', $data['uid']), 'left');

    // �����
    if (empty($data['ed_izm']))
        $ed_izm = '��.';
    else
        $ed_izm = $data['ed_izm'];

    $Tab1.=$PHPShopGUI->setField('�����:', $PHPShopGUI->setInputText(false, 'items_new', $data['items'], 50, $ed_izm), 'left');

    // ���
    $Tab1.=$PHPShopGUI->setField('���:', $PHPShopGUI->setInputText(false, 'weight_new', $data['weight'], 50, '��.'), 'left');

    // ������� ���������
    if (empty($data['ed_izm']))
        $data['ed_izm'] = '��.';
    $Tab1.=$PHPShopGUI->setField('������� ���.:', $PHPShopGUI->setInputText(false, 'ed_izm_new', $data['ed_izm'], 70), 'left');

    // ������������� ������
    $Tab1.=$PHPShopGUI->setLine() . $PHPShopGUI->setField('������������� ������ ��� ���������� �������:', $PHPShopGUI->setTextarea('odnotip_new', $data['odnotip'], false, '280px') .
                    $PHPShopGUI->setLine() .
                    $PHPShopGUI->setImage('../icon/icon_info.gif', 16, 16) .
                    __('������� ID ������� � ������� 1,2,3 ��� ��������'), 'left');

    // �������������� ��������
    $Tab1.=$PHPShopGUI->setField('�������������� ��������:', $PHPShopGUI->setTextarea('dop_cat_new', $data['dop_cat'], false, '280px') .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setImage('../icon/icon_info.gif', 16, 16) .
            __('������� ID ��������� � ������� #1#2#3# ��� ��������'), 'left');

    $Tab1.=$PHPShopGUI->setLine();

    // ����� ������
    $Tab1_1.=$PHPShopGUI->setLine() . $PHPShopGUI->setField('����� ������:', $PHPShopGUI->setCheckbox('enabled_new', 1, '����� � ��������', $data['enabled']) .
                    $PHPShopGUI->setLine() .
                    $PHPShopGUI->setCheckbox('spec_new', 1, '���������������', $data['spec']) .
                    $PHPShopGUI->setLine() .
                    $PHPShopGUI->setCheckbox('newtip_new', 1, '�������', $data['newtip']) .
                    $PHPShopGUI->setLine() .
                    $PHPShopGUI->setInputText('�', 'num_new', $data['num'], 50, '�� �������'), 'left');

    // ������
    $PHPShopValutaArray = new PHPShopValutaArray();
    $valuta_array = $PHPShopValutaArray->getArray();
    $valuta_area = null;
    if (is_array($valuta_array))
        foreach ($valuta_array as $val) {
            if ($data['baseinputvaluta'] == $val['id']) {
                $check = 'checked';
                $valuta_def_name = $val['code'];
            }
            else
                $check = false;
            $valuta_area.=$PHPShopGUI->setRadio('baseinputvaluta_new', $val['id'], $val['name'], $check);
            $valuta_area.=$PHPShopGUI->setLine();
        }

    // ����
    $Tab1_1.=$PHPShopGUI->setField('����:', $PHPShopGUI->setInputText('���� 1', 'price_new', $data['price'], 50, $valuta_def_name) .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setInputText('���� 2', 'price2_new', $data['price2'], 50, $valuta_def_name) .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setInputText('���� 3', 'price3_new', $data['price3'], 50, $valuta_def_name), 'left');

    // ������
    $Tab1_1.=$PHPShopGUI->setField(__('������:'), $valuta_area, 'left');

	$yandexmarkconf=read_yandex_opt_config('../../../yandexmark_config.txt');
	$jseventfunction='return true';
	if (!is_array_empty($yandexmarkconf)) {
		foreach ( $yandexmarkconf as $key => $value ) {
				if ($value['value']=='1'){
					$jseventfunction='addEventsToHTML(1)';
				} else {
					$jseventfunction='return true';
				}
		} 		
	}
    // ���� ��������������
    $Tab1_2.=$PHPShopGUI->setField('����:', $PHPShopGUI->setInputText('���� 4', 'price4_new', $data['price4'], 50, $valuta_def_name) .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setInputText('���� 5', 'price5_new', $data['price5'], 50, $valuta_def_name) .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setCheckbox('sklad_new', 1, '��� �����', $data['sklad'],$jseventfunction), 'left');

    // ����������
    $Tab1_2.=$PHPShopGUI->setField(__('����������:'), $PHPShopGUI->setInputText(__('������ ����'), 'price_n_new', $data['price_n'], 50, $valuta_def_name), 'left');

    //��������
    $Tab1_2.=$PHPShopGUI->setField(__('��������:'),$PHPShopGUI->setInputText('��������', 'promocode_new', $datapromo_row['promocode'], 60) .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setInputText('���� �� �������', 'discountprice_new', $datapromo_row['discountprice'], 55, $valuta_def_name), 'left');
    //����� ������
    $Tab1_2.=$PHPShopGUI->setField(__('����� ������:'),$PHPShopGUI->setInputText('�������', 'outdated_new', $data['outdated'], 60) .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setInputText('����� ������', 'analog_new', $data['analog'], 55), 'left');

    // ������
    if (!empty($data['pic_small'])) {
        $img_width = $PHPShopSystem->getSerilizeParam('admoption.img_tw');
        $PHPShopInterface = new PHPShopInterface('_pretab1_');
        $PHPShopInterface->setTab(array(__("�����������"), $PHPShopGUI->setFrame('img', $data['pic_small'], $img_width + 20, $img_width, 'none', 0, 'No'), 120));
        $Tab1.=$PHPShopGUI->setDiv('left', $PHPShopInterface->getContent(), 'width:' . ($img_width + 50) . 'px;float:left');
    }

    // YML
    $Tab1_3 = $PHPShopGUI->setField($PHPShopGUI->setCheckbox('yml_new', 1, __('����� � ������ �������'), $data['yml']), $PHPShopGUI->setRadio('p_enabled_new', 1, __('� �������'), $data['p_enabled']) .
            $PHPShopGUI->setRadio('p_enabled_new', 0, __('��� �����'), $data['p_enabled']));

    // BID
    $data['yml_bid_array'] = unserialize($data['yml_bid_array']);
    $Tab1_3.=$PHPShopGUI->setField(__('BID'), $PHPShopGUI->setInputText('������', 'yml_bid_array[bid]', $data['yml_bid_array']['bid'], 100, $PHPShopGUI->setLink('http://partner.market.yandex.ru/legal/tt/', $PHPShopGUI->setImage('../icon/icon_info.gif', 16, 16), false, false, '�������� ����')), 'left');

    // CBID
    $Tab1_3.=$PHPShopGUI->setField(__('CBID'), $PHPShopGUI->setInputText('������', 'yml_bid_array[cbid]', $data['yml_bid_array']['cbid'], 100, $PHPShopGUI->setLink('http://partner.market.yandex.ru/legal/tt/', $PHPShopGUI->setImage('../icon/icon_info.gif', 16, 16), false, false, '�������� ����')), 'left');

    // �������
    $Tab1_4 = $PHPShopGUI->setField(__('�����'), $PHPShopGUI->setRadio('parent_enabled_new', 0, __('������� �����'), $data['parent_enabled']) .
            $PHPShopGUI->setRadio('parent_enabled_new', 1, __('���������� ����� ��� �������� ������'), $data['parent_enabled']));
    $Tab1_4.=$PHPShopGUI->setTextarea('parent_new', $data['parent'], "none", '99%', '50px') .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setImage('../icon/icon_info.gif', 16, 16) .
            __('������� ID �������-�������� ����� ������� ��� ������� (100,101). ');

    // �����
    $PHPShopInterface = new PHPShopInterface('_pretab2_');
    $PHPShopInterface->setTab(array(__("��������"), $Tab1_1, 120), array(__("�������������� ����/promo ����"), $Tab1_2, 120), array(__("YML"), $Tab1_3, 120), array(__("�������"), $Tab1_4, 120));
    $Tab1.=$PHPShopGUI->setDiv('left', $PHPShopInterface->getContent(), 'float:left;padding-left:5px');

    // �������� �������� ��������
    $Tab2 = $PHPShopGUI->loadLib('tab_description', $data);

    // �������� ���������� ��������
    $Tab3 = $PHPShopGUI->loadLib('tab_content', $data);

    // ������
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['page']);
    $data_page = $PHPShopOrm->select(array('*'), false, array('order' => 'name'), array('limit' => 100));

    if (strstr($data['page'], ',')) {
        $data['page'] = explode(",", $data['page']);
    }else
        $data['page'] = array();

    $value = array();
    if (is_array($data_page))
        foreach ($data_page as $val) {
            if (is_numeric(array_search($val['link'], $data['page']))) {
                $check = 'selected';
            }
            else
                $check = false;

            $value[] = array($val['name'], $val['link'], $check);
        }
    $Tab4_1 = $PHPShopGUI->setSelect('page[]', $value, '90%', false, false, false, '90%', 30, true);

    // �����
    $Tab4_2 = $PHPShopGUI->loadLib('tab_files', $data);

    // ���������
    $PHPShopInterfaceDoc = new PHPShopInterface('_doc_');
    $PHPShopInterfaceDoc->setTab(array(__("������"), $Tab4_1, 400), array(__("�����"), $Tab4_2, 400));
    $Tab4 = $PHPShopInterfaceDoc->getContent();

    // �����������
    $Tab6 = $PHPShopGUI->loadLib('tab_img', $data);

    // ��������������
    $Tab7 = $PHPShopGUI->loadLib('tab_sorts', $data);

    // ���������
    $Tab8 = $PHPShopGUI->loadLib('tab_headers', $data);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array(__("��������"), $Tab1, 450), array(__("�����������"), $Tab6, 450), array(__("��������"), $Tab2, 450), array(__("��������"), $Tab3, 450), array(__("���������"), $Tab4, 450), array(__("��������������"), $Tab7, 450), array(__("���������"), $Tab8, 450));

    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $data);

    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "productID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "", "������", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("button", "delID", "�������", "right", 70, "return onDelete('" . __('�� ������������� ������ �������?') . "')", "but", "actionDelete.cat_prod.remove") .
            $PHPShopGUI->setInput("submit", "editID", "���������", "right", 70, "", "but", "actionUpdate.cat_prod.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionSave.cat_prod.edit")
            . '<!--����������� �� ������ � ���� �������� ������ ��� ���������-->'
            . '<script type="text/javascript">'
            . '$("input[name=\'discountprice_new\']:eq(0)").inputmask("mask", {mask: "9", "repeat": 10, "greedy": false});'
            . '$("input[name=\'promocode_new\']:eq(0)").inputmask("mask", {mask: "*","repeat": 10,"greedy": false,"showMaskOnFocus": true,definitions: {\'*\': {validator: "[0-9A-Za-z\u0410-\u044F\u0401\u0451\u00C0-\u00FF\u00B5]",cardinality: 1}},onKeyValidation: function (result) {}});'
            . '</script>';

    // �����
    $PHPShopGUI->setFooter($ContentFooter);
  
    return true;
}

/**
 * ���� ��������
 * @param int $category �� ���������
 * @return string 
 */
function getCatPath($category) {

    $PHPShopCategoryArray = new PHPShopCategoryArray();
    $i = 1;
    $str = __('������');
    while ($i < 10) {
        $parent = $PHPShopCategoryArray->getParam($category . '.parent_to');
        if (isset($parent)) {
            $path[$category] = $PHPShopCategoryArray->getParam($category . '.name');
            $category = $parent;
        }
        $i++;
    }

    if (is_array($path)) {
        $path = array_reverse($path);

        foreach ($path as $val)
            $str.=' -> ' . $val;

        return $str;
    }
}

/**
 * ����� ����������
 */
function actionSave() {
    global $PHPShopGUI;

    // ���������� ������
    actionUpdate();

    $PHPShopGUI->setAction($_POST['productID'], 'actionStart', 'none');
}

/**
 * ����� ����������
 * @return bool 
 */
function actionUpdate() {
    global $PHPShopModules, $PHPShopSystem;

    // ���������� �� ������
    switch ($PHPShopSystem->getSerilizeParam('admoption.sklad_status')) {

        case(3):
            if ($_POST['items_new'] < 1) {
                $_POST['sklad_new'] = 1;
                $_POST['enabled_new'] = 1;
            } else {
                $_POST['sklad_new'] = 0;
                $_POST['enabled_new'] = 1;
            }
            break;

        case(2):
            if ($_POST['items_new'] < 1) {
                $_POST['enabled_new'] = 0;
                $_POST['sklad_new'] = 0;
            } else {
                $_POST['enabled_new'] = 1;
                $_POST['sklad_new'] = 0;
            }
            break;

        default:
            break;
    }


    $_POST['datas_new'] = date('U');

    $_POST['yml_bid_array_new'] = serialize($_POST['yml_bid_array']);

    // ��������������
    $_POST['vendor_new'] = null;
    if (is_array($_POST['vendor_array_new']))
        foreach ($_POST['vendor_array_new'] as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $key => $p) {
                    $_POST['vendor_new'].="i" . $k . "-" . $p . "i";
                    if (empty($p))
                        unset($_POST['vendor_array_new'][$k][$key]);
                }
            }
            else
                $_POST['vendor_new'].="i" . $k . "-" . $v . "i";
        }
    $_POST['vendor_array_new'] = serialize($_POST['vendor_array_new']);

    // ������
    $_POST['page_new'] = null;
    $cnt=0;
    if (is_array($_POST['page'])){
        foreach ($_POST['page'] as $value) {
        	if ($cnt==0) {
        		$_POST['page_new'].=$value;
        	} else $_POST['page_new'].=",".$value;
        	$cnt++;
        }
    } else $_POST['page_new'] = " "; 

    // �����
    $_POST['files_new'] = serialize($_POST['filenum']);

    // �������� ��� ��������� default
    if (isset($_POST['EditorContent1']))
        $_POST['description_new'] = $_POST['EditorContent1'];
    if (isset($_POST['EditorContent2']))
        $_POST['content_new'] = $_POST['EditorContent2'];

    //�������� ������ ������� ��� ������������ �����
    if (empty($_POST['outdated_new']) || $_POST['outdated_new']==0 || empty($_POST['analog_new'])) {
       $_POST['outdated_new']=0;
       $_POST['analog_new']='';
    }
        
    // �������� ������
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $_POST);

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);

    // ������������� ������ ��������
    $PHPShopOrm->updateZeroVars('newtip_new', 'enabled_new', 'spec_new', 'yml_new', 'sklad_new');

    //$PHPShopOrm->debug = true;
    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['productID']));
    
    //��������� ������� ��������� ��� ������� ������
    $product_promocode_cnt = $PHPShopOrm->query('select count(id) cnt from '.$GLOBALS['SysValue']['base']['products'].' prod join phpshop_product_promo_relation ppr on prod.id=ppr.product_id and prod.id='.$_POST['productID']);
    $product_promocode_cnt_row = mysql_fetch_assoc($product_promocode_cnt);
    //ob_start();
    //echo 'select count(id) cnt from '.$GLOBALS['SysValue']['base']['products'].' prod join phpshop_product_promo_relation ppr on prod.id=ppr.product_id and prod.id='.$_POST['productID'];
    //echo '--'.$product_promocode_cnt_row['cnt'].'--';   
    /* PERFORM COMLEX QUERY, ECHO RESULTS, ETC. */
    //$page = ob_get_contents();
    //ob_end_clean();
    //$file = "db_promocode_cnt.log";
    //$fw = fopen($file, "w+");
    //fputs($fw,$page, strlen($page));
    //fclose($fw);
    //$product_promocode_cnt_row = mysql_fetch_array($product_promocode_cnt);
    //echo $product_promocode_cnt_row[0]['cnt'];
    //����� ������ � ������� phpshop_product_promo_relation,phpshop_promocode 
    //���� ��� ������ ��������� ��� ������
    //� ���� �������� �� ������
    if ( $product_promocode_cnt_row['cnt']==0 && !empty($_POST['promocode_new']) && !empty($_POST['discountprice_new']) ){
        //START TRANSACTION;
        $PHPShopOrm->query('insert into phpshop_promocode(promocode,discountprice) values(\''.$_POST['promocode_new'].'\','.$_POST['discountprice_new'].')');
        $product_promocode_id = $PHPShopOrm->query('select max(id) id from phpshop_promocode where promocode=\''.$_POST['promocode_new'].'\'');
        $product_promocode_id_row = mysql_fetch_assoc($product_promocode_id);
        if ($product_promocode_id_row['id']<>0){
            $PHPShopOrm->query('insert into phpshop_product_promo_relation(product_id,promo_id) values('.$_POST['productID'].','.$product_promocode_id_row['id'].')');
        }
        //ob_start();
        //echo 'select max(id) id from phpshop_promocode where promocode=\''.$_POST['promocode_new'].'\'';
        //echo '--'.$product_promocode_id['id'].'--';
        /* PERFORM COMLEX QUERY, ECHO RESULTS, ETC. */
        //$page = ob_get_contents();
        //ob_end_clean();
        //$file = "db_promocode_insert.log";
        //$fw = fopen($file, "w+");
        //fputs($fw,$page, strlen($page));
        //fclose($fw);
    } else if ($product_promocode_cnt_row['cnt']>0){

        //�������� ����� id ������ � ����������
        $datapromo = $PHPShopOrm->query('select promo_id from phpshop_product_promo_relation where product_id='.intval($_REQUEST['productID']));
        $datapromo_row = mysql_fetch_assoc($datapromo);
        //���� ������ ������ �������� � �������� ����� ������� ������
        if ( $datapromo_row['promo_id']>0 && (empty($_POST['promocode_new']) || empty($_POST['discountprice_new'])) ) {
            $PHPShopOrm->query('delete from phpshop_promocode where id = '.intval($datapromo_row['promo_id']));
            $PHPShopOrm->query('delete from phpshop_product_promo_relation where promo_id = '.intval($datapromo_row['promo_id']).' and product_id='.intval($_REQUEST['productID'])); 
        } else if ( $datapromo_row['promo_id']>0 && !empty($_POST['promocode_new']) && !empty($_POST['discountprice_new']) ) {
            $PHPShopOrm->query('update phpshop_promocode set promocode = \''.$_POST['promocode_new'].'\',discountprice='.$_POST['discountprice_new'].' where id='.intval($datapromo_row['promo_id']));
        //ob_start();
        //echo 'update phpshop_promocode set promocode = \''.$_POST['promocode_new'].'\',discountprice='.$_POST['discountprice_new'].' where id='.intval($datapromo_row['promo_id']);
        //echo '--'.$datapromo_row['promo_id'].'--';
        /* PERFORM COMLEX QUERY, ECHO RESULTS, ETC. */
        //$page = ob_get_contents();
        //ob_end_clean();
        //$file = "db_promocode_update.log";
        //$fw = fopen($file, "w+");
        //fputs($fw,$page, strlen($page));
        //fclose($fw);
        }
    }
    $PHPShopOrm->clean();

    return $action;
}

// �������� �����������
function fotoDelete() {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['foto']);
    $data = $PHPShopOrm->select(array('*'), array('parent' => '=' . intval($_POST['productID'])), false, array('limit' => 100));
    if (is_array($data)) {
        foreach ($data as $row) {
            $name = $row['name'];
            $pathinfo = pathinfo($name);
            $oldWD = getcwd();
            $dirWhereRenameeIs = $_SERVER['DOCUMENT_ROOT'] . $pathinfo['dirname'];
            $oldFilename = $pathinfo['basename'];

            @chdir($dirWhereRenameeIs);
            @unlink($oldFilename);
            $oldFilename_s = str_replace(".", "s.", $oldFilename);
            @unlink($oldFilename_s);
            @chdir($oldWD);
        }
        $PHPShopOrm->clean();
        $PHPShopOrm->delete(array('id' => '=' . intval($_POST['productID'])));
    }
}

// ������� ��������
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    // �������� ������
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $_POST);

    $action = $PHPShopOrm->delete(array('id' => '=' . intval($_POST['productID'])));
    
    // �������� �����������
    if($action)
        fotoDelete();
    
    return $action;
}

//������ ���������������� ���� ������������� ������ �������� ������ ��� yandex market � �������
function read_yandex_opt_config($filename){
	if (file_exists($filename)) {
		$filter_file=fopen($filename,'r');
		while (!feof($filter_file)) {
			$fitler_element=explode("|",str_replace("\n",'',fgets($filter_file)));
			if ($fitler_element[2]=='"1"') {
				$my_value=str_replace('"','',$fitler_element[2]);
				$my_name=str_replace('"','',$fitler_element[1]);
				$filter_array=array('name'=>$my_name,'value'=>$my_value);
			}	
		}
		fclose($filter_file);
	}
	if (!is_array_empty($filter_array)) {
		return $filter_array;
	} else {
		return 0;
	}			
}

function is_array_empty($a) {
	if(count($a) > 0) {
		return false;
	} else {
		return true;
	}
}


// ����� ����� ��� ������
$PHPShopGUI->setAction($_GET['productID'], 'actionStart', 'none');

// ��������� �������
$PHPShopGUI->getAction();
?>