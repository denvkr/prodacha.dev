<?php
  
/**
* File System monitor.
* Use cron to email output
* @author surgeon <r00t@skillz.ru>
*/

$root_dir = $this_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR;

// read config

$config = @include ($this_dir . 'config.php');

if (isset($config['root'])) {
    $root_dir = $config['root'];
}

$files_preg =  @$config['files']; 

$precache = $cache = array();

// read cache

$cache_file = $this_dir . '.cache';
if (file_exists($cache_file)) {
    $precache = $cache = unserialize(file_get_contents($cache_file));
}

// scan 

$result = array();

$checked_ids = array();

$tree = array();
fs::build_tree($root_dir, $tree, false, $files_preg);

$files = $tree['files'];

foreach ($files as $f) {
    
    $id = fs::file_id($f);
    
    $checked_ids []= $id;
    
    if (isset($cache[$id])) {
        // present
        
        $csumm = fs::crc_file($f);
        if ($cache[$id]['crc'] != $csumm) {
            // modded
            $cache[$id]['crc'] = $csumm;
            $cache[$id]['file'] = $f;
            $result[] = array('file' => $f, 'result' => 'modified');
        }
        else {
            // old one
        }
    }
    else {
        // new
        $csumm = fs::crc_file($f); 
        $cache[$id]['crc']  = $csumm;
        $cache[$id]['file'] = $f;        
        $result[] = array('file' => $f, 'result' => 'new'); 
    }
    
}

// check for deleted files

$deleted = 
array_diff(array_keys($precache), $checked_ids);

if (!empty($deleted)) {
    foreach ($deleted as $id) {
        $result[] = array('file' => $precache[$id]['file'], 'result' => 'deleted'); 
        unset($cache[$id]);            
    }    
}

// print result

if (!empty($result)) {
    foreach ($result as $r) {
        printf("[%10s]\t%s\t%s kb\t%s\n"
            , $r['result']
            , $r['file']            
            , @round(filesize($r['file'])/1024, 1)
            , @date('d.m.Y H:i', filemtime($r['file']))
            );        
    }
}


// save result

file_put_contents(
    $cache_file
    , serialize($cache)
    );  


// helpers
  
/**
* FileSystem
* 
* This is not core lib,
* use it as static class fs::
*/
class fs {
    
    /**
    * Сканировать директорию на файлы 
    */          
    
    public static function scan_dir_for_files($o_dir, $files_preg = '') {
        $ret = array();
        $dir = @opendir($o_dir);
            while( false !== ($file = @readdir($dir)) )
            {
                $path = $o_dir . /*DIRECTORY_SEPARATOR .*/ $file;
                if(!is_dir($path) && $file != '..' && $file != '.' 
                    && (empty($files_preg) || (!empty($files_preg) && preg_match("#{$files_preg}#", $file)))) {
                    $ret [] = $path;
                }
            }
        @closedir($dir);
        return $ret;

    }

    /**
    * Сканировать директории
    */

    public static function scan_dir_for_dirs($o_dir) {

        $ret = array();
        $dir = @opendir($o_dir);

            while(false !== ($file = @readdir($dir))) {
                $path = $o_dir /*. DIRECTORY_SEPARATOR*/ . $file;
                if(is_dir($path) && $file != '..' && $file != '.') {
                    $ret [] = $path;                   
                }
            }

        @closedir($dir);

        return $ret;

    }
    
    /**
    * Строим дерево из директорий/файлов
    * 
    * @desc build tree
    * @param string Корневая директория для индекса
    * @param array возвращаемые данные
    * @param array фильтр директорий (массив)
    * @param string рег.выр для фильтра файлов
    * @return array['files','dirs']
    */
    
    public static function build_tree($root_path, array &$data, $dirs_filter = array(), $files_preg = '.*') {      
        
        if (substr($root_path, -1, 1) != DIRECTORY_SEPARATOR) $root_path .= DIRECTORY_SEPARATOR;

        $dirs   =    self::scan_dir_for_dirs($root_path);
        $files  =    self::scan_dir_for_files($root_path, $files_preg);
        
        if (empty($data)) {
            $data['files']  = array();
            $data['dirs']   = array();
        }
        
        $data['dirs'][]  = $root_path;
        $data['files']   = array_merge($data['files'], $files);
        
        foreach ($dirs as $dir) {
            // проверяем фильтр
            if (empty($dirs_filter) || !in_array(preg_replace('/^.*\/(.*)$/','\1',$dir),$dirs_filter))
                self::build_tree($dir, $data, $dirs_filter, $files_preg);
        }   
    }
  

    /**
    * uniq file name
    */
    public static function file_id($path) {
        return md5($path);
    }   
    
    public static function crc_file($path) {
        return sprintf("%u", crc32 (file_get_contents ($path)));
    }
}

