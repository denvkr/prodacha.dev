<div class="page_nava">
  <div> <a href="/">PRO����</a> / ����� ����� </div>
</div>
<div class="pagetitle">
					<h1>����� �����</h1>
				</div>
<!-- <div class="pod_cart"> @catalFound@: <STRONG>@catalNum@</STRONG><br>
  @producFound@: <STRONG>@productNum@</STRONG><BR>
</div>
-->

<div class="mapa">

@php

$sql="SELECT * FROM phpshop_page WHERE category='1000' AND enabled='1' ";
$res=mysql_query($sql);


echo "<ul class='pagesmapa'>";

			  while($row=mysql_fetch_assoc($res))
				echo "<li><a href='/page/".$row['link'].".html' >".$row['name']."</a></li>";

echo "</ul>";

php@

@productPageDis@
</div> 