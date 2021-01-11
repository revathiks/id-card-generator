<?php include "header.php"; ?>
<?php
if(isset( $_SESSION['userid'])){
    $userid=$_SESSION['userid'];
}

//for select template with postion
$sql="SELECT * FROM idg_user_template_position where userid=$userid";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
         while($row = $result->fetch_assoc()){
             //print_r($row);
             if($row['xpos']!="" && $row['ypos']!="" ){
                $id[]=$row['user_template_id'];
               }
             
         }
        
 }
/* if(!empty($id)){
$template_id=array_unique($id);
$Check_query="SELECT * FROM idg_user_template_selectinfo ts LEFT JOIN idg_fields f ON f.id=ts.field WHERE ts.user_template_id IN(".implode(',', $template_id).")";
     if(!empty($Check_query)){
         $check_data=$conn->query($Check_query);
         while($checked_field=$check_data->fetch_assoc()){
             $field_id= $checked_field['field'];
             $user_id= $checked_field['userid'];
             $field_lable[$user_id]= $checked_field['label'];
             
         }
      }
  }
  if(!empty($field_lable)){
  echo"<pre>"; print_r($field_lable); echo "</pre>";
  $path="./tmp/$userid.csv";
  $csvWriter = Writer::createFromPath($path, 'w+');
  $csvWriter->setNewline("\r\n");
  $fields=implode('',$field_lable);
  $header = [$fields];
  $csvWriter->insertOne($header);
  } */
 ?>


  <!-- Start your project here-->  
<div class="content-wrapper">
 <div class=" col-md-12 container">
   <div class="row">
       	<div class="col-md-7">
                <div class="mid-wpr align-top">
                    <h4>Import Users</h4>
                    <form id="importuser" class="text-center border border-light p-5" method="POST" action="<?php echo BASE_URL;?>/upload_user.php" enctype="multipart/form-data">
                      	<div class="input-group">
                             	 <div class="input-group-prepend">
                                  <span class="input-group-text" id="inputGroupFileAddon01">Select Templates</span>
                                </div>
                                <div class="custom-file" id="templetes">
                          			<select class="form-control"  id="my_select_box" name="my_select_box" required>
                                      		<option value="" readonly>Select...</option>
                             				<?php 
                             				if(!empty($id)){
                         				       $unique_template_id=array_unique($id);
                                                 //$template_id=implode(',', $unique_template_id);
                         				       $template_query="select * from idg_templates where id IN (".implode(',', $unique_template_id).")";
                                                 $temp=$conn->query($template_query);
                                                 while($datas=$temp->fetch_assoc()){
                                                     $name=$datas['name'];
                                                     $frontPath= BASE_URL."/templates/".$datas['front_path'];
                                                     $backPath= BASE_URL."/templates/".$datas['back_path'];
                                                     $id=$datas['id'];?>
                                            <option data-front="<?php echo $frontPath;?>" data-back="<?php echo $backPath;?>" value="<?php echo $id;?>"><?php echo $name;?></option>
                                            <?php }
                             				}?>
                             				<input type="hidden" name="template_id" id="template_id" value="">
                             				<input type="hidden" name="user_id" id="user_id" value="<?php echo $userid?>">
                             			</select>
                             			<a href="" target="_blank"  class="csv_download" style="display:none;">Dowload csv</a>
                             	</div>
                          </div>
                          
                          <div style="padding:5px;"></div><br>
                              <div class="input-group mb-3" id="srcnamediv" >
                              	<input type="text" class="form-control" placeholder="Folder Name" aria-label="Name"
                                name="srcname" id="srcname" maxlength="10" >
                             </div>
                         <div style="padding:5px;"></div>
                    	 <div class="input-group">
                              <div class="input-group-prepend">
                                  <span class="input-group-text" id="inputGroupFileAddon01">Select Employees  <small> - csv</small></span>
                                </div>
                                <div class="custom-file" id="csv_filediv">
                                  <input type="file" class="custom-file-input" id="csv_file"
                                    name="csv_file" >
                                  <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                </div>
                          </div>
                      <div style="padding:5px;"></div>
                		<div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupFileAddon01">select Photos <small>- zip</small></span>
                              </div>
                              <div class="custom-file" id="zip_filediv">
                                <input type="file" class="custom-file-input" id="zip_file"
                                  name="zip_file" >
                                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                              </div>
                      	</div>
                    <input type="hidden" name="upload" value="upload">
                    <button type="button" class="btn btn-primary" name="upload" id="upload">submit</button>
                    </form>
                  </div>
          	 </div>
           	 <div class="col-md-5 show_template " style="display:none;margin-top: 80px;">
                   <div class="row">
                        <div class="col-md-6">
                       		 <img id="my_changing_image_front" src=""  width="200" height="300" ><br>
                         </div>
                         <div class="col-md-6">
                          	<img id="my_changing_image_back" src=""  width="200" height="300" >
                          </div>
                  </div>
            </div>
  		</div>
  	</div>
  </div>
  <!-- End your div here-->
  <!-- jQuery -->
<script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript">
$(document).ready(function() {
   $("#upload").click(function (e) {
    var err=0; 
    $(".error").remove();
    var srcname=$("#srcname").val().trim();    
        $(".error").remove();
        if (srcname.length < 1) {
        $('#srcnamediv').after('<div class="error  mb-3">Name is required</div>');
        err=1;
        }
        if( $("#csv_file").val().trim() =="") {
        $('#csv_filediv').after('<div class="error  mb-3">Please choose csv file</div>');
        err=1;
        }
        else if( $("#csv_file").val().trim().toLowerCase().lastIndexOf(".csv")==-1) {
        $('#csv_filediv').after('<div class="error  mb-3">Please choose only csv file</div>');
        err=1;
        }
        if( $("#zip_file").val().trim() =="") {
        $('#zip_filediv').after('<div class="error  mb-3">Please choose zip file</div>');
        err=1;
        }
        else if( $("#zip_file").val().trim().toLowerCase().lastIndexOf(".zip")==-1) {
        $('#zip_filediv').after('<div class="error  mb-3">Please choose only zip file</div>');
        err=1;
        }

        if(err==0){
          $("#importuser").submit();
        }
     
    });
   $( "#my_select_box" ).change(function() {
	   $('.show_template').css('display','block');
 		var front =$( "#my_select_box").find(':selected').attr('data-front');
		var back =$( "#my_select_box").find(':selected').attr('data-back');
		$('#my_changing_image_front').attr('src',front);
		$('#my_changing_image_back').attr('src',back);
		var id=$( "#my_select_box").val();
		$('#template_id').val(id);
		var userid=$( "#user_id").val();
		$('.csv_download').css('display','block');
		/*  $.ajax({
             type: "GET",
             url: 'generate_csv.php',
             data: {"id":id,"userid":userid}, 
             success: function(data)
             	{
            	// $('#success_message h2').text(response);
              	//$( "#show_checkbox" ).html(data);
             		$( "#show_checkbox" ).html(data);	
                 }
 			}); */
		var path="<?php echo BASE_URL;?>";
    $(".csv_download").attr("href", path+"/generate_csv.php?id="+id+"&userid="+userid+"");
		
	});
});
</script>
<?php include "footer.php";?>