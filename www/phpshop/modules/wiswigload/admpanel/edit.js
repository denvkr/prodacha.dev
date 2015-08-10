
// Инонка шаблона
function GetTemplateIcon(skin,path,id){
    path+="editors/"+skin+"/icon.png";
    document.getElementById(id).alt=skin;
    document.getElementById(id).src=path;
}

