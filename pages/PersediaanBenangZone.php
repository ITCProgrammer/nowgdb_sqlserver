<?php
$Zone    = isset($_POST['zone']) ? $_POST['zone'] : '';
$Lokasi    = isset($_POST['lokasi']) ? $_POST['lokasi'] : '';
$CKLokasi  = isset($_POST['cklokasi']) ? $_POST['cklokasi'] : '';
?>
<!-- Main content -->
<div class="container-fluid">
  <form role="form" method="post" enctype="multipart/form-data" name="form1">
    <div class="card card-default">
      <div class="card-header">
        <h3 class="card-title">Filter Data</h3>

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
          <label for="zone" class="col-md-1">Zone</label>
          <select class="form-control select2bs4" style="width: 100%;" name="zone">
            <option value="">Pilih</option>
            <?php $sqlZ = sqlsrv_query($con, " SELECT * FROM dbnow_gdb.tbl_zone ORDER BY nama ASC");
            while ($rZ = sqlsrv_fetch_array($sqlZ, SQLSRV_FETCH_ASSOC)) {
            ?>
              <option value="<?php echo $rZ['nama']; ?>" <?php if ($rZ['nama'] == $Zone) {
                                                          echo "SELECTED";
                                                        } ?>><?php echo $rZ['nama']; ?></option>
            <?php  } ?>
          </select>
        </div>
        <div class="form-group row">
          <label for="lokasi" class="col-md-1"><input type="checkbox" value="1" name="cklokasi" <?php if ($CKLokasi == "1") {
                                                                                                  echo "checked";
                                                                                                } ?>> Location</label>
          <select class="form-control select2bs4 " style="width: 100%;" name="lokasi" <?php if ($CKLokasi != "1") { ?> disabled <?php } ?>>
            <option value="">Pilih</option>
            <?php $sqlL = sqlsrv_query($con, " SELECT * FROM dbnow_gdb.tbl_lokasi WHERE zone='$Zone' ORDER BY nama ASC");
            while ($rL = sqlsrv_fetch_array($sqlL, SQLSRV_FETCH_ASSOC)) {
            ?>
              <option value="<?php echo $rL['nama']; ?>" <?php if ($rL['nama'] == $Lokasi) {
                                                          echo "SELECTED";
                                                        } ?>><?php echo $rL['nama']; ?></option>
            <?php  } ?>
          </select>
        </div>
        <button class="btn btn-info" type="submit" value="Cari" name="cari">Cari Data</button>
      </div>

      <!-- /.card-body -->

    </div>
  </form>
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Stock</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <table id="example1" class="table table-sm table-bordered table-striped" style="font-size:13px;">
        <thead>
          <tr>
            <th style="text-align: center">TGL</th>
            <th style="text-align: center">Code</th>
            <th style="text-align: center">Jenis Benang</th>
            <th style="text-align: center">Element</th>
            <th style="text-align: center">Lot</th>
            <th style="text-align: center">Weight</th>
            <th style="text-align: center">Satuan</th>
            <th style="text-align: center">Qty</th>
            <th style="text-align: center">Satuan</th>
            <th style="text-align: center">Grade</th>
            <th style="text-align: center">Zone</th>
            <th style="text-align: center">Lokasi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($Zone != "" and $Lokasi != "") {
            $Where = " AND WHSLOCATIONWAREHOUSEZONECODE='$Zone' AND WAREHOUSELOCATIONCODE LIKE '$Lokasi%' ";
          } else if ($CKLokasi != "1") {
            $Where = " AND WHSLOCATIONWAREHOUSEZONECODE='$Zone' ";
          } else {
            $Where = " AND WHSLOCATIONWAREHOUSEZONECODE='$Zone' AND WAREHOUSELOCATIONCODE='$Lokasi' ";
          }

          $no = 1;
          $c = 0;
          //if($Zone=="" and $Lokasi==""){
          //	echo"<script>alert('Zone atau Lokasi belum dipilih');</script>";
          //}else{
          $sqlDB21 = " SELECT  b.*,
            p.LONGDESCRIPTION,
            p.SHORTDESCRIPTION 
            FROM BALANCE b
            LEFT JOIN DB2ADMIN.PRODUCT p
              ON p.ITEMTYPECODE = 'GYR'
              AND p.SUBCODE01 = b.DECOSUBCODE01
              AND p.SUBCODE02 = b.DECOSUBCODE02
              AND p.SUBCODE03 = b.DECOSUBCODE03
              AND p.SUBCODE04 = b.DECOSUBCODE04
              AND p.SUBCODE05 = b.DECOSUBCODE05
              AND p.SUBCODE06 = b.DECOSUBCODE06
              AND p.SUBCODE07 = b.DECOSUBCODE07
            WHERE (b.ITEMTYPECODE='GYR' OR b.ITEMTYPECODE='DYR') AND b.LOGICALWAREHOUSECODE='M011' $Where ";
          $stmt1   = db2_exec($conn1, $sqlDB21, array('cursor' => DB2_SCROLLABLE));
          //}				  
          while ($rowdb21 = db2_fetch_assoc($stmt1)) {
            $kd = trim($rowdb21['DECOSUBCODE01']) . "-" . trim($rowdb21['DECOSUBCODE02']) . "-" . trim($rowdb21['DECOSUBCODE03']) . "-" . trim($rowdb21['DECOSUBCODE04']) . "-" . trim($rowdb21['DECOSUBCODE05']) . "-" . trim($rowdb21['DECOSUBCODE06']) . "-" . trim($rowdb21['DECOSUBCODE07']) . "-" . trim($rowdb21['DECOSUBCODE08']);
            if ($rowdb21['QUALITYLEVELCODE'] == "1") {
              $grd = "A";
            } else if ($rowdb21['QUALITYLEVELCODE'] == "2") {
              $grd = "B";
            } else if ($rowdb21['QUALITYLEVELCODE'] == "3") {
              $grd = "C";
            } else {
              $grd = "";
            }
          ?>
            <tr>
              <td style="text-align: center"><?php echo substr($rowdb21['CREATIONDATETIME'], 0, 10); ?></td>
              <td style="text-align: left"><?php echo $kd; ?></td>
              <td style="text-align: left"><?php echo $rowdb21['LONGDESCRIPTION'] . $rowdb21['SHORTDESCRIPTION']; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['ELEMENTSCODE']; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['LOTCODE']; ?></td>
              <td style="text-align: right"><?php echo round($rowdb21['BASEPRIMARYQUANTITYUNIT'], 2); ?></td>
              <td style="text-align: center"><?php echo $rowdb21['BASEPRIMARYUNITCODE']; ?></td>
              <td style="text-align: right"><?php echo round($rowdb21['BASESECONDARYQUANTITYUNIT'], 2); ?></td>
              <td style="text-align: center"><?php echo $rowdb21['BASESECONDARYUNITCODE']; ?></td>
              <td style="text-align: center"><?php echo $grd; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['WHSLOCATIONWAREHOUSEZONECODE']; ?></td>
              <td style="text-align: center"><?php echo $rowdb21['WAREHOUSELOCATIONCODE']; ?></td>
            </tr>
          <?php $no++;
          } ?>
        </tbody>

      </table>
    </div>
    <!-- /.card-body -->
  </div>
</div><!-- /.container-fluid -->
<!-- /.content -->
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
