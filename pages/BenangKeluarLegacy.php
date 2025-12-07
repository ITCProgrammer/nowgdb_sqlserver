<?php
$Tujuan		= isset($_POST['tujuan']) ? $_POST['tujuan'] : '';
$Tgl		= isset($_POST['tgl_trans']) ? $_POST['tgl_trans'] : '';
$Bon		= isset($_POST['bon']) ? $_POST['bon'] : '';
$Barcode	= isset($_POST['barcode']) ? substr($_POST['barcode'],-13) : '';
$Doc		= isset($_POST['no_doc']) ? $_POST['no_doc'] : '';	

include_once("../koneksi.php");

if(isset($_GET['doc']) && $_GET['doc']<>""){
    $Doc = $_GET['doc'];
}else{
    $Doc = isset($_POST['no_doc']) ? $_POST['no_doc'] : '';		
}
?>
<?php
function docno(){
	
		global $con;
		
		date_default_timezone_set("Asia/Jakarta");
		$format = date("ymd")."3";
		$sql=sqlsrv_query_safe($con,"SELECT TOP 1 no_doc FROM dbnow_gdb.tbl_stok_keluar WHERE SUBSTRING(no_doc,1,7) like '%".$format."%' ORDER BY no_doc DESC");
		$r = ($sql !== false) ? sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC) : null;
		if($r){
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

if(isset($_POST['simpan']) && $_POST['simpan']=="Simpan"){
// Skrip menyimpan data ke tabel transaksi utama
		$mySql	= "INSERT INTO dbnow_gdb.tbl_stok_keluar (tgl,no_doc,bon,tujuan)
						VALUES ('".sqlsrv_escape_str($Tgl)."',
						'".sqlsrv_escape_str($nou)."',
						'".sqlsrv_escape_str($Bon)."',
						'".sqlsrv_escape_str($Tujuan)."')";
		$qry	= sqlsrv_query_safe($con,$mySql);
	if($qry>0){
		echo "<script>window.location='BenangKeluarLegacy-$nou';</script>";
	}
}

$tmpSql 	= "SELECT COUNT(*) As qty, id,tujuan,tgl,bon,no_doc FROM dbnow_gdb.tbl_stok_keluar WHERE no_doc='".sqlsrv_escape_str($Doc)."' GROUP BY id,tujuan,tgl,bon,no_doc";
$tmpQry 	= sqlsrv_query_safe($con,$tmpSql);
$tmpData 	= ($tmpQry !== false) ? sqlsrv_fetch_array($tmpQry, SQLSRV_FETCH_ASSOC) : ['qty'=>0,'id'=>'','tujuan'=>'','tgl'=>'','bon'=>'','no_doc'=>''];
if (!$tmpData) {
	$tmpData = ['qty'=>0,'id'=>'','tujuan'=>'','tgl'=>'','bon'=>'','no_doc'=>''];
}
$tmpSql1 	= "SELECT id FROM dbnow_gdb.tbl_stok_keluar WHERE no_doc='".sqlsrv_escape_str($Doc)."'";
$tmpQry1 	= sqlsrv_query_safe($con,$tmpSql1);
$tmpData1 	= ($tmpQry1 !== false) ? sqlsrv_fetch_array($tmpQry1, SQLSRV_FETCH_ASSOC) : ['id'=>''];
$dataID 	= isset($tmpData1['id']) ? $tmpData1['id'] : '';

$sqlCek1	= sqlsrv_query_safe($con,"SELECT COUNT(*) as jml, SUM(berat) as tberat FROM dbnow_gdb.tbl_benang_keluar WHERE id_stok='".sqlsrv_escape_str($dataID)."' ");
$ck1		= ($sqlCek1 !== false) ? sqlsrv_fetch_array($sqlCek1, SQLSRV_FETCH_ASSOC) : ['jml'=>0,'tberat'=>0];
?>
<?php

if(isset($_POST['tambah']) && $_POST['tambah']=="Tambah"){
	
	if($Barcode==""){
	  echo"<script>alert('SN Masih Kosong');</script>";
  }else{
	  
	$sqlCek=sqlsrv_query_safe($con,"SELECT COUNT(*) as jml FROM dbnow_gdb.tbl_stoklegacy WHERE sn='".sqlsrv_escape_str($Barcode)."'");
	$ck=($sqlCek !== false) ? sqlsrv_fetch_array($sqlCek, SQLSRV_FETCH_ASSOC) : ['jml'=>0];
	if($ck['jml']>0){
		if($dataID<>""){
			$itSqlC="SELECT  COUNT(*) as jml FROM dbnow_gdb.tbl_benang_keluar
								where sn='".sqlsrv_escape_str($Barcode)."'";
			$itqryC		= sqlsrv_query_safe($con,$itSqlC);
			$ickC		= ($itqryC !== false) ? sqlsrv_fetch_array($itqryC, SQLSRV_FETCH_ASSOC) : ['jml'=>0];
			if($ickC['jml']>0){ }
			else{
			$itemSqlC="SELECT * FROM dbnow_gdb.tbl_stoklegacy
								where sn='".sqlsrv_escape_str($Barcode)."'";
			$iSqlC		= sqlsrv_query_safe($con,$itemSqlC);
			$ckC		= ($iSqlC !== false) ? sqlsrv_fetch_array($iSqlC, SQLSRV_FETCH_ASSOC) : null;
			if ($ckC) {
				$itemSql = "INSERT INTO dbnow_gdb.tbl_benang_keluar (id_stok, berat, cones, sn)
										VALUES ('".sqlsrv_escape_str($dataID)."',
										'".sqlsrv_escape_str($ckC['berat'])."',
										'".sqlsrv_escape_str($ckC['cones'])."',
										'".sqlsrv_escape_str($Barcode)."')";
				sqlsrv_query_safe($con,$itemSql,"benang_keluar insert");
				
				
				$itemSqlU="UPDATE dbnow_gdb.tbl_stoklegacy
									SET ada='0'
									where  sn='".sqlsrv_escape_str($Barcode)."'";
				sqlsrv_query_safe($con,$itemSqlU,"stoklegacy update ada=0");
			}
			}
			$sqlCek1	= sqlsrv_query_safe($con,"SELECT COUNT(*) as jml, SUM(berat) as tberat  FROM dbnow_gdb.tbl_benang_keluar WHERE id_stok='".sqlsrv_escape_str($dataID)."' ");
			$ck1		= ($sqlCek1 !== false) ? sqlsrv_fetch_array($sqlCek1, SQLSRV_FETCH_ASSOC) : ['jml'=>0,'tberat'=>0];
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
	$sql = sqlsrv_query_safe($con," SELECT b.*, b.id as ids FROM dbnow_gdb.tbl_stok_keluar a INNER JOIN dbnow_gdb.tbl_benang_keluar b ON a.id=b.id_stok WHERE a.no_doc='".sqlsrv_escape_str($Doc)."' ");		 
    while($sql !== false && ($r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC))){ 
	$sqlCek1=sqlsrv_query_safe($con,"SELECT * FROM dbnow_gdb.tbl_stoklegacy WHERE sn='".sqlsrv_escape_str($r['sn'])."'");
	$ck1=($sqlCek1 !== false) ? sqlsrv_fetch_array($sqlCek1, SQLSRV_FETCH_ASSOC) : ['berat'=>0,'satuan'=>'','blok'=>'','lot'=>''];			  
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
