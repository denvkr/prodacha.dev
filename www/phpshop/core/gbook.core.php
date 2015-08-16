<?php
/**
 * Обработчик гостевой книги
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopGbook
 * @version 1.1
 * @package PHPShopCore
 */
class PHPShopGbook extends PHPShopCore {

    /**
     * Конструктор
     */
    function PHPShopGbook() {

        // Имя Бд
        $this->objBase=$GLOBALS['SysValue']['base']['table_name7'];

        // Путь для навигации
        $this->objPath="/gbook/gbook_";

        // Отладка
        $this->debug=false;

        // Список экшенов
        $this->action=array("post"=>"send_gb","nav"=>"index","nav"=>"ID","get"=>"add_forma");
        parent::PHPShopCore();

        // Мета
        $this->title='Отзывы - '.$this->PHPShopSystem->getValue("name");
    }

    /**
     * Экшен по умолчанию, вывод отзывов
     */
    function index() {

        // Сообщение о записи отзыва
        if(!empty($_GET['write']))
            $this->set('Error',__("Сообщение успешно добавлено. Отзыв будет размещен только после проверки модератором"));

        // Выборка данных
        $this->dataArray=parent::getListInfoItem(array('*'),array('flag'=>"='1'"),array('order'=>'id DESC'));

        if(is_array($this->dataArray))
            foreach($this->dataArray as $row) {

                // Ссылка на автора
                if(!empty($row['mail']))  $d_mail=PHPShopText::a('mailto:'.$row['mail'],PHPShopText::b($row['name']),$row['name']);
                else  $d_mail=PHPShopText::b($row['name']);

                // Определяем переменые
                $this->set('gbookData',PHPShopDate::dataV($row['datas']));
                $this->set('gbookName',$row['name']);
                $this->set('gbookTema',$row['tema']);
                $this->set('gbookMail',$d_mail);
                $this->set('gbookOtsiv',$row['otsiv']);
                $this->set('gbookOtvet',$row['otvet']);
                $this->set('gbookId',$row['id']);

                // Перехват модуля
                $this->setHook(__CLASS__,__FUNCTION__,$row,'MIDDLE');

                // Подключаем шаблон
                $this->addToTemplate($this->getValue('templates.main_gbook_forma'));
            }

        // Пагинатор
        $this->setPaginator();

        // Перехват модуля
        $this->setHook(__CLASS__,__FUNCTION__,$row,'END');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.gbook_page_list'));

        // Ссылка на новый отзыв
        $this->add($this->attachLink());
    }

    /**
     * Экшен выборки подробной информации при наличии переменной навигации ID
     * @return string
     */
    function ID() {

        // Перехват модуля
        if($this->setHook(__CLASS__,__FUNCTION__,$row,'START'))
            return true;

        // Безопасность
        if(!PHPShopSecurity::true_num($this->PHPShopNav->getId())) return $this->setError404();

        // Выборка данных
        $row=parent::getFullInfoItem(array('*'),array('id'=>'='.$this->PHPShopNav->getId()));

        // 404
        if(!isset($row)) return $this->setError404();

        // Ссылка на автора
        if(!empty($row['mail']))  $d_mail=PHPShopText::a('mailto:'.$row['mail'],PHPShopText::b($row['name']));
        else  $d_mail=PHPShopText::b($row['name']);

        // Определяем переменные
        $this->set('gbookData',PHPShopDate::dataV($row['datas']));
        $this->set('gbookName',$row['name']);
        $this->set('gbookTema',$row['tema']);
        $this->set('gbookMail',$d_mail);
        $this->set('gbookOtsiv',$row['otsiv']);
        $this->set('gbookOtvet',$row['otvet']);
        $this->set('gbookId',$row['id']);

        // Перехват модуля
        $this->setHook(__CLASS__,__FUNCTION__,$row,'MIDDLE');

        // Подключаем шаблон
        $this->addToTemplate($this->getValue('templates.main_gbook_forma'));

        // Мета
        $this->title=$row['tema']." - ".$this->PHPShopSystem->getValue("name");
        $this->description=strip_tags($row['otsiv']);
        $this->lastmodified=PHPShopDate::GetUnicTime($row['datas']);

        // Перехват модуля
        $this->setHook(__CLASS__,__FUNCTION__,$row,'END');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.gbook_page_list'));
    }

    /**
     * Ссылка на новый отзыв
     * @return string
     */
    function attachLink() {

        // Перехват модуля
        $hook=$this->setHook(__CLASS__,__FUNCTION__);
        if($hook) return $hook;

        return PHPShopText::div(PHPShopText::a('/gbook/?add_forma=true','Оставить отзыв'),'center','padding:20');
    }

    /**
     * Новый отзыв
     */
    function add_forma() {

        // Перехват модуля
        if($this->setHook(__CLASS__,__FUNCTION__))
            return true;

        $this->parseTemplate($this->getValue('templates.gbook_forma_otsiv'));
    }

    /**
     * Экшен записи отзыва при получении $_POST[send_gb]
     */
    function send_gb() {
        
        // Перехват модуля
        if($this->setHook(__CLASS__,__FUNCTION__,$_POST,'START'))
            return true;

        if(!empty($_SESSION['text']) and $_POST['key']==$_SESSION['text']) {
            $this->write();
            header("Location: ../gbook/?write=ok");
        }else {
            $this->set('Error',__("Ошибка ключа, повторите попытку ввода ключа"));

            // Перехват модуля
            $this->setHook(__CLASS__,__FUNCTION__,$_POST,'END');

            $this->parseTemplate($this->getValue('templates.gbook_forma_otsiv'));
        }
    }

    /**
     * Запись отзыва в базу
     */
    function write() {

        // Подключаем библиотеку отправки почты
        PHPShopObj::loadClass("mail");

        // Перехват модуля
        if($this->setHook(__CLASS__,__FUNCTION__,$_POST))
            return true;

        if(isset($_POST['send_gb'])) {
            if(!PHPShopSecurity::true_email($_POST['mail_new']))//проверка почты
            {
                $_POST['mail_new']="";
            }
            if(PHPShopSecurity::true_param($_POST['name_new'],$_POST['otsiv_new'],$_POST['tema_new'])) {
                $name_new=PHPShopSecurity::TotalClean($_POST['name_new'],2);
                $otsiv_new=PHPShopSecurity::TotalClean($_POST['otsiv_new'],2);
                $tema_new=PHPShopSecurity::TotalClean($_POST['tema_new'],2);
                $mail_new=$_POST['mail_new'];
                $date = date("U");
                $ip=$_SERVER['REMOTE_ADDR'];

                // Запись в базу
                $this->PHPShopOrm->insert(array('datas'=>$date,'name'=>$name_new,'mail'=>$mail_new,'tema'=>$tema_new,'otsiv'=>$otsiv_new),
                        $prefix='');

                $zag=$this->PHPShopSystem->getValue('name')." - Уведомление о добалении отзыва / ".$date;
                
                // Пересенные для шаблона сообщения
                $this->set('gbook_name',$name_new);
                $this->set('gbook_mail',$mail_new);
                $this->set('gbook_title',$tema_new);
                $this->set('gbook_content',$otsiv_new);
                $this->set('gbook_ip',$ip);

                // Шаблон сообщения администратору
                $message=ParseTemplateReturn('phpshop/lib/templates/gbook/mail.tpl',true);
                
                $PHPShopMail = new PHPShopMail($this->PHPShopSystem->getValue('adminmail2'),$mail_new,$zag,$message);
            }
        }
    }
}
?>