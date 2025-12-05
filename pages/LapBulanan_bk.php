<?php
$Thn2			= isset($_POST['thn']) ? $_POST['thn'] : '';
$Bln2			= isset($_POST['bln']) ? $_POST['bln'] : '';
$Dept			= isset($_POST['dept']) ? $_POST['dept'] : '';
$Bulan			= $Thn2."-".$Bln2;
if($Thn2!="" and $Bln2!=""){
$d				= cal_days_in_month(CAL_GREGORIAN,$Bln2,$Thn2);
$Lalu 		= $Bln2-1;	
	if($Lalu=="0"){
	if(strlen($Lalu)==1){$bl0="0".$Lalu;}else{$bl0=$Lalu;}	
	$BlnLalu="12";	
	$Thn=$Thn2-1;
	$TBln=$Thn."-".$BlnLalu;	
	}else{
	if(strlen($Lalu)==1){$bl0="0".$Lalu;}else{$bl0=$Lalu;}	
	$BlnLalu=$bl0;
	$TBln=$Thn2."-".$BlnLalu;	
	}	
	
}
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Data Gudang Benang</h3>

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
			<div class="col-sm-1">
                	<select name="thn" class="form-control form-control-sm  select2"> 
                	<option value="">Pilih Tahun</option>
        <?php
                $thn_skr = date('Y');
                for ($x = $thn_skr; $x >= 2022; $x--) {
                ?>
        <option value="<?php echo $x ?>" <?php if($Thn2!=""){if($Thn2==$x){echo "SELECTED";}}else{if($x==$thn_skr){echo "SELECTED";}} ?>><?php echo $x ?></option>
        <?php
                }
   ?>
                	</select>
                	</div>
		       	<div class="col-sm-2">
                	<select name="bln" class="form-control form-control-sm  select2"> 
                	<option value="">Pilih Bulan</option>
					<option value="01" <?php if($Bln2=="01"){ echo "SELECTED";}?>>Januari</option>
					<option value="02" <?php if($Bln2=="02"){ echo "SELECTED";}?>>Febuari</option>
					<option value="03" <?php if($Bln2=="03"){ echo "SELECTED";}?>>Maret</option>
					<option value="04" <?php if($Bln2=="04"){ echo "SELECTED";}?>>April</option>
					<option value="05" <?php if($Bln2=="05"){ echo "SELECTED";}?>>Mei</option>
					<option value="06" <?php if($Bln2=="06"){ echo "SELECTED";}?>>Juni</option>
					<option value="07" <?php if($Bln2=="07"){ echo "SELECTED";}?>>Juli</option>
					<option value="08" <?php if($Bln2=="08"){ echo "SELECTED";}?>>Agustus</option>
					<option value="09" <?php if($Bln2=="09"){ echo "SELECTED";}?>>September</option>
					<option value="10" <?php if($Bln2=="10"){ echo "SELECTED";}?>>Oktober</option>
					<option value="11" <?php if($Bln2=="11"){ echo "SELECTED";}?>>November</option>
					<option value="12" <?php if($Bln2=="12"){ echo "SELECTED";}?>>Desember</option>	
                	</select>
                	</div>		
				 <!-- /.input group -->
			
              	  
          </div>
			  
				 
			 
          </div>		  
		  <div class="card-footer"> 
			  <button class="btn btn-info" type="submit">Cari Data</button>
		  </div>	
		  <!-- /.card-body -->          
        </div>  
		
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Laporan Bulanan Benang</h3>
				<!--<a href="pages/cetak/lapgmasuk_excel1.php?awal=<?php echo $Awal;?>&akhir=<?php echo $Akhir;?>" class="btn bg-blue float-right" target="_blank">Cetak Excel</a>-->  
          </div>
              <!-- /.card-header -->
              <div class="card-body">
			<?php	  
	$sql = mysqli_query($con," SELECT tgl_tutup,sum(qty) as qty,sum(weight) as kg FROM tblopname 
	WHERE DATE_FORMAT(tgl_tutup,'%Y-%m')='$TBln' AND tipe='GYR' GROUP BY tgl_tutup ORDER BY tgl_tutup DESC LIMIT 1 ");		  
    $r = mysqli_fetch_array($sql);
	$sqlY = mysqli_query($con," SELECT tgl_tutup,sum(qty) as qty,sum(weight) as kg FROM tblopname 
	WHERE DATE_FORMAT(tgl_tutup,'%Y-%m')='$TBln' AND tipe='DYR' GROUP BY tgl_tutup ORDER BY tgl_tutup DESC LIMIT 1 ");		  
    $rY = mysqli_fetch_array($sqlY);			  
	?>			<strong>Sisa Stok Bulan Lalu GYR: <?php echo number_format(round($r['kg'],2),2); ?></strong><br>
				<strong>Sisa Stok Bulan Lalu DYR: <?php echo number_format(round($rY['kg'],2),2); ?></strong><br>  
                <table id="example16" width="100%" class="table table-sm table-bordered table-striped" style="font-size: 11px; text-align: center;">
                  <thead>
                  <tr>
                    <th colspan="7" valign="middle" style="text-align: center">Masuk</th>
                    <th colspan="6" valign="middle" style="text-align: center">Keluar</th>
                    <th colspan="2" rowspan="2" valign="middle" style="text-align: center">Sisa</th>
                    </tr>
                  <tr>
                    <th width="2%" rowspan="2" valign="middle" style="text-align: center">Tgl</th>
                    <th colspan="2" valign="middle" style="text-align: center">Dari Supplier</th>
                    <th colspan="2" valign="middle" style="text-align: center">Retur</th>
                    <th colspan="2" valign="middle" style="text-align: center">Rekonsiliasi</th>
                    <th colspan="2" valign="middle" style="text-align: center">Kirim Ke Prod.</th>
                    <th colspan="2" valign="middle" style="text-align: center">Jual</th>
                    <th colspan="2" valign="middle" style="text-align: center">Retur ke Supplier</th>
                    </tr>
                  <tr>
                    <th width="7%" valign="middle" style="text-align: center">GYR</th>
                    <th width="7%" valign="middle" style="text-align: center">DYR</th>
                    <th width="7%" valign="middle" style="text-align: center">GYR</th>
                    <th width="7%" valign="middle" style="text-align: center">DYR</th>
                    <th width="7%" valign="middle" style="text-align: center">GYR</th>
                    <th width="7%" valign="middle" style="text-align: center">DYR</th>
                    <th width="7%" valign="middle" style="text-align: center">GYR</th>
                    <th width="7%" valign="middle" style="text-align: center">DYR</th>
                    <th width="7%" valign="middle" style="text-align: center">GYR</th>
                    <th width="7%" valign="middle" style="text-align: center">DYR</th>
                    <th width="7%" valign="middle" style="text-align: center">GYR</th>
                    <th width="7%" valign="middle" style="text-align: center">DYR</th>
                    <th width="7%" valign="middle" style="text-align: center">GYR</th>
                    <th width="7%" valign="middle" style="text-align: center">DYR</th>
                    </tr>
                  </thead>
                  <tbody>
<?php for ($i = 1; $i <= $d; $i++){ 
	$sqlMasuk = mysqli_query($con," SELECT tgl_tutup,sum(qty) as qty,sum(berat) as kg FROM tblmasukbenang 
	WHERE tgl_tutup='$Thn2-$Bln2-$i' AND tipe='GYR' AND ISNULL(no_po) GROUP BY tgl_tutup ");	
	$rMasuk = mysqli_fetch_array($sqlMasuk);
	$sqlMasukY = mysqli_query($con," SELECT tgl_tutup,sum(qty) as qty,sum(berat) as kg FROM tblmasukbenang 
	WHERE tgl_tutup='$Thn2-$Bln2-$i' AND tipe='DYR' AND ISNULL(no_po) GROUP BY tgl_tutup ");	
	$rMasukY = mysqli_fetch_array($sqlMasukY);
	$sqlRMasuk = mysqli_query($con," SELECT tgl_tutup,sum(qty) as qty,sum(berat) as kg FROM tblmasukbenang 
	WHERE tgl_tutup='$Thn2-$Bln2-$i' AND tipe='GYR' AND no_po='RETUR' GROUP BY tgl_tutup ");	
	$rRMasuk = mysqli_fetch_array($sqlRMasuk);	
	$sqlRMasukY = mysqli_query($con," SELECT tgl_tutup,sum(qty) as qty,sum(berat) as kg FROM tblmasukbenang 
	WHERE tgl_tutup='$Thn2-$Bln2-$i' AND tipe='DYR' AND no_po='RETUR' GROUP BY tgl_tutup ");	
	$rRMasukY = mysqli_fetch_array($sqlRMasukY);
	$sqlKeluar = mysqli_query($con," SELECT tgl_tutup,sum(qty) as qty,sum(berat) as kg FROM tblkeluarbenang 
	WHERE tgl_tutup='$Thn2-$Bln2-$i' AND tipe='GYR' AND NOT no_po LIKE '%RETUR%' GROUP BY tgl_tutup");		  
    $rKeluar = mysqli_fetch_array($sqlKeluar);
	$sqlKeluarY = mysqli_query($con," SELECT tgl_tutup,sum(qty) as qty,sum(berat) as kg FROM tblkeluarbenang 
	WHERE tgl_tutup='$Thn2-$Bln2-$i' AND tipe='DYR' AND NOT no_po LIKE '%RETUR%' GROUP BY tgl_tutup");		  
    $rKeluarY = mysqli_fetch_array($sqlKeluarY);
	$sqlRKeluar = mysqli_query($con," SELECT tgl_tutup,sum(qty) as qty,sum(berat) as kg FROM tblkeluarbenang 
	WHERE tgl_tutup='$Thn2-$Bln2-$i' AND tipe='GYR' AND no_po LIKE '%RETUR%' GROUP BY tgl_tutup");		  
    $rRKeluar = mysqli_fetch_array($sqlRKeluar);
	$sqlJual = mysqli_query($con," SELECT tgl_tutup,sum(qty) as qty,sum(berat) as kg FROM tblkeluarbenang 
	WHERE tgl_tutup='$Thn2-$Bln2-$i' AND tipe='GYR' AND no_po LIKE '%JUAL%' GROUP BY tgl_tutup");		  
    $rJual = mysqli_fetch_array($sqlJual);
	$sqlJualY = mysqli_query($con," SELECT tgl_tutup,sum(qty) as qty,sum(berat) as kg FROM tblkeluarbenang 
	WHERE tgl_tutup='$Thn2-$Bln2-$i' AND tipe='DYR' AND no_po LIKE '%JUAL%' GROUP BY tgl_tutup");		  
    $rJualY = mysqli_fetch_array($sqlJualY);
	if($i=="1"){
	$sisa+=round($r['kg'],2)+((round($rMasuk['kg'],3)+round($rRMasuk['kg'],3))-(round($rKeluar['kg'],3)+round($rRKeluar['kg'],3)));	
	}else{
	$sisa+=((round($rMasuk['kg'],3)+round($rRMasuk['kg'],3))-(round($rKeluar['kg'],3)+round($rRKeluar['kg'],3)+$rJual['kg']));
	}
	if($i=="1"){
	$sisaY+=round($rY['kg'],2)+((round($rRMasukY['kg'],3))-(round($rKeluarY['kg'],3)+round($rJualY['kg'],3)));	
	}else{
	$sisaY+=((round($rRMasukY['kg'],3))-(round($rKeluarY['kg'],3)));
	}
	
					  ?>
	  <tr>
	  <td><?php echo $i; ?></td>
	  <td align="right"><?php echo number_format(round($rMasuk['kg'],2),2); ?></td>
	  <td align="right">&nbsp;</td>
	  <td align="right"><?php echo number_format(round($rRMasuk['kg'],2),2); ?></td>
	  <td align="right"><?php echo number_format(round($rRMasukY['kg'],2),2); ?></td>
	  <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right"><?php echo number_format(round($rKeluar['kg'],2),2); ?></td>
      <td align="right"><?php echo number_format(round($rKeluarY['kg'],2),2); ?></td>
      <td align="right"><?php echo number_format(round($rJual['kg'],2),2); ?></td>
      <td align="right"><?php echo number_format(round($rJualY['kg'],2),2); ?></td>
      <td align="right"><?php echo number_format(round($rRKeluar['kg'],2),2); ?></td>
      <td>&nbsp;</td>
      <td align="right"><strong><?php echo number_format(round($sisa,3),3); ?></strong></td>
      <td align="right"><strong><?php echo number_format(round($sisaY,3),3); ?></strong></td>
      </tr>
	  				  
	<?php 
	 $no++; 
	$tM+=round($rMasuk['kg'],3);
	$tK+=round($rKeluar['kg'],3);
	$tR+=round($rRMasuk['kg'],3);
	$tMY+=round($rMasukY['kg'],3);
	$tKY+=round($rKeluarY['kg'],3);
	$tRY+=round($rRMasukY['kg'],3);
	$tRK+=round($rRKeluar['kg'],3);
	$tJ+=round($rJual['kg'],3);
	$tJY+=round($rJualY['kg'],3);
	
	} 
	$tS=(round($r['kg'],2)+$tM+$tR)-($tK+$tRK+$tJ);
	$tSY=(round($rY['kg'],2)+$tMY+$tRY)-($tKY);				  
					  ?>
				  </tbody>
                  <tfoot>
	 <tr>
	   <td><strong>Total</strong></td>
	   <td align="right"><strong><?php echo number_format(round($tM,2),2); ?></strong></td>
	   <td align="right">&nbsp;</td>
	   <td align="right"><strong><?php echo number_format(round($tR,2),2); ?></strong></td>
	   <td align="right"><strong><?php echo number_format(round($tRY,2),2); ?></strong></td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td align="right"><strong><?php echo number_format(round($tK,2),2); ?></strong></td>
	    <td align="right"><strong><?php echo number_format(round($tKY,2),2); ?></strong></td>
	    <td align="right"><strong><?php echo number_format(round($tJ,2),2); ?></strong></td>
	    <td align="right"><strong><?php echo number_format(round($tJY,2),2); ?></strong></td>
	    <td align="right"><strong><?php echo number_format(round($tRK,2),2); ?></strong></td>
	    <td>&nbsp;</td>
	    <td align="right"><strong><?php echo number_format(round($tS,3),3); ?></strong></td>
	    <td align="right"><strong><?php echo number_format(round($tSY,3),3); ?></strong></td>
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