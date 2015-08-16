<div class="page_nava">
  <div> @breadCrumbs@ </div>
</div>
	<div class="pagetitle">
					
					<h1>@php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/seotools/seotools.class.php');
$ST = new Seotools; 

// Вывод метки, где 
// $variable = название переменной (столбца). 
// $default_value = значение по умолчанию 


$oldh1="@catalogName@";

echo $ST->get("h1", $oldh1); 

php@</h1>
				</div>

<div>@catalogContent@</div>
<div>@catalogList@</div>
