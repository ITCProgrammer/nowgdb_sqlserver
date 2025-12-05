<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Data Tgl Masuk Benang</h3>

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
	<div class="card card-danger">
      <div class="card-header">
        <h3 class="card-title">Detail Data Benang Retur dari Supplier</h3>
		<a href="pages/cetak/cetaklapmasukretur.php?tgl1=<?php echo $Awal; ?>&tgl2=<?php echo $Akhir; ?>" class="btn btn-sm btn-success float-right" target="_blank"><i class="fa fa-file"></i> to Print</a>   
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="example3" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
          <thead>
          <tr>
            <th valign="middle" style="text-align: center">No</th>
            <th valign="middle" style="text-align: center">Tgl</th>
            <th valign="middle" style="text-align: center">PO RMP</th>
            <th valign="middle" style="text-align: center">NO Surat Jalan</th>
            <th valign="middle" style="text-align: center">Code</th>
            <th valign="middle" style="text-align: center">Supplier</th>
            <th valign="middle" style="text-align: center">Jenis Benang</th>
            <th valign="middle" style="text-align: center">Lot</th>
            <th valign="middle" style="text-align: center">Qty</th>
            <th valign="middle" style="text-align: center">Cones</th>
            <th valign="middle" style="text-align: center">Berat/Kg</th>
            <th valign="middle" style="text-align: center">Block</th>
            </tr>
          </thead>
          <tbody>
  <?php
	 
$no=1;   
$c=0;
$total_qty = null;		

  /* View yang ke 4 ini fungsinya untuk memfilter data di beberapa tabel yang tidak ada dalam tabel tersebut, 
  jadi terfilter data yang tidak ada agar tidak tampil */
			  
  $sqlDB21 = " SELECT  x.TRANSACTIONNUMBER, a1.VALUESTRING AS KETS, a2.VALUESTRING AS SJ, e.LEGALNAME1, SUM(x.BASESECONDARYQUANTITY) AS QTY_CONES, 
  SUM(x.BASEPRIMARYQUANTITY) AS QTY_KG, COUNT(x.ITEMELEMENTCODE) AS QTY_DUS, x.LOTCODE,bc.WHSLOCATIONWAREHOUSEZONECODE,bc.WAREHOUSELOCATIONCODE, x.TRANSACTIONDATE, f.SUMMARIZEDDESCRIPTION, x.ITEMTYPECODE, x.DECOSUBCODE01, x.DECOSUBCODE02, x.DECOSUBCODE03, x.DECOSUBCODE04,
x.DECOSUBCODE05, x.DECOSUBCODE06, x.DECOSUBCODE07, x.DECOSUBCODE08   
FROM DB2ADMIN.STOCKTRANSACTION x
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE a1 ON x.ABSUNIQUEID = a1.UNIQUEID AND a1.NAMENAME = 'KetSampleGYR'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE a2 ON x.ABSUNIQUEID = a2.UNIQUEID AND a2.NAMENAME = 'SuratJlnGYR'
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER f ON x.FULLITEMIDENTIFIER = f.IDENTIFIER
LEFT OUTER JOIN LOT c ON x.LOTCODE = c.CODE AND c.COMPANYCODE = '100' AND 
x.ITEMTYPECODE = c.ITEMTYPECODE AND
x.DECOSUBCODE01= c.DECOSUBCODE01 AND
x.DECOSUBCODE02= c.DECOSUBCODE02 AND
x.DECOSUBCODE03= c.DECOSUBCODE03 AND
x.DECOSUBCODE04= c.DECOSUBCODE04 AND
x.DECOSUBCODE05= c.DECOSUBCODE05 AND
x.DECOSUBCODE06= c.DECOSUBCODE06 AND
x.DECOSUBCODE07= c.DECOSUBCODE07 AND
x.DECOSUBCODE08= c.DECOSUBCODE08
LEFT OUTER JOIN DB2ADMIN.BALANCE bc ON bc.ELEMENTSCODE = x.ITEMELEMENTCODE 
LEFT OUTER JOIN CUSTOMERSUPPLIERDATA d ON c.SUPPLIERCODE =d.CODE AND d.COMPANYCODE = '100' AND d.TYPE = '2'
LEFT OUTER JOIN BUSINESSPARTNER e ON d.BUSINESSPARTNERNUMBERID =e.NUMBERID
WHERE x.ITEMTYPECODE = 'GYR' AND x.TEMPLATECODE = 'OPN' AND a1.VALUESTRING = '2' AND x.TRANSACTIONDATE BETWEEN '$Awal' AND '$Akhir'
GROUP BY x.TRANSACTIONNUMBER, a1.VALUESTRING, a2.VALUESTRING,  x.TRANSACTIONDATE, e.LEGALNAME1,
bc.WHSLOCATIONWAREHOUSEZONECODE,bc.WAREHOUSELOCATIONCODE, f.SUMMARIZEDDESCRIPTION, x.LOTCODE,x.ITEMTYPECODE, x.DECOSUBCODE01, x.DECOSUBCODE02, x.DECOSUBCODE03, x.DECOSUBCODE04,
x.DECOSUBCODE05, x.DECOSUBCODE06, x.DECOSUBCODE07, x.DECOSUBCODE08 ";

  $stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 

      // $cd=trim($rowdb21['SUBCODE01'])."-".trim($rowdb21['SUBCODE02'])."-".trim($rowdb21['SUBCODE03'])."-".trim($rowdb21['SUBCODE04'])."-".trim($rowdb21['SUBCODE05'])."-".trim($rowdb21['SUBCODE06'])."-".trim($rowdb21['SUBCODE07'])."-".trim($rowdb21['SUBCODE08'])."-".trim($rowdb21['SUBCODE09'])."-".trim($rowdb21['SUBCODE10']);		
      
  $cd = trim($rowdb21['DECOSUBCODE01'])."-".trim($rowdb21['DECOSUBCODE02'])."-"
        .trim($rowdb21['DECOSUBCODE03'])."-".trim($rowdb21['DECOSUBCODE04'])."-"
        .trim($rowdb21['DECOSUBCODE05'])."-".trim($rowdb21['DECOSUBCODE06'])."-"
        .trim($rowdb21['DECOSUBCODE07'])."-".trim($rowdb21['DECOSUBCODE08'])."-"
        .trim($rowdb21['DECOSUBCODE09'])."-".trim($rowdb21['DECOSUBCODE10']);	
      
    if ($rowdb21['DESTINATIONWAREHOUSECODE'] =='M904') { $knitt = 'KNITTING ITTI ATAS- BENANG'; }
    else if($rowdb21['DESTINATIONWAREHOUSECODE'] ='P501') { $knitt = 'KNITTING ITTI- BENANG'; }
    else if($rowdb21['DESTINATIONWAREHOUSECODE'] ='M051') { $knitt =  'KNITTING A- BENANG'; }
    else if($rowdb21['DESTINATIONWAREHOUSECODE'] ='P503') { $knitt =  'YARN DYE'; } 
$sqlDB211 = " SELECT a1.VALUESTRING AS PO FROM STOCKTRANSACTION s 
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE a1 ON s.ABSUNIQUEID = a1.UNIQUEID AND a1.NAMENAME = 'SjPoGYR'
WHERE s.TRANSACTIONNUMBER ='$rowdb21[TRANSACTIONNUMBER]' 
ORDER BY a1.VALUESTRING ASC LIMIT 1 ";
  $stmt11   = db2_exec($conn1,$sqlDB211, array('cursor'=>DB2_SCROLLABLE));
  $r1=db2_fetch_assoc($stmt11);
    ?>
	  <tr>
	    <td style="text-align: center"><?php echo $no;?></td> 
	    <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
      <td style="text-align: center"><?php echo $r1['PO']; ?></td>

      <td><?php echo $rowdb21['SJ']; ?></td>
      <td><?php echo $cd; ?></td> 
      <td style="text-align: left">
        <?php 
        echo $rowdb21['LEGALNAME1']; 
        // echo "-";
        ?>
      </td>
      <td style="text-align: left">
        <?php 
        echo $rowdb21['SUMMARIZEDDESCRIPTION'];
        // echo $rowdb21['ITEMDESCRIPTION'];
        ?>
      </td>
      <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
      <td style="text-align: right">
        <?php 
          echo $rowdb21['QTY_DUS'];
        ?>
      </td>
      <td style="text-align: right"><?php 
          echo round($rowdb21['QTY_CONES']);
        ?></td>
      <td style="text-align: right">
        <?php 
          echo number_format(round($rowdb21['QTY_KG'],2),2);
        ?>
      </td>
      <td><?php echo $rowdb21['WHSLOCATIONWAREHOUSEZONECODE']."-".$rowdb21['WAREHOUSELOCATIONCODE']; ?></td>
      </tr>				  
	<?php 
		$tCONES1+=$rowdb21['QTY_CONES'];
		$tDUS1+=$rowdb21['QTY_DUS'];
		$tKG1+=$rowdb21['QTY_KG'];
		
		$no++; } ?>        
				  </tbody>
   <tfoot>
			<tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td style="text-align: left">&nbsp;</td>
	    <td colspan="2" align="right"><strong>Total</strong></td>
	    <td style="text-align: right"><strong>
	      <?php 
          echo $tDUS1;
        ?>
	    </strong></td>
	    <td style="text-align: right"><strong>
	      <?php 
          echo $tCONES1;
        ?>
	    </strong></td>
	    <td style="text-align: right"><strong>
	      <?php 
          echo $tKG1;
        ?>
	    </strong></td>
	    <td>&nbsp;</td>
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
