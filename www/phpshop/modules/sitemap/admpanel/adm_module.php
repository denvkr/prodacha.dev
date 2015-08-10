<?

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
include($_classPath . "admpanel/enter_to_admin.php");


// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

PHPShopObj::loadClass("date");

// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.sitemap.sitemap_system"));

function sitemaptime($nowtime) {
    return PHPShopDate::dataV($nowtime, false, true);
}

// Создание sitemap
function setGeneration() {
    global $PHPShopModules;

    $stat_products = null;
    $stat_pages = null;
    $stat_news = null;
    $stat_catalog = null;

    // Учет модуля SEOURL
    if (!empty($GLOBALS['SysValue']['base']['seourl']['seourl_system'])) {
        PHPShopObj::loadClass('string');
        $seourl_enabled = true;
    }
    else
        $seourl_enabled = false;

    // Библиотека
    $title = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $title.= '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">' . "\n";

    // Страницы
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name11']);
    $data = $PHPShopOrm->select(array('id,datas,link'), array('enabled' => "!='0'"), array('order' => 'datas DESC'),array('limit'=>10000));

    if (is_array($data))
        foreach ($data as $row) {
            $stat_pages.= '<url>' . "\n";
            $stat_pages.= '<loc>http://' . $_SERVER['SERVER_NAME'] . '/page/' . $row['link'] . '.html</loc>' . "\n";
            $stat_pages.= '<lastmod>' . sitemaptime($row['datas']) . '</lastmod>' . "\n";
            $stat_pages.= '<changefreq>weekly</changefreq>' . "\n";
            $stat_pages.= '<priority>1.0</priority>' . "\n";
            $stat_pages.= '</url>' . "\n";
        }

    // Страницы каталоги
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['page_categories']);
    $data = $PHPShopOrm->select(array('id,name'),false,false,array('limit'=>10000));

    $seourl = null;
    if (is_array($data))
        foreach ($data as $row) {

            if ($seourl_enabled)
                $seourl = '_' . PHPShopString::toLatin($row['name']);

            $stat_pages.= '<url>' . "\n";
            $stat_pages.= '<loc>http://' . $_SERVER['SERVER_NAME'] . '/page/CID_' . $row['id'] . $seourl . '.html</loc>' . "\n";
            $stat_pages.= '<changefreq>weekly</changefreq>' . "\n";
            $stat_pages.= '<priority>0.5</priority>' . "\n";
            $stat_pages.= '</url>' . "\n";
        }

    // Новости
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name8']);
    $data = $PHPShopOrm->select(array('id,datas,zag'), false, array('order' => 'datas DESC'),array('limit'=>10000));

    $seourl = null;
    if (is_array($data))
        foreach ($data as $row) {

            if ($seourl_enabled)
                $seourl = '_' . PHPShopString::toLatin($row['zag']);

            $stat_news.= '<url>' . "\n";
            $stat_news.= '<loc>http://' . $_SERVER['SERVER_NAME'] . '/news/ID_' . $row['id'] . $seourl . '.html</loc>' . "\n";
            $stat_news.= '<lastmod>' . sitemaptime(PHPShopDate::GetUnixTime($row['datas'])) . '</lastmod>' . "\n";
            $stat_news.= '<changefreq>daily</changefreq>' . "\n";
            $stat_news.= '<priority>0.5</priority>' . "\n";
            $stat_news.= '</url>' . "\n";
        }

    // Товары
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
    $data = $PHPShopOrm->select(array('*'), array('enabled' => "='1'", 'parent_enabled' => "='0'"), array('order' => 'datas DESC'),array('limit'=>100000));


    if (is_array($data))
        foreach ($data as $row) {
            $stat_products.= '<url>' . "\n";

            if (empty($seourl_enabled))
                $stat_products.= '<loc>http://' . $_SERVER['SERVER_NAME'] . '/shop/UID_' . $row['id'] . '.html</loc>' . "\n";
            else
                $stat_products.= '<loc>http://' . $_SERVER['SERVER_NAME'] . '/shop/UID_' . $row['id'] . '_' . PHPShopString::toLatin($row['name']) . '.html</loc>' . "\n";

            $stat_products.= '<lastmod>' . sitemaptime($row['datas']) . '</lastmod>' . "\n";
            $stat_products.= '<changefreq>daily</changefreq>' . "\n";
            $stat_products.= '<priority>1.0</priority>' . "\n";
            $stat_products.= '</url>' . "\n";
        }

    // Каталоги
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
    $data = $PHPShopOrm->select(array('id,name'),false,false,array('limit'=>10000));

    $seourl = null;
    if (is_array($data))
        foreach ($data as $row) {

            if ($seourl_enabled)
                $seourl = '_' . PHPShopString::toLatin($row['name']);

            $stat_products.= '<url>' . "\n";
            $stat_products.= '<loc>http://' . $_SERVER['SERVER_NAME'] . '/shop/CID_' . $row['id'] . $seourl . '.html</loc>' . "\n";
            $stat_products.= '<changefreq>weekly</changefreq>' . "\n";
            $stat_products.= '<priority>0.5</priority>' . "\n";
            $stat_products.= '</url>' . "\n";
        }


    $sitemap = $title . $stat_pages . $stat_news . $stat_products . '</urlset>';

    // Запись в файл
    @fwrite(@fopen('../../../../UserFiles/Files/sitemap.xml', "w+"), $sitemap);
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    // Создание sitemap
    if (!empty($_POST['generation']))
        setGeneration();

    return true;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $SysValue, $_classPath, $PHPShopOrm;


    $PHPShopGUI->dir = $_classPath . "admpanel/";
    $PHPShopGUI->title = "Настройка модуля";
    $PHPShopGUI->size = "500,450";


// Выборка
    $data = $PHPShopOrm->select();
    @extract($data);


// Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Sitemap'", "Настройки", $PHPShopGUI->dir . "img/i_display_settings_med[1].gif");

// Создаем объекты для формы
    $ContentField1 = $PHPShopGUI->setCheckbox("generation", 1, "Запустить атоматическую генерацию файла Sitemap.", false);
    $ContentField1.=$PHPShopGUI->setLine();
    $ContentField1.=$PHPShopGUI->setInput("button", "", "Открыть файл sitemap.xml", "left", 150, "return window.open('../../../../UserFiles/Files/sitemap.xml');", "but");

    $Info = "
   1. Для автоматического создания sitemap.xml устоновите модуль <b>Cron</b> и добавте в него новую задачу с адресом
        исполняемого файла  <b>phpshop/modules/sitemap/cron/sitemap_generator.php</b>
        <p>
   2. В поисковиках указать адрес http://" . $_SERVER['SERVER_NAME'] . "/UserFiles/Files/sitemap.xml
       </p>
   3. Установить опцию CHMOD 775 на папку /UserFiles/Files/ для записи в нее sitemap.xml";
    $ContentField2 = $PHPShopGUI->setInfo($Info, 130, '95%');

// Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("Создание файла", $ContentField1);
    $Tab1.=$PHPShopGUI->setField("Настройка", $ContentField2);

    $Tab3 = $PHPShopGUI->setPay($serial, false);

// Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 270), array("О Модуле", $Tab3, 270));

// Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "newsID", $id, "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("submit", "editID", "ОК", "right", 70, "", "but", "actionUpdate");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

if ($UserChek->statusPHPSHOP < 2) {

// Вывод формы при старте
    $PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

// Обработка событий
    $PHPShopGUI->getAction();
}else
    $UserChek->BadUserFormaWindow();
?>


