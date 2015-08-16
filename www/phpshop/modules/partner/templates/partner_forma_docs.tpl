<p>
    На своих сайтах вы можете использовать следующие инструменты для перенаправления пользователей в партнерскую программу.
</p>
<h2>Партнерская ссылка</h2>
<textarea class="phpshop-gui" style="width:99%;height: 50px">
<a href="http://@serverName@@ShopDir@/?partner=@partnerId@" title="@name@">@name@</a>
</textarea>


<h2>Подключение витрины товаров через JavaScript</h2>
<textarea style="width:99%;height: 100px" class="phpshop-gui"><script src="http://@serverName@@ShopDir@phpshop/modules/partner/lib/js/phpshop-partner-lib.js" id="phpshop-lib-xml"></script>
<script>
var PHPShopXmlManager1 = new PHPShopXmlManager();
PHPShopXmlManager1.url = 'http://@serverName@@ShopDir@';
PHPShopXmlManager1.id= 'shopItem';
PHPShopXmlManager1.obj = 1;
PHPShopXmlManager1.imgwidth = 100;
PHPShopXmlManager1.currency = ' руб.';
PHPShopXmlManager1.partner = @partnerId@;
PHPShopXmlManager1.limit = 5;
PHPShopXmlManager1.load();
</script></textarea>
<p>Подробности настройки JS витрины описаны в <a href="http://wiki.phpshop.ru/index.php/Partner_Module">Wiki инструкции</a>.</p>

<h2>Установка PHPShop.CMS Free</h2>
Установка полнофункциональной бесплатной системы управления сайтом с модулем Catalog для трансляции товаров и каталогов по партнерской программе.
<p>Подробности настройки PHPShop.CMS Free описаны в <a href="http://wiki.phpshop.ru/index.php/Partner_Module">Wiki инструкции</a>.</p>


<h2>Установка PHPShop.CMS Micro</h2>
Установка бесплатной микро-системы управления сайтом с модулем Catalog для трансляции товаров по партнерской программе.
Не требует базы данных, работает на файлах. Поддерживает установку на бесплатных хостингах.
<p>Подробности настройки PHPShop.CMS Micro описаны в <a href="http://wiki.phpshop.ru/index.php/Partner_Module">Wiki инструкции</a>.</p>

<h2>Генератор HTML каталога Shop4Partner</h2>
Windows утилита для генерации html каталога товаров для дальнейшей загрузки файлов на свой сайт. Shop4Partner копирует
описания и изображения товаров с учетом SEO ссылок.
<p>Подробности настройки PHPShop.CMS Micro описаны в <a href="http://wiki.phpshop.ru/index.php/Partner_Module">Wiki инструкции</a>.</p>
<p><input type="button" value="Скачать Shop4Partner" onclick="javascript:window.open('@ShopDir@phpshop/modules/partner/files/setup.exe')"></p>