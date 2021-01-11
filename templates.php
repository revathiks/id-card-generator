<?php include "header.php";

if(isset( $_SESSION['userid'])){
    $userid=$_SESSION['userid'];
}
?>
<div class="content-wrapper">
	<div class="container">
		<div class="mid-wpr align-middle" >
        	<h4>Template List</h4>
        	<table class="table table-bordered" style="margin-left: 0px;">
             <thead class="black white-text">
            	<tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Created</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
              	<tr>
                  <?php $sql="select * from idg_templates order by code";
                    $result = $conn->query($sql);
                    $pathArray=array();
                    if ($result->num_rows > 0) {
                        $frontPath=$backPath="";
                        while($row = $result->fetch_assoc()){
                            //print_r($row);
                            $id=$row['id'];
                            $name=$row['name'];
                            $date=$row['code'];
                            $date=$row['createon'];
                            $frontPath= BASE_URL."/templates/".$row['front_path'];
                            $backPath= BASE_URL."/templates/".$row['back_path'];
                            ?>
                        <td><?php echo $id; ?></td>
                        <td><?php echo $name;?></td>
                        <td><?php echo $date;?></td>
                        <td>
                        	<button type="button" class="btn btn-info btn-sm view-btn" data-front="<?php echo $frontPath;?>" data-back="<?php echo $backPath;?>" id="path" data-toggle="modal" data-target="#myModal">View</button>
                       	</td>
                   </tr>
                 <?php
                    }
                }?>
                   <!-- Modal -->
                        <div id="myModal" class="modal fade" role="dialog">
                          <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content">
                            	<div class="modal-header">
                                	<button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                              <div class="modal-body">
                             	 <div class="row">
                                        <div class="col-md-6">
                                       		 <img id="my_changing_image_front" src=""  width="338" height="525" ><br>
                                         </div>
                                         <div class="col-md-6">
                                          	<img id="my_changing_image_back" src=""  width="338" height="525" >
                                          </div>
                                     </div>
                              </div>
                            </div>
                        
                          </div>
                        </div>
                	
             </table>
        </div>
    </div>


 </div>
 <script type="text/javascript" src="js/jquery.min.js"></script>
   <script type="text/javascript">
  $(document).ready(function() {
	$( ".btn" ).click(function (e) {
		var front =$(this).attr('data-front');
		var back =$(this).attr('data-back');
		$('#my_changing_image_front').attr('src',front);
		$('#my_changing_image_back').attr('src',back);
	});
  });
  </script>
<?php include "footer.php";?>
