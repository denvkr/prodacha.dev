<?php

class PHPShopPartner extends PHPShopCore {

    /**
     * Конструктор
     */
    function PHPShopPartner() {


        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['partner']['partner_users'];
        $this->debug = false;

        // Список экшенов
        $this->action = array("nav" => array("register", "sendpassword"),
            'post' => array("add_user", "update_user", "exit_user", "enter_user", "send_user", "addmoney_user", "key_id", "key_add"),
            'get' => 'activation');

        $this->icon = 'phpshop/modules/partner/templates/message.gif';
        $this->system();
        parent::PHPShopCore();
    }

    /**
     * Настройка 
     */
    function system() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['partner']['partner_system']);
        $this->data = $PHPShopOrm->select();
    }

    function addmoney_user() {

        if (PHPShopSecurity::true_num($_SESSION['partnerId']) and PHPShopSecurity::true_num($_POST['get_money_new'])) {

            // Проверка валидности суммы на счете партнера
            if ($_SESSION['partnerTotal'] < $_POST['get_money_new']) {
                $notice = PHPShopText::notice($GLOBALS['SysValue']['lang']['partner_notice'], $this->icon);
            } else {
                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['partner']['partner_payment']);
                $PHPShopOrm->debug = false;
                $PHPShopOrm->insert(array('date_new' => time(), 'sum_new' => $_POST['get_money_new'], 'partner_id_new' => $_SESSION['partnerId']));
                $notice = PHPShopText::message($GLOBALS['SysValue']['lang']['partner_money_done'], $this->icon);
                PHPShopObj::loadClass("mail");

                // Сообщение администратору о заявке на вывод
                new PHPShopMail($this->PHPShopSystem->getValue('adminmail2'), $_SESSION['partnerMail'],
                                $this->PHPShopSystem->getValue('name') . ' - заявка на вывод средств от ' . $_SESSION['partnerName'],
                                $GLOBALS['SysValue']['lang']['partner_money_mail'] . ' ' . $_POST['get_money_new'] . ' ' . $this->PHPShopSystem->getDefaultValutaCode());
            }
            unset($_POST['get_money_new']);
            $this->index($notice);
        }
        else
            header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    /**
     * Расчет бонуса от суммы заказа
     * @param array $order
     * @return float
     */
    function getSum($order, $percent, $enabled) {
        $sum = 0;
        $order = unserialize($order);
        if (is_array($order['Cart']['cart']))
            foreach ($order['Cart']['cart'] as $val)
                $sum+=$val['num'] * $val['price'];
        $format = $this->PHPShopSystem->getSerilizeParam("admoption.price_znak");

        if (empty($enabled))
            $sum = 0;
        else
            $sum = $sum * $percent / 100;

        return number_format($sum, $format, '.', '');
    }

    /**
     * Экшен удаления ключа 
     */
    function key_id() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['partner']['partner_key']);
        $PHPShopOrm->delete(array('id' => '=' . intval($_POST['key_id'])));
        $notice = PHPShopText::notice(__('Ключ удален'), $this->icon);
        $this->index($notice);
    }

    /**
     * Экшен добавления ключа
     */
    function key_add() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['partner']['partner_key']);
        $PHPShopOrm->debug = false;
        $url = parse_url($_POST['key_add']);

        $true_url = PHPShopSecurity::TotalClean($url['scheme'] . '://' . $url['host'], 2);

        if (!empty($url['host'])) {

            // Проверка на уникальность адреса
            $present_url = $PHPShopOrm->select(array('id'), array('url' => '="' . $true_url . '"', 'partner_id' => '=' . intval($_SESSION['partnerId'])), false, array('limit' => 1));

            if (empty($present_url)) {
                $key = strtoupper(md5(time() . rand(0, 1000)));
                $PHPShopOrm->insert(array('date_new' => time(), 'url_new' => $true_url, 'partner_id_new' => intval($_SESSION['partnerId']),
                    'url_key_new' => substr($key, 0, 10)));
                $notice = PHPShopText::message(__('Создан ключ для <b>' . $true_url . '</br>'), $this->icon);
            }
            else
                $notice = PHPShopText::notice(__('Домен <b>' . $true_url . '</b> уже зарегистрирован'), $this->icon);
        }
        else
            $notice = PHPShopText::notice(__('Неверно указан адрес сайта'), $this->icon);

        $this->index($notice);
    }

    /**
     * Экшен по умолчанию, личный кабинет
     */
    function index($notice = false) {
        if (PHPShopSecurity::true_num($_SESSION['partnerId'])) {

            // Библиотека графики
            PHPShopObj::loadClass("admgui");
            $PHPShopInterface = new PHPShopFrontInterface();
            $PHPShopInterface->css = 'phpshop/modules/partner/templates/phpshop-gui.css';
            $PHPShopInterface->js = 'phpshop/modules/partner/templates/phpshop-gui.js';
            $PHPShopInterface->imgPath = 'phpshop/admpanel/img/';

            /**
             * Заявки на вывод
             */
            $PHPShopInterface->setCaption(array("&plusmn;", "10%"), array("Дата", "20%"), array("Сумма (" . $this->PHPShopSystem->getDefaultValutaCode() . ')', "30%"));
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['partner']['partner_payment']);
            $PHPShopOrm->debug = false;
            $data = $PHPShopOrm->select(array('*'), array('partner_id' => "='" . $_SESSION['partnerId'] . "'"), array('order' => 'id desc'), array('limit' => 300));
            if (is_array($data))
                foreach ($data as $row) {
                    extract($row);
                    $sum = number_format($sum, "2", ".", "");

                    // Дата выполнения
                    if (!empty($enabled))
                        $date = $date_done;

                    $PHPShopInterface->setRow($id, $PHPShopInterface->icon($enabled), PHPShopDate::dataV($date), $sum);
                }
            $Tab1 = $PHPShopInterface->Compile();
            $PHPShopInterface->imgPath = 'phpshop/modules/partner/templates/';
            $Tab2 = $PHPShopInterface->setInputText('Сумма', 'get_money_new', round($_SESSION['partnerTotal'] / 2), 50, $this->PHPShopSystem->getDefaultValutaCode());
            $Tab2.=$PHPShopInterface->setInput('submit', 'addmoney_user', 'Подать заявку');
            $Tab2 = $PHPShopInterface->setForm($Tab2);


            /**
             * Заказы рефералов
             */
            $PHPShopTableOrders = new PHPShopFrontInterface();
            $PHPShopTableOrders->imgPath = 'phpshop/admpanel/img/';
            $PHPShopTableOrders->setCaption(array("&plusmn;", "10%"), array("Дата", "15%"), array("№ Заказа", '15%'), array("Бонус (" . $this->PHPShopSystem->getDefaultValutaCode() . ')', "20%"), array("%", "10%"));

            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['partner']['partner_log']);
            $PHPShopOrm->debug = false;
            $result = $PHPShopOrm->query('SELECT a.*, b.orders FROM ' . $GLOBALS['SysValue']['base']['partner']['partner_log'] . ' AS a
            JOIN ' . $GLOBALS['SysValue']['base']['orders'] . ' AS b ON a.order_id = b.uid where a.partner_id=' . $_SESSION['partnerId'] . ' order by a.id desc limit 0,100');
            while ($row = mysql_fetch_array($result)) {
                $PHPShopTableOrders->setRow($row['id'], $PHPShopTableOrders->icon($row['enabled']), PHPShopDate::dataV($row['date']), $row['order_id'], $this->getSum($row['orders'], $row['percent'], $row['enabled']), $row['percent']);
            }
            $Tab3 = $PHPShopTableOrders->Compile();


            /**
             * Настройки пользователя
             */
            $PHPShopOrm = new PHPShopOrm($this->objBase);
            $row = $PHPShopOrm->select(array('*'), array('id' => "='" . $_SESSION['partnerId'] . "'", 'enabled' => "='1'"));
            if (is_array($row)) {

                // Данные пользователя
                $this->set('userName', $row['login']);
                $this->set('userMail', $row['mail']);
                $this->set('userDate', $row['date']);

                if ($row['money'] > 0)
                    $this->set('userMoney', PHPShopText::a('javascript:tabPane.setSelectedIndex(3);', $row['money'] . ' ' .
                                    $this->PHPShopSystem->getDefaultValutaCode(), 'Вывести средства', 'green', $size = 17));
                else
                    $this->set('userMoney', PHPShopText::b('0 ') . $this->PHPShopSystem->getDefaultValutaCode());

                $_SESSION['partnerTotal'] = $row['money'];
                $_SESSION['partnerMail'] = $row['mail'];
                $this->set('partnerId', $_SESSION['partnerId']);

                // Дополнительные поля
                $content = unserialize($row['content']);
                $dop = null;

                if (is_array($content))
                    foreach ($content as $k => $v) {
                        $this->set('userAddName', str_replace('dop_', '', $k));
                        $this->set('userAddValue', $v);
                        $dop.=ParseTemplateReturn($GLOBALS['SysValue']['templates']['partner']['partner_forma_dop_content'], true);
                    }

                // Определяем переменные
                $this->set('userContent', $dop);
                $Tab4 = ParseTemplateReturn($GLOBALS['SysValue']['templates']['partner']['partner_forma_enter'], true);
            }


            /**
             * Рекламные материалы
             */
            $this->set('serverName', $this->convert($_SERVER['SERVER_NAME']));
            $Tab5 = ParseTemplateReturn($GLOBALS['SysValue']['templates']['partner']['partner_forma_docs'], true);


            /**
             * Ключи 
             */
            $PHPShopTableKeys = new PHPShopFrontInterface();
            $PHPShopTableKeys->imgPath = 'phpshop/admpanel/img/';
            $PHPShopTableKeys->setCaption(array("Дата", "15%"), array("URL", '30%'), array("KEY", '30%'), array('Действие', '15%'));

            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['partner']['partner_key']);
            $PHPShopOrm->debug = $this->debug;
            $data = $PHPShopOrm->select(array('*'), array('partner_id' => "=" . intval($_SESSION['partnerId'])), array('order' => 'date desc'), array('limit' => 100));
            if (is_array($data)) {
                foreach ($data as $row) {
                    $PHPShopTableKeys->setRow($row['id'], PHPShopDate::dataV($row['date']), $row['url'], $row['url_key'], $PHPShopTableKeys->setForm(
                                    $PHPShopTableKeys->setInput('hidden', 'key_id', $row['id']) .
                                    $PHPShopTableKeys->setInput('submit', 'key_edit', 'Удалить', $float = "none", $size = 80)
                            )
                    );
                }
                $Tab6 = $PHPShopTableKeys->Compile();
            }


            // Форма добавления нового ключа
            $Tab6.=$PHPShopTableKeys->setForm(
                    $PHPShopTableKeys->setInput('text', 'key_add', 'http://seamply.ru', 'left', $size = 150, 'this.value=\'\'') .
                    $PHPShopTableKeys->setInput('submit', 'key_edit', 'Добавить', $float = "left", $size = 80)
            );


            // Форма закладок навигации
            $PHPShopFrontGUI = new PHPShopFrontInterface();
            $TabName = explode("|", $GLOBALS['SysValue']['lang']['partner_menu']);

            // Вывод табов сучетом опции проверки ключей
            if ($this->data['key_enabled'] == 1) 
            $Forma = $PHPShopFrontGUI->getContent($PHPShopFrontGUI->setTab(array($TabName[0], $Tab4, 550), array($TabName[1], $Tab3, 550), array($TabName[2], $Tab1, 550), array($TabName[3], $Tab2, 550), array($TabName[4], $Tab5, 700), array($TabName[5], $Tab6, 600)));
            else 
                $Forma = $PHPShopFrontGUI->getContent($PHPShopFrontGUI->setTab(array($TabName[0], $Tab4, 550), array($TabName[1], $Tab3, 550), array($TabName[2], $Tab1, 550), array($TabName[3], $Tab2, 550), array($TabName[4], $Tab5, 700)));
           
            
            // Подключаем шаблон
            $this->set('pageContent', $notice . $Forma);
            $this->set('pageTitle', $GLOBALS['SysValue']['lang']['partner_path_name']);

            // Мета
            $this->title = $GLOBALS['SysValue']['lang']['partner_path_name'] . " - " . $this->PHPShopSystem->getValue("name");

            // Подключаем шаблон
            $this->parseTemplate($this->getValue('templates.page_page_list'));
        }

        else
            $this->enter();
    }

    /**
     * Экшен форма авторизации
     */
    function enter($activation = false) {

        if (!empty($activation))
            $this->set('activationNotice', PHPShopText::notice($GLOBALS['SysValue']['lang']['partner_activation_notice']));

        // Определяем переменные
        $this->set('pageContent', ParseTemplateReturn($GLOBALS['SysValue']['templates']['partner']['partner_forma'], true));
        $this->set('pageTitle', __('Авторизация партнера'));

        // Мета
        $this->title = __("Кабинет партнера - Авторизация - "). $this->PHPShopSystem->getValue("name");

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * Экшен прокерки активации
     */
    function activation() {
        $activation = PHPShopSecurity::TotalClean($_GET['activation'], 4);
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->debug = $this->debug;
        $row = $PHPShopOrm->select(array('id,login'), array('activation' => "='" . $activation . "'"), false, array('limit' => 1));
        if (!empty($row['login'])) {

            // Включение пользователя
            $PHPShopOrm = new PHPShopOrm($this->objBase);
            $PHPShopOrm->debug = $this->debug;
            $PHPShopOrm->update(array('enabled_new' => '1', 'activation_new' => 'done'), array('id' => '=' . $row['id']));

            $_SESSION['partnerName'] = $row['login'];
            $_SESSION['partnerId'] = $row['id'];
        }

        // Переход в личный кабинет
        $this->index();
    }

    /**
     * Экшен входа
     */
    function enter_user() {

        if (PHPShopSecurity::true_login($_POST['plogin']) and PHPShopSecurity::true_passw($_POST['ppassword'])) {

            $PHPShopOrm = new PHPShopOrm($this->objBase);
            $PHPShopOrm->debug = $this->debug;
            $row = $PHPShopOrm->select(array('id,login'), array('enabled' => "='1'", 'login' => "='" . $_POST['plogin'] . "'",
                'password' => "='" . base64_encode($_POST['ppassword']) . "'"), false, array('limit' => 1));

            if (!empty($row['id'])) {
                $_SESSION['partnerName'] = $row['login'];
                $_SESSION['partnerId'] = $row['id'];
            }

            $this->index();
        }
    }

    /**
     * Экшен выхода
     */
    function exit_user() {
        $_SESSION['partnerName'] = null;
        $_SESSION['partnerId'] = null;
        $this->index();
    }

    /**
     * Есть ли пользователь в базе
     * @param string $login имя пользователя
     * @return bool
     */
    function chek($login) {
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->debug = $this->debug;
        $num = $PHPShopOrm->select(array('id'), array('login' => "='$login'"), false, array('limit' => 1));
        if (empty($num['id']))
            return true;
    }

    /**
     *  Экшен потеря пароля
     */
    function sendpassword() {

        // Определяем переменные
        $this->set('pageContent', ParseTemplateReturn($GLOBALS['SysValue']['templates']['partner']['partner_forma_lost'], true));
        $this->set('pageTitle', 'Напоминание пароля');

        // Мета
        $this->title = __("Кабинет партнера - Напоминание пароля - ") . $this->PHPShopSystem->getValue("name");

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * Конвертирование РФ зоны
     * @param string $server_name имя сервера в кодировке Idna
     */
    function convert($server_name) {
        if (strpos($server_name, '--')) {
            if (function_exists('iconv')) {
                $dom_name = iconv("windows-1251", "UTF-8", $server_name);
            } else {
                PHPShopObj::loadClass('string');
                $dom_name = PHPShopString::win_utf8($server_name);
            }

            if (is_file('./phpshop/lib/convert/idna_convert.class.php')) {
                require_once './phpshop/lib/convert/idna_convert.class.php';
                $IDN = new idna_convert();
                if (function_exists('iconv')) {
                    $convert = iconv("UTF-8", "windows-1251", ($IDN->decode($dom_name)));
                }
                else
                    $convert = PHPShopString::utf8_win1251($IDN->decode($dom_name));
            } else
                $convert = $server_name;
        }
        else
            $convert = $server_name;
        return $convert;
    }

    /**
     * Экшен отправка пароля
     */
    function send_user() {

        $mail = PHPShopSecurity::TotalClean($_POST['mail'], 3);
        $login = PHPShopSecurity::TotalClean($_POST['login'], 2);

        // Выборка данных
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->Option['where'] = ' OR ';
        $row = $PHPShopOrm->select(array('*'), array('login' => "='" . $login . "'", 'mail' => "='" . $mail . "'"), false, array('limit' => 1));

        if (!empty($row['login'])) {

            PHPShopObj::loadClass("mail");
            $zag = __("Напоминание пароля в ") . $this->PHPShopSystem->getValue("name");
            $content = __('Доброго времени, ') . $row['login'] . '
----------------

Для доступа к сайту http://' . $this->convert($_SERVER['SERVER_NAME']) .$this->getValue('dir.dir'). '/partner/ используйте данные:
Логин: ' . $row['login'] . '
Пароль: ' . base64_decode($row['password']) . '

---
';

            // Сообщение пользователю
            $PHPShopMail = new PHPShopMail($row['mail'], $this->PHPShopSystem->getValue("admin_mail"), $zag, $content);
        }
    }

    /**
     *  Экшен форма регистрации
     */
    function register() {

        // Определяем переменные
        $this->set('pageContent', ParseTemplateReturn($GLOBALS['SysValue']['templates']['partner']['partner_forma_register'], true));
        $this->set('pageTitle', __('Регистрация партнера'));

        // Мета
        $this->title = __("Кабинет партнера - Регистрация - ") . $this->PHPShopSystem->getValue("name");

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * Экшен смены данных пользователя
     */
    function update_user() {

        $mail = PHPShopSecurity::TotalClean($_POST['mail'], 3);
        $password = PHPShopSecurity::TotalClean($_POST['password'], 2);

        if (PHPShopSecurity::true_param($mail)) {

            $PHPShopOrm = new PHPShopOrm($this->objBase);
            $PHPShopOrm->debug = $this->debug;

            // Дополнительные поля dop_
            foreach ($_POST as $v => $k)
                if (strstr($v, 'dop'))
                    $dop[$v] = $k;


            $update_var = array(
                'mail_new' => $mail,
                'content_new' => serialize($dop)
            );

            if (!empty($password))
                $update_var['password_new'] = base64_encode($password);

            $PHPShopOrm->debug = false;
            $PHPShopOrm->update($update_var, array('id' => '=' . $_SESSION['partnerId']));

            $notice = PHPShopText::message($GLOBALS['SysValue']['lang']['partner_update'], $this->icon);
        }
        else
            $notice = PHPShopText::message($GLOBALS['SysValue']['lang']['partner_error'], $this->icon);

        $this->index($notice);
    }

    /**
     * Экшен записи пользователя
     */
    function add_user() {
        $mes = null;
        $dop = array();

        $mail = PHPShopSecurity::TotalClean($_POST['mail'], 3);
        $password = PHPShopSecurity::TotalClean($_POST['ppassword'], 2);
        $login = PHPShopSecurity::TotalClean($_POST['plogin'], 2);

        if (PHPShopSecurity::true_param($mail, $password, $login))
            if (PHPShopSecurity::true_login($login) and PHPShopSecurity::true_email($mail)) {

                // проверка на уникальность имени
                if ($this->chek($login)) {
                    $PHPShopOrm = new PHPShopOrm($this->objBase);
                    $PHPShopOrm->debug = $this->debug;

                    // Защитный код
                    $check_text = md5(rand(0, 1000));

                    // Дополнительные поля dop_
                    foreach ($_POST as $v => $k)
                        if (strstr($v, 'dop'))
                            $dop[$v] = $k;


                    $PHPShopOrm->insert(array('date' => date("d-m-y"), 'mail' => $mail, 'login' => $login, 'password' => base64_encode($password), 'activation' => $check_text, 'content' => serialize($dop)), $prefix = '');

                    // Сообщение с активацией
                    PHPShopObj::loadClass("mail");
                    $zag = __("Регистрация в ") . $this->PHPShopSystem->getValue("name");
                    $content = 'Доброго времени
----------------

Для активации партнера ' . $login . ' перейдите по ссылке: http://' . $this->convert($_SERVER['SERVER_NAME']).$this->getValue('dir.dir') . '/partner/?activation=' . $check_text . '

Для доступа к сайту http://' . $this->convert($_SERVER['SERVER_NAME']).$this->getValue('dir.dir') . '/partner/ после активации используйте данные:
Логин: ' . $login . '
Пароль: ' . $password . '

---
';

                    // Сообщение пользователю
                    $PHPShopMail = new PHPShopMail($mail, $this->PHPShopSystem->getValue("admin_mail"), $zag, $content);

                    // Переход в личный кабинет
                    $this->enter(true);
                }
                else
                    $mes = 'Партнер с таким именем уже зарегистрирован';
            }
            else
                $mes = 'Ошибка заполнения формы регистрации';

        // Еще попытка
        if (!empty($mes)) {

            $this->set('mesageText', $mes);


            // Мета
            $this->title = "Кабинет партнера - Регистрация - " . $this->PHPShopSystem->getValue("name");

            // Определяем переменные
            $this->set('pageContent', ParseTemplateReturn($GLOBALS['SysValue']['templates']['partner']['partner_forma_register'], true));
            $this->set('pageTitle', 'Регистрация партнера');

            // Подключаем шаблон
            $this->parseTemplate($this->getValue('templates.page_page_list'));
        }
    }

}

?>