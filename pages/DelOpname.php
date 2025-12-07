<?php
$tgl = $_GET['tgl'];
sqlsrv_query_safe($con, "DELETE FROM dbnow_gdb.tblopname WHERE tgl_tutup='$tgl'");

echo "<script type=\"text/javascript\">
            window.location = \"TutupHarian\"
      </script>";
?>
