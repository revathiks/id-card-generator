<?php include "header.php";
if(isset( $_SESSION['userid'])){
    $userid=$_SESSION['userid'];
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
 <!-- Start your table content here--> 
<style>

.imgcontainer {
     max-width: 230px !important;  
    }
   /*.imgcontainer img {     
      max-width: 200px !important;  
      max-height:300px !important;   
    }
    .imgcontainer img{
      max-width:100% !important;
    }*/
    

    img {
      max-width: 100%;
    }
	.table{ margin-left: -73px;}
</style>
<?php
error_reporting(0);
define('ROOTPATH', __DIR__);
if(isset($_POST["upload"]))  
{ 
		$foldername=strtolower($_POST['srcname']);        
        if (!file_exists('uploads/'.$foldername)) {
			mkdir('uploads/'.$foldername, 0755, true);
        }
        $output = '';  
        if($_FILES['zip_file']['name'] != '')  
        {  
                   $file_name = $_FILES['zip_file']['name'];  
                   $array = explode(".", $file_name);  
                   $name = $array[0];  
                   $ext = $array[1];  
                   if($ext == 'zip')  
                   {  
						if (!file_exists('uploads/'.$foldername.'/photos')) {
							mkdir('uploads/'.$foldername.'/photos', 0755, true);
						}
                        $path = 'uploads/'.$foldername."/photos/";  
                        $location = $path . $file_name;  
                        if(move_uploaded_file($_FILES['zip_file']['tmp_name'], $location))  
                        {  

                             $zip = new ZipArchive;  
                             if($zip->open($location))  
                             {  
                                  $zip->extractTo($path);  
                                  $zip->close();  
                             } 
                             if(file_exists($path . $name)) {
	                             $files = scandir($path . $name); 
	                             //$name is extract folder from zip file  
	                             foreach($files as $file)  
	                             {                
	                                  $file_ext = @end(explode(".", $file));  
	                                  $allowed_ext = array('jpg', 'png','JPG','PNG','jpeg','JPEG');  
	                                  if(in_array($file_ext, $allowed_ext))  
	                                  {    
	                                       copy($path.$name.'/'.$file, $path . $file);  
	                                       unlink($path.$name.'/'.$file);  
	                                  }       
	                             }  
                            }
                             unlink($location);  
                             rmdir($path . $name);  
                        }  
                   }  
        }  
        if ( isset($_FILES["csv_file"])) {                
        if ($_FILES["csv_file"]["error"] > 0) {
            echo "Return Code: " . $_FILES["csv_file"]["error"] . "<br />";
        }
        else { 
             //if file already exists
             if (file_exists("uploads/" .$foldername."/". $_FILES["csv_file"]["name"])) {
               //echo $_FILES["csv_file"]["name"] . " already exists. ";
             }
             else {          
                    $storagename = basename(($_FILES["csv_file"]["name"]));
                    move_uploaded_file($_FILES["csv_file"]["tmp_name"], "uploads/" .$foldername."/". $storagename);            
            }
        }
     } 
}
?>

<?php 
use League\Csv\Reader;
use League\Csv\Writer;

require './vendor/league/csv/autoload.php';
require './vendor/autoload.php';
$foldername=strtolower($_POST['srcname']);
//ROOTPATH."/uploads/" .$foldername."/". $_FILES["csv_file"]["name"], 'r'
$mainPath=ROOTPATH."/uploads/".$foldername."/";
$baseurl= BASE_URL."/uploads/".$foldername."/";
$reader = Reader::createFromPath($mainPath. $_FILES["csv_file"]["name"],'r');
$reader->setHeaderOffset(0); //explicitly sets the CSV document header record
$headers=$reader->getHeader();
$template_id=$_POST['template_id'];
$fieldLabel=array();
$field_query="SELECT f.label FROM  idg_fields f LEFT JOIN  idg_user_template_position tp ON f.id=tp.field WHERE tp.user_template_id=$template_id AND tp.userid=$userid ORDER BY  f.id";
    $result = $conn->query($field_query);
    while($row = $result->fetch_assoc()){
        $fieldLabel[]=$row['label'];
    }
    $headers=array_map('trim',$headers);
    $fieldLabel=array_map('trim',$fieldLabel);
     if ($headers !== $fieldLabel) {
        $path="import_users.php"; ?>
        <div class="content-wrapper">
             <div class="container center-container  text-center">
             	 <div class="mid-wpr align-top">
             	 	<p>Header are not matched.</p>
             	 	<p>Please Try again<a href=<?php echo $path;?>> Go back</a></p>
             	 </div>
             </div>
         </div>
	   <?php  
        }else{
        $users=$reader->getRecords();
        $records=array(); ?>
        <!--Main Navigation-->
          <!-- Start your project here--> 
        <div class="content-wrapper">
         <div class="container center-container">
         <?php
         $sql_image="SELECT * FROM idg_user_template_position tp INNER JOIN idg_fields f  ON tp.field=f.id WHERE tp.userid=$userid AND tp.user_template_id=$template_id AND is_pic=1 ORDER BY FIELD";
         $image_data=$conn->query($sql_image);
         
         ?>
        	<form class="edit_table" name="edit_table" action="post.php" method="POST">
        		<table class="table table-bordered">
        		  <thead class="black white-text">
        			<tr>
        				<th> Sno</th>
        				<th> Employee Details</th>
        				<th> Details</th>
        				<th> Details</th>
        				<?php if($image_data-> num_rows>0){?>
        				<th> Image</th>
        				<?php } ?>
        				<th style='text-align:center;'><input type="checkbox" id="ckbCheckAll" /></th>
        			</tr>
        		  </thead>
        		  <tbody>
        			<?php if(!empty($users)){
        				$i=1;
        				foreach ($users as $key => $records) {
        				    //echo "<pre>"; print_r($records); echo "</pre>";
        					$recordC=count($records);
        					$keys=array_values($records);
        					$employee_id=$records['ID'];
        					$recordkey=count($keys);
        					?>
        					<tr class="hide">
        						<td>
        							<?php echo $i;?>
        						</td>
        						<?php $sql_data="SELECT * FROM idg_user_template_position tp INNER JOIN idg_fields f  ON tp.field=f.id WHERE tp.userid=$userid AND tp.user_template_id=$template_id AND is_pic=0 ORDER BY FIELD";
        							    $field_data=$conn->query($sql_data);
        							    $count=$field_data->num_rows; 
        							    $diviend=3;
        							    $remainder=$count % $diviend;
        							    $number=explode('.',($count / $diviend));
        							    $answer=$number[0];
        							    $idarray=array();
        							    while($field=$field_data->fetch_assoc()){
        							        $id= $field['id'];
        							        $idarray[$i][]= $field['id'];
        							        $fieldid_label[]= $field['label'];
        							    }
        							   ?>
        						<input type="hidden" name="emp_id[<?php echo $i;?>]" value="<?php echo $employee_id;?>">
        						<td>
        							<div class="form-group">
        							<?php 
        							/* if($remainder==0){
        							     $answer=$answer-1;
        							}else{
        							    $answer=$answer;
        							} */
        							 for($n=0; $n<$answer; $n++){ 
        							      $field_index=$idarray[$i][$n];?>
        							 	<label for="emp_id"><?php echo $fieldid_label[$n];?></label>
        									<input type="text" class="form-control" maxlength="20" name="input_<?php echo $field_index;?>[<?php echo $i;?>]" id="field"  placeholder="<?php echo $fieldid_label[$n];?>" value="<?php echo $records[$fieldid_label[$n]]; ?>">
        							<?php  } ?>
        									<input type="hidden" id="template_id" name="template_id" value="<?php echo $template_id;?>">
        							</div>
        						</td>
        						<td>
        							<div class="form-group">
        							<?php  for($j=$n; $j<$answer*2; $j++){ 
        							       $field_index=$idarray[$i][$j];?>
        							 	<label for="emp_id"><?php echo $fieldid_label[$j];?></label>
        									<input type="text" class="form-control" maxlength="20" name="input_<?php echo $field_index;?>[<?php echo $i;?>]" id="field"  placeholder="<?php echo $fieldid_label[$j];?>" value="<?php echo $records[$fieldid_label[$j]]; ?>">
        							<?php }?>
        									<input type="hidden" id="template_id" name="template_id" value="<?php echo $template_id;?>">
        							</div>
        						</td>
        						<td>
        							<div class="form-group">
        								<?php 
        								for($k=$j; $k<$answer*3; $k++){
        								       $field_index=$idarray[$i][$k];
        							    ?>
        							 	<label for="emp_id"><?php echo $fieldid_label[$k];?></label>
        									<input type="text" class="form-control" maxlength="20" name="input_<?php echo $field_index;?>[<?php echo $i;?>]" id="field"  placeholder="<?php echo $fieldid_label[$k];?>" value="<?php echo $records[$fieldid_label[$k]]; ?>">
            							<?php 
            							} ?>
        									<input type="hidden" id="template_id" name="template_id" value="<?php echo $template_id;?>">
        							</div>
        						</td>
        						<?php   $sql_image="SELECT * FROM idg_user_template_position tp INNER JOIN idg_fields f  ON tp.field=f.id WHERE tp.userid=$userid AND tp.user_template_id=$template_id AND is_pic=1 ORDER BY FIELD";
                                        $image_data=$conn->query($sql_image);
        						if($image_data-> num_rows>0){
        						?>	
        						<td class="text-center"> 
        							<div class="imgcontainer"> 
        								<?php 
        							        while($image=$image_data->fetch_assoc()){
        							        $id= $image['id'];
        							        $image_label= $image['label'];
                					    ?>
        									<img class="cropimg" id="image<?php echo $i;?>" src="<?php echo $baseurl."/photos/". $records[$image_label]; ?>.jpg" width="225" height="225"/>
        									<input type="hidden" name="input_<?php echo $id;?>[<?php echo $i;?>]" id="image" value="<?php echo $records[$image_label];?>.jpg" >
        									<div class="crop-wpr">
        										<button type="button" class="approve btn btn-info" empid="<?php echo $records[$image_label]; ?>" id="<?php echo $records[$image_label]; ?>" imgno="<?php echo $i;?>">Crop</button>
        										<span class="tick-mark"><i style="display:none;" class="approved<?php echo $records[$image_label]; ?> fa fa-check-circle" ></i></span>
        									</div>
        								<?php }?>	
        							</div>
        							<input type="hidden" id="crop_id<?php echo $i;?>" name="crop_id[<?php echo $i;?>]" value="0">
        						</td>
        						<?php }?>
        						<td class="checkboxtd">
        							<input type="checkbox" class="checkBoxClass" name="myTextEditBox[]" id="myTextEditBox" value="<?php echo $i;?>" 
        							style="margin-left:17px; margin-right:auto;">
        						</td>
        					</tr>
        					
        					<?php
        					$i++;
        				}
        			}
        			?>
        			</tbody>
        		</table>
        		<input type="hidden" name="foldername" value="<?php echo $foldername;?>"/>
        		<input type="submit" name="submit" id="submit" value="Submit" class="btn btn-dark">
        	</form>	
        	</div>
          </div>
  <?php }?>
<script src="js/cropper.js"></script>
<script type="text/javascript">
$(document).ready(function () {
	$("#ckbCheckAll").click(function () {
		$(".checkBoxClass").prop('checked', $(this).prop('checked'));
	});
    $("#submit").click(function () {  
    	var checked=$('[name="myTextEditBox[]"]:checked').length;
    		if( checked == 0){
    			alert("Please select atleast one employee!");
    			return false;
    		}else{
    			return true;
    		}
     }); 
    	  var images = document.querySelectorAll('.imgcontainer img');
          var length = images.length; 
          var croppers = [];
          var i;
    	  for (i = 0; i < length; i++) {
    			croppers.push( new Cropper(images[i], {
    				viewMode: 3,
                  dragMode: 'move',
                  autoCropArea: 1,       
                  restore: false,
                  modal: false,
                  guides: false,
                  highlight: false,
                  cropBoxMovable: false,
                  cropBoxResizable: false,
                  toggleDragModeOnDblclick: false, 
                }
              )
              );    
            } 
    	  $('.approve').on('click', function (ev) {
    				var foldername="<?php echo $foldername; ?>";
    				var emplid=$(this).attr('empid');
    				var url='<?php echo BASE_URL;?>';
                     var imgindex=$(this).attr('imgno');
    				 $('#crop_id'+imgindex).val(1);				 
                     var canvas = croppers[imgindex-1].getCroppedCanvas({  
                        
                      });  
    				var canvaURL = canvas.toDataURL('image/jpeg');  
                      var ischecked=1;   
                       $.ajax({
                        url: url+"/ajaxpro.php",
                        type: "POST",
                        data: {"image":canvaURL,"empid":emplid,'ischecked':ischecked,"foldername":foldername},
                        success: function (data) {
    						$(".approved"+emplid).css({"display": "block","color": "green"});    
    
                        }
    
                      });
             });
});
  </script>
<?php include "footer.php";?>