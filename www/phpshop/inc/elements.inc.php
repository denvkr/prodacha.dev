<?php

/**
 * Элемент стандартных системных переменных
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopCoreElement
 * @version 1.1
 * @package PHPShopElements
 */
class PHPShopCoreElement extends PHPShopElements {

    /**
     * Конструктор
     */
    function PHPShopCoreElement() {
        parent::PHPShopElements();
    }

    /**
     * Назначение текущего шаблона
     * @return string
     */
    function skin() {
        if (empty($_SESSION['skin']))
            $_SESSION['skin'] = $this->PHPShopSystem->getValue('skin');
        return $_SESSION['skin'];
    }

    /**
     * Проверка существования шаблона,
     * смена на другой найденный шаблон при переименовании папки с шаблоном
     * @return string
     */
    function checkskin() {
        if (!file_exists("phpshop/templates/" . $_SESSION['skin'] . "/main/index.tpl")) {
            $dir = $this->getValue('dir.templates') . chr(47);
            if (is_dir($dir)) {
                if (@$dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {
                        if (is_file($dir . $file . chr(47) . 'main/index.tpl')) {
                            $_SESSION['skin'] = $file;
                            header('Location: /?status=template_error');
                        }
                    }
                    closedir($dh);
                }
            }
            exit('Template error!');
        }
    }

    /**
     * Определение стандратных системных переменных для шаблонов
     * (имя, телефон, почта администратора, дата, логотип)
     */
    function setdefault() {
        $this->set('telNum', $this->PHPShopSystem->getValue('tel'));
        $this->set('name', $this->PHPShopSystem->getValue('name'));
        $this->set('company', $this->PHPShopSystem->getValue('company'));
        $this->set('descrip', $this->PHPShopSystem->getValue('descrip'));
        $this->set('adminMail', $this->PHPShopSystem->getValue('adminmail2'));
        $this->set('pathTemplate', $this->getValue('dir.templates') . chr(47) . $_SESSION['skin']);
        $this->set('serverName', $_SERVER['SERVER_NAME']);
        $this->set('serverShop', $_SERVER['SERVER_NAME']);
        if (!empty($_SESSION['UserLogin']))
            $this->set('UserLogin', $_SESSION['UserLogin']);
        $this->set('ShopDir', $this->getValue('dir.dir'));
        $this->set('date', date("d-m-y H:i a"));
        $this->set('user_ip', $_SERVER['REMOTE_ADDR']);
        $this->set('NavActive', $this->PHPShopNav->getPath());

        // Логотип
        $this->set('logo', $this->PHPShopSystem->getLogo());
    }

    /**
     * Стили шаблона дизайна
     * @return string
     */
    function pageCss() {
        $this->set('pathTemplate', $this->getValue('dir.templates') . chr(47) . $_SESSION['skin']);
        return $this->getValue('dir.templates') . chr(47) . $_SESSION['skin'] . chr(47) . $this->getValue('css.default');
    }

}

/**
 * Элемент формы авторизации пользователя
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopUserElement
 * @version 1.2
 * @package PHPShopElements
 */
class PHPShopUserElement extends PHPShopElements {

    /**
     * Конструктор
     */
    function PHPShopUserElement() {
        $this->debug = false;
        $this->objBase = $GLOBALS['SysValue']['base']['shopusers'];
        parent::PHPShopElements();

        // Экшены
        $this->setAction(array('post' => 'user_enter', 'get' => 'logout'));
    }

    /**
     * Кодирование пароля
     * @param string $str строка
     * @return string кодированная строка
     */
    function encode($str) {
        return base64_encode(trim($str));
    }

    /**
     * Экшен выхода пользователя
     */
    function logout() {
        unset($_SESSION['UsersId']);
        unset($_SESSION['UsersStatus']);
        $url_user = str_replace("?logout=true", "", $_SERVER['REQUEST_URI']);
        header("Location: " . $url_user);
    }

    /**
     * Проверка авторизации
     * @return bool
     */
    function autorization() {
        if (PHPShopSecurity::true_login($_POST['login']) and PHPShopSecurity::true_passw($_POST['password'])) {
            $PHPShopOrm = new PHPShopOrm($this->objBase);
            $PHPShopOrm->debug = $this->debug;
            $data = $PHPShopOrm->select(array('*'), array('login' => '="' . trim($_POST['login']) . '"', 'password' => '="' . $this->encode($_POST['password']) . '"', 'enabled' => "='1'"), false, array('limit' => 1));
            if (is_array($data))
                if (PHPShopSecurity::true_num($data['id'])) {

                    // ID пользователя
                    $_SESSION['UsersId'] = $data['id'];

                    // Логин пользователя
                    $_SESSION['UsersLogin'] = $data['login'];

                    // Статус пользователя
                    $_SESSION['UsersStatus'] = $data['status'];

                    // Дата входа
                    $this->log();

                    return true;
                }
        }
    }

    /**
     * Запись даты авторизации пользователя
     */
    function log() {
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->debug = $this->debug;
        $PHPShopOrm->update(array('datas_new' => time()), array('id' => '=' . $_SESSION['UsersId']));
    }

    /**
     * Экшен входа пользователя
     */
    function user_enter() {

        if ($this->autorization()) {

            // Запоминаем пользователя в cookie
            if (!empty($_POST['safe_users'])) {
                setcookie("UserLogin", trim($_POST['login']), time() + 60 * 60 * 24 * 30, "/", $_SERVER['SERVER_NAME'], 0);
                setcookie("UserPassword", trim($_POST['password']), time() + 60 * 60 * 24 * 30, "/", $_SERVER['SERVER_NAME'], 0);
                setcookie("UserChecked", 1, time() + 60 * 60 * 24 * 30, "/", $_SERVER['SERVER_NAME'], 0);
            } else {
                setcookie("UserLogin", "", time() + 60 * 60 * 24 * 30, "/", $_SERVER['SERVER_NAME'], 0);
                setcookie("UserPassword", "", time() + 60 * 60 * 24 * 30, "/", $_SERVER['SERVER_NAME'], 0);
                setcookie("UserChecked", "", time() + 60 * 60 * 24 * 30, "/", $_SERVER['SERVER_NAME'], 0);
            }

            // Редирект
            if (preg_match("/LogOut/", $_SERVER['REQUEST_URI']))
                $url_user = str_replace("?LogOut", "#userPage", $_SERVER['REQUEST_URI']);
            elseif (!empty($_GET['key']))
                $url_user = $this->getValue('dir.dir') . '/users/';
            else
                $url_user = $_SERVER['REQUEST_URI'];

            header("Location: " . $url_user);
        }
        else
            $this->set('usersError', $this->lang('error_login'));
    }

    /**
     * Форма авторизации пользователя
     */
    function usersDisp() {
    	$dis=null;
        if (!empty($_SESSION['UsersId']) and PHPShopSecurity::true_num($_SESSION['UsersId'])) {
            $this->set('UsersLogin', $_SESSION['UsersLogin']);
            $dis = $this->parseTemplate($this->getValue('templates.users_forma_enter'));
        } else {

            // Блок авторизации, данные из cookie
            if (PHPShopSecurity::true_num($_COOKIE['UserChecked']))
                $this->set('UserChecked', 'checked');

            if (PHPShopSecurity::true_login($_COOKIE['UserLogin']))
                $this->set('UserLogin', $_COOKIE['UserLogin']);

            if (PHPShopSecurity::true_passw($_COOKIE['UserPassword']))
                $this->set('UserPassword', $_COOKIE['UserPassword']);

            // Перехват модуля
            $this->setHook(__CLASS__, __FUNCTION__);

            $dis = $this->parseTemplate($this->getValue('templates.users_forma'));
        }
        return $dis;
    }

}

/**
 * Элемент каталоги страниц
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopPageCatalogElement
 * @version 1.2
 * @package PHPShopElements
 */
class PHPShopPageCatalogElement extends PHPShopElements {

    /**
     * @var bool проверять на единичные каталоги
     */
    var $chek_page = true;
    var $debug = false;

    /**
     * Конструктор
     */
    function PHPShopPageCatalogElement() {
        $this->objBase = $GLOBALS['SysValue']['base']['page_categories'];
        parent::PHPShopElements();
    }

    /**
     * Вывод навигации каталогов
     * @return string
     */
    function pageCatal() {
        $dis = null;
        $i = 0;

        $this->PHPShopOrm->cache = true;
        $data = $this->PHPShopOrm->select(array('*'), array('parent_to' => '=0'), array('order' => 'num'), array("limit" => 100));

        // Перехват модуля в начале
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $data, 'START');
        if ($hook)
            return $hook;

        if (is_array($data))
            foreach ($data as $row) {

                // Определяем переменные
                $this->set('catalogId', $row['id']);
                $this->set('catalogI', $i);
                $this->set('catalogTemplates', $this->getValue('dir.templates') . chr(47) . $this->PHPShopSystem->getValue('skin') . chr(47));

                // Если есть страницы
                if ($this->chek($row['id'])) {

                    $this->set('catalogName', $row['name']);
                    $this->set('catalogId', $row['id']);

                    // Перехват модуля
                    $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

                    $dis.=$this->parseTemplate($this->getValue('templates.catalog_page_forma_2'));
                } else {
                    $this->set('catalogPodcatalog', $this->subcatalog($row['id']));
                    $this->set('catalogName', $row['name']);

                    // Перехват модуля
                    $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');

                    $dis.=$this->parseTemplate($this->getValue('templates.catalog_page_forma'));
                }

                $i++;
            }
        return $dis;
    }

    /**
     * Проверка подкаталогов
     * @param Int $id ИД каталога
     * @return bool
     */
    function chek($id) {
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.page_categories'));
        $PHPShopOrm->debug = $this->debug;
        $num = $PHPShopOrm->select(array('id'), array('parent_to' => "=$id"), false, array('limit' => 1));
        if (empty($num['id']))
            return true;
    }

    /**
     * Вывод подкаталогов
     * @param Int $n ИД каталога
     * @return string
     */
    function subcatalog($n) {
        $dis = null;
        $i = 0;
        $n = PHPShopSecurity::TotalClean($n, 1);
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.page_categories'));
        $data = $PHPShopOrm->select(array('*'), array('parent_to' => '=' . $n), array('order' => 'num'), array("limit" => 100));

        // Перехват модуля в начале
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $data, 'START');
        if ($hook)
            return $hook;

        if (is_array($data))
            foreach ($data as $row) {

                // Определяем переменные
                $this->set('catalogId', $n);
                $this->set('catalogUid', $row['id']);
                $this->set('catalogI', $i);
                $this->set('catalogLink', 'CID_' . $row['id']);
                $this->set('catalogTemplates', $this->getValue('dir.templates') . chr(47) . $this->PHPShopSystem->getValue('skin') . chr(47));
                $this->set('catalogName', $row['name']);
                $i++;

                // Перехват модуля
                $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

                // Подключаем шаблон
                $dis.=$this->parseTemplate($this->getValue('templates.podcatalog_page_forma'));
            }
        return $dis;
    }

}

/**
 * Элемент текстовые блоки
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopTextElement
 * @version 1.0
 * @package PHPShopElements
 */
class PHPShopTextElement extends PHPShopElements {

    var $debug = false;

    /**
     * Конструктор
     */
    function PHPShopTextElement() {
        $this->objBase = $GLOBALS['SysValue']['base']['table_name14'];
        parent::PHPShopElements();
    }

    /**
     * Вывод текстового блока в левую часть
     * @return string
     */
    function leftMenu() {
        $dis = null;
        $data = $this->PHPShopOrm->select(array('*'), array("flag" => "='1'", 'element' => "='0'"), array('order' => 'num'), array("limit" => 20));
        if (is_array($data))
            foreach ($data as $row) {
                if (empty($row['dir'])) {

                    // Определяем переменные
                    $this->set('leftMenuName', $row['name']);
                    $this->set('leftMenuContent', Parser($row['content']));

                    // Перехват модуля
                    $this->setHook(__CLASS__, __FUNCTION__, $row);

                    // Подключаем шаблон
                    $dis.=$this->parseTemplate($this->getValue('templates.left_menu'));
                } else {
                    $dirs = explode(",", $row['dir']);
                    foreach ($dirs as $dir)
                        if (strpos($_SERVER['REQUEST_URI'], $dir) or $_SERVER['REQUEST_URI'] == $dir) {
                            $this->set('leftMenuName', $row['name']);
                            $this->set('leftMenuContent', Parser($row['content']));

                            // Перехват модуля
                            $this->setHook(__CLASS__, __FUNCTION__, $row);

                            // Подключаем шаблон
                            $dis.=$this->parseTemplate($this->getValue('templates.left_menu'));
                        }
                }
            }
        return $dis;
    }

    /**
     * Вывод текстового блока в правую часть
     * @return string
     */
    function rightMenu() {
        $dis = null;
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $data = $PHPShopOrm->select(array('*'), array("flag" => "='1'", 'element' => "='1'"), array('order' => 'num'), array("limit" => 20));
        if (is_array($data))
            foreach ($data as $row) {
                if (empty($row['dir'])) {

                    // Определяем переменные
                    $this->set('leftMenuName', $row['name']);
                    $this->set('leftMenuContent', Parser($row['content']));

                    // Перехват модуля
                    $this->setHook(__CLASS__, __FUNCTION__, $row);

                    $dis.=$this->parseTemplate($this->getValue('templates.right_menu'));
                } else {
                    $dirs = explode(",", $row['dir']);
                    foreach ($dirs as $dir)
                        if (strpos($_SERVER['REQUEST_URI'], $dir) or $_SERVER['REQUEST_URI'] == $dir) {
                            $this->set('leftMenuName', $row['name']);
                            $this->set('leftMenuContent', Parser($row['content']));

                            // Перехват модуля
                            $this->setHook(__CLASS__, __FUNCTION__, $row);

                            // Подключаем шаблон
                            $dis.=$this->parseTemplate($this->getValue('templates.right_menu'));
                        }
                }
            }
        return $dis;
    }

    /**
     * Вывод главного навигационного меню
     * @return string
     */
    function topMenu() {
        $dis = null;
        $objBase = $GLOBALS['SysValue']['base']['table_name11'];
        $PHPShopOrm = new PHPShopOrm($objBase);
        $data = $PHPShopOrm->select(array('name', 'link'), array("category" => "=1000", 'enabled' => "='1'"), array('order' => 'num'), array("limit" => 20));
        if (is_array($data))
            foreach ($data as $row) {

                // Определяем переменные
                $this->set('topMenuName', $row['name']);
                $this->set('topMenuLink', $row['link']);

                // Перехват модуля
                $this->setHook(__CLASS__, __FUNCTION__, $row);
                //echo $this->get('topMenuName');
                if ( $this->getValue('topMenuName')!=='' && $this->get('topMenuLink')!=='' ) {
                    // Подключаем шаблон
                    $dis.=$this->parseTemplate($this->getValue('templates.top_menu'));
                }
                    
            }
            //echo $dis;
        return $dis;
    }

}

/**
 * Элемент cмена шаблонов
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopSkinElement
 * @version 1.0
 * @package PHPShopElements
 */
class PHPShopSkinElement extends PHPShopElements {

    function PHPShopSkinElement() {
        parent::PHPShopElements();

        // Экшены
        $this->setAction(array('post' => 'skin', 'get' => 'skin'));
    }

    /**
     * Экшен по умолчанию, вывод формы выбора шаблона
     * @return string
     */
    function index() {
    	$dis=null;
        if ($this->PHPShopSystem->getSerilizeParam("admoption.user_skin") == 1) {
            $dir = $this->getValue('dir.templates') . chr(47);
            if (is_dir($dir)) {
                if (@$dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {

                        if ($_SESSION['skin'] == $file)
                            $sel = "selected";
                        else
                            $sel = "";

                        if ($file != "." and $file != ".." and $file != "index.html")
                            $value[] = array($file, $file, $sel);
                    }
                    closedir($dh);
                }
            }

            // Определяем переменные
            $forma = PHPShopText::p(PHPShopText::form(PHPShopText::select('skin', $value, 150, $float = "none", $caption = false, $onchange = "ChangeSkin()"), 'SkinForm'));
            $this->set('leftMenuContent', $forma);
            $this->set('leftMenuName', __("Сменить дизайн"));

            // Подключаем шаблон
            $dis = $this->parseTemplate($this->getValue('templates.left_menu'));
        }
        return $dis;
    }

    /**
     * Экшен смены шаблона
     */
    function skin() {
        if ($this->PHPShopSystem->getValue('spec_num')) {
            if (file_exists("phpshop/templates/" . $_REQUEST['skin'] . "/main/index.tpl")) {
                $skin = $_REQUEST['skin'];
                if (PHPShopSecurity::true_skin($_REQUEST['skin']))
                    $_SESSION['skin'] = $_REQUEST['skin'];
            }
        }
    }

}

/**
 * Элемент последние новости
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopNewsElement
 * @version 1.0
 * @package PHPShopElements
 */
class PHPShopNewsElement extends PHPShopElements {

    /**
     * @var bool Показывать новости только на главной
     */
    var $disp_only_index = true;

    /**
     * @var int  Кол-во новостей
     */
    var $limit = 5;

    /**
     * Конструктор
     */
    function PHPShopNewsElement() {
        $this->debug = false;
        $this->objBase = $GLOBALS['SysValue']['base']['table_name8'];
        parent::PHPShopElements();
    }

    /**
     * Вывод последних новостей
     * @return string
     */
    function index() {
        $dis = null;

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, false, 'START');

        // Выполнение только на главной странице
        if ($this->disp_only_index) {
            if ($this->PHPShopNav->index())
                $view = true;
            else
                $view = false;
        }
        else
            $view = true;

        if (!empty($view)) {

            $result = $this->PHPShopOrm->select(array('id', 'zag', 'datas', 'kratko'), false, array('order' => 'id DESC'), array("limit" => $this->limit));

            // Проверка на еденичню запись
            if ($this->limit > 1)
                $data = $result;
            else
                $data[] = $result;

            if (is_array($data))
                foreach ($data as $row) {

                    // Определяем переменные
                    $this->set('newsId', $row['id']);
                    $this->set('newsZag', $row['zag']);
                    $this->set('newsData', $row['datas']);
                    $this->set('newsKratko', $row['kratko']);

                    // Перехват модуля
                    $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');

                    // Подключаем шаблон
                    $dis.=$this->parseTemplate($this->getValue('templates.news_main_mini'));
                }
            return $dis;
        }
    }

}

/**
 * Элемент Форма опросов
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopOprosElement
 * @version 1.1
 * @package PHPShopElements
 */
class PHPShopOprosElement extends PHPShopElements {

    /**
     * Конструктор
     */
    function PHPShopOprosElement() {
        $this->debug = false;
        parent::PHPShopElements();
    }

    /**
     * Вывод формы голосования
     * @return string
     */
    function oprosDisp() {

        // Выборка данных
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.opros_categories'));
        $PHPShopOrm->debug = $this->debug;
        $dataArray = $PHPShopOrm->select(array('*'), array('flag' => "='1'"), array('order' => 'id DESC'), array('limit' => 10));
        $content = null;
        if (is_array($dataArray))
            foreach ($dataArray as $row) {

                if (empty($row['dir'])) {
                    // Определяем переменные
                    $this->set('oprosName', $row['name']);
                    $this->set('oprosContent', $this->getOprosValue($row['id'], "FORMA"));

                    // Подключаем шаблон
                    $content.= $this->parseTemplate($this->getValue('templates.opros_list'));
                } else {

                    // Если через запятую указано
                    if (strpos($row['dir'], ","))
                        $dirs = explode(",", $row['dir']);
                    else
                        $dirs[] = $row['dir'];

                    foreach ($dirs as $dir)
                        if (!empty($dir))
                            if (strpos($_SERVER['REQUEST_URI'], $dir) or $_SERVER['REQUEST_URI'] == $dir) {

                                // Определяем переменные
                                $this->set('oprosName', $row['name']);
                                $this->set('oprosContent', $this->getOprosValue($row['id'], "FORMA"));

                                // Перехват модуля
                                $this->setHook(__CLASS__, __FUNCTION__, $row);

                                // Подключаем шаблон
                                $content.= $this->parseTemplate($this->getValue('templates.opros_list'));
                            }
                }
            }

        return $content;
    }

    /**
     * Вывод ответов
     * @param int $n ИД опроса
     * @param string $flag [FORMA|RESULT] опция места вывода (форма опроса или результат опросов)
     * @return string
     */
    function getOprosValue($n, $flag) {
        $dis = null;
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.opros'));
        $PHPShopOrm->comment = 'getOprosValue';
        $PHPShopOrm->debug = $this->debug;
        $this->dataArray = $PHPShopOrm->select(array('*'), array('category' => '=' . $n), array('order' => 'num'), array('limit' => 100));
        if (is_array($this->dataArray))
            foreach ($this->dataArray as $row) {

                if ($row['total'] > 0)
                    $total = $row['total'];
                else
                    $total = "--";

                // Определяем переменые
                $this->set('valueName', $row['name']);
                $this->set('valueId', $row['id']);


                // Подключаем шаблон
                if ($flag == "FORMA")
                    $dis.=$this->parseTemplate($this->getValue('templates.opros_forma'));
                elseif ($flag == "RESULT") {
                    $sum = $this->getSumValue($row['category']);
                    $pr = @number_format(($total * 100) / $sum, "1", ".", "");

                    // Определяем переменые
                    $this->set('valueSum', $total);
                    $this->set('valueProc', $pr);
                    $this->set('valueWidth', $pr * 3 + 1);

                    $dis.=$this->parseTemplate($this->getValue('templates.opros_page_forma'));
                }
            }
        return $dis;
    }

    /**
     * Сумма значений
     * @param int $n ИД опроса
     * @return int
     */
    function getSumValue($n) {
        $objBase = $this->getValue('base.opros');
        $PHPShopOrm = new PHPShopOrm($objBase);
        $result = $PHPShopOrm->query("select SUM(total) as sum from " . $objBase . " where category=" . $n);
        $row = mysql_fetch_array($result);
        return $row['sum'];
    }

}

/**
 * Элемент баннер
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopBannerElement
 * @version 1.4
 * @package PHPShopElements
 */
class PHPShopBannerElement extends PHPShopElements {

    function PHPShopBannerElement() {
        $this->debug = false;
        $this->objBase = $GLOBALS['SysValue']['base']['table_name15'];
        parent::PHPShopElements();
    }

    /**
     * Вывод баннера
     * @return string
     */
    function index() {

        $data = $this->PHPShopOrm->select(array('*'), array("flag" => "='1'"), array('order' => 'RAND()'), array("limit" => 30));

        if (is_array($data))
            foreach ($data as $row) {
                if (empty($row['dir'])) {
                    
                    // Определяем переменные
                    $this->set('banerContent', $row['content']);
                    $this->set('banerTitle', $row['name']);

                    // Сообщение администратору о конце показов
                    //if ($row['count_all'] > $row['limit_all'])
                       // $this->mail();

                    // Обновляем данные показа
                    //$this->update();

                    // Подключаем шаблон
                    return $this->parseTemplate($this->getValue('templates.baner_list_forma'));
                }
                else {
                    $dirs = explode(",", $row['dir']);
                    foreach ($dirs as $dir)
                        if (!empty($dir))
                            if (strpos($_SERVER['REQUEST_URI'], trim($dir)) or $_SERVER['REQUEST_URI'] == trim($dir)) {

                                // Определяем переменные
                                $this->set('banerContent', $row['content']);
                                $this->set('banerTitle', $row['name']);

                                // Сообщение администратору о конце показов
                                //if ($this->row['count_all'] > $row['limit_all'])
                                   // $this->mail();

                                // Обновляем данные показа
                                //$this->update();

                                // Подключаем шаблон
                                return $this->parseTemplate($this->getValue('templates.baner_list_forma'));
                            }
                }
            }
    }

    /**
     * Запись показов баннера
     */
    function update() {
        if ($this->row['datas'] != date("d.m.y"))
            $count_today = 0;
        else
            $count_today = $this->row['count_today'] + 1;

        $count_all = $this->row['count_all'] + 1;
        $this->PHPShopOrm->update(array('count_all' => $count_all, 'count_today' => $count_today, 'datas' => date("d.m.y")), array('id' => "=" . $this->row['id']), $prefix = '');
    }

    /**
     * Сообщение об окончании показов баннера
     */
    function mail() {
        $this->PHPShopOrm->update(array('flag' => '0'), array('id' => "=" . $this->row['id']), $prefix = '');

        // Подключаем библиотеку отправки почты
        PHPShopObj::loadClass("mail");
        $zag = __("Закончились показы у банера") . " " . $this->row['name'];

        $this->set('banner_name', $this->row['name']);
        $this->set('banner_limit', $this->row['limit_all']);

        // Текст сообщения
        $message = ParseTemplateReturn('./phpshop/lib/templates/banner/mail_notice.tpl', true);

        $PHPShopMail = new PHPShopMail($this->PHPShopSystem->getParam('adminmail2'),
                        "robot@" . str_replace("www", '', $_SERVER['SERVER_NAME']), $zag, $message);
    }

}

?>