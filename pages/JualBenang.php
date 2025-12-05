<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-danger">
          <div class="card-header">
            <h3 class="card-title">Filter Data Tanggal</h3>

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
			
		<div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Detail Data Jual Benang</h3>
				<a href="pages/cetak/cetaklapbjual.php?tgl1=<?php echo $Awal; ?>&tgl2=<?php echo $Akhir; ?>" class="btn btn-sm btn-danger float-right" target="_blank"><i class="fa fa-file"></i> to Print</a>  
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">Tgl</th>
                    <th valign="middle" style="text-align: center">No BON</th>
                    <th valign="middle" style="text-align: center">No PO</th>
                    <th valign="middle" style="text-align: center">ItemDesc</th>
                    <th valign="middle" style="text-align: center">Supplier</th>
                    <th valign="middle" style="text-align: center">Code</th>
                    <th valign="middle" style="text-align: center">Jenis Benang</th>
                    <th valign="middle" style="text-align: center">Lot</th>
                    <th valign="middle" style="text-align: center">Satuan</th>
                    <th valign="middle" style="text-align: center">Qty</th>
                    <th valign="middle" style="text-align: center">Cones</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Block</th>
                    <th valign="middle" style="text-align: center">UserID</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					// Query awal  
	$sqlDB21 = "SELECT 
INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
INTERNALDOCUMENTLINE.ORDERLINE,
INTERNALDOCUMENT.EXTERNALREFERENCE,
INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
STOCKTRANSACTION.LOTCODE,
SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_KG,
COUNT(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS QTY_ROL,
SUM(STOCKTRANSACTION.BASESECONDARYQUANTITY) AS QTY_CONES,
INTERNALDOCUMENT.EXTERNALREFERENCEDATE,
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
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION,
ADSTORAGE.VALUESTRING AS SUPP,
	CASE
		WHEN ADSTORAGE2.VALUESTRING = 0 THEN 'DUS'
		WHEN ADSTORAGE2.VALUESTRING = 1 THEN 'KARUNG'
		WHEN ADSTORAGE2.VALUESTRING = 2 THEN 'CONES'
	END AS SATUAN
FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION 
LEFT OUTER JOIN DB2ADMIN.INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE=STOCKTRANSACTION.ORDERCODE AND 
INTERNALDOCUMENTLINE.ORDERLINE=STOCKTRANSACTION.ORDERLINE
LEFT OUTER JOIN DB2ADMIN.INTERNALDOCUMENT INTERNALDOCUMENT ON INTERNALDOCUMENT.PROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE AND 
INTERNALDOCUMENT.PROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE  
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ADSTORAGE ON
		INTERNALDOCUMENTLINE.ABSUNIQUEID = ADSTORAGE.UNIQUEID
	AND ADSTORAGE.NAMENAME = 'SuppName'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ADSTORAGE2 ON
		INTERNALDOCUMENTLINE.ABSUNIQUEID = ADSTORAGE2.UNIQUEID
	AND ADSTORAGE2.NAMENAME = 'Satuan'
WHERE INTERNALDOCUMENT.EXTERNALREFERENCE LIKE '%JUAL%' AND 
STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M011' AND
INTERNALDOCUMENT.EXTERNALREFERENCEDATE BETWEEN '$Awal' AND '$Akhir' AND NOT INTERNALDOCUMENTLINE.ORDERLINE IS NULL
GROUP BY
STOCKTRANSACTION.LOTCODE,
INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
INTERNALDOCUMENT.EXTERNALREFERENCE,
INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
INTERNALDOCUMENT.EXTERNALREFERENCEDATE,
INTERNALDOCUMENTLINE.ORDERLINE,
INTERNALDOCUMENTLINE.SUBCODE01,
INTERNALDOCUMENTLINE.SUBCODE02,
INTERNALDOCUMENTLINE.SUBCODE03,
INTERNALDOCUMENTLINE.SUBCODE04,
INTERNALDOCUMENTLINE.SUBCODE05,
INTERNALDOCUMENTLINE.SUBCODE06,
INTERNALDOCUMENTLINE.SUBCODE07,
INTERNALDOCUMENTLINE.SUBCODE08,
STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
STOCKTRANSACTION.LOGICALWAREHOUSECODE,
STOCKTRANSACTION.CREATIONUSER,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION,
ADSTORAGE.VALUESTRING,
ADSTORAGE2.VALUESTRING ";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
      $bon=trim($rowdb21['INTDOCUMENTPROVISIONALCODE'])."-".trim($rowdb21['ORDERLINE']);		
      if ($rowdb21['DESTINATIONWAREHOUSECODE'] =='M501' or $rowdb21['DESTINATIONWAREHOUSECODE'] =='M904') { $knitt = 'KNITTING ITTI ATAS- BENANG'; }
      else if($rowdb21['DESTINATIONWAREHOUSECODE'] ='P501'){ $knitt = 'KNITTING ITTI- BENANG'; }
      else if($rowdb21['DESTINATIONWAREHOUSECODE'] ='M051') { $knitt =  'KNITTING A- BENANG'; }
      else if($rowdb21['DESTINATIONWAREHOUSECODE'] ='P503') { $knitt =  'YARN DYE'; } 
	  $kdbenang=trim($rowdb21['SUBCODE01'])." ".trim($rowdb21['SUBCODE02'])." ".trim($rowdb21['SUBCODE03'])." ".trim($rowdb21['SUBCODE04'])." ".trim($rowdb21['SUBCODE05'])." ".trim($rowdb21['SUBCODE06'])." ".trim($rowdb21['SUBCODE07'])." ".trim($rowdb21['SUBCODE08']);				  
					  ?>
	  <tr>
	  <td style="text-align: center"><?php echo $no; ?></td>
	  <td style="text-align: center"><?php echo $rowdb21['EXTERNALREFERENCEDATE']; ?></td>
      <td style="text-align: center"><?php echo $bon; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['EXTERNALREFERENCE']; ?></td>
      <td align="left"><?php echo $rowdb21['ITEMDESCRIPTION']; ?></td> 
      <td style="text-align: center"><?php echo $rowdb21['SUPP'] ?></td>
      <td style="text-align: left"><?php echo $kdbenang; ?></td>
      <td style="text-align: left"><?php echo $rowdb21['SUMMARIZEDDESCRIPTION']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['SATUAN']; ?></td>
      <td style="text-align: right"><?php echo round($rowdb21['QTY_ROL']); ?></td>
      <td style="text-align: right"><?php echo round($rowdb21['QTY_CONES']); ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['QTY_KG'],2),2); ?></td>
      <td><?php echo $rowdb21['WHSLOCATIONWAREHOUSEZONECODE']."-".$rowdb21['WAREHOUSELOCATIONCODE']; ?></td>
      <td><?php echo $rowdb21['CREATIONUSER']; ?></td>
      </tr>
	  				  
	<?php 
	 $no++; 
	$totQTY+=round($rowdb21['QTY_ROL']);
	$totCON+=round($rowdb21['QTY_CONES']);
	$totKG+=round($rowdb21['QTY_KG'],2);	
	
	} ?>
				  </tbody>
                <tfoot>
				<tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: left">&nbsp;</td>
	    <td colspan="3" style="text-align: right"><strong>Total</strong></td>
	    <td style="text-align: right"><strong><?php echo $totQTY; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo $totCON; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format($totKG,2); ?></strong></td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>	
					</tfoot>  
                </table>
              </div>
              <!-- /.card-body -->
            </div> 
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Data Jual Benang RMP</h3>
				<a href="pages/cetak/cetaklapbjualrmp.php?tgl1=<?php echo $Awal; ?>&tgl2=<?php echo $Akhir; ?>" class="btn btn-sm btn-light float-right" target="_blank"><i class="fa fa-file"></i> to Print</a>  
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example3" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">Tgl Transaksi</th>
                    <th valign="middle" style="text-align: center">GoodIssue</th>
                    <th valign="middle" style="text-align: center">No BON</th>
                    <th valign="middle" style="text-align: center">No PO</th>
                    <th valign="middle" style="text-align: center">ItemDesc</th>
                    <th valign="middle" style="text-align: center">Supplier</th>
                    <th valign="middle" style="text-align: center">Code</th>
                    <th valign="middle" style="text-align: center">Jenis Benang</th>
                    <th valign="middle" style="text-align: center">Lot</th>
                    <th valign="middle" style="text-align: center">Satuan</th>
                    <th valign="middle" style="text-align: center">Qty</th>
                    <th valign="middle" style="text-align: center">Cones</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Block</th>
                    <th valign="middle" style="text-align: center">UserID</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					// Query awal  
	$sqlDB21 = " SELECT
	s.TRANSACTIONDATE,
	SUM(s.BASEPRIMARYQUANTITY) AS QTY_KG,
	COUNT(s.BASEPRIMARYQUANTITY) AS QTY_ROL,
	SUM(s.BASESECONDARYQUANTITY) AS QTY_CONES,
	s.LOTCODE,
	s.ORDERCODE,
	s.ORDERLINE,
	s.DECOSUBCODE01,
	s.DECOSUBCODE02,
	s.DECOSUBCODE03,
	s.DECOSUBCODE04,
	s.DECOSUBCODE05,
	s.DECOSUBCODE06,
	s.DECOSUBCODE07,
	s.DECOSUBCODE08,
	s.WHSLOCATIONWAREHOUSEZONECODE,
	s.WAREHOUSELOCATIONCODE,
	s.CREATIONUSER, 
	sl.ITEMDESCRIPTION,
	sl.EXTERNALREFERENCE,
	sd.GOODSISSUEDATE,
	f.SUMMARIZEDDESCRIPTION,
	b.LEGALNAME1
FROM
	STOCKTRANSACTION s
LEFT OUTER JOIN SALESDOCUMENTLINE sl ON
	sl.SALESDOCUMENTPROVISIONALCODE = s.ORDERCODE
	AND sl.ORDERLINE = s.ORDERLINE
LEFT OUTER JOIN SALESDOCUMENT sd ON
	sl.SALESDOCUMENTPROVISIONALCODE = sd.PROVISIONALCODE 
	AND sl.SALESDOCUMENTCOMPANYCODE = sd.COMPANYCODE  
	AND sl.SALDOCPROVISIONALCOUNTERCODE = sd.PROVISIONALCOUNTERCODE	
LEFT OUTER JOIN FULLITEMKEYDECODER f ON
	s.FULLITEMIDENTIFIER = f.IDENTIFIER
LEFT OUTER JOIN LOT lt ON
	s.LOTCODE =lt.CODE AND 
	s.COMPANYCODE = '100' AND
	s.ITEMTYPECODE = lt.ITEMTYPECODE AND
	s.DECOSUBCODE01 = lt.DECOSUBCODE01 AND
	s.DECOSUBCODE02 = lt.DECOSUBCODE02 AND
	s.DECOSUBCODE03 = lt.DECOSUBCODE03 AND
	s.DECOSUBCODE04 = lt.DECOSUBCODE04 AND
	s.DECOSUBCODE05 = lt.DECOSUBCODE05 AND
	s.DECOSUBCODE06 = lt.DECOSUBCODE06 AND
	s.DECOSUBCODE07 = lt.DECOSUBCODE07 AND
	s.DECOSUBCODE08 = lt.DECOSUBCODE08  
LEFT OUTER JOIN CUSTOMERSUPPLIERDATA cs ON
	cs.COMPANYCODE ='100' AND
	cs.TYPE ='2' AND
	cs.CODE = lt.SUPPLIERCODE 
LEFT OUTER JOIN BUSINESSPARTNER b ON
	b.NUMBERID = cs.BUSINESSPARTNERNUMBERID 
WHERE
	s.ORDERCOUNTERCODE = 'PCAPROV'
	AND s.ITEMTYPECODE = 'GYR'
	AND (s.LOGICALWAREHOUSECODE = 'M011' OR s.LOGICALWAREHOUSECODE = 'P501')
	AND s.TRANSACTIONDATE BETWEEN '$Awal' AND '$Akhir'
GROUP BY
	s.TRANSACTIONDATE,
	s.LOTCODE,
	s.ORDERCODE,
	s.ORDERLINE,
	s.DECOSUBCODE01,
	s.DECOSUBCODE02,
	s.DECOSUBCODE03,
	s.DECOSUBCODE04,
	s.DECOSUBCODE05,
	s.DECOSUBCODE06,
	s.DECOSUBCODE07,
	s.DECOSUBCODE08,
	s.WHSLOCATIONWAREHOUSEZONECODE,
	s.WAREHOUSELOCATIONCODE,
	s.CREATIONUSER, 
	sl.ITEMDESCRIPTION,
	sl.EXTERNALREFERENCE,
	sd.GOODSISSUEDATE,
	f.SUMMARIZEDDESCRIPTION,
	b.LEGALNAME1
	";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
      $bon=trim($rowdb21['ORDERCODE'])."-".trim($rowdb21['ORDERLINE']);		
      $kdbenang=trim($rowdb21['DECOSUBCODE01'])." ".trim($rowdb21['DECOSUBCODE02'])." ".trim($rowdb21['DECOSUBCODE03'])." ".trim($rowdb21['DECOSUBCODE04'])." ".trim($rowdb21['DECODECOSUBCODE05'])." ".trim($rowdb21['DECOSUBCODE06'])." ".trim($rowdb21['DECOSUBCODE07'])." ".trim($rowdb21['DECOSUBCODE08']);
  if($rowdb21['SATUAN']=="KARUNG"){
	  $stn="KARUNG";
  }else if($rowdb21['SATUAN']=="DUS"){
	  $stn="DUS";
  }else if($rowdb21['SATUAN']=="CONES"){
	  $stn="CONES";
  }	else{
		$stn="DUS";  
	  }	
					  ?>
	  <tr>
	  <td style="text-align: center"><?php echo $no; ?></td>
	  <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
	  <td style="text-align: center"><?php echo $rowdb21['GOODSISSUEDATE']; ?></td>
      <td style="text-align: center"><?php echo $bon; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['EXTERNALREFERENCE']; ?></td>
      <td align="left"><?php echo $rowdb21['ITEMDESCRIPTION']; ?></td> 
      <td style="text-align: center"><?php echo $rowdb21['LEGALNAME1'] ?></td>
      <td style="text-align: left"><?php echo $kdbenang; ?></td>
      <td style="text-align: left"><?php echo $rowdb21['SUMMARIZEDDESCRIPTION']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
      <td style="text-align: center"><?php echo $stn; ?></td>
      <td style="text-align: right"><?php echo round($rowdb21['QTY_ROL']); ?></td>
      <td style="text-align: right"><?php echo round($rowdb21['QTY_CONES']); ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['QTY_KG'],2),2); ?></td>
      <td><?php echo $rowdb21['WHSLOCATIONWAREHOUSEZONECODE']."-".$rowdb21['WAREHOUSELOCATIONCODE']; ?></td>
      <td><?php echo $rowdb21['CREATIONUSER']; ?></td>
      </tr>
	  				  
	<?php 
	 $no++; 
	$totQTY1+=round($rowdb21['QTY_ROL']);
	$totCON1+=round($rowdb21['QTY_CONES']);
	$totKG1+=round($rowdb21['QTY_KG'],2);	
	
	} ?>
				  </tbody>
                <tfoot>
				<tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: left">&nbsp;</td>
	    <td colspan="3" style="text-align: right"><strong>Total</strong></td>
	    <td style="text-align: right"><strong><?php echo $totQTY1; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo $totCON1; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format($totKG1,2); ?></strong></td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>	
					</tfoot>  
                </table>
              </div>
              <!-- /.card-body -->
            </div>
		<div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Detail Data Jual Benang M034</h3>
				<a href="pages/cetak/cetaklapbjualm034.php?tgl1=<?php echo $Awal; ?>&tgl2=<?php echo $Akhir; ?>" class="btn btn-sm btn-danger float-right" target="_blank"><i class="fa fa-file"></i> to Print</a>  
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1a" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
                  <thead>
                  <tr>
                    <th valign="middle" style="text-align: center">No</th>
                    <th valign="middle" style="text-align: center">Tgl Transaksi</th>
                    <th valign="middle" style="text-align: center">GoodIssue</th>
                    <th valign="middle" style="text-align: center">No BON</th>
                    <th valign="middle" style="text-align: center">No PO</th>
                    <th valign="middle" style="text-align: center">ItemDesc</th>
                    <th valign="middle" style="text-align: center">Supplier</th>
                    <th valign="middle" style="text-align: center">Code</th>
                    <th valign="middle" style="text-align: center">Jenis Benang</th>
                    <th valign="middle" style="text-align: center">Lot</th>
                    <th valign="middle" style="text-align: center">Satuan</th>
                    <th valign="middle" style="text-align: center">Qty</th>
                    <th valign="middle" style="text-align: center">Cones</th>
                    <th valign="middle" style="text-align: center">Berat/Kg</th>
                    <th valign="middle" style="text-align: center">Block</th>
                    <th valign="middle" style="text-align: center">UserID</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
	 
$no=1;   
$c=0;
					// Query awal  
	$sqlDB21 = " SELECT
	s.TRANSACTIONDATE,
	SUM(s.BASEPRIMARYQUANTITY) AS QTY_KG,
	COUNT(s.BASEPRIMARYQUANTITY) AS QTY_ROL,
	SUM(s.BASESECONDARYQUANTITY) AS QTY_CONES,
	s.LOTCODE,
	s.ORDERCODE,
	s.ORDERLINE,
	s.DECOSUBCODE01,
	s.DECOSUBCODE02,
	s.DECOSUBCODE03,
	s.DECOSUBCODE04,
	s.DECOSUBCODE05,
	s.DECOSUBCODE06,
	s.DECOSUBCODE07,
	s.DECOSUBCODE08,
	s.WHSLOCATIONWAREHOUSEZONECODE,
	s.WAREHOUSELOCATIONCODE,
	s.CREATIONUSER, 
	sl.ITEMDESCRIPTION,
	sl.EXTERNALREFERENCE,
	sd.GOODSISSUEDATE,
	f.SUMMARIZEDDESCRIPTION,
	b.LEGALNAME1
FROM
	STOCKTRANSACTION s
LEFT OUTER JOIN SALESDOCUMENTLINE sl ON
	sl.SALESDOCUMENTPROVISIONALCODE = s.ORDERCODE
	AND sl.ORDERLINE = s.ORDERLINE
LEFT OUTER JOIN SALESDOCUMENT sd ON
	sl.SALESDOCUMENTPROVISIONALCODE = sd.PROVISIONALCODE 
	AND sl.SALESDOCUMENTCOMPANYCODE = sd.COMPANYCODE  
	AND sl.SALDOCPROVISIONALCOUNTERCODE = sd.PROVISIONALCOUNTERCODE	
LEFT OUTER JOIN FULLITEMKEYDECODER f ON
	s.FULLITEMIDENTIFIER = f.IDENTIFIER
LEFT OUTER JOIN LOT lt ON
	s.LOTCODE =lt.CODE AND 
	s.COMPANYCODE = '100' AND
	s.ITEMTYPECODE = lt.ITEMTYPECODE AND
	s.DECOSUBCODE01 = lt.DECOSUBCODE01 AND
	s.DECOSUBCODE02 = lt.DECOSUBCODE02 AND
	s.DECOSUBCODE03 = lt.DECOSUBCODE03 AND
	s.DECOSUBCODE04 = lt.DECOSUBCODE04 AND
	s.DECOSUBCODE05 = lt.DECOSUBCODE05 AND
	s.DECOSUBCODE06 = lt.DECOSUBCODE06 AND
	s.DECOSUBCODE07 = lt.DECOSUBCODE07 AND
	s.DECOSUBCODE08 = lt.DECOSUBCODE08  
LEFT OUTER JOIN CUSTOMERSUPPLIERDATA cs ON
	cs.COMPANYCODE ='100' AND
	cs.TYPE ='2' AND
	cs.CODE = lt.SUPPLIERCODE 
LEFT OUTER JOIN BUSINESSPARTNER b ON
	b.NUMBERID = cs.BUSINESSPARTNERNUMBERID 
WHERE
	s.ORDERCOUNTERCODE IN ('PCAPROV','PODPROV') 
	AND s.ITEMTYPECODE = 'GYR'
	AND s.LOGICALWAREHOUSECODE = 'M034'
	AND s.TRANSACTIONDATE BETWEEN '$Awal' AND '$Akhir'
GROUP BY
	s.TRANSACTIONDATE,
	s.LOTCODE,
	s.ORDERCODE,
	s.ORDERLINE,
	s.DECOSUBCODE01,
	s.DECOSUBCODE02,
	s.DECOSUBCODE03,
	s.DECOSUBCODE04,
	s.DECOSUBCODE05,
	s.DECOSUBCODE06,
	s.DECOSUBCODE07,
	s.DECOSUBCODE08,
	s.WHSLOCATIONWAREHOUSEZONECODE,
	s.WAREHOUSELOCATIONCODE,
	s.CREATIONUSER, 
	sl.ITEMDESCRIPTION,
	sl.EXTERNALREFERENCE,
	sd.GOODSISSUEDATE,
	f.SUMMARIZEDDESCRIPTION,
	b.LEGALNAME1
	";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 
      $bon=trim($rowdb21['ORDERCODE'])."-".trim($rowdb21['ORDERLINE']);		
      $kdbenang=trim($rowdb21['DECOSUBCODE01'])." ".trim($rowdb21['DECOSUBCODE02'])." ".trim($rowdb21['DECOSUBCODE03'])." ".trim($rowdb21['DECOSUBCODE04'])." ".trim($rowdb21['DECODECOSUBCODE05'])." ".trim($rowdb21['DECOSUBCODE06'])." ".trim($rowdb21['DECOSUBCODE07'])." ".trim($rowdb21['DECOSUBCODE08']);
  if($rowdb21['SATUAN']=="KARUNG"){
	  $stn="KARUNG";
  }else if($rowdb21['SATUAN']=="DUS"){
	  $stn="DUS";
  }else if($rowdb21['SATUAN']=="CONES"){
	  $stn="CONES";
  }	else{
		$stn="DUS";  
	  }	
					  ?>
	  <tr>
	  <td style="text-align: center"><?php echo $no; ?></td>
	  <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
	  <td style="text-align: center"><?php echo $rowdb21['GOODSISSUEDATE']; ?></td>
      <td style="text-align: center"><?php echo $bon; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['EXTERNALREFERENCE']; ?></td>
      <td align="left"><?php echo $rowdb21['ITEMDESCRIPTION']; ?></td> 
      <td style="text-align: center"><?php echo $rowdb21['LEGALNAME1'] ?></td>
      <td style="text-align: left"><?php echo $kdbenang; ?></td>
      <td style="text-align: left"><?php echo $rowdb21['SUMMARIZEDDESCRIPTION']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
      <td style="text-align: center"><?php echo $stn; ?></td>
      <td style="text-align: right"><?php echo round($rowdb21['QTY_ROL']); ?></td>
      <td style="text-align: right"><?php echo round($rowdb21['QTY_CONES']); ?></td>
      <td style="text-align: right"><?php echo number_format(round($rowdb21['QTY_KG'],2),2); ?></td>
      <td><?php echo $rowdb21['WHSLOCATIONWAREHOUSEZONECODE']."-".$rowdb21['WAREHOUSELOCATIONCODE']; ?></td>
      <td><?php echo $rowdb21['CREATIONUSER']; ?></td>
      </tr>
	  				  
	<?php 
	 $no++; 
	$totQTY2+=round($rowdb21['QTY_ROL']);
	$totCON2+=round($rowdb21['QTY_CONES']);
	$totKG2+=round($rowdb21['QTY_KG'],2);	
	
	} ?>
				  </tbody>
                <tfoot>
				<tr>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td style="text-align: center">&nbsp;</td>
	    <td style="text-align: left">&nbsp;</td>
	    <td colspan="3" style="text-align: right"><strong>Total</strong></td>
	    <td style="text-align: right"><strong><?php echo $totQTY2; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo $totCON2; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format($totKG2,2); ?></strong></td>
	    <td>&nbsp;</td>
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
