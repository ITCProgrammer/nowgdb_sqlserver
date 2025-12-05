<?php

include "../../koneksi.php";
$Awal = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : '';
$Akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : '';

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=KeluarBenang-DetailDataBenangWarna_" . $Awal . "_" . $Akhir . "_.xls"); //ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");
?>
<div class="card card-warning">
    <div class="card-header">
        <h2 class="card-title" style="font-size: 2em; text-align:center;">Detail Data Benang Warna</h2>
        <p>Tanggal :
            <?= $Awal ?> s/d
            <?= $Akhir ?>
        </p>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="example3" border="1" style="border-collapse: collapse"
            class="table table-sm table-bordered table-striped" style="font-size: 13px; text-align: center;">
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
                    <th valign="middle" style="text-align: center">Element</th>
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
              s.ITEMELEMENTCODE,
              s.BASEPRIMARYQUANTITY AS QTY_KG,
              s.BASESECONDARYQUANTITY AS QTY_CONES,
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
              s.TRANSACTIONDATE BETWEEN '$Awal' AND '$Akhir' AND 
              s.TEMPLATECODE = '203' AND
              x.ITEMTYPEAFICODE ='DYR' AND
              s.LOGICALWAREHOUSECODE='M011' AND 
              NOT x.EXTERNALREFERENCE LIKE '%RETUR%' AND 
              NOT x.ORDERLINE IS NULL AND 
              INTDOCPROVISIONALCOUNTERCODE='I02M01'
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
                            <?php echo $knitt; ?>
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
                            <?php echo $rowdb21['ITEMELEMENTCODE']; ?>
                        </td>
                        <td style="text-align: right">
                            <?php echo '1' ?>
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
                    @$tQty1 += 1;
                    @$tCones1 += $rowdb21['QTY_CONES'];
                    @$tKG1 += $rowdb21['QTY_KG'];
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
                    <td colspan="3" style="text-align: left"><strong>Total</strong></td>
                    <td style="text-align: right"><strong>
                            <?php echo @round($tQty1); ?>
                        </strong></td>
                    <td style="text-align: right"><strong>
                            <?php echo @round($tCones1); ?>
                        </strong></td>
                    <td style="text-align: right"><strong>
                            <?php echo @number_format(round($tKG1, 2), 2); ?>
                        </strong></td>
                    <td>&nbsp;</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- /.card-body -->
</div>