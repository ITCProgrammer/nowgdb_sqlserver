<?php
ini_set("error_reporting", 1);
include("../koneksi.php");
$ip_num = $_SERVER['REMOTE_ADDR'];
$os= $_SERVER['HTTP_USER_AGENT'];
    $modal_id=$_GET['id'];
	$cSQL=sqlsrv_query_safe($con,"SELECT * FROM dbnow_gdb.tbl_stok_keluar a LEFT JOIN dbnow_gdb.tbl_benang_keluar b ON a.id=b.id_stok WHERE b.id='".sqlsrv_escape_str($modal_id)."'");
	$cR=($cSQL !== false) ? sqlsrv_fetch_array($cSQL, SQLSRV_FETCH_ASSOC) : [];
	$Doc=$cR['no_doc'];
	$modal1=sqlsrv_query_safe($con,"UPDATE dbnow_gdb.tbl_stoklegacy SET ada='1' WHERE sn='".sqlsrv_escape_str($cR['sn'])."'");
    $modal0=sqlsrv_query_safe($con,"DELETE FROM dbnow_gdb.tbl_benang_keluar WHERE id='".sqlsrv_escape_str($modal_id)."' ");    
    if ($modal1) {
        sqlsrv_query_safe($con,"INSERT into dbnow_gdb.tbl_log 
	(what, what_do, project, do_by, do_at, ip, os) VALUES
	('Delete Data Benang Keluar',
	'Delete Data Benang Keluar ".sqlsrv_escape_str($cR['sn'])."',
	'$Doc',
	'GDB',
	GETDATE(),
	'$ip_num',
	'$os')");
		echo "<script>window.location='BenangKeluarLegacy-$Doc';</script>";
		
    } else {
        echo "<script>alert('Gagal Hapus');window.location='BenangKeluarLegacy-$Doc';</script>";
    }
?>
