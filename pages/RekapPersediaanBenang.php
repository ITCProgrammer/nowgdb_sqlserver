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
<!-- Main content -->
<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Rekap Stock Benang Perhari</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <table id="example1" class="table table-sm table-bordered table-striped" style="font-size:13px;">
        <thead>
          <tr>
            <th style="text-align: center">Tipe</th>
            <th style="text-align: center">Tgl Download</th>
            <th style="text-align: center">Tanggal</th>
            <th style="text-align: center">Code</th>
            <th style="text-align: center">Jenis Benang</th>
            <th style="text-align: center">Lot</th>
            <th style="text-align: center">SupplierCode</th>
            <th style="text-align: center">Supplier</th>
            <th style="text-align: center">No. PO</th>
            <th style="text-align: center">Grade</th>
            <th style="text-align: center">Weight</th>
            <th style="text-align: center">Qty</th>
            <th style="text-align: center">Cones</th>
            <th style="text-align: center">Zone</th>
            <th style="text-align: center">Lokasi</th>
            <th style="text-align: center">Terima</th>
          </tr>
        </thead>
        <tbody>
          <?php          	 
$no=1;   
$c=0;

$sql = sqlsrv_query(
    $con,
    "
    ;WITH base AS (
        SELECT
            sn,
            tgl_tutup,
            tgl,
            tipe,
            kd_benang,
            jenis_benang,
            lot,
            suppliercode,
            suppliername,
            po,
            grd,
            zone,
            lokasi,
            terima,
            qty,
            cones,
            weight,
            id
        FROM dbnow_gdb.tblopname_detail_11
        WHERE tgl_tutup = ?
    ),
    dedup_sn AS (
        SELECT *,
               ROW_NUMBER() OVER (PARTITION BY sn, tgl_tutup, tipe ORDER BY id ASC) AS rn_sn
        FROM base
    ),
    deduped AS (
        SELECT *
        FROM dedup_sn
        WHERE rn_sn = 1
    ),
    agg AS (
        SELECT
            tgl_tutup,
            tgl,
            lot,
            zone,
            lokasi,
            terima,
            SUM(qty)    AS qty,
            SUM(cones)  AS cones,
            SUM(weight) AS weight
        FROM deduped
        GROUP BY
            tgl_tutup,
            tgl,
            lot,
            zone,
            lokasi,
            terima
    ),
    final_cte AS (
        SELECT
            a.tgl_tutup,
            a.tgl,
            d.tipe,
            d.kd_benang,
            d.jenis_benang,
            d.lot,
            d.suppliercode,
            d.suppliername,
            d.po,
            d.grd,
            d.zone,
            d.lokasi,
            d.terima,
            a.qty,
            a.cones,
            a.weight,
            ROW_NUMBER() OVER (
                PARTITION BY a.tgl_tutup, a.tgl, a.lot, a.zone, a.lokasi, a.terima
                ORDER BY d.id ASC
            ) AS rn_grp
        FROM agg a
        JOIN deduped d
          ON a.tgl_tutup = d.tgl_tutup
         AND a.tgl       = d.tgl
         AND a.lot       = d.lot
         AND a.zone      = d.zone
         AND a.lokasi    = d.lokasi
         AND a.terima    = d.terima
    )
    SELECT
        tgl_tutup,
        tgl,
        tipe,
        kd_benang,
        jenis_benang,
        lot,
        suppliercode,
        suppliername,
        po,
        grd,
        zone,
        lokasi,
        terima,
        qty,
        cones,
        weight
    FROM final_cte
    WHERE rn_grp = 1
    ORDER BY tgl DESC
    ",
    array($Awal)
);

if ($sql === false) {
    die(print_r(sqlsrv_errors(), true));
}

    while($rowdb21 = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){

        $tglTutup = $rowdb21['tgl_tutup'];
        if ($tglTutup instanceof DateTime) {
            $tglTutup = $tglTutup->format('Y-m-d');
        }
        $tgl = $rowdb21['tgl'];
        if ($tgl instanceof DateTime) {
            $tgl = $tgl->format('Y-m-d');
        }
		
	?>
            <tr>
              <td style="text-align: center">
                <?php echo $rowdb21['tipe']; ?>
              </td>
              <td style="text-align: center"><?php echo $tglTutup; ?></td>
              <td style="text-align: center"><?php echo $tgl; ?></td>
              <td style="text-align: left">
                <?php echo $rowdb21['kd_benang']; ?>
              </td>
              <td style="text-align: left">
                <?php echo $rowdb21['jenis_benang']; ?>
              </td>
              <td style="text-align: center">
                <?php echo $rowdb21['lot']; ?>
              </td>
              <td style="text-align: center">
                <?php echo $rowdb21['suppliercode']; ?>
              </td>
              <td style="text-align: right">
                <?php echo $rowdb21['suppliername']; ?>
              </td>
              <td style="text-align: right">
                <?php 
                  echo $rowdb21['po'];
				  ?>
              </td>
              <td style="text-align: center"><?php echo $rowdb21['grd']; ?></td>
              <td style="text-align: right">
                <?php echo number_format(round($rowdb21['weight'], 2), 2); ?>
              </td>
              <td style="text-align: right">
                <?php echo round($rowdb21['qty'], 0); ?>
              </td>
              <td style="text-align: right">
                <?php echo round($rowdb21['cones'], 0); ?>
              </td>
              <td style="text-align: center">
                <?php echo $rowdb21['zone']; ?>
              </td>
              <td style="text-align: center">
                <?php echo $rowdb21['lokasi']; ?>
              </td>
              <td style="text-align: center"><?php echo $rowdb21['terima']; ?></td>
            </tr>

            <?php $no++;
            $tKGs += $rowdb21['weight'];
            $tQTY += $rowdb21['qty'];
            $tCONES += $rowdb21['cones'];
          } ?>
        </tbody>
        <tfoot>
          <tr>
            <td style="text-align: left">&nbsp;</td>
            <td style="text-align: left">&nbsp;</td>
            <td style="text-align: left">&nbsp;</td>
            <td style="text-align: left">&nbsp;</td>
            <td style="text-align: left">&nbsp;</td>
            <td style="text-align: center">&nbsp;</td>
            <td style="text-align: right">&nbsp;</td>
            <td style="text-align: right">&nbsp;</td>
            <td style="text-align: right">&nbsp;</td>
            <td style="text-align: right">&nbsp;</td>
            <td style="text-align: right"><strong>
                <?php echo number_format(round($tKGs, 2), 2); ?>
              </strong></td>
            <td style="text-align: right"><strong>
                <?php echo number_format($tQTY); ?>
              </strong></td>
            <td style="text-align: right"><strong>
                <?php echo number_format($tCONES); ?>
              </strong></td>
            <td style="text-align: center">&nbsp;</td>
            <td style="text-align: center">&nbsp;</td>
            <td style="text-align: center">&nbsp;</td>
          </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
</div><!-- /.container-fluid -->
<!-- /.content -->
</form>
	<script>
  $(function () {
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

  });		
</script>
