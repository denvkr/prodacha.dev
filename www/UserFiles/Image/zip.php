<?php
$file="1cImages.zip";
if(is_file($file)){
include("../../phpshop/lib/zip/pclzip.lib.php");
$archive = new PclZip($file);
if($archive->extract(PCLZIP_OPT_PATH, "./")) {
    echo "Done";
    unlink($file);
} else echo "Eror";
} else echo "Eror ".$file." not found";
?>