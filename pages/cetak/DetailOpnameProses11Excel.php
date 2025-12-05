<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=detailopnameproses11 " . date($_GET['tgl'])."_".$_GET['tipe']. ".xls"); //ganti nama sesuai keperluan
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
// Konversi ke format seperti pada gambar
$bulanIndo = [
    'January' => 'JANUARI',
    'February' => 'FEBRUARI',
    'March' => 'MARET',
    'April' => 'APRIL',
    'May' => 'MEI',
    'June' => 'JUNI',
    'July' => 'JULI',
    'August' => 'AGUSTUS',
    'September' => 'SEPTEMBER',
    'October' => 'OKTOBER',
    'November' => 'NOVEMBER',
    'December' => 'DESEMBER'
];

$date = new DateTime($Tgl);
$hari = $date->format('d');
$bulan = $bulanIndo[$date->format('F')];
$tahun = $date->format('Y');
?>
<style type="text/css">
.no-border th {
    border: none !important;
  }	
</style>


<p><br>
<table border="1" style="border-collapse: collapse;">
                  <thead>
                  <tr class="no-border">
                    <th colspan="13" align="center"><strong>LAPORAN STOCK BENANG PROSES</strong></th>
                  </tr>
                  <tr class="no-border">
                    <th colspan="13" align="center"><strong>PT. INDO TAICHEN TEXTILE INDUSTRY</strong></th>
                  </tr>
                  <tr>
                    <td colspan="13" align="center" style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">FW-19-GDB-02/03</td>
                  </tr>
                  <tr>
                    <td colspan="13" align="left" style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="13" align="left" style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="13" align="left" style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;"><strong><?Php echo "TANGGAL : $hari $bulan $tahun"; ?></strong>
                    </td>
                    </tr>
                  <tr>
                    <th><strong>No</strong></th>
                    <th><strong>CODE</strong></th>
                    <th><strong>SUPPLIER CODE</strong></th>
                    <th><strong>SUPPLIER</strong></th>
                    <th><strong>JENIS BENANG</strong></th>
                    <th><strong>LOT</strong></th>
                    <th><strong>QTY</strong></th>
                    <th><strong>SATUAN</strong></th>
                    <th><strong>BERAT/Kg</strong></th>
                    <th><strong>CONES</strong></th>
                    <th><strong>ZONE</strong></th>
                    <th><strong>BLOCK</strong></th>
                    <th><strong>GRADE</strong></th>
                    </tr>                  
                  </thead>
                  <tbody>
				  <?php				  
   $no=1;   
   $c=0;

   $sql = sqlsrv_query(
       $con,
       "SELECT 
            tipe,
            kd_benang,
            suppliercode,
            suppliername,
            jenis_benang,
            lot,
            SUM(qty)    AS t_qty,
            SUM(weight) AS t_berat,
            SUM(cones)  AS t_cones,
            zone,
            lokasi,
            grd
        FROM dbnow_gdb.tblopname_detail_11
        WHERE tgl_tutup = ?
          AND tipe      = ?
        GROUP BY
            tipe,
            kd_benang,
            suppliercode,
            suppliername,
            jenis_benang,
            lot,
            grd,
            lokasi,
            zone
        ORDER BY
            kd_benang,
            lot,
            zone,
            lokasi",
       array($Tgl, $Tipe)
   );

   if ($sql === false) {
       die(print_r(sqlsrv_errors(), true));
   }

   while($r = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){
		
?>
	  <tr>
	  <td><?php echo $no; ?></td>
      <td><?php echo $r['kd_benang']; ?></td>
      <td><?php echo $r['suppliercode']; ?></td>
      <td><?php echo $r['suppliername']; ?></td>
      <td><?php echo $r['jenis_benang']; ?></td>
      <td><?php echo $r['lot']; ?></td>
      <td align="center"><?php echo $r['t_qty']; ?></td>
      <td><?php echo "DUS"; ?></td>
      <td align="right"><?php echo $r['t_berat']; ?></td>
      <td align="right"><?php echo $r['t_cones']; ?></td>
      <td align="center"><?php echo $r['zone']; ?></td>
      <td align="center"><?php echo $r['lokasi']; ?></td>
      <td align="center"><?php echo $r['grd']; ?></td>
      </tr>
	  				  
<?php	$no++;
		$totqty=$totqty+$r['t_qty'];
		$totcones=$totcones+$r['t_cones'];
		$totkg=$totkg+$r['t_berat'];
	} ?>
				  </tbody>
				<tfoot>
				  <tr>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    <td>&nbsp;</td>
	    </tr>	
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><strong>Total</strong></td>
                    <td align="right"><strong><?php echo number_format($totqty); ?></strong></td>
                    <td><strong>Dus</strong></td>
                    <td align="right"><strong><?php echo number_format(round($totkg,3),3); ?></strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><strong>Total</strong></td>
                    <td align="right"><strong><?php echo number_format($totcones); ?></strong></td>
                    <td><strong>Cones</strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><strong>Grand Total</strong></td>
                    <td align="right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="right"><strong><?php echo number_format(round($totkg,3),3); ?></strong></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                    <td style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                    <td style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                    <td style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                    <td style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                    <td style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                    <td align="right" style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                    <td style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                    <td align="right" style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                    <td style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                    <td style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                    <td style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                    <td style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="3">&nbsp;</td>
                    <td colspan="2" align="center">Dibuat Oleh :</td>
                    <td colspan="3" align="center">Diperiksa Oleh :</td>
                    <td colspan="4" align="center">Mengetahui:</td>
                    <td align="center" style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="3">NAMA</td>
                    <td colspan="2" align="center">N Gadish A</td>
                    <td colspan="3" align="center">N/A</td>
                    <td colspan="4" align="center">Redy Kurnianto</td>
                    <td align="center" style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="3">JABATAN</td>
                    <td colspan="2" align="center">Staff</td>
                    <td colspan="3" align="center">N/A</td>
                    <td colspan="4" align="center">Leader</td>
                    <td align="center" style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="3">TANGGAL</td>
                    <td colspan="2" align="center">&nbsp;</td>
                    <td colspan="3" align="center">&nbsp;</td>
                    <td colspan="4" align="center">&nbsp;</td>
                    <td align="center" style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="3">TANDA TANGAN
					  <p>&nbsp;</p>
   					<p>&nbsp;</p></td>
                    <td colspan="2">&nbsp;</td>
                    <td colspan="3" align="center">N/A</td>
                    <td colspan="4" align="center">&nbsp;</td>
                    <td align="center" style="border-bottom: 0px solid black;border-top: 0px solid black;border-right: 0px solid black; border-left: 0px solid black;">&nbsp;</td>
                  </tr>
                  </tfoot>                  
                </table>
<br>
<?php ob_end_flush(); ?>
