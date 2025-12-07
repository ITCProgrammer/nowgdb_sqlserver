<?php
$idUp = $_GET['id'];
sqlsrv_query_safe($con, "DELETE FROM dbnow_gdb.tbl_stokfull_bs WHERE id_upload='$idUp'");
sqlsrv_query_safe($con, "DELETE FROM dbnow_gdb.tbl_upload_bs WHERE id='$idUp'");

echo "<script type=\"text/javascript\">
            window.location = \"DataUploadBS\"
      </script>";
?>
