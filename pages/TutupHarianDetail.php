<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
?>

<!-- Main content -->
      <div class="container-fluid">
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
                  * Tutup Transaksi Membutuhkan Waktu, Harap Tunggu...<br>
** Jangan di Tutup Sebelum Selesai. 
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
                    <th valign="middle" style="text-align: center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
<?php	 
$no=1;   
$c=0;				  
$sql = mysqli_query($con," SELECT tgl_tutup,tipe,sum(qty) as qty,sum(cones) as cones,sum(weight) as kg,DATE_FORMAT(now(),'%Y-%m-%d') as tgl FROM tblopname_detail GROUP BY tgl_tutup,tipe ORDER BY tgl_tutup DESC LIMIT 30");		  
    while($r = mysqli_fetch_array($sql)){
		
?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><a href="DetailOpnameDetail-<?php echo $r['tgl_tutup'];?>-<?php echo $r['tipe'];?>" class="btn btn-info btn-xs" target="_blank"> <i class="fa fa-link"></i> Lihat Data</a></td>
	  <td style="text-align: center"><?php echo $r['tgl_tutup'];?></td>
	  <td style="text-align: center"><?php echo $r['tipe'];?></td>
      <td style="text-align: center"><?php echo number_format($r['qty']);?></td>
      <td style="text-align: center"><?php echo number_format($r['cones']);?></td>
      <td style="text-align: right"><?php echo number_format($r['kg'],2);?></td>
      <td style="text-align: center"><a href="#" class="btn btn-xs btn-danger <?php if($r['tgl']==$r['tgl_tutup']){ }else{echo"disabled";} ?>" onclick="confirm_delete('DelOpnameDetail-<?php echo $r['tgl_tutup']; ?>');" ><small class="fas fa-trash"> </small> Hapus</a></td>
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
	</form>		
      </div><!-- /.container-fluid -->
    <!-- /.content -->
<div class="modal fade" id="delOpname" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content" style="margin-top:100px;">
                  <div class="modal-header">
					<h4 class="modal-title">INFOMATION</h4>  
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    
                  </div>
					<div class="modal-body">
						<h5 class="modal-title" style="text-align:center;">Are you sure to delete this information ?</h5>
					</div>	
                  <div class="modal-footer justify-content-between">
                    <a href="#" class="btn btn-danger" id="delete_link">Delete</a>
                    <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                  </div>
                </div>
              </div>
            </div>
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
              function confirm_delete(delete_url) {
                $('#delOpname').modal('show', {
                  backdrop: 'static'
                });
                document.getElementById('delete_link').setAttribute('href', delete_url);
              }
</script>
<?php	
if(isset($_POST['submit'])){

$cektgl=mysqli_query($con,"SELECT DATE_FORMAT(NOW(),'%Y-%m-%d') as tgl,COUNT(tgl_tutup) as ck ,DATE_FORMAT(NOW(),'%H') as jam,DATE_FORMAT(NOW(),'%H:%i') as jam1 FROM tblopname_detail WHERE tgl_tutup='".$Awal."' LIMIT 1");
$dcek=mysqli_fetch_array($cektgl);
$t1=strtotime($Awal);
$t2=strtotime($dcek['tgl']);
$selh=round(abs($t2-$t1)/(60*60*45));

if($dcek['ck']>0){
	//echo "<script>";
		//echo "alert('Stok Tgl ".$Awal." Ini Sudah Pernah ditutup')";
		//echo "</script>";
		echo "<script>
  	$(function() {
    toastr.error('Stok Tgl ".$Awal." Ini Sudah Pernah ditutup')
  });
  
</script>";
		// Refresh form
		// echo "<meta http-equiv='refresh' content='0; url=index1.php?p=data-stok-kj'>";
	}else if($Awal > $dcek['tgl']){
		//echo "<script>";
		//echo "alert('Tanggal Lebih dari $selh hari')";
		//echo "</script>";
		echo "<script>
  	$(function() {
    toastr.error('Tanggal Lebih dari $selh hari')
  });
  
</script>";
		// Refresh form
		// echo "<meta http-equiv='refresh' content='0; url=index1.php?p=data-stok-kj'>";
	}else if($Awal < $dcek['tgl']){
		//echo "<script>";
		//echo "alert('Tanggal Kurang dari $selh hari')";
		//echo "</script>";
		echo "<script>
  		$(function() {
    		toastr.error('Tanggal Kurang dari $selh hari')
  		});  
  		</script>";
		// Refresh form
		// echo "<meta http-equiv='refresh' content='0; url=index1.php?p=data-stok-kj'>";
	}else if($dcek['jam'] < 6){
		//echo "<script>";
		//echo "alert('Tidak Bisa Tutup Sebelum jam 6 Pagi Sampai jam 12 Malam, Sekarang Masih Jam ".$dcek['jam1']."')";
		//echo "</script>";
		echo "<script>
  		$(function() {
    		toastr.error('Tidak Bisa Tutup Sebelum jam 6 Pagi Sampai jam 12 Malam, Sekarang Masih Jam ".$dcek['jam1']."')
  		});  
  		</script>";
		// Refresh form
		// echo "<meta http-equiv='refresh' content='0; url=index1.php?p=data-stok-kj'>";
			}
			else{	
	
	$sqlDB21 = " SELECT 
c.SUPPLIERCODE, 
c.LOTCREATIONORDERNUMBER,
e.LEGALNAME1, 
b.LOTCODE,
b.ITEMTYPECODE,
b.DECOSUBCODE01,
b.DECOSUBCODE02,
b.DECOSUBCODE03,
b.DECOSUBCODE04,
b.DECOSUBCODE05,
b.DECOSUBCODE06,
b.DECOSUBCODE07,
b.DECOSUBCODE08,
b.ELEMENTSCODE,
a.VALUESTRING AS TGLTERIMA,
SUM(b.BASEPRIMARYQUANTITYUNIT) AS KGS,
SUM(b.BASESECONDARYQUANTITYUNIT) AS CONES,
COUNT(b.ELEMENTSCODE) AS QTY,
b.QUALITYLEVELCODE, 
b.WHSLOCATIONWAREHOUSEZONECODE,
b.WAREHOUSELOCATIONCODE FROM BALANCE b 
LEFT OUTER JOIN LOT c ON b.LOTCODE = c.CODE AND c.COMPANYCODE = '100' AND 
b.ITEMTYPECODE = c.ITEMTYPECODE AND
b.DECOSUBCODE01= c.DECOSUBCODE01 AND
b.DECOSUBCODE02= c.DECOSUBCODE02 AND
b.DECOSUBCODE03= c.DECOSUBCODE03 AND
b.DECOSUBCODE04= c.DECOSUBCODE04 AND
b.DECOSUBCODE05= c.DECOSUBCODE05 AND
b.DECOSUBCODE06= c.DECOSUBCODE06 AND
b.DECOSUBCODE07= c.DECOSUBCODE07 AND
b.DECOSUBCODE08= c.DECOSUBCODE08
LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID =c.ABSUNIQUEID AND a.NAMENAME = 'ReceivedDate'
LEFT OUTER JOIN CUSTOMERSUPPLIERDATA d ON c.SUPPLIERCODE =d.CODE AND d.COMPANYCODE = '100' AND d.TYPE = '2'
LEFT OUTER JOIN BUSINESSPARTNER e ON d.BUSINESSPARTNERNUMBERID =e.NUMBERID 
WHERE (b.ITEMTYPECODE ='GYR' OR b.ITEMTYPECODE ='DYR') AND
b.LOGICALWAREHOUSECODE = 'M011' AND 
(
b.WHSLOCATIONWAREHOUSEZONECODE='GB0' OR
b.WHSLOCATIONWAREHOUSEZONECODE='GB1' OR  
b.WHSLOCATIONWAREHOUSEZONECODE='GB2' OR
b.WHSLOCATIONWAREHOUSEZONECODE='GB5' OR
b.WHSLOCATIONWAREHOUSEZONECODE='GB6' OR 
b.WHSLOCATIONWAREHOUSEZONECODE='GP1' OR 
b.WHSLOCATIONWAREHOUSEZONECODE='GR2' OR 
b.WHSLOCATIONWAREHOUSEZONECODE='GR3' OR 
b.WHSLOCATIONWAREHOUSEZONECODE='GY1' OR
b.WHSLOCATIONWAREHOUSEZONECODE='LT')
GROUP BY 
c.SUPPLIERCODE,
c.LOTCREATIONORDERNUMBER,
e.LEGALNAME1, 
b.QUALITYLEVELCODE,
b.LOTCODE, b.ITEMTYPECODE,
b.DECOSUBCODE01, b.DECOSUBCODE02,
b.DECOSUBCODE03, b.DECOSUBCODE04,
b.DECOSUBCODE05, b.DECOSUBCODE06,
b.DECOSUBCODE07, b.DECOSUBCODE08,
b.WHSLOCATIONWAREHOUSEZONECODE,
b.WAREHOUSELOCATIONCODE,
b.ELEMENTSCODE,
a.VALUESTRING ";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){
	$kd= trim($rowdb21['DECOSUBCODE01'])."-".trim($rowdb21['DECOSUBCODE02'])."-".trim($rowdb21['DECOSUBCODE03'])."-".trim($rowdb21['DECOSUBCODE04'])."-".trim($rowdb21['DECOSUBCODE05'])."-".trim($rowdb21['DECOSUBCODE06'])."-".trim($rowdb21['DECOSUBCODE07'])."-".trim($rowdb21['DECOSUBCODE08']);
	if($rowdb21['QUALITYLEVELCODE']=="1"){
		$grd="A";
	}else if($rowdb21['QUALITYLEVELCODE']=="2"){
		$grd="B";
	}else if($rowdb21['QUALITYLEVELCODE']=="3"){
		$grd="C";
	}else if($rowdb21['QUALITYLEVELCODE']=="4"){
		$grd="D";	
	}else{
		$grd="";
	}	
		
	$sqlDB23 = " SELECT FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION 
	   FROM DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER WHERE
       (FULLITEMKEYDECODER.ITEMTYPECODE='DYR' OR FULLITEMKEYDECODER.ITEMTYPECODE='GYR') AND
	   FULLITEMKEYDECODER.SUBCODE01='".trim($rowdb21['DECOSUBCODE01'])."' AND
       FULLITEMKEYDECODER.SUBCODE02='".trim($rowdb21['DECOSUBCODE02'])."' AND
       FULLITEMKEYDECODER.SUBCODE03='".trim($rowdb21['DECOSUBCODE03'])."' AND
	   FULLITEMKEYDECODER.SUBCODE04='".trim($rowdb21['DECOSUBCODE04'])."' AND
       FULLITEMKEYDECODER.SUBCODE05='".trim($rowdb21['DECOSUBCODE05'])."' AND
	   FULLITEMKEYDECODER.SUBCODE06='".trim($rowdb21['DECOSUBCODE06'])."' AND
	   FULLITEMKEYDECODER.SUBCODE07='".trim($rowdb21['DECOSUBCODE07'])."' AND
	   FULLITEMKEYDECODER.SUBCODE08='".trim($rowdb21['DECOSUBCODE08'])."'  ";
	   $stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
	   $rowdb23 = db2_fetch_assoc($stmt3);
	$simpan=mysqli_query($con,"INSERT INTO `tblopname_detail` SET 
					tipe = '".$rowdb21['ITEMTYPECODE']."',
					kd_benang = '".$kd."',
					jenis_benang = '".str_replace("'","''",$rowdb23['SUMMARIZEDDESCRIPTION'])."',
					lot = '".$rowdb21['LOTCODE']."',
					po = '".$rowdb21['LOTCREATIONORDERNUMBER']."',
					suppliercode = '".$rowdb21['SUPPLIERCODE']."',
					suppliername = '".$rowdb21['LEGALNAME1']."',
					qty = '".$rowdb21['QTY']."',
					weight = '".round($rowdb21['KGS'],2)."', 
					cones = '".$rowdb21['CONES']."',
					grd = '$grd',
					zone = '".$rowdb21['WHSLOCATIONWAREHOUSEZONECODE']."',
					lokasi = '".$rowdb21['WAREHOUSELOCATIONCODE']."',
					sn = '".$rowdb21['ELEMENTSCODE']."',
					terima = '".$rowdb21['TGLTERIMA']."',
					tgl_tutup = '".$Awal."',
					tgl_buat =now()
					
					") or die("GAGAL SIMPAN");	
	
	}
	if($simpan){		
		echo "<script>";
		echo "alert('Stok Tgl ".$Awal." Sudah ditutup')";
		echo "</script>";
        echo "<meta http-equiv='refresh' content='0; url=TutupHarianDetail'>";
		
		}			
 }
}
?>