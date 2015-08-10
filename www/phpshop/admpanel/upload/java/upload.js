var temp = 0;
var backup_dir="none";
var backup_code="none";
var max_connect_error = 5;
var max_load_error = 5;
var connect_error = 5;
var load_error = 5;
var update_base = "";
var temp_print = "";
function upload_go(tt){
	
	
	switch (tt) {
		
		case 0:
			$("upload_button").style.display = 'none';
			loading("connecting");
			flag = upload_ajax("./ajax/upload.php"+$('ftp_pars').value);
		break;
		
		case 1: 
			loading("Анализ карты обновлений. Создание несуществующих папок.");
			upload_ajax("./ajax/map_analizer.php");
		break;
		
		case 2: 
			loading("Создание резервной копии.");			
			upload_ajax("./ajax/backup.php?dir="+backup_dir);
		break;
		
		case 3: 
			loading("Копироване файлов обновления.");			
			upload_ajax("./ajax/copy.php"+$('ftp_pars').value);
		break;
		case 4: 
			upload_go(7);
			message("Скрипт не может открыть на сервере указанную в карте папку.<br>Попробуйте повторить попытку позже.<br> Сообщите об ошибке в техподдержку.");
		break;
		case 5: 
			upload_go(7);
			message("Скрипт не может соединиться с сервером, повторите попытку позже.");
		break;
		case 6: 
			upload_go(7);
			message("Превышен лимит повторных попыток загрузить файл.<br>Ошибка карты обновлений.<br>Попробуйте повторить попытку позже.<br> Сообщите об ошибке в техподдержку.");
		break;
		case 7: 
			loading("Очистка журнала загрузок.");			
			upload_ajax("./ajax/clear.php");
		break;
		case 8: 
			loading("Очистка журнала загрузок.");			
			upload_ajax("./ajax/backup.php?base=update");
		break;
		case 9: 
			loading("Очистка журнала загрузок.");			
			upload_ajax("./ajax/backup.php");
		break;
		case 10: 
			loading("Создание дерева каталогов.");			
			upload_ajax("./ajax/folsers.php?base=base");
		break;
		case 11:
			$("upload_button1").style.display = 'none';			
			loading("Создание дерева каталогов.");			
			upload_ajax("./ajax/folders.php?update_base="+update_base);
		break;
		case 12:
			$("upload_button").style.display = 'none';
			loading("Анализ карты восстановления.");
			upload_ajax("./ajax_backup/map_backup_analizer.php"); 
		break;
		case 13:
			$("upload_button1").style.display = 'none';
			loading("Копироване файлов.");
			upload_ajax("./ajax_backup/backup_backup.php"); 
		break;
		case 14:
			$("upload_button1").style.display = 'none';
			loading("Копироване файлов.");
			upload_ajax("./ajax_backup/clear_backup.php"); 
		break;

	}
	
}

function loading(par){
	$('upload_loading_info').innerHTML = par;
	$('upload_loading').style.display = 'block';
}
function dieloading(){
	$('upload_loading').style.display = 'none';
}

function upload_ajax(url,pars){
	
		
		var req = new Subsys_JsHttpRequest_Js();
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				
					// Записываем в <div> результат работы. 
					//Insertion.Top('upload_log',(req.responseText||'')+"<br>");
					Insertion.Top('upload_log',"-----------------<BR>"+(req.responseJS.stat||'')+"<br>");
					
					dieloading();
					if (req.responseJS.susses == "susses"){
						temp = temp+1;
						upload_go(temp);
					}
					if (req.responseJS.susses == "dirList_base"){
						update_base = "yes";
						$('dirList_base').style.display = 'inline';
					}	
					if (req.responseJS.susses == "dirList"){
						$('dirList').style.display = 'inline';
					}
					if (req.responseJS.susses == "error_upd"){
						$("dirList").style.display = 'none';			
						$("upload_button2").style.display = 'inline';			

					}
					if (req.responseJS.susses == "error_reload"){
						$("upload_button3").style.display = 'inline';			
                                                $("dirList2").style.display = 'none';			
					}
                                                                                 			
					if (req.responseJS.susses == "gobackup"){
						backup_dir = req.responseJS.folder;						
						upload_go(2);
					}
					if (req.responseJS.susses == "backup"){						
						upload_go(2);
					}
					if (req.responseJS.susses == "copy"){						
						upload_go(3);
					}
					if (req.responseJS.susses == "folder_error"){						
						upload_go(4);
					}
					if (req.responseJS.susses == "connect_error"){						
						upload_go(5);
					}
					if (req.responseJS.susses == "load_error"){	
						if (load_error < max_load_error){					
							upload_go(3);
							load_error++;
						}
						else upload_go(6);
					}	
					if (req.responseJS.susses == "continue"){						
						upload_go(7);
					}
					if (req.responseJS.susses == "dirList_backup"){
						$('dirList').style.display = 'inline';
					}
					if (req.responseJS.susses == "backup_backup"){
						upload_go(13);
					}
					if (req.responseJS.susses == "clear_backup"){
						upload_go(14);
					}
					if (req.responseJS.susses == "end_backup"){
						$('dirList_base').style.display = 'inline';
					}
					if (req.responseJS.susses == "connect_error_user"){
						confirm("Невозможно соединиться с фтп сервером пользователя. Дальшейнее обновление не возможно!");
					}                                                    

					if (req.responseJS.susses == "end"){
						if(confirm("Обновление системы завершено!\n\nПерейти на сайт разработчика и посмотреть список установленных обновлений?"))
                          window.open("http://www.phpshop.ru/docs/update.html#EE"+new_version);
						  window.opener.location.reload();
                                               
					}


				
			}
		}
		req.caching = false;
		// Подготваливаем объект.		
		req.open('POST', url, true);
		req.send(pars);
	
}