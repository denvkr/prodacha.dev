<?php

/* 
МодиФицируем цены в товарах согласно логики
 * 
 * Технически можно реализовать, например, так: цена1 - стандартная цена,
 * цена5 - цена, которой мы заменяем стандартную по расписанию. 
 * В нужное время делаются последовательно запросы: цена4 (буфер) = цена1, цена1=цена5. 
 * Потом обратно ставим цена1=цена4.
 *  */

//подключаем базу данных

	//$host="localhost";          
	//$user_db="u301639_second";           
	//$pass_db="peREneSti-AB6E";       
	//$dbase="u301639";
$host="u301639.mysql.masterhost.ru";
$user_db="u301639_test";
$pass_db="-o97iCiAlLop.";
$dbase="u301639_test";
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
        echo '<div id="sqlcommand" style="position:relative;margin 5px 5px 5px 5px;width:200px;height:40px;">';
        echo '<form id="sqlcommandform" POST="/alterproductprice.php">';
        echo '<input type="submit" name="runsql18" value="Изменение цены в 08:00" style="margin-top:5px;margin-borrom:5px;">';
        echo '<input type="submit" name="runsql8" value="Изменение цены в 18:00" style="margin-top:5px;margin-borrom:5px;">';
        echo '</form>';
        echo '</div>';

        echo '<div id="sqlinfo" style="position:relative;margin 5px 5px 5px 5px;width:600px;height:100%;top:10px;display:inline;">';
        //инфо из бд
        $sql0="SELECT id,name,price,price4,price5,price_n FROM phpshop_products where enabled='1' order by id";
        $res0=mysql_query($sql0,$dbcnx);
        //if ($_REQUEST['runsql8'])
        var_dump($_REQUEST['runsql8'],$_REQUEST['runsql18']);
        //читаем данные из файла
        $file = "price_button.log";
	$fh = fopen($file, "r");
        $fw=fread($fh,2);
        //echo '$fw'.$fw;
        $cnt=1;
        while ($row=mysql_fetch_assoc($res0)){
            if ((int)$row['price5']>0){
                $sqlupdate='';
                //делаем апдейт
                if (isset($_REQUEST['runsql8']) && $_REQUEST['runsql8']=='Изменение цены в 18:00'){
                    $sqlupdate='UPDATE phpshop_products SET price_n='.$row['price'].',price='.$row['price5'].' WHERE id='.$row['id'];
                    if (isset($fw) && ((int)$fw==0 || (int)$fw==8) && $cnt===1){
                        echo '==8==';
                        if (isset($fh))
                            fclose($fh);
                        //пишем в файл
                        $fh=fopen($file,"w+");
                        fwrite ($fh,'18',2);
                        fclose($fh);
                    }
                    if (isset($fw) && ((int)$fw===0 || (int)$fw===8))
                        $result=mysql_query($sqlupdate,$dbcnx);
                    //echo $row['id'].' '.$row['name'].' '.$row['price'].' '.$row['price4'].' '.$row['price5'].' '.$row['price_n'].'<br>'.$sqlupdate.'<br>';
                }

                if (isset($_REQUEST['runsql18']) && $_REQUEST['runsql18']=='Изменение цены в 08:00'){
                    $sqlupdate='UPDATE phpshop_products SET price='.$row['price_n'].',price_n=0 WHERE id='.$row['id'];
                    if (isset($fw) && ((int)$fw==0 || (int)$fw==18) && $cnt===1){
                        echo '==18==';
                        if (isset($fh))
                            fclose($fh);
                        //пишем в файл
                        $fh=fopen($file,"w+");
                        fwrite ($fh,'8',2);
                        fclose($fh);

                    }
                    if (isset($fw) && ((int)$fw===0 || (int)$fw===18))
                        $result=mysql_query($sqlupdate,$dbcnx);

                    //echo $row['id'].' '.$row['name'].' '.$row['price'].' '.$row['price4'].' '.$row['price5'].' '.$row['price_n'].'<br>'.$sqlupdate.'<br>';
                }
                //var_dump($result);
                //выполняем запрос на обновление
            $cnt++;                
            }
        }

        echo '</div>';        