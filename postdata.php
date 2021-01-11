<?php
session_start();
include "db.php";
$template_id=$_POST['id'];
?>
 <?php                            
    $query="SELECT fld.*  FROM idg_fields fld WHERE id IN (SELECT FIELD FROM `idg_user_template_selectinfo` WHERE user_template_id =$template_id)";
    $result = mysqli_query($conn, $query);
    if(!$result){
     echo("Errorcode: " . $conn -> errno);
    }
   
  if (mysqli_num_rows($result) > 0) {
      // output data of each row
      while($row = mysqli_fetch_assoc($result)) {
        $pid=$row["id"];
        $code= $row["code"];
        $label= $row["label"];
        $sample= $row["sample"];
        $is_pic=$row["is_pic"];
        
        echo  '<div id="'. $pid.'" class="col-sm-4 dragMe dragSigners" data-signer-id="'. $pid.'" data-document-id="'. $pid.'" data-sample="'. $sample.'" data-ispic="'. $is_pic.' ">
           <span id="sampledata">'. $label.'</span>
            <span did="'. $pid.'"  class="closeIt hideIt">x</span>
          </div>';
       }
  } else {
      echo "0 results";
  }

    ?>  