<?php
$Zone		= isset($_POST['zone']) ? $_POST['zone'] : '';
$Lokasi		= isset($_POST['lokasi']) ? $_POST['lokasi'] : '';
$Barcode	= substr($_POST['barcode'],-13);
?>

<?php
// Helper sederhana supaya error query tidak meledak dengan fatal TypeError
function sqlsrv_query_safe($conn, $sql)
{
    $stmt = sqlsrv_query($conn, $sql);
    if ($stmt === false) {
        // Log detail error ke error_log agar mudah dicek tanpa memutus eksekusi
        error_log('SQLSRV query failed: ' . print_r(sqlsrv_errors(), true) . ' | SQL: ' . $sql);
    }

    return $stmt;
}

$sqlCek1 = sqlsrv_query_safe($con, "SELECT COUNT(*) as jml FROM dbnow_gdb.tbl_stokfull WHERE status='ok' and zone='$Zone' AND lokasi='$Lokasi'");
$ck1     = ($sqlCek1 !== false) ? sqlsrv_fetch_array($sqlCek1, SQLSRV_FETCH_ASSOC) : ['jml' => 0];
$sqlCek2 = sqlsrv_query_safe($con, "SELECT COUNT(*) as jml FROM dbnow_gdb.tbl_stokfull WHERE status='belum cek' and zone='$Zone' AND lokasi='$Lokasi'");
$ck2     = ($sqlCek2 !== false) ? sqlsrv_fetch_array($sqlCek2, SQLSRV_FETCH_ASSOC) : ['jml' => 0];

if($_POST['cek']=="Cek" or $_POST['cari']=="Cari"){
	//if (strlen($_POST['barcode'])==13){
	//$sqlCek=sqlsrv_query($con,"SELECT COUNT(*) as jml FROM	dbnow_gdb.tbl_stokfull WHERE zone='$Zone' AND lokasi='$Lokasi' AND SN='$Barcode'");
		$sqlCek=sqlsrv_query_safe($con,"SELECT COUNT(*) as jml, zone, lokasi, kg, lot FROM dbnow_gdb.tbl_stokfull WHERE SN='$Barcode'");
		$ck=($sqlCek !== false) ? sqlsrv_fetch_array($sqlCek, SQLSRV_FETCH_ASSOC) : ['jml' => 0, 'zone' => '', 'lokasi' => '', 'kg' => 0, 'lot' => ''];
	if($Zone=="" and $Lokasi==""){
		echo"<script>alert('Zone atau Lokasi belum dipilih');</script>";
	}else if(is_numeric(trim($Barcode))== true and $Barcode!="" and strlen($Barcode)==13 and (substr($Barcode,0,2)=="15" or substr($Barcode,0,2)=="16" or
														substr($Barcode,0,2)=="17" or substr($Barcode,0,2)=="18" or
														substr($Barcode,0,2)=="19" or substr($Barcode,0,2)=="20" or
														substr($Barcode,0,2)=="21" or substr($Barcode,0,2)=="22" or 
														substr($Barcode,0,2)=="23") ){
		echo"<script>alert('SN Legacy');</script>";
	}else if($Barcode!="" and strlen($Barcode)==13 and ($ck['zone']!=$Zone or $ck['lokasi']!=$Lokasi)){	
		$LokasiAsl=$ck['zone']."-".$ck['lokasi'];
		$sqlDataE=sqlsrv_query(
            $con,
            "INSERT INTO dbnow_gdb.tbl_stokloss (lokasi, lokasi_asli, lot, KG, zone, SN, tgl_masuk, tgl_cek)
             VALUES ('$Lokasi', '$LokasiAsl', '".$ck['lot']."', '".$ck['kg']."', '$Zone', '$Barcode', '$tglMasuk', GETDATE())"
        );
	}else if($Barcode!="" and strlen($Barcode)==13 and (substr($Barcode,0,2)=="00" or substr($Barcode,0,3)=="000" or
														substr($Barcode,0,2)=="70" or substr($Barcode,0,2)=="80" or 
														substr($Barcode,0,2)=="90") and $ck['zone']==$Zone and $ck['lokasi']==$Lokasi ){
if($ck['jml']>0){		
	$sqlData=sqlsrv_query($con,"UPDATE dbnow_gdb.tbl_stokfull SET 
		  status='ok',
		  tgl_cek=GETDATE()
		  WHERE zone='$Zone' AND lokasi='$Lokasi' AND SN='$Barcode'");
	$sqlCek1=sqlsrv_query($con,"SELECT COUNT(*) as jml FROM dbnow_gdb.tbl_stokfull WHERE status='ok' and zone='$Zone' AND lokasi='$Lokasi'");
	$ck1=sqlsrv_fetch_array($sqlCek1, SQLSRV_FETCH_ASSOC);
	$sqlCek2=sqlsrv_query($con,"SELECT COUNT(*) as jml FROM dbnow_gdb.tbl_stokfull WHERE status='belum cek' and zone='$Zone' AND lokasi='$Lokasi'");
	$ck2=sqlsrv_fetch_array($sqlCek2, SQLSRV_FETCH_ASSOC);		
}else{
	$sqlDB21 = " SELECT WHSLOCATIONWAREHOUSEZONECODE, WAREHOUSELOCATIONCODE, CREATIONDATETIME,BASEPRIMARYQUANTITYUNIT FROM 
	BALANCE b WHERE (b.ITEMTYPECODE='GYR' OR b.ITEMTYPECODE='DYR') AND b.LOGICALWAREHOUSECODE='M011' AND b.ELEMENTSCODE='$Barcode' ";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	$rowdb21 = db2_fetch_assoc($stmt1);
	$lokasiAsli=trim($rowdb21['WHSLOCATIONWAREHOUSEZONECODE'])."-".trim($rowdb21['WAREHOUSELOCATIONCODE']);
	$tglMasuk=substr($rowdb21['CREATIONDATETIME'],0,10);
	$KGnow=round($rowdb21['BASEPRIMARYQUANTITYUNIT'],2);	
	if($lokasiAsli!="-"){
		echo"<script>alert('Data Roll ini dilokasi $lokasiAsli');</script>";
		if( $Zone!="" and $Lokasi!=""){				  
			$Where= " AND sf.zone='$Zone' AND sf.lokasi='$Lokasi' " ; 
		}else{
			$Where= " AND sf.zone='$Zone' AND sf.lokasi='$Lokasi' " ;
		}
		$sql=sqlsrv_query($con," SELECT sf.* FROM dbnow_gdb.tbl_stokfull sf
		LEFT JOIN dbnow_gdb.tbl_upload tu ON tu.id=sf.id=sf.id_upload  
		WHERE tu.status='Open' $Where ");
		$rowd=sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC);
		/*$sqlDataE=mysqli_query($con,"INSERT INTO tbl_stokloss SET 
		  lokasi='$Lokasi',
		  lokasi_asli='$lokasiAsli',
		  KG='$KGnow',
		  zone='$Zone',
		  SN='$Barcode',
		  tgl_masuk='$tglMasuk',
		  id_upload='$rowd[id_upload]',
		  tgl_cek=now()");*/
	}else{
		echo"<script>alert('SN tidak OK');</script>";
		if( $Zone!="" and $Lokasi!=""){				  
			$Where= " AND sf.zone='$Zone' AND sf.lokasi='$Lokasi' " ; 
		}else{
			$Where= " AND sf.zone='$Zone' AND sf.lokasi='$Lokasi' " ;
		}
		$sql=sqlsrv_query($con," SELECT sf.* FROM dbnow_gdb.tbl_stokfull sf
		LEFT JOIN dbnow_gdb.tbl_upload tu ON tu.id=sf.id_upload  
		WHERE tu.status='Open' $Where ");
		$rowd=sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC);
		$sqlDataE=sqlsrv_query(
            $con,
            "INSERT INTO dbnow_gdb.tbl_stokloss (lokasi, lokasi_asli, KG, zone, SN, tgl_masuk, id_upload, tgl_cek)
             VALUES ('$Lokasi', '$lokasiAsli', '$KGnow', '$Zone', '$Barcode', '$tglMasuk', '".$rowd['id_upload']."', GETDATE())"
        );
	}
	$sqlCek1=sqlsrv_query($con,"SELECT COUNT(*) as jml, sf.id_upload FROM dbnow_gdb.tbl_stokfull sf
	LEFT JOIN dbnow_gdb.tbl_upload tu ON tu.id=sf.id_upload  
	WHERE tu.status='Open' AND SN='$Barcode'");
	$ck1=sqlsrv_fetch_array($sqlCek1, SQLSRV_FETCH_ASSOC);		
	if($ck1['jml']>0){	
	/*$sqlDataE=mysqli_query($con,"INSERT INTO tbl_stokloss SET 
		  lokasi='$Lokasi',
		  lokasi_asli='$lokasiAsli',
		  KG='$KGnow',
		  zone='$Zone',
		  SN='$Barcode',
		  tgl_masuk='$tglMasuk',
		  id_upload='$ck1[id_upload]',
		  tgl_cek=now()");*/
	$sqlData1=sqlsrv_query($con,"UPDATE dbnow_gdb.tbl_stokfull SET 
		  status='ok',
		  zone='$Zone',
		  lokasi='$Lokasi',
		  tgl_cek=GETDATE()
		  WHERE id_upload='".$ck1['id_upload']."' AND SN='$Barcode'");	
	}
}
/* 
else{
	$sql04=sqlsrv_query($conn,"select
CAST(TM.dbo.stockmovement.[ID] AS VARCHAR(8000)) AS IDSTOCK,
CAST(TM.dbo.stockmovement.[PONo] AS VARCHAR(8000)) AS PONo,
CAST(TM.dbo.stockmovement.[DONo] AS VARCHAR(8000)) AS DONo,
CAST([PartnerName] AS VARCHAR(8000)) AS SUPPLIER,
CAST([ProductNumber] AS VARCHAR(8000)) AS ProductNumber,
CAST([Description] AS VARCHAR(8000)) AS Description,
CAST([BatchNo] AS VARCHAR(8000)) AS BatchNo,
CAST([UnitName] AS VARCHAR(8000)) AS UnitName,
sum(TM.dbo.stockmovementdetails.weight) as berat,
count(TM.dbo.stockmovementdetails.weight) as qty,
CONVERT(VARCHAR(11),TM.dbo.ProductProp.[ExpiryDate],106)  AS TglProd

from (TM.dbo.StockMovement  
LEFT join TM.dbo.stockmovementdetails on TM.dbo.StockMovement.ID=TM.dbo.stockmovementdetails.StockmovementID
LEFT Join  TM.dbo.Partners on TM.dbo.StockMovement.FromToID= TM.dbo.Partners.ID)
LEFT join TM.dbo.ProductMaster on TM.dbo.ProductMaster.ID=TM.dbo.stockmovementdetails.ProductID
LEFT join TM.dbo.ProductProp on TM.dbo.ProductProp.ID=dbo.stockmovementdetails.ProductPropID
LEFT join TM.dbo.UnitDescription on TM.dbo.UnitDescription.ID=dbo.stockmovementdetails.UnitID
where TM.dbo.stockmovementdetails.SN='$Barcode' and
	TM.dbo.StockMovement.transactionstatus='0' and 
	TM.dbo.StockMovement.transactiontype='1' and NOT FromToID=1971 and
	(TM.dbo.StockMovement.WID='11' or TM.dbo.StockMovement.WID='16' or TM.dbo.StockMovement.WID='75') and NOT FromToID=2014 and TM.dbo.stockmovementdetails.Quantity > 0 group by 
	TM.dbo.StockMovement.ID,TM.dbo.StockMovement.PONo,
	TM.dbo.UnitDescription.UnitName,TM.dbo.ProductMaster.ProductNumber,TM.dbo.StockMovement.DONo,
	TM.dbo.Partners.PartnerName,TM.dbo.ProductProp.BatchNo,TM.dbo.ProductMaster.Description,TM.dbo.ProductProp.ExpiryDate 
 ", array(), array( "Scrollable" => 'static' )) or die("gagal");
$r04=sqlsrv_fetch_array($sql04);
$ida=$r04['IDSTOCK'];
$sql304=sqlsrv_query($conn,"select top 1 CAST(TM.dbo.stockmovement.[Note] AS VARCHAR(8000)) AS Note,
CONVERT(VARCHAR(11),TM.dbo.stockmovement.Dated,21) AS tglmasuk
 	from dbo.stockmovement where ID='$ida'");
	 $as2=sqlsrv_fetch_array($sql304);
$name =$as2['Note'];
$posisi= strpos($name, 'BLOCK');
if($posisi == 0){ $posisi= strpos($name, 'BLOK'); }
$potong=substr($name,0,$posisi);
$potong2=substr($potong,-9);
$tp=substr($potong2,0,4);
$tp1=trim($tp);
block
if($posisi != 0){
$posisib=strpos($name, 'BLOCK');
$potongb=substr($name,$posisib );
$posisibc=strpos($potongb, 'ACTUAL');
$potongbct=substr($potongb,$posisibc );
$n1=strlen($potongb);
$n2=strlen($potongbct);
$posisiptg=$n1-$n2-5;
$potong2b=substr($potongb,5,$posisiptg);
$tp1b=trim($potong2b);
}else{$tp1b="";}
	$lokasiAsli=$tp1b;
	$tglMasuk=substr($as2['tglmasuk'],0,10);
	$KGnow=round($r04['berat'],2);
    $sqlCek0=sqlsrv_query($con,"SELECT * FROM dbnow_gdb.tbl_stokfull WHERE SN='$Barcode'");
	$rck=sqlsrv_fetch_array($sqlCek0, SQLSRV_FETCH_ASSOC);
	$LokasiAsl=$rck['zone']."-".$rck['lokasi'];	
	if($LokasiAsl!="-"){
		echo"<script>alert('Data Roll ini dilokasi $LokasiAsl');</script>";		
	$sqlData=sqlsrv_query($con,"UPDATE dbnow_gdb.tbl_stokfull SET 
		  status='ok',
		  zonex='$Zone',
		  lokasix='$Lokasi',
		  tgl_cek=GETDATE()
		  WHERE SN='$Barcode'");
	}else{
		echo"<script>alert('SN tidak OK');</script>";
	}
	
	$sqlDataE=sqlsrv_query(
        $con,
        "INSERT INTO dbnow_gdb.tbl_stokloss (lokasi, lokasi_asli, KG, zone, SN, tgl_masuk, tgl_cek)
         VALUES ('$Lokasi', '$LokasiAsl', '$KGnow', '$Zone', '$Barcode', '$tglMasuk', GETDATE())"
    );	
		}

*/
	
	} else if($Barcode==""){
		//barcode masih kosong
	}
	else{
		echo"<script>alert('SN tidak ditemukan Atau SN Legacy');</script>";
		$sqlDataE=sqlsrv_query(
            $con,
            "INSERT INTO dbnow_gdb.tbl_stokloss (lokasi, lokasi_asli, KG, zone, SN, tgl_masuk, tgl_cek)
             VALUES ('$Lokasi', '$LokasiAsl', '$KGnow', '$Zone', '$Barcode', '$tglMasuk', GETDATE())"
        );
	}

}
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Filter Data</h3>

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
               	<label for="zone" class="col-md-1">Zone</label> 
			   	<div class="input-group input-group-sm">           
					<select class="form-control select2bs4" style="width: 80%;" name="zone">
					<option value="">Pilih</option>	 
					<?php $sqlZ=sqlsrv_query($con," SELECT * FROM dbnow_gdb.tbl_zone ORDER BY nama ASC"); 
					  while($rZ=sqlsrv_fetch_array($sqlZ, SQLSRV_FETCH_ASSOC)){
					?>
					<option value="<?php echo $rZ['nama'];?>" <?php if($rZ['nama']==$Zone){ echo "SELECTED"; }?>><?php echo $rZ['nama'];?></option>
					<?php  } ?>
					</select>			   
					<span class="input-group-append">
						<button type="button" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#DataZone"><i class="fa fa-plus"></i> </button>
					</span>
				</div>	 
			</div>
			<div class="form-group row">
                <label for="lokasi" class="col-md-1">Location</label>
				<div class="input-group input-group-sm">
					<select class="form-control select2bs4" style="width: 80%;" name="lokasi">
                    <option value="">Pilih</option>	 
					<?php $sqlL=sqlsrv_query($con," SELECT * FROM dbnow_gdb.tbl_lokasi WHERE zone='$Zone' ORDER BY nama ASC"); 
					  while($rL=sqlsrv_fetch_array($sqlL, SQLSRV_FETCH_ASSOC)){
					 ?>
                    <option value="<?php echo $rL['nama'];?>" <?php if($rL['nama']==$Lokasi){ echo "SELECTED"; }?>><?php echo $rL['nama'];?></option>
                    <?php  } ?>
                  	</select>	
				  	<span class="input-group-append">
                   	  	<button type="button" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#DataLokasi"><i class="fa fa-plus"></i> </button>
               		</span>
                </div>	 
            </div> 
			<button class="btn btn-info" type="submit" value="Cari" name="cari">Cari Data</button>
          </div>
		  
		  <!-- /.card-body -->
          
        </div> 
		<!--	</form>
		<form role="form" method="post" enctype="multipart/form-data" name="form2">-->  
		<div class="card card-default">
         
          <!-- /.card-header -->
          <div class="card-body">
             <div class="form-group row">
               <label for="barcode" class="col-md-1">Barcode</label>               
               <input type="text" class="form-control"  name="barcode" placeholder="SN / Elements" id="barcode" on autofocus>			    
            </div>	
			  <button class="btn btn-primary" type="submit" name="cek" value="Cek">Check</button>
			  
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
				 <strong>Stok OK Sesuai Tempat</strong> <small class='badge badge-success'> <?php echo $ck1['jml'];?> roll </small><br>
				 <strong>Stok belum Cek</strong> <small class='badge badge-danger'> <?php echo $ck2['jml'];?> roll </small> 
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size:13px;">
                  <thead>
                  <tr>
                    <th style="text-align: center">SN</th>
                    <th style="text-align: center">Kg</th>
                    <th style="text-align: center">Status</th>
                    <th style="text-align: center">Lokasi</th>
                    <th style="text-align: center">NOW</th>
                    <th style="text-align: center">Lot</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	if( $Zone!="" and $Lokasi!=""){				  
	$Where= " WHERE dbnow_gdb.tbl_stokfull.zone='$Zone' AND dbnow_gdb.tbl_stokfull.lokasi='$Lokasi' " ;
	}else{
		$Where= " WHERE dbnow_gdb.tbl_stokfull.zone='$Zone' AND dbnow_gdb.tbl_stokfull.lokasi='$Lokasi' " ;
	}
	if($Shift!=""){
		$Shft=" AND a.shft='$Shift' ";
	}else{
		$Shft=" ";
	}				  
		$sql=sqlsrv_query($con," SELECT * FROM dbnow_gdb.tbl_stokfull $Where ");
   $no=1;   
   $c=0;
    while($rowd=sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){
$sqlDB22 = " SELECT WHSLOCATIONWAREHOUSEZONECODE, WAREHOUSELOCATIONCODE, LOTCODE FROM 
	BALANCE b WHERE (b.ITEMTYPECODE='GYR' or b.ITEMTYPECODE='DYR') AND b.ELEMENTSCODE='$rowd[SN]' ";
	$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
	$rowdb22 = db2_fetch_assoc($stmt2);
	$lokasiBalance=trim($rowdb22['WHSLOCATIONWAREHOUSEZONECODE'])."-".trim($rowdb22['WAREHOUSELOCATIONCODE']);
	   ?>
	  <tr>
      <td style="text-align: center"><?php echo $rowd['SN']; ?></td>
      <td style="text-align: right"><?php echo $rowd['KG']; ?></td>
      <td style="text-align: center"><small class='badge <?php if($rowd['status']=="ok"){ echo"badge-success";}else if($rowd['status']=="belum cek"){ echo"badge-danger";}?>'> <?php echo $rowd['status']; ?></small></td>
      <td style="text-align: center"><?php echo $rowd['zone']."-".$rowd['lokasi']; ?></td>
      <td style="text-align: center"><?php echo $lokasiBalance; ?></td>
      <td style="text-align: center"><?php echo $rowdb22['LOTCODE']; ?></td>
      </tr>				  
					  <?php 
	 
	 $no++;} ?>
				  </tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
        </div>  
<div class="card">
              <div class="card-header">
                <h3 class="card-title">ReCheck Stock </h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example3" class="table table-sm table-bordered table-striped" style="font-size:13px;">
                  <thead>
                  <tr>
                    <th style="text-align: center">SN</th>
                    <th style="text-align: center">KG</th>
                    <th style="text-align: center">Lokasi Scan</th>
                    <th style="text-align: center">Lokasi Upload</th>
                    <th style="text-align: center">Lokasi Balance</th>
                    <th style="text-align: center">Tgl Masuk</th>
                    <th style="text-align: center">Lot</th>
                    <th style="text-align: center">Keterangan</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	if( $Zone!="" and $Lokasi!=""){				  
	$Where= " WHERE dbnow_gdb.tbl_stokloss.zone='$Zone' AND dbnow_gdb.tbl_stokloss.lokasi='$Lokasi' " ;
	}else{
		$Where= " WHERE dbnow_gdb.tbl_stokloss.zone='$Zone' AND dbnow_gdb.tbl_stokloss.lokasi='$Lokasi' " ;
		}

			$sql1=sqlsrv_query(
                $con,
                "SELECT SN, KG, zone, lokasi, lokasi_asli, tgl_masuk, status, COUNT(SN) AS jmlscn
                 FROM dbnow_gdb.tbl_stokloss $Where
                 GROUP BY SN, KG, zone, lokasi, lokasi_asli, tgl_masuk, status"
            );
            if ($sql1 === false) {
                die(print_r(sqlsrv_errors(), true));
            }
   $no=1;   
   $c=0;
    while($rowd1=sqlsrv_fetch_array($sql1, SQLSRV_FETCH_ASSOC)){
	if(strlen($rowd1['SN'])!="13"){	
	$ketSN= "jumlah Karakter di SN tidak Sesuai";}else{$ketSN= "";}
	if($rowd1['jmlscn']>1){
	$ketSCN= "Jumlah Scan ".$rowd1['jmlscn']." kali";	
	}else{ $ketSCN= "";}	
		if($rowd1['tgl_masuk']=="0000-00-00" or $rowd1['tgl_masuk']==""){
				$tglmsk="";
		}else{
				$tglmsk=fmt_date($rowd1['tgl_masuk']); }
	$sqlDB22 = " SELECT WHSLOCATIONWAREHOUSEZONECODE, WAREHOUSELOCATIONCODE, LOTCODE FROM 
	BALANCE b WHERE (b.ITEMTYPECODE='GYR' or b.ITEMTYPECODE='DYR') AND b.ELEMENTSCODE='$rowd1[SN]' ";
	$stmt2   = db2_exec($conn1,$sqlDB22, array('cursor'=>DB2_SCROLLABLE));
	$rowdb22 = db2_fetch_assoc($stmt2);
	$lokasiBalance=trim($rowdb22['WHSLOCATIONWAREHOUSEZONECODE'])."-".trim($rowdb22['WAREHOUSELOCATIONCODE']);	
	   ?>
	  <tr>
      <td style="text-align: center"><?php echo $rowd1['SN']; ?></td>
      <td style="text-align: center"><?php echo $rowd1['KG']; ?></td>
      <td style="text-align: center"><?php echo $rowd1['zone']."-".$rowd1['lokasi']; ?></td>
      <td style="text-align: center"><?php echo $rowd1['lokasi_asli']; ?></td>
      <td style="text-align: center"><?php echo $lokasiBalance; ?></td>
      <td style="text-align: center"><?php echo $tglmsk; ?></td>
      <td style="text-align: center"><?php echo $rowdb22['LOTCODE']; ?></td>
      <td style="text-align: center"><small class='badge <?php if($rowd1['status']=="tidak ok"){ echo"badge-warning";}?>' ><i class='fas fa-exclamation-triangle text-default blink_me'></i> <?php echo $rowd1['status']; ?></small> <?php echo $ketSN.", ".$ketSCN; ?> </td>
      </tr>				  
					  <?php 
	 
	 $no++;} ?>
				  </tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
            </div>      
</div><!-- /.container-fluid -->
    <!-- /.content -->
<div class="modal fade" id="DataZone">
	<div class="modal-dialog">
		<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="" enctype="multipart/form-data">
			<div class="modal-content">            
				<div class="modal-header">
					<h4 class="modal-title">Input Data Zone</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
					<span aria-hidden="true">&times;</span>
				</div>
				<div class="modal-body">
					<input type="hidden" id="id" name="id">
					<div class="form-group">
					<label for="zone1" class="col-md-3 control-label">Zone</label>
					<div class="col-md-12">
					<input type="text" class="form-control" id="zone1" name="zone1" maxlength="3" required>
					<span class="help-block with-errors"></span>
					</div>
					</div>				  
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<input type="submit" value="Save changes" name="simpan_zone" class="btn btn-primary" >
					</div>	  
			</div>
		</form>				
	</div>
</div>

<div class="modal fade" id="DataLokasi">
    <div class="modal-dialog">
		<form class="form-horizontal" name="modal_popup" data-toggle="validator" method="post" action="" enctype="multipart/form-data">
			<div class="modal-content">            
				<div class="modal-header">
					<h4 class="modal-title">Input Data Lokasi</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
					<span aria-hidden="true">&times;</span>
				</div>
				<div class="modal-body">
					<input type="hidden" id="id" name="id">
					<div class="form-group">
						<label for="zone" class="col-md-3 control-label">Zone</label>
						<div class="col-md-12">                 
							<select class="form-control select2bs4" name="zone2" required>
								<option value="">Pilih</option>	 
								<?php $sqlZ=sqlsrv_query($con," SELECT * FROM dbnow_gdb.tbl_zone ORDER BY nama ASC"); 
								while($rZ=sqlsrv_fetch_array($sqlZ, SQLSRV_FETCH_ASSOC)){
								?>
								<option value="<?php echo $rZ['nama'];?>"><?php echo $rZ['nama'];?></option>
								<?php  } ?>
							</select>			  
							<span class="help-block with-errors"></span>
						</div>
					</div>
					<div class="form-group">
						<label for="lokasi1" class="col-md-3 control-label">Lokasi</label>
						<div class="col-md-12">
							<input type="text" class="form-control" id="lokasi1" name="lokasi1" maxlength="10" required>
							<span class="help-block with-errors"></span>
						</div>
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<input type="submit" value="Save changes" name="simpan_lokasi" class="btn btn-primary" >
				</div>	  
			</div>
		</form>				
    </div>
</div>
<?php 
if($_POST['simpan_zone']=="Save changes"){
	$zone1=sqlsrv_escape(strtoupper($_POST['zone1']));
	$sqlData1=sqlsrv_query($con,"INSERT INTO dbnow_gdb.tbl_zone (nama) VALUES ('$zone1')");
	if($sqlData1){	
		echo "<script>window.location='CheckStock';</script>";
	}
}
if($_POST['simpan_lokasi']=="Save changes"){
	$zone2=sqlsrv_escape(strtoupper($_POST['zone2']));
	$lokasi2=sqlsrv_escape(strtoupper($_POST['lokasi1']));
	$sqlData1=sqlsrv_query($con,"INSERT INTO dbnow_gdb.tbl_lokasi (nama, zone) VALUES ('$lokasi2','$zone2')");
	if($sqlData1){	
		echo "<script>window.location='CheckStock';</script>";
	}
}
?>
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
