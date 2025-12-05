<!-- Main content -->
<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Stock Benang</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <table id="example1" class="table table-sm table-bordered table-striped" style="font-size:13px;">
        <thead>
          <tr>
            <th style="text-align: center">Tipe</th>
            <th style="text-align: center">Tanggal</th>
            <th style="text-align: center">Code</th>
            <th style="text-align: center">Jenis Benang</th>
            <th style="text-align: center">Lot</th>
            <th style="text-align: center">SupplierCode</th>
            <th style="text-align: center">Supplier</th>
            <th style="text-align: center">Warna</th>
            <th style="text-align: center">No. PO</th>
            <th style="text-align: center">Grade</th>
            <th style="text-align: center">Weight</th>
            <th style="text-align: center">Qty</th>
            <th style="text-align: center">Cones</th>
            <th style="text-align: center">Zone</th>
            <th style="text-align: center">Lokasi</th>
            <th style="text-align: center">Terima</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          $c = 0;
          $sqlDB21 = "SELECT 
                  c.SUPPLIERCODE,
                  c.LOTCREATIONORDERNUMBER, 
                  e.LEGALNAME1, 
                  b.LOTCODE,
                  s.TRANSACTIONNUMBER,
                  s.ORDERCODE,
                  s.ORDERLINE,
                  b.ITEMTYPECODE,
                  b.DECOSUBCODE01,
                  b.DECOSUBCODE02,
                  b.DECOSUBCODE03,
                  b.DECOSUBCODE04,
                  b.DECOSUBCODE05,
                  b.DECOSUBCODE06,
                  b.DECOSUBCODE07,
                  b.DECOSUBCODE08,
                  a.VALUESTRING AS TGLTERIMA,
                  b.QUALITYLEVELCODE, 
                  SUM(b.BASEPRIMARYQUANTITYUNIT) AS KGS,
                  SUM(b.BASESECONDARYQUANTITYUNIT) AS CONES,
                  COUNT(b.ELEMENTSCODE) AS QTY,
                  b.WHSLOCATIONWAREHOUSEZONECODE,
                  b.WAREHOUSELOCATIONCODE,
                  p.LONGDESCRIPTION,
                  p.SHORTDESCRIPTION,
                  mh.CHALLANDATE,
                  c.FIRSTENTRYDATE,
                  a2.VALUESTRING AS CODEWARNA 
              FROM BALANCE b 
              LEFT JOIN LOT c 
                  ON b.LOTCODE = c.CODE 
                  AND c.COMPANYCODE = '100' 
                  AND b.ITEMTYPECODE = c.ITEMTYPECODE 
                  AND b.DECOSUBCODE01 = c.DECOSUBCODE01
                  AND b.DECOSUBCODE02 = c.DECOSUBCODE02
                  AND b.DECOSUBCODE03 = c.DECOSUBCODE03
                  AND b.DECOSUBCODE04 = c.DECOSUBCODE04
                  AND b.DECOSUBCODE05 = c.DECOSUBCODE05
                  AND b.DECOSUBCODE06 = c.DECOSUBCODE06
                  AND b.DECOSUBCODE07 = c.DECOSUBCODE07
                  AND b.DECOSUBCODE08 = c.DECOSUBCODE08
              LEFT JOIN ADSTORAGE a 
                  ON a.UNIQUEID = c.ABSUNIQUEID 
                  AND a.NAMENAME = 'ReceivedDate'
              LEFT JOIN CUSTOMERSUPPLIERDATA d 
                  ON c.SUPPLIERCODE = d.CODE 
                  AND d.COMPANYCODE = '100' 
                  AND d.TYPE = '2'
              LEFT JOIN BUSINESSPARTNER e 
                  ON d.BUSINESSPARTNERNUMBERID = e.NUMBERID 
              LEFT JOIN STOCKTRANSACTION s 
                  ON b.ELEMENTSCODE = s.ITEMELEMENTCODE 
                  AND s.TOKENCODE = 'RECEIPT' 
                  AND s.TEMPLATECODE = 'QCT'
              -- join PRODUCT langsung
              LEFT JOIN PRODUCT p
                  ON p.ITEMTYPECODE = 'GYR'
                  AND p.SUBCODE01 = b.DECOSUBCODE01
                  AND p.SUBCODE02 = b.DECOSUBCODE02
                  AND p.SUBCODE03 = b.DECOSUBCODE03
                  AND p.SUBCODE04 = b.DECOSUBCODE04
                  AND p.SUBCODE05 = b.DECOSUBCODE05
                  AND p.SUBCODE06 = b.DECOSUBCODE06
                  AND p.SUBCODE07 = b.DECOSUBCODE07
              -- join MRN
              LEFT JOIN MRNDETAIL md 
                  ON s.TRANSACTIONNUMBER = md.TRANSACTIONNUMBER
              LEFT JOIN MRNHEADER mh 
                  ON md.MRNHEADERCODE = mh.CODE
              LEFT JOIN ADSTORAGE a2     
              	  ON a2.UNIQUEID = mh.ABSUNIQUEID AND a2.NAMENAME = 'WARNA'
              WHERE 
                  (b.ITEMTYPECODE ='GYR' OR b.ITEMTYPECODE ='DYR')
                  AND b.LOGICALWAREHOUSECODE = 'M011'
                  AND b.WHSLOCATIONWAREHOUSEZONECODE IN 
                      ('GB0','GB1','GB2','GB5','GB6','GP1','GR2','GR3','GY1','GW2','PRT','LT')
              GROUP BY 
                  b.QUALITYLEVELCODE,
                  c.SUPPLIERCODE,
                  c.LOTCREATIONORDERNUMBER,
                  s.TRANSACTIONNUMBER,
                  s.ORDERCODE,
                  s.ORDERLINE,
                  e.LEGALNAME1, 
                  b.LOTCODE, 
                  b.ITEMTYPECODE,
                  b.DECOSUBCODE01, b.DECOSUBCODE02,
                  b.DECOSUBCODE03, b.DECOSUBCODE04,
                  b.DECOSUBCODE05, b.DECOSUBCODE06,
                  b.DECOSUBCODE07, b.DECOSUBCODE08,
                  b.WHSLOCATIONWAREHOUSEZONECODE,
                  b.WAREHOUSELOCATIONCODE,
                  a.VALUESTRING,
                  p.LONGDESCRIPTION,
                  p.SHORTDESCRIPTION,
                  mh.CHALLANDATE,
                  c.FIRSTENTRYDATE,
                  a2.VALUESTRING";
          $stmt1 = db2_exec($conn1, $sqlDB21, array('cursor' => DB2_SCROLLABLE));

          $lotCodes = [];
          $rows = [];
          while ($row = db2_fetch_assoc($stmt1)) {
              // simpan LOTCODE apa adanya dulu
              $lotCodes[] = rtrim($row['LOTCODE']); 
              $row['LOTCODE'] = rtrim($row['LOTCODE']); // normalisasi biar konsisten
              $rows[] = $row;
          }

          if (!empty($lotCodes)) {
              $placeholders = "'" . implode("','", array_map('addslashes', $lotCodes)) . "'";
              $sqlLot = "SELECT CODE, FIRSTENTRYDATE 
                        FROM LOT 
                        WHERE RTRIM(CODE) IN ($placeholders) -- pastikan juga di SQL
                        ORDER BY FIRSTENTRYDATE DESC";

              $stmtLot = db2_exec($conn1, $sqlLot, ['cursor' => DB2_SCROLLABLE]);

              $lotMap = [];
              while ($rowLot = db2_fetch_assoc($stmtLot)) {
                  // normalisasi key pakai rtrim
                  $lotMap[rtrim($rowLot['CODE'])] = $rowLot['FIRSTENTRYDATE'];
              }
          }

          foreach ($rows as &$row) {
              // akses pakai key yg sudah di-trim
              $row['FIRSTENTRYDATE'] = $lotMap[rtrim($row['LOTCODE'])] ?? null;
          }

          foreach ($rows as $rowdb21) {
            $kd = trim($rowdb21['DECOSUBCODE01']) . "-" . trim($rowdb21['DECOSUBCODE02']) . "-" . trim($rowdb21['DECOSUBCODE03']) . "-" . trim($rowdb21['DECOSUBCODE04']) . "-" . trim($rowdb21['DECOSUBCODE05']) . "-" . trim($rowdb21['DECOSUBCODE06']) . "-" . trim($rowdb21['DECOSUBCODE07']) . "-" . trim($rowdb21['DECOSUBCODE08']);
            ?>
            <tr>
              <td style="text-align: center">
                <?php echo $rowdb21['ITEMTYPECODE']; ?>
              </td>
              <td style="text-align: center">
                <?php if($rowdb21['CHALLANDATE']!=""){echo $rowdb21['CHALLANDATE'];}else{echo $rowdb21['FIRSTENTRYDATE'];} ?>
              </td>
              <td style="text-align: left">
                <?php echo $kd; ?>
              </td>
              <td style="text-align: left">
                <?php echo $rowdb21['LONGDESCRIPTION']; ?>
              </td>
              <td style="text-align: center">
                <?php echo $rowdb21['LOTCODE']; ?>
              </td>
              <td style="text-align: center">
                <?php echo $rowdb21['SUPPLIERCODE']; ?>
              </td>
              <td style="text-align: right">
                <?php echo $rowdb21['LEGALNAME1']; ?>
              </td>
              <td style="text-align: right"><?php 
			  	if ($rowdb21['CODEWARNA'] == "1") {
                  echo "Merah";
				} else if ($rowdb21['CODEWARNA'] == "2") {
                  echo "Kuning";
				} else if ($rowdb21['CODEWARNA'] == "3") {
                  echo "Hijau";	
				} else if ($rowdb21['CODEWARNA'] == "4") {
                  echo "Ungu";
				} else if ($rowdb21['CODEWARNA'] == "5") {
                  echo "Biru";
				} else if ($rowdb21['CODEWARNA'] == "6") {
                  echo "Putih";	
				} else if ($rowdb21['CODEWARNA'] == "7") {
                  echo "Coklat";	
				} else if ($rowdb21['CODEWARNA'] == "8") {
                  echo "Orange";		
                } else {
                  echo "";
                } ?></td>
              <td style="text-align: right">
                <?php if ($rowdb21['ORDERCODE'] != "") {
                  echo $rowdb21['ORDERCODE'];
                } else {
                  echo $rowdb21['LOTCREATIONORDERNUMBER'];
                } ?>
              </td>
              <td style="text-align: center"><?php echo $rowdb21['QUALITYLEVELCODE']; ?></td>
              <td style="text-align: right">
                <?php echo number_format(round($rowdb21['KGS'], 2), 2); ?>
              </td>
              <td style="text-align: right">
                <?php echo round($rowdb21['QTY'], 0); ?>
              </td>
              <td style="text-align: right">
                <?php echo round($rowdb21['CONES'], 0); ?>
              </td>
              <td style="text-align: center">
                <?php echo $rowdb21['WHSLOCATIONWAREHOUSEZONECODE']; ?>
              </td>
              <td style="text-align: center">
                <?php echo $rowdb21['WAREHOUSELOCATIONCODE']; ?>
              </td>
              <td style="text-align: center"><?php echo $rowdb21['TGLTERIMA']; ?></td>
            </tr>

            <?php $no++;
            $tKGs += $rowdb21['KGS'];
            $tQTY += $rowdb21['QTY'];
            $tCONES += $rowdb21['CONES'];
          } ?>
        </tbody>
        <tfoot>
          <tr>
            <td style="text-align: left">&nbsp;</td>
            <td style="text-align: left">&nbsp;</td>
            <td style="text-align: left">&nbsp;</td>
            <td style="text-align: left">&nbsp;</td>
            <td style="text-align: center">&nbsp;</td>
            <td style="text-align: right">&nbsp;</td>
            <td style="text-align: right">&nbsp;</td>
            <td style="text-align: right">&nbsp;</td>
            <td style="text-align: right">&nbsp;</td>
            <td style="text-align: right">&nbsp;</td>
            <td style="text-align: right"><strong>
                <?php echo number_format(round($tKGs, 2), 2); ?>
              </strong></td>
            <td style="text-align: right"><strong>
                <?php echo number_format($tQTY); ?>
              </strong></td>
            <td style="text-align: right"><strong>
                <?php echo number_format($tCONES); ?>
              </strong></td>
            <td style="text-align: center">&nbsp;</td>
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