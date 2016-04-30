<?
//������ ��� ����� � ����� ���� "�������", "��� ������", � "�����"
$left_menu_sp_options=array(13=>'<span style="font-size:7px;color:red;">�������!</span>',
                            119=>'<span>&nbsp;&nbsp;&nbsp;</span><span style="font-size:8px;color:red;">�������!</span>',
                            70=>'<span>&nbsp;&nbsp;&nbsp;</span><span style="font-size:8px;color:red;">�������!</span>',
                            236=>'<span style="font-size:8px;color:red;">�������!</span>',
                            595=>'<span>&nbsp;&nbsp;&nbsp;</span><span style="font-size:8px;color:red;">�������!</span>',
                            620=>'<span style="font-size:8px;color:red;">�������!</span>'
    );
    
//������ ���������������� ���� ������������� ������ ������� �������
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
function read_ceo_custom_menu($filename){
	//echo $filename;
        $item_count=0;
	if (file_exists($filename)) {
		$filter_file=fopen($filename,'r+');
		while (!feof($filter_file)) {
			$fitler_element=explode("|",str_replace("\n",'',fgets($filter_file)));
			$my_str2=str_ireplace('"','',$fitler_element[2]);
			$my_str1=str_ireplace('"','',$fitler_element[1]);
			$my_id=str_ireplace('"','',$fitler_element[0]);				
			$filter_array[$item_count]=array('id'=>$my_id,'str1'=>$my_str1,'str2'=>$my_str2);
                        $item_count++;
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
function custom_menu_count($filename){
	//echo $filename;
        $item_count=0;
	if (file_exists($filename)) {
		$filter_file=fopen($filename,'r+');
		while (!feof($filter_file)) {
			$fitler_element=explode("|",str_replace("\n",'',fgets($filter_file)));
			$my_count=str_replace('"','',$fitler_element[1]);
			$my_id=str_replace('"','',$fitler_element[0]);				
			$filter_array[$item_count]=array('id'=>$my_id,'cnt'=>$my_count);
                        $item_count++;
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

function array_custom_search($given_array,$id) {
 foreach ($given_array as $given_array_key=>$given_array_value){
     if ($given_array_key==$id)
         return $given_array_value;
 }
}
?>