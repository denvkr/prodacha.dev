<div class="page_nava">
  <div> @breadCrumbs@ </div>
</div>
	<div class="pagetitle">
	<h1>@php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/seotools/seotools.class.php');
$ST = new Seotools; 

// Вывод метки, где 
// $variable = название переменной (столбца). 
// $default_value = значение по умолчанию


$oldh1="@pageTitle@";

echo $ST->get("h1", $oldh1); 

php@</h1>
					
				</div>


<p>@catContent@</p>

<div style="padding:5px 0px" class="page_content" >@pageContent@</div>
@php


	include($_SERVER['DOCUMENT_ROOT'].'/voting_class.php');

	$host=$GLOBALS['SysValue']['connect']['host'];          
	$user_db=$GLOBALS['SysValue']['connect']['user_db'];           
	$pass_db=$GLOBALS['SysValue']['connect']['pass_db'];       
	$dbase=$GLOBALS['SysValue']['connect']['dbase']; 

	$session_start_time=date('Y-m-d H:i:s');
	$session_end_time=date('Y-m-d H:i:s',mktime(0,0,0,date("m"),date("d")+1, date("Y")));
	//echo 'твоя сессия:'.$_COOKIE['PHPSESSID'].' начало сессии '.$session_start_time.' окончание сессии '.$session_end_time.curPageURL().'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$myvoting_class=new voting_class($host,$dbase,$user_db,$pass_db);
	if (!empty($_COOKIE['PHPSESSID'])) {
		if ($myvoting_class->get_session_data($_COOKIE['PHPSESSID'],$session_end_time)==0)  {
			$retval=$myvoting_class->add_session_data($_COOKIE['PHPSESSID'],$session_start_time,$session_end_time);
		} else if ($myvoting_class->get_session_data($_COOKIE['PHPSESSID'],$session_end_time)==-1) {
			exit;
		}
	}
	if (curPageURL()=='http://prodacha.ru/page/china_engines.html' ||
		curPageURL()=='http://prodacha.ru/page/motoblok_ili_motokultivator.html' || 
		curPageURL()=='http://prodacha.ru/page/kak_vybrat_motoblok.html' ||
		curPageURL()=='http://prodacha.ru/page/kak_vybrat_motokultivator.html' ||
		curPageURL()=='http://prodacha.ru/page/frezy_dlya_motobloka.html' ||
		curPageURL()=='http://prodacha.ru/page/kak_vybrat_motoblok_po_funkcionalu.html' ||
		curPageURL()=='http://test.prodacha.ru/page/china_engines.html' ||
		curPageURL()=='http://test.prodacha.ru/page/motoblok_ili_motokultivator.html' || 
		curPageURL()=='http://test.prodacha.ru/page/kak_vybrat_motoblok.html' ||
		curPageURL()=='http://test.prodacha.ru/page/kak_vybrat_motokultivator.html' ||
		curPageURL()=='http://test.prodacha.ru/page/frezy_dlya_motobloka.html' ||
		curPageURL()=='http://test.prodacha.ru/page/kak_vybrat_motoblok_po_funkcionalu.html') {
		
		$dbcnx=mysql_connect($host,$user_db,$pass_db);
		
		if (!$dbcnx) 
		{
		  echo("<p> error MySql </p>");
		   exit;
		}
														
		if (!@mysql_select_db($dbase,$dbcnx)) 
		{
		   echo("<p> error DB MySql </p>");	
		   exit;
		}
		
		$outstr='<div id="Main_vote1" class="ul.hide_button" style="display:block;position: relative;right:27%">';
		$outstr.='<b></b>';
		$outstr.='<form method="post" enctype="multipart/form-data" onsubmit="return false">';
		$outstr.='<table border = "0.5" style="width:550px">';
		$outstr.='<tr>';
		$outstr.='<td>';
		$outstr.='<div align="center" style="display:block"><br />';
		$outstr.='<b>Понравилась статья?</b><br />';
		$outstr.='<input type="radio" name="question1" id="question1_yes" value="Yes" checked>Да<span>&nbsp;&nbsp;</span><b id="question1_answer_yes"></b><span>&nbsp;&nbsp;</span>';
		$outstr.='<input type="radio" name="question1" id="question1_no" value="No">Нет<span>&nbsp;&nbsp;</span><b id="question1_answer_no"></b><br /><br />';
		$outstr.='<input type="button" name="_Vote" onclick="vote_set('."'".$_COOKIE['PHPSESSID']."','".$session_end_time."'".')" value="Голосовать">';
		$outstr.='</div>';
		$outstr.='</td>';
		$outstr.='</tr>';
		$outstr.='</table>';
		$outstr.='</form>';
		$outstr.='</div>';
		echo $outstr;
	}
	
	function curPageURL() {
		 $pageURL = 'http';
		 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
		 return $pageURL;
	}
	
php@

<!-- <script src="phpshop/lib/JsHttpRequest/JsHttpRequest.js"></script> -->
<script language="JavaScript">
    // функция проверяет голосование в текущей сессии
    function vote_set(session_id,session_end_time) {
		var req = new Subsys_JsHttpRequest_Js();
		
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if (req.responseJS) {
					//document.getElementById("debug").innerHTML = (req.responseText.errors||''); 
					document.getElementById("question1_answer_yes").innerHTML=(req.responseJS.answers_yes||'');
					document.getElementById("question1_answer_no").innerHTML=(req.responseJS.answers_no||'');
				}
			}
		}
		req.caching = false;
		// Подготваливаем объект.
		//alert(window.location.hostname);
		req.open('POST','../../../vote.php', true);
		req.send({
            // статья нравиться
            q1_yes: document.getElementById("question1_yes").checked,  
            // статья не нравится
            q1_no: document.getElementById("question1_no").checked,
			// url статьи
			q1_page: document.URL,
			//session_id
			q1_session: session_id,
			//session_end_time
			session_end_time: session_end_time,
			//только чтение
			only_read: 0			
		});
    }
</script>
<div class="page_nav_bot">@pageNav@</div>
