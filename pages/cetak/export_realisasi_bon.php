<?php
    // Set header agar browser mendownload file sebagai Excel
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Realisasi_Bon_Permohonan_Benang_" . $_GET['thn'] . "_" . $_GET['bln'] . ".xls");

    ini_set("error_reporting", 1);
    include "../../koneksi.php";

    // Sanitasi input GET
    $Thn2 = isset($_GET['thn']) ? htmlspecialchars($_GET['thn']) : '';
    $Bln2 = isset($_GET['bln']) ? str_pad(htmlspecialchars($_GET['bln']), 2, '0', STR_PAD_LEFT) : '';

    // Format untuk LIKE (contoh: 2025-06)
    $like_date = $Thn2 . '-' . $Bln2;
    if ($Bln2 == '01') {
        $nama_bulan = 'JAN';
    } elseif ($Bln2 == '02') {
        $nama_bulan = 'FEB';
    } elseif ($Bln2 == '03') {
        $nama_bulan = 'MAR';
    } elseif ($Bln2 == '04') {
        $nama_bulan = 'APR';
    } elseif ($Bln2 == '05') {
        $nama_bulan = 'MEI';
    } elseif ($Bln2 == '06') {
        $nama_bulan = 'JUN';
    } elseif ($Bln2 == '07') {
        $nama_bulan = 'JUL';
    } elseif ($Bln2 == '08') {
        $nama_bulan = 'AGT';
    } elseif ($Bln2 == '09') {
        $nama_bulan = 'SEP';
    } elseif ($Bln2 == '10') {
        $nama_bulan = 'OKT';
    } elseif ($Bln2 == '11') {
        $nama_bulan = 'NOV';
    } elseif ($Bln2 == '12') {
        $nama_bulan = 'DES';
    } else {
        $nama_bulan = 'INVALID';
    }

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            text-align: center;
            padding: 5px;
        }

        th {
            background-color: yellow;
        }

        .header-title {
            background-color: yellow;
            font-weight: bold;
            text-align: center;
        }

        .no-border {
            border: none;
        }
    </style>
</head>

<body>

    <!-- Judul -->
    <table class="no-border">
        <tr>
            <td colspan="9" class="header-title">REKAP REALISASI BON PERMOHONAN BENANG</td>
        </tr>
        <tr>
            <td colspan="9" class="header-title">PER <?= $nama_bulan . '-' . $Thn2 ?></td>
        </tr>
    </table>

    <br>
    <?php
        // 1. Persiapan array tanggal lengkap dalam sebulan
        // $Thn2 = '2025';
        // $Bln2 = '06';
        // $like_date = "$Thn2-$Bln2";

        $start_date = "$Thn2-$Bln2-01";
        $end_date = date("Y-m-t", strtotime($start_date));

        $all_dates = [];
        $current = strtotime($start_date);
        $end = strtotime($end_date);

        while ($current <= $end) {
            $tgl = date("Y-m-d", $current);
            $all_dates[$tgl] = [
                'TRANSACTIONDATE' => $tgl,
                'JML_BONORDER_ONTIME' => 0,
                'QTY_ONTIME' => 0,
                'PRESENTASE_TERCAPAI' => 0,
                'JML_BONORDER_DELAY' => 0,
                'QTY_DELAY' => 0,
                'PRESENTASE_TIDAK_TERCAPAI' => 0,
                'TOTAL_BONORDER' => 0,
                'TOTAL_QTY' => 0,
            ];
            $current = strtotime("+1 day", $current);
        }

        // 2. Query DB2 seperti sebelumnya
        $query = "SELECT * FROM (
                SELECT 
                    TRANSACTIONDATE,
                    YEAR(TRANSACTIONDATE) AS tahun,
                    MONTH(TRANSACTIONDATE) AS bulan,
                    COUNT(CASE WHEN status_ = 'Ontime' THEN ORDERCODE ELSE NULL END) AS JML_BONORDER_ONTIME,
                    COUNT(CASE WHEN status_ = 'Delay' THEN ORDERCODE ELSE NULL END) AS JML_BONORDER_DELAY,
                    SUM(CASE WHEN status_ = 'Ontime' THEN USERPRIMARYQUANTITY ELSE 0 END) AS QTY_ONTIME,
                    SUM(CASE WHEN status_ = 'Delay' THEN USERPRIMARYQUANTITY ELSE 0 END) AS QTY_DELAY,
                    LOGICALWAREHOUSECODE,
                    USERPRIMARYUOMCODE,
                    SUM(USERPRIMARYQUANTITY) AS TOTAL_QTY,
                    COUNT(ORDERCODE) AS TOTAL_BONORDER,
                    ROUND(100.0 * COUNT(CASE WHEN status_ = 'Delay' THEN ORDERCODE ELSE NULL END) / COUNT(ORDERCODE), 2) AS PRESENTASE_TIDAK_TERCAPAI,
                    ROUND(100.0 * COUNT(CASE WHEN status_ = 'Ontime' THEN ORDERCODE ELSE NULL END) / COUNT(ORDERCODE), 2) AS PRESENTASE_TERCAPAI
                FROM (
                    SELECT 
                        s.LOGICALWAREHOUSECODE,
                        SUM(s.USERPRIMARYQUANTITY) AS USERPRIMARYQUANTITY,
                        s.USERPRIMARYUOMCODE,
                        s.TRANSACTIONDATE,
                        trim(s.ORDERCODE) ||'-'|| s.ORDERLINE AS ORDERCODE,
                        i.RECEIVINGDATE,
                        CASE 
                            WHEN s.TRANSACTIONDATE <= i.RECEIVINGDATE THEN 'Ontime'
                            ELSE 'Delay'
                        END AS status_
                    FROM STOCKTRANSACTION s
                    LEFT JOIN INTERNALDOCUMENTLINE i 
                        ON i.INTDOCUMENTPROVISIONALCODE = s.ORDERCODE 
                        AND i.ORDERLINE = s.ORDERLINE
                    WHERE 
                        s.LOGICALWAREHOUSECODE = 'M011'
                        AND s.ITEMTYPECODE IN ('GYR', 'DYR')
                        AND s.TEMPLATECODE = '203'
                        AND s.TRANSACTIONDATE LIKE '%$like_date%'
                    GROUP BY 
                        s.TRANSACTIONDATE,
                        s.LOGICALWAREHOUSECODE,
                        s.USERPRIMARYUOMCODE,
                        s.ORDERCODE,
                        s.ORDERLINE,
                        i.RECEIVINGDATE
                )
                GROUP BY 
                    LOGICALWAREHOUSECODE,
                    USERPRIMARYUOMCODE,
                    TRANSACTIONDATE
                ORDER BY TRANSACTIONDATE ASC
            ) AS subquery
            WHERE tahun = '$Thn2' AND bulan = '$Bln2'";

        $stmt = db2_exec($conn1, $query);

        // 3. Gabungkan hasil query dengan array tanggal
        while ($row = db2_fetch_assoc($stmt)) {
            $tgl = date("Y-m-d", strtotime($row['TRANSACTIONDATE']));
            if (isset($all_dates[$tgl])) {
                $all_dates[$tgl] = array_merge($all_dates[$tgl], $row);
            }
        }
    ?>
    <!-- Tabel Data -->
    <table>
        <thead>
            <tr>
                <th rowspan="3">Tanggal</th>
                <th colspan="6">Realisasi</th>
                <th colspan="2">Jumlah Bon</th>
            </tr>
            <tr>
                <th colspan="3">Ontime</th>
                <th colspan="3">Delay</th>
                <th rowspan="2">Bon</th>
                <th rowspan="2">Qty</th>
            </tr>
            <tr>
                <th>%</th>
                <th>Bon</th>
                <th>Qty</th>
                <th>%</th>
                <th>Bon</th>
                <th>Qty</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // Inisialisasi total
                $total_bon_ontime   = 0;
                $total_qty_ontime   = 0;
                $total_bon_delay    = 0;
                $total_qty_delay    = 0;
                $total_bon          = 0;
                $total_qty          = 0;
            ?>
            <?php foreach ($all_dates as $row): ?>
            <?php
                $total_bon_ontime += $row['JML_BONORDER_ONTIME'];
                $total_qty_ontime += $row['QTY_ONTIME'];
                $total_bon_delay  += $row['JML_BONORDER_DELAY'];
                $total_qty_delay  += $row['QTY_DELAY'];
                $total_bon        += $row['TOTAL_BONORDER'];
                $total_qty        += $row['TOTAL_QTY'];
            ?>
                <tr>
                    <td align="left"><?= date('d-M-Y', strtotime($row['TRANSACTIONDATE'])) ?></td>
                    <td><?= $row['JML_BONORDER_ONTIME'] ?></td>
                    <td align="right"><?= number_format($row['QTY_ONTIME'], 2) ?></td>
                    <td><?= number_format($row['PRESENTASE_TERCAPAI'], 2) ?>%</td>
                    <td><?= $row['JML_BONORDER_DELAY'] ?></td>
                    <td align="right"><?= number_format($row['QTY_DELAY'], 2) ?></td>
                    <td><?= number_format($row['PRESENTASE_TIDAK_TERCAPAI'], 2) ?>%</td>
                    <td><?= $row['TOTAL_BONORDER'] ?></td>
                    <td align="right"><?= number_format($row['TOTAL_QTY'], 2) ?></td>
                </tr>
            <?php endforeach; ?>

            <!-- Baris total -->
            <tr style="font-weight:bold; background:#f0f0f0;">
                <td align="center">TOTAL</td>
                <td><?= $total_bon_ontime ?></td>
                <td align="right"><?= number_format($total_qty_ontime, 2) ?></td>
                <td>-</td>
                <td><?= $total_bon_delay ?></td>
                <td align="right"><?= number_format($total_qty_delay, 2) ?></td>
                <td>-</td>
                <td><?= $total_bon ?></td>
                <td align="right"><?= number_format($total_qty, 2) ?></td>
            </tr>
        </tbody>
    </table>

    <br>
    <table>
        <tr>
            <th colspan="3">Dibuat oleh :</th>
            <th colspan="3">Diperiksa oleh :</th>
            <th colspan="3">Diketahui oleh :</th>
        </tr>
        <tr>
            <td>Nama</td>
            <td>Jabatan</td>
            <td>Tanggal</td>
            <td>Nama</td>
            <td>Jabatan</td>
            <td>Tanggal</td>
            <td>Nama</td>
            <td>Jabatan</td>
            <td>Tanggal</td>
        </tr>
        <tr>
            <td height="50px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="3">Tanda tangan</td>
            <td colspan="3">Tanda tangan</td>
            <td colspan="3">Tanda tangan</td>
        </tr>
    </table>

</body>

</html>