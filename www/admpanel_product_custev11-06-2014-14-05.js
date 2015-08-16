//переопределяем поведение 

function addEventsToHTML(from_p_enabled){
    var sklad_new_el=document.getElementById("sklad_new");
	var yml_new_el=document.getElementById("yml_new");
	var characters_option_el=document.getElementsByTagName("option");
	var yml_options_el=document.getElementsByName("p_enabled_new");
	var picture_samovyvoz=false;
	var elm1200;
	from_p_enabled=from_p_enabled||0;

//	alert(sklad_new.checked);
//  sklad_new.onchange = changeHandler;
//  function changeHandler(){
	if (sklad_new_el.checked) {
		
		yml_new_el.checked=false;

		//ищем картинку самовывоза в характеристиках
		for (var i=0;i<characters_option_el.length;i++) {
			if (characters_option_el[i].value=="1200" && characters_option_el[i].selected==true) {
				picture_samovyvoz=true;
				elm1200=i;
			}
		}		

		if (picture_samovyvoz==true) {
			if (from_p_enabled===1) {
				for (var i=0;i<characters_option_el.length;i++) {
					if (characters_option_el[i].value=="1194") {
						//console.log('elm1200 '+elm1200+' characters_option_el[elm1200].selected '+characters_option_el[elm1200].selected+' characters_option_el[i].previousElementSibling.selected '+characters_option_el[i].previousElementSibling.selected);
						characters_option_el[elm1200].selected=false;
						characters_option_el[i].previousElementSibling.selected=true;
					}
				}		
			}
		}
		
	} else {
	
		yml_new_el.checked=true;
		console.log(picture_samovyvoz);
		//ищем картинку самовывоза в характеристиках
		for (var i=0;i<characters_option_el.length;i++) {
			if (characters_option_el[i].value=="1200" && characters_option_el[i].selected==true) {
				picture_samovyvoz=true;
			}		
		}

			if (picture_samovyvoz==false && from_p_enabled===1) {
				for (var i=0;i<characters_option_el.length;i++) {
					if (characters_option_el[i].value=="1194" && characters_option_el[i].previousElementSibling.selected==true) {
						characters_option_el[i].previousElementSibling.selected=false;
					}
				/*
					if (characters_option_el[i].value=="1200" && characters_option_el[i].selected==false) {
						//console.log('elm1200 '+elm1200+' characters_option_el[elm1200].selected '+characters_option_el[elm1200].selected+' characters_option_el[i].previousElementSibling.selected '+characters_option_el[i].previousElementSibling.selected);
						picture_samovyvoz=true;
						characters_option_el[i].selected=true;
					}
				*/
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