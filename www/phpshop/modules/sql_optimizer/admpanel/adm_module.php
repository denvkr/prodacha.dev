<?php
$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");


// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->reload='none';

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.sql_optimizer.sqloptimizer_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    if(empty($_POST['enabled_new'])) $_POST['enabled_new']=0;
    $action = $PHPShopOrm->update($_POST);
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Настройка модуля";
    $PHPShopGUI->size="500,450";


    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);


    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'sqloptimizer'","Настройки",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");
    //выбираем нужную информацию из БД
    //$tbl_count_ret=$PHPShopOrm->query('select table_name,table_rows from information_schema.tables where table_rows>1000 order by table_rows DESC');
    $PHPShopOrm=new PHPShopOrm('information_schema.tables');
    $PHPShopOrm->debug=false;
    $tbl_count_ret=$PHPShopOrm->select(array('table_name','table_rows'), array('table_rows'=>'>100','table_schema'=>'=\''.$SysValue['connect']['dbase'].'\''), array('order'=>'table_rows DESC'),array('limit'=>10));
    //array('id'=>'=10'),array('order'=>'id DESC'),array('limit'=>1)
    //$tbl_count=mysql_fetch_assoc($tbl_count_ret);
    $tbl_count_col_head='Таблица<br>';
    //var_dump($tbl_count_ret);
    foreach($tbl_count_ret as $tbl_count_item){ 
        $tbl_count_col_data.= $tbl_count_item['table_name'].'<br>';
    }
    $tbl_count_col_head2='Кол-во строк<br>';
    //var_dump($tbl_count_ret);
    foreach($tbl_count_ret as $tbl_count_item){ 
        $tbl_count_col_data2.= $tbl_count_item['table_rows'].'<br>';
    }
        //Кол-во строк.'   '.$tbl_count_item['table_rows']
        //echo $tbl_count['table_name'];
    $Tab1=$PHPShopGUI->setDiv('left', $tbl_count_col_head.$tbl_count_col_data,'width:200px;height:70px;font-size:12px;display:inline-block;','table_rows1','table_rows_div1').
          $PHPShopGUI->setDiv('left', $tbl_count_col_head2.$tbl_count_col_data2,'width:100px;height:70px;font-size:12px;display:inline-block;','table_rows2','table_rows_div2').
          $PHPShopGUI->setButton('Очистить', '', '60', 20, "none", "javascript:DoClearTables();");// setCheckbox("enabled_new",1,'Откат изменений',$enabled);


    // Содержание закладки 2
    $Tab2=$PHPShopGUI->setPay($serial);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,270),array("О Модуле",$Tab2,270));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            $PHPShopGUI->setInput("button","","Отмена","right",70,"return onCancel();","but").
            $PHPShopGUI->setInput("submit","editID","ОК","right",70,"","but","actionUpdate");

    $PHPShopGUI->setFooter($ContentFooter);
    //$PHPShopGUI->includeJava=$_classPath.'modules/sql_optimizer/inc/jquery-1.11.0.min.js';
    //$PHPShopGUI->includeJava=$_classPath.'modules/sql_optimizer/inc/url_mod.js';
    $PHPShopGUI->addJSFiles($_classPath.'modules/sql_optimizer/inc/jquery-1.11.0.min.js');
    $PHPShopGUI->addJSFiles($_classPath.'modules/sql_optimizer/inc/url_mod.js');
    return true;
}

if($UserChek->statusPHPSHOP < 2) {

    // Вывод формы при старте
    $PHPShopGUI->setLoader($_POST['editID'],'actionStart');

    // Обработка событий
    $PHPShopGUI->getAction();

} else $UserChek->BadUserFormaWindow();

?>


