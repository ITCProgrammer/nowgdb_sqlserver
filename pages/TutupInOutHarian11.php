<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
?>
<!-- SweetAlert2 -->
<link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
<link rel="stylesheet" href="plugins/toastr/toastr.min.css">

<!-- Main content -->
      <div class="container-fluid">	
		<div class="alert alert-primary alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-info"></i> Note!</h5>
                  * Tutup Transaksi Jam 23:00<br> 
                </div>	
		<div class="row">
			<div class="col-md-6">	
		<div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Detail Data Masuk Benang Perhari</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 14px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">Detail</th>
                    <th valign="middle" style="text-align: center">Tgl Tutup</th>
                    <th valign="middle" style="text-align: center">Rol</th>
                    <th valign="middle" style="text-align: center">KG</th>
                    </tr>
                  </thead>
                  <tbody>
<?php
$no = 1;
$c  = 0;

$sql = sqlsrv_query(
    $con,
    "SELECT TOP 30 
        tgl_tutup,
        SUM(qty)   AS rol,
        SUM(berat) AS kg,
        CONVERT(varchar(10),GETDATE(),120) AS tgl
     FROM dbnow_gdb.tblmasukbenang_11
     GROUP BY tgl_tutup
     ORDER BY tgl_tutup DESC"
);

if ($sql === false) {
    die(print_r(sqlsrv_errors(), true));
}

while ($r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {
    $tglTutup = fmt_date($r['tgl_tutup']);
    ?>
    <tr>
        <td style="text-align: center"><?php echo $no; ?></td>
        <td style="text-align: center">
            <a href="DetailInHarian11-<?php echo $tglTutup; ?>"
               class="btn btn-info btn-xs" target="_blank">
                <i class="fa fa-link"></i> Lihat Data
            </a>
        </td>
        <td style="text-align: center"><?php echo $tglTutup; ?></td>
        <td style="text-align: center"><?php echo $r['rol']; ?></td>
        <td style="text-align: right"><?php echo number_format(round($r['kg'], 2), 2); ?></td>
    </tr>
    <?php
    $no++;
}
?>
				  </tbody>
                  <tfoot>				
				  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
          </div>
			</div>
			<div class="col-md-6">
		<div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Detail Data Keluar Benang Perhari</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example3" class="table table-sm table-bordered table-striped" style="font-size: 14px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">Detail</th>
                    <th valign="middle" style="text-align: center">Tgl Tutup</th>
                    <th valign="middle" style="text-align: center">Rol</th>
                    <th valign="middle" style="text-align: center">KG</th>
                    </tr>
                  </thead>
                  <tbody>
<?php
$no = 1;
$c  = 0;

$sql = sqlsrv_query(
    $con,
    "SELECT TOP 30 
        tgl_tutup,
        SUM(qty)   AS rol,
        SUM(berat) AS kg,
        CONVERT(varchar(10),GETDATE(),120) AS tgl
     FROM dbnow_gdb.tblkeluarbenang_11
     GROUP BY tgl_tutup
     ORDER BY tgl_tutup DESC"
);

if ($sql === false) {
    die(print_r(sqlsrv_errors(), true));
}

while ($r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)) {
    $tglTutup = fmt_date($r['tgl_tutup']);
    ?>
    <tr>
        <td style="text-align: center"><?php echo $no; ?></td>
        <td style="text-align: center">
            <a href="DetailOutHarian11-<?php echo $tglTutup; ?>"
               class="btn btn-info btn-xs" target="_blank">
                <i class="fa fa-link"></i> Lihat Data
            </a>
        </td>
        <td style="text-align: center"><?php echo $tglTutup; ?></td>
        <td style="text-align: center"><?php echo $r['rol']; ?></td>
        <td style="text-align: right"><?php echo number_format(round($r['kg'], 2), 2); ?></td>
    </tr>
    <?php
    $no++;
}
?>
				  </tbody>
                  <tfoot>				
					</tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>  
			</div>	
		</div>
		
      </div><!-- /.container-fluid -->
    <!-- /.content -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>	
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
