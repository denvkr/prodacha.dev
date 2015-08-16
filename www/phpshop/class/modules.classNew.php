<?php

/**
 * Подключение модулей
 * @author PHPShop Software
 * @version 1.6
 * @package PHPShopClass
 */
class PHPShopModules {

    /**
     * @var mixed массив системных настроек модулей
     */
    var $ModValue;

    /**
     * @var string Относительное размещение модулей
     */
    var $ModDir;

    /**
     * @var bool режим отладки
     */
    var $debug = false;

    /**
     * @var bool кэширования результата проверки перехвата функций
     */
    var $memory = false;

    /**
     * Конструктор
     * @param string $ModDir  Относительное размещение модулей
     */
    function PHPShopModules($ModDir = "phpshop/modules/", $mod_path = false) {
        $this->ModDir = $ModDir;
        $this->objBase = $GLOBALS['SysValue']['base']['modules'];

        $this->PHPShopOrm = &new PHPShopOrm($this->objBase);
        $this->PHPShopOrm->debug = $this->debug;

        $this->path = $mod_path;

        $this->checkKeyBase();

        $data = $this->PHPShopOrm->select(array('*'), false, false, array('limit' => 100));
        if (is_array($data))
            foreach ($data as $row) {
                $path = $row['path'];
                if (empty($_SESSION[$this->getKeyName()][crc32($path)]) or $this->path)
                    $this->getIni($path);
            }

        // Добавляем хуки шаблона
        $this->addTemplateHook();
    }

    /**
     * Обработка паметров конфига хуков шаблона /php/hook/
     */
    function addTemplateHook() {
        $ini = $GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . "/php/inc/config.ini";
        if (file_exists($ini)) {
            $SysValue = parse_ini_file($ini, 1);

            if (is_array($SysValue['autoload']))
                foreach ($SysValue['autoload'] as $k => $v)
                    $this->ModValue['autoload'][$k] = './phpshop/templates/' . $_SESSION['skin'] . chr(47) . $v;

            if (is_array($SysValue['hook']))
                foreach ($SysValue['hook'] as $k => $v)
                    $this->ModValue['hook'][][$k] = './phpshop/templates/' . $_SESSION['skin'] . chr(47) . $v;
        }
    }

    /**
     * Обновление БД модуля
     * @param string $version предыдущая версия
     */
    function getUpdate($version = false) {

        if (empty($version))
            $version = 'default';

        $file = '../update/' . $version . '/update_module.sql';
        if (file_exists($file)) {
            $sql = file_get_contents($file);
            $sqlArray = explode(";", $sql);
            if (is_array($sqlArray))
                foreach ($sqlArray as $val)
                    mysql_query($val);
        }
        $db = $this->getXml("../install/module.xml");
        return $db['version'];
    }

    /**
     * Обработка паметров конфига модулей
     * @param string $path путь до конфигурации модуля
     */
    function getIni($path) {
        $ini = $this->ModDir . $path . "/inc/config.ini";
        if (file_exists($ini)) {
            $SysValue = parse_ini_file($ini, 1);

            if (is_array($SysValue['autoload']))
                foreach ($SysValue['autoload'] as $k => $v)
                    $this->ModValue['autoload'][$k] = $v;

            if (is_array($SysValue['core']))
                foreach ($SysValue['core'] as $k => $v)
                    $this->ModValue['core'][$k] = $v;

            if (is_array($SysValue['class']))
                foreach ($SysValue['class'] as $k => $v)
                    $GLOBALS['SysValue']['class'][$k] = $v;

            if (is_array($SysValue['lang']))
                foreach ($SysValue['lang'] as $k => $v)
                    $GLOBALS['SysValue']['lang'][$k] = $v;

            if (is_array($SysValue['admpanel']))
                foreach ($SysValue['admpanel'] as $k => $v)
                    $this->ModValue['admpanel'][$k] = $v;

            if (is_array($SysValue['hook']))
                foreach ($SysValue['hook'] as $k => $v)
                    $this->ModValue['hook'][][$k] = $v;

            $this->ModValue['templates'] = $SysValue['templates'];
            $GLOBALS['SysValue']['templates'][$path] = $SysValue['templates'];
            $this->ModValue['base'][$path] = $SysValue['base'];
            $GLOBALS['SysValue']['base'][$path] = $SysValue['base'];
            $this->ModValue['class'] = $SysValue['class'];
            $this->ModValue['field'][$path] = $SysValue['field'];
        }
    }

    function getKeyName() {
        return substr(md5($_SERVER["HTTP_USER_AGENT"]), 0, 5);
    }

    function crc16($data) {
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($data); $i++) {
            $x = (($crc >> 8) ^ ord($data[$i])) & 0xFF;
            $x ^= $x >> 4;
            $crc = (($crc << 8) ^ ($x << 12) ^ ($x << 5) ^ $x) & 0xFFFF;
        }
        return $crc;
    }

    function checkKey($key, $path) {
        $str = $path . $_SERVER['SERVER_NAME'];
        if ($this->crc16(substr($str, 0, 5)) . "-" . $this->crc16(substr($str, 5, 10)) . "-" . $this->crc16(substr($str, 10, 15)) == $key)
            return true;
    }

    function checkKeyBase($path = false) {

        if (!empty($path))
            $this->path = $path;

        if ($this->path) {
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['modules_key']);
            $data = $PHPShopOrm->select(array('*'), array('path' => "='" . $this->path . "'",), false, array('limit' => 1));
            if (is_array($data)) {
                if ($data['verification'] != md5($data['path'] . $data['date'] . $_SERVER['SERVER_NAME'] . $data['key']) or $data['date'] < time()) {
                    return $data['date'];
                }
            }else
                return true;
        }

        elseif (!isset($_SESSION[$this->getKeyName()])) {
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['modules_key']);
            $data = $PHPShopOrm->select(array('*'), false, false, array('limit' => 100));
            if (is_array($data)) {
                foreach ($data as $val) {
                    if ($val['verification'] != md5($val['path'] . $val['date'] . $_SERVER['SERVER_NAME'] . $val['key']) or $val['date'] < time()) {
                        $_SESSION[$this->getKeyName()][crc32($val['path'])] = time();
                    }
                }
            }
            if (empty($_SESSION[$this->getKeyName()])) {
                $_SESSION[$this->getKeyName()] = array();
            }
        }
    }

    function setKeyBase() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['modules_key']);
        $update = array();
        $update['key_new'] = time();
        $update['date_new'] = 1537777023;
        $update['verification_new'] = md5($this->path . $update['date_new'] . $_SERVER['SERVER_NAME'] . $update['key_new']);
        $PHPShopOrm->update($update, array('path' => "='" . $this->path . "'"));
    }

    /**
     * Загрузка параметра автозагрузки модулей
     */
    function doLoad() {
        global $SysValue, $PHPShopSystem, $PHPShopNav;
        if (is_array($this->ModValue['autoload']))
            foreach ($this->ModValue['autoload'] as $k => $v) {
                if (file_exists($v))
                    require_once($v);
                else
                    echo("Ошибка загрузки модуля " . $k . "<br>Путь: " . $v);
            }
    }

    /**
     * Загрузка ядра модулей
     * @param string $path путь размещения core файла модуля
     * @return mixed
     */
    function doLoadPath($path) {
        global $SysValue;
        if (!empty($this->ModValue['core'][$path])) {
            if (is_file($this->ModValue['core'][$path])) {
                require_once($this->ModValue['core'][$path]);
                $classname = 'PHPShop' . ucfirst($SysValue['nav']['path']);

                if (class_exists($classname)) {
                    $PHPShopCore = new $classname ();
                    $PHPShopCore->loadActions();
                    return true;
                } else
                    echo PHPShopCore::setError($classname, "не определен класс phpshop/modules/*/core/$classname.core.php");
            }
            else
                PHPShopCore::setError($path, "Ошибка загрузки модуля " . $path . "<br>Путь: " . $this->ModValue['core'][$path]);
        }else
            return false;
    }

    /**
     * Выдача конфигурационных настроек модулей
     * @param string имя параметра формы раздел.наименование [раздел.подраздел.наименование]
     * @return array
     */
    function getParam($param) {
        $param = explode(".", $param);
        if (count($param) > 2)
            return $this->ModValue[$param[0]][$param[1]][$param[2]];
        return $this->ModValue[$param[0]][$param[1]];
    }

    /**
     * Выдача конфигурационных настроек модулей
     * @return array
     */
    function getModValue() {
        return $this->ModValue;
    }

    /**
     * Парсер с заменой данных на лету
     * <code>
     * // example:
     * $PHPShopModules->Parser(array('page'=>'market'),'catalog_page_1');
     * </code>
     * @param array $preg массив заменяемых занчений
     * @param string $TemplateName имя шаблона
     * @return string
     */
    function Parser($preg, $TemplateName) {
        $file = newGetFile($GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . chr(47) . $TemplateName);

        // Замена
        foreach ($preg as $k => $v)
            $file = str_replace($k, $v, $file);

        $dis = newParser($file);
        return @$dis;
    }

    /**
     * Выдача XML настрек модуля
     * @param string $path путь до xml настроек модуля
     * @return array
     */
    function getXml($path) {
        PHPShopObj::loadClass("xml");
        if (function_exists("xml_parser_create")) {
            if (@$db = readDatabase($path, "module")) {

                if (count($db) > 1)
                    return $db;
                else
                    return $db[0];
            }
        }
    }

    /**
     * Проверка на ликвидность серийного номера
     * @param string $serial серийный номер
     * @return bool
     */
    function true_serial($serial) {
        if (preg_match('/^\d{5}-\d{5}-\d{5}$/', $serial)) {
            return true;
        }
    }

    /**
     * Проверка на обработчик файла для панели управления
     * @param string $path имя файла
     * @param string $function_name имя функции для добавления
     * @param array $data данные
     * @return string
     */
    function setAdmHandler($path, $function_name, $data) {
        global $PHPShopGUI;
        $file = pathinfo($path);
        $mod = $this->ModValue['admpanel'][substr($file['basename'], 0, -4)];
        if ($mod)
            if (is_file($this->ModDir . $mod)) {
                include_once($this->ModDir . $mod);
                $addHandler[$function_name];
            }
            else
                $this->PHPShopOrm->setError('setAdmHandler', "Ошибка размещения модуля " . $this->ModDir . $mod);
    }

    /**
     * Перехват событий Hook
     * @param string $class_name имя класса
     * @param string $function_name имя функции
     * @param mixed $obj объект
     * @param mixed $data данные
     * @param string параметр размещения хука [END|START|MIDDLE]
     */
    function setHookHandler($class_name, $function_name, $obj = false, $data = false, $rout = 'END') {

        if (!empty($this->ModValue['hook'])) {

            //if($this->memory_check($class_name,$function_name))
            foreach ($this->ModValue['hook'] as $hooks) {

                if (isset($hooks[strtolower($class_name)])) {
                    if ((phpversion() * 1) >= '5.0')
                        $hook = $hooks[strtolower($class_name)];
                    else
                        $hook = $hooks[$class_name];
                }
                else
                    $hook = null;

                if (isset($hook)) {
                    if (is_file($hook)) {

                        $addHandler = null;
                     //   include_once($hook);

                        if ((phpversion() * 1) >= '5.0') {

                            if (is_array($addHandler))
                                foreach ($addHandler as $v => $k)
                                    if (!strstr($v, '#'))
                                        $this->addHandler[$class_name][$v][] = $k;
                        }
                        else {

                            // Обработка имен функций в нижний регистр
                            if (is_array($addHandler))
                                foreach ($addHandler as $v => $k)
                                    if (!strstr($v, '#'))
                                        $this->addHandler[$class_name][strtolower($v)][] = $k;
                        }

                        if (is_array($this->addHandler[$class_name][$function_name]))
                            foreach ($this->addHandler[$class_name][$function_name] as $hook_function_name) {
                                $user_func_result = call_user_func_array($hook_function_name, array(&$obj, &$data, $rout));

                                if (!empty($user_func_result))
                                    return $user_func_result;
                            }
                    }
                    else
                        $this->PHPShopOrm->setError('setHookHandler', "Ошибка размещения модуля " . $hook);
                }
            }

            // Заносим в список отсутвия модуля для класса
            //$this->memory_set($class_name.'.'.$function_name,1);
        }
    }

    /**
     * Проверка записи в памяти
     * @return bool
     */
    function memory_check($class_name, $function_name) {
        if ($this->memory) {
            if ($this->memory_get($class_name . '.' . $function_name) != 1)
                return true;
        }
        else
            return true;
    }

    /**
     * Запись в память
     * @param string $param имя параметра [catalog.param]
     * @param mixed $value значение
     */
    function memory_set($param, $value) {
        if (!empty($this->memory)) {
            $param = explode(".", $param);
            $_SESSION['Memory'][__CLASS__][$param[0]][$param[1]] = $value;
            $_SESSION['Memory'][__CLASS__]['time'] = time();
        }
    }

    /**
     * Выборка из памяти
     * @param string $param имя параметра [catalog.param]
     * @return
     */
    function memory_get($param) {
        $this->memory_clean();
        if (!empty($this->memory)) {
            $param = explode(".", $param);
            if (isset($_SESSION['Memory'][__CLASS__][$param[0]][$param[1]])) {
                return $_SESSION['Memory'][__CLASS__][$param[0]][$param[1]];
            }
        }
    }

    /**
     * Чистка памяти по времени
     * @param bool $clean_now принудительная чистка
     */
    function memory_clean($clean_now = false) {
        if (!empty($clean_now))
            unset($_SESSION['Memory'][__CLASS__]);
        elseif ($_SESSION['Memory'][__CLASS__]['time'] < (time() - 60 * 10))
            unset($_SESSION['Memory'][__CLASS__]);
    }

}

?>