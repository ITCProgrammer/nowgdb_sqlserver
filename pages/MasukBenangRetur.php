<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Data Tgl Benang Retur</h3>

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
                <h3 class="card-title">Detail Data Benang Retur</h3>
				<div class="btn-group float-right">
				<a href="pages/cetak/laprmasuk_excel.php?awal=<?php echo $Awal;?>&akhir=<?php echo $Akhir;?>" class="btn bg-blue" target="_blank">Cetak Excel</a>
				<a href="pages/cetak/cetaklaprmasuk.php?awal=<?php echo $Awal;?>&akhir=<?php echo $Akhir;?>" class="btn bg-green" target="_blank">Cetak</a>
				</div>  
				  
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">TGL</th>
                    <th valign="middle" style="text-align: center">User</th>
                    <th valign="middle" style="text-align: center">No BON</th>
                    <th valign="middle" style="text-align: center">KNITT</th>
                    <th valign="middle" style="text-align: center">No PO</th>
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
		  
	$sqlDB21 = " SELECT
	INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
	INTERNALDOCUMENTLINE.ORDERLINE,
	INTERNALDOCUMENTLINE.EXTERNALREFERENCE,
	INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
	INTERNALDOCUMENTLINE.WAREHOUSECODE,
	STOCKTRANSACTION.LOTCODE,
	LOT.SUPPLIERCODE,
	BUSINESSPARTNER.LEGALNAME1,
	STOCKTRANSACTION.TRANSACTIONDATE,
	SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG,
	COUNT(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_ROL,
	SUM(STOCKTRANSACTION.BASESECONDARYQUANTITY) AS QTY_CONES,
	INTERNALDOCUMENTLINE.SUBCODE01,
	INTERNALDOCUMENTLINE.SUBCODE02,
	INTERNALDOCUMENTLINE.SUBCODE03,
	INTERNALDOCUMENTLINE.SUBCODE04,
	INTERNALDOCUMENTLINE.SUBCODE05,
	INTERNALDOCUMENTLINE.SUBCODE06,
	INTERNALDOCUMENTLINE.SUBCODE07,
	INTERNALDOCUMENTLINE.SUBCODE08,
	STOCKTRANSACTION.CREATIONUSER,
	STOCKTRANSACTION.LOGICALWAREHOUSECODE,
	STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
	STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
	FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
FROM
	DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION
LEFT OUTER JOIN DB2ADMIN.INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON
	INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE = STOCKTRANSACTION.ORDERCODE
	AND 
INTERNALDOCUMENTLINE.ORDERLINE = STOCKTRANSACTION.ORDERLINE
    AND INTERNALDOCUMENTLINE.ITEMTYPEAFICODE = STOCKTRANSACTION.ITEMTYPECODE
	AND INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE = STOCKTRANSACTION.ORDERCOUNTERCODE
	AND INTERNALDOCUMENTLINE.SUBCODE01  = STOCKTRANSACTION.DECOSUBCODE01
	AND INTERNALDOCUMENTLINE.SUBCODE02  = STOCKTRANSACTION.DECOSUBCODE02
	AND INTERNALDOCUMENTLINE.SUBCODE03  = STOCKTRANSACTION.DECOSUBCODE03
	AND INTERNALDOCUMENTLINE.SUBCODE04  = STOCKTRANSACTION.DECOSUBCODE04
	AND INTERNALDOCUMENTLINE.SUBCODE05  = STOCKTRANSACTION.DECOSUBCODE05
	AND INTERNALDOCUMENTLINE.SUBCODE06  = STOCKTRANSACTION.DECOSUBCODE06
	AND INTERNALDOCUMENTLINE.SUBCODE07  = STOCKTRANSACTION.DECOSUBCODE07
	AND INTERNALDOCUMENTLINE.SUBCODE08  = STOCKTRANSACTION.DECOSUBCODE08
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
	STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
LEFT OUTER JOIN LOT ON
	STOCKTRANSACTION.LOTCODE = LOT.CODE
	AND LOT.COMPANYCODE = '100'
	AND 
STOCKTRANSACTION.ITEMTYPECODE = LOT.ITEMTYPECODE
	AND
STOCKTRANSACTION.DECOSUBCODE01 = LOT.DECOSUBCODE01
	AND
STOCKTRANSACTION.DECOSUBCODE02 = LOT.DECOSUBCODE02
	AND
STOCKTRANSACTION.DECOSUBCODE03 = LOT.DECOSUBCODE03
	AND
STOCKTRANSACTION.DECOSUBCODE04 = LOT.DECOSUBCODE04
	AND
STOCKTRANSACTION.DECOSUBCODE05 = LOT.DECOSUBCODE05
	AND
STOCKTRANSACTION.DECOSUBCODE06 = LOT.DECOSUBCODE06
	AND
STOCKTRANSACTION.DECOSUBCODE07 = LOT.DECOSUBCODE07
	AND
STOCKTRANSACTION.DECOSUBCODE08 = LOT.DECOSUBCODE08
LEFT OUTER JOIN CUSTOMERSUPPLIERDATA ON
	LOT.SUPPLIERCODE = CUSTOMERSUPPLIERDATA.CODE
	AND CUSTOMERSUPPLIERDATA.COMPANYCODE = '100'
	AND CUSTOMERSUPPLIERDATA.TYPE = '2'
LEFT OUTER JOIN BUSINESSPARTNER ON
	CUSTOMERSUPPLIERDATA.BUSINESSPARTNERNUMBERID = BUSINESSPARTNER.NUMBERID
WHERE
	(INTERNALDOCUMENTLINE.EXTERNALREFERENCE = 'RETUR'
		OR INTERNALDOCUMENTLINE.EXTERNALREFERENCE = 'RETURAN')
	AND STOCKTRANSACTION.LOGICALWAREHOUSECODE = 'M011'
	AND INTERNALDOCUMENTLINE.ITEMTYPEAFICODE = 'GYR'
	AND
STOCKTRANSACTION.TRANSACTIONDATE BETWEEN '$Awal' AND '$Akhir'
	AND NOT INTERNALDOCUMENTLINE.ORDERLINE IS NULL
GROUP BY
	STOCKTRANSACTION.LOTCODE,
	INTERNALDOCUMENTLINE.WAREHOUSECODE,
	INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
	INTERNALDOCUMENTLINE.EXTERNALREFERENCE,
	INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
	INTERNALDOCUMENTLINE.ORDERLINE,
	INTERNALDOCUMENTLINE.SUBCODE01,
	INTERNALDOCUMENTLINE.SUBCODE02,
	INTERNALDOCUMENTLINE.SUBCODE03,
	INTERNALDOCUMENTLINE.SUBCODE04,
	INTERNALDOCUMENTLINE.SUBCODE05,
	INTERNALDOCUMENTLINE.SUBCODE06,
	INTERNALDOCUMENTLINE.SUBCODE07,
	INTERNALDOCUMENTLINE.SUBCODE08,
	LOT.SUPPLIERCODE,
	BUSINESSPARTNER.LEGALNAME1,
	STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
	STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
	STOCKTRANSACTION.TRANSACTIONDATE,
	STOCKTRANSACTION.LOGICALWAREHOUSECODE,
	STOCKTRANSACTION.CREATIONUSER,
	FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION ";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	$no=1;   
	$c=0;		
	$knitt="";				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
$bon=$rowdb21['INTDOCUMENTPROVISIONALCODE']."-".$rowdb21['ORDERLINE'];		
if (trim($rowdb21['WAREHOUSECODE']) =="M904") { $knitt = 'LT2'; }
else if(trim($rowdb21['WAREHOUSECODE']) =="P501"){ $knitt = 'LT1'; }
else if(trim($rowdb21['WAREHOUSECODE']) =="P503" or trim($rowdb21['WAREHOUSECODE']) =="P504"){ $knitt = 'YND'; }
					  ?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
	  <td style="text-align: center"><?php echo $rowdb21['CREATIONUSER']; ?></td>
      <td style="text-align: center"><a href="#" id="<?php echo trim($rowdb21['INTDOCUMENTPROVISIONALCODE'])."-".trim($rowdb21['ORDERLINE'])."-".trim($rowdb21['TRANSACTIONDATE']); ?>" class="show_detail"><?php echo $bon; ?></a></td>
      <td style="text-align: center"><?php echo $knitt; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['EXTERNALREFERENCE']; ?></td>
      <td><?php echo $rowdb21['ITEMDESCRIPTION']; ?></td>
      <td style="text-align: left"><?php echo $rowdb21['LEGALNAME1']; ?></td> 
      <td style="text-align: left"><?php echo $rowdb21['SUMMARIZEDDESCRIPTION']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
      <td style="text-align: right"><?php echo $rowdb21['QTY_ROL']; ?></td>
      <td style="text-align: right"><?php echo round($rowdb21['QTY_CONES']); ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['QTY_KG'],2),2); ?></td>
      <td><?php echo $rowdb21['WHSLOCATIONWAREHOUSEZONECODE']."-".$rowdb21['WAREHOUSELOCATIONCODE']; ?></td>
      </tr>	  				  
	<?php 
	 $no++; 
	 	$tCONES+=$rowdb21['QTY_CONES'];
		$tDUS+=$rowdb21['QTY_ROL'];
		$tKG+=$rowdb21['QTY_KG'];	
	} ?>
				  </tbody>
          <tfoot>
	  <tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td style="text-align: left">&nbsp;</td>
	    <td colspan="2" style="text-align: right"><strong>Total</strong></td>
	    <td style="text-align: right"><strong>
	      <?php 
          echo $tDUS;
        ?>
	      </strong></td>
	    <td style="text-align: right"><strong>
	      <?php 
          echo $tCONES;
        ?>
	      </strong></td>
	    <td style="text-align: right"><strong>
	      <?php 
          echo $tKG;
        ?>
	      </strong></td>
	    <td>&nbsp;</td>
	    </tr>				
					</tfoot>        
                </table>
              </div>
              <!-- /.card-body -->
            </div> 
	<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Data Benang Retur YND</h3>
				<div class="btn-group float-right">
				<a href="pages/cetak/laprmasukW_excel.php?awal=<?php echo $Awal;?>&akhir=<?php echo $Akhir;?>" class="btn bg-blue" target="_blank">Cetak Excel</a>
				<a href="pages/cetak/cetaklaprmasukW.php?awal=<?php echo $Awal;?>&akhir=<?php echo $Akhir;?>" class="btn bg-green" target="_blank">Cetak</a>
				</div>  
				  
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example3" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">TGL</th>
                    <th valign="middle" style="text-align: center">User</th>
                    <th valign="middle" style="text-align: center">No BON</th>
                    <th valign="middle" style="text-align: center">KNITT</th>
                    <th valign="middle" style="text-align: center">No PO</th>
                    <th valign="middle" style="text-align: center">Code Warna</th>
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
		  
	$sqlDB21 = " SELECT 
INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
INTERNALDOCUMENTLINE.ORDERLINE,
INTERNALDOCUMENTLINE.EXTERNALREFERENCE,
INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
INTERNALDOCUMENTLINE.WAREHOUSECODE,
STOCKTRANSACTION.LOTCODE,  
LOT.SUPPLIERCODE, 
BUSINESSPARTNER.LEGALNAME1,
STOCKTRANSACTION.TRANSACTIONDATE,
SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG,
COUNT(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_ROL,
SUM(STOCKTRANSACTION.BASESECONDARYQUANTITY) AS QTY_CONES,
INTERNALDOCUMENTLINE.SUBCODE01,
INTERNALDOCUMENTLINE.SUBCODE02,
INTERNALDOCUMENTLINE.SUBCODE03,
INTERNALDOCUMENTLINE.SUBCODE04,
INTERNALDOCUMENTLINE.SUBCODE05,
INTERNALDOCUMENTLINE.SUBCODE06,
INTERNALDOCUMENTLINE.SUBCODE07,
INTERNALDOCUMENTLINE.SUBCODE08,
STOCKTRANSACTION.CREATIONUSER,
STOCKTRANSACTION.LOGICALWAREHOUSECODE,
STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION
FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION 
LEFT OUTER JOIN DB2ADMIN.INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE=STOCKTRANSACTION.ORDERCODE AND 
INTERNALDOCUMENTLINE.ORDERLINE=STOCKTRANSACTION.ORDERLINE 
AND INTERNALDOCUMENTLINE.ITEMTYPEAFICODE = STOCKTRANSACTION.ITEMTYPECODE
AND INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE = STOCKTRANSACTION.ORDERCOUNTERCODE
	AND INTERNALDOCUMENTLINE.SUBCODE01  = STOCKTRANSACTION.DECOSUBCODE01
	AND INTERNALDOCUMENTLINE.SUBCODE02  = STOCKTRANSACTION.DECOSUBCODE02
	AND INTERNALDOCUMENTLINE.SUBCODE03  = STOCKTRANSACTION.DECOSUBCODE03
	AND INTERNALDOCUMENTLINE.SUBCODE04  = STOCKTRANSACTION.DECOSUBCODE04
	AND INTERNALDOCUMENTLINE.SUBCODE05  = STOCKTRANSACTION.DECOSUBCODE05
	AND INTERNALDOCUMENTLINE.SUBCODE06  = STOCKTRANSACTION.DECOSUBCODE06
	AND INTERNALDOCUMENTLINE.SUBCODE07  = STOCKTRANSACTION.DECOSUBCODE07
	AND INTERNALDOCUMENTLINE.SUBCODE08  = STOCKTRANSACTION.DECOSUBCODE08
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
LEFT OUTER JOIN LOT  ON STOCKTRANSACTION.LOTCODE = LOT.CODE AND LOT.COMPANYCODE = '100' AND 
STOCKTRANSACTION.ITEMTYPECODE = LOT.ITEMTYPECODE AND
STOCKTRANSACTION.DECOSUBCODE01= LOT.DECOSUBCODE01 AND
STOCKTRANSACTION.DECOSUBCODE02= LOT.DECOSUBCODE02 AND
STOCKTRANSACTION.DECOSUBCODE03= LOT.DECOSUBCODE03 AND
STOCKTRANSACTION.DECOSUBCODE04= LOT.DECOSUBCODE04 AND
STOCKTRANSACTION.DECOSUBCODE05= LOT.DECOSUBCODE05 AND
STOCKTRANSACTION.DECOSUBCODE06= LOT.DECOSUBCODE06 AND
STOCKTRANSACTION.DECOSUBCODE07= LOT.DECOSUBCODE07 AND
STOCKTRANSACTION.DECOSUBCODE08= LOT.DECOSUBCODE08
LEFT OUTER JOIN CUSTOMERSUPPLIERDATA  ON LOT.SUPPLIERCODE =CUSTOMERSUPPLIERDATA.CODE AND CUSTOMERSUPPLIERDATA.COMPANYCODE = '100' AND CUSTOMERSUPPLIERDATA.TYPE = '2'
LEFT OUTER JOIN BUSINESSPARTNER ON CUSTOMERSUPPLIERDATA.BUSINESSPARTNERNUMBERID =BUSINESSPARTNER.NUMBERID
WHERE (INTERNALDOCUMENTLINE.EXTERNALREFERENCE='RETUR' OR INTERNALDOCUMENTLINE.EXTERNALREFERENCE='RETURAN') AND STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M011' AND INTERNALDOCUMENTLINE.ITEMTYPEAFICODE='DYR' AND
STOCKTRANSACTION.TRANSACTIONDATE BETWEEN '$Awal' AND '$Akhir' AND NOT INTERNALDOCUMENTLINE.ORDERLINE IS NULL
GROUP BY
STOCKTRANSACTION.LOTCODE,
INTERNALDOCUMENTLINE.WAREHOUSECODE,
INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
INTERNALDOCUMENTLINE.EXTERNALREFERENCE,
INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
INTERNALDOCUMENTLINE.ORDERLINE,
INTERNALDOCUMENTLINE.SUBCODE01,
INTERNALDOCUMENTLINE.SUBCODE02,
INTERNALDOCUMENTLINE.SUBCODE03,
INTERNALDOCUMENTLINE.SUBCODE04,
INTERNALDOCUMENTLINE.SUBCODE05,
INTERNALDOCUMENTLINE.SUBCODE06,
INTERNALDOCUMENTLINE.SUBCODE07,
INTERNALDOCUMENTLINE.SUBCODE08,
LOT.SUPPLIERCODE,
BUSINESSPARTNER.LEGALNAME1,
STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
STOCKTRANSACTION.TRANSACTIONDATE,
STOCKTRANSACTION.LOGICALWAREHOUSECODE,
STOCKTRANSACTION.CREATIONUSER,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION ";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	$no=1;   
	$c=0;		
	$knitt="";				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
$bon=$rowdb21['INTDOCUMENTPROVISIONALCODE']."-".$rowdb21['ORDERLINE'];		
if (trim($rowdb21['WAREHOUSECODE']) =="M904") { $knitt = 'LT2'; }
else if(trim($rowdb21['WAREHOUSECODE']) =="P501"){ $knitt = 'LT1'; }
else if(trim($rowdb21['WAREHOUSECODE']) =="P503"){ $knitt = 'YND'; }
					  ?>
	  <tr>
	  <td style="text-align: center"><?php echo $no;?></td>
	  <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
	  <td style="text-align: center"><?php echo $rowdb21['CREATIONUSER']; ?></td>
      <td style="text-align: center"><a href="#" id="<?php echo trim($rowdb21['INTDOCUMENTPROVISIONALCODE'])."-".trim($rowdb21['ORDERLINE'])."-".trim($rowdb21['TRANSACTIONDATE']); ?>" class="show_detail"><?php echo $bon; ?></a></td>
      <td style="text-align: center"><?php echo $knitt; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['EXTERNALREFERENCE']; ?></td>
      <td><?php echo $rowdb21['SUBCODE08']; ?></td>
      <td style="text-align: left"><?php echo $rowdb21['LEGALNAME1']; ?></td> 
      <td style="text-align: left"><?php echo $rowdb21['SUMMARIZEDDESCRIPTION']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
      <td style="text-align: right"><?php echo $rowdb21['QTY_ROL']; ?></td>
      <td style="text-align: right"><?php echo round($rowdb21['QTY_CONES']); ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['QTY_KG'],2),2); ?></td>
      <td><?php echo $rowdb21['WHSLOCATIONWAREHOUSEZONECODE']."-".$rowdb21['WAREHOUSELOCATIONCODE']; ?></td>
      </tr>	  				  
	<?php 
	 $no++; 
		$tCONES1+=$rowdb21['QTY_CONES'];
		$tDUS1+=$rowdb21['QTY_ROL'];
		$tKG1+=$rowdb21['QTY_KG'];
	
	} ?>
				  </tbody>
        <tfoot>
		<tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td style="text-align: left">&nbsp;</td>
	    <td colspan="2" style="text-align: right"><strong>Total</strong></td>
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
<div id="DetailShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
<?php 
if(isset($_POST['mutasikain']) && $_POST['mutasikain']=="MutasiKain"){
	
function mutasiurut(){
include "koneksi.php";		
$format = "20".date("ymd");
$sql=sqlsrv_query_safe($con,"SELECT TOP 1 no_mutasi FROM dbnow_gdb.tbl_mutasi_kain WHERE SUBSTRING(no_mutasi,1,8) like '%".$format."%' ORDER BY no_mutasi DESC");
$r=($sql !== false) ? sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC) : null;
if($r){
$d=$r['no_mutasi'];
$str=substr($d,8,2);
$Urut = (int)$str;
}else{
$Urut = 0;
}
$Urut = $Urut + 1;
$Nol="";
$nilai=2-strlen($Urut);
for ($i=1;$i<=$nilai;$i++){
$Nol= $Nol."0";
}
$tidbr =$format.$Nol.$Urut;
return $tidbr;
}
$nomid=mutasiurut();	

$sql1=sqlsrv_query_safe($con,"SELECT a.*,count(b.transid) as jmlrol,a.transid as kdtrans FROM dbnow_gdb.tbl_mutasi_kain a 
LEFT JOIN dbnow_gdb.tbl_prodemand b ON a.transid=b.transid 
WHERE a.no_mutasi IS NULL AND CONVERT(char(10),a.tgl_buat,120)='".sqlsrv_escape_str($Awal)."' AND a.gshift='".sqlsrv_escape_str($Gshift)."' 
GROUP BY a.transid, a.tgl_buat, a.gshift");
$n1=1;
$noceklist1=1;	
while($sql1 !== false && ($r1=sqlsrv_fetch_array($sql1, SQLSRV_FETCH_ASSOC))){	
	if(!empty($_POST['cek'][$n1])) 
		{
		$transid1 = $_POST['cek'][$n1];
		sqlsrv_query_safe($con,"UPDATE dbnow_gdb.tbl_mutasi_kain SET
		no_mutasi='".sqlsrv_escape_str($nomid)."',
		tgl_mutasi=GETDATE()
		WHERE transid='".sqlsrv_escape_str($transid1)."'
		");
		}else{
			$noceklist1++;
	}
	$n1++;
	}
if($noceklist1==$n1){
	echo "<script>
  	$(function() {
    const Toast = Swal.mixin({
      toast: false,
      position: 'middle',
      showConfirmButton: false,
      timer: 2000
    });
	Toast.fire({
        icon: 'info',
        title: 'Data tidak ada yang di Ceklist',
		
      })
  });
  
</script>";	
}else{	
echo "<script>
	$(function() {
    const Toast = Swal.mixin({
      toast: false,
      position: 'middle',
      showConfirmButton: true,
      timer: 6000
    });
	Toast.fire({
  title: 'Data telah di Mutasi',
  text: 'klik OK untuk Cetak Bukti Mutasi',
  icon: 'success',  
}).then((result) => {
  if (result.isConfirmed) {
    	window.open('pages/cetak/cetak_mutasi_ulang.php?mutasi=$nomid', '_blank');
  }
})
  });
	</script>";
	
/*echo "<script>
	Swal.fire({
  title: 'Data telah di Mutasi',
  text: 'klik OK untuk Cetak Bukti Mutasi',
  icon: 'success',  
}).then((result) => {
  if (result.isConfirmed) {
    	window.location='Mutasi';
  }
});
	</script>";	*/
}
}
?>
