<?php
header("Access-Control-Allow-Origin: *");
$ischecked=$_POST['ischecked'];
$empid=$_POST['empid'];
$foldername=$_POST['foldername'];
$imageName = $empid.'_crop.jpg';
$folder_name= "uploads/".$foldername."/photos/";
if(!empty($ischecked)){
	$data = $_POST['image'];	
	list($type, $data) = explode(';', $data);
	list(, $data)      = explode(',', $data);
	$data = base64_decode($data);	
	 if (file_exists($folder_name."/".$imageName)) { 	
	 	unlink($folder_name."/".$imageName);
	 }
	file_put_contents($folder_name."/".$imageName, $data);
	echo 'Photo uploaded';
}else{
    if (file_exists($folder_name."/".$imageName)) { 	
	 	unlink($folder_name."/".$imageName);
	 }
	 echo 'Photo deleted';
}
?>