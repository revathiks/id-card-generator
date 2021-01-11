<?php include "header.php";?>
  <!-- Start your project here-->  
<div class="content-wrapper">
	  <div class="container">
       <h2 class="h2-responsive"><strong>ID Cards History</strong></h2>
    
          <table class="table table-bordered">
          <thead class="black white-text">
            <tr style='text-align:center;'>
              <th scope="col">#</th>
              <th scope="col">Name</th>
			  <th scope="col">Download</th>
              <th scope="col">Created Date</th>             
            </tr>
          </thead>
          <tbody>
            <?php
            $sno=1;
            $targetPath="id_cards";
            $directories = glob($targetPath . '/*' , GLOB_ONLYDIR);            
            if(!empty($directories) && count($directories)>0 ){ 
               foreach($directories as $key=>$dir){ 
                 
                            $ffs = scandir($dir); 
                            foreach($ffs as $ff){                         
                                $ext = pathinfo($ff, PATHINFO_EXTENSION);
                                if($ff != '.' && $ff != '..' && $ext == 'zip'){   
                                  echo "<tr>";
                                  echo "<td style='text-align:center;'>".($sno)."</td>"; 
								  $created=date ("F d Y H:i:s.", filemtime($dir."/".$ff)); echo 
								  "<td class='downloadlink'>".ucfirst(pathinfo($ff,PATHINFO_FILENAME))." </td>";
									echo "<td style='text-align:center;'><i src='".$dir."' filename='".$ff."' class='fas fa-file-download'></i></td>";
                                    echo "<td>".$created."</td>";
									echo "<tr>";
                                    $sno++;
                                }

                            }
                            
                          }
                          
              }else{
              echo "<tr><td colspan='3' style='text-align:center;'>No records found</td></tr>";
            }
                      
                       
            ?>
           
          </tbody>
        </table>
        <form method="POST" name="download" id="download" action="<?php echo BASE_URL;?>/download.php">
          <input type="hidden" name="filename" value="" id="filename">
          <input type="hidden" name="src" value="" id="src"/>
        </form>
   </div>
 </div>
  <!-- End your project here-->
<!-- jQuery -->
<script type="text/javascript" src="js/jquery.min.js"></script> 
  <!-- Your custom scripts (optional) -->
  <script type="text/javascript">
    $(document).ready(function(){
     $(".fa-file-download").click(function(){
     $("#filename").val($(this).attr("filename"));
     $("#src").val( $(this).attr("src"));    
     $("#download").submit();   
      })
    });
  </script>

<?php include "footer.php";?>