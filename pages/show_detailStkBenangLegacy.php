<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
    $modal_id=$_GET['id'];
	
	
?>
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
			<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="UbahStokBenang" enctype="multipart/form-data">  
            <div class="modal-header">
              <h5 class="modal-title">Detail Data Stock Benang Untuk No Doc <?php echo $modal_id; ?></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
			<input type="hidden" value="<?php echo $modal_id; ?>" name="intdoc">
            <table id="lookup1" class="table table-sm table-bordered table-striped" style="font-size:13px;">
                  <thead>
                  <tr>
                    <th style="text-align: center">No</th>
                    <th style="text-align: center">SN</th>
                    <th style="text-align: center">Code</th>
                    <th style="text-align: center">Jenis Benang</th>
                    <th style="text-align: center">Lot</th>
                    <th style="text-align: center">Satuan</th>
                    <th style="text-align: center">Weight</th>
                    <th style="text-align: center">Qty</th>
                    <th style="text-align: center">Cones</th>
                    <th style="text-align: center">Lokasi</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php				  
   $no=1;   
   $c=0;
   $sql= sqlsrv_query_safe($con,"SELECT x.* FROM dbnow_gdb.tbl_stoklegacy x WHERE x.no_doc='".sqlsrv_escape_str($modal_id)."'");
   while($sql !== false && ($r=sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC))){		
	
?>
	  <tr>
	  <td style="text-align: center"><?php echo $no; ?></td>
	  <td style="text-align: left"><?php echo $r['sn']; ?></td>
	  <td style="text-align: left"><?php echo $r['code']; ?></td>
      <td style="text-align: left"><?php echo $r['jenis_benang'] ; ?></td>
      <td style="text-align: center"><?php echo $r['lot'];?></td>
      <td style="text-align: right"><?php echo $r['satuan'];?></td>
      <td style="text-align: right"><?php echo number_format(round($r['berat'],2),2);?></td>
      <td style="text-align: right"><?php echo round($r['qty'],0);?></td>
      <td style="text-align: right"><?php echo round($r['cones'],0);?></td>
      <td style="text-align: center"><?php echo $r['blok'];?></td>
      </tr>
	  				  
<?php	$no++;
		$tKGs += round($r['berat'],2);
		$tQTY += $r['qty'];
		$tCONES += $r['cones'];
	} ?>
				  </tbody>
       <tfoot>
		<tr>
		  <td style="text-align: left">&nbsp;</td>
		  <td style="text-align: left">&nbsp;</td>
		  <td style="text-align: left">&nbsp;</td>
	    <td style="text-align: left">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: right">&nbsp;</td>
	    <td style="text-align: right"><strong><?php echo number_format(round($tKGs,2),2); ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format($tQTY); ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format($tCONES); ?></strong></td>
	    <td style="text-align: center">&nbsp;</td>
	    </tr>   
	   </tfoot>           
                </table>
				
				
				</div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>			  	
            </div>
			</form>	
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
               
<script>
  $(function () {	 
	$('.select2sts').select2({
    placeholder: "Select a status",
    allowClear: true
});   
  });
</script>
<script>
  $(function () {
    $("#lookup1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "excel", "pdf"]
    }).buttons().container().appendTo('#lookup1_wrapper .col-md-6:eq(0)');	 
  
  });
</script>
