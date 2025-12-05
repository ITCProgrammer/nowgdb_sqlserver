<?php
$Awal	= isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir	= isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
$LOT 	= isset($_POST['lot']) ? $_POST['lot'] : '';
?>
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-default">
      <div class="card-header">
        <h3 class="card-title">Filter Identifikasi Benang Per Lot</h3>

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
          <label for="lot" class="col-md-1">Lot</label>
          <div class="col-md-4">
            <input type="text" class="form-control form-control-sm" value="<?php echo $LOT; ?>" name="lot"
              placeholder="Lot Benang" required>
          </div>
        </div>


        <button class="btn btn-info" type="submit">Cari Data</button>
      </div>
      <!-- /.card-body -->
    </div>  
			
		<div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Detail Data Masuk Benang Supplier</h3>		  
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
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
			  
$sqlDB21 = "SELECT
	x.TRANSACTIONNUMBER,
	x.DECOSUBCODE01,
	x.DECOSUBCODE02,
	x.DECOSUBCODE03,
	x.DECOSUBCODE04,
	x.DECOSUBCODE05,
	x.DECOSUBCODE06,
	x.DECOSUBCODE07,
	x.DECOSUBCODE08,
	x.DECOSUBCODE09,
	x.DECOSUBCODE10,
	x.ITEMDESCRIPTION,
	x.USERPRIMARYUOMCODE,
	f.SUMMARIZEDDESCRIPTION,
	COUNT(x.ITEMELEMENTCODE) AS QTY_DUS,
	SUM(x.BASEPRIMARYQUANTITY) AS QTY_KG,
	b.LEGALNAME1,
	SUM(x.BASESECONDARYQUANTITY) AS QTY_CONES,
	x.TRANSACTIONDATE,
	m.CHALLANDATE,
	x.TOKENCODE,
	x.ORDERCOUNTERCODE,
	x.ORDERCODE,
	x.ORDERLINE,
	x.LOTCODE,
	m.CHALLANNO
FROM
	DB2ADMIN.STOCKTRANSACTION x
LEFT OUTER JOIN DB2ADMIN.MRNDETAIL m2 ON
	m2.TRANSACTIONNUMBER = x.TRANSACTIONNUMBER
LEFT OUTER JOIN DB2ADMIN.MRNHEADER m ON
	m.CODE = m2.MRNHEADERCODE
LEFT OUTER JOIN DB2ADMIN.CUSTOMERSUPPLIERDATA n ON
	x.SUPPLIERCODE = n.CODE
LEFT OUTER JOIN DB2ADMIN.BUSINESSPARTNER b ON
	b.NUMBERID = n.BUSINESSPARTNERNUMBERID
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER f ON
	x.FULLITEMIDENTIFIER = f.IDENTIFIER
WHERE
	x.LOTCODE = '$LOT'
	AND (ORDERCOUNTERCODE = 'POYRL'
		OR ORDERCOUNTERCODE = 'POYRI')
	AND x.TOKENCODE = 'RECEIPT'
GROUP BY
	x.TRANSACTIONNUMBER,
	x.DECOSUBCODE01,
	x.DECOSUBCODE02,
	x.DECOSUBCODE03,
	x.DECOSUBCODE04,
	x.DECOSUBCODE05,
	x.DECOSUBCODE06,
	x.DECOSUBCODE07,
	x.DECOSUBCODE08,
	x.DECOSUBCODE09,
	x.DECOSUBCODE10,
	x.ITEMDESCRIPTION,
	x.USERPRIMARYUOMCODE,
	f.SUMMARIZEDDESCRIPTION,
	x.TRANSACTIONDATE,
	x.TOKENCODE,
	x.ORDERCOUNTERCODE,
	x.LOTCODE,
	m.CHALLANNO,
	m.CHALLANDATE ,
	b.LEGALNAME1,
	x.ORDERLINE,
	x.ORDERCODE
ORDER BY
	x.TRANSACTIONNUMBER ASC";

  $stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){ 	
      
  $cd = trim($rowdb21['DECOSUBCODE01'])."-".trim($rowdb21['DECOSUBCODE02'])."-"
        .trim($rowdb21['DECOSUBCODE03'])."-".trim($rowdb21['DECOSUBCODE04'])."-"
        .trim($rowdb21['DECOSUBCODE05'])."-".trim($rowdb21['DECOSUBCODE06'])."-"
        .trim($rowdb21['DECOSUBCODE07'])."-".trim($rowdb21['DECOSUBCODE08'])."-"
        .trim($rowdb21['DECOSUBCODE09'])."-".trim($rowdb21['DECOSUBCODE10']);	
      
    if ($rowdb21['DESTINATIONWAREHOUSECODE'] =='M904') { $knitt = 'KNITTING ITTI ATAS- BENANG'; }
    else if($rowdb21['DESTINATIONWAREHOUSECODE'] ='P501') { $knitt = 'KNITTING ITTI- BENANG'; }
    else if($rowdb21['DESTINATIONWAREHOUSECODE'] ='M051') { $knitt =  'KNITTING A- BENANG'; }
    else if($rowdb21['DESTINATIONWAREHOUSECODE'] ='P503') { $knitt =  'YARN DYE'; } 
		
$sqlDBLKS = " 
	SELECT
		y.TRANSACTIONNUMBER,
		LISTAGG(DISTINCT CONCAT(TRIM(WHSLOCATIONWAREHOUSEZONECODE), CONCAT('-', TRIM(WAREHOUSELOCATIONCODE))) ,
		', ') AS LOKASI
	FROM
		(
		SELECT
			x.TRANSACTIONNUMBER,
			x.ITEMELEMENTCODE,
			CASE
				WHEN NOT z.WHSLOCATIONWAREHOUSEZONECODE = '' THEN z.WHSLOCATIONWAREHOUSEZONECODE
				ELSE x.WHSLOCATIONWAREHOUSEZONECODE
			END AS WHSLOCATIONWAREHOUSEZONECODE,
			CASE
				WHEN NOT z.WAREHOUSELOCATIONCODE = '' THEN z.WAREHOUSELOCATIONCODE
				ELSE x.WAREHOUSELOCATIONCODE
			END AS WAREHOUSELOCATIONCODE
		FROM
			DB2ADMIN.STOCKTRANSACTION x
		LEFT JOIN (
			SELECT
				x.WHSLOCATIONWAREHOUSEZONECODE,
				x.WAREHOUSELOCATIONCODE,
				x.ITEMELEMENTCODE
			FROM
				DB2ADMIN.STOCKTRANSACTION x
			WHERE
				x.LOGICALWAREHOUSECODE = 'M011'
				AND 
	x.ITEMTYPECODE = 'GYR'
				AND 
	x.TEMPLATECODE = '302'
				AND 
	x.ORDERCOUNTERCODE IS NULL
				AND 
	x.ORDERCODE IS NULL
				AND 
	x.ORDERLINE = '0'
			ORDER BY
				TRANSACTIONTIME DESC ) z 	
ON
			x.ITEMELEMENTCODE = z.ITEMELEMENTCODE
		WHERE
			x.LOGICALWAREHOUSECODE = 'M011'
			AND 
			x.ITEMTYPECODE = 'GYR'
			AND
			x.TRANSACTIONNUMBER='$rowdb21[TRANSACTIONNUMBER]'
) y
	GROUP BY
		y.TRANSACTIONNUMBER

";
$stmtLKS	= db2_exec($conn1,$sqlDBLKS, array('cursor'=>DB2_SCROLLABLE));		
$rowdbLKS	= db2_fetch_assoc($stmtLKS);
    ?>
	  <tr>
	    <td style="text-align: center"><?php echo $no;?></td> 
	    <td style="text-align: center"><?php echo $rowdb21['CHALLANDATE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['ORDERCODE']."-".$rowdb21['ORDERLINE']; ?></td>
      <td><?php echo $rowdb21['CHALLANNO']; ?></td>
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
      <td><?php echo $rowdbLKS['LOKASI']; ?></td>
      </tr>				  
	<?php 
		$tCONES+=$rowdb21['QTY_CONES'];
		$tDUS+=$rowdb21['QTY_DUS'];
		$tKG+=$rowdb21['QTY_KG'];
		
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
          echo number_format($tKG,2);
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
                <h3 class="card-title">Data Masuk Benang Retur</h3>	
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1a" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
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
STOCKTRANSACTION.LOTCODE = '$LOT' 
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
<div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Data Masuk Benang Retur dari Supplier</h3>		   
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="example1b" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
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
WHERE x.ITEMTYPECODE = 'GYR' AND x.TEMPLATECODE = 'OPN' AND a1.VALUESTRING = '2' AND x.LOTCODE = '$LOT'
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
		$tCONES2+=$rowdb21['QTY_CONES'];
		$tDUS2+=$rowdb21['QTY_DUS'];
		$tKG2+=$rowdb21['QTY_KG'];
		
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
          echo $tDUS2;
        ?>
	    </strong></td>
	    <td style="text-align: right"><strong>
	      <?php 
          echo $tCONES2;
        ?>
	    </strong></td>
	    <td style="text-align: right"><strong>
	      <?php 
          echo $tKG2;
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
        <h3 class="card-title">Data Masuk Benang Retur dari Supplier (RMP)</h3>	   
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="example1g" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
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
WHERE x.ITEMTYPECODE = 'GYR' AND x.TEMPLATECODE = 'OPN' AND a1.VALUESTRING = '3' AND x.LOTCODE = '$LOT'
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
		$tCONES3+=$rowdb21['QTY_CONES'];
		$tDUS3+=$rowdb21['QTY_DUS'];
		$tKG3+=$rowdb21['QTY_KG'];
		
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
          echo $tDUS3;
        ?>
	    </strong></td>
	    <td style="text-align: right"><strong>
	      <?php 
          echo $tCONES3;
        ?>
	    </strong></td>
	    <td style="text-align: right"><strong>
	      <?php 
          echo $tKG3;
        ?>
	    </strong></td>
	    <td>&nbsp;</td>
	    </tr>		
   </tfoot>               
                </table>
              </div>
              <!-- /.card-body -->
            </div>			
		<div class="card card-danger">
      <div class="card-header">
        <h3 class="card-title">Detail Data Keluar Benang </h3>        
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="example1c" class="table table-sm table-bordered table-striped"
          style="font-size: 13px; text-align: center;">
          <thead>
            <tr>
              <th valign="middle" style="text-align: center">No</th>
              <th valign="middle" style="text-align: center">Tgl</th>
              <th valign="middle" style="text-align: center">No BON</th>
              <th valign="middle" style="text-align: center">KNITT</th>
              <th valign="middle" style="text-align: center">No PO</th>
              <th valign="middle" style="text-align: center">ItemDesc</th>
              <th valign="middle" style="text-align: center">Supplier</th>
              <th valign="middle" style="text-align: center">Code</th>
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

            $no = 1;
            $c = 0;
            // Query awal  
            $sqlDB21 = " SELECT 
x.INTDOCUMENTPROVISIONALCODE,
x.ORDERLINE,
x.EXTERNALREFERENCE,
x.ITEMDESCRIPTION,
s.LOTCODE,
SUM(s.BASEPRIMARYQUANTITY) AS QTY_KG,
COUNT(s.BASEPRIMARYQUANTITY) AS QTY_ROL,
SUM(s.BASESECONDARYQUANTITY) AS QTY_CONES,
x.SUBCODE01,
x.SUBCODE02,
x.SUBCODE03,
x.SUBCODE04,
x.SUBCODE05,
x.SUBCODE06,
x.SUBCODE07,
x.SUBCODE08,
x.CONDITIONRETRIEVINGDATE,
TRIM(x.DESTINATIONWAREHOUSECODE) AS DESTINATIONWAREHOUSECODE,
s.TRANSACTIONDATE,
s.CREATIONUSER,
s.LOGICALWAREHOUSECODE,
s.WHSLOCATIONWAREHOUSEZONECODE,
s.WAREHOUSELOCATIONCODE,
a.VALUESTRING AS SUPPLIER,
f.SUMMARIZEDDESCRIPTION
FROM DB2ADMIN.INTERNALDOCUMENTLINE x
LEFT OUTER JOIN STOCKTRANSACTION s ON x.INTDOCUMENTPROVISIONALCODE=s.ORDERCODE AND 
x.ORDERLINE=s.ORDERLINE 
LEFT OUTER JOIN FULLITEMKEYDECODER f ON
s.FULLITEMIDENTIFIER = f.IDENTIFIER
LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID =x.ABSUNIQUEID AND a.NAMENAME ='SuppName'
WHERE 
s.LOTCODE = '$LOT' AND 
x.ITEMTYPEAFICODE ='GYR' AND
s.LOGICALWAREHOUSECODE='M011' AND 
NOT x.EXTERNALREFERENCE LIKE '%RETUR%' AND 
NOT x.ORDERLINE IS NULL
GROUP BY 
x.INTDOCUMENTPROVISIONALCODE,
x.ORDERLINE,
x.EXTERNALREFERENCE,
x.ITEMDESCRIPTION,
s.LOTCODE,
x.SUBCODE01,
x.SUBCODE02,
x.SUBCODE03,
x.SUBCODE04,
x.SUBCODE05,
x.SUBCODE06,
x.SUBCODE07,
x.SUBCODE08,
x.CONDITIONRETRIEVINGDATE,
x.DESTINATIONWAREHOUSECODE,
s.TRANSACTIONDATE,
s.CREATIONUSER,
s.LOGICALWAREHOUSECODE,
s.WHSLOCATIONWAREHOUSEZONECODE,
s.WAREHOUSELOCATIONCODE,
f.SUMMARIZEDDESCRIPTION,
a.VALUESTRING 
ORDER BY x.INTDOCUMENTPROVISIONALCODE,x.ORDERLINE ASC";
            $stmt1 = db2_exec($conn1, $sqlDB21, array('cursor' => DB2_SCROLLABLE));
            //}				  
            while ($rowdb21 = db2_fetch_assoc($stmt1)) {
              $bon = $rowdb21['INTDOCUMENTPROVISIONALCODE'] . "-" . $rowdb21['ORDERLINE'];
              ;
              if ($rowdb21['DESTINATIONWAREHOUSECODE'] == "M904") {
                $knitt = 'KNITTING ITTI ATAS- BENANG';
              } else if ($rowdb21['DESTINATIONWAREHOUSECODE'] == "P501") {
                $knitt = 'KNITTING ITTI- BENANG';
              } else if ($rowdb21['DESTINATIONWAREHOUSECODE'] == "M051") {
                $knitt = 'KNITTING A- BENANG';
              } else if ($rowdb21['DESTINATIONWAREHOUSECODE'] == "P503") {
                $knitt = 'YARN DYE';
              } else if ($rowdb21['DESTINATIONWAREHOUSECODE'] == '') {
                $knitt = 'RMP';
              }
              $kdbenang = trim($rowdb21['SUBCODE01']) . " " . trim($rowdb21['SUBCODE02']) . " " . trim($rowdb21['SUBCODE03']) . " " . trim($rowdb21['SUBCODE04']) . " " . trim($rowdb21['SUBCODE05']) . " " . trim($rowdb21['SUBCODE06']) . " " . trim($rowdb21['SUBCODE07']) . " " . trim($rowdb21['SUBCODE08']);
              $sqlDB22 = " SELECT x.WHSLOCATIONWAREHOUSEZONECODE,x.WAREHOUSELOCATIONCODE FROM DB2ADMIN.STOCKTRANSACTION x
WHERE ORDERCODE ='" . $rowdb21['INTDOCUMENTPROVISIONALCODE'] . "' and 
ORDERLINE='" . $rowdb21['ORDERLINE'] . "' and TOKENCODE IS NULL and 
TRANSACTIONDATE ='" . $rowdb21['TRANSACTIONDATE'] . "' ";
              $stmt2 = db2_exec($conn1, $sqlDB22, array('cursor' => DB2_SCROLLABLE));
              $rowdb22 = db2_fetch_assoc($stmt2);
              ?>
              <tr>
                <td style="text-align: center">
                  <?php echo $no; ?>
                </td>
                <td style="text-align: center">
                  <?php echo $rowdb21['CONDITIONRETRIEVINGDATE']; ?>
                </td>
                <td style="text-align: center">
                  <?php echo $bon; ?>
                </td>
                <td style="text-align: center">
                  <?php echo $knitt; ?>
                </td>
                <td style="text-align: center">
                  <?php echo $rowdb21['EXTERNALREFERENCE']; ?>
                </td>
                <td style="text-align: left">
                  <?php echo $rowdb21['ITEMDESCRIPTION']; ?>
                </td>
                <td style="text-align: left">
                  <?php echo $rowdb21['SUPPLIER']; ?>
                </td>
                <td style="text-align: left">
                  <?php echo $kdbenang; ?>
                </td>
                <td style="text-align: left">
                  <?php echo $rowdb21['SUMMARIZEDDESCRIPTION']; ?>
                </td>
                <td style="text-align: center">
                  <?php echo $rowdb21['LOTCODE']; ?>
                </td>
                <td style="text-align: right">
                  <?php echo round($rowdb21['QTY_ROL']); ?>
                </td>
                <td style="text-align: right">
                  <?php echo round($rowdb21['QTY_CONES']); ?>
                </td>
                <td style="text-align: right">
                  <?php echo number_format(round($rowdb21['QTY_KG'], 2), 2); ?>
                </td>
                <td>
                  <?php echo $rowdb22['WHSLOCATIONWAREHOUSEZONECODE'] . "-" . $rowdb22['WAREHOUSELOCATIONCODE']; ?>
                </td>
              </tr>
              <?php
              $tQty7 += $rowdb21['QTY_ROL'];
              $tCones7 += $rowdb21['QTY_CONES'];
              $tKG7 += $rowdb21['QTY_KG'];
              $no++;
            } ?>
          </tbody>
          <tfoot>
            <tr>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: left">&nbsp;</td>
              <td style="text-align: left">&nbsp;</td>
              <td style="text-align: left">&nbsp;</td>
              <td colspan="2" style="text-align: left"><strong>Total</strong></td>
              <td style="text-align: right"><strong>
                  <?php echo round($tQty7); ?>
                </strong></td>
              <td style="text-align: right"><strong>
                  <?php echo round($tCones7); ?>
                </strong></td>
              <td style="text-align: right"><strong>
                  <?php echo number_format(round($tKG7, 2), 2); ?>
                </strong></td>
              <td>&nbsp;</td>
            </tr>
          </tfoot>
        </table>
      </div>
      <!-- /.card-body -->
    </div>	    
<div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Detail Data Jual Benang</h3>				  
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1e" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
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
STOCKTRANSACTION.LOTCODE = '$LOT' AND NOT INTERNALDOCUMENTLINE.ORDERLINE IS NULL
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
	$totQTY5+=round($rowdb21['QTY_ROL']);
	$totCON5+=round($rowdb21['QTY_CONES']);
	$totKG5+=round($rowdb21['QTY_KG'],2);	
	
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
	    <td style="text-align: right"><strong><?php echo $totQTY5; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo $totCON5; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format($totKG5,2); ?></strong></td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>	
					</tfoot>  
                </table>
              </div>
              <!-- /.card-body -->
            </div>	
<div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Detail Data Jual Benang RMP</h3>				
          </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1f" class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
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
	l.SUPPLIERCODE,
	b.LEGALNAME1,
	f.SUMMARIZEDDESCRIPTION
FROM
	STOCKTRANSACTION s
LEFT OUTER JOIN SALESDOCUMENTLINE sl ON
	sl.SALESDOCUMENTPROVISIONALCODE = s.ORDERCODE
	AND sl.ORDERLINE = s.ORDERLINE
LEFT OUTER JOIN LOT l ON
	l.CODE = s.LOTCODE
LEFT OUTER JOIN CUSTOMERSUPPLIERDATA c ON
	c.CODE = l.SUPPLIERCODE
LEFT OUTER JOIN BUSINESSPARTNER b ON
	b.NUMBERID = c.BUSINESSPARTNERNUMBERID
LEFT OUTER JOIN FULLITEMKEYDECODER f ON
	s.FULLITEMIDENTIFIER = f.IDENTIFIER
WHERE
	s.ORDERCOUNTERCODE = 'PCAPROV'
	AND s.ITEMTYPECODE = 'GYR'
	AND s.LOGICALWAREHOUSECODE = 'M011'
	AND s.LOTCODE = '$LOT'
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
	l.SUPPLIERCODE,
	b.LEGALNAME1,
	f.SUMMARIZEDDESCRIPTION
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
	$totQTY6+=round($rowdb21['QTY_ROL']);
	$totCON6+=round($rowdb21['QTY_CONES']);
	$totKG6+=round($rowdb21['QTY_KG'],2);	
	
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
	    <td style="text-align: right"><strong><?php echo $totQTY6; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo $totCON6; ?></strong></td>
	    <td style="text-align: right"><strong><?php echo number_format($totKG6,2); ?></strong></td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>	
					</tfoot>  
                </table>
              </div>
              <!-- /.card-body -->
            </div>			
		  </form>
			<?php 
			$sisaBenangDus=($tDUS + $tDUS1 + $tDUS2 + $tDUS3)-($tQty7 + $totQTY5 + $totQTY6);
			$sisaBenang=($tKG + $tKG1 + $tKG2 + $tKG3) - ($tKG7 + $totKG5 + $totKG6); 
		  	?>	
		<div class="row">
			<div class="col-md-4">	
		<div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Sisa Data Benang</h3>
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
               <label for="sisa" class="col-md-5">Sisa Greige</label>
			   <div class="col-md-2">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo $sisaBenangDus; ?>" name="sisarol" style="text-align: right" readonly>
			   </div>			
               <div class="col-md-3">  
                 <input type="text" class="form-control form-control-sm" value="<?php echo round($sisaBenang,3); ?>" name="sisa" style="text-align: right" readonly>
			   </div>
			  <strong> Kgs</strong>	  
            </div>					
					</div> 
              <!-- /.card-body -->
            </div>
		 </div>
			</div>		  
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
