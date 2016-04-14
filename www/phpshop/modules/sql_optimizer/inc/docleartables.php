<?php
$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");

$PHPShopOrm=new PHPShopOrm('information_schema.tables');
$PHPShopOrm->debug=false;
$tbl_count_ret1=$PHPShopOrm->select(array('table_name'), array('table_rows'=>'>1000','table_schema'=>'=\''.$SysValue['connect']['dbase'].'\'','table_name'=>'=\'temporary_log\''), false,array('limit'=>1));

$tbl_count_ret2=$PHPShopOrm->select(array('table_name'), array('table_rows'=>'>1000','table_schema'=>'=\''.$SysValue['connect']['dbase'].'\'','table_name'=>'=\'phpshop_modules_stat_visitors\''), false,array('limit'=>1));

// SQL
if ($tbl_count_ret1['table_name']!='')
$PHPShopOrm->query('delete from '.$PHPShopBase->getParam('connect.dbase').'.temporary_log');

if ($tbl_count_ret2['table_name']!='')
$PHPShopOrm->query('delete from '.$PHPShopBase->getParam('connect.dbase').'.phpshop_modules_stat_visitors');

//echo 'delete from '.$PHPShopBase->getParam('connect.dbase').'.temporary_log';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$mas['rv']=1;
$mas['trdd1']='test1';
$mas['trdd2']='test2';
echo json_encode($mas);