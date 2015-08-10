<?php
ini_set('display_errors', true);
/*Скрипт обработки голосования*/
//echo $_POST['question1'].'<br>';
//echo $_POST['question2'].'<br>';
//print_r($_POST);
//Класс для проведения голования по указанным вопросам и размещения результата голосования на страницах сайта.
//require "database_connection_base_class.php";
//************************************************************************************************************************************
class voting_class {
   public $rows_array;//=array();
   public $dbhost;//= 'xyz';
   public $dbname;//= 'xyz';
   public $dbuser;//= 'xyz';
   public $dbpass;//= 'xyz';
   public $conn;   
   //************************************************************************************************************************************
function __construct($dbhost,$dbname,$dbuser,$dbpass) {
	try {
	    $this->dbhost=$dbhost;
	    $this->dbuser=$dbuser;
	    $this->dbpass=$dbpass;
	    $this->dbname=$dbname;
	} catch (Exception $e) {
    	echo $this->WinToUtf8('Ошибка в классе: voting_class метод: __construct($dbhost,$dbname,$dbuser,$dbpass) исключение: '),  $e->getMessage(), "\n";
	}
}
//************************************************************************************************************************************
function __destruct() {
	try {
		unset($this->dbhost);
	    unset($this->dbuser);
	    unset($this->dbpass);
	    unset($this->dbname);
		mysqli_close($this->conn);
	} catch (Exception $e) {
    	echo $this->WinToUtf8('Ошибка в классе: voting_class метод: __destruct() исключение: '),  $e->getMessage(), "\n";
	}
}

//************************************************************************************************************************************
function set_dbhost($dbhost) {
    $this->dbhost=$dbhost;
return 1;
}
//************************************************************************************************************************************
function set_user($dbuser) {
    $this->dbuser=$dbuser;
return 1;
}
//************************************************************************************************************************************
function set_dbpass($dbpass) {
	$this->dbpass=$dbpass;
return 1;
}
//************************************************************************************************************************************
function get_dbhost($dbhost) {
return $this->dbhost;
}
//************************************************************************************************************************************
function get_user($dbuser) {
return $this->dbuser;
}
//************************************************************************************************************************************
function get_dbpass($dbpass) {
return $this->dbpass;
}
function db_connect(){
    $this->conn=mysqli_connect($this->dbhost,$this->dbuser,$this->dbpass);
    if (isset($this->conn)) {
        $ret=mysqli_select_db ($this->conn,$this->dbname);
        //print "Database ".$this->dbname." was selected <br>";
        //print $ret."<br>";
   } else {
        die('Could not connect in fucntion db_connect: ' . mysqli_connect_error());
    }
   return $this->conn;
}
//************************************************************************************************************************************
function db_close(){
mysql_close($this->conn);
}
//возвращаемые значение 0-если сессия уже проголосовала
//						1-если сессия проголосовала сейчас
//						-1-если что то пошло не так
function alter_voted_session_status($QUESTION,$SESSION_ID,$SESSION_END_TIME){
	try {
			$this->db_connect();
		    mysqli_query($this->conn,"set names utf8;");
			mysqli_query($this->conn,"SET @RETVAL = 0;");
			mysqli_query($this->conn,"SET SQL_SAFE_UPDATES=0;");
			//проверяем есть ли данная сессия в таблице
			mysqli_query($this->conn,"SET @RETVAL=(SELECT COUNT(SESSION_ID) FROM ".$this->dbname.".session WHERE SESSION_ID='".$SESSION_ID."');");
			$result = mysqli_query($this->conn,'SELECT @RETVAL');
		    $row = mysqli_fetch_array($result);
			//если сессия имеется то
		    if (intval($row[0])==1){
				mysqli_free_result($result);
				//проверяем просрочена ли она
				mysqli_query($this->conn,"SET @RETVAL=(SELECT SESSION_END_TIME FROM ".$this->dbname.".session WHERE SESSION_ID='".$SESSION_ID."');");
				$result = mysqli_query($this->conn,'SELECT @RETVAL');
		    	$row = mysqli_fetch_array($result);
				//если не просрочена тогда пробуем оценить сколько ей осталось жить
		    	if ($row[0]!='') {
					mysqli_free_result($result);
					mysqli_query($this->conn,"SET @RETVAL = 0;");					
					mysqli_query($this->conn,"SET @RETVAL=(select DATEDIFF(SESSION_END_TIME,'".$SESSION_END_TIME."') FROM ".$this->dbname.".session WHERE SESSION_ID='".$SESSION_ID."');");
					$result = mysqli_query($this->conn,'SELECT @RETVAL');
					$row = mysqli_fetch_array($result);
					//если время существования сессии не истекло тогда обновляем поле VOTED
					if (intval($row[0])==0 || intval($row[0])==-1) {
						//проверяем голосовала ли уже данная сессия
						mysqli_free_result($result);
						if ($QUESTION=='http://test.prodacha.ru/page/china_engines.html' || $QUESTION=='http://prodacha.ru/page/china_engines.html') {
							  $vote='VOTED1';
						}
						 if ($QUESTION=='http://test.prodacha.ru/page/motoblok_ili_motokultivator.html' || $QUESTION=='http://prodacha.ru/page/motoblok_ili_motokultivator.html'){
							  $vote='VOTED2';
						}
						 if ($QUESTION=='http://test.prodacha.ru/page/kak_vybrat_motoblok.html'  || $QUESTION=='http://prodacha.ru/page/kak_vybrat_motoblok.html'){
							  $vote='VOTED3';
						}
						 if ($QUESTION=='http://test.prodacha.ru/page/kak_vybrat_motokultivator.html' || $QUESTION=='http://prodacha.ru/page/kak_vybrat_motokultivator.html'){
							  $vote='VOTED4';
						}
						 if ($QUESTION=='http://test.prodacha.ru/page/frezy_dlya_motobloka.html' || $QUESTION=='http://prodacha.ru/page/frezy_dlya_motobloka.html'){
							  $vote='VOTED5';
						}
						 if ($QUESTION=='http://test.prodacha.ru/page/kak_vybrat_motoblok_po_funkcionalu.html' || $QUESTION=='http://prodacha.ru/page/kak_vybrat_motoblok_po_funkcionalu.html'){
							  $vote='VOTED6';
						}
						mysqli_query($this->conn,"SET @RETVAL = 0;");					
						mysqli_query($this->conn,"SET @RETVAL=(SELECT ".$vote." FROM ".$this->dbname.".session WHERE SESSION_ID='".$SESSION_ID."');");    	
						$result = mysqli_query($this->conn,'SELECT @RETVAL');
						$row = mysqli_fetch_array($result);
						if (intval($row[0])==0) {
							mysqli_query($this->conn,"UPDATE ".$this->dbname.".session SET ".$vote."=1 WHERE SESSION_ID='".$SESSION_ID."';");
							$row[0]='1';
						}	else {
							$row[0]='0';
						}					
					}
				}
			}
			//проверяем на всякий случай, если после получения session_id значение $row[0] не обнулялось тогда необходимо признать результат равным 0
			if (intval($row[0])!=0 && intval($row[0])!=1){
				$row[0]='-1';
			}

			if (!$this->is_array_empty($row)){
				return intval($row[0]);
			} else {
				return 0;
			}	
		    $mysqli->close();
		} catch (Exception $e) {
	    	echo $this->Utf8ToWin('Ошибка в классе: voting_class метод: alter_voted_session_status() исключение: '),  $e->getMessage(), "\n";
	}
}
//функция возвращает 1-если сессия уже голосовала
//					 0-если сессия еще не голосовала
//					 -1-если что то пошло не так, сессия удалена из за просрочки или ...
function get_voted_session_status($QUESTION,$SESSION_ID,$SESSION_END_TIME){
	try {
			$this->db_connect();
		    mysqli_query($this->conn,"set names utf8;");
			mysqli_query($this->conn,"SET @RETVAL = 0;");
			mysqli_query($this->conn,"SET SQL_SAFE_UPDATES=0;");
			//проверяем есть ли данная сессия в таблице
			mysqli_query($this->conn,"SET @RETVAL=(SELECT COUNT(SESSION_ID) FROM ".$this->dbname.".session WHERE SESSION_ID='".$SESSION_ID."');");
			$result = mysqli_query($this->conn,'SELECT @RETVAL');
		    $row = mysqli_fetch_array($result);
			//если сессия имеется то
		    if (intval($row[0])==1){
				mysqli_free_result($result);
				//проверяем просрочена ли она
				mysqli_query($this->conn,"SET @RETVAL=(SELECT SESSION_END_TIME FROM ".$this->dbname.".session WHERE SESSION_ID='".$SESSION_ID."');");
				$result = mysqli_query($this->conn,'SELECT @RETVAL');
		    	$row = mysqli_fetch_array($result);
				//если не просрочена тогда пробуем оценить сколько ей осталось жить
		    	if ($row[0]!='') {
					mysqli_free_result($result);
					mysqli_query($this->conn,"SET @RETVAL = 0;");					
					mysqli_query($this->conn,"SET @RETVAL=(SELECT DATEDIFF(SESSION_END_TIME,'".$SESSION_END_TIME."') FROM ".$this->dbname.".session WHERE SESSION_ID='".$SESSION_ID."');");    	
					$result = mysqli_query($this->conn,'SELECT @RETVAL');
					$row = mysqli_fetch_array($result);
					//если время существования сессии не истекло тогда получаем поле VOTED
					if (intval($row[0])==0 || intval($row[0])==-1) {
						//проверяем голосовала ли уже данная сессия
						mysqli_free_result($result);
						if ($QUESTION=='http://test.prodacha.ru/page/china_engines.html' || $QUESTION=='http://prodacha.ru/page/china_engines.html') {
							  $vote='VOTED1';
						}
						 if ($QUESTION=='http://test.prodacha.ru/page/motoblok_ili_motokultivator.html' || $QUESTION=='http://prodacha.ru/page/motoblok_ili_motokultivator.html'){
							  $vote='VOTED2';
						}
						 if ($QUESTION=='http://test.prodacha.ru/page/kak_vybrat_motoblok.html'  || $QUESTION=='http://prodacha.ru/page/kak_vybrat_motoblok.html'){
							  $vote='VOTED3';
						}
						 if ($QUESTION=='http://test.prodacha.ru/page/kak_vybrat_motokultivator.html' || $QUESTION=='http://prodacha.ru/page/kak_vybrat_motokultivator.html'){
							  $vote='VOTED4';
						}
						 if ($QUESTION=='http://test.prodacha.ru/page/frezy_dlya_motobloka.html' || $QUESTION=='http://prodacha.ru/page/frezy_dlya_motobloka.html'){
							  $vote='VOTED5';
						}
						 if ($QUESTION=='http://test.prodacha.ru/page/kak_vybrat_motoblok_po_funkcionalu.html' || $QUESTION=='http://prodacha.ru/page/kak_vybrat_motoblok_po_funkcionalu.html'){
							  $vote='VOTED6';
						}
						mysqli_query($this->conn,"SET @RETVAL = 0;");					
						//echo "SET @RETVAL=(SELECT ".$vote." FROM ".$this->dbname.".session WHERE SESSION_ID='".$SESSION_ID."');";
						$result=mysqli_query($this->conn,"SET @RETVAL=(SELECT ".$vote." FROM ".$this->dbname.".session WHERE SESSION_ID='".$SESSION_ID."');");    	
						$result = mysqli_query($this->conn,'SELECT @RETVAL');
						$row = mysqli_fetch_array($result);
					} else {
						mysqli_free_result($result);
						mysqli_query($this->conn,"DELETE FROM ".$this->dbname.".session WHERE SESSION_ID='".$SESSION_ID."');");    	
						$row[0]='-1';
					}
				}
			}
			//проверяем на всякий случай, если после получения session_id значение $row[0] не обнулялось тогда необходимо признать результат равным 0
			if (intval($row[0])!=0 && intval($row[0])!=1){
				$row[0]='-1';
			}
			if (!$this->is_array_empty($row)){
				return intval($row[0]);
			} else {
				return 0;
			}			
		    $mysqli->close();
		} catch (Exception $e) {
	    	echo $this->Utf8ToWin('Ошибка в классе: voting_class метод: get_voted_session_status() исключение: '),  $e->getMessage(), "\n";
	}
}


function get_question_data($QUESTION){
		try {
	$this->db_connect();
    mysqli_query($this->conn,"set names utf8;");
	mysqli_query($this->conn,"SET @RETVAL = 0;");
	mysqli_query($this->conn,"SET @QUESTION = 0;");
	mysqli_query($this->conn,"SET @YES_VOTE = 0;");
	mysqli_query($this->conn,"SET @NO_VOTE = 0;");
	$result=mysqli_query($this->conn,"SELECT QUESTION, VOTE_NUMBER_YES, VOTE_NUMBER_NO FROM ".$this->dbname.".web_poll WHERE QUESTION='".$QUESTION."';");
    $row = mysqli_fetch_assoc($result);
	//print_r($row);
	if (!$this->is_array_empty($row)){
		return $row;
	} else {
		return 0;
	}
    $mysqli->close();
		} catch (Exception $e) {
    	echo $this->Utf8ToWin('Ошибка в классе: voting_class метод: get_question_data() исключение: '),  $e->getMessage(), "\n";
	}
}

function add_question_data($QUESTION,$YES_VOTE,$NO_VOTE){
		try {
	//echo 'Результаты голосования по вопросу:'.$QUESTION.'<br>';
	$this->db_connect();
    mysqli_query($this->conn,"set names utf8;");
	mysqli_query($this->conn,"SET @RETVAL=0;");
	mysqli_query($this->conn,"SET @YES_VOTE=$YES_VOTE;");
	mysqli_query($this->conn,"SET @NO_VOTE=$NO_VOTE;");
	mysqli_query($this->conn,"SET @QUESTION=$QUESTION;");
	mysqli_query($this->conn,"SET SQL_SAFE_UPDATES=0;");

 	
	if ($YES_VOTE==1 && $NO_VOTE==0){
		mysqli_query($this->conn,"SET @VOTE_NUMBER = (SELECT CASE WHEN VOTE_NUMBER_YES IS NULL THEN 0 ELSE VOTE_NUMBER_YES END FROM ".$this->dbname.".web_poll WHERE QUESTION='".$QUESTION."');");
	    mysqli_query($this->conn,"UPDATE ".$this->dbname.".web_poll SET VOTE_NUMBER_YES = (@VOTE_NUMBER + @YES_VOTE) WHERE QUESTION='".$QUESTION."';");
		mysqli_query($this->conn,"SET @RETVAL = 1;");
		//echo "UPDATE ".$this->dbname.".web_poll SET VOTE_NUMBER_YES = (@VOTE_NUMBER + @YES_VOTE) WHERE QUESTION='".$QUESTION."';";
		}
	if ($YES_VOTE==0 && $NO_VOTE==1){
		mysqli_query($this->conn,"SET @VOTE_NUMBER = (SELECT CASE WHEN VOTE_NUMBER_NO IS NULL THEN 0 ELSE VOTE_NUMBER_NO END FROM ".$this->dbname.".web_poll WHERE QUESTION='".$QUESTION."');");
	    mysqli_query($this->conn,"UPDATE ".$this->dbname.".web_poll SET VOTE_NUMBER_NO = (@VOTE_NUMBER + @NO_VOTE) WHERE QUESTION='".$QUESTION."';");
		mysqli_query($this->conn,"SET @RETVAL = 1;");
		//echo "UPDATE ".$this->dbname.".web_poll SET VOTE_NUMBER_NO = (@VOTE_NUMBER + @NO_VOTE) WHERE QUESTION='".$QUESTION."';";
	}
	$result = mysqli_query($this->conn,"SELECT QUESTION, VOTE_NUMBER_YES, VOTE_NUMBER_NO FROM ".$this->dbname.".web_poll WHERE QUESTION='".$QUESTION."';");
	
    $row = mysqli_fetch_assoc($result);
	//print_r($row);
	if (!$this->is_array_empty($row)){
		return $row;
	} else {
		return 0;
	}
    $mysqli->close();
		} catch (Exception $e) {
    	echo $this->WinToUtf8('Ошибка в классе: voting_class метод: add_question_data() исключение: '),  $e->getMessage(), "\n";
	}
}

function del_session_data(){
		try {
	//echo 'Результаты голосования по вопросу:'.$QUESTION.'<br>';
	$this->db_connect();
    mysqli_query($this->conn,"set names utf8;");
	mysqli_query($this->conn,"SET @RETVAL = 0;");
    mysqli_query($this->conn,"SET @RETVAL=(DELETE FROM ".$this->dbname.".session WHERE SESSION_END_TIME NOT IS NULL);");    	
	$result = mysqli_query($this->conn,'SELECT @RETVAL');
    $row = mysqli_fetch_array($result);
	//print_r($row);
	if ($this->conn->connect_errno){
		return 0;
	} else {
		return 1;
	}
    $mysqli->close();
		} catch (Exception $e) {
    	echo $this->Utf8ToWin('Ошибка в классе: voting_class метод: del_session_data() исключение: '),  $e->getMessage(), "\n";
	}
}
function add_session_data($SESSION_ID,$SESSION_START_TIME,$SESSION_END_TIME){
		try {
	//echo 'Результаты голосования по вопросу:'.$QUESTION_ID.'<br>';
	$this->db_connect();
    //mysqli_query($this->conn,"set names utf8;");
	mysqli_query($this->conn,"SET @RETVAL = 0;");
	mysqli_query($this->conn,"SET SQL_SAFE_UPDATES=0;");
    mysqli_query($this->conn,"INSERT INTO ".$this->dbname.".session(SESSION_ID,SESSION_START_TIME,SESSION_END_TIME) VALUES ('".$SESSION_ID."','".$SESSION_START_TIME."','".$SESSION_END_TIME."');");    	
    //echo "INSERT INTO ".$this->dbname.".session(session_id,session_start_time) VALUES ('".$SESSION_ID."','".$SESSION_TIME."');";
    mysqli_query($this->conn,"SET @RETVAL=(SELECT COUNT(SESSION_ID) FROM ".$this->dbname.".session WHERE SESSION_ID='".$SESSION_ID."' AND SESSION_START_TIME=STR_TO_DATE('".$SESSION_START_TIME."','%Y-%m-%d %H:%i:%s'));");    	
    $result = mysqli_query($this->conn,'SELECT @RETVAL');
    $row = mysqli_fetch_array($result);
	//print_r($row);
	if (!$this->is_array_empty($row)){
		return intval($row[0]);
	} else {
		return 0;
	}	
    $mysqli->close();
		} catch (Exception $e) {
    	echo $this->Utf8ToWin('Ошибка в классе: voting_class метод: add_session_data() исключение: '),  $e->getMessage(), "\n";
	}
}
//возвращаемые значение 0-если сессия новая или если сессия была просрочена
//						1-если сессия существует и не просрочена
//						-1-если что то пошло не так
function get_session_data($SESSION_ID,$SESSION_END_TIME){
	try {
			//echo 'Результаты голосования по вопросу:'.$QUESTION_ID.'<br>';
			$this->db_connect();
		    //mysqli_query($this->conn,"set names utf8;");
			mysqli_query($this->conn,"SET @RETVAL = 0;");
			mysqli_query($this->conn,"SET SQL_SAFE_UPDATES=0;");
			//проверяем есть ли данная сессия в таблице
			mysqli_query($this->conn,"SET @RETVAL=(SELECT COUNT(SESSION_ID) FROM ".$this->dbname.".session WHERE SESSION_ID='".$SESSION_ID."');");
			//echo "SET @RETVAL=(select count(session_id) from ".$this->dbname.".session where SESSION_ID='".$SESSION_ID."');";
			$result = mysqli_query($this->conn,'SELECT @RETVAL');
		    $row = mysqli_fetch_array($result);
			//если сессия имеется то
		    if (intval($row[0])==1){
				mysqli_free_result($result);
				//проверяем просрочена ли она
				mysqli_query($this->conn,"SET @RETVAL=(SELECT SESSION_END_TIME FROM ".$this->dbname.".session WHERE SESSION_ID='".$SESSION_ID."');");
				$result = mysqli_query($this->conn,'SELECT @RETVAL');
		    	$row = mysqli_fetch_array($result);
				//если не просрочена тогда пробуем оценить сколько ей осталось жить
		    	if ($row[0]!='') {
					mysqli_free_result($result);
					mysqli_query($this->conn,"SET @RETVAL = 0;");					
					mysqli_query($this->conn,"SET @RETVAL=(SELECT DATEDIFF(SESSION_END_TIME,'".$SESSION_END_TIME."') FROM ".$this->dbname.".session WHERE SESSION_ID='".$SESSION_ID."');");    	
					$result = mysqli_query($this->conn,'SELECT @RETVAL');
					$row = mysqli_fetch_array($result);
					//если время существования сессии истекло тогда удаляем сессию
					if (intval($row[0])<-1) {
						mysqli_query($this->conn,"DELETE FROM ".$this->dbname.".session WHERE SESSION_ID='".$SESSION_ID."';");
						$row[0]='0';
					} else {
						$row[0]='1';
					}
				}
			}
			//проверяем на всякий случай, если после получения session_id значение $row[0] не обнулялось тогда необходимо признать результат равным 0
			if (intval($row[0])!=0 && intval($row[0])!=1){
				$row[0]='-1';
			}
			if (!$this->is_array_empty($row)){
				return intval($row[0]);
			} else {
				return 0;
			}
		    $mysqli->close();
		} catch (Exception $e) {
	    	echo $this->Utf8ToWin('Ошибка в классе: voting_class метод: get_session_data() исключение: '),  $e->getMessage(), "\n";
	}
}

//************************************************************************************************************************************
public function Utf8ToWin($s)
        {
            $byte2=false;
            for ($c=0;$c<strlen($s);$c++)
            {
               $i=ord($s[$c]);
               if ($i<=127) $out.=$s[$c];
                   if ($byte2){
                       $new_c2=($c1&3)*64+($i&63);
                       $new_c1=($c1>>2)&5;
                       $new_i=$new_c1*256+$new_c2;
                   if ($new_i==1025){
                       $out_i=168;
                   } else {
                       if ($new_i==1105){
                           $out_i=184;
                       } else {
                           $out_i=$new_i-848;
                       }
                   }
                   $out.=chr($out_i);
                   $byte2=false;
                   }
               if (($i>>5)==6) {
                   $c1=$i;
                   $byte2=true;
               }
            }
            return $out;
        }
//************************************************************************************************************************************ 
public function WinToUtf8($data)
{
    if (is_array($data))
    {
        $d = array();
        foreach ($data as $k => &$v) $d[WinToUtf8($k)] = WinToUtf8($v);
        return $d;
    }
    if (is_string($data))
    {
        if (function_exists('iconv')) return iconv('cp1251', 'utf-8//IGNORE//TRANSLIT', $data);
        if (! function_exists('cp1259_to_utf8')) include_once 'cp1259_to_utf8.php';
        return WinToUtf8($data);
    }
    if (is_scalar($data) or is_null($data)) return $data;
    #throw warning, if the $data is resource or object:
    trigger_error('An array, scalar or null type expected, ' . gettype($data) . ' given!', E_USER_WARNING);
    return $data;
}
//************************************************************************************************************************************
function generateRandStr($length){
    $randstr = "";
    for($i=0; $i<$length; $i++){
        $randnum = mt_rand(0,61);
        if($randnum < 10){
            $randstr .= chr($randnum+48);
        }else if($randnum < 36){
            $randstr .= chr($randnum+55);
        }else{
            $randstr .= chr($randnum+61);
        }
    }
    return $randstr;
} 
//************************************************************************************************************************************
public function generatemd5salt($length){
  return md5($this->generateRandStr($length));
}
//************************************************************************************************************************************
	function is_array_empty($a){
		foreach($a as $key=>$elm) {
			if(!empty($elm)) {
				return false;
			} else {
				return true;
			}
		}
	}
}
?>