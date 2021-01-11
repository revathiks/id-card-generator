<?php
echo $filepath=$_POST['src']."/".$_POST['filename'];
if (file_exists($filepath)) {
    header("Content-type: application/zip");
    header("Content-Disposition: attachment; filename = ".basename($filepath)."");
    header("Pragma: no-cache");
    header("Expires: 0");
    readfile("$filepath");
    exit;
   }
    
   ?>