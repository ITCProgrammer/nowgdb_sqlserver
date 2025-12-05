<?php
ini_set("error_reporting", 1);
include("../koneksi.php");
$ip_num = $_SERVER['REMOTE_ADDR'];
$os= $_SERVER['HTTP_USER_AGENT'];
    $modal_id=$_GET['id'];
	$cSQL=mysqli_query($con,"SELECT * FROM tbl_stok_keluar a LEFT JOIN tbl_benang_keluar b ON a.id=b.id_stok WHERE b.id='$modal_id'");
	$cR=mysqli_fetch_array($cSQL);
	$Doc=$cR['no_doc'];
	$modal1=mysqli_query($con,"UPDATE tbl_stoklegacy SET ada='1' WHERE sn='".$cR['sn']."'");
    $modal0=mysqli_query($con,"DELETE FROM `tbl_benang_keluar` WHERE id='$modal_id' ");    
    if ($modal1) {
        mysqli_query($con,"INSERT into tbl_log SET
	`what` = 'Delete Data Benang Keluar',
	`what_do` = 'Delete Data Benang Keluar $cR[sn]',
	`project` = '$Doc',
	`do_by` = 'GDB',
	`do_at` = now(),
	`ip` = '$ip_num',
	`os` = '$os'");
		echo "<script>window.location='BenangKeluarLegacy-$Doc';</script>";
		
    } else {
        echo "<script>alert('Gagal Hapus');window.location='BenangKeluarLegacy-$Doc';</script>";
    }
?>