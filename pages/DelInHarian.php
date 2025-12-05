<?php
$tgl = $_GET['tgl'];
mysqli_query($con, "DELETE FROM tblmasukbenang WHERE tgl_tutup='$tgl'");

echo "<script type=\"text/javascript\">
            window.location = \"TutupInOutHarian\"
      </script>";
?>