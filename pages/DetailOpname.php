<!-- Main content -->
      <div class="container-fluid">		
		  <div class="card card-pink">
              <div class="card-header">
                <h3 class="card-title">Stock Tanggal <?php echo $_GET['tgl']; ?> <?php echo $_GET['tipe']; ?></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
          <table id="example1" class="table table-sm table-bordered table-striped" style="font-size:13px;">
                  <thead>
                  <tr>
                    <th style="text-align: center">Tipe</th>
                    <th style="text-align: center">Code</th>
                    <th style="text-align: center">Jenis Benang</th>
                    <th style="text-align: center">PO</th>
                    <th style="text-align: center">Lot</th>
                    <th style="text-align: center">SupplierCode</th>
                    <th style="text-align: center">Supplier</th>
                    <th style="text-align: center">Weight</th>
                    <th style="text-align: center">Qty</th>
                    <th style="text-align: center">Cones</th>
                    <th style="text-align: center">Grade</th>
                    <th style="text-align: center">Zone</th>
                    <th style="text-align: center">Lokasi</th>
                    </tr>                  
                  </thead>
                  <tbody>
				  <?php				  
$no=1;   
$c=0;
 $sql = sqlsrv_query_safe($con," SELECT * FROM dbnow_gdb.tblopname WHERE tgl_tutup='".$_GET['tgl']."'and tipe='".$_GET['tipe']."' ORDER BY id ASC");		  
    while($sql !== false && ($r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC))){
		
?>
	  <tr>
	  <td style="text-align: left"><?php echo $r['tipe']; ?></td>
      <td style="text-align: left"><?php echo $r['kd_benang']; ?></td>
      <td style="text-align: center"><?php echo $r['jenis_benang']; ?></td>
      <td style="text-align: center"><?php echo $r['po']; ?></td>
      <td style="text-align: center"><?php echo $r['lot']; ?></td>
      <td style="text-align: center"><?php echo $r['suppliercode']; ?></td>
      <td style="text-align: center"><?php echo $r['suppliername']; ?></td>
      <td style="text-align: center"><?php echo $r['weight']; ?></td>
      <td style="text-align: left"><?php echo $r['qty']; ?></td>
      <td style="text-align: left"><?php echo $r['cones']; ?></td>
      <td style="text-align: left"><?php echo $r['grd']; ?></td>
      <td style="text-align: left"><?php echo $r['zone']; ?></td>
      <td style="text-align: left"><?php echo $r['lokasi']; ?></td>
      </tr>				  
<?php	$no++;
		$totqty=$totqty+$r['qty'];
		$totcones=$totcones+$r['cones'];
		$totkg=$totkg+$r['weight'];
	} ?>
				  </tbody>
				<tfoot>
                  <tr>
                    <td style="text-align: center">&nbsp;</td>
                    <td style="text-align: center">&nbsp;</td>
                    <td style="text-align: center">&nbsp;</td>
                    <td style="text-align: center">&nbsp;</td>
                    <td style="text-align: center">&nbsp;</td>
                    <td colspan="2" style="text-align: right"><strong>TOTAL</strong></td>
                    <td style="text-align: right"><strong><?php echo number_format(round($totkg,3),3); ?></strong></td>
                    <td style="text-align: right"><strong><?php echo number_format($totqty); ?></strong></td>
                    <td style="text-align: center"><strong><?php echo number_format($totcones); ?></strong></td>
                    <td style="text-align: right">&nbsp;</td>
                    <td style="text-align: right">&nbsp;</td>
                    <td style="text-align: right">&nbsp;</td>
                    </tr>
                  </tfoot>                  
                </table>
              </div>
              <!-- /.card-body -->
        </div>        
</div><!-- /.container-fluid -->
    <!-- /.content -->
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
