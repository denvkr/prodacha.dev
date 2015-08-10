<?php

/**
 * Тестовая фукция, выводит имя сайта
 * @package PHPShopTest
 * @return string
 */
function myTest(){
$PHPShopOrm=new PHPShopOrm($GLOBALS['SysValue']['base']['table_name3']);
$row=$PHPShopOrm->select(array('name'));
$disp='
<p><br></p>
<div align="right">
<strong>См. также:</strong><br>
<a href="/doc/license.html">&raquo; Лицензионное соглашение</a><br>
<a href="/doc/design.html">&raquo; Редактирование дизайна</a><br>
<a href="/skin/">&raquo; База бесплатных шаблонов PHPShop</a><br>
<a href="/doc/test.html">&raquo; Подключение HTML файлов</a><br>
<a href="/phptest/">&raquo; Подключение PHP логики</a><br>
<a href="/coretest/">&raquo; Подключение PHP логики через API</a><br>
</div>
<h1>Подключение PHP логики</h1>
<p>
Исходник этого файла расположен по адресу: /pages/phptest.php<br>
Возможно использование логики php.<br>
Для подключения  HTML файлов используйте файлы в папке <a href="/doc/test.html">/doc/test.html</a>
</p>
<p>Начиная с версии 3.0 существует возможность использования <a href="/doc/design.html#id8">PHP логики в шаблонах</a> через встроенный парсер.</p>
<p>
<p>Начиная с версии 3.3 существует возможность использовать строенное <a href="/coretest/">API PHPShop Core</a>,
намного упрощающее написание сложных модулей с использованием внутренних функций и методов.</p>
<p>
<h1>Имя вашего сайта: "'.$row['name'].'"</h1>
Разберем модуль PHPTEST:

<ul>
  <li>Создаем функцию<br>
  <pre>
function <strong>myTest()</strong>{
return "Hellow world!";
}

Все переменные возвращаются только return, 
никаких echo и print!!!
</pre>

   <li>Создаем Title<br>
      <pre>
$SysValue["other"]["pageTitl"] ="Подключение PHP логики - "
.$PHPShopSystem->getValue("name");
   </pre>
   <li>Подключаем шаблонизатор<br>
   <pre>
$SysValue[\'other\'][\'DispShop\']=myTest();
ParseTemplate($SysValue[\'templates\'][\'shop\']);
   </pre>
   
   <li>В итоге получаем вывод сообщения "Hellow world!" в общем дизайне сайта.
</ul>

</p>
';
return $disp;
}

// Title
$SysValue['other']['pageTitl'] ="Подключение PHP логики - ".$PHPShopSystem->getValue('name');

// Подключаем шаблон
$SysValue['other']['DispShop']=myTest();
ParseTemplate($SysValue['templates']['shop']);
?>