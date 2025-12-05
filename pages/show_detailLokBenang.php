<?php
ini_set("error_reporting", 1);
session_start();
include("../koneksi.php");
    $modal_id=$_GET['id'];
	$pos=strpos($modal_id,"#");
	$KodeBng=substr($modal_id,0,$pos);
	$IntDoc=substr($modal_id,$pos+1,300);
	
?>
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
			<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="HapusLokasiBenang" enctype="multipart/form-data">  
            <div class="modal-header">
              <h5 class="modal-title">Detail Data Stok Benang Untuk <?php echo $IntDoc; ?></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
			<input type="hidden" value="<?php echo $IntDoc; ?>" name="intdoc">	
            <table id="lookup11" class="table table-sm table-bordered table-striped" style="font-size:13px;">
                  <thead>
                  <tr>
                    <th style="text-align: center">#</th>
                    <th style="text-align: center">Lokasi</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php				  
   $no=1;   
   $c=0;
	$sqllok=mysqli_query($con, "SELECT id,lokasi FROM tblambillokasi WHERE no_doc='$IntDoc'");					  
    while($rLok=mysqli_fetch_array($sqllok)){	
?>
	  <tr>
	  <td style="text-align: center"><input type=checkbox name="cek1[<?php echo $no; ?>]" value="<?php echo $no; ?>"></td>
	  <td style="text-align: center"><?php echo $rLok['lokasi'];?></td>
      </tr>
	  				  
<?php	$no++;
		
	} ?>
				  </tbody>
       <tfoot>
		<tr>
		  <td style="text-align: left">&nbsp;</td>
		  <td style="text-align: center">&nbsp;</td>
	    </tr>   
	   </tfoot>           
                </table>
				
				
				</div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-danger btn-sm" name="delete">Delete</button>			  	
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
