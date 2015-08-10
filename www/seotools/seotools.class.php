<?php

//
//	Подстановка SEO-значений
//

require('config.php');

class Seotools 
{
	public $urles = Array();
	public $filename ;
	public $items;
	
	function Seotools()
	{
		$this->filename = $_SERVER['DOCUMENT_ROOT'] . SEOTOOLS_PATH . '/data.txt';
		if (is_file($this->filename)) 
		{
			$lines = file($this->filename);
			foreach ($lines as $line_num => $line) 
			{
				list ($url, $title, $h1, $keywords, $description, $desc, $desc2, $desc3) = explode ('	', $line);
				$this->urles[$url]['title'] 	= $title;
				$this->urles[$url]['h1'] 	= $h1;
				$this->urles[$url]['keywords'] 	= $keywords;
				$this->urles[$url]['description']= $description;
				$this->urles[$url]['desc'] 	= $desc;
				$this->urles[$url]['desc2'] 	= $desc2;
				$this->urles[$url]['desc3'] 	= $desc3;
			}
		}
	}
	
	// 
	//	$var = название переменной (title, h1, ...)
	//	$default = значение если не определенно
	//
	function get($var = '', $default = '')
	{
		$url = rawurldecode ( $_SERVER['REQUEST_URI'] );
		$return = $default;
		foreach ($this->urles as $_url => $_arr)
		{
			if ($url == $_url)
			{
				if ($var == '') return $_arr;
				else
				{
					if (isset($_arr[$var]))
					{
						if (SEOTOOLS_CHARSET && SEOTOOLS_CHARSET != 'UTF-8') 
							$_arr[$var] = iconv('UTF-8', SEOTOOLS_CHARSET, $_arr[$var]);
							
						if (strlen(trim($_arr[$var])) != 0) 
							$return = $_arr[$var];
					}
				}
			}
		}
		return $return;
	}	
	
        
    //
	//	Сохраняем информацию в export.csv
	//
	function save_data_to_csv($items)
	{ 
            $fp = fopen('export.csv', 'w');
            if (!$fp) return false;
			
            if ($items)
            {
				$_items = array();
				foreach ($items as $item)
				{
					$_item = array();
					foreach ($item as $var => $val)
					{
						$val = $this->process_value($val);
						$_item[$var] = $val;
					}
					$_items[] = $_item;
				}
				
                $headers = array_keys ($_items[0]);
                fputcsv($fp, $headers , ';');
                
                foreach ($_items as $row => $col)
                {
                    if ($col) fputcsv($fp, $col, ';');                   
                }
            }
            fclose($fp);
			
			if (SEOTOOLS_CHARSET && SEOTOOLS_CHARSET == 'UTF-8') 
			{
				$file = file_get_contents('export.csv');
				$file = iconv('UTF-8', 'WINDOWS-1251', $file);
				file_put_contents('export.csv', $file);				
			}
            return true;
        }
        
	//
	//	Сохраняем информацию в файл data.txt
	//
	function save_data($items)
	{
		$filename = $_SERVER['DOCUMENT_ROOT'] . SEOTOOLS_PATH . SEOTOOLS_DATAFILE;
		
		if (!$items) return;
		
		$result = '';
		foreach ($items as $item)
		{
			if ($item['url'] && !preg_match('/^\/url$/i', $item['url']))
			{
				foreach ($item as $var => $val)
				{
					$val = $this->process_value($val);
					$item[$var] = $val;
				}
				$result .= $item['url'] . '	' . $item['title'] . '	' . $item['h1'] . '	' . $item['keywords'] . '	' . $item['description'] . '	' . $item['desc'] . '	' . $item['desc2'] . '	' . $item['desc3'] . '
';
			}
		}
		
		//echo $result; die;
		
		// сохраняем
		if (!$handle = fopen($filename, 'w')) 
		{
			echo "Не могу открыть файл ($filename)";
			exit;
		}
		
		// Записываем $somecontent в наш открытый файл.
		if (fwrite($handle, $result) === FALSE) 
		{
			echo "Не могу произвести запись в файл ($filename)";
			exit;
		}
		fclose($handle);
		
		return true;
	}
	
	
	// Очистка данных
	function process_value ($val)
	{
		$val = trim($val);
		$val = preg_replace('/	/', '', $val);
		$val = preg_replace('/
/', '', $val);
		$val = preg_replace('/"/', '&quot;', $val);
		if (get_magic_quotes_gpc()) 
		{
			$val = stripslashes ($val);
		}
		return $val;
	}
	
	// Обработка CSV данных 
	function process_csv_value ($val)
	{
		$val = trim($val);
		if (!$val) return;
		if (SEOTOOLS_CHARSET != 'UTF-8') return iconv ('windows-1251', 'utf-8', $val);
		else return $val;
	}
	
	// Обработка CSV данных URL
	function process_csv_value_url ($url)
	{
		$url = trim($url);
		if (!$url) return;
		
		if (strpos($url, '/') === false) $url = '/' . $url;
		
		$url_parsed = parse_url ($url);
		$url_query = ($url_parsed['query']) ? '?' . $url_parsed['query'] : '';
		if ($url_parsed['path'][0] != '/') $url_parsed['path'] = '/' . $url_parsed['path'];
		$url = $url_parsed['path'] . $url_query;
		$url = preg_replace('/\s/', '', $url);
		
		//print_r($url_parsed);
		
		if (SEOTOOLS_CHARSET != 'UTF-8') return iconv ('windows-1251', 'utf-8', $url);
		else return $url;
	}
}

?>