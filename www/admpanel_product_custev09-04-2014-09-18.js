//переопределяем поведение 

function addEventsToHTML(){
    var sklad_new=document.getElementById('sklad_new');
	var yml_new=document.getElementById('yml_new');
//	alert(sklad_new.checked);
//  sklad_new.onchange = changeHandler;
//  function changeHandler(){
	if (sklad_new.checked) {
		yml_new.checked=false;
	} else {
		yml_new.checked=true;
	}
//    }
}
//window.onload = addEventsToHTML;
