<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
$tgl = date("Y-m-d");

$Awal  = isset($_GET['tgl1']) ? $_GET['tgl1'] : '';
$Akhir  = isset($_GET['tgl2']) ? $_GET['tgl2'] : '';
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
                <font size="+1">LAPORAN HARIAN KELUAR BENANG</font>
                <br>
                FW-19-GDB-12 / 02
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
	border-right:0px #000 solid;">TGL: <?php echo date('d F Y', strtotime($Awal)); ?></td>
          </tr>
          <tr>
            <td class="tombol">NO</td>
            <td class="tombol">NO. BON</td>
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
          $no = 1;
          $c = 0;
          $dustot = 0;
          $pltot = 0;
          $kartot = 0;
          $contot = 0;
          $totdus = 0;
          $totpl = 0;
          $totkar = 0;
          $totcon = 0;
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
            s.USERSECONDARYUOMCODE,
            a.VALUESTRING AS SUPPLIER,
            f.SUMMARIZEDDESCRIPTION
            FROM DB2ADMIN.INTERNALDOCUMENTLINE x
            LEFT OUTER JOIN STOCKTRANSACTION s ON x.INTDOCUMENTPROVISIONALCODE=s.ORDERCODE AND 
            x.ORDERLINE=s.ORDERLINE AND s.TOKENCODE IS NULL
            LEFT OUTER JOIN FULLITEMKEYDECODER f ON
            s.FULLITEMIDENTIFIER = f.IDENTIFIER
            LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID =x.ABSUNIQUEID AND a.NAMENAME ='SuppName'
            WHERE 
            s.TRANSACTIONDATE BETWEEN '$_GET[tgl1]' AND '$_GET[tgl2]' AND 
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
            s.USERSECONDARYUOMCODE,
            f.SUMMARIZEDDESCRIPTION,
            a.VALUESTRING 
            ORDER BY x.INTDOCUMENTPROVISIONALCODE,x.ORDERLINE ASC";
          $stmt1   = db2_exec($conn1, $sqlDB21, array('cursor' => DB2_SCROLLABLE));
          //}				  
          while ($rowdb21 = db2_fetch_assoc($stmt1)) {
            $bon = $rowdb21['INTDOCUMENTPROVISIONALCODE'] . "-" . $rowdb21['ORDERLINE'];;
            if ($rowdb21['DESTINATIONWAREHOUSECODE'] == 'M904') {
              $knitt = 'KNITTING ITTI ATAS- BENANG';
            } else if ($rowdb21['DESTINATIONWAREHOUSECODE'] == 'P501') {
              $knitt = 'KNITTING ITTI- BENANG';
            } else if ($rowdb21['DESTINATIONWAREHOUSECODE'] == 'M051') {
              $knitt =  'KNITTING A- BENANG';
            } else if ($rowdb21['DESTINATIONWAREHOUSECODE'] == 'P503') {
              $knitt =  'YARN DYE';
            } else if ($rowdb21['DESTINATIONWAREHOUSECODE'] == '') {
              $knitt =  'RMP';
            }
            $kdbenang = trim($rowdb21['SUBCODE01']) . " " . trim($rowdb21['SUBCODE02']) . " " . trim($rowdb21['SUBCODE03']) . " " . trim($rowdb21['SUBCODE04']) . " " . trim($rowdb21['SUBCODE05']) . " " . trim($rowdb21['SUBCODE06']) . " " . trim($rowdb21['SUBCODE07']) . " " . trim($rowdb21['SUBCODE08']);
            if ($rowdb21['USERSECONDARYUOMCODE'] == "BL") {
              $stn = "KARUNG";
            } else {
              $stn = "DUS";
            }
            echo "<tr>
              <td >$no</td>
              <td >$bon</td>
              <td >$knitt</td>
              <td >$rowdb21[EXTERNALREFERENCE]</td>
              <td >$kdbenang</td>
              <td >$rowdb21[SUPPLIER]</td>
              <td >$rowdb21[SUMMARIZEDDESCRIPTION]</td>
              <td >$rowdb21[LOTCODE]</td>
              <td >" . round($rowdb21['QTY_ROL']) . "</td>
              <td >$stn</td>
              <td align=right>" . number_format(round($rowdb21['QTY_KG'], 2), 2) . "</td>
              <td >" . $rowdb21['WHSLOCATIONWAREHOUSEZONECODE'] . "-" . $rowdb21['WAREHOUSELOCATIONCODE'] . "</td>
            </tr>";
            if ($stn == 'DUS') {
              $dustot = $dustot + $rowdb21['QTY_ROL'];
              $totdus = $totdus + $rowdb21['QTY_KG'];
            };
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
            $totr = $totr + $rowdb21['QTY_ROL'];
            $no++;
          }
          ?>
          <?php for ($i = $no; $i <= 35; $i++) { ?>
            <td class="tombol"><?php echo $i; ?></td>
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
  <td class="tombol"><?php echo $dustot; ?></td>
  <td class="tombol">DUS</td>
  <td class="tombol" align="right"><?PHP echo number_format($totdus, '2', '.', ',') . $r['berat']; ?></td>
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
  <td class="tombol"><?php echo $kartot; ?></td>
  <td class="tombol">KARUNG</td>
  <td class="tombol" align="right"><?PHP echo number_format($totkar, '2', '.', ','); ?></td>
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
  <td class="tombol"><?php echo $pltot; ?></td>
  <td class="tombol">PALET</td>
  <td class="tombol" align="right"><?PHP echo number_format($totpl, '2', '.', ','); ?></td>
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
  <td class="tombol"><?PHP echo $contot; ?></td>
  <td class="tombol">CONES</td>
  <td class="tombol" align="right"><?PHP echo number_format($totcon, '2', '.', ','); ?></td>
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
  <td class="tombol"><?PHP echo $totr; ?></td>
  <td class="tombol">&nbsp;</td>
  <td class="tombol" align="right"><?php $grand = $totdus + $totpl + $totkar + $totcon;
                                    echo number_format($grand, '2', '.', ','); ?></td>
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