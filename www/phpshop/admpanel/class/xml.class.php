<?
// XML обработчик
class XMLparser {
     var $ar;
	 function XMLparser($aa){
	 foreach ($aa as $k=>$v){
	  $this->$k=$aa[$k];
	  $this->ar[$k] = $this->$k;
	  }
	 }
}

function readDatabase($filename,$keyName){
     $data=implode("",file($filename));
	 $parser=xml_parser_create();
	 xml_parser_set_option($parser,XML_OPTION_CASE_FOLDING,0);
	 xml_parser_set_option($parser,XML_OPTION_SKIP_WHITE,1);
	 xml_parse_into_struct($parser,$data,$values,$tags);
	 xml_parser_free($parser);
	 
	 foreach ($tags as $key=>$val){
	       if($key == $keyName){
		   $molranges=$val;
		   
		   for ($i=0; $i<count($molranges); $i+=2) {
		     $offset=$molranges[$i]+1;
			 $len=$molranges[$i+1]-$offset;
			 $tdb[]=parseDatabase(array_slice($values,$offset,$len));
		     }
		   } else continue;
	   
	 }
return $tdb;
}

function parseDatabase($mvalues){
     for($i=0;$i<count($mvalues); $i++)
	   $mol[$mvalues[$i]["tag"]]=$mvalues[$i]["value"];
	   
	   $db=new XMLparser($mol);
	   $array=$db->ar;
return $array;
}
?>
