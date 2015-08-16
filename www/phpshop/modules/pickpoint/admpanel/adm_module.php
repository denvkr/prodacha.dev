<?php

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
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.pickpoint.pickpoint_system"));


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $PHPShopOrm->debug=false;
    $action = $PHPShopOrm->update($_POST);
    return $action;
}


function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Настройка модуля PickPoint";
    $PHPShopGUI->size="500,450";

    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);

    if($type_service =='STD')
        $s0='selected';
    else $s1='selected';

    $type_service_value[]=array('STD - стандарт, доставка предоплаченного товара без приема оплаты за товар','STD',$s0);
    $type_service_value[]=array('STDCOD - доставка с приемом оплаты за товар, т.е. наложенный платеж','STDCOD',$s1);


    switch($type_reception){
        case "CUR":
            $s2='selected';
        break;
        case "WIN":
            $s3='selected';
        break;
        case "APTCON":
            $s4='selected';
        break;
        case "APT":
            $s5='selected';
        break;
    }

    $type_reception_value[]=array('CUR – сбор отправлений курьером PickPoint','CUR',$s2);
    $type_reception_value[]=array('WIN – самостоятельный привоз отправлений в окно приема на сортировочный центр PickPoint','WIN',$s3);
    $type_reception_value[]=array('APTCON – сдача отправлений консолидировано в 1 ячейку в Постамате валом','APTCON',$s4);
    $type_reception_value[]=array('APT – самостоятельный развоз отправлений по Постаматам','APT',$s4);

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'PickPoint'","Настройки подключения",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    $Tab1=$PHPShopGUI->setField('Имя доставка PickPoint',$PHPShopGUI->setInputText(false,'city_new', $city,300,'<br>* Доставка должны быть создана
        в базе и с точностью совпадать с ее именем.'));
    $Tab1.=$PHPShopGUI->setField('Текст ссылки',$PHPShopGUI->setInputText(false,'name_new', $name,300));
   
    $Tab1.=$PHPShopGUI->setField('Титы услуг',$PHPShopGUI->setSelect('type_service_new',$type_service_value,400));
    $Tab1.=$PHPShopGUI->setField('Вид приема',$PHPShopGUI->setSelect('type_reception_new',$type_reception_value,400));


    $info='Необходимо создать новые доставки, в именах которых есть слово \'PickPoint\'. Если имя доcтавки требуется изменить, то
        новое имя-формулу вхождения нужно указать в настройках этого модуля в поле \'Имя доставка PickPoint\'. Для примера, при указании имени
        PickPoint, выбор пункта доставки появится у всех доставок, у которых слово PickPoint присутвует в имени доставки. Это правило не относится к
        именам каталога доставки.
<p>
После заказа на почту администратора придет письмо с вложенным XML файлом выгрузки заказа в панель управления услугой <a href="http://
PickPoint.ru?from=phpshop_mod" target="_blank">PickPoint<a>.
</p> ';

    $Tab2=$PHPShopGUI->setInfo($info, 200, '96%');

    // Форма регистрации
    $Tab3=$PHPShopGUI->setPay($serial,false);

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