<?php
$Awal = isset($_POST['tgl_awal']) ? $_POST['tgl_awal'] : '';
$Akhir = isset($_POST['tgl_akhir']) ? $_POST['tgl_akhir'] : '';
?>
<!-- Main content -->
<div class="container-fluid">
  <form role="form" method="post" enctype="multipart/form-data" name="form1">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Filter Data Tgl Keluar Benang</h3>

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
              <input name="tgl_awal" value="<?php echo $Awal; ?>" type="text" class="form-control form-control-sm" id=""
                autocomplete="off" required>
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
              <input name="tgl_akhir" value="<?php echo $Akhir; ?>" type="text" class="form-control form-control-sm"
                id="" autocomplete="off" required>
            </div>
          </div>
        </div>

        <button class="btn btn-info" type="submit">Cari Data</button>
      </div>
      <!-- /.card-body -->
    </div>


    <div class="card card-danger">
      <div class="card-header">
        <h3 class="card-title">Detail Data Benang </h3>
        <a href="javascript:void(0);" class="btn btn-sm btn-success float-right mx-1"
          onclick="cetak_cek('cetakKeluarBenangExcel')"><i class="fa fa-file"></i> To Excel</a>

        <a href="pages/cetak/cetaklapkeluar.php?tgl1=<?php echo $Awal; ?>&tgl2=<?php echo $Akhir; ?>"
          class="btn btn-sm btn-success float-right mx-1" target="_blank"><i class="fa fa-file"></i> To Print</a>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="example1" class="table table-sm table-bordered table-striped"
          style="font-size: 13px; text-align: center;">
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
              x.ORDERLINE=s.ORDERLINE AND s.TOKENCODE IS NULL
              LEFT OUTER JOIN FULLITEMKEYDECODER f ON
              s.FULLITEMIDENTIFIER = f.IDENTIFIER
              LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID =x.ABSUNIQUEID AND a.NAMENAME ='SuppName'
              WHERE 
              s.TRANSACTIONDATE BETWEEN '$Awal' AND '$Akhir' AND 
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
                  <?php echo $rowdb21['SUPPLIER']; ?>
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
                  <?php echo $rowdb21['WHSLOCATIONWAREHOUSEZONECODE'] . "-" . $rowdb21['WAREHOUSELOCATIONCODE']; ?>
                </td>
              </tr>
            <?php
              $tQty += $rowdb21['QTY_ROL'];
              $tCones += $rowdb21['QTY_CONES'];
              $tKG += $rowdb21['QTY_KG'];
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
              <td colspan="2" style="text-align: left"><strong>Total</strong></td>
              <td style="text-align: right"><strong>
                  <?php echo round($tQty); ?>
                </strong></td>
              <td style="text-align: right"><strong>
                  <?php echo round($tCones); ?>
                </strong></td>
              <td style="text-align: right"><strong>
                  <?php echo number_format(round($tKG, 2), 2); ?>
                </strong></td>
              <td>&nbsp;</td>
            </tr>
          </tfoot>
        </table>
      </div>
      <!-- /.card-body -->
    </div>

    <!-- Detai Warna -->
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Detail Data Benang Warna</h3>
        <a href="javascript:void(0);" class="btn btn-sm btn-default float-right mx-1"
          onclick="cetak_cek('cetakKeluarBenangWarnaExcel');"><i class="fa fa-file"></i> To Excel</a>

        <a href="pages/cetak/cetaklapkeluarW.php?tgl1=<?php echo $Awal; ?>&tgl2=<?php echo $Akhir; ?>"
          class="btn btn-sm btn-default float-right mx-1" target="_blank"><i class="fa fa-file"></i> to Print</a>

      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="example3" class="table table-sm table-bordered table-striped"
          style="font-size: 13px; text-align: center;">
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
              LEFT OUTER JOIN STOCKTRANSACTION s ON x.INTDOCUMENTPROVISIONALCODE=s.ORDERCODE 
                AND x.ORDERLINE=s.ORDERLINE 
                AND (TOKENCODE = 'RECEIPT' or TOKENCODE IS NULL)
                AND TRANSACTIONDATE = s.TRANSACTIONDATE
                AND LOGICALWAREHOUSECODE ='M011' 
              LEFT OUTER JOIN FULLITEMKEYDECODER f ON
              s.FULLITEMIDENTIFIER = f.IDENTIFIER
              LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID =x.ABSUNIQUEID AND a.NAMENAME ='SuppName'
              WHERE 
              s.TRANSACTIONDATE BETWEEN '$Awal' AND '$Akhir' AND 
              x.ITEMTYPEAFICODE ='DYR' AND
              s.LOGICALWAREHOUSECODE='M011' AND 
              NOT x.EXTERNALREFERENCE LIKE '%RETUR%' AND 
              NOT x.ORDERLINE IS NULL AND 
              INTDOCPROVISIONALCOUNTERCODE='I02M01'
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
                  <?php echo round($rowdb21['QTY_ROL']); ?>
                </td>
                <td style="text-align: right">
                  <?php echo round($rowdb21['QTY_CONES']); ?>
                </td>
                <td style="text-align: right">
                  <?php echo number_format(round($rowdb21['QTY_KG'], 2), 2); ?>
                </td>
                <td>
                  <?php echo $rowdb21['WHSLOCATIONWAREHOUSEZONECODE'] . "-" . $rowdb21['WAREHOUSELOCATIONCODE']; ?>
                </td>
              </tr>
            <?php
              $tQty1 += $rowdb21['QTY_ROL'];
              $tCones1 += $rowdb21['QTY_CONES'];
              $tKG1 += $rowdb21['QTY_KG'];
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
              <td colspan="2" style="text-align: left"><strong>Total</strong></td>
              <td style="text-align: right"><strong>
                  <?php echo round($tQty1); ?>
                </strong></td>
              <td style="text-align: right"><strong>
                  <?php echo round($tCones1); ?>
                </strong></td>
              <td style="text-align: right"><strong>
                  <?php echo number_format(round($tKG1, 2), 2); ?>
                </strong></td>
              <td>&nbsp;</td>
            </tr>
          </tfoot>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- End of Detail Warna -->


  </form>
</div><!-- /.container-fluid -->
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

  function cetak_cek(filename) {
    var awal = document.querySelector('input[name="tgl_awal"]').value;
    var akhir = document.querySelector('input[name="tgl_akhir"]').value;

    if (awal != "" && akhir != "") {
      let url = 'pages/cetak/' + filename + '.php?tgl_awal=' + awal + '&tgl_akhir=' + akhir + '';

      window.open(url, '_blank');
    } else {
      alert('Filter tidak boleh kosong!')
    }
  }
</script>
<script type="text/javascript">
  function checkAll(form1) {
    for (var i = 0; i < document.forms['form1'].elements.length; i++) {
      var e = document.forms['form1'].elements[i];
      if ((e.name != 'allbox') && (e.type == 'checkbox')) {
        e.checked = document.forms['form1'].allbox.checked;

      }
    }
  }
</script>
<?php
if ($_POST['mutasikain'] == "MutasiKain") {

  function mutasiurut()
  {
    include "koneksi.php";
    $format = "20" . date("ymd");
    $sql = mysqli_query($con, "SELECT no_mutasi FROM dbnow_gdb.tbl_mutasi_kain WHERE substr(no_mutasi,1,8) like '%" . $format . "%' ORDER BY no_mutasi DESC OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY") or die(print_r(sqlsrv_errors(), true));
    $d = mysqli_num_rows($sql);
    if ($d > 0) {
      $r = mysqli_fetch_array($sql);
      $d = $r['no_mutasi'];
      $str = substr($d, 8, 2);
      $Urut = (int) $str;
    } else {
      $Urut = 0;
    }
    $Urut = $Urut + 1;
    $Nol = "";
    $nilai = 2 - strlen($Urut);
    for ($i = 1; $i <= $nilai; $i++) {
      $Nol = $Nol . "0";
    }
    $tidbr = $format . $Nol . $Urut;
    return $tidbr;
  }
  $nomid = mutasiurut();

  $sql1 = mysqli_query($con, "SELECT *,count(b.transid) as jmlrol,a.transid as kdtrans FROM tbl_mutasi_kain a 
LEFT JOIN tbl_prodemand b ON a.transid=b.transid 
WHERE isnull(a.no_mutasi) AND date_format(a.tgl_buat ,'%Y-%m-%d')='$Awal' AND a.gshift='$Gshift' 
GROUP BY a.transid");
  $n1 = 1;
  $noceklist1 = 1;
  while ($r1 = mysqli_fetch_array($sql1)) {
    if ($_POST['cek'][$n1] != '') {
      $transid1 = $_POST['cek'][$n1];
      mysqli_query($con, "UPDATE tbl_mutasi_kain SET
		no_mutasi='$nomid',
		tgl_mutasi=now()
		WHERE transid='$transid1'
		");
    } else {
      $noceklist1++;
    }
    $n1++;
  }
  if ($noceklist1 == $n1) {
    echo "<script>
  	$(function() {
    const Toast = Swal.mixin({
      toast: false,
      position: 'middle',
      showConfirmButton: false,
      timer: 2000
    });
	Toast.fire({
        icon: 'info',
        title: 'Data tidak ada yang di Ceklist',
		
      })
  });
  
</script>";
  } else {
    echo "<script>
	$(function() {
    const Toast = Swal.mixin({
      toast: false,
      position: 'middle',
      showConfirmButton: true,
      timer: 6000
    });
	Toast.fire({
  title: 'Data telah di Mutasi',
  text: 'klik OK untuk Cetak Bukti Mutasi',
  icon: 'success',  
}).then((result) => {
  if (result.isConfirmed) {
    	window.open('pages/cetak/cetak_mutasi_ulang.php?mutasi=$nomid', '_blank');
  }
})
  });
	</script>";

    /*echo "<script>
      Swal.fire({
      title: 'Data telah di Mutasi',
      text: 'klik OK untuk Cetak Bukti Mutasi',
      icon: 'success',  
    }).then((result) => {
      if (result.isConfirmed) {
          window.location='Mutasi';
      }
    });
      </script>";	*/
  }
}
?>
