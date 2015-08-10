<?php

class PHPShopSocauth extends PHPShopCore {

    // массив настроек для соцсетей. Формируем в конструкторе.
    var $authConfig = array();
    // буфер ошибок
    var $error;

    /**
     * Конструктор
     */
    function PHPShopSocauth() {

        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['socauth']['socauth_system'];
        $this->debug = false;

        // модель работы с БД
        $this->PHPShopOrm = new PHPShopOrm($this->objBase);
        $this->PHPShopOrm->debug = $this->debug;
        $data = $this->PHPShopOrm->select();

        // получаем настройки соцсетей.
        $this->authConfig = unserialize($data['authConfig']);

        // Список экшенов
        $this->action = array("nav" => array("facebook", "twitter", "vk", "index"));

        parent::PHPShopCore();
    }

    // index экшен
    function index() {

        header("Location: /");
    }

    // экшен авторизации через twitter
    function twitter() {
        // получаем данные от фейсбука
        $regArr = $this->twitter_getData();
        if ($regArr) { // если данные получены
            //print_r($regArr);
            $regUser = $this->authUser($regArr);
            if ($regUser) {
                $this->set('message', ParseTemplateReturn($GLOBALS['SysValue']['templates']['socauth']['socauth_twitter_success'], true));
            } else {
                $this->set('message', $this->error);
            }
        } else {
            $this->set('message', $this->error);
        }


        $this->parseTemplate($GLOBALS['SysValue']['templates']['socauth']['socauth_main_forma'], true);
    }

    // метод получения данных от twitter
    function twitter_getData() {
        require './phpshop/modules/socauth/lib/twitter/twitteroauth.php';

        if (!empty($_GET['oauth_verifier']) && !empty($_SESSION['twitter']['oauth_token']) && !empty($_SESSION['twitter']['oauth_token_secret'])) {

            $twitteroauth = new TwitterOAuth($this->authConfig['twitter']['key'], $this->authConfig['twitter']['secretkey'], $_SESSION['twitter']['oauth_token'], $_SESSION['twitter']['oauth_token_secret']);
            // Let's request the access token
            $access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);
            // Save it in a session var
            $_SESSION['twitter']['access_token'] = $access_token;
            // Let's get the user's info
            $user_info = $twitteroauth->get('account/verify_credentials');
            // Print user's info
            if (isset($user_info->error)) {
                // Something's wrong, go back to square 1  
                //header('Location: login-twitter.php');
                $this->error = ParseTemplateReturn($GLOBALS['SysValue']['templates']['socauth']['socauth_twitter_auth_fail'], true);
            } else {
                $uid = $user_info->id;
                $regMass['login'] = "twitter" . $user_info->id . "@" . str_replace("www.", "", $_SERVER['SERVER_NAME']);
                $regMass['name'] = PHPShopString::utf8_win1251($user_info->name);

                return $regMass;
            }
        } else {

            $twitteroauth = new TwitterOAuth($this->authConfig['twitter']['key'], $this->authConfig['twitter']['secretkey']);
            // Requesting authentication tokens, the parameter is the URL we will be redirected to
            $getDataUrl = "http://" . $_SERVER['SERVER_NAME'] . "/socauth/twitter";
            $request_token = $twitteroauth->getRequestToken($getDataUrl);

            // Saving them into the session

            $_SESSION['twitter']['oauth_token'] = $request_token['oauth_token'];
            $_SESSION['twitter']['oauth_token_secret'] = $request_token['oauth_token_secret'];

            // If everything goes well..
            if ($twitteroauth->http_code == 200) {
                // Let's generate the URL and redirect
                $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
                header('Location: ' . $url);
            } else {
                // It's a bad idea to kill the script, but we've got to know when there's an error.
                $this->error = ParseTemplateReturn($GLOBALS['SysValue']['templates']['socauth']['socauth_twitter_auth_fail'], true);
            }
        }
        return false;
    }

    // экшен авторизации через facebook
    function facebook() {
        // получаем данные от фейсбука
        $regArr = $this->facebook_getData();
        if ($regArr) { // если данные получены
            //print_r($regArr);
            $regUser = $this->authUser($regArr);
            if ($regUser) {
                $this->set('message', ParseTemplateReturn($GLOBALS['SysValue']['templates']['socauth']['socauth_facebook_success'], true));
            } else {
                $this->set('message', $this->error);
            }
        } else {
            $this->set('message', $this->error);
        }


        $this->parseTemplate($GLOBALS['SysValue']['templates']['socauth']['socauth_main_forma'], true);
    }

    // экшен авторизации через VK
    function vk() {
        // получаем данные от фейсбука
        $regArr = $this->vk_getData();
        if ($regArr) { // если данные получены
            //print_r($regArr);
            $regUser = $this->authUser($regArr);
            if ($regUser) {
                $this->set('message', ParseTemplateReturn($GLOBALS['SysValue']['templates']['socauth']['socauth_vkontakte_success'], true));
            } else {
                $this->set('message', $this->error);
            }
        } else {
            $this->set('message', $this->error);
        }


        $this->parseTemplate($GLOBALS['SysValue']['templates']['socauth']['socauth_main_forma'], true);
    }

    // метод авторизации или регистрации пользователя.
    function authUser($mass) {
        $auth = new PHPShopUserSoc($mass);
        //проверяем существование, если существует, то авторизуем
        if (!$auth->checkUser()) {
            //если нет пользователя, то регистрируем, пишем в БД и авторизуем.
            if ($auth->registerUser()) {
                if ($auth->autorization()) {
                    return true;
                }
            }
        } else {
            if ($auth->autorization())
                return true;
        }
        return false;
    }

    // метод получения данных от фейсбука
    function facebook_getData() {
        require './phpshop/modules/socauth/lib/facebook/facebook.php';

        $facebook = new Facebook(array(
            'appId' => $this->authConfig['facebook']['appid'],
            'secret' => $this->authConfig['facebook']['secret'],
            'cookie' => false
        ));
        // Get User ID
        $user = $facebook->getUser();

        if ($user) {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                $user_profile = $facebook->api('/me');
            } catch (FacebookApiException $e) {
                error_log($e);
                $user = null;
            }
        }


        if ($user) {  // если авторизация прошла
            if (!empty($user_profile)) { // если данные получены
                // Если емейл не передан, отказываем в авторизации.
                if (!isset($user_profile['email'])) {
                    $this->set('link', $facebook->getLoginUrl(array('req_perms' => 'email')));
                    $this->error = ParseTemplateReturn($GLOBALS['SysValue']['templates']['socauth']['socauth_facebook_email_fail'], true);

                    return false;
                }

                $regMass['login'] = $user_profile['email'];
                $regMass['name'] = PHPShopString::utf8_win1251($user_profile['name']);
                $regMass['first_name'] = PHPShopString::utf8_win1251($user_profile['first_name']);
                $regMass['last_name'] = PHPShopString::utf8_win1251($user_profile['last_name']);

                return $regMass;
            } else {
                # For testing purposes, if there was an error, let's kill the script
                $this->error = ParseTemplateReturn($GLOBALS['SysValue']['templates']['socauth']['socauth_facebook_auth_fail'], true);

                return false;
            }
        } else {
            // переходим на авторизацию с запросом возможности передачи емейла.
            $login_url = $facebook->getLoginUrl(array('req_perms' => 'email'));
            header("Location: " . $login_url);
        }
    }

    // метод получения данных от VK
    function vk_getData() {
        $client_id = $this->authConfig['vk']['client_id']; // ID приложения
        $client_secret = $this->authConfig['vk']['client_secret']; // Защищённый ключ
        $redirect_uri = "http://" . $_SERVER['SERVER_NAME'] . "/socauth/vk/"; // Адрес сайта
        $url = 'https://oauth.vk.com/authorize';

        $params = array(
            'client_id' => $client_id,
            'redirect_uri' => $redirect_uri,
            'response_type' => 'code'
        );


        if (isset($_GET['code'])) {
            $result = false;
            $params = array(
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'code' => $_GET['code'],
                'redirect_uri' => $redirect_uri
            );
            $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);
            if (isset($token['access_token'])) {
                $params = array(
                    'uids' => $token['user_id'],
                    'fields' => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
                    'access_token' => $token['access_token']
                );
                $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
                if (isset($userInfo['response'][0]['uid'])) {
                    $userInfo = $userInfo['response'][0];
                    $result = true;
                }
            }

        } else {
            header("Location:" . $url . '?' . urldecode(http_build_query($params)));
            die();
        }


        if ($result) {  // если авторизация прошла
            $regMass['login'] = $user_profile['email'];
            $regMass['login'] = "vk" . $userInfo['uid'] . "@" . str_replace("www.", "", $_SERVER['SERVER_NAME']);
            $regMass['name'] = PHPShopString::utf8_win1251($userInfo['first_name'] . " " . $userInfo[';ast_name']);
            $regMass['first_name'] = PHPShopString::utf8_win1251($userInfo['first_name']);
            $regMass['last_name'] = PHPShopString::utf8_win1251($userInfo['last_name']);

            return $regMass;
        } else {
            $this->error = ParseTemplateReturn($GLOBALS['SysValue']['templates']['socauth']['socauth_vkontakte_auth_fail'], true);
            return false;
        }
    }

}

/* класс авторизации или регистрации пользователей по данным от соц сетей */

class PHPShopUserSoc extends PHPShopElements {

    /**
     * Конструктор
     */
    function PHPShopUserSoc($mass) {
        $this->debug = false;
        $this->objBase = $GLOBALS['SysValue']['base']['shopusers'];


        $this->userData = $mass;
        parent::PHPShopElements();
    }

    // проверяем есть такой пользователь или нет.
    // если есть, авторизуем.
    function checkUser() {
        // Проверка уникальности логина
        $this->PHPShopOrm = new PHPShopOrm($this->objBase);
        $this->PHPShopOrm->debug = $this->debug;

        $login = $this->userData['login'];
        $data = $this->PHPShopOrm->select(array('id'), array('login' => "='$login'"), false, array('limit' => 1));
        if (!empty($data['id']))
            return true;
        return false;
    }

    // регистрируем пользователя
    function registerUser() {
        // Массив данных нового пользователя
        $this->PHPShopOrm = new PHPShopOrm($this->objBase);
        $this->PHPShopOrm->debug = $this->debug;

        $password = base64_encode($this->generate_password(8));

        $insert = array(
            'login_new' => $this->userData['login'],
            'password_new' => $password,
            'datas_new' => time(),
            'mail_new' => $this->userData['login'],
            'name_new' => $this->userData['name'],
            'company_new' => '',
            'inn_new' => '',
            'tel_new' => '',
            'adres_new' => '',
            'enabled_new' => 1,
            'status_new' => 1,
            'kpp_new' => '',
            'tel_code_new' => ''
        );


        // Запись в БД
        $this->PHPShopOrm->insert($insert);
        return true;
    }

    // авторизация пользователя.

    function autorization() {
        $this->PHPShopOrm = new PHPShopOrm($this->objBase);
        $this->PHPShopOrm->debug = $this->debug;

        $login = $this->userData['login'];
        $data = $this->PHPShopOrm->select(array('*'), array('login' => '="' . $login . '"'), false, array('limit' => 1));
        if (is_array($data))
            if (PHPShopSecurity::true_num($data['id'])) {

                // Логин пользователя
                $_SESSION['UsersId'] = $data['id'];

                // Статус пользователя
                $_SESSION['UsersStatus'] = $data['status'];

                return true;
            }
        return false;
    }

    // метод генерации пароля, в параметре задаётся кол-во символов в пароле
    function generate_password($number) {
        $arr = array('a', 'b', 'c', 'd', 'e', 'f',
            'g', 'h', 'i', 'j', 'k', 'l',
            'm', 'n', 'o', 'p', 'r', 's',
            't', 'u', 'v', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F',
            'G', 'H', 'I', 'J', 'K', 'L',
            'M', 'N', 'O', 'P', 'R', 'S',
            'T', 'U', 'V', 'X', 'Y', 'Z',
            '1', '2', '3', '4', '5', '6',
            '7', '8', '9', '0', '.', ',',
            '(', ')', '[', ']', '!', '?',
            '&', '^', '%', '@', '*', '$',
            '<', '>', '/', '|', '+', '-',
            '{', '}', '`', '~');
        // Генерируем пароль
        $pass = "";
        for ($i = 0; $i < $number; $i++) {
            // Вычисляем случайный индекс массива
            $index = rand(0, count($arr) - 1);
            $pass .= $arr[$index];
        }
        return $pass;
    }

}

?>