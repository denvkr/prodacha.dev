<?
/**
 * PHPShop Guard
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopInc
 */


class Guard {

    var $version='1.2';
    var $none_chek_temlates=array('aeroblue','blue_classic','grass','gray','green_classic','red_classic','pink',
            'yellow_classic','phpshop_1','phpshop_2','phpshop_3','phpshop_4','phpshop_5','phpshop_6','phpshop_7',
            'phpshop_8','example');
    var $none_chek_dir=array('UserFiles','pda','install','1cManager','backup','files','csv','editor3','Packs','doc',
            'enterprise','.hg','awstats');
    var $src=array('php','html','tpl','js');
    var $update_url='http://www.phpshop.ru/update/guard/update.php';
    var $update_enabled=false;
    var $backup_path='UserFiles/Files/';
    var $license_path='license/';
    var $none_chek_file=array('../phpshop/lib/JsHttpRequest/JsHttpRequest.js');



    // Конструктор
    function Guard($dir_global) {
        global $PHPShopSystem,$_classPath;
        $this->classPath=$_classPath;
        $this->dir_global=$dir_global;

        // Включаем таймер
        $time=explode(' ', microtime());
        $this->start_time=$time[1]+$time[0];

        $this->PHPShopSystem=$PHPShopSystem;
        $this->SysValue=$GLOBALS['SysValue'];
        $this->date=date("U");
        $this->sec=md5($this->date);

        // Сообщение о заражении
        $this->stop_message=$this->SysValue['lang']['guard_stop_message'];

        // Дизайн
        $this->my_template=$this->PHPShopSystem->getParam('skin');

        // Настройка
        $this->system();

        // Заглушка
        if(!empty($this->system['stop'])) $this->alert();

    }


    // Запуск
    function start() {

        if($this->system['enabled'] and empty($this->system['used'])) {

            // Проверка обновлений сигнатур
            if($this->date-$this->system['last_update']>86400) $this->update();

            if($this->date-$this->system['last_chek']>(86400/$this->system['chek_day_num'])) {

                // Пишем лог
                $this->log('start');

                // Проверяем файлы
                $this->file($this->dir_global);

                // Первый бекап
                if($this->log_id == 1) {
                    $this->create();
                    $this->first_start=true;
                }

                // Сравниваем
                $this->chek();

                // Сигнатуры
                $this->signature();

                // Пишем лог
                $this->log('end');
            }
        }
    }

    // Настройка защитника
    function system() {
        $PHPShopOrm= &new PHPShopOrm($this->SysValue['base']['guard']['guard_system']);
        $this->system=$PHPShopOrm->select();
    }


    // Срок действия тех. поддержки
    function license() {
        if (@$dh = opendir($this->license_path)) {
            while (($file = readdir($dh)) !== false) {
                $fstat = explode(".",$file);
                if($fstat[1] == "lic")
                    $license = $this->license_path.$file;
            }
            closedir($dh);
        }
        $license=@parse_ini_file($license,1);
        $this->support=$license['License']['SupportExpires'];
    }


    // Обновление сигнатур
    function update() {
        
        // Если разрешена проверка обновлений
        if($this->update_enabled) {

            PHPShopObj::loadClass("xml");

            // Проверка лицензии
            $this->license();

            if($this->support > time()) {

                $this->update_url.="?from=".$_SERVER['SERVER_NAME']."&version=".$this->SysValue['upload']['version']."&support=".$this->support;
                if(function_exists("xml_parser_create")) {
                    if(@$db=readDatabase($this->update_url,"virus")) {

                        // Очищаем
                        if(is_array($db)) {

                            if($db[0]['status'] != 'passive') {

                                $PHPShopOrm = &new PHPShopOrm();
                                $PHPShopOrm->query('TRUNCATE TABLE '.$this->SysValue['base']['guard']['guard_signature']);

                                // Вставляем новые сигнатуры
                                foreach($db as $key=>$val) {
                                    $PHPShopOrm = &new PHPShopOrm($this->SysValue['base']['guard']['guard_signature']);
                                    $PHPShopOrm->insert(array('virus_name_new'=>$val['name'],'virus_signature_new'=>$val['signature']));
                                }

                                // Обновляем дату обновления сигнатур
                                $PHPShopOrm= &new PHPShopOrm($this->SysValue['base']['guard']['guard_system']);
                                $PHPShopOrm->update(array('last_update_new'=>$this->date),array('id'=>'=1'));

                                $this->update_result=1;
                            }
                            else $this->update_result=0;
                        }
                        else $this->update_result=2;
                    }


                    // Кол-во сигнатур
                    $this->signature_num=count($db);

                }
            }
        }
    }

    // Закрываем сайт при заражении
    function alert() {
        $PHPShopOrm= &new PHPShopOrm($this->SysValue['base']['guard']['guard_log']);
        $data=$PHPShopOrm->select(array('infected_files'),false,array('order'=>'id DESC'),array('limit'=>1));
        if($data['infected_files']>0) $this->message($this->stop_message);
    }

    // Проверка сигнатур вирусов
    function signature() {
        $PHPShopOrm= &new PHPShopOrm($this->SysValue['base']['guard']['guard_signature']);
        $data=$PHPShopOrm->select(array('virus_name','virus_signature'),false,false,array('limit'=>1000));
        $pattern='/(';
        if(is_array($data)) {
            foreach($data as $row) $pattern.=$row['virus_signature'].'|';
            $pattern=substr($pattern,0,-1);
            $pattern.=')/i';

            if(is_array($this->update) and is_array($this->new))
                $warning_list=array_merge($this->update,$this->new);

            if(is_array($warning_list))
                foreach($warning_list as $val) {

                    if(!in_array($val,$this->none_chek_file)) {
                        $content=file_get_contents($val);
                        if (preg_match($pattern,$content)) {
                            $this->infected[]=$val;
                        }
                    }
                }
        }
    }

    // Проверка текущего шаблона
    function template_chek() {
        if(is_array($this->none_chek_temlates))
            foreach($this->none_chek_temlates as $val)
                if($val != $this->my_template) $this->temlates[]=$val;
    }

    // Проверка режима
    function mode($file,$ext) {
        if(empty($this->system['mode'])) {
            if($ext == "php") {
                if($file=="index.php") return true;
                else return false;
            }
            else return true;
        }
        else return true;
    }


    // Выбор файла
    function file($dir) {

        $this->template_chek();
        $none=array_merge($this->temlates,$this->none_chek_dir);

        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != ".." && !in_array($file,$none)) {

                    // Проверка расширения
                    $ext=pathinfo($file);

                    if(is_dir($dir."/".$file)) $this->file($dir."/".$file);
                    else if( in_array($ext['extension'],$this->src) and $this->mode($file,$ext['extension']) and file_get_contents($dir."/".$file)) {
                        $this->base[md5_file($dir."/".$file)]=$dir."/".$file;
                    }
                }
            }
            closedir($dh);
        }
        return null;
    }

    // Сообщение
    function message($content,$caption='PHPShop Guard') {

        $message= '<h2>'.$caption.'</h2><hr><p>'.$content.'</p>';
        $message.= '<hr>';
        $message.= date('r').' / -- guardian system v. '.$this->version;
        exit($message);
    }

    function backup() {
        $PHPShopOrm= &new PHPShopOrm($this->SysValue['base']['guard']['guard_log']);
        $data=$PHPShopOrm->select(array('backup'),array('backup'=>"!=''"),array('order'=>'id DESC'),array('limit'=>1));
        return $data['backup'];
    }

    // Запись лога в базу
    function log($action='start') {

        switch($action) {

            case "start":

            // Блокируем загрузки
                $PHPShopOrm= &new PHPShopOrm($this->SysValue['base']['guard']['guard_system']);
                $PHPShopOrm->update(array('used_new'=>'1'),array('id'=>'=1'));

                // Пишем лог
                $PHPShopOrm= &new PHPShopOrm($this->SysValue['base']['guard']['guard_log']);
                $PHPShopOrm->insert(array('date_new'=>$this->date));
                $this->log_id=mysql_insert_id();
                break;

            case "end":

            // Имя последнего бекапа
                $last_backup=$this->backup();
                if(empty($last_backup)) $last_backup=date("d-m-Y-H-i",$this->date).'-'.$this->sec;

                // Бекап
                if(count($this->infected)==0)
                    if( count($this->changes)>0 or count($this->new)>0) {
                        $this->zip();
                        $backup=date("d-m-Y-H-i",$this->date).'-'.$this->sec;
                    }

                // Если первый запуск
                if($this->first_start) {
                    $this->zip();
                    $backup=date("d-m-Y-H-i",$this->date).'-'.$this->sec;
                }


                // Сообщение администратору
                if(count($this->infected)>0 or count($this->changes)>0)
                    if($this->system['mail_enabled']) $this->mail($last_backup);

                // Выкл таймер
                $this->timer();

                $PHPShopOrm= &new PHPShopOrm($this->SysValue['base']['guard']['guard_log']);
                $PHPShopOrm->update(array('infected_files_new'=>count($this->infected),'new_files_new'=>count($this->new),
                        'change_files_new'=>count($this->changes),'time_new'=>$this->timer,'backup_new'=>$backup),array('id'=>'='.$this->log_id));

                // Снимаем блокировку загрузки
                $PHPShopOrm= &new PHPShopOrm($this->SysValue['base']['guard']['guard_system']);
                $PHPShopOrm->update(array('used_new'=>'0','last_chek_new'=>$this->date),array('id'=>'=1'));

                break;

            case "end_admin":
                $this->zip();
                $backup=date("d-m-Y-H-i",$this->date).'-'.$this->sec;

                // Выкл таймер
                $this->timer();
                $PHPShopOrm= &new PHPShopOrm($this->SysValue['base']['guard']['guard_log']);
                $PHPShopOrm->update(array('infected_files_new'=>count($this->infected),'new_files_new'=>count($this->new),
                        'change_files_new'=>count($this->changes),'time_new'=>$this->timer,'backup_new'=>$backup),array('id'=>'='.$this->log_id));

                // Снимаем блокировку загрузки
                $PHPShopOrm= &new PHPShopOrm($this->SysValue['base']['guard']['guard_system']);
                $PHPShopOrm->update(array('used_new'=>'0','last_crc_new'=>$this->last_crc,'last_chek_new'=>$this->date),array('id'=>'=1'));
                break;
        }
    }

    // Трассировка
    function trace($obj,$caption=false) {
        echo "<h4>$caption</h4>";
        echo "<pre>";
        print_r($obj);
        echo "</pre>";
    }

    // Проверка измененных файлов
    function chek() {
        $PHPShopOrm= &new PHPShopOrm($this->SysValue['base']['guard']['guard_crc']);
        $data=$PHPShopOrm->select(array('crc_name','crc_file'),false,false,array('limit'=>1000));

        if(is_array($data))
            foreach($data as $val)
                $this->log[$val['crc_file']]=$val['crc_name'];

        if(is_array($this->base))
            foreach($this->base as $name=>$file)
                if (@!array_key_exists($name, $this->log)) $this->update[$name]=$file;

        // Разделение на новые и измененные
        if(is_array($this->update))
            foreach($this->update as $name=>$file) {
                $PHPShopOrm= &new PHPShopOrm($this->SysValue['base']['guard']['guard_crc']);
                $data=$PHPShopOrm->select(array('id'),array('crc_name'=>'="'.md5($file).'"'),false,array('limit'=>1));
                if(is_array($data)) $this->changes[$name]=$file;
                else $this->new[$name]=$file;
            }

    }


    // Создание списка файлов в базе
    function create() {

        // Очищаем
        $PHPShopOrm= &new PHPShopOrm();
        $PHPShopOrm->query('TRUNCATE TABLE '.$this->SysValue['base']['guard']['guard_crc']);

        if(is_array($this->base))
            foreach($this->base as $key=>$val) {

                // Новая запись
                $PHPShopOrm= &new PHPShopOrm($this->SysValue['base']['guard']['guard_crc']);
                $PHPShopOrm->insert(array('log_id_new'=>$this->log_id,'date_new'=>$this->date,'crc_name_new'=>md5($val),
                        'crc_file_new'=>$key,'path_file_new'=>$val));
                $PHPShopOrm= &new PHPShopOrm($this->SysValue['base']['guard']['guard_crc']);
            }

        $this->last_crc=$this->date;
        $this->crc_num=mysql_insert_id();

        // Заносим в БД дату последней проверки
        $PHPShopOrm= &new PHPShopOrm($this->SysValue['base']['guard']['guard_system']);
        $PHPShopOrm->update(array('last_crc_new'=>$this->date));
    }


    // ZIP
    function zip($dir=false,$fname=false) {

        // Библиотека ZIP
        include_once($_SERVER['DOCUMENT_ROOT'].$this->SysValue['dir']['dir']."/phpshop/lib/zip/pclzip.lib.php");

        if(empty($dir)) $dir=$this->base;

        $zip_files='';
        $name=$fname.date("d-m-Y-H-i",$this->date).'-'.$this->sec;
        if(is_array($dir))
            foreach($dir as $file) $zip_files.=$file.',';

        // Имя архива
        $archive_name=$this->backup_path.$name.'.zip';

        $archive = new PclZip($archive_name);
        $v_list = $archive->create($zip_files,PCLZIP_OPT_REMOVE_PATH, "../../../");
        if ($v_list == 0) {
            die("Error : ".$archive->errorInfo(true));
        }
        return $archive_name;
    }

    // Скорость
    function timer() {

        // Выключаем таймер
        $time=explode(' ', microtime());
        $seconds=($time[1]+$time[0]-$this->start_time);
        $seconds=substr($seconds,0,6);
        $this->timer=$seconds;
    }

    // Проверка прав
    function admin($password) {
        if(!preg_match("/^([\d]{2}-[\d]{2}-[\d]{4}-[\d]{2}-[\d]{2}-[a-z0-9]{32})$/i",$password)) return false;
        $PHPShopOrm=&new PHPShopOrm($this->SysValue['base']['guard']['guard_log']);
        $data=$PHPShopOrm->select(array('date'),array('backup'=>'="'.$password.'"'));
        if(is_array($data)) {
            $today=date("U");
            if(($today-$data['date'])<86400) return true;

        }
    }

    function mail($last_backup) {
        PHPShopObj::loadClass("mail");
        $zag='Возможное заражение вирусом сайта '.$this->PHPShopSystem->getParam('name');
        $content='Внимание!
---------------

Мониторинг сайта '.$_SERVER['SERVER_NAME'].' сообщает об изменении в структуры файлов:

* Измененных файлов - '.count($this->changes).'
* Новых файлов - '.count($this->new).'
* Зараженных файлов - '.count($this->infected).'
    ';

        // Предупреждение об вирусной базе через 10 дней
        if(($this->date-$this->system['last_update'])>(86400*10))
            $content.='

!!!!!!!!!
---
ВНИМАНИЕ:

Антивирусная база PHPShop Guard устарела, в целях защиты вашего сайта неоходимо продлить техническую поддержку, которая позволит обновить сигнатуры вирусов и спесет ваш сайт от заражения вирусами!
Для продления технической поддержки перейдите по ссылке: http://www.phpshop.ru/order/
---
!!!!!!!!!

';


        if(is_array($this->new)) {
            $content.='
Анализ показал наличие новых файлов:

';
            foreach($this->new as $key => $infected)
                $content.='* '.str_replace('../','',$infected).'
';


        }

        if(is_array($this->changes)) {
            $content.='
Анализ показал изменение содержимого в файлах:

';
            foreach($this->changes as $key => $infected)
                $content.='* '.str_replace('../','',$infected).'
';

        }

        if(is_array($this->changes))
            $content
                    .='
Измененные файлы могут быть причиной заражения их вирусами или внесения в файлы изменений службой поддержки или техническим специалистом для доработки сайта.
Если Вы уверены, что изменение файлов было спонтанным, то переходите к инструкции по борьбе с вирусами, представленной чуть ниже.

';
        if(is_array($this->infected)) {
            $content.='Предварительный анализ сигнатур показал наличие вирусов в файлах:

'; 

            foreach($this->infected as $key => $infected)
                $content.='* '.str_replace('../','',$infected).'
';
        }
        if(is_array($this->changes))
            $content.='

Для борьбы с вирусом следуйте инструкции:

* Проверить свой компьютер антивирусом
* Поменять пароль FTP сайта
* Воспользоваться бэкапом и заменить файлы на FTP.

Рекомендуются к использованию
бэкап: http://'.$_SERVER['SERVER_NAME'].'/UserFiles/File/'.$last_backup.'.zip

Если изменения файлов было санкционированным, то перейдите по ссылке для создания нового образа файлов:
http://'.$_SERVER['SERVER_NAME'].'/phpshop/modules/guard/admin.php?do=create&backup='.$last_backup.'
';
        // Отправить в карантин
        $this->license();
        if(($this->date)<($this->support) and is_array($this->changes))
            $content.='
Если изменения файлов было несанкционированным и есть подозрение, что произошло заражение вирусом, то перейдите по ссылке
для анализа измененных файлов из карантина службой поддержки PHPShop Guard:
http://'.$_SERVER['SERVER_NAME'].'/phpshop/modules/guard/admin.php?do=quarantine&backup='.$last_backup.'
';

        $content.='
---
'.date('r').' / -- guardian system v. '.$this->version;

        $PHPShopMail=&new PHPShopMail($this->PHPShopSystem->getParam('adminmail2'),'guard@'.$_SERVER['SERVER_NAME'],$zag,$content);
    }
}

?>