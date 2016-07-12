<?php

// Библиотеки
PHPShopObj::loadClass('user');
PHPShopObj::loadClass('mail');
PHPShopObj::loadClass('order');
PHPShopObj::loadClass('delivery');

/**
 * Обработчик кабинета пользователя
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopUsers
 * @version 1.2
 * @package PHPShopCore
 */
class PHPShopUsers extends PHPShopCore {

    /**
     * Конструктор
     */
    function PHPShopUsers() {

        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['shopusers'];

        // Отладка
        $this->debug = false;

        // Список экшенов
        $this->action = array('get' => array('productId', 'noticeId'), 'post' => array('add_notice', 'update_password', 'add_user', 'update_user', 'passw_send'),
            'name' => array('register', 'order', 'useractivate', 'sendpassword', 'notice', 'message'), 'nav' => 'index');

        // Префикс для экшенов методов
        $this->action_prefix = 'action_';

        // Локализация
        $this->locale = array(
            'error_key' => __('Некорректный ключ'),
            'error_id' => __('Пользователь с таким логином уже существует'),
            'error_password' => __('Пароли не совпадают'),
            'error_login' => __('Некорректный логин'),
            'error_password_hack' => __('Некорректный пароль'),
            'error_password_hack1' => __('Некорректный пароль1'),
            'error_password_hack2' => __('Некорректный пароль2'),
            'error_mail' => __('Некорректный e-mail'),
            'error_name' => __('Некорректное имя'),
            'error_lastname' => __('Некорректная фамилия'),
            'done' => __('Данные изменены'),
            'activation_title' => __('Активация регистрации пользователя'),
            'activation_admin_title' => __('Активация регистрации пользователя'),
            'order_info' => __('Информация о заказе') . ' №',
            'order_table_title_1' => '№ ' . __('Заказа'),
            'order_table_title_2' => __('Дата'),
            'order_table_title_3' => __('Кол-во'),
            'order_table_title_4' => __('Скидка') . ' %',
            'order_table_title_5' => __('Сумма'),
            'order_table_title_6' => __('Статус'),
            'user' => __('Авторизованный пользователь')
        );

        parent::PHPShopCore();
    }

    /**
     * Экшен по умочанию
     */
    function action_index() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        // Проверка прохождения авторизации
        if ($this->true_user()) {

            // Форма редактирования персональных данных
            $this->user_info();
        } else {
            // Форма регистрации нового пользователя
            $this->action_register();
        }
    }

    /**
     * Экшен записи нового уведомления
     */
    function action_add_notice() {
        if ($this->true_user()) {
            if (PHPShopSecurity::true_num($_POST['productId'])) {
                $this->notice_add();
            }
            $this->action_notice();
        } else {
            // Форма регистрации нового пользователя
            $this->action_register();
        }
    }

    /**
     * Экшен списка всех сообщений
     */
    function action_message() {
        if ($this->true_user()) {
            $this->user_message();
        } else {
            // Форма регистрации нового пользователя
            $this->action_register();
        }
    }

    /**
     * Вывод списка сообщений
     * Функция вынесена в отдельный файл users.core/user_message.php
     */
    function user_message() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__);
    }

    /**
     * Экшен списка всех уведомлений
     */
    function action_notice() {
        if ($this->true_user()) {
            $this->notice_list();
        } else {
            // Форма регистрации нового пользователя
            $this->action_register();
        }
    }

    /**
     * Вывод списка уведомлений
     */
    function notice_list() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__);
    }

    /**
     * Запись нового уведомления
     */
    function notice_add() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__);
    }

    /**
     * Экшен удаления уведомления
     */
    function action_noticeId() {
        if ($this->true_user()) {
            if (PHPShopSecurity::true_num($_GET['noticeId'])) {
                $PHPShopOrm = new PHPShopOrm($this->getValue('base.notice'));
                $PHPShopOrm->debug = $this->debug;
                $PHPShopOrm->delete(array('user_id' => '=' . $this->UsersId, 'id' => '=' . $_GET['noticeId']));
                $this->action_notice();
            }
            else
                $this->setError404();
        }
        else {
            // Форма регистрации нового пользователя
            $this->action_register();
        }
    }

    /**
     * Экшен форма уведомления
     */
    function action_productId() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        if (PHPShopSecurity::true_num($_GET['productId'])) {
            $PHPShopProduct = new PHPShopProduct($_GET['productId']);
            if (PHPShopSecurity::true_num($PHPShopProduct->getParam('id'))) {
                $this->set('productId', $_GET['productId']);
                $this->set('pic_small', $PHPShopProduct->getParam('pic_small'));
                $this->set('name', $PHPShopProduct->getParam('name'));

                // Перехват модуля
				
                $this->setHook(__CLASS__,__FUNCTION__,$PHPShopProduct,'MIDDLE');
				
				//если не зарегин юзер - делаем ему предложение :) nah								
				
				if   (isset($_SESSION['UsersId']))  
                	$this->set('formaContent',ParseTemplateReturn('phpshop/lib/templates/users/notice.tpl',true));
				else if (strpos($_SERVER['HTTP_REFERER'],"useractivate.html?key")!==false)
					$this->set('formaContent',ParseTemplateReturn('phpshop/lib/templates/users/notice_auth_wr.tpl',true));
				else
				 {	
					
					$this->set('formaContent',ParseTemplateReturn('phpshop/lib/templates/users/notice_auth.tpl',true));
					
					session_start();
					$_SESSION['notice_url']=$_SERVER['REQUEST_URI'];
					// $this->set('formaContent2',ParseTemplateReturn('phpshop/lib/templates/users/register.tpl',true));
				}	
					
				
                $this->set('formaTitle',__('Уведомления'));

                // Перехват модуля
                $this->setHook(__CLASS__, __FUNCTION__, $PHPShopProduct, 'END');

                $this->ParseTemplate($this->getValue('templates.users_page_list'));
            }
            else
                $this->setError404();
        }
    }

    /**
     * Вывод подробных данных по заказам
     * Функция вынесена в отдельный файл users.core/order_info.php
     * @return mixed
     */
    function action_order_info() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__, $tip = 1);
    }

    /**
     * Вывод таблицы заказов пользователя
     * Функция вынесена в отдельный файл users.core/order_list.php
     * @return mixed
     */
    function order_list() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__, $tip = 1);
    }

    /**
     * Шифрование ссылки на файлы
     * @param string $files имя файла
     * @return string 
     */
    function link_encode($files) {
        $str = array(
            "files" => $files,
            "time" => (time("U") + ($this->getValue('my.digital_time') * 86400))
        );
        $str = serialize($str);
        $code = base64_encode($str);
        $code2 = str_replace($this->getValue('my.digital_pass1'), "!", $code);
        $code2 = str_replace($this->getValue('my.digital_pass2'), "$", $code2);

        return $code2;
    }

    /**
     * Чистка старых активаций
     */
    function clean_old_activation() {
        $nowData = time() - 432000;
        $this->PHPShopOrm->delete(array('datas' => '<' . $nowData, 'enabled' => "='0'"));
        $this->PHPShopOrm->clean();
    }

    /**
     * Проверка ключа активации
     */
    function true_key($passw) {
        return preg_match("/^[a-zA-Z0-9_]{4,35}$/", $passw);
    }

    /**
     * Экшен активации по ключу
     */
    function action_useractivate() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        if ($this->true_key($_GET['key'])) {

            // Чистка старых активаций
            $this->clean_old_activation();

            $data = $this->PHPShopOrm->select(array('login','name'), array('status' => "='" . $_GET['key'] . "'"), false, array('limit' => 1));
            if (!empty($data['login'])) {

                $this->set('date', date("d-m-y H:i a"));
                $this->set('user_ip', $_SERVER['REMOTE_ADDR']);
                $name=split(' ',$data['name']);
                $this->set('user_name', str_replace('_','',$data['name']));//$name[1]
                $this->set('user_login', $data['login']);

                if (!$this->PHPShopSystem->ifSerilizeParam('admoption.user_mail_activate_pre')) {

                    $this->PHPShopOrm->clean();

                    $this->PHPShopOrm->update(array('enabled_new' => '1', 'status_new' => $this->PHPShopSystem->getSerilizeParam('admoption.user_status')), array('status' => "='" . $_GET['key'] . "'"));

					if (isset($_SESSION['notice_url']))
					{
						//$message="Теперь вы можете оставить зявку об извещении, перейдя по  <a href='".$_SESSION['notice_url']."'>ссылке</a>.";
						 $this->set('formaContent',ParseTemplateReturn('phpshop/lib/templates/users/message_activation_done1.tpl',true));
						  $this->set('formaContent2',ParseTemplateReturn('phpshop/lib/templates/users/message_activation_done2.tpl',true));
						//$this->set('formaContent2',$message);						
						}
					else		
						$this->set('formaContent',ParseTemplateReturn('phpshop/lib/templates/users/message_activation_done.tpl',true));
												
                } else {

                    $this->PHPShopOrm->clean();

                    $this->PHPShopOrm->update(array('status_new' => $this->PHPShopSystem->getSerilizeParam('admoption.user_status')), array('status' => "='" . $_GET['key'] . "'"));

                    // Заголовок e-mail администратору
                    $title = $this->PHPShopSystem->getName() . " - " . $obj->locale['activation_admin_title'] . " " . $_POST['name_new'];

                    // Содержание e-mail администратору
                    $content = ParseTemplateReturn('./phpshop/lib/templates/users/mail_admin_activation.tpl', true);

                    // Отправка e-mail администратору
                    $PHPShopMail = new PHPShopMail($this->PHPShopSystem->getValue('adminmail2'), $_POST['mail_new'], $title, $content);

                    $this->set('formaContent', ParseTemplateReturn('phpshop/lib/templates/users/message_admin_activation.tpl', true), true);
                }
            } else {
                $this->set('formaContent', ParseTemplateReturn('phpshop/lib/templates/users/message_activation_error.tpl', true));
            }

            $this->set('formaTitle', $this->lang('user_register_title'));

            // Перехват модуля
            $this->setHook(__CLASS__, __FUNCTION__, $data, 'END');

            $this->ParseTemplate($this->getValue('templates.users_page_list'));
        }
        else
            $this->action_register();
    }

    /**
     * Экшен вывода заказов
     */
    function action_order() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        if ($this->true_user()) {

            // Форма вывода заказов
            $this->order_list();

            // Ожидание экшена подробного описания заказа
            $this->waitAction('order_info');

            // Перехват модуля
            $this->setHook(__CLASS__, __FUNCTION__, false, 'END');

            $this->ParseTemplate($this->getValue('templates.users_page_list'));
        } else {

            // Форма регистрации нового пользователя
            $this->action_register();
        }
    }

    /**
     * Экшен обновления персональных данных
     */
    function action_update_user() {

        if (PHPShopSecurity::true_num($_SESSION['UsersId'])) {

            if (!PHPShopSecurity::true_email($_POST['mail_new']))
                $this->error[] = $this->locale('error_mail');

            if (strlen(str_replace('_','',$_POST['name_new'])) <2)
                $this->error[] = $this->locale('error_name');
            
            if (strlen(str_replace('_','',$_POST['lastname_new'])) < 2)
                $this->error[] = $this->locale('error_lastname');
            
            if (count($this->error) == 0) {
                $this->PHPShopOrm->update(array(
                    'mail_new' => $_POST['mail_new'],
                    'name_new' => htmlspecialchars($_POST['lastname_new'].' '.$_POST['name_new'],ENT_QUOTES,'cp1251'),
                    'company_new' => htmlspecialchars($_POST['company_new'],ENT_QUOTES,'cp1251'),
                    'inn_new' => htmlspecialchars($_POST['inn_new'],ENT_QUOTES,'cp1251'),
                    'tel_new' => htmlspecialchars($_POST['tel_new'],ENT_QUOTES,'cp1251'),
                    'adres_new' => htmlspecialchars($_POST['adres_new'],ENT_QUOTES,'cp1251'),
                    'kpp_new' => htmlspecialchars($_POST['kpp_new'],ENT_QUOTES,'cp1251'),
                    //'tel_code_new' => htmlspecialchars($_POST['tel_code_new']),
                    ENT_QUOTES,'cp1251'), array('id' => '=' . $_SESSION['UsersId']));
                $this->error[] = $this->locale['done'];

                // Перехват модуля
                $this->setHook(__CLASS__, __FUNCTION__, $_POST);
            }
        }

        // Список ошибок смены данных
        $this->error();

        // Форма персональных данных пользователя
        $this->user_info();
    }

    /**
     * Экшен формы восстановления пароля
     */
    function action_sendpassword() {
        $this->set('formaTitle', __('Восстановление пароля'));
        $this->set('formaContent', ParseTemplateReturn('phpshop/lib/templates/users/sendpassword.tpl', true));
        $this->setHook(__CLASS__, __FUNCTION__);
        $this->ParseTemplate($this->getValue('templates.users_page_list'));
    }

    /**
     * Экшен отправления пароля по почте
     */
    function action_passw_send() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        if (PHPShopSecurity::true_login($_POST['login'])) {
            $this->PHPShopOrm->clean();
            $data = $this->PHPShopOrm->select(array('*'), array('login' => '="' . $_POST['login'] . '"'), false, array('limit' => 1));
            if (is_array($data)) {

                $this->set('date', date("d-m-y H:i a"));
                $this->set('user_ip', $_SERVER['REMOTE_ADDR']);
                $this->set('user_login', $data['login']);
                $this->set('user_mail', $data['mail']);
                $this->set('user_password', $this->decode($data['password']));

                // Заголовок e-mail пользователю
                $title = $this->PHPShopSystem->getName() . " - " . __('Восстановление пароля пользователя') . " " . $_POST['login'];

                // Содержание e-mail пользователю
                $content = ParseTemplateReturn('./phpshop/lib/templates/users/mail_sendpassword.tpl', true);

                // Отправка e-mail пользователю
                $PHPShopMail = new PHPShopMail($data['mail'], 'robot@' . str_replace("www.", "", $_SERVER['SERVER_NAME']), $title, $content);

                // Сообщение об успешном отправлении пароля
                $this->set('formaContent', ParseTemplateReturn('phpshop/lib/templates/users/message_sendpassword.tpl', true));
            } else {
                // Сообщение об успешном отправлении пароля
                $this->set('formaContent', ParseTemplateReturn('phpshop/lib/templates/users/message_sendpassword_error.tpl', true));
            }
        }

        $this->set('formaTitle', __('Личный кабинет'));
        $this->ParseTemplate($this->getValue('templates.users_page_list'));
    }

    /**
     * Экшен обновления пароля пользователя
     */
    function action_update_password() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        //Проверка валидности новых данных
        if (PHPShopSecurity::true_num($_SESSION['UsersId'])) {

            if ($_POST['password_new'] != $_POST['password_new2'])
                $this->error[] = $this->locale('error_password');

            if (!PHPShopSecurity::true_login($_POST['login_new']))
                $this->error[] = $this->locale('error_login');

            if (!PHPShopSecurity::true_passw($_POST['password_new']))
                $this->error[] = $this->locale('error_password_hack');

            if (count($this->error) == 0) {
                $this->PHPShopOrm->update(array('login_new' => $_POST['login_new'],
                    'password_new' => $this->encode($_POST['password_new'])), array('id' => '=' . $_SESSION['UsersId']));
                $this->error[] = $this->locale['done'];
            }
        }

        // Список ошибок смены данных
        $this->error();

        // Форма персональных данных пользователя
        $this->user_info();
    }

    /**
     * Сообщения и ошибки о смене данных пользователем
     */
    function error() {
        $user_error = null;
        if (is_array($this->error))
            foreach ($this->error as $val)
                $user_error.=PHPShopText::ul(PHPShopText::li($val));

        $this->set('user_error', $user_error);
    }

    /**
     * Персональные данные пользователя
     */
    function user_info() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        $this->PHPShopUser = new PHPShopUser($_SESSION['UsersId']);

        // Персональные данные пользователя
        $this->set('user_status', $this->PHPShopUser->getStatusName());

        if ($this->get('user_status') == "")
            $this->set('user_status', __('Авторизованный пользователь'));

        $this->set('user_login', $this->PHPShopUser->getParam('login'));
        $this->set('user_password', $this->decode($this->PHPShopUser->getParam('password')));
        //echo $this->PHPShopUser->getName();
        $this->set('user_name', str_replace('_','',$this->PHPShopUser->getName()));
        //echo $this->PHPShopUser->getLastName();
        $this->set('user_lastname', str_replace('_','',$this->PHPShopUser->getLastName()));
        $this->set('user_mail', $this->PHPShopUser->getParam('mail'));
        $this->set('user_company', $this->PHPShopUser->getParam('company'));
        $this->set('user_inn', $this->PHPShopUser->getParam('inn'));
        $this->set('user_tel', $this->PHPShopUser->getParam('tel_code').$this->PHPShopUser->getParam('tel'));
        //$this->set('user_tel_code', $this->PHPShopUser->getParam('tel_code'));
        $this->set('user_adres', $this->PHPShopUser->getParam('adres'));
        $this->set('user_kpp', $this->PHPShopUser->getParam('kpp'));

        // Форма личного кабинета пользователя
        $this->set('formaTitle', $this->lang('user_info_title'));
        $this->set('formaContent', ParseTemplateReturn('phpshop/lib/templates/users/info.tpl', true));

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $this->PHPShopUser, 'END');

        $this->ParseTemplate($this->getValue('templates.users_page_list'));
    }

    /**
     * Проверки прохождения авторизации
     * @return bool
     */
    function true_user() {

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__);
        if ($hook)
            return $hook;

        if (PHPShopSecurity::true_num($_SESSION['UsersId'])) {
            $this->UsersId = $_SESSION['UsersId'];
            $this->UsersStatus = $_SESSION['UsersStatus'];
            return true;
        }
    }

    /**
     * Кодирование пароля
     * @param string $str пароль
     * @return string
     */
    function encode($str) {

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $str);
        if ($hook)
            return $hook;

        return base64_encode($str);
    }

    /**
     * Декодирование пароля
     * @param string $str пароль
     * @return string
     */
    function decode($str) {

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $str);
        if ($hook)
            return $hook;

        return base64_decode($str);
    }

    /**
     * Проверка нового пользователя
     * @return Bool
     */
    function add_user_check() {

        // Проверка на защитную картинку
        if (empty($_SESSION['text']) or ($_POST['key'] != $_SESSION['text'])) {
            $this->error[] = $this->locale['error_key'];
            return false;
        }

        // Проверка уникальности логина
        if (PHPShopSecurity::true_login($_POST['login_new'])) {
            $data = $this->PHPShopOrm->select(array('id'), array('login' => "='" . $_POST['login_new'] . "'"), false, array('limit' => 1));
            if (!empty($data['id']))
                $this->error[] = $this->locale['error_id'];
        }

        // Проверка равности паролей 1 и 2
        if ($_POST['password_new'] != $_POST['password_new2'])
            $this->error[] = $this->locale['error_password'];
        // Проверка длинны пароля
        if (strlen(str_replace('_', '', $_POST['password_new'])) < 6)
            $this->error[] = $this->locale['error_password_hack1'];
        if (strlen(str_replace('_', '', $_POST['password_new2'])) < 6)
            $this->error[] = $this->locale['error_password_hack2'];
        
        // Проверка валидности логина
        if (!PHPShopSecurity::true_login($_POST['login_new']))
            $this->error[] = $this->locale['error_login'];

        // Проверка валидности пароля
        if (!PHPShopSecurity::true_passw($_POST['password_new']))
            $this->error[] = $this->locale['error_password_hack'];

        // Проверка валидности почты
        if (!PHPShopSecurity::true_email($_POST['mail_new']))
            $this->error[] = $this->locale['error_mail'];

        // Проверка валидности имени
        if (strlen(str_replace('_', '', $_POST['name_new'])) < 2)
            $this->error[] = $this->locale['error_name'];

        // Проверка валидности фамилии
        if (strlen(str_replace('_', '', $_POST['lastname_new'])) < 2)
            $this->error[] = $this->locale['error_lastname'];

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $_POST);

        if (count($this->error) == 0)
            return true;
    }

    /**
     * Запись нового пользователя в БД
     * @return Int ИД нового пользователя в БД
     */
    function add() {

        // Проверка на подтверждение активации
        if (!$this->PHPShopSystem->ifSerilizeParam('admoption.user_mail_activate')
                and !$this->PHPShopSystem->ifSerilizeParam('admoption.user_mail_activate_pre')) {
            $user_mail_activate = 1;
            $this->user_status = $this->PHPShopSystem->getSerilizeParam('admoption.user_status');
        } else {
            $user_mail_activate = 0;
            $this->user_status = md5(time());
        }

        // Массив данных нового пользователя
        $insert = array(
            'login_new' => $_POST['login_new'],
            'password_new' => $this->encode($_POST['password_new']),
            'datas_new' => time(),
            'mail_new' => $_POST['mail_new'],
            'name_new' => str_replace('_','',htmlspecialchars($_POST['lastname_new'],ENT_QUOTES,'cp1251').' '.htmlspecialchars($_POST['name_new'],ENT_QUOTES,'cp1251')),
            'company_new' => htmlspecialchars($_POST['company_new'],ENT_QUOTES,'cp1251'),
            'inn_new' => htmlspecialchars($_POST['inn_new'],ENT_QUOTES,'cp1251'),
            'tel_new' => str_replace('_','',htmlspecialchars($_POST['tel_new'],ENT_QUOTES,'cp1251')),
            'adres_new' => htmlspecialchars($_POST['adres_new'],ENT_QUOTES,'cp1251'),
            'enabled_new' => $user_mail_activate,
            'status_new' => $this->user_status,
            'kpp_new' => htmlspecialchars($_POST['kpp_new'],ENT_QUOTES,'cp1251')
            //,'tel_code_new' => htmlspecialchars($_POST['tel_code_new'],ENT_QUOTES,'cp1251')
        );

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $insert);
        if (is_array($hook))
            $insert = $hook;

        // Запись в БД
        $this->PHPShopOrm->insert($insert);

        // Возвращаем ИД нового пользователя
        return mysql_insert_id();
    }

    /**
     * Экшен добавления нового пользователя
     */
    function action_add_user() {


        // Если пройдена проверка на существующий логин
        if ($this->add_user_check()) {

            // Добавление запись в БД
            $UsersId = $this->add();

            // Проверка на подтверждение активации
            if (!$this->PHPShopSystem->ifSerilizeParam('admoption.user_mail_activate')
                    and !$this->PHPShopSystem->ifSerilizeParam('admoption.user_mail_activate_pre')) {

                $this->PHPShopUser = new PHPShopUser($UsersId);

                // Включаем параметр успешной атворизации
                $_SESSION['UsersId'] = $UsersId;
                $_SESSION['UsersStatus'] = $this->PHPShopUser->getStatus();

                // Форма персональных данных
                $this->user_info();
            } else {

                // Сообщение об активации аккаунта
                $this->message_activation();
            }
        } else {

            // Список ошибок
            $this->error();

            // Форма регистрации
            $this->action_register();
        }
    }

    /**
     * Сообщение регистрации
     * Функция вынесена в отдельный файл users.core/message_activation.php
     * @return mixed
     */
    function message_activation() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__);
    }

    /**
     * Экшен форма регистрации нового пользователя
     * Результат заполнения формы обработывается в action_add_user()
     */
    function action_register() {
        $this->set('formaTitle', $this->lang('user_register_title'));
        $this->set('formaContent', ParseTemplateReturn('phpshop/lib/templates/users/register.tpl', true));
        $this->setHook(__CLASS__, __FUNCTION__);
        $this->ParseTemplate($this->getValue('templates.users_page_list'));
    }

    /**
     * Шаблонизация вывода данных
     * @return string
     */
    function tr() {
        $Arg = func_get_args();

        $tr = '<tr>';

        foreach ($Arg as $key => $val)
            if ($val != '-')
                $col[$key] = 1;
            else
                $col[$key] = 2;

        foreach ($Arg as $key => $val) {
            if ($val != '-')
                $tr.=PHPShopText::td($val, false, @$col[$key + 1], $id = 'allspecwhite');
        }

        $tr.='</tr>';
        return $tr;
    }

    /**
     * Шаблонизация вывода данных. Заголовок.
     * @return string
     */
    function caption() {
        $Arg = func_get_args();
        $tr = '<tr id="allspec">';
        foreach ($Arg as $val) {
            $tr.=PHPShopText::td(PHPShopText::b($val), false, false, $id = 'allspecwhite');
        }
        $tr.='</tr>';
        return $tr;
    }

}

?>