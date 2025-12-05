<?php
$Awal  = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir  = isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
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
              <input name="tgl_awal" value="<?php echo $Awal; ?>" type="text" class="form-control form-control-sm" id="" autocomplete="off" required>
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
              <input name="tgl_akhir" value="<?php echo $Akhir; ?>" type="text" class="form-control form-control-sm" id="" autocomplete="off" required>
            </div>
          </div>
        </div>

        <button class="btn btn-info" type="submit">Cari Data</button>
      </div>
      <!-- /.card-body -->
    </div>

    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Detail Data Benang Supplier</h3>
        <a href="pages/cetak/cetaklapmasuk.php?tgl1=<?php echo $Awal; ?>&tgl2=<?php echo $Akhir; ?>" class="btn btn-sm btn-light float-right" target="_blank"><i class="fa fa-file"></i> to Print</a>
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
              <th valign="middle" style="text-align: center">Trn. No</th>
            </tr>
          </thead>
          <tbody>
            <?php

            $no = 1;
            $c = 0;
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
              m.CHALLANNO,
              m.MRNDATE,
              m.PURCHASEORDERCODE
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
              m.CHALLANDATE BETWEEN '$Awal' AND '$Akhir'
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
              m.PURCHASEORDERCODE,
              m.MRNDATE,
              b.LEGALNAME1,
              x.ORDERLINE,
              x.ORDERCODE
            ORDER BY
              x.TRANSACTIONNUMBER ASC";

            $stmt1   = db2_exec($conn1, $sqlDB21, array('cursor' => DB2_SCROLLABLE));

            $dataMain = [];
            $transactionNumbers = [];

            while ($row = db2_fetch_assoc($stmt1)) {
              $key = trim($row['TRANSACTIONNUMBER']) . '-' . trim($row['LOTCODE']);
              $dataMain[trim($key)] = $row;
              $transactionNumbers[] = "'" . trim($row['TRANSACTIONNUMBER']) . "'";
            }

            $transactionNumbers = array_unique($transactionNumbers);
            
            if (!empty($transactionNumbers)) {
              $inClause = implode(",", $transactionNumbers);

              $sqlDBLKS = "
                  SELECT
                      y.TRANSACTIONNUMBER,
                      y.LOTCODE,
                      LISTAGG(
                          DISTINCT CONCAT(TRIM(WHSLOCATIONWAREHOUSEZONECODE), CONCAT('-', TRIM(WAREHOUSELOCATIONCODE))),
                          ', '
                      ) AS LOKASI
                  FROM (
                      SELECT
                          x.TRANSACTIONNUMBER,
                          x.LOTCODE,
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
                              AND x.ITEMTYPECODE = 'GYR'
                              AND x.TEMPLATECODE = '302'
                              AND x.ORDERCOUNTERCODE IS NULL
                              AND x.ORDERCODE IS NULL
                              AND x.ORDERLINE = '0'
                          ORDER BY
                              TRANSACTIONTIME DESC
                      ) z ON x.ITEMELEMENTCODE = z.ITEMELEMENTCODE
                      WHERE
                          x.LOGICALWAREHOUSECODE = 'M011'
                          AND x.ITEMTYPECODE = 'GYR'
                          AND x.TRANSACTIONNUMBER IN ($inClause)
                  ) y
                  GROUP BY y.TRANSACTIONNUMBER, y.LOTCODE
              ";

              $stmtLKS  = db2_exec($conn1, $sqlDBLKS, array('cursor' => DB2_SCROLLABLE));

              if (!$stmtLKS) {
                echo "DB2 Exec Error: " . db2_stmt_errormsg();
                exit;
              }

              while ($row = db2_fetch_assoc($stmtLKS)) {
                $key = trim($row['TRANSACTIONNUMBER']) . '-' . trim($row['LOTCODE']);
                if (isset($dataMain[$key])) {
                  $dataMain[$key]['LOKASI'] = $row['LOKASI'];
                }
              }
            }

            foreach ($dataMain as $rowdb21) {
              $cd = trim($rowdb21['DECOSUBCODE01']) . "-" . trim($rowdb21['DECOSUBCODE02']) . "-"
                . trim($rowdb21['DECOSUBCODE03']) . "-" . trim($rowdb21['DECOSUBCODE04']) . "-"
                . trim($rowdb21['DECOSUBCODE05']) . "-" . trim($rowdb21['DECOSUBCODE06']) . "-"
                . trim($rowdb21['DECOSUBCODE07']) . "-" . trim($rowdb21['DECOSUBCODE08']) . "-"
                . trim($rowdb21['DECOSUBCODE09']) . "-" . trim($rowdb21['DECOSUBCODE10']);

              if ($rowdb21['DESTINATIONWAREHOUSECODE'] == 'M904') {
                $knitt = 'KNITTING ITTI ATAS- BENANG';
              } else if ($rowdb21['DESTINATIONWAREHOUSECODE'] = 'P501') {
                $knitt = 'KNITTING ITTI- BENANG';
              } else if ($rowdb21['DESTINATIONWAREHOUSECODE'] = 'M051') {
                $knitt =  'KNITTING A- BENANG';
              } else if ($rowdb21['DESTINATIONWAREHOUSECODE'] = 'P503') {
                $knitt =  'YARN DYE';
              }

              $sqlDB22 = "SELECT COUNT(BASEPRIMARYQUANTITYUNIT) AS ROL,SUM(BASEPRIMARYQUANTITYUNIT) AS BERAT,BALANCE.LOTCODE  
                FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION LEFT OUTER JOIN 
                DB2ADMIN.BALANCE  BALANCE ON BALANCE.ELEMENTSCODE =STOCKTRANSACTION.ITEMELEMENTCODE  
                WHERE STOCKTRANSACTION.LOGICALWAREHOUSECODE='M011' AND STOCKTRANSACTION.ORDERCODE='$rowdb21[PURCHASEORDERCODE]'
                AND STOCKTRANSACTION.ORDERLINE ='$rowdb21[ORDERLINE]' AND STOCKTRANSACTION.TRANSACTIONNUMBER='$rowdb21[TRANSACTIONNUMBER]' 
                AND STOCKTRANSACTION.LOTCODE='$rowdb21[LOTCODE]'
                GROUP BY BALANCE.LOTCODE ";
              $stmt2 = db2_exec($conn1, $sqlDB22, array('cursor' => DB2_SCROLLABLE));
              $rowdb22 = db2_fetch_assoc($stmt2);
            ?>
              <tr>
                <td style="text-align: center"><?php echo $no; ?></td>
                <td style="text-align: center"><?php echo $rowdb21['CHALLANDATE']; ?></td>
                <td style="text-align: center"><?php echo $rowdb21['ORDERCODE'] . "-" . $rowdb21['ORDERLINE']; ?></td>
                <td><?php echo $rowdb21['CHALLANNO']; ?></td>
                <td><?php echo $cd; ?></td>
                <td style="text-align: left"><?php echo $rowdb21['LEGALNAME1']; ?></td>
                <td style="text-align: left">
                  <?php echo $rowdb21['SUMMARIZEDDESCRIPTION']; // echo $rowdb21['ITEMDESCRIPTION']; 
                  ?>
                </td>
                <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
                <td style="text-align: right"><?php echo $rowdb21['QTY_DUS']; ?></td>
                <td style="text-align: right"><?php echo round($rowdb21['QTY_CONES']); ?></td>
                <td style="text-align: right">
                  <?php echo number_format(round($rowdb21['QTY_KG'], 2), 2); ?>
                </td>
                <td><?php echo $rowdb21['LOKASI']; ?></td>
                <td><a href="#"
                    id="<?php echo trim($rowdb21['PURCHASEORDERCODE']) . "-" . trim($rowdb21['TRANSACTIONNUMBER']) . "-" . trim($rowdb21['MRNDATE']) . "-" . trim($rowdb21['ORDERLINE']) . "-" . trim($rowdb22['LOTCODE']); ?>"
                    class="show_detail">
                    <?php echo $rowdb21['TRANSACTIONNUMBER']; ?>
                  </a></td>
              </tr>
            <?php
              $tCONES += $rowdb21['QTY_CONES'];
              $tDUS += $rowdb21['QTY_DUS'];
              $tKG += $rowdb21['QTY_KG'];

              $no++;
            } ?>
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
                  echo number_format($tKG, 2);
                  ?>
                </strong></td>
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
        <h3 class="card-title">Detail Data Benang Sample</h3>
        <a href="pages/cetak/cetaklapmasuksample.php?tgl1=<?php echo $Awal; ?>&tgl2=<?php echo $Akhir; ?>" class="btn btn-sm btn-success float-right" target="_blank"><i class="fa fa-file"></i> to Print</a>
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
              <th valign="middle" style="text-align: center">Satuan</th>
              <th valign="middle" style="text-align: center">Berat/Kg</th>
              <th valign="middle" style="text-align: center">Block</th>
            </tr>
          </thead>
          <tbody>
            <?php

            $no = 1;
            $c = 0;
            $total_qty = null;

            /* View yang ke 4 ini fungsinya untuk memfilter data di beberapa tabel yang tidak ada dalam tabel tersebut, 
      jadi terfilter data yang tidak ada agar tidak tampil */

            $sqlDB21 = " SELECT  x.TRANSACTIONNUMBER, x.TEMPLATECODE, m.PACKINGUMCODE,a1.VALUESTRING AS KETS, a2.VALUESTRING AS SJ, a3.VALUESTRING AS POBENANG, e.LEGALNAME1, SUM(x.BASESECONDARYQUANTITY) AS QTY_CONES, 
              SUM(x.BASEPRIMARYQUANTITY) AS QTY_KG, COUNT(x.ITEMELEMENTCODE) AS QTY_DUS, x.LOTCODE,bc.WHSLOCATIONWAREHOUSEZONECODE,bc.WAREHOUSELOCATIONCODE, x.TRANSACTIONDATE, f.SUMMARIZEDDESCRIPTION, x.ITEMTYPECODE, x.DECOSUBCODE01, x.DECOSUBCODE02, x.DECOSUBCODE03, x.DECOSUBCODE04,
              x.DECOSUBCODE05, x.DECOSUBCODE06, x.DECOSUBCODE07, x.DECOSUBCODE08, x.ORDERCOUNTERCODE, x.ORDERCODE   
              FROM DB2ADMIN.STOCKTRANSACTION x
              LEFT OUTER JOIN DB2ADMIN.MRNDETAIL m ON x.TRANSACTIONNUMBER =m.TRANSACTIONNUMBER
              LEFT OUTER JOIN DB2ADMIN.ADSTORAGE a1 ON x.ABSUNIQUEID = a1.UNIQUEID AND a1.NAMENAME = 'KetSampleGYR'
              LEFT OUTER JOIN DB2ADMIN.ADSTORAGE a2 ON x.ABSUNIQUEID = a2.UNIQUEID AND a2.NAMENAME = 'SuratJlnGYR'
              LEFT OUTER JOIN DB2ADMIN.ADSTORAGE a3 ON x.ABSUNIQUEID = a3.UNIQUEID AND a3.NAMENAME = 'SjPoGYR'
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
              WHERE x.ITEMTYPECODE = 'GYR'  AND x.LOGICALWAREHOUSECODE='M011' AND ((x.TEMPLATECODE = 'OPN' AND a1.VALUESTRING = '1') OR x.TEMPLATECODE = '101') AND x.TRANSACTIONDATE BETWEEN '$Awal' AND '$Akhir'
              GROUP BY x.TRANSACTIONNUMBER, x.TEMPLATECODE, m.PACKINGUMCODE, a1.VALUESTRING, a2.VALUESTRING, a3.VALUESTRING, x.TRANSACTIONDATE,e.LEGALNAME1,
              bc.WHSLOCATIONWAREHOUSEZONECODE,bc.WAREHOUSELOCATIONCODE, f.SUMMARIZEDDESCRIPTION, x.LOTCODE,x.ITEMTYPECODE, x.DECOSUBCODE01, x.DECOSUBCODE02, x.DECOSUBCODE03, x.DECOSUBCODE04,
              x.DECOSUBCODE05, x.DECOSUBCODE06, x.DECOSUBCODE07, x.DECOSUBCODE08, x.ORDERCOUNTERCODE, x.ORDERCODE ";

            $stmt1   = db2_exec($conn1, $sqlDB21, array('cursor' => DB2_SCROLLABLE));
            //}				  
            while ($rowdb21 = db2_fetch_assoc($stmt1)) {

              // $cd=trim($rowdb21['SUBCODE01'])."-".trim($rowdb21['SUBCODE02'])."-".trim($rowdb21['SUBCODE03'])."-".trim($rowdb21['SUBCODE04'])."-".trim($rowdb21['SUBCODE05'])."-".trim($rowdb21['SUBCODE06'])."-".trim($rowdb21['SUBCODE07'])."-".trim($rowdb21['SUBCODE08'])."-".trim($rowdb21['SUBCODE09'])."-".trim($rowdb21['SUBCODE10']);		
              if ($rowdb21r['PACKINGUMCODE'] == "BGS") {
                $stn = "KARUNG";
              } else if ($rowdb21['PACKINGUMCODE'] == "BX") {
                $stn = "DUS";
              } else if ($rowdb21['PACKINGUMCODE'] == "con") {
                $stn = "CONES";
              } else {
                $stn = "DUS";
              }
              $cd = trim($rowdb21['DECOSUBCODE01']) . "-" . trim($rowdb21['DECOSUBCODE02']) . "-"
                . trim($rowdb21['DECOSUBCODE03']) . "-" . trim($rowdb21['DECOSUBCODE04']) . "-"
                . trim($rowdb21['DECOSUBCODE05']) . "-" . trim($rowdb21['DECOSUBCODE06']) . "-"
                . trim($rowdb21['DECOSUBCODE07']) . "-" . trim($rowdb21['DECOSUBCODE08']) . "-"
                . trim($rowdb21['DECOSUBCODE09']) . "-" . trim($rowdb21['DECOSUBCODE10']);

              if ($rowdb21['DESTINATIONWAREHOUSECODE'] == 'M904') {
                $knitt = 'KNITTING ITTI ATAS- BENANG';
              } else if ($rowdb21['DESTINATIONWAREHOUSECODE'] = 'P501') {
                $knitt = 'KNITTING ITTI- BENANG';
              } else if ($rowdb21['DESTINATIONWAREHOUSECODE'] = 'M051') {
                $knitt =  'KNITTING A- BENANG';
              } else if ($rowdb21['DESTINATIONWAREHOUSECODE'] = 'P503') {
                $knitt =  'YARN DYE';
              }

              $sqlDB22SJ = "SELECT x.CHALLANNO, x.PURCHASEORDERCODE FROM DB2ADMIN.MRNHEADER x
                WHERE PURCHASEORDERCOUNTERCODE = '" . $rowdb21['ORDERCOUNTERCODE'] . "' AND PURCHASEORDERCODE ='" . $rowdb21['ORDERCODE'] . "' ";
              $stmt2SJ = db2_exec($conn1, $sqlDB22SJ, array('cursor' => DB2_SCROLLABLE));
              $rowdb22SJ = db2_fetch_assoc($stmt2SJ);
              if (!empty($rowdb21['SJ']) and $rowdb21['TEMPLATECODE'] == "OPN") {
                $sj = $rowdb21['SJ'];
                $po = $rowdb21['POBENANG'];
              } else if (!empty($rowdb21['SJ']) and $rowdb21['TEMPLATECODE'] != "OPN") {
                $sj = "SAMPLE";
                $po = $rowdb21['ORDERCODE'] . "-" . $rowdb21['ORDERLINE'];
              } else {
                $sj = $rowdb22SJ['CHALLANNO'];
                $po = $rowdb22SJ['PURCHASEORDERCODE'];
              }
            ?>
              <tr>
                <td style="text-align: center"><?php echo $no; ?></td>
                <td style="text-align: center"><?php echo $rowdb21['TRANSACTIONDATE']; ?></td>
                <td style="text-align: center"><?php echo $po; ?></td>

                <td><?php echo $sj ?></td>
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
                <td style="text-align: center"><?Php echo $stn; ?></td>
                <td style="text-align: right">
                  <?php
                  echo number_format(round($rowdb21['QTY_KG'], 2), 2);
                  ?>
                </td>
                <td><?php echo $rowdb21['WHSLOCATIONWAREHOUSEZONECODE'] . "-" . $rowdb21['WAREHOUSELOCATIONCODE']; ?></td>
              </tr>
            <?php
              $tCONES1 += $rowdb21['QTY_CONES'];
              $tDUS1 += $rowdb21['QTY_DUS'];
              $tKG1 += $rowdb21['QTY_KG'];

              $no++;
            } ?>
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
              <td style="text-align: right">&nbsp;</td>
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
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Detail Data Benang Warna</h3>
        <a href="pages/cetak/cetaklapMasukW.php?tgl1=<?php echo $Awal; ?>&tgl2=<?php echo $Akhir; ?>"
          class="btn btn-sm btn-danger float-right mx-1" target="_blank"><i class="fa fa-file"></i> to Print</a>

      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="example4" class="table table-sm table-bordered table-striped"
          style="font-size: 13px; text-align: center;">
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
              x.ORDERLINE=s.ORDERLINE AND x.ITEMTYPEAFICODE =s.ITEMTYPECODE AND x.INTDOCPROVISIONALCOUNTERCODE =s.ORDERCOUNTERCODE
              LEFT OUTER JOIN FULLITEMKEYDECODER f ON
              s.FULLITEMIDENTIFIER = f.IDENTIFIER
              LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID =x.ABSUNIQUEID AND a.NAMENAME ='SuppName'
              WHERE 
			  -- x.CONDITIONRETRIEVINGDATE BETWEEN '$Awal' AND '$Akhir' AND 
              s.TRANSACTIONDATE BETWEEN '$Awal' AND '$Akhir' AND
			  NOT x.EXTERNALREFERENCE LIKE '%RETUR%' AND			  
			  -- x.INTERNALREFERENCE IS NULL AND
              x.ITEMTYPEAFICODE ='DYR' AND
              s.LOGICALWAREHOUSECODE='M011' AND
              NOT x.ORDERLINE IS NULL AND 
              (INTDOCPROVISIONALCOUNTERCODE='I02P50' OR INTDOCPROVISIONALCOUNTERCODE='I02M90')
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
              $sqlDB22 = " 
                SELECT
                x.WHSLOCATIONWAREHOUSEZONECODE,
                x.WAREHOUSELOCATIONCODE
              FROM
                DB2ADMIN.STOCKTRANSACTION x
              WHERE
                ORDERCODE = '" . $rowdb21['INTDOCUMENTPROVISIONALCODE'] . "'
                AND ORDERLINE = '" . $rowdb21['ORDERLINE'] . "'
                AND (TOKENCODE = 'RECEIPT' or TOKENCODE IS NULL)
                AND TRANSACTIONDATE = '" . $rowdb21['TRANSACTIONDATE'] . "'
                AND LOGICALWAREHOUSECODE ='M011' ";
              $stmt2 = db2_exec($conn1, $sqlDB22, array('cursor' => DB2_SCROLLABLE));
              $rowdb22 = db2_fetch_assoc($stmt2);
            ?>
              <tr>
                <td style="text-align: center">
                  <?php echo $no; ?>
                </td>
                <td style="text-align: center">
                  <?php echo $rowdb21['TRANSACTIONDATE']; ?>
                </td>
                <td style="text-align: center">
                  <?php echo $bon; ?>
                </td>
                <td style="text-align: center">
                  <?php echo $rowdb21['EXTERNALREFERENCE']; ?>
                </td>
                <td style="text-align: left">
                  <?php echo $rowdb21['ITEMDESCRIPTION']; ?>
                </td>
                <td style="text-align: left">
                  <?php if ($rowdb21['SUPPLIER'] != "") {
                    echo $rowdb21['SUPPLIER'];
                  } else {
                    echo "YND-ITTI";
                  } ?>
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
              $tQty11 += $rowdb21['QTY_ROL'];
              $tCones11 += $rowdb21['QTY_CONES'];
              $tKG11 += $rowdb21['QTY_KG'];
              $no++;
            } ?>
          </tbody>
          <tfoot>
            <tr>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: center">&nbsp;</td>
              <td style="text-align: left">&nbsp;</td>
              <td style="text-align: left">&nbsp;</td>
              <td style="text-align: left">&nbsp;</td>
              <td colspan="2" style="text-align: left"><strong>Total</strong></td>
              <td style="text-align: right"><strong>
                  <?php echo round($tQty11); ?>
                </strong></td>
              <td style="text-align: right"><strong>
                  <?php echo round($tCones11); ?>
                </strong></td>
              <td style="text-align: right"><strong>
                  <?php echo number_format(round($tKG11, 2), 2); ?>
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
<div id="DetailShow" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
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
  $(function() {
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