<?php
include "header.php";
?>
<div class="content-wrapper">
<div class="container">
<?php
error_reporting(0);
$foldername=strtolower($_POST['foldername']);
/**
 * Text Alignments
 */
function textSize($size, $angle, $font, $text) {
    $f = imagettfbbox($size, $angle, $font, $text);
    return array(
        'height' => $f[1] - $f[5],
        'width' => $f[4] - $f[0]
    );
}

function textWithAligned(&$im, $size, $angle, $xpos,$y, $color, $font, $text, $align) {
    // Image/text sizes
    $imageSize = array(
    'height' => imagesy($im),
    'width' => imagesx($im)
    );
    $textSize = textSize($size, $angle, $font, $text);
    $bgImgWidth=$imageSize['width'];
    $textWidth=$textSize['width'];
    $dragLeft=$xpos;
    //pending width after text generate based on dynamic data with font style
    $pendingWidth=$bgImgWidth-$textWidth;
    
    if($dragLeft > $pendingWidth){
        $x=$pendingWidth;
    }else{
        $x=$xpos;
    }
    // Render
    imagettftext($im, $size, $angle, $x, $y, $color, $font, $text);
    return;
}

$employees=	array();
$field_array=$countarray=$postion_array= $employee_id=array();
if(isset($_POST['submit']))
{
    $field_array=$_POST;
    //echo "<pre>"; print_r($field_array); echo "</pre>"; 
    $count= count($field_array);
    $template_id=$_POST['template_id'];
    $employee_id=$_POST['emp_id'];
    $data_query="SELECT * FROM idg_user_template_position tp LEFT JOIN idg_fields f ON f.id=tp.field  WHERE tp.user_template_id=$template_id";
    $field_data=$conn->query($data_query);
    if(!empty($data_query)){
        while($postion_data=$field_data->fetch_assoc()){
            $type=$postion_data['type'];
            $field=$postion_data['field'];
            $xpos=$postion_data['xpos'];
            $ypos=$postion_data['ypos'];
            $isPic=$postion_data['is_pic'];
            $font_size=$postion_data['font_size'];
            $rgb=$postion_data['rgb'];
            $font_family=$postion_data['font_family'];
            if($type==1){
                $type_label="front";
            }else{
                $type_label="back";
            }
            if($isPic==1){
                $postion_array[$type_label]['img'][$field]= $field_array['input_'.$field];
                $postion_array[$type_label]['img'][$field]['pos']['x']=$xpos;
                $postion_array[$type_label]['img'][$field]['pos']['y']=$ypos;
                $postion_array[$type_label]['img'][$field]['cropped']=$field_array['crop_id'];
            }else{
            //$fields=$field_array;
            $postion_array[$type_label]['nonimg'][$field]= $field_array['input_'.$field];
            $postion_array[$type_label]['nonimg'][$field]['pos']['x']=$xpos;
            $postion_array[$type_label]['nonimg'][$field]['pos']['y']=$ypos;
            $postion_array[$type_label]['nonimg'][$field]['style']['font_size']=$font_size;
            $postion_array[$type_label]['nonimg'][$field]['style']['rgb']=$rgb;
            $postion_array[$type_label]['nonimg'][$field]['style']['font_family']=$font_family;
            $postion_array[$type_label]['nonimg']['employee_id']=$employee_id;
            }
         }
         //$postion_array['back']['nonimg']['employee_id']=$employee_id;
    }
    $image_array=array();
   $image_query="SELECT * FROM idg_templates WHERE id=$template_id ";
   $image_data=$conn->query($image_query);
   if(!empty($image_data)){
       $row = $image_data->fetch_assoc(); 
       $front_image=BASE_URL."/templates/".$row['front_path'];
       $back_image=BASE_URL."/templates/".$row['back_path'];
   }
   if (!file_exists('uploads/'.$foldername.'/idcard')) {
		mkdir('uploads/'.$foldername.'/idcard', 0755, true);
	}
	$employees=$postion_array;
	//echo "<pre>"; print_r($postion_array); echo "</pre>"; 
    if(!empty($_POST['myTextEditBox'])){    
        $myTextEditBox=$_POST['myTextEditBox'];    
        foreach($myTextEditBox as $index){
        $bgpic = imagecreatefrompng($front_image);
        
        $namecolor = imagecolorallocate($bgpic,34,48,62);
        $font_bold=__DIR__ ."/fonts/NexaBold.ttf";
        $font_year=__DIR__ ."/fonts/MyriadPro-Bold.ttf";
        $font_light=__DIR__ ."/fonts/MyriadPro-Regular.ttf";
        $font=__DIR__ ."/fonts/verdana.ttf";
        $f4=__DIR__ ."/fonts/avro.ttf";

        foreach($employees['front']['nonimg'] as $fields){
            $data=$fields[$index];           
            $length=strlen($data);
            $xpos=$fields['pos']['x'];
            $ypos=$fields['pos']['y'];
            $xpos=$xpos-$length;
            $rbgColor=$fields['style']['rgb'];
            $fontSize=$fields['style']['font_size'];
            $fontfamily=$fields['style']['font_family'];
            $rbgColorSep=explode(",",$rbgColor);
            list($r, $b, $g)=$rbgColorSep;
            $textColor = imagecolorallocate($bgpic,$r,$b,$g);
            $textFont=__DIR__ ."/fonts/".$fontfamily;     
            textWithAligned($bgpic,$fontSize, 0,$xpos,$ypos,$textColor,$textFont,$data, 'c');            
        }
        foreach($employees['front']['img'] as $fields){
             $cropped=$fields['cropped'][$index];
        	$save = 'uploads/'.$foldername.'/idcard/front_'.$fields[$index];
        	$avl = BASE_URL."/uploads/".$foldername."/photos/".$fields[$index];        	
        	list($picname,$picext) = explode('.', $fields[$index]);        	
        	if($cropped==1){
        	    $avl = BASE_URL."/uploads/".$foldername."/photos/".$picname."_crop.".$picext;  
        	}
           // $avl=$fields[$index];
            $xpos=$fields['pos']['x'];
            $ypos=$fields['pos']['y'];
            
            if(trim($avl!=""))
            {
                $imgi = getimagesize($avl);
                if($imgi[0]>0)
                {
                    if($imgi[2]==1)
                    {
                        $av = imagecreatefromgif($avl);
                        imagecopyresized($bgpic, $av,$xpos,$ypos,0,0,315,409,$imgi[0], $imgi[1]);
                    }else if($imgi[2]==2)
                    {
                        $av = imagecreatefromjpeg($avl);
                        imagecopyresized($bgpic, $av,($xpos+10),($ypos+3),0,0,306,390,$imgi[0], $imgi[1]);
                    }else if($imgi[2]==3)
                    {
                        $av = imagecreatefrompng($avl);
                        imagecopyresized($bgpic, $av,$xpos,$ypos,0,0,315,409,$imgi[0], $imgi[1]);
                    }
                    
                }
            }
            
        }       
        imagejpeg($bgpic,$save,100);
        imagedestroy($bgpic);
    }

    //back card image
    foreach($myTextEditBox as $index){
    $save = 'uploads/'.$foldername.'/idcard/back_'.$employees['back']['nonimg']['employee_id'][$index].".jpg";   
    $bgpic = imagecreatefrompng($back_image);
    $infcolor = imagecolorallocate($bgpic,0,0,0);
    $namecolor = imagecolorallocate($bgpic,34,48,62);
    $font_bold=__DIR__ ."/fonts/NexaBold.ttf";
    $font_year=__DIR__ ."/fonts/MyriadPro-Bold.ttf";
    $font_light=__DIR__ ."/fonts/MyriadPro-Regular.ttf";
    $font=__DIR__ ."/fonts/verdana.ttf";
    $f4=__DIR__ ."/fonts/avro.ttf";

    if(!empty($employees['back']['nonimg'])){
        foreach($employees['back']['nonimg'] as $fields){
            $data=$fields[$index];           
            $length=strlen($data);
            $xpos=$fields['pos']['x'];
            $ypos=$fields['pos']['y'];
            $xpos=$xpos-$length;
            $rbgColor=$fields['style']['rgb'];
            $fontSize=$fields['style']['font_size'];
            $fontfamily=$fields['style']['font_family'];
            $rbgColorSep=explode(",",$rbgColor);
            list($r, $b, $g)=$rbgColorSep;
            $textColor = imagecolorallocate($bgpic,$r,$b,$g);
            $textFont=__DIR__ ."/fonts/".$fontfamily; 
           textWithAligned($bgpic,$fontSize, 0,$xpos,$ypos,$textColor,$textFont,$data, 'c');
           // $save = 'uploads/'.$foldername.'/idcard/back_'.$fields[$index].".jpg";          
        }
    }
        if(!empty($employees['back']['img'])){
        foreach($employees['back']['img'] as $fields){        	
        	$avl = BASE_URL."/uploads/".$foldername."/photos/".$fields[$index];
           // $avl=$fields[$index];
            $xpos=$fields['pos']['x'];
            $ypos=$fields['pos']['y'];
            if(trim($avl!=""))
            {
                $imgi = getimagesize($avl);
                if($imgi[0]>0)
                {
                    if($imgi[2]==1)
                    {
                        $av = imagecreatefromgif($avl);
                        imagecopyresized($bgpic, $av,$xpos,$ypos,0,0,315,409,$imgi[0], $imgi[1]);
                    }else if($imgi[2]==2)
                    {
                        $av = imagecreatefromjpeg($avl);
                        imagecopyresized($bgpic, $av,($xpos+10),($ypos+3),0,0,306,390,$imgi[0], $imgi[1]);
                    }else if($imgi[2]==3)
                    {
                        $av = imagecreatefrompng($avl);
                        imagecopyresized($bgpic, $av,$xpos,$ypos,0,0,315,409,$imgi[0], $imgi[1]);
                    }
                    
                }
            }
            
        }   
        }    
        imagejpeg($bgpic,$save,100);
        imagedestroy($bgpic);        
    }
		// Enter the name of directory 
		$pathdir = 'uploads/'.$foldername.'/idcard/';    
		// Enter the name to creating zipped directory 
	   if (!file_exists('id_cards')) {
			mkdir('id_cards', 0755, true);
		}
		if (!file_exists('id_cards/'.$foldername)) {
			mkdir('id_cards/'.$foldername, 0755, true);
		}
		$zipfilename=$foldername."_idcards".time().".zip";
		$zipcreated = "id_cards/".$foldername."/".$zipfilename;   
		// Create new zip class 
		$zip = new ZipArchive;    
		if($zip -> open($zipcreated, ZipArchive::CREATE ) === TRUE) {       
		    // Store the path into the variable 
		    $dir = opendir($pathdir);        
		    while($file = readdir($dir)) { 
		        if(is_file($pathdir.$file)) { 
		            $zip -> addFile($pathdir.$file, $file); 
		        } 
		    } 
		    $zip ->close(); 
		} 

  }
}
	?>	
	<center class="content-pad">
	<h1>Click  icon to get  ID cards</h1>
		<i style="font-size:60px;" src='id_cards/<?php echo $foldername;?>' filename='<?php echo $zipfilename;?>' class='fas fa-file-download'></i></center>
	<form method="POST" name="download" id="download" action="<?php echo BASE_URL;?>/download.php">
          <input type="hidden" name="filename" value="" id="filename">
          <input type="hidden" name="src" value="" id="src"/>
        </form>
	</div>
</div>
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
<?php
include "footer.php";
?>
