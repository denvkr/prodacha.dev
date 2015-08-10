<?
$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("orm");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");


// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.yandexmap.yandexmap_system"));


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $action = $PHPShopOrm->update($_POST);
    return $action;
}


function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm;
    
    
    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Настройка модуля";
    $PHPShopGUI->size="500,450";
    
    
    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);
    
    
    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Яндекс.Карты'","Настройки",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    $Tab1=$PHPShopGUI->setTextarea('code_new', $code, null, '98%', '200px');
    $Tab3=$PHPShopGUI->setPay($serial,false);
    $Info='<h4>Для вставки Яндекс.Карты следуйте инструкции</h4>
        <ol>
        <li> <a href="http://api.yandex.ru/maps/form.xml" target="_blank">Получите API ключ для своего сайта</a>
        <li> <a href="http://api.yandex.ru/maps/tools/constructor/" target="_blank">Создайте точки на карте</a>.Введите адрес улицы.
        Расставьте на карте точки и линии и подпишите их.
        <li> Получите код для вставки.
        <li> Скопируйте код и вставьте в закладку "Код кнопки" текущего окна настройки модуля.
        <li> Карта будет доступна по переменной <b>@yandexmap@ </b>. Для вставки переменной @yandexmap@ перейдите в редактор страницы, включите режим редактирования
        HTML кода страницы и вставьте в нужное место @yandexmap@.

</ol>';
    $Tab2=$PHPShopGUI->setInfo($Info, '200px', '95%');

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Код кнопки",$Tab1,270),array("Описание",$Tab2,270),array("О Модуле",$Tab3,270));
    
    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            $PHPShopGUI->setInput("button","","Отмена","right",70,"return onCancel();","but").
            $PHPShopGUI->setInput("submit","editID","ОК","right",70,"","but","actionUpdate");
    
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

if($UserChek->statusPHPSHOP < 2) {
    
    // Вывод формы при старте
    $PHPShopGUI->setLoader($_POST['editID'],'actionStart');
    
    // Обработка событий 
    $PHPShopGUI->getAction();
    
}else $UserChek->BadUserFormaWindow();

?>


