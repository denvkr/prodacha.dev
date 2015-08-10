<?php

// Заголовок
$TitlePage = __("Каталог страниц");

// Подключить JS библиотеку
$addJS = true;

function actionStart() {
    $PHPShopIframePanel = new PHPShopIframePanel(array('page/tree.php', 300, 500, 'frame1'), array('page/admin_page_content.php', '100%', 570, 'frame2'));
    $PHPShopIframePanel->title = __('Каталоги');

    $PHPShopIcon = new PHPShopIcon($start = 100);
    $PHPShopIcon->padding=0;
    $PHPShopIcon->margin=0;

    // Форма поиска
    $Search = $PHPShopIcon->setForm(
            $PHPShopIcon->setInputText(__('Поиск: '), 'words', '', $size = 180, false, "left",false, __('Поиск по заголовку или ссылке')) .
            $PHPShopIcon->setInput("button", "search_but", "Искать", "right", 70,'PHPShopJS.page.search(search.words.value)'), $action = false, $name = "search", 'get');

    $Tab1 = $PHPShopIcon->add($Search,300,'10px');
    
    $Tab1.= $PHPShopIcon->setBorder() .
            $PHPShopIcon->setIcon("icon/folder_add.gif", __('Новый каталог'), "PHPShopJS.page.addcat()") .
            $PHPShopIcon->setIcon("icon/folder_edit.gif", __('Редактировать каталог'), "PHPShopJS.page.edit()") .
            $PHPShopIcon->setBorder() .
            $PHPShopIcon->setIcon("icon/page_new.gif", __('Новая страница'), "PHPShopJS.page.addpage()") .
            $PHPShopIcon->setIcon("icon/layout_content.gif", __('Вывод всех страниц'), "PHPShopJS.page.all()").
            $PHPShopIcon->setBorder();

    // Заполнение селектора
    $selact_value[]=array(__('С отмеченными'),0,'selected');
    $selact_value[]=array(__('Включить вывод'),30,false);
    $selact_value[]=array(__('Отключить вывод'),31,false);
    $selact_value[]=array(__('Включить регистрацию'),31,false);
    $selact_value[]=array(__('Отключить регистрацию'),33,false);
    $selact_value[]=array(__('Перенести в каталог'),34,false);
    $selact_value[]=array(__('Добавить рекомендованные товары'),35,false);
    $selact_value[]=array(__('Удалить из базы'),39,false);
    $Select=$PHPShopIcon->setSelect('action', $selact_value, 200, 'none', false, $onchange = 'PHPShopJS.page.action(this.value)', $height = false, $size = 1, $multiple = false, $id = 'actionSelect');

    $Tab1.=$PHPShopIcon->add($Select,100);
    
    $Tab1.=$PHPShopIcon->setIcon("icon/chart_organisation_add.gif", __('Отметить все'), "PHPShopJS.selectall(1)") .
           $PHPShopIcon->setBorder().
           $PHPShopIcon->setIcon("icon/chart_organisation_delete.gif", __('Снять отметку'), "PHPShopJS.selectall(2)");
    
    $PHPShopIcon->setTab($Tab1);
    $PHPShopIframePanel->addTop($PHPShopIcon->Compile(true));
    $PHPShopIframePanel->Compile();
}

?>