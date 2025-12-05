<!-- Main content -->
      <div class="container-fluid">		
	    <div class="card">
              <div class="card-header">
                <h3 class="card-title">Stock</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
          <table id="example1" class="table table-sm table-bordered table-striped" style="font-size:13px;">
                  <thead>
                  <tr>
                    <th style="text-align: center">Code</th>
                    <th style="text-align: center">Jenis Benang</th>
                    <th style="text-align: center">Lot</th>
                    <th style="text-align: center">Weight</th>
                    <th style="text-align: center">Qty</th>
                    <th style="text-align: center">Cones</th>
                    <th style="text-align: center">Zone</th>
                    <th style="text-align: center">Lokasi</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php				  
   $no=1;   
   $c=0;
	$sqlDB21 = " SELECT 
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
SUM(b.BASEPRIMARYQUANTITYUNIT) AS KGS,
SUM(b.BASESECONDARYQUANTITYUNIT) AS CONES,
COUNT(b.ELEMENTSCODE) AS QTY,
b.WHSLOCATIONWAREHOUSEZONECODE,
b.WAREHOUSELOCATIONCODE FROM BALANCE b 
WHERE (b.ITEMTYPECODE ='GYR' OR b.ITEMTYPECODE ='DYR') AND
b.LOGICALWAREHOUSECODE = 'M011' AND 
(
b.WHSLOCATIONWAREHOUSEZONECODE='GB1' OR  
b.WHSLOCATIONWAREHOUSEZONECODE='GB2' OR
b.WHSLOCATIONWAREHOUSEZONECODE='GB5' OR
b.WHSLOCATIONWAREHOUSEZONECODE='GB6' OR 
b.WHSLOCATIONWAREHOUSEZONECODE='GP1' OR 
b.WHSLOCATIONWAREHOUSEZONECODE='GR2' OR 
b.WHSLOCATIONWAREHOUSEZONECODE='GR3' OR 
b.WHSLOCATIONWAREHOUSEZONECODE='LT')
GROUP BY b.LOTCODE, b.ITEMTYPECODE,
b.DECOSUBCODE01, b.DECOSUBCODE02,
b.DECOSUBCODE03, b.DECOSUBCODE04,
b.DECOSUBCODE05, b.DECOSUBCODE06,
b.DECOSUBCODE07, b.DECOSUBCODE08,
b.WHSLOCATIONWAREHOUSEZONECODE,
b.WAREHOUSELOCATIONCODE ";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){
	$kd= trim($rowdb21['DECOSUBCODE01'])."-".trim($rowdb21['DECOSUBCODE02'])."-".trim($rowdb21['DECOSUBCODE03'])."-".trim($rowdb21['DECOSUBCODE04'])."-".trim($rowdb21['DECOSUBCODE05'])."-".trim($rowdb21['DECOSUBCODE06'])."-".trim($rowdb21['DECOSUBCODE07'])."-".trim($rowdb21['DECOSUBCODE08']);	
	$sqlDB23 = " SELECT PRODUCT.LONGDESCRIPTION,PRODUCT.SHORTDESCRIPTION 
	   FROM DB2ADMIN.PRODUCT PRODUCT WHERE
       PRODUCT.ITEMTYPECODE='GYR' AND
	   PRODUCT.SUBCODE01='".trim($rowdb21['DECOSUBCODE01'])."' AND
       PRODUCT.SUBCODE02='".trim($rowdb21['DECOSUBCODE02'])."' AND
       PRODUCT.SUBCODE03='".trim($rowdb21['DECOSUBCODE03'])."' AND
	   PRODUCT.SUBCODE04='".trim($rowdb21['DECOSUBCODE04'])."' AND
       PRODUCT.SUBCODE05='".trim($rowdb21['DECOSUBCODE05'])."' AND
	   PRODUCT.SUBCODE06='".trim($rowdb21['DECOSUBCODE06'])."' AND
       PRODUCT.SUBCODE07='".trim($rowdb21['DECOSUBCODE07'])."' ";
	   $stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
	   $rowdb23 = db2_fetch_assoc($stmt3);
		
	
?>
	  <tr>
	    <td style="text-align: left"><?php echo $kd; ?></td>
      <td style="text-align: left"><?php echo $rowdb23['LONGDESCRIPTION'].$rowdb23['SHORTDESCRIPTION'] ; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['LOTCODE'];?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['KGS'],2),2);?></td>
      <td style="text-align: right"><?php echo round($rowdb21['QTY'],0);?></td>
      <td style="text-align: right"><?php echo round($rowdb21['CONES'],0);?></td>
      <td style="text-align: center"><?php echo $rowdb21['WHSLOCATIONWAREHOUSEZONECODE'];?></td>
      <td style="text-align: center"><?php echo $rowdb21['WAREHOUSELOCATIONCODE'];?></td>
      </tr>
	  				  
<?php	$no++;
		$tKGs += $rowdb21['KGS'];
		$tQTY += $rowdb21['QTY'];
		$tCONES += $rowdb21['CONES'];
	} ?>
				  </tbody>
       <tfoot>
		<tr>
		  <td style="text-align: left">&nbsp;</td>
	    <td style="text-align: left">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: right"><strong><?php echo number_format(round($tKGs,2),2); ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format($tQTY); ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format($tCONES); ?></strong></td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    </tr>   
	   </tfoot>           
                </table>
            </div>
              <!-- /.card-body -->
        </div>        
</div><!-- /.container-fluid -->
    <!-- /.content -->
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