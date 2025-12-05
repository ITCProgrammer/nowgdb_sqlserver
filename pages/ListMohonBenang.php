<!-- Main content -->
      <div class="container-fluid">	
		<form method="post" name="form1" action="pages/cetak/list-permohonan.php" target="_blank">  
	    <div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Data List Mohon Benang</h3>
				<button type="submit" class="btn btn-sm btn-success float-right" name="cetak" value="Cetak" formtarget="_blank"><i class="far fa-file"></i> Cetak</button>  
              </div>
              <!-- /.card-header -->
              <div class="card-body">
          <table id="example4" class="table table-sm table-bordered table-striped" style="font-size:12px;">
                  <thead>
                  <tr>
                    <th style="text-align: center">#</th>
                    <th style="text-align: center">No</th>
                    <th style="text-align: center">Tgl Permintaan Kirim</th>
                    <th style="text-align: center">Project</th>
                    <th style="text-align: center">No IntDoc</th>
                    <th style="text-align: center">Tipe</th>
                    <th style="text-align: center">Kd Benang</th>
                    <th style="text-align: center">Kode</th>
                    <th style="text-align: center">Supplier</th>
                    <th style="text-align: center">Lot</th>
                    <th style="text-align: center">Kgs</th>
                    <th style="text-align: center">Qty</th>
                    <th style="text-align: center">Dept</th>
                    <th style="text-align: center">Status</th>
                    <th style="text-align: center">Blok</th>
                    <th style="text-align: center">Blok Aktual</th>
                    <th style="text-align: center">Q.Lvl</th>
                    <th style="text-align: center">Tgl Masuk</th>
                    <th style="text-align: center">Tgl Prod.</th>
                    <th style="text-align: center">Keterangan</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
					  
   $no=1;   
   $c=0;
	
	$sqlDB21 = " SELECT x.*, a1.VALUESTRING AS supp
,a2.VALUESTRING AS LOT
,a3.VALUEDECIMAL AS QTY
,a4.VALUESTRING AS LOKASI
,a5.VALUESTRING AS KET
,a6.VALUESTRING AS KDBENANG
,a7.VALUESTRING AS SATUAN
,a8.VALUESTRING AS TGLMASUK
,a9.VALUESTRING AS TGLPRO
FROM DB2ADMIN.INTERNALDOCUMENTLINE x
LEFT OUTER JOIN ADSTORAGE a1 ON a1.UNIQUEID =x.ABSUNIQUEID AND a1.NAMENAME ='SuppName'
LEFT OUTER JOIN ADSTORAGE a2 ON a2.UNIQUEID =x.ABSUNIQUEID AND a2.NAMENAME ='Lot'
LEFT OUTER JOIN ADSTORAGE a3 ON a3.UNIQUEID =x.ABSUNIQUEID AND a3.NAMENAME ='QtyP'
LEFT OUTER JOIN ADSTORAGE a4 ON a4.UNIQUEID =x.ABSUNIQUEID AND a4.NAMENAME ='LokasiB'
LEFT OUTER JOIN ADSTORAGE a5 ON a5.UNIQUEID =x.ABSUNIQUEID AND a5.NAMENAME ='KetB'
LEFT OUTER JOIN ADSTORAGE a6 ON a6.UNIQUEID =x.ABSUNIQUEID AND a6.NAMENAME ='KdBenang'
LEFT OUTER JOIN ADSTORAGE a7 ON a7.UNIQUEID =x.ABSUNIQUEID AND a7.NAMENAME ='Satuan'
LEFT OUTER JOIN ADSTORAGE a8 ON a8.UNIQUEID =x.ABSUNIQUEID AND a8.NAMENAME ='TglMasukB'
LEFT OUTER JOIN ADSTORAGE a9 ON a9.UNIQUEID =x.ABSUNIQUEID AND a9.NAMENAME ='TglProduksiB'
	WHERE INTDOCPROVISIONALCOUNTERCODE='I02M01' AND RECEIVINGDATE > '2022-12-31' AND  
	(DESTINATIONWAREHOUSECODE ='P501' OR DESTINATIONWAREHOUSECODE ='P503' OR DESTINATIONWAREHOUSECODE ='P504' OR DESTINATIONWAREHOUSECODE ='M904' OR DESTINATIONWAREHOUSECODE ='M011' OR DESTINATIONWAREHOUSECODE IS NULL) and WAREHOUSECODE ='M011' and PROGRESSSTATUS ='0'
	ORDER BY x.RECEIVINGDATE DESC,x.CREATIONDATETIME DESC ";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){	
	$kd= trim($rowdb21['SUBCODE01'])."-".trim($rowdb21['SUBCODE02'])."-".trim($rowdb21['SUBCODE03'])."-".trim($rowdb21['SUBCODE04'])."-".trim($rowdb21['SUBCODE05'])."-".trim($rowdb21['SUBCODE06'])."-".trim($rowdb21['SUBCODE07'])."-".trim($rowdb21['SUBCODE08']);	$kdb=trim($rowdb21['SUBCODE01']).trim($rowdb21['SUBCODE02']).trim($rowdb21['SUBCODE03']).trim($rowdb21['SUBCODE04']).trim($rowdb21['SUBCODE05']).trim($rowdb21['SUBCODE06']).trim($rowdb21['SUBCODE07']).trim($rowdb21['SUBCODE08']);
	$intDoc=trim($rowdb21['INTDOCUMENTPROVISIONALCODE'])."-".$rowdb21['ORDERLINE'];	
	if($rowdb21['PROGRESSSTATUS']=="0"){	
	$stts="<small class='badge badge-info'><i class='far fa-clock blink_me'></i> Entered</small>";	
	}else if($rowdb21['PROGRESSSTATUS']=="1"){	
	$stts="<small class='badge badge-warning'><i class='far fa-clock blink_me'></i> Partially Shipped</small>";	
	}
	if($rowdb21['QUALITYCODE']=="1"){
		$qly="<small class='badge badge-danger'>1</small>";
	}else if($rowdb21['QUALITYCODE']=="2"){
		$qly="<small class='badge badge-warning'>2</small>";
	}else if($rowdb21['QUALITYCODE']=="3"){
		$qly="<small class='badge badge-success'>3</small>";
	}
	$pos1= strpos($rowdb21['ITEMDESCRIPTION'],"*");
	$pot1= substr($rowdb21['ITEMDESCRIPTION'],$pos1+1,200);
	$pos2= strpos($pot1,"*");
	$pot2= substr($pot1,$pos2+1,200);
	$pos3= strpos($pot2,"*");	
	$pot3= substr($pot2,$pos3+1,200);
	$pos4= strpos($pot3,"*");	
	$supp=substr($rowdb21['ITEMDESCRIPTION'],0,$pos1);
	$lot=substr($pot1,0,$pos2);	
	$blok=substr($pot2,0,$pos3);
	$qty=substr($pot3,0,$pos4);

    // Ambil lokasi dari SQL Server (pengganti GROUP_CONCAT MySQL)
    $sqllok = sqlsrv_query(
        $con,
        "SELECT STRING_AGG(lokasi, ',') AS lok
         FROM (
             SELECT DISTINCT lokasi
             FROM dbnow_gdb.tblambillokasi
             WHERE no_doc = ?
         ) AS s",
        array($intDoc)
    );

    if ($sqllok === false) {
        die(print_r(sqlsrv_errors(), true));
    }

	$rLok = sqlsrv_fetch_array($sqllok, SQLSRV_FETCH_ASSOC);
	if($rowdb21['SATUAN']=="0"){
	$satuan	="DUS";
	}else if($rowdb21['SATUAN']=="1"){
	$satuan	="KARUNG";	}
	else if($rowdb21['SATUAN']=="2"){
	$satuan	="CONES";	}	
?>
	  <tr>
	  <td style="text-align: center"><input type=checkbox name="cek[<?php echo $no; ?>]" value="<?php echo $no; ?>"></td>
      <td style="text-align: center"><?php echo $no;?></td>
      <td style="text-align: center"><?php echo substr($rowdb21['RECEIVINGDATE'],0,10); ?></td>
      <td style="text-align: left"><?php echo $rowdb21['EXTERNALREFERENCE']; ?></td>
      <td style="text-align: left"><?php echo trim($rowdb21['INTDOCUMENTPROVISIONALCODE'])."-".$rowdb21['ORDERLINE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['ITEMTYPEAFICODE']; ?></td>
      <td style="text-align: left"><a href="#" id="<?php echo $kdb."#".$intDoc; ?>" class="show_detailStkBenang"><?php echo $kd;?></a></td>
      <td style="text-align: center"><?php echo $rowdb21['KDBENANG']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['SUPP']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['LOT']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['BASEPRIMARYQUANTITY']; ?></td>
      <td style="text-align: center"><?php echo round($rowdb21['QTY'])." ".$satuan; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['DESTINATIONWAREHOUSECODE']; ?></td>
      <td style="text-align: center"><?php echo $stts;?></td>
      <td style="text-align: center"><?php echo $rowdb21['LOKASI']; ?></td>
      <td style="text-align: left"><a href="#" id="<?php echo $kdb."#".$intDoc; ?>" class="show_detailLokBenang"><?php echo $rLok['lok']; ?></a></td>
      <td style="text-align: center"><?php echo $qly; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['TGLMASUK']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['TGLPRO']; ?></td>
      <td style="text-align: left"><?php echo $rowdb21['KET']; ?></td>
      </tr>				  
<?php	$no++;} ?>
				  </tbody>
                  
                </table>
            </div>
              <!-- /.card-body -->
        </div>
		  </form>			
</div><!-- /.container-fluid -->
    <!-- /.content -->
<div id="DetailShowStkBenang" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
<div id="DetailShowLokBenang" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
