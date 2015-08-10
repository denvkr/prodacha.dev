<?php

/**
 * Библиотека для работы с файлами
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopClass
 */
class PHPShopFile {

    /**
     * Права на запись файла
     * @param string $file имя файла
     */
    function chmod($file, $error=false) {
        if (function_exists('chmod')) {
            if(@chmod($file, 0775))
                    return true;
            elseif($error) echo 'Нет файла '.$file;
        }
        elseif($error) echo __FUNCTION__.'() запрещена';
    }
    

    /**
     * Запись данных в файл
     * @param string $file путь до файла
     * @param string $csv данные для записи
     * @param string $type параметр записи
     * @param bool $error вывод ошибки
     */
    function write($file, $csv, $type = 'w+', $error=false) {
        $fp = @fopen($file, $type);
        if ($fp) {
            //stream_set_write_buffer($fp, 0);
            fputs($fp, $csv);
            fclose($fp);
        }
        elseif($error) echo 'Нет файла '.$file;
    }

    /**
     * Запись данных в csv файл
     * используется стандартная функция php fputcsv
     * @param string $file путь до файла
     * @param array $csv данные для записи
     * @param bool $error вывод ошибки
     */
    function writeCsv($file, $csv, $error=false) {
        $fp = @fopen($file, "w+");
        if ($fp) {
            foreach ($csv as $value) {
                fputcsv($fp, $value, ';', '"');
            }
            //stream_set_write_buffer($fp, 0);
            fclose($fp);
        }
        elseif($error) echo 'Нет файла '.$file;
    }

    /**
     * Чтение CSV файла
     * @param string $file адрес файла
     * @param string $function имя функции обработчика 
     */
    function readCsv($file, $function) {
        $fp = fopen($file, "r");
        while (($data = fgetcsv($fp, 1000, ";")) !== FALSE) {
            call_user_func($function, $data);
        }
        fclose($fp);
    }

    /**
     * GZIP компресия файла
     * @param string $source путь до файла
     * @param int $level степень сжатия
     * @return bool
     */
    function gzcompressfile($source, $level = false) {
        $dest = $source . '.gz';
        $mode = 'wb' . $level;
        $error = false;
        if ($fp_out = gzopen($dest, $mode)) {
            if ($fp_in = fopen($source, 'rb')) {
                while (!feof($fp_in))
                    gzwrite($fp_out, fread($fp_in, 1024 * 512));
                fclose($fp_in);
            }
            else
                $error = true;
            gzclose($fp_out);
            unlink($source);
            rename($dest, $source . '.bz2');
        }
        else
            $error = true;
        if ($error)
            return false;
        else
            return $dest;
    }

}

?>