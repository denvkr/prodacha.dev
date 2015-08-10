<?php

$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("text");
PHPShopObj::loadClass("array");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");


// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/",'yescredit');


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.yescredit.yescredit_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $PHPShopOrm->debug=false;
    $action = $PHPShopOrm->update($_POST);
    return $action;
}

function payment($id) {
    PHPShopObj::loadClass('payment');
    $PHPShopPayment = new PHPShopPaymentArray();
    $Payment=$PHPShopPayment->getArray();
    if(is_array($Payment))
        foreach($Payment as $val)
            if(!empty($val['enabled'])){
                if($id == $val['id']) $sel='selected';
                else $sel=null;
                $value[]=array($val['name'],$val['id'],$sel);
            }

    return PHPShopText::select('payment_id_new',$value,250);
}

function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm,$PHPShopModules;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Настройка модуля Yes Credit";
    $PHPShopGUI->size="500,450";

    // Выборка
    $data = $PHPShopOrm->select();

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Yes Credit'","Настройки подключения",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    $Tab1=$PHPShopGUI->setField('Тип оплаты',payment($data['payment_id']));
    $Tab1.=$PHPShopGUI->setField('MERCHANT ID',$PHPShopGUI->setInputText(false,'MERCHANT_ID_new',$data['MERCHANT_ID'],100,' * выдается при подключении услуги'));

    $info='Свяжитесь со специалистами <a href="http://www.yes-credit.ru/?from=phpshop_mod" target="_blank">YesCredit</a> по телефону +7 (495) 640-38-28 или отправив письмо на <a href="mailto:info@webfinance.ru">info@webfinance.ru</a> для согласования
пакета документов, выбора тарифа и консультации по техническим вопросам подключения.

<p>После подписания договора Вам будут предоставлены необходимые ключи безопасности, сериный номер этого модуля и доступ к личному кабинету,
в котором Вы сможете отслеживать статусы обработки кредитных заявок, делать отметки о наличии товара, получать
аналитические срезы за указанные периоды.
';

    $Tab2=$PHPShopGUI->setInfo($info, 200, '96%');

    // Форма регистрации
    $Tab3=$PHPShopGUI->setPay($data['serial'],true);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,270),array("Инструкция",$Tab2,270),array("О Модуле",$Tab3,270));

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