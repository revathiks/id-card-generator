<?php 
use League\Csv\Reader;
use League\Csv\Writer;

require './vendor/league/csv/autoload.php';
require './vendor/autoload.php';


$reader = Reader::createFromPath('./CM_Bulk_User_Import _4.csv', 'r');
$reader->setHeaderOffset(0); //explicitly sets the CSV document header record
$users=$reader->getRecords();
$records=array();
/* Zip Extract*/
$zip = new ZipArchive;
if ($zip->open('./photos.zip') === TRUE) {
    $zip->extractTo('./csv/');
    $zip->close();
} else {
}
?>
<html>
<?php
$firstname=$lastname=$employeeid=$contact_num=$image_left=array();
if(isset($_POST['submit'])){//to run PHP script on submit
if(!empty($_POST['myTextEditBox'])){
	//echo "<pre>"; print_r($_POST); echo "</pre>";
	$myTextEditBox=$_POST['myTextEditBox'];
	$firstname=$_POST['firstname'];
	$lastname=$_POST['lastname'];
	$employeeid=$_POST['employeeid'];
	$contact_num=$_POST['contact_num'];
	//$image_left = $_POST['user_images'];
	$first_name=$last_name=$emp_id=$contact_id=$image_left="";
// Loop to store and display values of individual checked checkbox.
		foreach($myTextEditBox as $data){
				echo "<pre>"; print_r($image_left); echo "</pre>";
				$first_name=$firstname[$data];
				$last_name=$lastname[$data];
				$emp_id=$employeeid[$data];
				$contact_id=$contact_num[$data];
				$image_src=$image_left[$data];
				echo "Firstname: ".$first_name."<br>";
				echo "Lastname: ".$last_name."<br>";
				echo "Employee Id: ".$emp_id."<br>";
				echo "Contact_Num: ".$contact_id."<br>";
				//echo "Image_src: ".$image_src."<br><br>";
			}
	}
}
?>
<body>
<form class="edit_table" name="edit_table" action="#" method="POST">
<table class="tutorial-table" width="100%" border="1" cellspacing="0">
		<tr>
			<th> Sl.no</th>
			<th> First name</th>
			<th> Last name</th>
			<th> Employee Id</th>
			<th> Contact NUM</th>
			<th> image</th>
			<th> Select</th>
		</tr>
<?php if(!empty($users)){
	$i=1;
	foreach ($users as $records) {
		//echo "<pre>"; print_r($records); echo "</pre>";
		$recordC=count($records);
		?>
			<tr class="hide">
				<td><input type="text" name="id[<?php echo $i;?>]" id="id" value="<?php echo $i;?>"></td>
				<td><input type="text" name="firstname[<?php echo $i;?>]" id="firstname" value="<?php echo $records['firstname']; ?>"></td>
				<td><input type="text" name="lastname[<?php echo $i;?>]" id="lastname" value="<?php echo $records['lastname']; ?>"></td>
				<td><input type="text" name="employeeid[<?php echo $i;?>]" id="employeeid" value="<?php echo $records['employeeid']; ?>"></td>
				<td><input type="text" name="contact_num[<?php echo $i;?>]" id="contact_num"  value="<?php echo $records['contact_num']; ?>"></td>
				<td><img src="./csv/<?php echo $records['employeeid']; ?>.jpg" name="user_images"  alt="" border='3' height='100' width='100' /></td>
				<td><input type="checkbox" name="myTextEditBox[]" id="myTextEditBox" value="<?php echo $i;?>" 
					style="margin-left:17px; margin-right:auto;">
				</td>
		   </tr><?php
		$i++;
	}
}
?>
</table>
<button type="submit" name="submit" value="Submit">Submit</button>
</form>
</body>
</html>

