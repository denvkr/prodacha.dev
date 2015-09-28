<?php

/**
 * Библиотека офорфмления текста
 * @version 1.2
 * @package PHPShopClass
 * @subpackage Helper
 */
class PHPShopText {

    /**
     * Пробел &nbsp
     * @param int $n количество пробелов
     * @return string
     */
    function nbsp($n = 1) {
        $i = 0;
        $nbsp = null;
        while ($i < $n) {
            $nbsp.='&nbsp;';
            $i++;
        }
        return $nbsp;
    }

    /**
     * Жирный текст
     * @param string $string текст
     * @param string $style стиль
     * @return string
     */
    function b($string, $style = false) {
        return '<b style="' . $style . '">' . $string . '</b>';
    }

    /**
     * Оповещение
     * @param string $string текст
     * @param string> $icon иконка
     * @param string $size размер текста
     * @return string
     */
    function notice($string, $icon = false, $size = false) {
        if (!empty($icon))
            $img = PHPShopText::img($icon);
        else
            $img = null;
        return $img . '<font color="red" style="font-size:' . $size . '">' . $string . '</font>';
    }

    /**
     * Сообщение
     * @param string $string текст
     * @param string $icon иконка
     * @param string $size размер текста
     * @param string $color цвет текста
     * @return string
     */
    function message($string, $icon = false, $size = false, $color = 'green') {
        if (!empty($icon))
            $img = PHPShopText::img($icon);
        else
            $img = null;
        return $img . '<font color="' . $color . '" style="font-size:' . $size . '">' . $string . '</font>';
    }

    /**
     * Изображение
     * @param string $src изобржение
     * @param int $hspace горизонтальный отсутп
     * @param string $align выравнивание
     * @return string
     */
    function img($src, $hspace = 5, $align = 'left',$style='') {
        return '<img '.$style.' src="' . $src . '" hspace="' . $hspace . '" align="' . $align . '" border="0">';
    }

    /**
     * Перевод строки
     * @return string
     */
    function br() {
        return '<br>';
    }

    /**
     * Ссылка
     * @param string $href ссылка
     * @param string $text текст
     * @param string $title описание
     * @param string $color цвет
     * @param string $size размер
     * @param string $target ссылка
     * @param string $class класс
     * @return string
     */
    function a($href, $text, $title = false, $color = false, $size = false, $target = false, $class = false) {
        $style = 'text-decoration:underline;';
        if ($size)
            $style.='font-size:' . $size . 'px;';
        if ($color)
            $style.='color:' . $color;
        if (empty($title))
            $title = $text;
        return '<a href="' . $href . '" title="' . $title . '" target="' . $target . '" class="' . $class . '" style="' . $style . '">' . $text . '</a>';
    }

    /**
     * Якорь
     * @param string $name имя
     * @return string
     */
    function slide($name) {
        return '<a name="' . $name . '"></a>';
    }

    /**
     * Заголовок H1
     * @param string $string текст
     * @return string
     */
    function h1($string) {
        return '<h1>' . $string . '</h1>';
    }

    /**
     * Заголовок H2
     * @param string $string текст
     * @return string
     */
    function h2($string) {
        return '<h2>' . $string . '</h2>';
    }

    /**
     * Заголовок H3
     * @param string $string текст
     * @return string
     */
    function h3($string) {
        return '<h3>' . $string . '</h3>';
    }

    /**
     * Список
     * @param string $string текст
     * @return string
     */
    function ul($string) {
        return '<ul>' . $string . '</ul>';
    }

    /**
     * Нумерованный список
     * @param string $string текст
     * @param string $type тип
     * @return string
     */
    function ol($string, $type = null) {
        return '<ol type="' . $type . '">' . $string . '</ol>';
    }

    /**
     * Элемент списка
     * @param string $string текст
     * @param string $href ссылка
     * @return string
     */
    function li($string, $href = null) {
        if (!empty($href)) {
            $text = PHPShopText::a($href, $string);
            $li = '<li>' . $text . '</li>';
        }
        else
            $li = '<li>' . $string . '</li>';
        return $li;
    }

    /**
     * Генератор слоя TR
     * @return string
     */
    function tr() {
        $Arg = func_get_args();
        $tr = '<tr class=tablerow>';
        foreach ($Arg as $val) {
            $tr.=PHPShopText::td($val, 'tablerow');
        }
        $tr.='</tr>';
        return $tr;
    }
	
    function tr2($Arg=null,$td_count=0) {
        //$Arg = func_get_args();
        $tr = '<tr class=tablerow>';
        foreach ($Arg as $val) {
            $tr.=PHPShopText::td($val, 'tablerow',false,false,$td_count);
        }
        $tr.='</tr>';
        return $tr;
    }
    /**
     * Выпадающий список
     * <code>
     * // example:
     * $value[]=array('моя цифра 1',123,'selected');
     * $value[]=array('моя цифра 2',456,false);
     * PHPShopText::select('my',$value,100);
     * </code>
     * @param string $name имя
     * @param array $value значенение в виде массива
     * @param int $width ширина
     * @param string $float float
     * @param string $caption текст перед элементом
     * @param string $onchange имя javascript функции по экшену onchange
     * @param int $height высота
     * @param int $size размер
     * @return string
     */
    function select($name, $value, $width, $float = "none", $caption = false, $onchange = "return true", $height = false, $size = 1, $id = false) {

        if (empty($id))
            $id = $name;

        $select = $caption . ' <select name="' . $name . '" id="' . $id . '" size="' . $size . '" style="float:' . $float . ';width:' . $width . 'px;height:' . $height . 'px" onchange="' . $onchange . '">';
        if (is_array($value))
            foreach ($value as $val)
                $select.='<option value="' . $val[1] . '" ' . @$val[2] . '>' . $val[0] . '</option>';
        $select.='</select>';
        return $select;
    }

    /**
     * Ячейка таблицы TD
     * @param string $string текст
     * @param string $class класс
     * @param string $colspan колспан
     * @param string $id ид
     * @param number $td_count кол-во столбцов
     * @return string
     */
    function td($string, $class = false, $colspan = false, $id = false,$td_count=0) {
        if ($td_count>0) {
            $css_width=round(100/$td_count);
            return '<td class="' . $class . '" id="' . $id . '" colspan="' . $colspan . '" style="width:'.$css_width.'%">' . $string . '</td>';            
        }
        else
            return '<td class="' . $class . '" id="' . $id . '" colspan="' . $colspan . '">' . $string . '</td>';
    }

    /**
     * Заголовок таблицы TH
     * @param string $string текст
     * @return string
     */
    function th($string) {
        return '<th>' . $string . '</th>';
    }

    /**
     * Блок DIV
     * @param string $string текст
     * @param string $align выравнивание
     * @param string $style стиль
     * @param string $id ид
     * @param string $class класс
     * @return string
     */
    function div($string, $align = "left", $style = false, $id = false, $class = false) {
        return '<div align="' . $align . '" id="' . $id . '" style="' . $style . '" class="' . $class . '">' . $string . '</div>';
    }

    /**
     * Зачеркнутый текст
     * @param string $string текст
     * @return string
     */
    function strike($string) {
        return '<strike>' . $string . '</strike>';
    }

    /**
     * Комментарий
     * @param string $type [<] или [>]
     * @return string
     */
    function comment($type = '<') {
        if ($type == '<')
            return '<!--';
        else
            return '-->';
    }

    /**
     * Абзац
     * @param string $string текст
     * @param string $style стиль
     * @return string
     */
    function p($string = '<br>', $style = false) {
        return '<p style="' . $style . '">' . $string . '</p>';
    }

    /**
     * Кнопка
     * @param string $value текст
     * @param string $onclick JS функция по клику
     * @param string $class класс
     * @return string
     */
    function button($value, $onclick, $class = 'ok') {
        return '<input type="button" value="' . $value . '" onclick="' . $onclick . '" class="' . $class . '">';
    }

    /**
     * Таблица
     * @param string $content содержание
     * @param string $cellpadding cellpadding
     * @param string $cellspacing cellspacing
     * @param string $align выравнивание
     * @param string $width длина
     * @param string $bgcolor фон
     * @param string $border бордюр
     * @param string $id ид
     * @return string
     */
    function table($content, $cellpadding = 3, $cellspacing = 1, $align = 'center', $width = '98%', $bgcolor = false, $border = 0, $id = false) {
        return '<table id="' . $id . '" cellpadding="' . $cellpadding . '" cellspacing="' . $cellspacing . '" border="' . $border . '" bgcolor="' . $bgcolor . '" width="' . $width . '" align="' . $align . '">' . $content . '</table>';
    }

    /**
     * Форма
     * @param string $content содержание
     * @param string $name имя, ид
     * @param string $method метод передачи
     * @param string $action цель передачи
     * @return string
     */
    function form($content, $name, $method = 'post', $action = false) {
        return '<form action="' . $action . '" name="' . $name . '" id="' . $name . '" method="' . $method . '">' . $content . '</form>';
    }

    /**
     * Input
     * @param string $type тип [text,password,button и т.д]
     * @param string $name имя
     * @param mixed $value значение
     * @param int $float float
     * @param int $size размер
     * @param string $onclick экшен по клику, имя javascript функции
     * @param string $class имя класса стиля
     * @param string $caption текст перед элементом
     * @param string $description текст после элемента
     * @return string
     */
    function setInput($type, $name, $value, $float = "none", $size = 200, $onclick = "return true", $class = false, $caption = false, $description = false) {
        $input = '
	 <div style="float:' . $float . ';padding:5px;">
             ' . $caption . ' <input type="' . $type . '" value="' . $value . '" name="' . $name . '" id="' . $name . '" style="width:' . $size . 'px;"
                 class="' . $class . '" onclick="' . $onclick . '"> ' . $description . '</div>';
        return $input;
    }

    /**
     * Поле для ввода текста
     * @param string $caption текст перед элементом
     * @param string $name имя
     * @param mixed $value значение
     * @param int $size размер
     * @param string $description текст после элемента
     * @param string $float  float
     * @param string $class имя класса стиля
     * @return string
     */
    function setInputText($caption, $name, $value, $size = 300, $description = false, $float = "none", $class = false) {
        return PHPShopText::setInput('text', $name, $value, $float, $size, false, $class, $caption, $description);
    }

}

?>