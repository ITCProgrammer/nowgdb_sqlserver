<?php
sqlsrv_query_safe($con, "INSERT INTO dbnow_gdb.tbl_upload_bs ([status]) 
					VALUES ('Open')");
echo "<script type=\"text/javascript\">
            alert(\"Data Berhasil Ditambah\");
            window.location = \"DataUploadBS\"
            </script>";
?>
