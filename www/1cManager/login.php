<?php

$_classPath = "../phpshop/";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("math");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("security");

// ����������� � ��
$PHPShopBase = new PHPShopBase($_classPath."/inc/config.ini");
            echo 'login.php '.$PHPShopBase->getParam("connect.user_db").'<br>';
            echo 'login.php '.$PHPShopBase->getParam("connect.host").'<br>';
            echo 'login.php '.$PHPShopBase->getParam("connect.dbase").'<br>';  
// ����������� ����
function loadHooks() {
    if (@$dh = opendir('hook')) {
        while (($file = readdir($dh)) !== false) {
            $fstat = explode(".",$file);
            if($fstat[1] == "php" and !strstr($fstat[0],'#'))
                include_once('hook/'.$file);
        }
        closedir($dh);
    }
}

// ����������� ����
loadHooks();

/**
 * ����������� �������������
 * @package PHPShopExchange
 * @author PHPShop Software
 * @version 1.1
 */
class UserChek {
    var $logPHPSHOP;
    var $pasPHPSHOP;
    var $idPHPSHOP;
    var $statusPHPSHOP;
    var $mailPHPSHOP;
    var $OkFlag=0;

    function ChekBase($table_name) {
        $sql="select * from ".$table_name." where enabled='1'";
        @$result=mysql_query(@$sql);
        while (@$row = mysql_fetch_array(@$result)) {
            //echo $row['login'].' '.$row['password'];
            if($this->logPHPSHOP==$row['login']) {
                if($this->pasPHPSHOP==$row['password']) {
                    $this->OkFlag=1;
                }
            }
        }
    }
    
    function myDecode($disp) {
        $decode=substr($disp,0,strlen($disp)-4);
        $decode=str_replace("I",11,$decode);
        $decode=explode("O",$decode);
        $disp_pass="";
        for ($i=0;$i<(count($decode)-1);$i++) $disp_pass.=chr($decode[$i]);
        return base64_encode($disp_pass);
    }

    function BadUser() {
        if($this->OkFlag == 0) exit("Login Error");
    }

    function UserChek($logPHPSHOP,$pasPHPSHOP,$table_name) {
        $this->logPHPSHOP=$logPHPSHOP;
        $this->pasPHPSHOP=$pasPHPSHOP;//$this->myDecode($pasPHPSHOP);
        //echo $this->pasPHPSHOP;
        $this->ChekBase($table_name);
        $this->BadUser();
    }
}

// �������� �����������
$UserChek = new UserChek('denvkr','UGM0ZmRmZg==',$PHPShopBase->getParam("base.table_name19"));//$_GET['log'],$_GET['pas']
?>