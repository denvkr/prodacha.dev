<?php

/* 
������������ ���� � ������� �������� ������
 * 
 * ���������� ����� �����������, ��������, ���: ����1 - ����������� ����,
 * ����5 - ����, ������� �� �������� ����������� �� ����������. 
 * � ������ ����� �������� ��������������� �������: ����4 (�����) = ����1, ����1=����5. 
 * ����� ������� ������ ����1=����4.
 *  */

//���������� ���� ������

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
				
        //���� �� ��
        $sql0="SELECT id,name,price,price4,price5 FROM phpshop_products where enabled='1' order by id";
        $res0=mysql_query($sql0,$dbcnx);
        while ($row=mysql_fetch_assoc($res0)){
            if ((int)$row['price5']>0){
                //������ ������
                $sqlupdate='UPDATE phpshop_products SET price='.$row['price5'].',price5='.$row['price'].' WHERE id='.$row['id'];
                echo $row['id'].' '.$row['name'].' '.$row['price'].' '.$row['price4'].' '.$row['price5'].'<br>'.$sqlupdate.'<br>';                
            }
            
        }
