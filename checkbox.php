<?php 
 session_start();
 include "config.php";
 include "db.php"; 

/* checked already selected fields*/
//$ischecked_id=$_POST['id'];
//$ischecked_id=$_POST['id'];
$ischecked_id=$_POST['id']; 
$data_fields=array();
$field_query="SELECT * FROM idg_user_template_selectinfo s  INNER JOIN  idg_fields f ON f.id=s.field WHERE  s.user_template_id=$ischecked_id and s.userid=".$_SESSION['userid'];
if(!empty($field_query)){
    $field_data=$conn->query($field_query);
	while($fields=$field_data->fetch_assoc()){
        $data_id=$fields['id'];
        $data_fields[$data_id]=$fields['id'];
    }
}
$unCheck_query="SELECT * FROM  idg_fields";
if(!empty($unCheck_query)){
    $uncheck_data=$conn->query($unCheck_query);
    while($unchecked_field=$uncheck_data->fetch_assoc()){
        $fieldid= $unchecked_field['id'];
        $fieldcode= $unchecked_field['code'];
        $field_lable= $unchecked_field['label'];
        $checked="";
        if(in_array($fieldid, $data_fields)){
            $checked="checked";
        }
        echo '<div class="form-check alignleft">
                <input type="checkbox" class="form-check-input" id="'.$fieldcode.'" value="'.$fieldid.'" name="checked[]" '.$checked.'>
            		<label class="form-check-label" for="employeeid">'. $field_lable.'</label>
            </div>';
    }
    
}
?>
