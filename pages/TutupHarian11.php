<!-- Main content -->
      <div class="container-fluid">
			<div class="alert alert-primary alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-info"></i> Note!</h5>
                  * Tutup Transaksi Jam 23:00<br>
                </div>	
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Data Persediaan Benang Perhari</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 14px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">Detail</th>
                    <th valign="middle" style="text-align: center">Tgl Tutup</th>
                    <th valign="middle" style="text-align: center">Tipe</th>
                    <th valign="middle" style="text-align: center">Qty</th>
                    <th valign="middle" style="text-align: center">Cones</th>
                    <th valign="middle" style="text-align: center">KG</th>
                    </tr>
                  </thead>
                  <tbody>
<?php	 
$no=1;   
$c=0;				  
$sql = sqlsrv_query(
    $con,
    "SELECT TOP 120 
        tgl_tutup,
        tipe,
        SUM(qty)   AS qty,
        SUM(cones) AS cones,
        SUM(weight) AS kg,
        FORMAT(GETDATE(),'yyyy-MM-dd') AS tgl
     FROM dbnow_gdb.tblopname_11
     GROUP BY tgl_tutup, tipe
     ORDER BY tgl_tutup DESC"
);

if ($sql === false) {
    die(print_r(sqlsrv_errors(), true));
}

    while($r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){
		
?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><a href="DetailOpname11-<?php echo fmt_date($r['tgl_tutup']);?>-<?php echo $r['tipe'];?>" class="btn btn-info btn-xs" target="_blank"> <i class="fa fa-link"></i> Lihat Data</a></td>
	  <td style="text-align: center"><?php echo fmt_date($r['tgl_tutup']);?></td>
	  <td style="text-align: center"><?php echo $r['tipe'];?></td>
      <td style="text-align: center"><?php echo number_format($r['qty']);?></td>
      <td style="text-align: center"><?php echo number_format($r['cones']);?></td>
      <td style="text-align: right"><?php echo number_format($r['kg'],2);?></td>
      </tr>
	  				  
	<?php 
	 $no++; 
	
	} ?>
				  </tbody>
                  <tfoot>				
					</tfoot>
                </table>
              </div>
              <!-- /.card-body -->
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
