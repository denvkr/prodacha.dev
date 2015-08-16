<?php 
header ('Content-Type: text/html; charset=UTF-8');

require ('config.php');
require ('auth.php');

//print_r($_SERVER['DOCUMENT_ROOT']);
//print_r($_POST);
//print_r($_FILES);

$path = $_SERVER['DOCUMENT_ROOT'] . SEOTOOLS_PATH;
$filename = $path . SEOTOOLS_DATAFILE; // здесь хранятся все данные
	
	
require_once ('seotools.class.php');
$ST = new Seotools; 

// Сохраняем
if ($_POST['items'] && $_POST['save'])
{
	if ($ST->save_data($_POST['items'])) $pinfo['success'][] = 'Данные сохранены в ' . date("H:i:s");
}

// Сохраняем как CSV
if ($_POST['items'] && $_POST['export_csv'])
{
	if ($ST->save_data_to_csv($_POST['items'])) $pinfo['success'][] = 'Данные сохранены в файл <a href="export.csv">export.csv</a> ['  . date("H:i:s") .']';
        else $pinfo['errors'][] = 'Не удалось записать данные в файл export.csv';
}

// Загружаем из CSV
if ($_POST['import_csv'])
{
	if (is_uploaded_file($_FILES['csv']['tmp_name']))
	{
		$csv = array();
		$handle = fopen($_FILES['csv']['tmp_name'], "r");
		while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) 
		{
		    for ($c = 0; $c < count($data); $c++) 
			{
				// url
				if ($c == 0) 
				{
					$url = $ST->process_csv_value_url ($data[$c]);
					$csv[$url]['url'] = $url;
				}
				// other
				else 
				{
					switch ($c)
					{
						case 1: $var = 'title'; break;
						case 2: $var = 'h1'; break;
						case 3: $var = 'keywords'; break;
						case 4: $var = 'description'; break;
						case 5: $var = 'desc'; break;
						case 6: $var = 'desc2'; break;
						case 7: $var = 'desc3'; break;
					}
					$csv[$url][$var] = $ST->process_csv_value ($data[$c]);
				}
		    }
		}
		fclose($handle);
		ksort($csv);
		
		if ($ST->save_data($csv)) $pinfo['success'][] = 'Данные загружены в ' . date("H:i:s");;
		//echo '<pre>'; print_r($csv); echo '</pre>';
	}
	else 
	{
		$pinfo['errors'][] = 'Не могу сохранить файл ' . $_FILES['userfile']['tmp_name'] . ' на сервере';
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
	<title>SEOTOOLS (v. <?=SEOTOOLS_VER?>)</title>
	<link rel="stylesheet" type="text/css" href="design/reset.css" >
	<link rel="stylesheet" type="text/css" href="design/styles.css" >
	
	<script type="text/javascript" src="design/jquery-1.6.3.min.js"></script>
	<script type="text/javascript" src="design/jquery.url.js"></script>
	<script type="text/javascript" src="design/app.js"></script>
</head>
<body>
<?=$login_panel?>
<div id="wrap">
	<?
	if ($pinfo)
	{
		if (is_array($pinfo['success']))
		{
			echo '<div class="block success">';
			foreach ($pinfo['success'] as $pinfo_success)
			{
				echo $pinfo_success . '<br>';
			}
			echo '</div>';
		}
		if (is_array($pinfo['errors']))
		{
			echo '<div class="block errors">Ошибка!<br>';
			foreach ($pinfo['errors'] as $pinfo_errors)
			{
				echo $pinfo_errors . '<br>';
			}
			echo '</div>';
		}
	}
	?>
	<h1><strong>SEO<span style="color: gray">TOOLS</span></strong><sup style="font-size: 9px"><?=SEOTOOLS_VER?></sup></h1>
	<p>Интеграция дополнительной информации в CMS с помощью URL</p>
	
	<!--h2>Данные</h2-->
	<form method=POST>
	<table id="data" class="tab" width=100%>
		<thead>
			<tr>
				<th width=1%></th>
				<th>URL (относительный)</th>
				<?if (SEOTOOLS_FIELD_TITLE) :?><th class="delete_all_col">[title]</th><? endif; ?>
				<?if (SEOTOOLS_FIELD_H1) :?><th class="delete_all_col">[h1]</th><? endif; ?>
				<?if (SEOTOOLS_FIELD_KEYWORDS) :?><th class="delete_all_col">[keywords]</th><? endif; ?>
				<?if (SEOTOOLS_FIELD_DESCRIPTION) :?><th class="delete_all_col">[description]</th><? endif; ?>
				<?if (SEOTOOLS_FIELD_DESC) :?><th class="delete_all_col">[desc]</th><? endif; ?>
				<?if (SEOTOOLS_FIELD_DESC2) :?><th class="delete_all_col">[desc2]</th><? endif; ?>
				<?if (SEOTOOLS_FIELD_DESC3) :?><th class="delete_all_col">[desc3]</th><? endif; ?>
			</tr>
		</thead>
		<tbody>
			<? 
			if (is_file($filename))
			{
				$lines = file($filename);
				foreach ($lines as $line_num => $line) 
				{
					list ($url, $title, $h1, $keywords, $description, $desc, $desc2, $desc3) = explode ('	', $line);
					
					$url = trim($url);
					if ($url)
					{
						echo '
						<tr oid='. ++$line_num .'>
							<td><a href="'. $url .'" target=_blank><img src="design/magnifier.png" alt="Открыть URL в новой вкладке" /></a><div class="row_count">'. $line_num .'</div></td>
							<td><input type="text" name="items['. $line_num .'][url]" value="'. $url .'" /></td>';
							
						if (SEOTOOLS_FIELD_TITLE) echo '<td><input type="text" name="items['. $line_num .'][title]" value="'. $title .'" /></td>';
						if (SEOTOOLS_FIELD_H1) echo '<td><input type="text" name="items['. $line_num .'][h1]" value="'. $h1 .'" /></td>';
						if (SEOTOOLS_FIELD_KEYWORDS) echo '<td><input type="text" name="items['. $line_num .'][keywords]" value="'. $keywords .'" /></td>';
						if (SEOTOOLS_FIELD_DESCRIPTION) echo '<td><input type="text" name="items['. $line_num .'][description]" value="'. $description .'" /></td>';
						if (SEOTOOLS_FIELD_DESC) echo '<td><textarea class="expandable" name="items['. $line_num .'][desc]" >'. $desc .'</textarea></td>';
						if (SEOTOOLS_FIELD_DESC2) echo '<td><textarea class="expandable" name="items['. $line_num .'][desc2]" >'. $desc2 .'</textarea></td>';
						if (SEOTOOLS_FIELD_DESC3) echo '<td><textarea class="expandable" name="items['. $line_num .'][desc3]" >'. $desc3 .'</textarea></td>';
						echo '</tr>';
					}
				}
			}
			?>
			
			<tr oid=0>
				<td></td>
				<td><input type="text" name="items[0][url]" value="" /></td>
				<?if (SEOTOOLS_FIELD_TITLE) :?><td><input type="text" name="items[0][title]" value="" /></td><? endif; ?>
				<?if (SEOTOOLS_FIELD_H1) :?><td><input type="text" name="items[0][h1]" value="" /></td><? endif; ?>
				<?if (SEOTOOLS_FIELD_KEYWORDS) :?><td><input type="text" name="items[0][keywords]" value="" /></td><? endif; ?>
				<?if (SEOTOOLS_FIELD_DESCRIPTION) :?><td><input type="text" name="items[0][description]" value="" /></td><? endif; ?>
				<?if (SEOTOOLS_FIELD_DESC) :?><td><textarea class="expandable" name="items[0][desc]" ></textarea></td><? endif; ?>
				<?if (SEOTOOLS_FIELD_DESC2) :?><td><textarea class="expandable" name="items[0][desc2]" ></textarea></td><? endif; ?>
				<?if (SEOTOOLS_FIELD_DESC3) :?><td><textarea class="expandable" name="items[0][desc3]" ></textarea></td><? endif; ?>
			</tr>
		</tbody>
	</table>
	<div class="block">
		<div class="left_col">
			<input type=button value="Добавить новую строку..." id="add_row" />
			<input type=submit value="Сохранить" class=green name="save" />
			<input type=submit value="Сохранить как CSV" name="export_csv" />
		</form>
		</div>
		<div class="right_col">
		<form action="" method="post" enctype="multipart/form-data">
			<input type=file name=csv />
			<input type=submit value="Загрузить из CSV" name="import_csv" />
		</form>
		</div>		
	</div>
	
	<div style="clear: both"></div>
	
	<blockquote>
		<h2><a href="#" class="expander dashed" obj-target="#memorandum">Памятка</a></h2>
		<div id="memorandum">
		<p>
			<ul>
				<li>Чтобы удалить строку, достаточно просто очистить поле "URL"</li>
				<li>Чтобы очистить столбец, два раза кликните его заголовок</li>
			</ul>
		</p>
		<h3>Вставка в php</h3> 
	<p>Вставка в php осуществляется следующим способом:<br>
<textarea style="width: 800px; height: 200px">&lt;?
require_once ($_SERVER['DOCUMENT_ROOT'] . '<?=SEOTOOLS_PATH?>seotools.class.php');
$ST = new Seotools; 

// Вывод метки, где 
// $variable = название переменной (столбца). 
// $default_value = значение по умолчанию 
echo $ST->get($variable, $default_value); 
?&gt;</textarea>
	</p>
	<h3>Загрузка из CSV</h3>
	<ul>
		<li>все старые данные будут перезаписаны</li>
		<li>столбцы определены в следующем порядке: 1 - URL, 2 - &lt;title&gt;, 3 - &lt;H1&gt;, 4 - meta-keywords, 5 - meta-description, 6 - произвольный текст №1, 7 - произвольный текст №2, 8 - произвольный текст №3</li>
		<li>если значение столбца "URL" будет равно собственно "URL", то строка загружена не будет</li>
	</ul>
		</div>
	</blockquote>
	
	
	<blockquote>
		<h2><a href="#" class="expander dashed" obj-target="#snippet">Образцы кода</a></h2>
		<div id="snippet" style="display: none">
		<textarea style="width: 800px; height: 200px">&lt;?
// Сниппет для мета тегов
$meta_keywords 	= $ST->get('keywords');
$meta_desc	 	= $ST->get('description');
if ($meta_keywords) echo '&lt;meta name="keywords" content="'. $meta_keywords .'" /&gt;';
if ($meta_desc) 	echo '&lt;meta name="description" content="'. $meta_desc .'" /&gt;';
?&gt;</textarea>
		</div>
	</blockquote>
	
	<?
	/*require ($_SERVER['DOCUMENT_ROOT']  . '/seotools/seotools.class.php');
	$st = new Seotools;
	echo $st->display('title', 'default title');*/
	?>
</div>
</body>
</html>
