<?php
include "config.php";
include "db.php";
/*echo "<pre>";
print_r($_POST);
echo "</pre>";*/
if(!empty($_POST['stuff'])){
	$pixData=array();
	$stuff=$_POST['stuff'];
	foreach($stuff as $k=> $data){
	   $x=$data['x'];
       $y=$data['y'];       
       $type=$data['type'];
       $field=$data['id'];
       $template_id=$data['template_id'];
       $userid=$data['userid'];
       $date=date('Y-m-d h:i:s');
       $sel_query="select * from idg_user_template_position where user_template_id=$template_id AND userid=$userid and field=$field";
               $result=$conn->query($sel_query);
	               if($result->num_rows == 0){
				       $sql="insert into idg_user_template_position(userid,user_template_id,field,xpos,ypos,type,createdon) values($userid,$template_id,'$field',$x,$y,$type,'$date')";
				       $conn->query($sql);
				       echo "1";
		  			}
	   }	
}



?>