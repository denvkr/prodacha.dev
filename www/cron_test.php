<?php
echo date('Y-m-d H:i:s');
        $file = "cron_test.log";
	 $fh = fopen($file, "c+");
        fwrite ($fh,date('Y-m-d H:i:s'));
        fclose($fh);

?>