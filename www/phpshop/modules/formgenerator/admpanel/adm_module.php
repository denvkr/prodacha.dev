<?
$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
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
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.formgenerator.formgenerator_system"));


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->update($_POST);
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Настройка модуля";
    $PHPShopGUI->size="500,450";


    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);


    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Form Generator'","Настройки",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    $Info='Для интеграции формы  в ручном режиме включите следующий код в содержание страницы или текстового блока:
        <p>
        <b>@php
        $PHPShopFormgeneratorElement = new PHPShopFormgeneratorElement();
        echo $PHPShopFormgeneratorElement->forma("маркер формы");
        php@</b>
         </p>
         <p>
         Для добавления новых полей используйте в обязательном порядке имена полей с префиксом formgenerator_, например:<br>
         &lt;input  type="text" <b>name="formgenerator_Тест"</b>&gt;   
         </p>
         <p>
         Для включения поля в список обязательного заполнения вставьте знак звездочки в имя поля, например:<br>
         &lt;input  type="text" name="formgenerator_<b>*Тест</b>"&gt;  
         </p>
         <p>
         Для запоминания данных поля и вывода сохраненных результытов при повторном заполнении формы используйете параметр значение
         поля с номером поля в форме по порядку, начиная сверху, например:<br>
         &lt;input  type="text" name="formgenerator_Тест" <b>value="@formamemory3@</b>"&gt;
         </p>

';
    $Tab2=$PHPShopGUI->setInfo($Info,250,'97%');


    // Содержание закладки 2
    $Tab3=$PHPShopGUI->setPay($serial,false);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Описание",$Tab2,270),array("О Модуле",$Tab3,270));

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


