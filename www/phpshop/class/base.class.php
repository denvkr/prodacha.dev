<?php

/**
 * Библиотека подключения к БД
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopClass
 * @param string $iniPath путь до конфигурационного файла config.ini
 */
class PHPShopBase {

    /**
     * путь до конфигурационного файла config.ini
     * @var string 
     */
    var $iniPath;

    /**
     * массив данных настроек конфигурационного файла config.ini
     * @var array 
     */
    var $SysValue;

    /**
     * Кодировка MySQL (русская cp1251)
     * @var string
     */
    var $codBase = "cp1251";

    /**
     * Настройки локали сервера (русская cp1251)
     * @var string 
     */
    var $locale = 'ru_RU.cp1251';

    /**
     * Временная зона (Москва Europe/Moscow)
     * @var string 
     */
    var $timezone = 'Europe/Moscow';

    /**
     * режим отладки
     * @var bool 
     */
    var $debug = true;

    /**
     * Подключения к БД
     * @param string $iniPath путь до конфигурационного файла config.ini
     */
    function PHPShopBase($iniPath, $connectdb = true) {

        // Временная зона
        $this->setTimeZone();

        // Отладка ядра
        $this->setPHPCoreReporting();

        $this->iniPath = $iniPath;
        $this->SysValue = parse_ini_file($this->iniPath, 1);
        $GLOBALS['SysValue'] = &$this->SysValue;

        if (!empty($connectdb))
            $this->connect();
    }

    /**
     * Выдача системных параметров конфига
     * @return array
     */
    function getSysValue() {
        return $this->SysValue;
    }

    /**
     * Выдача системных параметров конфига
     * <code>
     * // example
     * $PHPShopBase= new PHPShopBase('./inc/config.ini');
     * $PHPShopBase->getParam('base.table_name');
     * </code>
     * @param mixed $param имя параметра
     * @return string
     */
    function getParam($param) {
        $param = explode(".", $param);
        if (count($param) > 2)
            return $this->SysValue[$param[0]][$param[1]][$param[2]];
        return $this->SysValue[$param[0]][$param[1]];
    }

    /**
     * Добавить параметр
     * <code>
     * // example
     * $PHPShopBase= new PHPShopBase('./inc/config.ini');
     * $PHPShopBase->setParam('base.table_name','mybase');
     * </code>
     * @param string $param имя параметра
     * @param mixed $value значение параметра
     */
    function setParam($param, $value) {
        $param = explode(".", $param);
        if ($param[0] == "var")
            $param[0] = "other";
        $GLOBALS['SysValue'][$param[0]][$param[1]] = $value;
    }

    /**
     * Вывод сообщения об ошибке
     * @param int $e номер внутренней ошибки
     * @param string $message текст сообщения
     * @param string $error текст ошибки
     */
    function errorConnect($e = false, $message = "Нет соединения с базой", $error = false) {
        echo "<strong>$message</strong> ( <a href='http://www.phpshop.ru/help/Content/install/phpshop.html#6' target='_blank'>Error $e</a> )<br>";
        echo "<em>Ошибка: " . $error . mysql_error() . "</em>";

        if (is_dir('./install/'))
            echo '<script>window.open("./install/");</script>';
        else
            echo '<script>window.open("http://www.phpshop.ru/help/Content/install/phpshop.html#6");</script>';
        exit();
    }

    /**
     * Соединение с БД MySQL
     */
    function connect() {
        $SysValue = $this->SysValue;
        @mysql_connect($this->getParam("connect.host"), $this->getParam("connect.user_db"), $this->getParam("connect.pass_db")) or die($this->errorConnect(101));
        mysql_select_db($SysValue['connect']['dbase']) or die($this->errorConnect(102));
        mysql_query("SET NAMES '" . $this->codBase . "'");
    }

    /**
     * Проверка прав администратора
     * @param bool $require загрузка проверочного файла
     */
    function chekAdmin($require = true) {
        global $UserChek, $UserStatus;
        $adminPath = explode("../", $this->iniPath);
        $aPath=null;
        $i = 2;
        while (count($adminPath) > $i) {
            $aPath.="../";
            $i++;
        }
        $loadPath = $aPath . "enter_to_admin.php";
        if ($require) {
            require_once($loadPath);
            PHPShopObj::loadClass('admrule');
            $this->Rule = new PHPShopAdminRule();
        }
        else
            return $loadPath;
    }

    /**
     * Выдача кол-ва строк в таблице
     * @param string $from_base имя таблицы
     * @param string $query SQL запрос
     * @return int
     */
    function getNumRows($from_base, $query) {
        $num = 0;
        $sql = "select COUNT('id') as count from " . $this->SysValue['base'][$from_base] . " " . $query;
        $result = mysql_query($sql);
        $row = mysql_fetch_array(@$result);
        $num = $row['count'];
        return $num;
    }

    /**
     * Настройка локали сервера 
     */
    function setLocale() {
        if (function_exists('setlocale') and !empty($this->locale))
            setlocale(LC_ALL, $this->locale);
    }

    /**
     * Настройка временной зоны сервера 
     */
    function setTimeZone() {
        if (function_exists('date_default_timezone_set') and !empty($this->timezone))
            date_default_timezone_set($this->timezone);
    }

    /**
     *  Настройка уровня оповещения отладчика
     */
    function setPHPCoreReporting() {
        if (function_exists('error_reporting')) {
            if ($this->phpversion('5.0')){
                error_reporting('E_ALL & ~E_NOTICE & ~E_DEPRECATED');
                if ($this->phpversion() and function_exists('ini_set')){
                ini_set('allow_call_time_pass_reference',1);
                }
            }
            else
                error_reporting('E_ALL & ~E_NOTICE');
        }
    }
    
    /**
     * Определение версии PHP для поддержки PHP 5.4
     * @param float $version версия
     * @return boolean 
     */
    function phpversion($version='5.3'){
        if ((phpversion() * 1) >= $version)
            return true;
    }

}

?>