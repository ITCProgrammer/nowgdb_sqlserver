<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
$tgl = date("Y-m-d");
?>
<?php
$Awal = isset($_GET['awal']) ? $_GET['awal'] : '';
$Akhir = isset($_GET['akhir']) ? $_GET['akhir'] : '';
?>
<style type="text/css">
  .tombolkanan {
    text-align: right;
  }

  input {
    text-align: center;
    border: hidden;
  }

  @media print {
    ::-webkit-input-placeholder {
      /* WebKit browsers */
      color: transparent;
    }

    :-moz-placeholder {
      /* Mozilla Firefox 4 to 18 */
      color: transparent;
    }

    ::-moz-placeholder {
      /* Mozilla Firefox 19+ */
      color: transparent;
    }

    :-ms-input-placeholder {
      /* Internet Explorer 10+ */
      color: transparent;
    }

    .pagebreak {
      page-break-before: always;
    }

    .header {
      display: block
    }

    table thead {
      display: table-header-group;
    }
  }
</style>
<link href="styles_cetak.css" rel="stylesheet" type="text/css">
<table width="100%" border="0" style="width:9.50in;">
  <thead>
    <tr>
      <td>
        <table width="100%" border="0" class="table-list1">
          <tr>
            <td width="5%"><img src="Indo.jpg" alt="" width="50" height="50"></td>
            <td>
              <div align="center">
                <font size="+1"><strong>LAPORAN HARIAN RETUR MASUK BENANG</strong></font><br>
                FW-19-GDB-11 / 03
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </thead>
  <tr>
    <td>
      <table width="100%" border="0" class="table-list1">
        <thead>
          <tr>
            <td colspan="12" class="tombol" style="border-bottom:0px #000 solid;
  border-top:0px #000 solid;
  border-left:0px #000 solid;
  border-right:0px #000 solid;">TGL:
              <?php echo date('d F Y', strtotime($_GET['awal'])); ?> s/d
              <?php echo date('d F Y', strtotime($_GET['akhir'])); ?>
            </td>
          </tr>
          <tr>
            <td class="tombol">NO</td>
            <td class="tombol">NO. SJ</td>
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
LOT.SUPPLIERCODE,
BUSINESSPARTNER.LEGALNAME1,
STOCKTRANSACTION.WHSLOCATIONWAREHOUSEZONECODE,
STOCKTRANSACTION.WAREHOUSELOCATIONCODE,
STOCKTRANSACTION.TRANSACTIONDATE,
STOCKTRANSACTION.LOGICALWAREHOUSECODE,
STOCKTRANSACTION.CREATIONUSER,
STOCKTRANSACTION.USERSECONDARYUOMCODE,
FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION ";

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
            } else if (trim($rowdb21['WAREHOUSECODE']) == "P503" or trim($rowdb21['WAREHOUSECODE']) == "P504") {
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
  	<td >$no</td>
    <td >$bon</td>
    <td >$knitt</td>
    <td >" . $rowdb21['EXTERNALREFERENCE'] . "</td>
    <td >" . $rowdb21['ITEMDESCRIPTION'] . "</td>
    <td >" . $rowdb21['LEGALNAME1'] . "</td>
	<td >" . $rowdb21['SUMMARIZEDDESCRIPTION'] . "</td>
	<td >" . $rowdb21['LOTCODE'] . "</td>
	<td >" . $rowdb21['QTY_ROL'] . "</td>
	<td >$stn</td>
	<td align=right>" . number_format(round($rowdb21['QTY_KG'], 2), 2) . "</td>
	<td >" . $rowdb21['WHSLOCATIONWAREHOUSEZONECODE'] . "-" . $rowdb21['WAREHOUSELOCATIONCODE'] . "</td>
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
          <?php for ($i = $no; $i <= 35; $i++) { ?>
            <tr>
              <td class="tombol">
                <?php echo $i; ?>
              </td>
              <td class="tombol">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
              <td align="right">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
              <td class="tombol">&nbsp;</td>
            </tr>
          <?php } ?>
          <tr>
            <td class="tombol">&nbsp;</td>
            <td class="tombol">&nbsp;</td>
            <td class="tombol">&nbsp;</td>
            <td class="tombol">&nbsp;</td>
            <td class="tombol">&nbsp;</td>
            <td class="tombol">&nbsp;</td>
            <td align="right"><b>TOTAL :</td>
            <td class="tombol">&nbsp;</td>
            <td class="tombol">
              <?php echo $dustot; ?>
            </td>
            <td class="tombol">DUS</td>
            <td class="tombol" align="right">
              <?PHP echo number_format($totdus, '2', '.', ',') . $r['berat']; ?>
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
            <td align="right">TOTAL :</td>
            <td class="tombol">&nbsp;</td>
            <td class="tombol">
              <?php echo $kartot; ?>
            </td>
            <td class="tombol">KARUNG</td>
            <td class="tombol" align="right">
              <?PHP echo number_format($totkar, '2', '.', ','); ?>
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
            <td align="right">TOTAL :</td>
            <td class="tombol">&nbsp;</td>
            <td class="tombol">
              <?php echo $pltot; ?>
            </td>
            <td class="tombol">PALET</td>
            <td class="tombol" align="right">
              <?PHP echo number_format($totpl, '2', '.', ','); ?>
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
            <td align="right">TOTAL :</td>
            <td class="tombol">&nbsp;</td>
            <td class="tombol">
              <?PHP echo $contot; ?>
            </td>
            <td class="tombol">CONES</td>
            <td class="tombol" align="right">
              <?PHP echo number_format($totcon, '2', '.', ','); ?>
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
            <td align="right">GRAND TOTAL :</td>
            <td class="tombol">&nbsp;</td>
            <td class="tombol">
              <?php echo $totr; ?>
            </td>
            <td class="tombol">&nbsp;</td>
            <td class="tombol" align="right">
              <?php echo number_format($totqt, '2', '.', ','); ?>
            </td>
            <td class="tombol">&nbsp;</td>
          </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" class="table-list1">
        <tr>
          <td width="15%">&nbsp;</td>
          <td width="31%">
            <div align="center">DIBUAT OLEH:</div>
          </td>
          <td width="27%">
            <div align="center">DIPERIKSA OLEH:</div>
          </td>
          <td width="27%">
            <div align="center">DIKETAHUI OLEH:</div>
          </td>
        </tr>
        <tr>
          <td>NAMA</td>
          <td>
            <div align="center">
              <input name=nama type=text placeholder="Ketik disini" size="33" maxlength="30">
            </div>
          </td>
          <td>
            <div align="center">
              <input name=nama3 type=text placeholder="Ketik disini" size="33" maxlength="30">
            </div>
          </td>
          <td>
            <div align="center">
              <input name=nama5 type=text placeholder="Ketik disini" size="33" maxlength="30">
            </div>
          </td>
        </tr>
        <tr>
          <td>JABATAN</td>
          <td>
            <div align="center">
              <input name=nama2 type=text placeholder="Ketik disini" size="33" maxlength="30">
            </div>
          </td>
          <td>
            <div align="center">
              <input name=nama4 type=text placeholder="Ketik disini" size="33" maxlength="30">
            </div>
          </td>
          <td>
            <div align="center">
              <input name=nama6 type=text placeholder="Ketik disini" size="33" maxlength="30">
            </div>
          </td>
        </tr>
        <tr>
          <td>TANGGAL</td>
          <td>
            <div align="center">
              <input type="text" name="date" placeholder="dd-mm-yyyy" onKeyUp="
  var date = this.value;
  if (date.match(/^\d{2}$/) !== null) {
     this.value = date + '-';
  } else if (date.match(/^\d{2}\-\d{2}$/) !== null) {
     this.value = date + '-';
  }" maxlength="10">
            </div>
          </td>
          <td>
            <div align="center">
              <input type="text" name="date" placeholder="dd-mm-yyyy" onKeyUp="
  var date = this.value;
  if (date.match(/^\d{2}$/) !== null) {
     this.value = date + '-';
  } else if (date.match(/^\d{2}\-\d{2}$/) !== null) {
     this.value = date + '-';
  }" maxlength="10">
            </div>
          </td>
          <td>
            <div align="center">
              <input type="text" name="date" placeholder="dd-mm-yyyy" onKeyUp="
  var date = this.value;
  if (date.match(/^\d{2}$/) !== null) {
     this.value = date + '-';
  } else if (date.match(/^\d{2}\-\d{2}$/) !== null) {
     this.value = date + '-';
  }" maxlength="10">
            </div>
          </td>
        </tr>
        <tr>
          <td height="60" valign="top">TANDA TANGAN</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
  </tbody>
</table>