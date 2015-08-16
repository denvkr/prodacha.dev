<?

$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopInterface = new PHPShopInterface();


// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");

// Расширения для редактирования
$AllowedTypes = "tpl|js|css";

// Проверка расширения файла
function isTypeAllowed($sFileName) {
    global $AllowedTypes;
    $pathinfo=pathinfo($sFileName);
    if(ereg($AllowedTypes,$pathinfo['extension']) ) return $pathinfo['extension'];
    else return false;
}

function actionStart() {

    // Запись
    if(isset($_POST['edit'])) actionUpdate();

    $tpl_file=base64_decode($_GET['edit']);


    $mode=array(
            'css'=>'text/css',
            'js'=>'text/javascript',
            'tpl'=>'text/html'
    );

    if(isTypeAllowed($tpl_file)) {
        $content=file_get_contents($tpl_file);
        $code='<textarea name="code" id="code" style="width:100%;height:600px">'.stripslashes($content).'</textarea>';
        $code.='    <script>
      var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "'.$mode[isTypeAllowed($tpl_file)].'",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",
        lineWrapping: true
      });

    parent.window.document.getElementById("code_button").style.display="block";
    </script>';
    }
    elseif(!empty($_GET['edit']))
        $code="Запрещенный формат файла шаблона";

    echo $code;
}


// Функция обновления
function actionUpdate() {

    $tpl_file=base64_decode($_POST['edit']);
    $return='Запрещенный формат файла шаблона';

    
    
    if(isTypeAllowed($tpl_file)) {

        // Попытка изменить атрибуты
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['edit']['edit_system']);
        $option=$PHPShopOrm->select();

        if(empty($option['chmod'])) 
            $option['chmod']=0775;
        @chmod($tpl_file, 0775);


        if(fwrite(fopen($tpl_file,"w+"), stripslashes($_POST['code']))) $return='Файл ['.str_replace('../../..','',$tpl_file).'] сохранен.';
        else $return='Не могу произвести запись в файл '.str_replace('../../..','',$tpl_file).'\n\nДля редактирования файла необходимо поставить права\nна его запись CHMOD 775';

        echo '<script>alert("'.$return.'")</script>';
    }

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "xhtml11.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?=$GLOBALS['PHPShopLangCharset']?>">
        <LINK href="<?=$_classPath.'admpanel/'?>skins/classic/texts.css" type=text/css rel=stylesheet>
              <link rel="stylesheet" href="../codemirror/lib/codemirror.css">
        <script src="../codemirror/lib/codemirror.js"></script>
        <script src="../codemirror/mode/xml/xml.js"></script>
        <script src="../codemirror/mode/javascript/javascript.js"></script>
        <script src="../codemirror/mode/css/css.js"></script>
        <script src="../codemirror/mode/htmlmixed/htmlmixed.js"></script>
        <script src="../codemirror/mode/htmlembedded/htmlembedded.js"></script>
        <script src="../codemirror/lib/util/searchcursor.js"></script>
        <script src="../codemirror/lib/util/search.js"></script>
        <style type="text/css">
            .CodeMirror {
                border: 1px solid #eee;
            }

            .CodeMirror-scroll {
                height: auto;
                overflow-y: hidden;
                overflow-x: auto;
                width: 100%;
            }

            div {
                font-size:14px;
                background: #FFFFFF;
            }
        </style>
    </head>
    <body bottommargin="0" rightmargin="0" topmargin="0" leftmargin="0" bgcolor="ffffff">
        <form name="source_edit" id="source_edit" method="post">
            <input type="hidden" name="edit" value="<?=$_GET['edit']?>">
            <?
            // Вывод формы при старте
            $PHPShopInterface->setLoader(false,'actionStart');

            ?>

        </form>

    </body>
</html>