<?php include "header.php";
if(isset( $_SESSION['userid'])){
    $userid=$_SESSION['userid'];
}
?>
<!-- Start your project here-->  
<div class="content-wrapper">
 <div class="container">
   <div class=" row">
    	<div class="col-md-7">
           	 <div class="mid-wpr align-top">
           		<h4>Template Configuration</h4>
                    <form  class="text-center border border-light p-5" method="POST" action="save_template.php" name="templates" id="templates" enctype="multipart/form-data">
                        	<div class="input-group">
                         		<div class="row ">
                                 	 <div class="input-group-prepend">
                                      <span class="input-group-text" id="inputGroupFileAddon01">Select Template</span>
                                    </div>
                                </div>
                                <div class="custom-file" id="templetes">
                      				<select class="form-control"  id="my_select_box" name="my_select_box" required>
                                  		<option value="0" readonly >Select...</option>
                         				<?php 
                         		            $template_query="select * from idg_templates  order by code";
                         		            $temp=$conn->query($template_query);
                         		            while($datas=$temp->fetch_assoc()){
                         		                $name=$datas['name'];
                         		                $id=$datas['id'];
                         		                $frontPath= BASE_URL."/templates/".$datas['front_path'];
                         		                $backPath= BASE_URL."/templates/".$datas['back_path'];
                         		                $id=$datas['id'];
                         		          ?>
                                 		<option data-front="<?php echo $frontPath;?>" data-back="<?php echo $backPath;?>" value="<?php echo $id;?>"><?php echo $name;?></option>
                          				<?php } ?>
                                  </select>
                         	 </div>
                    	</div>
           				<div style="padding:5px;"></div><br>
                 		<div class="row">
                          	<div class="col-md-12" style="text-align: left;">
                          		<label for="usr">Personal Details:</label>
                          		<div class="row-content" id="show_checkbox">
                          			<?php $unCheck_query="SELECT * FROM  idg_fields";
                                        if(!empty($unCheck_query)){
                                            $uncheck_data=$conn->query($unCheck_query);
                                            while($unchecked_field=$uncheck_data->fetch_assoc()){
                                                $fieldid= $unchecked_field['id'];
                                                $fieldcode= $unchecked_field['code'];
                                                $field_lable= $unchecked_field['label'];
                                                echo '<div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="'.$fieldcode.'" value="'.$fieldid.'" name="checked[]" >
                                                    		<label class="form-check-label" for="employeeid">'. $field_lable.'</label>
                                                    </div>';
                                            }
                                            
                                        }?>
                    			</div>
                			</div>
                       </div>
                       <div style="padding:5px;"></div>
                       <input type="submit" class="btn btn-primary" name="submit" id="submit" value="submit">
                </form>
              </div>
          </div>
           <div class="col-md-5 show_template"  id="show_template" style="margin-top: 80px;">
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
	  $('#show_template').hide();	  
	  
  $("#submit").click(function () {  
  			var checked=$('[name="checked[]"]:checked').length;
  				if( checked == 0){
  					alert("Please select atleast one field!");
  					return false;
  				}else{
  					return true;
  				}
  		});
  	$( "#my_select_box" ).change(function() {
  		var id=$( "#my_select_box").val();  		
  		if(id>0){
  	     $('#show_template').show();
  		var front =$( "#my_select_box").find(':selected').attr('data-front');
		var back =$( "#my_select_box").find(':selected').attr('data-back');
		$('#my_changing_image_front').attr('src',front);
		$('#my_changing_image_back').attr('src',back);
		
		
		 $.ajax({
             type: "POST",
             url: 'checkbox.php',
             data: {"id":id}, 
             success: function(data)
             	{
            	// $('#success_message h2').text(response);
              	$( "#show_checkbox" ).html(data);
              	if($(".form-check-input:checked").length>0){
                  	$('#submit').hide();
                 }else{
                	 $('#submit').show();
                 }
             	}
 			});
  		}else{
  			 $('#show_template').hide();
  		}
		

	});
	
  	
  });
  </script>
<?php include "footer.php";?>