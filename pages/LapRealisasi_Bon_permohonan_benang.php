<?php
$Thn2			= isset($_POST['thn']) ? $_POST['thn'] : '';
$Bln2			= isset($_POST['bln']) ? $_POST['bln'] : '';
$Bulan			= $Thn2."-".$Bln2;
if($Thn2!="" and $Bln2!=""){
$filterBulan = $Bulan; // format: YYYY-MM

$tanggalskrng = new DateTime($filterBulan . '-01');
	
// Buat objek DateTime dari awal bulan
$tanggal = new DateTime($filterBulan . '-01');	

// Kurangi satu hari untuk dapat tanggal terakhir bulan sebelumnya
$tanggal->modify('-1 day');

// Tanggal akhir bulan berjalan
$tanggalAkhirBulanBerjalan = new DateTime($filterBulan . '-01');
$tanggalAkhirBulanBerjalan->modify('last day of this month');

// Format jika ingin ditampilkan sebagai string
$akhirBulanBerjalan = $tanggalAkhirBulanBerjalan->format('Y-m-d');	

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
			  <!-- <button class="btn btn-info" type="submit">Cari Data</button> -->
			   <a href="javascript:void(0);" class="btn btn-sm btn-info"
          onclick="cetak_cek('export_realisasi_bon');"><i class="fa fa-file"></i> Export Excel</a>  
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
	 function cetak_cek(filename) {
  var awal = document.querySelector('select[name="thn"]').value;
  var akhir = document.querySelector('select[name="bln"]').value;

  if (awal !== "" && akhir !== "") {
    let url = 'pages/cetak/' + filename + '.php?thn=' + awal + '&bln=' + akhir;
    window.open(url, '_blank');
  } else {
    alert('Filter tidak boleh kosong!');
  }
}
</script>