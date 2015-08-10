<?php

/**
 * Родительский класс ядра
 * Примеры использования размещены в папке phpshop/core/
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopCore
 * @version 1.6
 * @package PHPShopClass
 */
class PHPShopCore {

    /**
     * имя БД
     * @var string 
     */
    var $objBase;

    /**
     * Путь для навигации
     * @var string
     */
    var $objPath;

    /**
     * режим отладки
     * @var bool 
     */
    var $debug = false;

    /**
     * результат работы парсера
     * @var string 
     */
    var $Disp, $ListInfoItems;

    /**
     * массив обработки POST, GET запросов
     * @var array 
     */
    var $action = array("nav" => "index");

    /**
     * префикс экшен функций (action_|a_), используется при большом количестве методов в классах
     * @var string 
     * 
     */
    var $action_prefix = null;

    /**
     * метатеги
     * @var string
     */
    var $title, $description, $keywords, $lastmodified;

    /**
     * ссылка в навигации от корня
     * @var string 
     */
    var $navigation_link, $navigation_array = null;

    /**
     * шаблон вывода
     * @var string 
     */
    var $template = 'templates.shop';

    /**
     * таблица массива навигации
     * @var string  
     */
    var $navigationBase = 'base.categories';
    var $arrayPath;

    /**
     * длина пагинации для форматирования длины строки
     * @var int 
     */
    var $nav_len = 10;
    var $cache = false;

    /**
     * очистка временных переменных шаблона 
     * @var bool 
     */
    var $garbage_enabled = false;

    /**
     * Конструктор
     */
    function PHPShopCore() {
        global $PHPShopSystem, $PHPShopNav, $PHPShopModules;

        if ($this->objBase)
            $this->PHPShopOrm = new PHPShopOrm($this->objBase);

        $this->PHPShopOrm->debug = $this->debug;
        $this->SysValue = &$GLOBALS['SysValue'];
        $this->PHPShopSystem = $PHPShopSystem;
        $this->PHPShopOrm->cache = $this->cache;
        $this->num_row = $this->PHPShopSystem->getParam('num_row');
        $this->PHPShopNav = $PHPShopNav;
        $this->PHPShopModules = &$PHPShopModules;
        $this->page = $this->PHPShopNav->getId();
        
        if (strlen($this->page) == 0)
            $this->page = 1;

        // Определяем переменные
        $this->set('pageProduct', $this->SysValue['license']['product_name']);
    }

    /**
     * Сравнение параметра из массива
     * @param string $paramName имя переменной
     * @param string $paramValue значение переменной
     * @return bool
     */
    function ifValue($paramName, $paramValue = false) {
        if (empty($paramValue))
            $paramValue = 1;
        if ($this->objRow[$paramName] == $paramValue)
            return true;
    }

    /**
     * Расчет навигации хлебных крошек
     * @param int $id ИД позиции
     * @return array
     */
    function getNavigationPath($id) {
        $PHPShopOrm = new PHPShopOrm($this->getValue($this->navigationBase));
        $PHPShopOrm->debug = $this->debug;
        $PHPShopOrm->cache = $this->cache;


		//seo~mah		
		$need2hide=array('14','44','48');

        if  ( (!empty($id)) AND (!in_array($id,$need2hide)) )
		 {
            $PHPShopOrm->comment="Навигация";
            $v=$PHPShopOrm->select(array('name,id,parent_to'),array('id'=>'='.$id),false,array('limit'=>1));
            if(is_array($v)) {
                $this->navigation_array[]=array('id'=>$v['id'],'name'=>$v['name'],'parent_to'=>$v['parent_to']);
                if(!empty($v['parent_to']))
                    $this->getNavigationPath($v['parent_to']);
            }
        }
    }

    /**
     * Навигация хлебных крошек
     * @param int $id текущий ИД родителя
     * @param string $name имя раздела
     */
    function navigation($id, $name) {
        $dis = null;

        // Шаблоны разделителя навигации
        $spliter = ParseTemplateReturn($this->getValue('templates.breadcrumbs_splitter'));
        $home = ParseTemplateReturn($this->getValue('templates.breadcrumbs_home'));

        // Если нет шаблона разделителей
        if (empty($spliter))
            $spliter = ' / ';
        if (empty($home))
            $home = PHPShopText::a('/', __('Главная'));

        // Реверсивное построение массива категорий
        $this->getNavigationPath($id);

        if (is_array($this->navigation_array))
            $arrayPath = array_reverse($this->navigation_array);

        if (!empty($arrayPath) and is_array($arrayPath)) {
            foreach ($arrayPath as $v) {
                $dis.= $spliter . PHPShopText::a('/' . $this->PHPShopNav->getPath() . '/CID_' . $v['id'] . '.html', $v['name']);
            }
        }

        $dis = $home . $dis . $spliter . PHPShopText::b($name);
        $this->set('breadCrumbs', $dis);

        // Навигация для javascript в shop.tpl
        $this->set('pageNameId', $id);
    }

    /**
     * Генерация даты изменения документа
     */
    function header() {
        if ($this->getValue("cache.last_modified") == "true") {

            // Некоторые сервера требуют обзательных заголовков 200
            //header("HTTP/1.1 200");
            //header("Status: 200");
            @header("Cache-Control: no-cache, must-revalidate");
            @header("Pragma: no-cache");

            if (!empty($this->lastmodified)) {
                $updateDate = @gmdate("D, d M Y H:i:s", $this->lastmodified);
            } else {
                $updateDate = gmdate("D, d M Y H:i:s", (date("U") - 21600));
            }

            @header("Last-Modified: " . $updateDate . " GMT");
        }
    }

    /**
     * Генерация заголовков документа
     */
    function meta() {

        if (!empty($this->title))
            $this->set('pageTitl', $this->title);
        else
            $this->set('pageTitl', $this->PHPShopSystem->getValue("title"));

        if (!empty($this->description))
            $this->set('pageDesc', $this->description);
        else
            $this->set('pageDesc', $this->PHPShopSystem->getValue("descrip"));

        if (!empty($this->keywords))
            $this->set('pageKeyw', $this->keywords);
        else
            $this->set('pageKeyw', $this->PHPShopSystem->getValue("keywords"));
    }

    /**
     * Загрузка экшенов
     */
    function loadActions() {
        $this->setAction();
        $this->Compile();
    }

    /**
     * Выдача списка данных
     * @param array $select имена колонок БД для выборки
     * @param array $where параметры условий запроса
     * @param array $order параметры сортировки данных при выдаче
     * @return array
     */
    function getListInfoItem($select = false, $where = false, $order = false, $class_name = false, $function_name = false, $sql = false) {
        $this->ListInfoItems = null;
        $this->where = $where;

        // Обработка номера страницы
        if (!PHPShopSecurity::true_num($this->page) and strtoupper($this->page) != 'ALL')
            return $this->setError404();

        if (empty($this->page)) {
            $num_ot = 0;
            $num_do = $this->num_row;
        } else {
            $num_ot = $this->num_row * ($this->page - 1);
            $num_do = $this->num_row;
        }

        // Вывод всех страниц
        if (strtoupper($this->page) == 'ALL') {
            $num_ot = 0;
            $num_do = $this->max_item;
        }


        $option = array('limit' => $num_ot . ',' . $num_do);

        $this->set('productFound', $this->getValue('lang.found_of_products'));
        $this->set('productNumOnPage', $this->getValue('lang.row_on_page'));
        $this->set('productPage', $this->getValue('lang.page_now'));

        $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;

        if (!empty($sql)) {
            $this->PHPShopOrm->sql = 'select * from ' . $this->objBase . ' where ' . $sql . ' limit ' . $option['limit'];
        }

        return $this->PHPShopOrm->select($select, $where, $order, $option, $class_name, $function_name);
    }

    /**
     * Генерация пагинатора
     */
    function setPaginator() {

        $SQL = null;
        // Выборка по параметрам WHERE
        $nWhere = 1;
        if (is_array($this->where)) {
            $SQL.=' where ';
            foreach ($this->where as $pole => $value) {
                $SQL.=$pole . $value;
                if ($nWhere < count($this->where))
                    $SQL.=$this->PHPShopOrm->Option['where'];
                $nWhere++;
            }
        }

        // Кол-во страниц
        $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
        $result = $this->PHPShopOrm->query("select COUNT('id') as count from " . $this->objBase . $SQL);
        $row = mysql_fetch_array($result);
        $this->num_page = $row['count'];
        $i = 1;
        $navigat = null;

        // Кол-во страниц в навигации
        $num = ceil($this->num_page / $this->num_row);

        while ($i <= $num) {

            if ($i > 1) {
                $p_start = $this->num_row * ($i - 1);
                $p_end = $p_start + $this->num_row;
            } else {
                $p_start = $i;
                $p_end = $this->num_row;
            }
            if ($i != $this->page) {
                if ($i > ($this->page - $this->nav_len) and $i < ($this->page + $this->nav_len))
                    $navigat.=PHPShopText::a($this->objPath . $i . '.html', $p_start . '-' . $p_end) . ' / ';
                else if ($i - ($this->page + $this->nav_len) < 3 and (($this->page - $this->nav_len) - $i) < 3)
                    $navigat.=".";
            }
            else
                $navigat.=PHPShopText::b($p_start . '-' . $p_end . ' / ');
            $i++;
        }

        // Расчет навигации вперед и назад
        if ($num > 1) {
            if ($this->page >= $num) {
                $p_to = $i - 1;
                $p_do = $this->page - 1;
            } else {
                $p_to = $this->page + 1;
                $p_do = 1;
            }

            $nav = $this->getValue('lang.page_now') . ': ';
            $nav.=PHPShopText::a($this->objPath . ($p_do) . '.html', '&laquo;&laquo;&nbsp;', '&laquo; ' . $this->lang('nav_back'));
            $nav.=' / ' . $navigat . '&nbsp';
            $nav.=PHPShopText::a($this->objPath . ($p_to) . '.html', '&raquo;&raquo;&nbsp;', $this->lang('nav_forw') . ' &raquo;');
            $this->set('productPageNav', $nav);
        }
    }

    /**
     * Выдача подробного описания
     * @param array $select имена колонок БД для выборки
     * @param array $where параметры условий запроса
     * @param array $order параметры сортировки данных при выдаче
     * @return array
     */
    function getFullInfoItem($select, $where, $class_name = false, $function_name = false) {
        $result = $this->PHPShopOrm->select($select, $where, false, array('limit' => '1'), $class_name, $function_name);
        return $result;
    }

    /**
     * Добавление данных в вывод парсера
     * @param string $template шаблон для парсинга
     * @param bool $mod работа в модуле
     */
    function addToTemplate($template, $mod = false) {
        if ($mod)
            $template_file = $template;
        else
            $template_file = $this->getValue('dir.templates') . chr(47) . $_SESSION['skin'] . chr(47) . $template;
        if (is_file($template_file)) {
            $this->ListInfoItems.=ParseTemplateReturn($template, $mod);
            $this->set('pageContent', $this->ListInfoItems);
        }else
            $this->setError("addToTemplate", $template_file);
    }

    /**
     * Добавление данных
     * @param string $content содержание
     * @param bool $list [1] - добавление в список данных, [0] - добавление в общую переменную вывода
     */
    function add($content, $list = false) {
        if ($list)
            $this->ListInfoItems.=$content;
        else
            $this->Disp.=$content;
    }

    /**
     * Парсинг шаблона и добавление в общую переменную вывода
     * @param string $template имя шаблона
     * @param bool $mod работа в модуле
     */
    function parseTemplate($template, $mod = false) {
        $this->set('productPageDis', $this->ListInfoItems);
        $this->Disp = ParseTemplateReturn($template, $mod);
    }

    /**
     * Сообщение об ошибке
     * @param string $name имя функции
     * @param string $action сообщение
     */
    function setError($name, $action) {
        echo '<p style="BORDER: #000000 1px dashed;padding-top:10px;padding-bottom:10px;background-color:#FFFFFF;color:000000;font-size:12px">
<img hspace="10" style="padding-left:10px" align="left" src="../phpshop/admpanel/img/i_domainmanager_med[1].gif"
width="32" height="32" alt="PHPShopCore Debug On"/ ><strong>Ошибка обработчика события:</strong> ' . $name . '()
	 <br><em>' . $action . '</em></p>';
    }

    /**
     * Компиляция парсинга
     */
    function Compile() {
        global $PHPShopDebug;

        // Переменная вывода
        $this->set('DispShop', $this->Disp, false, true);

        // Мета
        $this->meta();

        // Дата модификации
        $this->header();

        // Запись файла локализации
        writeLangFile();

        // Вывод в шаблон
        ParseTemplate($this->getValue($this->template));

        // Очистка временных переменных шаблонов
        $this->garbage();
    }

    /**
     * Создание переменной шаблонизатора для парсинга
     * @param string $name имя
     * @param mixed $value значение
     * @param bool $flag [1] - добавить, [0] - переписать
     */
    function set($name, $value, $flag = false) {
        if ($flag)
            $this->SysValue['other'][$name].=$value;
        else
            $this->SysValue['other'][$name] = $value;
    }

    /**
     * Выдача переменной шаблонизатора
     * @param string $name
     * @return string
     */
    function get($name) {
        return $this->SysValue['other'][$name];
    }

    /**
     * Выдача системной переменной
     * @param string $param раздел.имя переменной
     * @return mixed
     */
    function getValue($param) {
        $param = explode(".", $param);

        if (count($param) > 2 and !empty($this->SysValue[$param[0]][$param[1]][$param[2]]))
            return $this->SysValue[$param[0]][$param[1]][$param[2]];

        if (!empty($this->SysValue[$param[0]][$param[1]]))
            return $this->SysValue[$param[0]][$param[1]];
    }

    /**
     * Назначение экшена обработки переменных POST и GET
     */
    function setAction() {

        if (is_array($this->action)) {
            foreach ($this->action as $k => $v) {

                switch ($k) {

                    // Экшен POST
                    case("post"):

                        // Если несколько экшенов
                        if (is_array($v)) {
                            foreach ($v as $function)
                                if (!empty($_POST[$function]) and $this->isAction($function))
                                    return call_user_func(array(&$this, $this->action_prefix . $function));
                        } else {
                            // Если один экшен
                            if (!empty($_POST[$v]) and $this->isAction($v))
                                return call_user_func(array(&$this, $this->action_prefix . $v));
                        }
                        break;

                    // Экшен GET
                    case("get"):

                        // Если несколько экшенов
                        if (is_array($v)) {
                            foreach ($v as $function)
                                if (!empty($_GET[$function]) and $this->isAction($function))
                                    return call_user_func(array(&$this, $this->action_prefix . $function));
                        } else {
                            // Если один экшен
                            if (!empty($_GET[$v]) and $this->isAction($v))
                                return call_user_func(array(&$this, $this->action_prefix . $v));
                        }

                        break;

                    // Экшен NAME
                    case("name"):

                        // Если несколько экшенов
                        if (is_array($v)) {
                            foreach ($v as $function)
                                if ($this->PHPShopNav->getName() == $function and $this->isAction($function))
                                    return call_user_func(array(&$this, $this->action_prefix . $function));
                        } else {
                            // Если один экшен
                            if ($this->PHPShopNav->getName() == $v and $this->isAction($v))
                                return call_user_func(array(&$this, $this->action_prefix . $v));
                        }

                        break;


                    // Экшен NAV
                    case("nav"):

                        // Если несколько экшенов
                        if (is_array($v)) {
                            foreach ($v as $function) {
                                if ($this->PHPShopNav->getNav() == $function and $this->isAction($function)) {
                                    return call_user_func(array(&$this, $this->action_prefix . $function));
                                    $call_user_func = true;
                                }
                            }
                            if (empty($call_user_func)) {
                                if ($this->isAction('index'))
                                    call_user_func(array(&$this, $this->action_prefix . 'index'));
                                else
                                    $this->setError($this->action_prefix . "index", "метод не существует");
                            }
                        } else {
                            // Если один экшен
                            if ($this->PHPShopNav->getNav() == $v and $this->isAction($v))
                                return call_user_func(array(&$this, $this->action_prefix . $v));
                            elseif ($this->isAction('index'))
                                call_user_func(array(&$this, $this->action_prefix . 'index'));
                            else
                                $this->setError($this->action_prefix . "phpshop" . $this->PHPShopNav->getPath() . "->index", "метод не существует");
                        }

                        break;
                }
            }
        }else
            $this->setError("action", "экшены объявлена неверно");
    }

    /**
     * Проверка экшена
     * @param string $method_name имя метода
     * @return bool
     */
    function isAction($method_name) {
        if (method_exists($this, $this->action_prefix . $method_name))
            return true;
    }

    /**
     * Ожидание экшена
     * @param string $method_name  имя метода
     */
    function waitAction($method_name) {
        if (!empty($_REQUEST[$method_name]) and $this->isAction($method_name))
            call_user_func(array(&$this, $this->action_prefix . $method_name));
    }

    /**
     * Генерация ошибки 404
     */
    function setError404() {

        // Титл
        $this->title = "Ошибка 404  - " . $this->PHPShopSystem->getValue("name");

        // Заголовок ошибки
        header("HTTP/1.0 404 Not Found");
        header("Status: 404 Not Found");

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.error_page_forma'));
    }

    /**
     * Подключение функций из файлов ядра
     * @param string $class_name имя класса
     * @param string $function_name имя функции
     * @param array $function_row массив дополнительны данных из функции
     * @param string $path имя раздела
     * @return mixed
     */
    function doLoadFunction($class_name, $function_name, $function_row = false, $path = false) {

        if (empty($path))
            $path = $GLOBALS['SysValue']['nav']['path'];

        $function_path = './phpshop/core/' . $path . '.core/' . $function_name . '.php';
        if (is_file($function_path)) {
            include_once($function_path);
            if (function_exists($function_name)) {
                return call_user_func_array($function_name, array(&$this, $function_row));
            }
        }
    }

    /**
     * Вывод языкового параметра по ключу [config.ini]
     * @param string $str ключ языкового массива
     * @return string
     */
    function lang($str) {
        if ($this->SysValue['lang'][$str])
            return $this->SysValue['lang'][$str];
        else
            return 'Не определено';
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
     * @param bool $check сравнить с нулем
     * @return
     */
    function memory_get($param, $check = false) {
        $this->memory_clean();
        if (!empty($this->memory)) {
            $param = explode(".", $param);
            if (isset($_SESSION['Memory'][__CLASS__][$param[0]][$param[1]])) {
                if (!empty($check)) {
                    if (!empty($_SESSION['Memory'][__CLASS__][$param[0]][$param[1]]))
                        return true;
                }
                else
                    return $_SESSION['Memory'][__CLASS__][$param[0]][$param[1]];
            }
            elseif (!empty($check))
                return true;
        }
        else
            return true;
    }

    /**
     * Чистка памяти по времени
     * @param bool $clean_now принудительная чистка
     */
    function memory_clean($clean_now = false) {
        if (!empty($_SESSION['Memory'])) {
            if (!empty($clean_now))
                unset($_SESSION['Memory'][__CLASS__]);
            elseif (@$_SESSION['Memory'][__CLASS__]['time'] < (time() - 60 * 10))
                unset($_SESSION['Memory'][__CLASS__]);
        }
    }

    /**
     * Назначение перехвата события выполнения модулем
     * @param string $class_name имя класса
     * @param string $function_name имя метода
     * @param mixed $data данные для обработки
     * @param string $rout позиция вызова к функции [END | START | MIDDLE], по умолчанию END
     * @return bool
     */
    function setHook($class_name, $function_name, $data = false, $rout = false) {
        return $this->PHPShopModules->setHookHandler($class_name, $function_name, array(&$this), $data, $rout);
    }

    /**
     * Сообщение
     * @param string $title заголовок
     * @param string $content содержание
     * @return string
     */
    function message($title, $content) {
        $message = PHPShopText::b(PHPShopText::notice($title, false, '14px')) . PHPShopText::br();
        $message.=PHPShopText::message($content, false, '12px', 'black');
        return $message;
    }

    /**
     * Очистка временных переменных
     */
    function garbage() {
        if ($this->garbage_enabled) {
            timer('start', 'Garbage');
            unset($this->SysValue['other']);
            timer('end', 'Garbage');
        }
    }

}

?>