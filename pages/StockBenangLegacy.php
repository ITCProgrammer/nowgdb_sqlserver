<!-- Main content -->
      <div class="container-fluid">	
		<form method="post" name="form1" action="pages/cetak/list-permohonan.php" target="_blank">  
	    <div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Data  Benang Legacy</h3> 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
          <table id="example4" class="table table-sm table-bordered table-striped" style="font-size:12px;">
                  <thead>
                  <tr>
                    <th width="15" style="text-align: center">No</th>
                    <th width="43" style="text-align: center">Action</th>
                    <th width="43" style="text-align: center">Tanggal</th>
                    <th width="20" style="text-align: center">Doc</th>
                    <th width="17" style="text-align: center">PO</th>
                    <th width="62" style="text-align: center">Surat Jalan</th>
                    <th width="27" style="text-align: center">Code</th>
                    <th width="46" style="text-align: center">Supplier</th>
                    <th width="71" style="text-align: center">Jenis Benang</th>
                    <th width="17" style="text-align: center">Lot</th>
                    <th width="20" style="text-align: center">Qty</th>
                    <th width="38" style="text-align: center">Satuan</th>
                    <th width="29" style="text-align: center">Berat</th>
                    <th width="33" style="text-align: center">Cones</th>
                    <th width="34" style="text-align: center">Status</th>
                    <th width="54" style="text-align: center">Inspection</th>
                    <th width="29" style="text-align: center">Lama</th>
                    <th width="25" style="text-align: center">Blok</th>
                    <th width="36" style="text-align: center">Aktual</th>
                    <th width="19" style="text-align: center">Ket</th>
                    <th width="54" style="text-align: center">Ket</th>
                    </tr>
                  </thead>
                  <tbody>
<?php 
$no=1;   
$c=0;				  
$sql = mysqli_query($con," SELECT *, sum(cones) as cones1, sum(berat) as berat1, count(qty) as qty1 FROM tbl_stoklegacy WHERE ada='1' GROUP BY no_doc, blok ");		  
    while($r = mysqli_fetch_array($sql)){ ?>
	  <tr>
	  <td style="text-align: center"><?php echo $no; ?></td>
	  <td style="text-align: center"><a href="#" id="<?php echo $r['no_doc']; ?>" class="btn btn-xs btn-info show_detailStkBenanglegacy">Detail</a></td>
      <td style="text-align: center"><?php echo $r['tgl']; ?></td>
      <td style="text-align: left"><?php echo $r['no_doc']; ?></td>
      <td style="text-align: left"><?php echo $r['po']; ?></td>
      <td style="text-align: center"><?php echo $r['surat_jalan']; ?></td>
      <td style="text-align: left"><?php echo $r['code']; ?></td>
      <td style="text-align: center"><?php echo $r['supplier']; ?></td>
      <td style="text-align: center"><?php echo $r['po']; ?></td>
      <td style="text-align: center"><?php echo $r['lot']; ?></td>
      <td style="text-align: center"><?php echo number_format($r['qty1'],0); ?></td>
      <td style="text-align: center"><?php echo $r['satuan']; ?></td>
      <td style="text-align: center"><?php echo number_format($r['berat1'],2); ?></td>
      <td style="text-align: center"><?php echo number_format($r['cones1'],0); ?></td>
      <td style="text-align: left"><?php echo $r['sts']; ?></td>
      <td style="text-align: center"><?php echo $r['inspection']; ?></td>
      <td style="text-align: center"><?php echo $r['lama']; ?></td>
      <td style="text-align: left"><?php echo $r['blok']; ?></td>
      <td style="text-align: left"><?php echo $r['aktual']; ?></td>
      <td style="text-align: left"><?php echo $r['ket']; ?></td>
      <td style="text-align: left"><?php echo $r['note']; ?></td>
      </tr>	  				  
	  <?php	
		$tqty1+=$r['qty1'];	
		$tberat1+=$r['berat1'];
		$tcones1+=$r['cones1'];								 
		$no++;} 
	  ?>
				  </tbody>
      <tfoot>
	  <tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: left">&nbsp;</td>
	    <td style="text-align: left">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: left">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center"><strong>Total</strong></td>
	    <td style="text-align: center"><strong><?php echo number_format($tqty1,0); ?></strong></td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center"><strong><?php echo number_format($tberat1,2); ?></strong></td>
	    <td style="text-align: center"><strong><?php echo number_format($tcones1,0); ?></strong></td>
	    <td style="text-align: left">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: left">&nbsp;</td>
	    <td style="text-align: left">&nbsp;</td>
	    <td style="text-align: left">&nbsp;</td>
	    <td style="text-align: left">&nbsp;</td>
	    </tr>		  
  </tfoot>            
                </table>
            </div>
              <!-- /.card-body -->
        </div>
		  </form>			
</div><!-- /.container-fluid -->
    <!-- /.content -->
<div id="DetailShowStkBenangLegacy" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
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