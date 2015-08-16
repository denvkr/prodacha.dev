<?

/**
 * Библиотека подключения к БД
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopClass
 * @param string $iniPath путь до конфигурационного файла config.ini
 */
class PHPShopBase {

    /**
     * @var string путь до конфигурационного файла config.ini
     */
    var $iniPath;

    /**
     * @var array массив данных настроек конфига
     */
    var $SysValue;

    /**
     * @var string кодировка базы
     */
    var $codBase = "cp1251";

    /**
     * @var bool режим отладки
     */
    var $debug = true;

    /**
     * Подключения к БД
     * @param string $iniPath путь до конфигурационного файла config.ini
     */
    function PHPShopBase($iniPath) {
        $this->iniPath = $iniPath;
        $this->SysValue = parse_ini_file($this->iniPath, 1);

        // Кодировка БД
        if ($this->getParam("my.base_charset"))
            $this->codBase = $this->getParam("my.base_charset");

        $GLOBALS['SysValue'] = &$this->SysValue;
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
     * @param mixed $value знячение параметра
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
        echo "<strong>$message</strong> ( <a href='http://www.phpshopcms.ru/help/Content/install/phpshop.html#6' target='_blank'>Error $e</a> )<br>";
        echo "<em>Ошибка: " . $error . mysql_error() . "</em>";
        echo '<script>window.open("http://www.phpshopcms.ru/help/Content/install/phpshop.html#6");</script>';
        exit();
    }

    /**
     * Соединение с БД MySQL
     */
    function connect() {
        $SysValue = $this->SysValue;
        mysql_connect($this->getParam("connect.host"), $this->getParam("connect.user_db"), $this->getParam("connect.pass_db")) or die($this->errorConnect(101));
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
        $i = 2;
        while (count($adminPath) > $i) {
            @$aPath.="../";
            $i++;
        }
        @$loadPath = $aPath . "enter_to_admin.php";
        if ($require)
            require_once($loadPath);
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
        global $SysValue;
        $num = 0;
        $sql = "select COUNT('id') as count from " . $this->SysValue['base'][$from_base] . " " . $query;
        $result = mysql_query($sql);
        $row = mysql_fetch_array(@$result);
        $num = $row['count'];
        return $num;
    }

}

?>