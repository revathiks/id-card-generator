<?php include "header.php";
if(isset($_SESSION["userid"])){
    header("Location:history.php");
}
if (isset($_POST['submit']) && !empty($_POST['submit'])) {
  $username=$_POST['username'];
  $password=$_POST['password'];
    $msg = '';
        $sql="select * from idg_user where username='$username' and password='$password'";
        $result = $conn->query($sql);
         if (isset($result) && $result->num_rows > 0) {
          $row = $result->fetch_row();                       
           $_SESSION['userid'] =$row[0];
           $_SESSION['username'] =$row[1];
            // output data of each row
           header("location: templates.php");
                
        }else {
                          $msg = 'Wrong username or password';
                      }
       
 }
?>

<div class="content-wrapper">
 <div class="container center_div">
   <div >
    <div class="mid-wpr align-middle">
    	<h4>LOGIN</h4>
      <?php if(!empty($msg)){ ?>     
              <div class="alert alert-danger" role="alert">
               <?php echo $msg;?>
              </div>
         <?php }
      ?>
    	<form class="text-center border border-light p-5 " action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <div class="row col-md-12 form-group">    
                          
                 	 <div class="col-sm-4">
                    	<input type="text" class="form-control"  id="username" placeholder="Enter Your Username" name="username" required>
                  	 </div>
                      <div class="col-sm-4">
                      <input type="password" class="form-control"  id="password" placeholder="Enter Your Password" name="password" required>
                     </div>
                      <div class="col-sm-3">
                      	 <!-- <input type="submit" name="submit" class="btn btn-primary waves-effect waves-light" value="submit"> -->
                            <input type="submit" class="btn btn-primary loginbtn" name="submit" id="submit" value="submit">
                      </div>
                   
                </div>
         </form>
  	</div>
  </div>
 </div>
</div>

<?php include "footer.php";?>
