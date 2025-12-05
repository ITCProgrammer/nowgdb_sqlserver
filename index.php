<?php
session_start();
//include config
include"koneksi.php";
ini_set("error_reporting", 1);

//request page
$page = isset($_GET['p'])?$_GET['p']:'';
$act  = isset($_GET['act'])?$_GET['act']:'';
$id   = isset($_GET['id'])?$_GET['id']:'';
$page = strtolower($page);
?>

<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>NOWgdb | <?php if ($_GET['p']!="") {
    echo ucwords($_GET['p']);
} else {
    echo "Home";
}?></title>

  <!-- Google Font: Source Sans Pro -->
  <!--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">-->
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">	
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">	
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">	
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">	  
  <!-- Theme style -->
  <style>
	  .blink_me {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
	</style>
<style>
    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    .loader {
      border: 4px solid #f3f3f3;
      /* Light grey */
      border-top: 4px solid #3498db;
      /* Blue */
      border-radius: 50%;
      width: 21px;
      height: 21px;
      animation: spin 2s linear infinite;
    }

    .loader.show {
      display: block;
    }
  </style>	
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="icon" type="image/png" href="dist/img/ITTI_Logo index.ico">	
</head>
<body class="hold-transition sidebar-collapse layout-top-nav">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md navbar-dark navbar-info">
    <div class="container">
      <a href="Home" class="navbar-brand">
        <img src="dist/img/ITTI_Logo 2021.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">NOW<strong>gdb</strong></span>
      </a>

      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="Home" class="nav-link">Home</a>
          </li>
		  <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Full Check</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="CheckStock" class="dropdown-item">Check Stock</a></li>
			  <li><a href="DataUpload" class="dropdown-item">Data Upload</a></li>
			  <li><a href="DataUploadBS" class="dropdown-item">Upload Data Jual BS</a></li>	
			</ul>
          </li>		
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Laporan</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">			  
			   <li class="dropdown-submenu dropdown-hover">
			    <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Masuk Benang</a>
				<ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                  <li>
                    <a tabindex="-1" href="MasukBenangSupplier" class="dropdown-item">Masuk Benang Supplier</a>
					<a tabindex="-1" href="MasukBenangRetur" class="dropdown-item">Retur Masuk</a>
					<a tabindex="-1" href="ReturMasukSupplier" class="dropdown-item">Masuk Benang Pengganti Dari Supplier</a>
					<a tabindex="-1" href="ReturMasukSupplierRMP" class="dropdown-item">Benang Sisa Po (RMP)</a>  
                  </li>
				</ul> 	
					</li>
			  <li class="dropdown-submenu dropdown-hover">
			    <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Keluar Benang</a>
				<ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                  <li>
                    <a tabindex="-1" href="KeluarBenang" class="dropdown-item">Keluar Benang Prod.</a>
					<a tabindex="-1" href="KeluarBenangRetur" class="dropdown-item">Retur Keluar</a> 
					<a tabindex="-1" href="JualBenang" class="dropdown-item">Jual Benang</a>  
                  </li>
				</ul> 	
					</li>
				<li><a href="LapBulanan" class="dropdown-item">Laporan Bulanan</a></li>
        <li><a href="LapRealisasi_Bon_permohonan_benang" class="dropdown-item">Laporan Realesasi Bon Permohonan Benang</a></li>
			</ul>
          </li>
		  <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Pergerakan Stock</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">			  
			  <li><a href="IdentifikasiBenang" class="dropdown-item">Identifikasi Benang</a></li>
			  <li><a href="IdentifikasiBenangPerLot" class="dropdown-item">Identifikasi Benang Per Lot</a></li>	
			  <li><a href="PersediaanBenangZone" class="dropdown-item">Persediaan Benang Per Zone</a></li>
			  <li><a href="PersediaanBenang" class="dropdown-item">Persediaan Benang</a></li>
			  <li><a href="RekapPersediaanBenang" class="dropdown-item">Rekap Persediaan Benang</a></li>	
			  <li><a href="PergerakanBenangLot" class="dropdown-item">Pergerakan Benang Per Lot</a></li>	
			</ul>
          </li>
		  <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Stock Opname</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
<!--              <li><a href="TutupHarian" class="dropdown-item">Tutup Transaksi Harian</a></li>-->
<!--			  <li><a href="TutupInOutHarian" class="dropdown-item">Tutup Transaksi In-Out Harian</a></li>-->
			  <li><a href="TutupHarian11" class="dropdown-item">Tutup Transaksi Harian (Jam 11)</a></li>
			  <li><a href="TutupHarianDetail11" class="dropdown-item">Tutup Detail Transaksi Harian (Jam 11)</a></li>	
			  <li><a href="TutupInOutHarian11" class="dropdown-item">Tutup Transaksi In-Out Harian (Jam 11)</a></li>
      </ul>
          </li>	
		  <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Bon Permohonan</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
               <li><a href="ListMohonBenang" class="dropdown-item">List Mohon Benang</a></li>
			</ul>
          </li>
<!--
		  <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Stock Legacy</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
               <li><a href="StockBenangLegacy" class="dropdown-item">Stock Benang</a></li>
			   <li><a href="BenangKeluarLegacy" class="dropdown-item">Benang Keluar</a></li>
			   <li><a href="LapBenangKeluarLegacy" class="dropdown-item">Laporan Benang Keluar</a></li>	
			</ul>
          </li>	
-->
        </ul>
      </div>
      
    </div>
  </nav>
  <!-- /.navbar -->
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
	<section class="content">  
    <div class="content">
     <?php
          if (!empty($page) and !empty($act)) {
              $files = 'pages/'.$page.'.'.$act.'.php';
          } elseif (!empty($page)) {
              $files = 'pages/'.$page.'.php';
          } else {
              $files = 'pages/home.php';
          }

          if (file_exists($files)) {
              include($files);
          } else {
              include_once("blank.php");
          }
          ?>
		
    </div>
	</section>	
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Indo Taichen Textile Industy
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; <?php echo date("Y");?> <a href="">DIT</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<!-- bs-custom-file-input -->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>	
<!-- InputMask -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- DataTables  & Plugins -->
  <script src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="plugins/jszip/jszip.min.js"></script>
  <script src="plugins/pdfmake/pdfmake.min.js"></script>
  <script src="plugins/pdfmake/vfs_fonts.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>	
<!-- Bootstrap Switch -->
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- BS-Stepper -->
<script src="plugins/bs-stepper/js/bs-stepper.min.js"></script>
<!-- dropzonejs -->
<script src="plugins/dropzone/min/dropzone.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "excel", "pdf"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
	$("#example1a").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "excel", "pdf"]
    }).buttons().container().appendTo('#example1a_wrapper .col-md-6:eq(0)');
	$("#example1b").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "excel", "pdf"]
    }).buttons().container().appendTo('#example1b_wrapper .col-md-6:eq(0)');
	$("#example1c").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "excel", "pdf"]
    }).buttons().container().appendTo('#example1c_wrapper .col-md-6:eq(0)');
	$("#example1d").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "excel", "pdf"]
    }).buttons().container().appendTo('#example1d_wrapper .col-md-6:eq(0)'); 
	$("#example1e").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "excel", "pdf"]
    }).buttons().container().appendTo('#example1e_wrapper .col-md-6:eq(0)');
	$("#example1f").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "excel", "pdf"]
    }).buttons().container().appendTo('#example1f_wrapper .col-md-6:eq(0)'); 
	$("#example1g").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "excel", "pdf"]
    }).buttons().container().appendTo('#example1g_wrapper .col-md-6:eq(0)');  
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
	$("#example3").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "excel", "pdf"]
    }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');
	$("#example4").DataTable({
	  "paging": false,	
      "responsive": true, 
	  "lengthChange": false,
	  "autoWidth": false,
	  "scrollX": true,
      "scrollY": '550px',	
      "buttons": ["copy", "excel", "pdf"]
    }).buttons().container().appendTo('#example4_wrapper .col-md-6:eq(0)');  
  });
</script>
<script>
	$(function () {
		
	//Initialize Select2 Elements
    $('.select2').select2()	
	//Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })	
		//Datepicker
    $('#datepicker').datetimepicker({
      format: 'YYYY-MM-DD'
    });
    $('#datepicker1').datetimepicker({
      format: 'YYYY-MM-DD'
    });
    $('#datepicker2').datetimepicker({
      format: 'YYYY-MM-DD'
    });
	//Date picker
    $('#reservationdate').datetimepicker({
        format: 'L'
    });
});		
</script>
<script>
$(document).on('click', '.show_detail', function(e) {
    var m = $(this).attr("id");
	
	$(this).before('<div class="loader" id="loader"></div>');
	
    $.ajax({
      url: "pages/show_detail.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#DetailShow").html(ajaxData);
        $("#DetailShow").modal('show', {
          backdrop: 'true'
        });
		  
		$("#loader").remove();  
      }
    });
  });
$(document).on('click', '.show_detailStkBenang', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/show_detailStkBenang.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#DetailShowStkBenang").html(ajaxData);
        $("#DetailShowStkBenang").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });
$(document).on('click', '.show_detailStkBenanglegacy', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/show_detailStkBenanglegacy.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#DetailShowStkBenangLegacy").html(ajaxData);
        $("#DetailShowStkBenangLegacy").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });	
$(document).on('click', '.show_detailLokBenang', function(e) {
    var m = $(this).attr("id");
    $.ajax({
      url: "pages/show_detailLokBenang.php",
      type: "GET",
      data: {
        id: m,
      },
      success: function(ajaxData) {
        $("#DetailShowLokBenang").html(ajaxData);
        $("#DetailShowLokBenang").modal('show', {
          backdrop: 'true'
        });
      }
    });
  });	
</script>
<script>
$(function () {
  bsCustomFileInput.init();
});
</script>	
</body>
</html>
