<?php

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("date");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");

// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

// Учет модуля SEOURL
if (!empty($GLOBALS['SysValue']['base']['seourl']['seourl_system'])) {
    PHPShopObj::loadClass('string');
    $seourl_enabled = true;
}
else
    $seourl_enabled = false;

function sitemaptime($nowtime) {
    return PHPShopDate::dataV($nowtime, false, true);
}

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
        $stat_pages.= '<lastmod>' . sitemaptime($row['datas'], false) . '</lastmod>' . "\n";
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
        $stat_pages.= '<loc>http://' . $_SERVER['SERVER_NAME'] . '/page/CID_' . $row['id'] .$seourl. '.html</loc>' . "\n";
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
$data = $PHPShopOrm->select(array('*'), array('enabled' => "='1'", 'parent_enabled' => "='0'"), array('order' => 'datas DESC'),array('limit'=>10000));

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

$seourl=null;
if (is_array($data))
    foreach ($data as $row) {
    
            if ($seourl_enabled)
            $seourl = '_' . PHPShopString::toLatin($row['name']);
    
        $stat_products.= '<url>' . "\n";
        $stat_products.= '<loc>http://' . $_SERVER['SERVER_NAME'] . '/shop/CID_' . $row['id'] . $seourl.'.html</loc>' . "\n";
        $stat_products.= '<changefreq>weekly</changefreq>' . "\n";
        $stat_products.= '<priority>0.5</priority>' . "\n";
        $stat_products.= '</url>' . "\n";
    }


$sitemap = $title . $stat_pages . $stat_news . $stat_products . '</urlset>';


// Запись в файл
@fwrite(@fopen('../../../../UserFiles/Files/sitemap.xml', "w+"), $sitemap);

echo "Sitemap.xml done!";
?>