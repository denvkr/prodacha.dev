<?
$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");


$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");

$PHPShopSystem = new PHPShopSystem();

// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->debug_close_window=false;
$PHPShopGUI->reload='top';
$PHPShopGUI->ajax="'modules','formgenerator'";
$PHPShopGUI->includeJava='<SCRIPT language="JavaScript" src="../../../lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>';
$PHPShopGUI->dir=$_classPath."admpanel/";

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.formgenerator.formgenerator_forms"));


// Функция записи
function actionInsert() {
    global $PHPShopOrm;
    if(empty($_POST['user_mail_copy_new'])) $_POST['user_mail_copy_new']=0;
    if(empty($_POST['enabled_new'])) $_POST['enabled_new']=0;

    $action = $PHPShopOrm->insert($_POST);
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Создание новой формы";
    $PHPShopGUI->size="630,530";


    // Графический заголовок окна
    $PHPShopGUI->setHeader("Создание новой формы","Укажите данные для записи в базу.",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    if(is_file('../templates/formgenerator.tpl'))
        $content=file_get_contents('../templates/formgenerator.tpl');

    $Tab1=$PHPShopGUI->setField('Название:',$PHPShopGUI->setInputText(false,'name_new','Новая форма','98%'));
    $Tab1.=$PHPShopGUI->setField('Ссылка:',$PHPShopGUI->setInputText('http://'.$_SERVER['SERVER_NAME'].'/formgenerator/','path_new','example',100),'left');
    $Tab1.=$PHPShopGUI->setField('E-mail:',$PHPShopGUI->setInputText(false,'mail_new',$PHPShopSystem->getParam("admin_mail"),'97%'));
    $Tab1.=$PHPShopGUI->setField('Опции:',$PHPShopGUI->setCheckbox('enabled_new','1','Вывод на сайте',1).
            $PHPShopGUI->setCheckbox('user_mail_copy_new','1','Выслать копию пользователю на e-mail',1));
    $Tab1.=$PHPShopGUI->setField('Сообщение после отправки:',$PHPShopGUI->setTextarea('success_message_new','Данные приняты, наши менеджеры свяжутся с вами.',false,'97%'));
    $Tab1.=$PHPShopGUI->setField('Сообщение о заполнении обязательных полей:',$PHPShopGUI->setTextarea('error_message_new','Ошибка заполнения формы. Заполните все поля, отмеченные звездочками (*).',false,'97%'));
    

    // Редактор 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"),true);

    if(class_exists('Editor')) {

        $oFCKeditor = new Editor('content_new') ;
        $oFCKeditor->Height = '320';
        $oFCKeditor->Config['EditorAreaCSS'] = $_classPath."templates".chr(47).$PHPShopSystem->getParam("skin").chr(47).$SysValue['css']['default'];

        // Коррекция адреса редактора
        $oFCKeditor->BasePath=$PHPShopGUI->dir.'editors/'.$oFCKeditor->BasePath;
        $oFCKeditor->ToolbarSet = 'Normal';
        $oFCKeditor->Value = $content;

        $Tab2=$oFCKeditor->AddGUI();

    } else $Tab2=$PHPShopGUI->setTextarea('content_new', $content, 'none', '97%', '300px');

    $Tab3=$PHPShopGUI->setField('Привязка к страницам:',$PHPShopGUI->setInputText(false,'dir_new','','98%',' * Пример: /page/about.html,/page/company.html'));

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,350),array("Содержание",$Tab2,350),array("Опции",$Tab3,350));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            $PHPShopGUI->setInput("button","","Отмена","right",70,"return onCancel();","but").
            $PHPShopGUI->setInput("submit","editID","ОК","right",70,"","but","actionInsert");

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


