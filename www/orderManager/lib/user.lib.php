<?php

error_reporting('E_ALL & ~E_NOTICE & ~E_DEPRECATED');

/**
 * Авторизация Order Agent
 * @package PHPShopExchange
 * @author PHPShop Software
 * @version 1.8
 */
class UserChek {
    var $logPHPSHOP;
    var $pasPHPSHOP;
    var $idPHPSHOP;
    var $statusPHPSHOP;
    var $mailPHPSHOP;
    var $OkFlag=0;
    
    function ChekBase($table_name) {
        $sql="select * from $table_name where enabled='1'";
        $result=mysql_query($sql);
        while ($row = mysql_fetch_array($result)) {
            if($this->logPHPSHOP==$row['login']) {
                if($this->pasPHPSHOP==$row['password']) {
                    $this->OkFlag=1;
                    $this->idPHPSHOP=$row['id'];
                    $this->statusPHPSHOP=$row['status'];
                    $this->mailPHPSHOP=$row['mail'];
                }
            }
        }
    }

    function BadUser() {
        if($this->OkFlag == 0)
            exit("Login Error!");
    }
    
    function UserChek($logPHPSHOP,$pasPHPSHOP,$table_name) {
        $this->logPHPSHOP=$logPHPSHOP;
        $this->pasPHPSHOP=$pasPHPSHOP;
        $this->ChekBase($table_name);
        $this->BadUser();
    }
}


$UserChek = new UserChek($_REQUEST['log'],$_REQUEST['pas'],$SysValue['base']['table_name19']);
?>