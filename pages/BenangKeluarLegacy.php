<?php
$Tujuan		= isset($_POST['tujuan']) ? $_POST['tujuan'] : '';
$Tgl		= isset($_POST['tgl_trans']) ? $_POST['tgl_trans'] : '';
$Bon		= isset($_POST['bon']) ? $_POST['bon'] : '';
$Barcode	= substr($_POST['barcode'],-13);
$Doc		= isset($_POST['no_doc']) ? $_POST['no_doc'] : '';	

if($_GET['doc']<>""){
$Doc		= isset($_GET['doc']) ? $_GET['doc'] : '';
}else{
$Doc		= isset($_POST['no_doc']) ? $_POST['no_doc'] : '';		
}
?>
<?php
function docno(){
	
		global $con;
		
		date_default_timezone_set("Asia/Jakarta");
		$format = date("ymd")."3";
		$sql=mysqli_query($con,"SELECT no_doc FROM tbl_stok_keluar WHERE substr(no_doc,1,7) like '%".$format."%'
		ORDER BY no_doc DESC LIMIT 1 ") or die ("Gagal");
		$d=mysqli_num_rows($sql);
		if($d>0){
			$r=mysqli_fetch_array($sql);
			$d=$r['no_doc'];
			$str=substr($d,7,3);
			$Urut = (int)$str;
		}else{
			$Urut = 0;
		}
		$Urut = $Urut + 1;
		$Nol="";
		$nilai=3-strlen($Urut);
		for ($i=1;$i<=$nilai;$i++){
			$Nol= $Nol."0";
		}
		$nipbr =$format.$Nol.$Urut;
		return $nipbr;
}
$nou=docno();

if($_POST['simpan']=="Simpan"){
// Skrip menyimpan data ke tabel transaksi utama
		$mySql	= "INSERT INTO tbl_stok_keluar SET
						tgl='".$Tgl."',
						no_doc='".$nou."',
						bon='".$Bon."',
						tujuan='".$Tujuan."'";
		$qry	= mysqli_query($con,$mySql) or die ("Gagal query 1 ");
	if($qry>0){
		echo "<script>window.location='BenangKeluarLegacy-$nou';</script>";
	}
}

$tmpSql 	= "SELECT COUNT(*) As qty, id,tujuan,tgl,bon,no_doc FROM tbl_stok_keluar WHERE no_doc='".$Doc."'";
$tmpQry 	= mysqli_query($con,$tmpSql) or die ("Gagal Query Tmp0");
$tmpData 	= mysqli_fetch_array($tmpQry);
$tmpSql1 	= "SELECT id FROM tbl_stok_keluar WHERE no_doc='".$Doc."'";
$tmpQry1 	= mysqli_query($con,$tmpSql1) or die ("Gagal Query Tmp1");
$tmpData1 	= mysqli_fetch_array($tmpQry1);
$dataID 	= $tmpData1['id'];

$sqlCek1	= mysqli_query($con,"SELECT COUNT(*) as jml, SUM(berat) as tberat FROM tbl_benang_keluar WHERE id_stok='$dataID' ");
$ck1		= mysqli_fetch_array($sqlCek1);
?>
<?php

if($_POST['tambah']=="Tambah"){
	
  if($Barcode==""){
	  echo"<script>alert('SN Masih Kosong');</script>";
  }else{
	  
	$sqlCek=mysqli_query($con,"SELECT COUNT(*) as jml FROM tbl_stoklegacy WHERE sn='$Barcode'");
	$ck=mysqli_fetch_array($sqlCek);
	if($ck['jml']>0){
		if($dataID<>""){
			$itSqlC="SELECT  COUNT(*) as jml FROM tbl_benang_keluar
								where sn='$Barcode'";
			$itqryC		= mysqli_query($con,$itSqlC);
			$ickC		= mysqli_fetch_array($itqryC);
			if($ickC['jml']>0){ }
			else{
			$itemSqlC="SELECT * FROM tbl_stoklegacy
								where sn='$Barcode'";
			$iSqlC		= mysqli_query($con,$itemSqlC);
			$ckC		= mysqli_fetch_array($iSqlC);
			$itemSql = "INSERT INTO tbl_benang_keluar SET
									id_stok='$dataID',
									berat='".$ckC['berat']."',
									cones='".$ckC['cones']."',
									sn='$Barcode'";
			mysqli_query($con,$itemSql) or die ("Gagal Query item");
			
			
			$itemSqlU="UPDATE tbl_stoklegacy
								SET ada='0'
								where  sn='$Barcode'";
			mysqli_query($con,$itemSqlU) or die ("Gagal Query UPdate");
			}
			$sqlCek1	= mysqli_query($con,"SELECT COUNT(*) as jml, SUM(berat) as tberat  FROM tbl_benang_keluar WHERE id_stok='$dataID' ");
			$ck1		= mysqli_fetch_array($sqlCek1);
		}
			
		//echo"<script>location.reload();</script>";
		
	}else{
		echo"<script>alert('SN tidak OK');</script>";
	}
	  
  }	
	
}
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Data Keluar</h3>

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
                <label for="bon" class="col-md-1">No Document</label>
				<div class="col-md-2"> 
				<input tpye="text" name ="no_doc" class="form-control" value="<?php if($Tujuan<>"" and $Doc== ""){echo $nou;}else{ echo $Doc;} ?>" onChange="window.location='BenangKeluarLegacy-'+this.value" <?php if($Doc<>""){?> readonly <?php } ?>>	
                </div>
				</div> 
             <div class="form-group row">
               <label for="tujuan" class="col-md-1">Tujuan</label> 
			   <div class="col-md-2"> 
               <select name="tujuan" class="form-control">
			   <option value="">Pilih</option>	   
			   <option value="Knitting" <?php if($Tujuan=="Knitting" or $tmpData['tujuan']=="Knitting"){ echo "SELECTED";} ?>>Knitting</option>
			   <option value="Kragh" <?php if($Tujuan=="Kragh" or $tmpData['tujuan']=="Kragh"){ echo "SELECTED";} ?>>Kragh</option>	   
			   <option value="RMP" <?php if($Tujuan=="RMP" or $tmpData['tujuan']=="RMP"){ echo "SELECTED";} ?>>RMP</option>
			   <option value="Yarn Dye" <?php if($Tujuan=="Yarn Dye" or $tmpData['tujuan']=="Yarn Dye"){ echo "SELECTED";} ?>>Yarn Dye</option>
			   <option value="Jual" <?php if($Tujuan=="Jual" or $tmpData['tujuan']=="Jual"){ echo "SELECTED";} ?>>Jual</option>  	   
			   </select>  	
			   </div>	   
            </div>
				 <div class="form-group row">
                    <label for="bon" class="col-md-1">No BON</label>
				<div class="col-md-2">	 
				<input tpye="text" name ="bon" class="form-control" value="<?php if ($tmpData['qty']>0){echo $tmpData['bon'];}else{ echo $Bon; }  ?>">	
                  </div>
				</div>	 
			  <div class="form-group row">
               <label for="tgl_trans" class="col-md-1">Tgl Transaksi</label>
               <div class="col-md-2">  
                 <div class="input-group date" id="datepicker1" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datepicker1" data-toggle="datetimepicker">
                      <span class="input-group-text btn-info">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input name="tgl_trans" value="<?php if ($tmpData['qty']>0){echo $tmpData['tgl'];}else{ echo $Tgl; } ?>" type="text" class="form-control" id=""  autocomplete="off" >
                 </div>
			   </div>	
            </div>
			  <button class="btn btn-info" type="submit" value="Simpan" name="simpan" <?php if($tmpData['qty']>0 or $Tujuan <> ""){ echo "disabled"; } ?>>Simpan</button>
			  <button class="btn btn-primary float-right" type="button" value="Baru" name="baru" onclick="window.location.href='BenangKeluarLegacy'">Baru</button>
          </div>
		  
		  <!-- /.card-body -->
          
        </div> 
			</form>
		<form role="form" method="post" enctype="multipart/form-data" name="form2"> 
		<div class="card card-default">
         
          <!-- /.card-header -->
          <div class="card-body">
             <div class="form-group row">
               <label for="barcode" class="col-md-1">Barcode</label>               
               <input type="text" class="form-control"  name="barcode" placeholder="SN / Elements" id="barcode" on autofocus <?php if($Tujuan=="" and $tmpData['tujuan']==""){ echo "disabled"; } ?>>			    
            </div>	
			  <button class="btn btn-primary <?php if($Tujuan=="" and $tmpData['tujuan']==""){ echo "disabled"; } ?>"  name="tambah" value="Tambah" <?php if($Tujuan=="" and $tmpData['tujuan']==""){ echo "disabled"; } ?>>Tambah</button>
			  
          </div>
		  
		  <!-- /.card-body -->
          
        </div> 
			</form>
		  <div class="card">
              <div class="card-header">
                <h3 class="card-title">Stock</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
				 <strong>Jumlah Qty</strong> <small class='badge badge-success'> <?php echo $ck1['jml']; ?> dus</small><br>
				 <strong>Total Berat</strong> <small class='badge badge-danger'> <?php echo $ck1['tberat']; ?> Kgs</small><br> 
				<table id="example1" class="table table-sm table-bordered table-striped" style="font-size:13px;">
                  <thead>
                  <tr>
                    <th style="text-align: center">No</th>
                    <th style="text-align: center">SN</th>
                    <th style="text-align: center">Kg</th>
                    <th style="text-align: center">Satuan</th>
                    <th style="text-align: center">Lokasi</th>
                    <th style="text-align: center">Lot</th>
                    <th style="text-align: center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	$no=1;				  
	$sql = mysqli_query($con," SELECT *, b.id as ids FROM tbl_stok_keluar a INNER JOIN tbl_benang_keluar b ON a.id=b.id_stok WHERE a.no_doc='$Doc' ");		 
    while($r = mysqli_fetch_array($sql)){ 
	$sqlCek1=mysqli_query($con,"SELECT * FROM tbl_stoklegacy WHERE sn='".$r['sn']."'");
	$ck1=mysqli_fetch_array($sqlCek1);			  
					  ?>
	  <tr>
	  <td style="text-align: center"><?php echo $no; ?></td>
      <td style="text-align: center"><?php echo $r['sn']; ?></td>
      <td style="text-align: right"><?php echo $ck1['berat']; ?></td>
      <td style="text-align: center"><?php echo $ck1['satuan']; ?></td>
      <td style="text-align: center"><?php echo $ck1['blok']; ?></td>
      <td style="text-align: center"><?php echo $ck1['lot']; ?></td>
      <td style="text-align: center"><a href="#" class="btn btn-sm btn-danger" onclick="confirm_delete('DelStokKeluar-<?php echo $r['ids']; ?>');"><i class="fa fa-trash"></i> </a></td>
      </tr>				  
	  <?php 
		$no++; }
	  ?>
				  </tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
        </div>  
     
</div><!-- /.container-fluid -->
    <!-- /.content -->
<!-- Modal Popup untuk delete-->
            <div class="modal fade" id="delAbsensi" tabindex="-1">
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
                $('#delAbsensi').modal('show', {
                  backdrop: 'static'
                });
                document.getElementById('delete_link').setAttribute('href', delete_url);
              }
</script>
