<?php
$tgl = $_GET['tgl'];
sqlsrv_query_safe($con, "DELETE FROM dbnow_gdb.tblmasukbenang WHERE tgl_tutup='$tgl'");

echo "<script type=\"text/javascript\">
            window.location = \"TutupInOutHarian\"
      </script>";
?>
