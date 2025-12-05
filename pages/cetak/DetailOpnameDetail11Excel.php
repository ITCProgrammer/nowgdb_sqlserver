<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=detailopname11 " . date($_GET['tgl'])." ". $_GET['tipe'] . ".xls"); //ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");
//disini script laporan anda
ob_start();
?>
<?php
$Tgl 	= isset($_GET['tgl']) ? $_GET['tgl'] : '';
$Tipe 	= isset($_GET['tipe']) ? $_GET['tipe'] : '';
?>
<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";
$tgl = date("Y-m-d");
?>
<table border="1">
                  <thead>
                  <tr>
                    <th>Tipe</th>
                    <th>Code</th>
                    <th>Jenis Benang</th>
                    <th>PO</th>
                    <th>Lot</th>
                    <th>SupplierCode</th>
                    <th>Supplier</th>
                    <th>Weight</th>
                    <th>Qty</th>
                    <th>Cones</th>
                    <th>Element</th>
                    <th>Grade</th>
                    <th>Zone</th>
                    <th>Lokasi</th>
                    </tr>                  
                  </thead>
                  <tbody>
				  <?php				  
   $no=1;   
   $c=0;

   $sql = sqlsrv_query(
       $con,
       "
       ;WITH base AS (
            SELECT
                id,
                tipe,
                kd_benang,
                jenis_benang,
                po,
                lot,
                suppliercode,
                suppliername,
                weight,
                qty,
                cones,
                sn,
                grd,
                zone,
                lokasi
            FROM dbnow_gdb.tblopname_detail_11
            WHERE tgl_tutup = ?
              AND tipe      = ?
       ),
       dedup AS (
            SELECT *,
                   ROW_NUMBER() OVER (PARTITION BY sn ORDER BY id ASC) AS rn
            FROM base
       )
       SELECT
            id,
            tipe,
            kd_benang,
            jenis_benang,
            po,
            lot,
            suppliercode,
            suppliername,
            weight,
            qty,
            cones,
            sn,
            grd,
            zone,
            lokasi
       FROM dedup
       WHERE rn = 1
       ORDER BY id ASC",
       array($Tgl, $Tipe)
   );

   if ($sql === false) {
       die(print_r(sqlsrv_errors(), true));
   }

   while($r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){
		
?>
	  <tr>
	  <td><?php echo $r['tipe']; ?></td>
      <td><?php echo $r['kd_benang']; ?></td>
      <td><?php echo $r['jenis_benang']; ?></td>
      <td><?php echo $r['po']; ?></td>
      <td><?php echo $r['lot']; ?></td>
      <td><?php echo $r['suppliercode']; ?></td>
      <td><?php echo $r['suppliername']; ?></td>
      <td><?php echo $r['weight']; ?></td>
      <td><?php echo $r['qty']; ?></td>
      <td><?php echo $r['cones']; ?></td>
      <td>`<?php echo $r['sn']; ?></td>
      <td><?php echo $r['grd']; ?></td>
      <td><?php echo $r['zone']; ?></td>
      <td><?php echo $r['lokasi']; ?></td>
      </tr>				  
<?php	$no++;
		$totqty=$totqty+$r['qty'];
		$totcones=$totcones+$r['cones'];
		$totkg=$totkg+$r['weight'];
	} ?>
				  </tbody>
				<tfoot>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan="2"><strong>TOTAL</strong></td>
                    <td><strong><?php echo number_format(round($totkg,3),3); ?></strong></td>
                    <td><strong><?php echo number_format($totqty); ?></strong></td>
                    <td><strong><?php echo number_format($totcones); ?></strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    </tr>
                  </tfoot>                  
                </table>
<?php ob_end_flush(); ?>
