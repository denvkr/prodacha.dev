<?php

/**
 * Обработчик новостей
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopNews
 * @version 1.4
 * @package PHPShopCore
 */
class PHPShopNews extends PHPShopCore {

    /**
     * Конструктор
     */
    function PHPShopNews() {

        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['table_name8'];

        // Путь для навигации
        $this->objPath = "/news/news_";

        // Отладка
        $this->debug = false;

        // Список экшенов
        $this->action = array('get' => 'timestamp', "nav" => array("ID", "index"), "post" => "news_plus");
        parent::PHPShopCore();

        // Календарь
        $this->calendar();
    }

    /**
     * Экшен по умолчанию
     */
    function index() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        // Выборка данных
        $this->dataArray = parent::getListInfoItem(array('*'), false, array('order' => 'id DESC'));

        // 404
        if (!isset($this->dataArray))
            return $this->setError404();

        if (is_array($this->dataArray))
            foreach ($this->dataArray as $row) {

                // Определяем переменные
                $this->set('newsId', $row['id']);
                $this->set('newsData', $row['datas']);
                $this->set('newsZag', $row['zag']);
                $this->set('newsKratko', $row['kratko']);

                // Перехват модуля
                $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

                // Подключаем шаблон
                $this->addToTemplate($this->getValue('templates.main_news_forma'));
            }

        // Пагинатор
        $this->setPaginator();

        // Мета
        $this->title = "Новости - " . $this->PHPShopSystem->getValue("name");

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $this->dataArray, 'END');

        // Подключаем шаблон
        return $this->parseTemplate($this->getValue('templates.news_page_list'));
    }

    /**
     * Экшен сортировки новостей по дате из календаря
     */
    function timestamp() {

        if (PHPShopSecurity::true_num($_GET['timestamp'])) {
            $year = date("Y", $_GET['timestamp']);
            $month = date("m", $_GET['timestamp']);
            $day = date("d", $_GET['timestamp']);
            $timestampstart = intval($_GET['timestamp']);
            $timestampend = mktime(23, 59, 59, $month, $day, $year);

            // Выборка данных
            $this->PHPShopOrm->sql = 'select * from ' . $this->objBase . ' where datau>=' . $timestampstart . ' AND datau<=' . $timestampend . ' order by datau desc';
            $this->dataArray = $this->PHPShopOrm->select();

            // 404
            if (!isset($this->dataArray))
                return $this->setError404();

            if (is_array($this->dataArray))
                foreach ($this->dataArray as $row) {

                    // Определяем переменные
                    $this->set('newsId', $row['id']);
                    $this->set('newsData', $row['datas']);
                    $this->set('newsZag', $row['zag']);
                    $this->set('newsKratko', $row['kratko']);

                    // Перехват модуля
                    $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');


                    // Подключаем шаблон
                    $this->addToTemplate($this->getValue('templates.main_news_forma'));
                }

            // Мета
            $this->title = "Новости - " . $this->PHPShopSystem->getValue("name");

            // Перехват модуля
            $this->setHook(__CLASS__, __FUNCTION__, $this->dataArray, 'END');

            // Подключаем шаблон
            $this->parseTemplate($this->getValue('templates.news_page_list'));
        } else {
            $this->setError404();
        }
    }

    /**
     * Экшен выборки подробной информации при наличии переменной навигации ID
     * @return string
     */
    function ID() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        // Безопасность
        if (!PHPShopSecurity::true_num($this->PHPShopNav->getId()))
            return $this->setError404();

        // Выборка данных
        $row = parent::getFullInfoItem(array('*'), array('id' => '=' . $this->PHPShopNav->getId()));

        // 404
        if (!isset($row))
            return $this->setError404();

        // Определяем переменые
        $this->set('newsData', $row['datas']);
        $this->set('newsZag', $row['zag']);
        $this->set('newsKratko', $row['kratko']);
        $this->set('newsPodrob', $row['podrob']);

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

        // Подключаем шаблон
        $this->addToTemplate($this->getValue('templates.main_news_forma_full'));

        // Мета
        $this->title = strip_tags($row['zag']) . " - " . $this->PHPShopSystem->getValue("name");
        $this->description = strip_tags($row['kratko']);
        $this->lastmodified = PHPShopDate::GetUnixTime($row['datas']);

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.news_page_full'));
    }

    /**
     * Экшен записи новости при получении $_POST[news_plus]
     */
    function news_plus() {
        $mail = PHPShopSecurity::TotalClean($_POST['mail'], 3);

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $_POST, 'START'))
            return true;

        switch ($_POST['status']) {

            case("1"):
                $this->write($mail);
                break;

            case("0"):
                $this->del($mail);
                break;
        }

        // Мета
        $this->title = "Новости - Подписка - " . $this->PHPShopSystem->getValue("name");

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $_POST, 'END');

        $this->parseTemplate($this->getValue('templates.news_forma_mesage'));
    }

    /**
     * Есть ли адрес в базе
     * @param string $mail почта
     * @return bool
     */
    function chek($mail) {
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name9'));
        $PHPShopOrm->debug = $this->debug;
        $num = $PHPShopOrm->select(array('id'), array('mail' => "='$mail'"), false, array('limit' => 1));
        if (empty($num['id']))
            return true;
    }

    /**
     * Добавление адреса  в БД
     * @param string $mail
     */
    function write($mail) {

        if (!empty($mail)) {

            if ($this->chek($mail)) {
                $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name9'));
                $PHPShopOrm->debug = $this->debug;
                $PHPShopOrm->insert(array('datas' => date("d.m.y"), 'mail' => $mail), $prefix = '');

                // Сообщение пользователю
                $mes = $this->message($this->lang('good_news_mesage_1'), $this->lang('good_news_mesage_2'));
            } else {
                // Сообщение пользователю
                $mes = $this->message($this->lang('bad_news_mesage_1'), $this->lang('good_news_mesage_2'));
            }
        } else {
            // Сообщение пользователю
            $mes = $this->message($this->lang('bad_news_mesage_3'), $this->lang('good_news_mesage_2'));
        }

        $this->set('mesageText', $mes);
    }

    /**
     * Удаление адреса из БД
     * @param string $mail
     */
    function del($mail) {

        if (!$this->chek($mail)) {
            $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name9'));
            $PHPShopOrm->debug = $this->debug;
            $PHPShopOrm->delete(array('mail' => "='$mail'"));
            $mes = $this->message($this->getValue('lang.bad_news_mesage_2'), $this->getValue('lang.good_news_mesage_2'));
        } else {
            $mes = $this->message($this->getValue('lang.bad_news_mesage_3'), $this->getValue('lang.good_news_mesage_2'));
        }

        $this->set('mesageText', $mes);
    }

    /**
     * Календарь новостей
     * Функция вынесена в отдельный файл news.core/calendar.php
     * @return mixed
     */
    function calendar() {

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__);
        if ($hook)
            return $hook;

        return $this->doLoadFunction(__CLASS__, __FUNCTION__);
    }

}

?>