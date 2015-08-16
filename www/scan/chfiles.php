<?
//http://sitesdata.ru/
//(c) Mile05 (http://forum.searchengines.ru/showthread.php?t=697566)

//  set_time_limit(30);

$dirfrom='../'; // путь к верхней дир. начала сканирования от места скрипта
// Данные хранятся в chfiles.tmk (при первом запуске ругнется, потом создаст). Лог в chfiles.log

// $bads - список файлов и директорий, которые не контролировать. Каждый в виде просто имя (с расширением где есть), либо
// дир/имяфайла.расш или дир/дир - именно так, с одним слешем посредине. Совпадения на любом уровне исключаются из обраб.
$bads=array(
	'.','..','chfiles.tmk','tmp','rss.xml','sitemap.xml'
);

// список расширений (или просто окончаний) файлов и директорий, которые не контролировать
$badext=array('.gif','.jpg','.jpeg','.png','.bmp','.log','.xml.gz');

$makelog=1; //писать в лог результаты 0- не писать (изменить имя лога см. внизу)
// настройки закончены


$start = microtime(true);
tolog(date('Y-m-d H:i:s',time()));
$far=unserialize(file_get_contents('chfiles.tmk'));
$out='';
foreach($far as $k=>$f) $far1[$k]=1; //для контроля удалений

$curdir=realpath('.');
chdir($dirfrom);
$basdir=realpath('.');

recurdir($basdir);

$ouf='';
foreach($far1 as $k=>$f) {
	$ouf.="$k\n";
	unset($far[$k]);
}
if($ouf) $out.="\nDeleted files:\n$ouf\n";

if($out) echo $out;

chdir($curdir);
file_put_contents('chfiles.tmk',serialize($far));

$elapsed = microtime(true) - $start;
tolog($out."\nIt took $elapsed s");


function recurdir($d){
	global $bads,$badext;
	$aa=scandir($d);
	$ddir=substr($d,strrpos($d,'/')+1).'/';
	foreach($aa as $f){
		if(in_array($f,$bads) or in_array($ddir.$f,$bads)) continue;
		$yy=0;
		foreach($badext as $e)
			if(substr($f,-strlen($e))==$e) {$yy=1;break;}
		if($yy)continue;
		$d1=$d.'/'.$f;
		if(is_dir($d1)) {
			recurdir($d1);
		}
		else { // действие с файлом $d1
			chf($d1);
		}
	}
}

//--------------------------------------
function chf($f){
global $far,$far1,$out;
$now=time();
$o='';
$mod=0;
if(!isset($far[$f])) {
	$far[$f]['mtime']=filemtime($f);
	$far[$f]['size']=filesize($f);
	$far[$f]['crc']=crc32(file_get_contents($f));
	$far[$f]['timestamp']=$now;
	$o="New file, time ".date('Y-m-d H:i:s',$far[$f]['mtime'])."\n";
}
else {
	unset($far1[$f]);
	if(($fsiz=filesize($f))!=$far[$f]['size']){$mod=1;$o.="New file size.. ";}
	if(($ftim=filemtime($f))!=$far[$f]['mtime']){$mod=1;$o.="New filetime ".date('Y-m-d H:i:s',$ftim).".. ";}
	if(($fcrc=crc32(file_get_contents($f)))!=$far[$f]['crc']){$mod=1;$o.="New file content.. ";}
}
if($mod){
	$t['mtime']=     $far[$f]['mtime'];
	$t['size']=      $far[$f]['size'];
	$t['crc']=       $far[$f]['crc'];
	$t['timestamp']= $far[$f]['timestamp'];

	// храним 4 посл изменения
	if(@count($far[$f]['old'])>3){
		$ts='2019-01-01';
		foreach($far[$f]['old'] as $k=>$v){
			if(strtotime($k)<strtotime($ts))$ts=$k;		
		}
		unset($far[$f]['old'][$ts]);
	}
	$far[$f]['old'][date('Y-m-d H:i:s',$now)]=$t;

	$far[$f]['mtime']=$ftim;
	$far[$f]['size']=$fsiz;
	$far[$f]['crc']=$fcrc;
	$far[$f]['timestamp']=$now;

}

if($o) $out.="\nFile: $f\n$o\n";

} //func


function tolog ($var1,$fnam='chfiles.log'){ // запись в лог
	global $makelog;
	if(!$makelog) return true;
	$h=fopen($fnam,"a+");
	fwrite($h,$var1."\n");
	fclose($h);
	return true;
}




?>