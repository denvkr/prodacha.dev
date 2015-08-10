<?
	
		$url=$_SERVER['REQUEST_URI'];		
		
		echo '
		
		<div class="choose_city">';
		
				

				         
				
				switch($_COOKIE['sincity']){
					case"m":{echo '<a class="city_link" href="javascript:void(0)">Москва <span class="city_arrow"></span></a> ';};break;
					case"sp":{echo '<a class="city_link" href="javascript:void(0)">Санкт-Петербург <span class="city_arrow"></span></a> ';};break;
					case"chb":{echo '<a class="city_link" href="javascript:void(0)">Чебоксары <span class="city_arrow"></span></a> ';};break;
					case"other":{echo '<a class="city_link" href="javascript:void(0)">Другой регион <span class="city_arrow"></span></a> ';};break;					
					//default:{echo '<a class="city_link" href="javascript:void(0)">Москва <span class="city_arrow"></span></a> ';}break;
				}
				
				
				
		echo '			<div class="city_popup">';
					
					if ($_COOKIE['sincity']!="m")
						echo '<a onclick="save_(\'msc\');" title="Москва"><span>Москва</span></a>';
						
					if ($_COOKIE['sincity']!="sp")	
						echo '<a onclick="save_(\'spb\');" title="Санкт-Петербург"><span>Санкт-Петербург</span></a>';

					if ($_COOKIE['sincity']!="chb")	
						echo '<a onclick="save_(\'chb\');" title="Чебоксары"><span>Чебоксары</span></a>';
						
					if ($_COOKIE['sincity']!="other")	
						echo '<a onclick="save_(\'other\');"  title="Другой регион"><span>Другой регион</span></a>';
						
						echo '<div class="arrowupline">
						</div>
					</div>
				</div>		
	
		';
		
?>
