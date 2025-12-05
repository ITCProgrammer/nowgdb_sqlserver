<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=benang_retur_masuk" . date($_GET['awal']) . ".xls"); //ganti nama sesuai keperluan
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
    <td class="tombol">NO S. JALAN</td>
    <td class="tombol">KNIT</td>
    <td class="tombol">NO. PO</td>
    <td class="tombol">CODE</td>
    <td class="tombol">SUPPLIER</td>
    <td class="tombol">JENIS BENANG</td>
    <td class="tombol">LOT</td>
    <td class="tombol">QTY</td>
    <td class="tombol">SATUAN</td>
    <td class="tombol">BERAT/Kg</td>
    <td class="tombol">BLOCK</td>
    <td class="tombol">NOTE</td>
    <td class="tombol">TGL MASUK</td>
  </tr>
  <?php

  $sqlDB21 = " SELECT 
INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE,
INTERNALDOCUMENTLINE.ORDERLINE,
INTERNALDOCUMENTLINE.EXTERNALREFERENCE,
INTERNALDOCUMENTLINE.ITEMDESCRIPTION,
INTERNALDOCUMENTLINE.WAREHOUSECODE,
STOCKTRANSACTION.LOTCODE,  
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
STOCKTRANSACTION.USERSECONDARYUOMCODE,
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
WHERE (INTERNALDOCUMENTLINE.EXTERNALREFERENCE='RETUR' OR INTERNALDOCUMENTLINE.EXTERNALREFERENCE='RETURAN') AND STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M011' AND INTERNALDOCUMENTLINE.ITEMTYPEAFICODE='GYR' AND
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
STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
STOCKTRANSACTION.TRANSACTIONDATE,
STOCKTRANSACTION.LOGICALWAREHOUSECODE,
STOCKTRANSACTION.CREATIONUSER,
STOCKTRANSACTION.USERSECONDARYUOMCODE,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION";
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
    $bon = $rowdb21['INTDOCUMENTPROVISIONALCODE'] . "-" . $rowdb21['ORDERLINE'];
    if (trim($rowdb21['WAREHOUSECODE']) == 'M904') {
      $knitt = 'LT2';
    } else if (trim($rowdb21['WAREHOUSECODE']) == 'P501') {
      $knitt = 'LT1';
    } else if (trim($rowdb21['WAREHOUSECODE']) == "P503") {
      $knitt = 'YND';
    }
			if ($rowdb21['USERSECONDARYUOMCODE'] == "BL") {
              $stn = "KARUNG";
//			} else if($rowdb21['USERSECONDARYUOMCODE'] == "con"){
//			  $stn = "CONES";
            } else {
              $stn = "DUS";
            }  
    echo "<tr>
  	<td class='normal333'>$no</td>
	<td class='normal333'>" . $bon . "</td>
    <td class='normal333'>$knitt</td>
    <td class='normal333'>" . $rowdb21['EXTERNALREFERENCE'] . "</td>
    <td class='normal333'>" . $rowdb21['ITEMDESCRIPTION'] . "</td>
    <td class='normal333'></td>
	<td class='normal333'>" . $rowdb21['SUMMARIZEDDESCRIPTION'] . "</td>
	<td class='normal333'>" . $rowdb21['LOTCODE'] . "</td>
	<td class='normal333'>" . $rowdb21['QTY_ROL'] . "</td>
	<td class='normal333'>$stn</td>
	<td class='normal333'>" . number_format(round($rowdb21['QTY_KG'], 2), 2) . "</td>
	<td class='normal333'>" . $rowdb21['WHSLOCATIONWAREHOUSEZONECODE'] . "-" . $rowdb21['WAREHOUSELOCATIONCODE'] . "</td>
	<td class='normal333'></td>
	<td class='normal333'>" . $rowdb21['TRANSACTIONDATE'] . "</td>
    </tr>";
    		if ($stn == 'DUS') {
              $dustot = $dustot + $rowdb21['QTY_ROL'];
              $totdus = $totdus + $rowdb21['QTY_KG'];
            }
            if ($stn == 'KARUNG') {
              $kartot = $kartot + $rowdb21['QTY_ROL'];
              $totkar = $totkar + $rowdb21['QTY_KG'];
            }
            if ($stn == 'PALET') {
              $pltot = $pltot + $rowdb21['QTY_ROL'];
              $totpl = $totpl + $rowdb21['QTY_KG'];
            }
            if ($stn == 'CONES') {
              $contot = $contot + $rowdb21['QTY_ROL'];
              $totcon = $totcon + $rowdb21['QTY_KG'];
            }
            $grand = $grand + $rowdb21['QTY_KG'];

            $totqt = $totqt + $rowdb21['QTY_KG'];
            $totr = $totr + $rowdb21['QTY_ROL'];

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
    <td class="tombol">&nbsp;</td>
    <td class="tombol">&nbsp;</td>
  </tr>
</table>