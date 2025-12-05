<?php
//include("../koneksi.php");
if($_POST){ 
	extract($_POST);
	$intDoc = mysqli_real_escape_string($con,$_POST['intdoc']);
	$no=1;
	$sqllok=mysqli_query($con, "SELECT id,lokasi FROM tblambillokasi WHERE no_doc='".$_POST['intdoc']."'");					  
    while($rLok=mysqli_fetch_array($sqllok)){
	if($_POST['cek1'][$no]==$no){
	mysqli_query($con, "DELETE FROM tblambillokasi WHERE id='$rLok[id]'");	
	}	
		else{
			
		}
			
	$no++;	
	}
  echo "<script type=\"text/javascript\">
            window.location = \"ListMohonBenang\"
      </script>";
	
}
?>