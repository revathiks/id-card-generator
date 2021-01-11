<?php
include "config.php";
include "db.php";
/* echo "<pre>";
print_r($_POST);
echo "</pre>"; */
error_reporting(0);
if(!empty($_POST['stuff'])){
	$pixData=array();
	$stuff=$_POST['stuff'];
	for($i=0;$i<count($_POST['stuff']);$i++){
       $pixindex=$stuff[$i]['id'];
       $pixData[$pixindex]['x']=$stuff[$i]['x'];
       $pixData[$pixindex]['y']=$stuff[$i]['y'];
       $pixData[$pixindex]['is_pic']=$stuff[$i]['is_pic'];
       $pixData[$pixindex]['sample']=$stuff[$i]['sample'];
       if($stuff[$i]['frontpath']!=""){
           $pixData[$pixindex]['front_path']=$stuff[$i]['frontpath'];
       }
       if($stuff[$i]['backpath']!=""){
           $pixData[$pixindex]['back_path']=$stuff[$i]['backpath'];
       }
	}	
}
/*echo "<pre>";
print_r($pixData);
echo "</pre>";*/


if(!empty($pixData)){
    foreach ($pixData as $k=> $image) {
       if(!empty($image['front_path']) && $image['front_path']!=""){
            $front_path=$image['front_path'];
            $save = 'preview/front.jpg';
            $bgpic = imagecreatefrompng($front_path);
        }
        if(!empty($image['back_path']) && $image['back_path']!=""){
            $back_path=$image['back_path'];
            $save = 'preview/back.jpg';
            $bgpic = imagecreatefrompng($back_path);
        }
    }

foreach ($pixData as $key => $pix) {
	$data=$pix['sample'];
	$length=strlen($data);
	$xpos=$pix['x'];
	$ypos=$pix['y'];
	$ispic=$pix['is_pic'];
  $query="SELECT * FROM idg_fields WHERE id=$key";
  $queryData=$conn->query($query);
  if(!empty($queryData)){
    $row = $queryData->fetch_assoc(); 
    $rbgColor=$row['rgb'];
    $fontSize=$row['font_size'];
    $fontfamily=$row['font_family'];
    $rbgColorSep=explode(",",$rbgColor);
    list($r, $b, $g)=$rbgColorSep;
    $textColor = imagecolorallocate($bgpic,$r,$b,$g);
    $textFont=__DIR__ ."/fonts/".$fontfamily;

  }else{
  $fontSize=30;
	$textColor = imagecolorallocate($bgpic,0,0,0);
	$textFont=__DIR__ ."/fonts/NexaBold.ttf";
  }	
    if($ispic==0){    	
    $xpos=$xpos-$length;	
     textWithAligned($bgpic,$fontSize, 0,$xpos,$ypos,$textColor,$textFont,$data, 'c');  
   }else{
	   	$avl =$data;
		if(trim($avl!=""))
		{
		  $imgi = getimagesize($avl);
     // echo "<pre>";print_r($imgi);echo "</pre>";
		  if($imgi[0]>0)
		  {
		      if($imgi[2]==1)
		      {
		        $av = imagecreatefromgif($avl);
		        imagecopyresized($bgpic, $av,$xpos,$ypos,0,0,300,400,$imgi[0], $imgi[1]);
		      }else if($imgi[2]==2)
		      {
		        $av = imagecreatefromjpeg($avl);
		        imagecopyresized($bgpic, $av,($xpos+10),($ypos+3),0,0,300,400,$imgi[0], $imgi[1]);
		      }else if($imgi[2]==3)
		      {
		        $av = imagecreatefrompng($avl);
		        imagecopyresized($bgpic, $av,$xpos,$ypos,0,0,300,400,$imgi[0], $imgi[1]);
		      }

		  }
		}
   }
   
}
   imagejpeg($bgpic,$save,100);
   imagedestroy($bgpic);	  
	 echo BASE_URL."/".$save;
}

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
?>