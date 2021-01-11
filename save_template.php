<?php include "header.php";
if(isset( $_SESSION['userid'])){
    $userid=$_SESSION['userid'];
}
?>
<style type="text/css">
  #finalsubdiv{
    margin:10px;
  }
  
</style>
<script type="text/javascript" src="js/jquery.min.js"></script>
   <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL;?>/css/drag.css">
  <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.js"></script>
  <!-- Start your project here-->  
  <?php
  $isPosted=0;
if(isset($_POST['submit']) && !empty($_POST['submit'])){
  $isPosted=1;
        $template_id=$_POST['my_select_box'];
        $checked_field=$_POST['checked'];
        //$filename=$_FILES["fileToUpload"]["name"];
        $date= date("Y-m-d h:i:s");
        if(!empty($template_id)){
            $checkquery_query="select * from idg_user_template where template_id=$template_id and userid=$userid";
            $res=$conn->query($checkquery_query);
            if($res->num_rows == 0){
                $sel_query="insert into idg_user_template (id,userid,template_id,createdon) values('',$userid,$template_id,'$date')";
                if ($conn->query($sel_query) === TRUE) {
                    $last_id = $conn->insert_id;
                     if(isset($_POST['checked'])){
                        $checkbox_query="select * from idg_user_template_selectinfo where user_template_id=$template_id ";
                        $resp = $conn->query($checkbox_query);
                        if($resp->num_rows != 0){
                            $delete_query = "delete from idg_user_template_selectinfo where user_template_id=$template_id";
                            $result = $conn->query($delete_query);
                            $checked_data=$_POST['checked'];
                            foreach($checked_data as $data){
                                $s = "insert into idg_user_template_selectinfo(id,userid,user_template_id,field,createdon) values('',$userid,$template_id,'$data','$date')";
                                $result = $conn->query($s);
							}
                        }else{
                            $checked_data=$_POST['checked'];
                            foreach($checked_data as $data){
                                $s = "insert into idg_user_template_selectinfo(id,userid,user_template_id,field,createdon) values('',$userid,$template_id,'$data','$date')";
                                $result = $conn->query($s);
                            }
                        }
                     }
                   }
                 }else{
                     $checkbox_query="select * from idg_user_template_selectinfo where user_template_id=$template_id ";
                     $resp = $conn->query($checkbox_query);
                     if($resp->num_rows != 0){
                         $delete_query = "delete from idg_user_template_selectinfo where user_template_id=$template_id";
                         $result = $conn->query($delete_query);
                         $checked_data=$_POST['checked'];
                         foreach($checked_data as $data){
                             $s = "insert into idg_user_template_selectinfo(id,userid,user_template_id,field,createdon) values('',$userid,$template_id,'$data','$date')";
                             $result = $conn->query($s);
                         }
                     }else{
                         $checked_data=$_POST['checked'];
                         foreach($checked_data as $data){
                             $s = "insert into idg_user_template_selectinfo(id,userid,user_template_id,field,createdon) values('',$userid,$template_id,'$data','$date')";
                             $result = $conn->query($s);
                         }
                     }
                 }
                   
        }
        if(!empty($template_id)){
            $template_query="SELECT * FROM idg_templates  WHERE id=$template_id";
            $result = $conn->query($template_query);
            $row = $result->fetch_assoc(); 
            $front_path= BASE_URL."/templates/".$row['front_path'];
            $back_path= BASE_URL."/templates/".$row['back_path'];
            $temp_id=$template_id;
        }
        $field_array=array();
        if(!empty($checked_field) && !empty($template_id)){
            $field_query="SELECT * FROM idg_user_template_selectinfo ts LEFT JOIN idg_fields f  ON f.id=ts.field WHERE ts.userid=$userid AND ts.user_template_id=$template_id";
            $result = $conn->query($field_query);
            while($row = mysqli_fetch_assoc($result)) {
                $label=$row['label'];
                $field=$row['field'];
                $sample= $row["sample"];
                $is_pic=$row["is_pic"];
                $field_array[$field]['field']=$field;
                $field_array[$field]['label']=$label;
                $field_array[$field]['sample']=$sample;
                $field_array[$field]['is_pic']=$is_pic;
            }
        }
       
}

?>
<div class="content-wrapper">
 <div class="container">
  	<div class="mid-wpr">
    <h4> Templates' Layout setup </h4>
    <?php
    $sql="SELECT t.* FROM  idg_user_template ut JOIN idg_templates t ON t.id=ut.template_id WHERE ut.userid=$userid";
    $result=$conn->query($sql);
    if (mysqli_num_rows($result) > 0) {
    ?>
   	<form  class="text-center border border-light p-5" method="POST" action="import_users.php" name="templates" id="templates" enctype="multipart/form-data">
         <div class="input-group">
             	 <div class="input-group-prepend">
                  <span class="input-group-text" id="inputGroupFileAddon01">Select Templates</span>
                </div>
                <div class="custom-file" id="templetes">
						<select class="form-control"  id="my_select_box" name="my_select_box" required>
                      		<option value="0" readonly>Select...</option>
                        <?php while($row = mysqli_fetch_assoc($result)) {
                        $templateid=$row["id"];
                        $name= $row["name"];
						$frontPath= BASE_URL."/templates/".$row['front_path'];
							$backPath= BASE_URL."/templates/".$row['back_path'];
							if($temp_id==$templateid){
							    $selected="selected";
							}else{
							    $selected="";
							}?>
                 		<option data-front="<?php echo $frontPath;?>" data-back="<?php echo $backPath;?>" value="<?php echo $templateid;?>" <?php echo $selected;?>><?php echo $name;?></option>
          				<?php } ?>
                      </select>
                </div>
          </div>  
    </form>
    <div id="template_postion" >
      <div class="row" >
                <div class="col-sm-12 document-content">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-xs-4 col-sm-4">
                        <div>
                          <div id="document-reader" class="fixed">
                          <?php if(!empty($template_id)){ ?>
                            <img src="<?php echo $front_path?>" id="frontimg"  width="676" height="1050">   
                            <?php }else{?>  
                            <img src="" id="frontimg"  width="676" height="1050">
                            <?php }?>                       
                          </div>
                          <div><input class="btn btn-secondary" type="button" id="frontview" value=" Front Preview"/></div>
                      </div>
                    </div>

                      <div class="col-xs-4 col-sm-4">
                        <div>
                          <div id="document-reader2" class="fixed">
                          <?php if(!empty($template_id)){ ?>
                            <img src="<?php echo $back_path?>" id="backimg"  width="676" height="1050">  
                            <?php }else{?>  
                            <img src="" id="backimg"  width="676" height="1050">
                            <?php }?>                           
                        </div>
                        <div><input class="btn btn-secondary" type="button" id="backview" value=" Back Preview"/></div>
                      </div>
                    </div>

                    <div class="col-xs-4 col-sm-4 " id="draggable1">                    
                           <div id="show_fields">
                               <?php if(!empty($field_array)){
                                   foreach( $field_array as $field_data){?>
                                <div id="<?php echo $field_data['field']?>" class="col-sm-4 dragMe dragSigners" data-signer-id="<?php echo $field_data['field']?>" data-document-id="<?php echo $field_data['field']?>" data-sample="<?php echo $field_data['sample']?>" data-ispic="<?php echo $field_data['is_pic']?>">
                                   <span id="sampledata"><?php echo $field_data['label']?></span>
                                    <span did="<?php echo $field_data['field']?>"  class="closeIt hideIt">x</span>
                              	</div>
                              <?php } }?>
                           </div> 
                            <div id="finalsubdiv"><input class="btn btn-success" type="button" id="finalsubmit" value="Submit"/></div>
                    </div>
                   </div>
                </div>
              </div>
            </div>

           <div class="row">
            <div class="col-sm-12 sidebar">  
              <div class="form-group">
                    <div class="row">
                      
                    </div>
                  </div>
            </div>
            </div>

            <!-- The Modal -->
			<div class="modal" id="myModal">
			  <div class="modal-dialog">
			    <div class="modal-content">

			      <!-- Modal Header -->
			      <div class="modal-header">
			        <h4 class="modal-title">Preview</h4>
			        <button type="button" class="close" data-dismiss="modal">&times;</button>
			      </div>

			      <!-- Modal body -->
			      <div class="modal-body">
			       <div id="imgarea"><img id="imgsrc" src="" style="width: 100%;height:100%;"/></div>
			      </div>

			    </div>
			  </div>
			</div>

        </div>

<?php }
   else
    {
      ?>
   
    <div class="mid-wpr align-top">
    <p>Please configure <a href="<?php echo BASE_URL.'/choose_templates.php';?>">template</a> first .</p>
   </div>           
<?php 
}
?>
  </div>
  </div>
  
  </div>
  <!-- End your div here-->
  <!-- jQuery -->
  <script type="text/javascript">
$(document).ready(function() {
  var isPosted='<?php echo $isPosted;?>';
  if(isPosted==1){
   $('#template_postion').show();
 }else{
  $('#template_postion').hide();
 }
	    var frontImg = document.querySelector("#frontimg");
        var realWidth = frontImg.naturalWidth;
        var realHeight = frontImg.naturalHeight;
        var modifiedWidth=(realWidth/2);
        var modifiedHeight=(realHeight/2);
        $("#frontimg").css({'width':modifiedWidth,'height':modifiedHeight});
        $("#document-reader").css({'width':modifiedWidth,'height':modifiedHeight});

        var backImg = document.querySelector("#frontimg");
        var bckrealWidth = backImg.naturalWidth;
        var bckrealHeight = backImg.naturalHeight;
        var backModifiedWidth=(bckrealWidth/2);
        var backModifiedHeight=(bckrealHeight/2);
        $("#backimg").css({'width':backModifiedWidth,'height':backModifiedHeight});
        $("#document-reader2").css({'width':backModifiedWidth,'height':backModifiedHeight}); 
   	   
   	    DragSigner(); 

   	$( "#my_select_box" ).change(function() { 
    var id=$( "#my_select_box").val(); 
    if(id!=0){ 
   	var front =$( "#my_select_box").find(':selected').attr('data-front');
		var back =$( "#my_select_box").find(':selected').attr('data-back');
		$('#frontimg').attr('src',front);
		$('#backimg').attr('src',back); 
		
  		
		var frontImg = document.querySelector("#frontimg");
        var realWidth = frontImg.naturalWidth;
        var realHeight = frontImg.naturalHeight;
        var modifiedWidth=(realWidth/2);
        var modifiedHeight=(realHeight/2);
        $("#frontimg").css({'width':modifiedWidth,'height':modifiedHeight});
        $("#document-reader").css({'width':modifiedWidth,'height':modifiedHeight});

        var backImg = document.querySelector("#frontimg");
        var bckrealWidth = backImg.naturalWidth;
        var bckrealHeight = backImg.naturalHeight;
        var backModifiedWidth=(bckrealWidth/2);
        var backModifiedHeight=(bckrealHeight/2);
        $("#backimg").css({'width':backModifiedWidth,'height':backModifiedHeight});
        $("#document-reader2").css({'width':backModifiedWidth,'height':backModifiedHeight});
		if(front && back){
			 $('#template_postion').show();
		}
		
		
		$.ajax({
            type: "POST",
            url: 'postdata.php',
            data: {"id":id}, 
            success: function(datas)
            	{
           		$( "#show_fields" ).html(datas);
             	DragSigner();
                }
			});
  }else{
    $('#template_postion').hide();
  }
	});
   	$( "#finalsubmit" ).click(function() {
      if($('#document-reader .dragMe1').length!=0 && $('#document-reader2 .dragMe2').length!=0)
      {
      	var stuff = [];
      	var userid='<?php echo $userid;?>';      	
      	var template_id=$("#my_select_box").val();
      	 $('#document-reader .dragMe1 ').each(function(i, e) {
			    var currentElemetWidth=$(this).innerWidth();
			    var currentElemetHeight=$(this).innerHeight();
			    var signid=($(this).data("signer-id"));    
			    var xpos=$(this).css("left");
			    xpos=parseInt(xpos,10);
			    xpos=(xpos*2);
			    var ypos=$(this).css("top"); 
			    ypos=parseInt(ypos,10);
			    ypos=(ypos*2);
			    var is_pic=$(this).data("ispic");
			    var sample=$(this).data("sample"); 
			   // var xposwithDiv=xpos;
			     var yposwithDiv=ypos+(currentElemetHeight+10);
           if(is_pic==1){
             var yposwithDiv=ypos+10;
             xpos=xpos-25;
           }
			     stuff.push( {'id':signid,'x':parseInt(xpos),'y':parseInt(yposwithDiv),'template_id':template_id,'userid':userid,'type':1} );
			    });	          
	      	  $('#document-reader2 .dragMe2 ').each(function(i, e) {
			     var currentElemetWidth=$(this).innerWidth();
			    var currentElemetHeight=$(this).innerHeight();
			    var signid=($(this).data("signer-id"));    
			    var xpos=$(this).css("left");
			    xpos=parseInt(xpos,10);
			    xpos=xpos=(xpos*2);
			    var ypos=$(this).css("top"); 
			    ypos=parseInt(ypos,10);
			    ypos=(ypos*2);
			    var is_pic=$(this).data("ispic");
			    var sample=$(this).data("sample"); 
			   // var xposwithDiv=xpos;
			     var yposwithDiv=ypos+(currentElemetHeight+10);
           if(is_pic==1){
           var yposwithDiv=ypos+10;
           xpos=xpos-25;
           }
			     stuff.push( {'id':signid,'x':parseInt(xpos),'y':parseInt(yposwithDiv),'template_id':template_id,'userid':userid,'type':2} );
			    });

	      	   $.ajax({
			      url: '<?php echo BASE_URL;?>/save_position.php',
			      data: {
			       stuff			       
			      },
			      type: "POST",
			      dataType: "script",
			      success: function(data) {
				      console.log(data);
				      window.location.href = "import_users.php";	
				  }
			    })

      }else{
        alert("Please drop few item(s) to submit");
      }
      });

   	   $( "#frontview" ).click(function() {	
   	   	$draglenthFront=$('#document-reader .dragMe1').length;
		  var stuff = [];		  
		    if($draglenthFront){
			  $('#document-reader .dragMe1 ').each(function(i, e) {
			    var currentElemetWidth=$(this).innerWidth();
			    var currentElemetHeight=$(this).innerHeight();
			    var signid=($(this).data("signer-id"));
          var ispic=($(this).data("ispic"));     
			    var xpos=$(this).css("left");
			    xpos=parseInt(xpos,10);
			    xpos=xpos=(xpos*2);
			    var ypos=$(this).css("top"); 
			    ypos=parseInt(ypos,10);
			    ypos=(ypos*2);
			    var is_pic=$(this).data("ispic");
			    var sample=$(this).data("sample");
			    var frontpath = $('#frontimg').attr('src');
			    // var xposwithDiv=xpos;
          var yposwithDiv=ypos+(currentElemetHeight+10);
          if(ispic==1){
			     var yposwithDiv=ypos+10;
           xpos=xpos-25;
         }
			     stuff.push( {'id':signid,'x':parseInt(xpos),'y':parseInt(yposwithDiv),'is_pic':is_pic,'sample':sample,'frontpath':frontpath} );
			    });		    
			   
			   $.ajax({
			      url: '<?php echo BASE_URL;?>/preview.php',
             cache: false,
			      data: {
			       stuff,
			       'view':1
			      },
			      type: "POST",
			      dataType: "script",
           // timeout: 5000,
			      success: function(data) {              
			      	$("#myModal").modal("show");			      	
              $("#imgsrc").attr("src", data+"?"+(new Date).getTime());
              //$("#imgarea").html(data);             
			      }

			    })
		    }else{
        alert("Please drop few item(s) to preview");
      }
		});

 $( "#backview" ).click(function() {
	 var backpath=$("#backimg").val();
	var stuff = [];
 $draglenthBack=$('#document-reader2 .dragMe2').length;
  if($draglenthBack){
  $('#document-reader2 .dragMe2 ').each(function(i, e) {
     var currentElemetWidth=$(this).innerWidth();
    var currentElemetHeight=$(this).innerHeight();
    var signid=($(this).data("signer-id"));    
    var xpos=$(this).css("left");
    xpos=parseInt(xpos,10);
    xpos=xpos=(xpos*2);
    var ypos=$(this).css("top"); 
    ypos=parseInt(ypos,10);
    ypos=(ypos*2);
    var is_pic=$(this).data("ispic");
    var sample=$(this).data("sample");
    var backpath = $('#backimg').attr('src');
    
   // var xposwithDiv=xpos;
     var yposwithDiv=ypos+(currentElemetHeight+10);
     stuff.push( {'id':signid,'x':parseInt(xpos),'y':parseInt(yposwithDiv),'is_pic':is_pic,'sample':sample,'backpath':backpath} );
    });
     $.ajax({
      url: '<?php echo BASE_URL;?>/preview.php',
      data: {
       stuff,
        'view':2
      },
      type: "POST",
      dataType: "script",
      success: function(data) {
      	$("#myModal").modal("show");
        $("#imgsrc").attr("src", data+"?"+(new Date).getTime());
      }
    })
 }else{
        alert("Please drop few item(s) to preview");
      }
});
});

  //remove the dragsigner when click on close icon
  $(document).on("click", ".closeIt", function(){
    var parent = $(this).parent();
    parent.remove();
    var removedelement=$(this).attr('did');
    $("#"+removedelement).show();
  });

function DragSigner(){
    $(".dragSigners").draggable({
      helper: 'clone',
      cursor: 'move',
      tolerance: 'fit',
      revert: true,
      containment:".document-content",
    });
    var currentParent;
    $("#document-reader").droppable({
      accept: '.dragSigners',
      activeClass: "drop-area",
      drop: function(e, ui) {
        dragEl = ui.helper.clone();
        ui.helper.remove();
        document_id   = dragEl.data("document-id");
        signer_id     = dragEl.data("signer-id");
        $sample=dragEl.data("sample");
        leftPosition  = ui.offset.left - $(this).offset().left;
        topPosition   = ui.offset.top - $(this).offset().top; 
        $ispic     = dragEl.data("ispic");
        // debug current dropped position
        //alert("top: " + topPosition + ", left: " + leftPosition); 
        dragEl.data("signer-id", signer_id);            
        dragEl.draggable({
          containment:"#document-reader",
          helper: 'original',
          cursor: 'move',
          tolerance: 'fit',
          drop: function (event, ui) {
            $(ui.draggable).remove();
          }
        });
        $dynamicClass="dragMe1";        
        // append element to #document-reader
        dragEl.addClass($dynamicClass);
        dragEl.removeClass("dragSigners col-sm-4 parent");
        dragEl.find("span.closeIt").removeClass("hideIt");
        if($ispic ==1)  {
          $sample="<img src ='"+$sample+"' width='150' height='200'/>";
        } 
         dragEl.find("#sampledata").html($sample);         
        //dragEl.appendTo("#document-reader");
        $(this)
                .append(dragEl.css({
                position: 'absolute',
                left: leftPosition+'px',
                top: topPosition+'px'
            }));
        
        $('#'+document_id).hide();
        // update draged element position to database
        // updateDraggedPosition(dragEl, stopPosition, document_id, signer_id)

        // activate dragging for cloned element      
       // DragMe(area);
      }
    });

     $("#document-reader2").droppable({
      accept: '.dragSigners',
      activeClass: "drop-area",
      drop: function(e, ui) {
        dragEl = ui.helper.clone();        
        ui.helper.remove();        
        document_id   = dragEl.data("document-id");
        signer_id     = dragEl.data("signer-id");
        $sample=dragEl.data("sample");
        $ispic     = dragEl.data("ispic");
        leftPosition  = ui.offset.left - $(this).offset().left;
        topPosition   = ui.offset.top - $(this).offset().top; 
        // debug current dropped position
       // alert("top: " + topPosition + ", left: " + leftPosition); 
        dragEl.data("signer-id", signer_id);
          
        dragEl.draggable({
           containment:"#document-reader2",
          helper: 'original',
          cursor: 'move',
          tolerance: 'fit',
          drop: function (event, ui) {
            $(ui.draggable).remove();
          }
        });
        $dynamicClass="dragMe2";        
        // append element to #document-reader
        dragEl.addClass($dynamicClass);
        dragEl.removeClass("dragSigners col-sm-4 parent");
        dragEl.find("span.closeIt").removeClass("hideIt");
        if($ispic ==1)  {
          $sample="<img src ='"+$sample+"' width='150' height='200'/>";
        }
        dragEl.find("#sampledata").html($sample);      
        //dragEl.appendTo("#document-reader2");
         $(this)
                .append(dragEl.css({
                position: 'absolute',
                left: leftPosition+'px',
                top: topPosition+'px'
            }));         
        //$('#'+document_id).remove();
        $('#'+document_id).hide();
        

        // update draged element position to database
        // updateDraggedPosition(dragEl, stopPosition, document_id, signer_id)

        // activate dragging for cloned element      
       // DragMe(area);
      }
    });

  }



</script>
<?php //include "footer.php";?>