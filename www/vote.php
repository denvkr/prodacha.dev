<?php
/*
	include("phpshop/class/obj.class.php");
	PHPShopObj::loadClass("base");
	PHPShopObj::loadClass("system");
	$PHPShopBase = new PHPShopBase("phpshop/inc/config.ini",false);
*/
	//require_once $_SERVER['DOCUMENT_ROOT'] ."/phpshop/lib/Subsys/JsHttpRequest/JsHttpRequest.php";
	require_once $_SERVER['DOCUMENT_ROOT'] ."/phpshop/lib/Subsys/JsHttpRequest/Php.php";
	include $_SERVER['DOCUMENT_ROOT'] ."/voting_class.php";

	ini_set('display_errors', true);

	/*Скрипт обработки голосования*/

	$JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");

	$SysValue = parse_ini_file('phpshop/inc/config.ini', 1);
	
	$host=$SysValue['connect']['host'];
	$user_db=$SysValue['connect']['user_db'];
	$pass_db=$SysValue['connect']['pass_db'];
	$dbase=$SysValue['connect']['dbase'];

/*
	$host="u301639.mysql.masterhost.ru";          
	$user_db="u301639_test";           
	$pass_db="-o97iCiAlLop.";       
	$dbase="u301639_test";
*/
	//echo $host.'<br>'.$user_db.'<br>'.$pass_db.'<br>'.$dbase.'<br>'.$q1_yes.'<br>'.$q1_no;
	$myvoting_class=new voting_class($host,$dbase,$user_db,$pass_db);	
	
	if ($_REQUEST['only_read']==1) {
		$q1_page=$_REQUEST['q1_page'];
		$my_vot_arr=$myvoting_class->get_question_data($q1_page);
		if (isset($my_vot_arr)) {
			reset($my_vot_arr);
			foreach($my_vot_arr AS $key=>$myItem) {
				if ($key=='VOTE_NUMBER_YES') {
					$outarr=array('answers_yes' => '('.$myItem.')');
				}
				if ($key=='VOTE_NUMBER_NO') {
					$outarr['answers_no']='('.$myItem.')';
				}
			}
		}
		$_RESULT=$outarr;
	} else if ($_REQUEST['only_read']==0){
		// Fetch request parameters.
		$q1_yes = $_REQUEST['q1_yes'];
		$q1_no = $_REQUEST['q1_no'];
		$q1_page=$_REQUEST['q1_page'];
		$q1_session=$_REQUEST['q1_session'];
		$session_end_time=$_REQUEST['session_end_time'];

		if ($q1_yes=='true') {
				//echo $myvoting_class->get_voted_session_status($q1_page,$q1_session,$session_end_time);
				if ($myvoting_class->get_voted_session_status($q1_page,$q1_session,$session_end_time)==0){
					$my_vot_arr=$myvoting_class->add_question_data($q1_page,1,0);
					$myvoting_class->alter_voted_session_status($q1_page,$q1_session,$session_end_time);
				}
		}
		if ($q1_no=='true') {
				//echo $myvoting_class->get_voted_session_status($q1_page,$q1_session,$session_end_time);
				if ($myvoting_class->get_voted_session_status($q1_page,$q1_session,$session_end_time)==0){
					$my_vot_arr=$myvoting_class->add_question_data($q1_page,0,1);
					$myvoting_class->alter_voted_session_status($q1_page,$q1_session,$session_end_time);
				}
		}
		
		$my_vot_arr=$myvoting_class->get_question_data($q1_page);

		if (isset($my_vot_arr)) {
			reset($my_vot_arr);
			foreach($my_vot_arr AS $key=>$myItem) {
				if ($key=='VOTE_NUMBER_YES') {
					$outarr=array('answers_yes' => '('.$myItem.')');
				}
				if ($key=='VOTE_NUMBER_NO') {
					$outarr['answers_no']='('.$myItem.')';
				}
			}
		}
		$_RESULT=$outarr;

		// This includes a PHP fatal error! It will go to the debug stream,
		// frontend may intercept this and act a reaction.
		/*
		if ($_REQUEST['q1_session'] == 'error') {
		  error_demonstration__make_a_mistake_calling_undefined_function();
		}
		*/
	}
?>