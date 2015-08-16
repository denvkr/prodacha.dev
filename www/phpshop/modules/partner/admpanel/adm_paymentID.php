<?php
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
$PHPShopGUI->ajax="'modules','partner','payment'";
$PHPShopGUI->includeJava='<SCRIPT language="JavaScript" src="../../../lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>';
$PHPShopGUI->dir=$_classPath."admpanel/";

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.partner.partner_payment"));



// Функция обновления
function actionUpdate() {
    global $PHPShopOrm,$PHPShopModules,$PHPShopSystem;

    // Списываем баланс партнера
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.partner.partner_users"));
    $PHPShopOrm->debug=false;
    $data=$PHPShopOrm->select(array('id,money'),array('login'=>"='".$_POST['partnerLogin']."'"),false,array('limit'=>1));
    print_r($data);
    if(is_array($data))
        if($data['money']>=$_POST['sum']) {
            $money=$data['money'];
            $total=$money-$_POST['sum'];
            $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.partner.partner_users"));
            $PHPShopOrm->debug=false;
            $action = $PHPShopOrm->update(array('money_new'=>$total),array('id'=>'='.$data['id']));
        }

    if($action == true) {

        if(empty($_POST['enabled_new'])) $_POST['enabled_new']=0;
        $_POST['date_done_new']=time();

        // Сообщение пользователю
        if(!empty($_POST['sendmail'])) {
            PHPShopObj::loadClass("mail");
            $PHPShopMail = new PHPShopMail($_POST['mail'],$PHPShopSystem->getValue('adminmail2'),
                    'Выплаты партнерам - '.$PHPShopSystem->getValue('name'),$_POST['content'] );
        }

        $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.partner.partner_payment"));
        $action = $PHPShopOrm->update($_POST,array('id'=>'='.$_POST['newsID']));
    }


    return true;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm,$PHPShopModules;

    $PHPShopGUI->title="Заявка на выплаты";

    // Выборка
    $PHPShopOrm->sql='SELECT a.*, b.login, b.mail, b.money, b.content FROM '.$PHPShopModules->getParam("base.partner.partner_payment").' AS a JOIN '.$PHPShopModules->getParam("base.partner.partner_users").' AS b ON a.partner_id = b.id where a.id='.$_GET['id'];
    $data = $PHPShopOrm->select();
    @extract($data[0]);

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Заявка на выплаты","Укажите данные для записи в базу.",$PHPShopGUI->dir."img/i_visa_med[1].gif");

    $Tab1=$PHPShopGUI->setInputText('Логин: ','login', $login);
    $Tab1.=$PHPShopGUI->setInputText('E-mail: ','mail', $mail);
    $Tab1.=$PHPShopGUI->setInputText('Баланс: ','', $money,50,$PHPShopSystem->getDefaultValutaCode());
    $Tab1.=$PHPShopGUI->setInputText('Заявка: ','sum', $sum,50,$PHPShopSystem->getDefaultValutaCode());


    // Дополнительные поля
    $content=unserialize($content);
    $dop=null;

    if(is_array($content))
        foreach($content as $k=>$v) {
            $name=str_replace('dop_', '', $k);
            $dop.=$name.': '.$v.'
';
        }
    $dop=substr($dop,0,strlen($dop)-1);

    $Tab1.=$PHPShopGUI->setField('Дополнительно', $PHPShopGUI->setTextarea('dop', $dop));

    $message='Уважаемый, '.$login.'.
'.$GLOBALS['SysValue']['lang']['partner_money_ready'].'
Выплачено '.$sum.' '.$PHPShopSystem->getDefaultValutaCode();

    $Tab1.=$PHPShopGUI->setField($PHPShopGUI->setCheckbox('sendmail', 1, 'Сообщение пользователю', $enabled), $PHPShopGUI->setTextarea('content', $message));
    $Tab1.=$PHPShopGUI->setCheckbox('enabled_new', 1, 'Заявка выполнена', $enabled);


    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,350));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","partnerLogin",$login,"right",70,"","but").
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            $PHPShopGUI->setInput("button","","Отмена","right",70,"return onCancel();","but").
            $PHPShopGUI->setInput("submit","delID","Удалить","right",70,"","but","actionDelete").
            $PHPShopGUI->setInput("submit","editID","ОК","right",70,"","but","actionUpdate");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}


// Функция удаления
function actionDelete() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->delete(array('id'=>'='.$_POST['newsID']));
    return $action;
}


// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'],'actionStart','none');

// Обработка событий
$PHPShopGUI->getAction();
?>