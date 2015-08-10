<?php
function log_clear(){
	$log_file = fopen("../../../../backup/upd_log_backup.txt","a");
	fwrite($log_file,"\n\n\n\n***********************\n***********************\n***".date("d.m.Y(H:i)")."***\n***********************\n***********************\n");
	fclose($log_file);
}
function log_write($str){
	$log_file = fopen("../../../../backup/upd_log_backup.txt","a");
	fwrite($log_file,"----------------------------\n$str\n");
}
?>