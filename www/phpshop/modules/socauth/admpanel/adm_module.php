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
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.socauth.socauth_system"));

// Выбор шкуры
function GetSkins() {
    global $SysValue;
    $dir = "../../../templates";
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." and $file != ".." and $file != "index.html")
                    $mass[] = $file;
            }
            closedir($dh);
        }
    }
    return $mass;
}

// Выбор иконки шкуры
function GetSkinsIcon($skin) {
    global $SysValue;
    $dir = "../../../templates";
    $filename = $dir . '/' . $skin . '/icon/icon.gif';
    if (file_exists($filename))
        $disp = '<img src="' . $filename . '" alt="' . $skin . '" width="150" height="120" border="1" id="icon">';
    else
        $disp='<img src="../../../admpanel/img/icon_non.gif" alt="Изображение не доступно" width="150" height="120" border="1" id="icon">';
    return @$disp;
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $upArr['authConfig_new'] = serialize($_POST['authConfig']);
    // запоминпаем данные по соц сетям
        $action = $PHPShopOrm->update($upArr, array('id' => '= 1'));
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $SysValue, $_classPath, $PHPShopOrm;


    $PHPShopGUI->dir = $_classPath . "admpanel/";
    $PHPShopGUI->title = "Настройка модуля SocAuth";
    $PHPShopGUI->size = "500,500";


// Выборка
    $data = $PHPShopOrm->select();
    
    $authConfig = unserialize($data['authConfig']);
    $fcCongif = $authConfig['facebook'];
    $twCongif = $authConfig['twitter'];
    $vkCongif = $authConfig['vk'];
    
// Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'SocAuth'", "Настройки", $PHPShopGUI->dir . "img/i_display_settings_med[1].gif");

// Создаем объекты для формы

    $ContentField1 = $PHPShopGUI->setInput("text", "authConfig[facebook][appid]", $fcCongif['appid'], '' , 250, '', '', '', 'App ID');
    $ContentField1 .= $PHPShopGUI->setInput("text", "authConfig[facebook][secret]", $fcCongif['secret'], '' , 250, '', '', '', 'Secret ID');
    
    $ContentField2 = $PHPShopGUI->setInput("text", "authConfig[twitter][key]", $twCongif['key'], '' , 250, '', '', '', 'Consumer key');
    $ContentField2 .= $PHPShopGUI->setInput("text", "authConfig[twitter][secretkey]", $twCongif['secretkey'], '' , 250, '', '', '', 'Consumer secret');
    
    $ContentField3 = $PHPShopGUI->setInput("text", "authConfig[vk][client_id]", $vkCongif['client_id'], '' , 250, '', '', '', 'ID приложения');
    $ContentField3 .= $PHPShopGUI->setInput("text", "authConfig[vk][client_secret]", $vkCongif['client_secret'], '' , 250, '', '', '', 'Защищенный ключ');


// Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("Настройка facebook", $ContentField1);
    $Tab1.=$PHPShopGUI->setField("Настройка twitter", $ContentField2);
    $Tab1.=$PHPShopGUI->setField("Настройка ВКонтакте", $ContentField3);


    $Info = getInstruct();
    $ContentField2 = $PHPShopGUI->setInfo($Info, 200, '95%');
	
	$Tab2.=$PHPShopGUI->setField("Инструкция по настройке", $ContentField2);

    $Tab3 = $PHPShopGUI->setPay($serial, false);

// Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 270), array("Инструкции", $Tab2, 270), array("О Модуле", $Tab3, 270));

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


function getInstruct(){
return '
<h4>Настройка шаблона</h4>
Чтобы добавить ссылки на авторизацию через соцсети в форму авторизации на сайне, нужно в шаблон<br><br>
users/users_forma.tpl<br><br>
вставить метки:<br><br>
@facebookAuth@<br><br>
@twitterAuth@<br><br>
@vkontakteAuth@
<h4>Настройка twitter.com</h4>
- авторизоваться в твиттере<br><br>
- перейти по ссылке:<br><br>
<a href="https://dev.twitter.com/apps/new?from=phpshop" target="_blank">https://dev.twitter.com/apps/new</a><br><br>
- заполнить все поля на своё усмотрение, кроме поля "Callback URL:"<br><br>
- в "Callback URL:" необходимо указать ссылку возврата на ваш сайт:<br><br>
http://<b>вашДомен.ру</b>/socauth/twitter/<br><br>
- в поле "WebSite: *" нужно указать адрес вашего сайта:<br><br>
http://<b>вашДомен.ру</b>/
- после того как создастся приложение, откроется страница настроек<br><br>
- данные поля настраиваются на своё усмотрение<br><br>
- со страницы настроек необходимо взять параметры: <br><br>
<b>Consumer key</b><br><br>
<b>Consumer secret</b><br><br>
- указанные в предыдущем меню параметры необходимо прописать в соответствующие поля 
в настройке модуля авторизации через соц сети во вкладке "основное"



<h4>Настройка facebook.com</h4>
- авторизоваться в фейсбуке<br><br>
- перейти по ссылке:<br><br>
<a href="http://www.facebook.com/developers/createapp.php?from=phpshop" target="_blank">http://www.facebook.com/developers/createapp.php</a><br><br>
- создать приложение (App Name может быть любым)<br><br>
- заполнить поля из раздела основной информации нужными данными<br><br>
- в поле "App Domains" необходимо указать ваш домен (если сайт лежит ну субдомене, нужно указывать только основной), например:<br><br>
сайт лежи на test.phpshop-partners.ru, нужно указать phpshop-partners.ru<br><br>
- все поля ниже раздела основных данных оставить без изменения, кроме поля "Site URL"<br><br>
- в поле "Site URL" необходимо указать полностью адрес вашего домена, например:<br><br>
http://test.phpshop-partners.ru<br><br>
- из созданного приложения взять данные<br><br>
<b>App ID</b><br><br>
<b>App Secret</b><br><br>
- указанные в предыдущем меню параметры необходимо прописать в соответствующие поля 
в настройке модуля авторизации через соц сети во вкладке "основное"

<h4>Настройка vk.com</h4>
- авторизоваться в vk.com<br><br>
- перейти по ссылке:<br><br>
<a href="http://vk.com/editapp?act=create" target="_blank">http://vk.com/editapp?act=create</a><br><br>
- создать приложение типа веб-сайт (Название может быть любым) <br><br>
- в поле "Адрес сайта" необходимо указать полностью адрес вашего домена, например:<br><br>
http://test.phpshop-partners.ru<br><br>
- в поле "Базовый домен" необходимо указать ваш домен (если сайт лежит ну субдомене, нужно указывать только основной), например:<br><br>
сайт лежи на test.phpshop-partners.ru, нужно указать phpshop-partners.ru<br><br>
- из созданного приложения взять данные<br><br>
<b>ID приложения</b><br><br>
<b>Защищенный ключ</b><br><br>
- указанные в предыдущем меню параметры необходимо прописать в соответствующие поля 
в настройке модуля авторизации через соц сети во вкладке "основное"

';
}
?>


