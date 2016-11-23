<?
//читаем конфигурационный файл автоматизации выбора фильтра товаров
function read_filter_info($filename){
	//echo $filename;
	if (file_exists($filename)) {
		$filter_file=fopen($filename,'r+');
		while (!feof($filter_file)) {
			$fitler_element=explode("|",str_replace("\n",'',fgets($filter_file)));
			$my_value=str_replace('"','',$fitler_element[2]);
			$my_name=str_replace('"','',$fitler_element[1]);
			$my_id=str_replace('"','',$fitler_element[0]);
			if ($my_value==1) {				
				$filter_array[$my_id]=array('id'=>$my_id,'name'=>$my_name,'value'=>$my_value);
				//var_dump($filter_array);
			}	
		}
		fclose($filter_file);
	}
	//var_dump(is_array_empty($filter_array));
	if (!is_array_empty($filter_array)) {
		return $filter_array;
	} else {
		return 0;
	}			
}

function custom_menu_1($filename){
	//echo $filename;
	if (file_exists($filename)) {
		$filter_file=fopen($filename,'r+');
		while (!feof($filter_file)) {
			$fitler_element=explode("|",str_replace("\n",'',fgets($filter_file)));
			$my_css_option_width=$fitler_element[3];
			$my_href=$fitler_element[2];
			$my_sub_id=$fitler_element[1];
			$my_id=$fitler_element[0];
			$filter_array[$fitler_element[0]]=array('id'=>$my_id,'sub_id'=>$my_sub_id,'href'=>$my_href,'css_option_width'=>$my_css_option_width);
			//var_dump($filter_array);
		}
		fclose($filter_file);
	}
	//var_dump(is_array_empty($filter_array));
	if (!is_array_empty($filter_array)) {
		return $filter_array;
	} else {
		return 0;
	}
}

function is_array_empty($a) {
	if(count($a) > 0) {
		return false;
	} else {
		return true;
	}
}

?>