<?
	
		$url=$_SERVER['REQUEST_URI'];		
		
		echo '
		
		<div class="choose_city">';
		
				

				         
				
				switch($_COOKIE['sincity']){
					case"m":{echo '<a class="city_link" href="javascript:void(0)">������ <span class="city_arrow"></span></a> ';};break;
					case"sp":{echo '<a class="city_link" href="javascript:void(0)">�����-��������� <span class="city_arrow"></span></a> ';};break;
					default:{echo '<a class="city_link" href="javascript:void(0)">������ <span class="city_arrow"></span></a> ';}break;
				}
				
				
				
		echo '			<div class="city_popup">';
					
					if ($_COOKIE['sincity']!="m")
						echo '<a onclick="save_(\'msc\');" title="������"><span>������</span></a>';
						
					if ($_COOKIE['sincity']!="sp")	
						echo '<a onclick="save_(\'spb\');" title="�����-���������"><span>�����-���������</span></a>';
						
					echo '<a onclick="save_(\'msc\');"  title="������ ������"><span>������ ������</span></a>
						<div class="arrowupline">
						</div>
					</div>
				</div>		
	
		';
		
?>
