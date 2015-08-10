var thumbchangeon_php = 0;
var rotate_php = 0;

function next_thumb_sa() {
	thumbchangeon_php = 0;
	if (rotate_php == 1) { picture1(picture1);}	
	if (rotate_php == 2) { picture2(picture2);}
	if (rotate_php == 3) { picture3(picture3);}
}
function picture1(id) {
	rotate_php = 1;
	picture1_php_display();
	blendimage('blenddiv','blendimage', picture1_php, tween_time)
    window.setTimeout('if_picture1_display_yes()',display_time);}
	function if_picture1_display_yes() {
		if (rotate_php == 1) {picture1_next();}
}
function picture2(id) {
	rotate_php = 2;
	picture2_php_display();
	blendimage('blenddiv','blendimage', picture2_php, tween_time)
    window.setTimeout('if_picture2_display_yes()',display_time);}
	function if_picture2_display_yes() {
		if (rotate_php == 2) {picture2_next();}
}
function picture3(id) {
	rotate_php = 3;
	picture3_php_display();
	blendimage('blenddiv','blendimage', picture3_php, tween_time)
    window.setTimeout('if_picture3_display_yes()',display_time);}
	function if_picture3_display_yes() {
		if (rotate_php == 3) {picture3_next();}
}
function picture3_next(id) {
	if (thumbchangeon_php == 0) {
	picture1('picture1');}
}	
var is_ie/*@cc_on = {
  // quirksmode : (document.compatMode=="BackCompat"),
  version : parseFloat(navigator.appVersion.match(/MSIE (.+?);/)[1])
}@*/;
function opacity(id, opacStart, opacEnd, millisec) {
	//speed for each frame
	var speed = Math.round(millisec / 100);
	var timer = 0;
	//determine the direction for the blending, if start and end are the same nothing happens
	if(opacStart > opacEnd) {
		for(i = opacStart; i >= opacEnd; i--) {
			setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
			timer++;
		}
	} else if(opacStart < opacEnd) {
		for(i = opacStart; i <= opacEnd; i++)
			{
			setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
			timer++;
		}
	}
}
//change the opacity for different browsers
function changeOpac(opacity, id) {
	var object = document.getElementById(id).style; 
	object.opacity = (opacity / 100);
	object.MozOpacity = (opacity / 100);
	object.KhtmlOpacity = (opacity / 100);
	object.filter = "alpha(opacity=" + opacity + ")";
}
function blendimage(divid, imageid, imagefile, millisec) {
	var speed = Math.round(millisec / 100);
	var timer = 0;
	//set the current image as background
	document.getElementById(divid).style.backgroundImage = "url(" + document.getElementById(imageid).src + ")";
	//make image transparent
	changeOpac(0, imageid);
	//make new image
	document.getElementById(imageid).src = imagefile;
	//fade in image
	for(i = 0; i <= 100; i++) {
		setTimeout("changeOpac(" + i + ",'" + imageid + "')",(timer * speed));
		timer++;
	}
}
//Hide and Show Descriptions
function php_picture1_click() {
	if (picture1target == "_top") {
		window.location.href=picture1link;
	}else{
		window.open(picture1link);
	}
}
function picture1_php_display() {
	s5_currentslide_if = 1;
	if (picture1link == "") {
		document.getElementById("blendimage").style.cursor = "default";
		document.getElementById("blendimage").onclick = "";
	}
	if (picture1link != "") {
		document.getElementById("blendimage").style.cursor = "pointer";
		document.getElementById("blendimage").onclick = php_picture1_click;
	}
}
function php_picture2_click() {
	if (picture2target == "_top") {
		window.location.href=picture2link;
	}else{
		window.open(picture2link);
	}
}
function picture2_php_display() {
	s5_currentslide_if = 2;
	if (picture2link == "") {
		document.getElementById("blendimage").style.cursor = "default";
		document.getElementById("blendimage").onclick = "";
	}
	if (picture2link != "") {
		document.getElementById("blendimage").style.cursor = "pointer";
		document.getElementById("blendimage").onclick = php_picture2_click;
	}
}
function php_picture3_click() {
	if (picture3target == "_top") {
		window.location.href=picture3link;
	}else{
		window.open(picture3link);
	}
}
function picture3_php_display() {
	s5_currentslide_if = 3;
	if (picture3link == "") {
		document.getElementById("blendimage").style.cursor = "default";
		document.getElementById("blendimage").onclick = "";
	}
	if (picture3link != "") {
		document.getElementById("blendimage").style.cursor = "pointer";
		document.getElementById("blendimage").onclick = php_picture3_click;
	}
}