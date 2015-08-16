<?php

/**
 * Обработчик формы сообщения с сайта
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopForma
 * @version 1.0
 * @package PHPShopCore
 */
class PHPShopForma extends PHPShopCore {

    /**
     * Конструктор
     */
    function PHPShopForma() {
        $this->debug=false;
        
        // список экшенов
        $this->action=array("post"=>"content","nav"=>"index");
        parent::PHPShopCore();
    }


    /**
     * Экшен по умолчанию, вывод формы связи
     */
    function index() {

        // Мета
        $this->title="Форма связи - ".$this->PHPShopSystem->getValue("name");

        // Определяем переменные
        $this->set('pageTitle','Форма связи');

        // Подключаем шаблон
        $this->addToTemplate("forma/page_forma_list.tpl");

        // Перехват модуля
        $this->setHook(__CLASS__,__FUNCTION__);

        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * Экшен отправка формы при получении $_POST[content]
     */
    function content() {

        // Перехват модуля
        if($this->setHook(__CLASS__,__FUNCTION__,$_POST))
                return true;

        if(!empty($_SESSION['text']) and $_POST['key']==$_SESSION['text']) {
            $this->send();
            $this->set('Error',"Сообщение успешно отправлено");
        }else $this->set('Error',"Ошибка ключа, повторите попытку ввода ключа");
        $this->index();
    }


    /**
     * Генерация сообщения
     */
    function send() {

        // Подключаем библиотеку отправки почты
        PHPShopObj::loadClass("mail");

        // Перехват модуля
        if($this->setHook(__CLASS__,__FUNCTION__,$_POST))
                return true;

		if( !empty($_POST['tema']) and !empty($_POST['name']) and !empty($_POST['content'])) {

            $zag=$_POST['tema']." - ".$this->PHPShopSystem->getValue('name');
            
            $message="Вам пришло сообщение с сайта ".$this->PHPShopSystem->getValue('name')."

Данные о пользователе:
----------------------
";

            // Информация по сообщению
            foreach($_POST as $key=>$val)
$message.=$val."
";

            $message.="
Дата:               ".date("d-m-y H:s a")."
IP:
".$_SERVER['REMOTE_ADDR']."
---------------

С уважением,
http://".$_SERVER['SERVER_NAME'];

            $PHPShopMail = new PHPShopMail($this->PHPShopSystem->getValue('adminmail2'),$_POST['mail'],$zag,$message);
        }
    }
}
?>