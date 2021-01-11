<?php
include "config.php";
include "db.php";

use League\Csv\Reader;
use League\Csv\Writer;

require './vendor/league/csv/autoload.php';
require './vendor/autoload.php';
$template_id=$_GET['id'];
$userid=$_GET['userid'];

//$template_id=1;
if(!empty($template_id)){
    $Check_query="SELECT * FROM idg_user_template_position tp LEFT JOIN idg_fields f ON f.id=tp.field WHERE tp.user_template_id=$template_id and tp.userid=$userid order by f.id";
    if(!empty($Check_query)){
        $check_data=$conn->query($Check_query);
        while($checked_field=$check_data->fetch_assoc()){
            $field_id= $checked_field['field'];
            $field_lable[]= trim($checked_field['label']);
         }
       if(!empty($field_lable)){
           $currenttime=time();
           $csvfile="sample_".$template_id."_".$currenttime;
           $csvWriter = Writer::createFromPath('./tmp/'.$csvfile.'.csv', 'w');
             $csvWriter->setNewline("\r\n");
             $header = $field_lable;
           
             //$csvWriter = Writer::createFromString('');
             $insert=$csvWriter->insertOne($header);
             if($insert){
                 $fileName = basename('./tmp/'.$csvfile.'.csv');
                 $filePath ='./tmp/'.$csvfile.'.csv';
                 if(!empty($fileName) && file_exists($filePath)){
                     // Define headers
                     header("Cache-Control: public");
                     header("Content-Description: File Transfer");
                     header("Content-Disposition: attachment; filename=$fileName");
                     header("Content-Type: application/csv");
                     header("Content-Transfer-Encoding: binary");
                     
                     // Read the file
                     readfile($filePath);
                     exit;
                 }else{
                     echo 'The file does not exist.';
                 }
             }
        } 
     }
}
