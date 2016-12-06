<?
	$host="localhost";          
	$user_db="u301639_second";           
	$pass_db="peREneSti-AB6E";       
	$dbase="u301639";
				
				$dbcnx=mysql_connect($host,$user_db,$pass_db);
				
				if (!$dbcnx) 
				{
				  echo("<p> error MySql </p>");
				   exit;
				}
																
				if (!@mysql_select_db($dbase,$dbcnx)) 
				{
				   echo("<p> error DB MySql </p>");	
				   exit;
				}
				
							
				//инфо из бд
				$sql0="SELECT id,sort FROM phpshop_categories where num_row='1' order by id";
				$res0=mysql_query($sql0,$dbcnx);
				
				$row0=mysql_fetch_array($res0);
				$cnt=$cnt1=0;
				while ($row0=mysql_fetch_assoc($res0)){
					$cnt1=$cnt;
					$categoriesarray=unserialize($row0['sort']);
					//var_dump($categoriesarray,'<br>');
                                        if (!is_null($categoriesarray))
					if (array_search ( '186' ,$categoriesarray)) {
                                            $cnt1=$cnt+1;
					}
					if ($cnt1==$cnt){
                                            $categoriesarray[]='186';
                                            //var_dump($categoriesarray);
                                            echo 'update phpshop_categories set sort=\''.serialize($categoriesarray).'\' where id='.$row0['id'].'<br>';
                                            $resultarr[]=$row0['id'];
					}
					
				}
				echo implode(',',$resultarr);
                die();

?>