//переопределяем поведение 

function addEventsToHTML(){
    var sklad_new_el=document.getElementById("sklad_new");
	var yml_new_el=document.getElementById("yml_new");
	var characters_option_el=document.getElementsByTagName("option");
	var yml_options_el=document.getElementsByName("p_enabled_new");
	var picture_samovyvoz=false;
//	alert(sklad_new.checked);
//  sklad_new.onchange = changeHandler;
//  function changeHandler(){
	if (sklad_new_el.checked) {
		yml_new_el.checked=false;
	} else {
		yml_new_el.checked=true;
		//ищем картинку самовывоза в характеристиках
		for (var i=0;i<characters_option_el.length;i++) {
			if (characters_option_el[i].value=="1200" && characters_option_el[i].selected==true) {
				picture_samovyvoz=true;
			}
		}

		if (picture_samovyvoz==true) {
			yml_options_el[0].checked=true;
			//yml_options_el[1].checked=false;
		} else {
			yml_options_el[1].checked=true;
			//yml_options_el[0].checked=false;
		}
		
	}
//    }
}
//window.onload = addEventsToHTML;