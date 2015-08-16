<?php

/**
 * Библиотека парсинга данных
 * @author PHPShop Software
 * @version 1.3
 * @package PHPShopClass
 */
class PHPShopParser {

    /**
     * Проверка шаблона на присутствие переменной
     * @param string $path путь к файлу шаблона
     * @param string $value переменная шабонизатора
     * @return boolean 
     */
    function check($path, $value) {
        $string = null;
        $path = $GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . chr(47) . $path;
        if (file_exists($path))
            $string = @file_get_contents($path);
        else
            echo "Error Tmp File: $path";
        if (stristr($string, '@' . $value . '@'))
            return true;
    }

    /**
     * Обработка файла шаблона, вставка переменнных
     * @param string $path путь к файлу шаблона
     * @param bool $return режим вывода информации или возврата информации
     * @return string
     */
    function file($path, $return = false) {

        $string = null;
        if (is_file($path))
            $string = @file_get_contents($path);
        else
            echo "Error Tmp File: $path";
        $replaces = array(
            "/images\//i" => $GLOBALS['SysValue']['dir']['dir'] . $GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . "/images/",
            "/java\//i" => "/java/",
            "/css\//i" => "/css/",
            "/phpshop\//i" => "/phpshop/",
        );

        $string = @preg_replace_callback("/(@php)(.*)(php@)/sU", "phpshopparserevalstr", $string);
        $string = @preg_replace("/@([a-zA-Z0-9_]+)@/e", '$GLOBALS["SysValue"]["other"]["\1"]', $string);
        $string = preg_replace(array_keys($replaces), array_values($replaces), $string);
        if (!empty($return))
            return $string;
        else
            echo $string;
    }

    /**
     * Создание системной переменной для парсинга
     * @param string $name имя
     * @param mixed $value значение
     * @param bool $flag [1] - добавить, [0] - переписать
     */
    function set($name, $value, $flag = false) {
        if ($flag)
            $GLOBALS['SysValue']['other'][$name].=$value;
        else
            $GLOBALS['SysValue']['other'][$name] = $value;
    }
    /**
     * Выдача системной переменной
     * @param string $name
     * @return string
     */
    function get($name) {
        return $GLOBALS['SysValue']['other'][$name];
    }

}

// Обработка php тегов
function phpshopparserevalstr($str) {
    ob_start();
    if (eval(stripslashes($str[2])) !== NULL) {
        echo ('<center style="color:red"><br><br><b>PHPShop Template Code: В шаблоне обнаружена ошибка выполнения php</b><br>');
        echo ('Код содержащий ошибки:');
        echo ('<pre>');
        echo ($str[2]);
        echo ('</pre></center>');
        return ob_get_clean();
    }
    return ob_get_clean();
}