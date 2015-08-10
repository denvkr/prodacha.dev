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
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.facebookpage.facebookpage_system"));

// Выбор шкуры
function GetSkins() {
    global $SysValue;
    $dir = "../../../templates";
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." and $file != ".." and $file != "index.html" and strpos($file, "acebook_"))
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

    // запоминпаем выбранный шаблон
    if (!empty($_POST['skin_new'])) {
        $action = $PHPShopOrm->update($_POST, array('id' => '= 1'));
    }
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $SysValue, $_classPath, $PHPShopOrm;


    $PHPShopGUI->dir = $_classPath . "admpanel/";
    $PHPShopGUI->title = "Настройка модуля facebookpage";
    $PHPShopGUI->size = "500,500";


// Выборка
    $data = $PHPShopOrm->select();


// Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Facebookpage'", "Настройки", $PHPShopGUI->dir . "img/i_display_settings_med[1].gif");


    //////////// установка шаблонов
    $skin_arr = GetSkins();
    $tpl_dir = "../../../templates";
    // пробуем проставить права
    @chmod($tpl_dir, 0775);
    if (isset($_GET['zip']) and !count($skin_arr)) {
        // пробуем распаковать архив с шаблона для фейсбука
        $file = "../install/facebook_templates.zip";
        if (is_file($file)) {
            require '../../../lib/zip/pclzip.lib.php';
            $archive = new PclZip($file);
            if ($archive->extract(PCLZIP_OPT_PATH, $tpl_dir)) {
                $zip_log = 1;
                //unlink($file);
            } else
                $zip_log = 2;
        }
    }
    
    $skin_arr = GetSkins();
    if (!count($skin_arr))
        $skinAlert = 'Шаблоны для facebook отсутствуют в системе:<br>
            <input type="button" value="Установить" name="" id="" style="width:80px;" class="but" onclick="window.location.replace(\'?zip=true\')">';
    if ($zip_log == 1)
        $skinAlert = "Шаблоны для facebook успешно установлены!";
    if ($zip_log == 2 OR ($zip_log == 1 and !count($skin_arr)))
        $skinAlert = 'Ошибка распаковки, необходимо установить права 775 на папку /phpshop/templates:<br>
            <input type="button" value="Повторить" name="" id="" style="width:80px;" class="but" onclick="window.location.replace(\'?zip=true\')">';

    ////////////
// Создаем объекты для формы
    //получаем массив скинов
    $skin_arr = GetSkins();
    $now_skin = $data[skin];
    if(is_array($skin_arr))
    foreach ($skin_arr as $value) {
        if ($value == $now_skin)
            $select_arr[] = array($value, $value, 'selected');
        else
            $select_arr[] = array($value, $value, false);
    }

    $ContentField1 = '<script>
        // Вывод скриншота
        function GetSkinIcon_facebook(skin){
            var path="../../../templates/"+skin+"/icon/icon.gif";
            document.getElementById("icon").src=path;
        }
        </script>';
    $ContentField1 .= $PHPShopGUI->setSelect('skin_new', $select_arr, 200, "none", '', 'GetSkinIcon_facebook(this.value)', false, 10);
    $ContentField1 .= GetSkinsIcon($now_skin);

    $Info = getInstruct();
    $ContentField2 = $PHPShopGUI->setInfo($Info, 200, '95%');


// Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("Выберите дизайн для страницы в facebook", $ContentField1);
    $Tab1 .= $skinAlert;
    $Tab2 = $PHPShopGUI->setField("Настройка", $ContentField2);

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

function getInstruct() {
    return '
    <p>Модуль Facebook Templates установит специально адаптированные шаблоны-витрины для этой социальной сети.</p>
<p><strong>1.</strong>&nbsp;Залогиньтесь или создайте свой аккаунт на Facebook&nbsp;<a title="www.facebook.com" href="http://www.facebook.com/?from=phpshop">www.facebook.com</a><br><strong>2.</strong>&nbsp;Создайте страницу <a title="www.facebook.com/pages/create.php" href="http://www.facebook.com/pages/create.php?from=phpshop">www.facebook.com/pages/create.php</a>. Выберите вид "<span>Компания, организация или учереждение"</span>, далее выберите категорию, введи название компании и нажмите кнопку "Начало работы".</p>
<p><strong>3.</strong> Выберите картинку профайла, нажмите "Далее".&nbsp;</p>
<p><strong>4.</strong> Заполните информацию о магазине.</p>
<p><strong>5.</strong> Теперь найдите с помощью поиска Facebook приложение&nbsp;<span>&nbsp;"<strong>Static iframe tab</strong>" и установите его. Затем выберите созданную Вами страницу.</span></p>
<p><span><strong>6.</strong>&nbsp;<span>&nbsp;В настройках приложения нужно прописать url распакованного шаблона. Это адрес Вашего сайта + /?faceBook=true. Например, ваш домен test.ru, тогда нужно прописать url - test.ru/?faceBook=true.</span></span></p>
<p><span><strong>7.</strong> Теперь можно поменять изображения вкладки, со ссылкой на Ваш магазин. Зайдите в настройки аккаунта, Приложения, выберите Static Iframe Tab, измените картику и название магазина.</span></p>
<p><span><strong>8.</strong> В настройках страницы магазина выберите изображения профиля страницы и заставку.&nbsp;</span></p>
<p><span>Настройки готовы! Результат - Ваш магазин в адаптированном шаблоне для Facebook. Пользоваель может выбрать интересующий товар, и на сайте оформить заказ.</span></p>
<BR> Подробная нструкция со скриншотами:<br>
<a href="http://faq.phpshop.ru/page/facebook-templates.html">http://faq.phpshop.ru/page/facebook-templates.html </a>    ';
}
?>


