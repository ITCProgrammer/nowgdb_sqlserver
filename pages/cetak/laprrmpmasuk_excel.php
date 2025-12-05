<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=benang_retur_rmp_masuk" . date($_GET['awal']) . ".xls"); //ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");
//disini script laporan anda
?>
<?php
$Awal = isset($_GET['awal']) ? $_GET['awal'] : '';
$Akhir = isset($_GET['akhir']) ? $_GET['akhir'] : '';
?>
<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
$tgl = date("Y-m-d");
?>

<div align="center">
  <h1>LAPORAN HARIAN RETUR MASUK BENANG</h1>
</div>
<div align="Right"> NO. FORM:<br />
  NO. REVISI:<br />
  TGL Terbit:<br />
</div>
<div align="LEFT">TGL :
  <?php echo date($_GET['awal']); ?> s/d
  <?php echo date($_GET['akhir']); ?>
</div>
<table width="100%" border="1" align="left">
  <tr>

    <td class="tombol">No</td>
    <td class="tombol">NO. SJ</td>
    <td class="tombol">KNIT</td>
    <td class="tombol">NO PO</td>
    <td class="tombol">CODE</td>
    <td class="tombol">SUPPLIER</td>
    <td class="tombol">JENIS BENANG</td>
    <td class="tombol">LOT</td>
    <td class="tombol">QTY</td>
    <td class="tombol">SATUAN</td>
    <td class="tombol">BERAT/Kg</td>
    <td class="tombol">BLOCK</td>
  </tr>
  <?php

  $sqlDB21 = " SELECT  x.TRANSACTIONNUMBER, a1.VALUESTRING AS KETS, a2.VALUESTRING AS SJ, a3.VALUESTRING AS KETSDYR, a4.VALUESTRING AS SJDYR, a5.VALUESTRING AS KNTDYR, a6.VALUESTRING AS KNTGYR, e.LEGALNAME1, SUM(x.BASESECONDARYQUANTITY) AS QTY_CONES, 
  SUM(x.BASEPRIMARYQUANTITY) AS QTY_KG, COUNT(x.ITEMELEMENTCODE) AS QTY_DUS, x.LOTCODE,bc.WHSLOCATIONWAREHOUSEZONECODE,bc.WAREHOUSELOCATIONCODE, x.TRANSACTIONDATE, f.SUMMARIZEDDESCRIPTION, x.ITEMTYPECODE, x.DECOSUBCODE01, x.DECOSUBCODE02, x.DECOSUBCODE03, x.DECOSUBCODE04,
x.DECOSUBCODE05, x.DECOSUBCODE06, x.DECOSUBCODE07, x.DECOSUBCODE08   
FROM DB2ADMIN.STOCKTRANSACTION x
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE a1 ON x.ABSUNIQUEID = a1.UNIQUEID AND a1.NAMENAME = 'KetSampleGYR'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE a2 ON x.ABSUNIQUEID = a2.UNIQUEID AND a2.NAMENAME = 'SuratJlnGYR'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE a3 ON x.ABSUNIQUEID = a3.UNIQUEID AND a3.NAMENAME = 'KetSampledyr'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE a4 ON x.ABSUNIQUEID = a4.UNIQUEID AND a4.NAMENAME = 'SuratJlndyr'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE a5 ON x.ABSUNIQUEID = a5.UNIQUEID AND a5.NAMENAME = 'KnittDYR'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE a6 ON x.ABSUNIQUEID = a6.UNIQUEID AND a6.NAMENAME = 'KnittGYR'
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
WHERE x.ITEMTYPECODE = 'GYR' AND x.TEMPLATECODE = 'OPN' AND (a1.VALUESTRING = '3' OR a3.VALUESTRING = '3') AND x.TRANSACTIONDATE BETWEEN '$Awal' AND '$Akhir'
GROUP BY x.TRANSACTIONNUMBER, a1.VALUESTRING, a2.VALUESTRING, a3.VALUESTRING, a4.VALUESTRING,a5.VALUESTRING,a6.VALUESTRING,  x.TRANSACTIONDATE, e.LEGALNAME1,
bc.WHSLOCATIONWAREHOUSEZONECODE,bc.WAREHOUSELOCATIONCODE, f.SUMMARIZEDDESCRIPTION, x.LOTCODE,x.ITEMTYPECODE, x.DECOSUBCODE01, x.DECOSUBCODE02, x.DECOSUBCODE03, x.DECOSUBCODE04,
x.DECOSUBCODE05, x.DECOSUBCODE06, x.DECOSUBCODE07, x.DECOSUBCODE08 ";
  $stmt1 = db2_exec($conn1, $sqlDB21, array('cursor' => DB2_SCROLLABLE));
  $no = 1;
  $knitt = "";

  $c = 0;
  $dustot = 0;
  $pltot = 0;
  $kartot = 0;
  $contot = 0;
  $totdus = 0;
  $totpl = 0;
  $totkar = 0;
  $totcon = 0;
  $totr	= 0;	
  while ($rowdb21 = db2_fetch_assoc($stmt1)) {
    $cd = trim($rowdb21['DECOSUBCODE01']) . "-" . trim($rowdb21['DECOSUBCODE02']) . "-"
              . trim($rowdb21['DECOSUBCODE03']) . "-" . trim($rowdb21['DECOSUBCODE04']) . "-"
              . trim($rowdb21['DECOSUBCODE05']) . "-" . trim($rowdb21['DECOSUBCODE06']) . "-"
              . trim($rowdb21['DECOSUBCODE07']) . "-" . trim($rowdb21['DECOSUBCODE08']) . "-"
              . trim($rowdb21['DECOSUBCODE09']) . "-" . trim($rowdb21['DECOSUBCODE10']);
    if (trim($rowdb21['WAREHOUSECODE']) == 'M904') {
      $knitt = 'LT2';
    } else if (trim($rowdb21['WAREHOUSECODE']) == 'P501') {
      $knitt = 'LT1';
    } else if (trim($rowdb21['WAREHOUSECODE']) == "P503") {
      $knitt = 'YND';
    }
			if ($rowdb21['USERSECONDARYUOMCODE'] == "BL") {
              $stn = "KARUNG";
			  $qtys= round($rowdb21['QTY_DUS']);		
            } else if($rowdb21['USERSECONDARYUOMCODE'] == "con"){
              $stn = "CONES";
			  $qtys= round($rowdb21['QTY_CONES']);	
            } else {
              $stn = "DUS";
			  $qtys= round($rowdb21['QTY_DUS']);	
            }

            $sqlDB211 = " SELECT a1.VALUESTRING AS PO FROM STOCKTRANSACTION s 
                          LEFT OUTER JOIN DB2ADMIN.ADSTORAGE a1 ON s.ABSUNIQUEID = a1.UNIQUEID AND a1.NAMENAME = 'SjPoGYR'
                          WHERE s.TRANSACTIONNUMBER ='$rowdb21[TRANSACTIONNUMBER]' 
                          ORDER BY a1.VALUESTRING ASC LIMIT 1 ";
            $stmt11 = db2_exec($conn1, $sqlDB211, array('cursor' => DB2_SCROLLABLE));
            $r1 = db2_fetch_assoc($stmt11);
	 		if($rowdb21['ITEMTYPECODE']=="GYR"){ $DSJ=$rowdb21['SJ']; $KNTTT=$rowdb21['KNTGYR']; }else if($rowdb21['ITEMTYPECODE']=="DYR"){  $DSJ=$rowdb21['SJDYR']; $KNTTT=$rowdb21['KNTDYR'];} 
    echo "<tr>
  	<td class='normal333'>$no</td>
	<td class='normal333'>" . $DSJ . "</td>
    <td class='normal333'>" . $KNTTT . "</td>
    <td class='normal333'>" . $r1['PO'] . "</td>
    <td class='normal333'>" . $cd . "</td>
    <td class='normal333'>" . $rowdb21['LEGALNAME1'] ."</td>
	<td class='normal333'>" . $rowdb21['SUMMARIZEDDESCRIPTION'] . "</td>
	<td class='normal333'>" . $rowdb21['LOTCODE'] . "</td>
	<td class='normal333'>" . $qtys . "</td>
	<td class='normal333'>$stn</td>
	<td class='normal333'>" . number_format(round($rowdb21['QTY_KG'], 2), 2) . "</td>
	<td class='normal333'>" . $rowdb21['WHSLOCATIONWAREHOUSEZONECODE'] . "-" . $rowdb21['WAREHOUSELOCATIONCODE'] . "</td>	
    </tr>";
    		if ($stn == 'DUS') {
              $dustot = $dustot + $rowdb21['QTY_DUS'];
              $totdus = $totdus + $rowdb21['QTY_KG'];
			  $totr = $totr + $rowdb21['QTY_DUS'];	
            }
            if ($stn == 'KARUNG') {
              $kartot = $kartot + $rowdb21['QTY_ROL'];
              $totkar = $totkar + $rowdb21['QTY_KG'];
			  $totr = $totr + $rowdb21['QTY_DUS'];	
            }
            if ($stn == 'PALET') {
              $pltot = $pltot + $rowdb21['QTY_ROL'];
              $totpl = $totpl + $rowdb21['QTY_KG'];
			  $totr = $totr + $rowdb21['QTY_DUS'];	
            }
            if ($stn == 'CONES') {
              $contot = $contot + $rowdb21['QTY_CONES'];
              $totcon = $totcon + $rowdb21['QTY_KG'];
			  $totr = $totr + $rowdb21['QTY_CONES'];	
            }
            $grand = $grand + $rowdb21['QTY_KG'];			
            $totqt = $totqt + $rowdb21['QTY_KG'];
            
            $no++;
  }
  ?>
  <tr>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <th align="right">&nbsp;</th>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
  </tr>
  <tr>

    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <th align="right"><b>TOTAL :</th>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">
      <?php echo $dustot; ?>
    </td>
    <td class="tombol">DUS</td>
    <td class="tombol">
      <?PHP echo $totdus; ?>
    </td>
    <td class="tombol">&nbsp;</td>
  </tr>
  <tr>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <th align="right">TOTAL :</th>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">
      <?php echo $kartot; ?>
    </td>
    <td class="tombol">KARUNG</td>
    <td class="tombol">
      <?PHP echo $totkar; ?>
    </td>
    <td class="tombol">&nbsp;</td>
  </tr>
  <tr>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <th align="right">TOTAL :</th>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">
      <?php echo $pltot; ?>
    </td>
    <td class="tombol">PALET</td>
    <td class="tombol">
      <?PHP echo $totpl; ?>
    </td>
    <td class="tombol">&nbsp;</td>
  </tr>
  <tr>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <th align="right">TOTAL :</th>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">
      <?PHP echo $contot; ?>
    </td>
    <td class="tombol">CONES</td>
    <td class="tombol">
      <?PHP echo $totcon; ?>
    </td>
    <td class="tombol">&nbsp;</td>
  </tr>
  <tr>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <th align="right">GRAND TOTAL :</th>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">
      <?php echo $totr; ?>
    </td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">
      <?php echo $totqt; ?>
    </td>
    <td class="tombol">&nbsp;</td>
  </tr>
  <tr>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <th align="right">&nbsp;</th>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
  </tr>
  <tr>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <th align="right">&nbsp;</th>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
  </tr>
  <tr>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <th align="right">&nbsp;</th>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
  </tr>
  <tr>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">Nama</td>
    <td class="tombol">Dibuat Oleh</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">DiPeriksa Oleh</td>
    <th align="right">&nbsp;</th>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">Mengetahui</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
  </tr>
  <tr>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">Jabatan</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <th align="right">&nbsp;</th>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
  </tr>
  <tr>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">Tanggal</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <th align="right">&nbsp;</th>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
  </tr>
  <tr>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">Tanda Tangan</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <th align="right">&nbsp;</th>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
  </tr>
</table>