<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-danger">
          <div class="card-header">
            <h3 class="card-title">Filter Data Tanggal Keluar</h3>

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
             <div class="form-group row">
               <label for="tgl_awal" class="col-md-1">Tgl Awal</label>
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
			 <div class="form-group row">
               <label for="tgl_akhir" class="col-md-1">Tgl Akhir</label>
               <div class="col-md-2">  
                 <div class="input-group date" id="datepicker2" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datepicker2" data-toggle="datetimepicker">
                      <span class="input-group-text btn-info">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input name="tgl_akhir" value="<?php echo $Akhir;?>" type="text" class="form-control form-control-sm" id=""  autocomplete="off" required>
                 </div>
			   </div>	
            </div> 
				 
			  <button class="btn btn-info" type="submit">Cari Data</button>
          </div>		  
		  <!-- /.card-body -->          
        </div>  
			
		<div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Detail Data  Benang Keluar Legacy</h3>
				 
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">Tgl</th>
                    <th valign="middle" style="text-align: center">Documentno</th>
                    <th valign="middle" style="text-align: center">No BON</th>
                    <th valign="middle" style="text-align: center">Jenis Benang</th>
                    <th valign="middle" style="text-align: center">Lot</th>
                    <th valign="middle" style="text-align: center">Satuan</th>
                    <th valign="middle" style="text-align: center">Qty</th>
                    <th valign="middle" style="text-align: center">Cones</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					// Query awal  
	$sql = mysqli_query($con,"select
	x.*,
	sum(y.berat) as berat,
	sum(y.cones) as cones,
	count(y.sn) as qty,
	ts.jenis_benang,
	ts.lot,
	ts.satuan
from
	tbl_stok_keluar x
inner join tbl_benang_keluar y on
	y.id_stok = x.id
inner join tbl_stoklegacy ts on
	ts.sn = y.sn
where
	x.tgl between '$Awal' and '$Akhir'
group by
	x.id
order by
	id asc");
	while($r=mysqli_fetch_array($sql)){				  
					  ?>
	  <tr>
	  <td style="text-align: center"><?php echo $no; ?></td>
	  <td style="text-align: center"><?php echo $r['tgl']; ?></td>
	  <td style="text-align: center"><?php echo $r['no_doc']; ?></td>
      <td style="text-align: center"><?php echo $r['bon']; ?></td>
      <td style="text-align: left"><?php echo $r['jenis_benang']; ?></td>
      <td style="text-align: center"><?php echo $r['lot']; ?></td>
      <td style="text-align: center"><?php echo $r['satuan']; ?></td>
      <td style="text-align: right"><?php echo round($r['qty']); ?></td>
      <td style="text-align: right"><?php echo round($r['cones']); ?></td>
      <td style="text-align: right"><?php echo number_format(round($r['berat'],2),2); ?></td>
      </tr>
	  				  
	<?php 
	 $no++; 
	$totQTY+=round($r['qty']);
	$totCON+=round($r['cones']);
	$totKG+=round($r['berat'],2);	
	
	} ?>
				  </tbody>
                <tfoot>
				<tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td colspan="3" style="text-align: right"><strong>Total</strong></td>
	    <td style="text-align: right"><strong><?php echo $totQTY; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo $totCON; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format($totKG,2); ?></strong></td>
	    </tr>	
					</tfoot>  
                </table>
              </div>
              <!-- /.card-body -->
            </div> 
	</form>		
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
<script type="text/javascript">
function checkAll(form1){
    for (var i=0;i<document.forms['form1'].elements.length;i++)
    {
        var e=document.forms['form1'].elements[i];
        if ((e.name !='allbox') && (e.type=='checkbox'))
        {
            e.checked=document.forms['form1'].allbox.checked;
			
        }
    }
}
</script>
