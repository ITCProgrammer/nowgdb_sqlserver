<?php
//include("../koneksi.php");
if($_POST){ 
	extract($_POST);
	$intDoc = sqlsrv_escape_str($_POST['intdoc']);
	$no=1;
	$sqllok=sqlsrv_query_safe($con, "SELECT id,lokasi FROM dbnow_gdb.tblambillokasi WHERE no_doc='".$intDoc."'");					  
    while($sqllok !== false && ($rLok=sqlsrv_fetch_array($sqllok, SQLSRV_FETCH_ASSOC))){
	if($_POST['cek1'][$no]==$no){
	sqlsrv_query_safe($con, "DELETE FROM dbnow_gdb.tblambillokasi WHERE id='".$rLok['id']."'");	
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
