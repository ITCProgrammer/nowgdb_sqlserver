<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
?>
<!-- Main content -->
<form role="form" method="post" enctype="multipart/form-data" name="form1">
<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Tgl Tutup Persediaan Benang</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->		  
          <div class="card-body">
			<div class="alert alert-primary alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-info"></i> Note!</h5>
                  * Tutup Transaksi Jam 23:00<br>
				
                </div>
             <div class="form-group row">
               <label for="tgl_awal" class="col-md-1">Tgl Tutup</label>
               <div class="col-md-2">  
                 <div class="input-group date" id="datepicker1" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datepicker1" data-toggle="datetimepicker">
                      <span class="input-group-text btn-info">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input name="tgl_awal" value="<?php echo $Awal;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" required>
                 </div>
			   </div>
			   	 
            </div>	  	 
			  
          </div>	
		  <div class="card-footer">
			<button class="btn btn-info" type="submit" name="submit">Submit</button>
		</div>	
		  <!-- /.card-body -->          
        </div>
      <div class="container-fluid">
				
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Data Persediaan Benang Harian Per ElementCode</h3>				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 14px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">Action</th>
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
$no = 1;
$c  = 0;

if ($Awal !== '') {
    $sql = sqlsrv_query(
        $con,
        "
        ;WITH base AS (
            SELECT
                sn,
                tgl_tutup,
                tipe,
                qty,
                cones,
                weight,
                id
            FROM dbnow_gdb.tblopname_detail_11
            WHERE tgl_tutup = ?
        ),
        dedup AS (
            SELECT *,
                   ROW_NUMBER() OVER (PARTITION BY sn, tgl_tutup, tipe ORDER BY id ASC) AS rn
            FROM base
        ),
        agg AS (
            SELECT
                tgl_tutup,
                tipe,
                SUM(qty)   AS qty,
                SUM(cones) AS cones,
                SUM(weight) AS kg
            FROM dedup
            WHERE rn = 1
            GROUP BY
                tgl_tutup,
                tipe
        )
        SELECT TOP 120
            tgl_tutup,
            tipe,
            qty,
            cones,
            kg,
            CONVERT(varchar(10),GETDATE(),120) AS tgl
        FROM agg
        ORDER BY tgl_tutup DESC",
        array($Awal)
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
                <a href="pages/cetak/DetailOpnameDetail11Excel.php?tgl=<?php echo $tglTutup; ?>&tipe=<?php echo $r['tipe']; ?>"
                   class="btn btn-danger btn-xs" target="_blank">
                    <i class="fa fa-excel"></i> Lihat Data
                </a>
                 ||
                <a href="pages/cetak/DetailOpnameProses11Excel.php?tgl=<?php echo $tglTutup; ?>&tipe=<?php echo $r['tipe']; ?>"
                   class="btn btn-primary btn-xs" target="_blank">
                    <i class="fa fa-file-excel"></i> Excel
                </a>
            </td>
            <td style="text-align: center">
                <a href="DetailOpnameDetail11-<?php echo $tglTutup; ?>-<?php echo $r['tipe']; ?>"
                   class="btn btn-info btn-xs" target="_blank">
                    <i class="fa fa-link"></i> Lihat Data
                </a>
            </td>
            <td style="text-align: center"><?php echo $tglTutup; ?></td>
            <td style="text-align: center"><?php echo $r['tipe']; ?></td>
            <td style="text-align: center"><?php echo number_format($r['qty']); ?></td>
            <td style="text-align: center"><?php echo number_format($r['cones']); ?></td>
            <td style="text-align: right"><?php echo number_format($r['kg'], 2); ?></td>
        </tr>
        <?php
        $no++;
    }
}
?>
				  </tbody>
                  <tfoot>				
					</tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div> 		
      </div><!-- /.container-fluid -->
    <!-- /.content -->
</form>	
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>	
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
