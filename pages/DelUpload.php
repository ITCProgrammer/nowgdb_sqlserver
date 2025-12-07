<?php
$idUp = $_GET['id'];
sqlsrv_query_safe($con, "DELETE FROM dbnow_gdb.tbl_stokfull WHERE id_upload='$idUp'");
sqlsrv_query_safe($con, "DELETE FROM dbnow_gdb.tbl_upload WHERE id='$idUp'");

echo "<script type=\"text/javascript\">
            window.location = \"DataUpload\"
      </script>";
?>
