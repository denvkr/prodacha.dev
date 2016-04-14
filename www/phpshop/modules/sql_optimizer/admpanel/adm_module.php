<?php
$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");


// ��������� ������
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");


// ��������
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->reload='none';

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.sql_optimizer.sqloptimizer_system"));

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;
    if(empty($_POST['enabled_new'])) $_POST['enabled_new']=0;
    $action = $PHPShopOrm->update($_POST);
    return $action;
}

// ��������� ������� ��������
function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="��������� ������";
    $PHPShopGUI->size="500,450";


    // �������
    $data = $PHPShopOrm->select();
    @extract($data);


    // ����������� ��������� ����
    $PHPShopGUI->setHeader("��������� ������ 'sqloptimizer'","���������",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");
    //�������� ������ ���������� �� ��
    //$tbl_count_ret=$PHPShopOrm->query('select table_name,table_rows from information_schema.tables where table_rows>1000 order by table_rows DESC');
    $PHPShopOrm=new PHPShopOrm('information_schema.tables');
    $PHPShopOrm->debug=false;
    $tbl_count_ret=$PHPShopOrm->select(array('table_name','table_rows'), array('table_rows'=>'>100','table_schema'=>'=\''.$SysValue['connect']['dbase'].'\''), array('order'=>'table_rows DESC'),array('limit'=>10));
    //array('id'=>'=10'),array('order'=>'id DESC'),array('limit'=>1)
    //$tbl_count=mysql_fetch_assoc($tbl_count_ret);
    $tbl_count_col_head='�������<br>';
    //var_dump($tbl_count_ret);
    foreach($tbl_count_ret as $tbl_count_item){ 
        $tbl_count_col_data.= $tbl_count_item['table_name'].'<br>';
    }
    $tbl_count_col_head2='���-�� �����<br>';
    //var_dump($tbl_count_ret);
    foreach($tbl_count_ret as $tbl_count_item){ 
        $tbl_count_col_data2.= $tbl_count_item['table_rows'].'<br>';
    }
        //���-�� �����.'   '.$tbl_count_item['table_rows']
        //echo $tbl_count['table_name'];
    $Tab1=$PHPShopGUI->setDiv('left', $tbl_count_col_head.$tbl_count_col_data,'width:200px;height:70px;font-size:12px;display:inline-block;','table_rows1','table_rows_div1').
          $PHPShopGUI->setDiv('left', $tbl_count_col_head2.$tbl_count_col_data2,'width:100px;height:70px;font-size:12px;display:inline-block;','table_rows2','table_rows_div2').
          $PHPShopGUI->setButton('��������', '', '60', 20, "none", "javascript:DoClearTables();");// setCheckbox("enabled_new",1,'����� ���������',$enabled);


    // ���������� �������� 2
    $Tab2=$PHPShopGUI->setPay($serial);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������",$Tab1,270),array("� ������",$Tab2,270));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            $PHPShopGUI->setInput("button","","������","right",70,"return onCancel();","but").
            $PHPShopGUI->setInput("submit","editID","��","right",70,"","but","actionUpdate");

    $PHPShopGUI->setFooter($ContentFooter);
    //$PHPShopGUI->includeJava=$_classPath.'modules/sql_optimizer/inc/jquery-1.11.0.min.js';
    //$PHPShopGUI->includeJava=$_classPath.'modules/sql_optimizer/inc/url_mod.js';
    $PHPShopGUI->addJSFiles($_classPath.'modules/sql_optimizer/inc/jquery-1.11.0.min.js');
    $PHPShopGUI->addJSFiles($_classPath.'modules/sql_optimizer/inc/url_mod.js');
    return true;
}

if($UserChek->statusPHPSHOP < 2) {

    // ����� ����� ��� ������
    $PHPShopGUI->setLoader($_POST['editID'],'actionStart');

    // ��������� �������
    $PHPShopGUI->getAction();

} else $UserChek->BadUserFormaWindow();

?>


