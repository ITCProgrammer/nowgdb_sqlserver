<!-- Main content -->
      <div class="container-fluid">		
	    <div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Data Permohonan Benang</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
          <table id="example1" class="table table-sm table-bordered table-striped" style="font-size:13px;">
                  <thead>
                  <tr>
                    <th style="text-align: center">No</th>
                    <th style="text-align: center">Tgl Buat</th>
                    <th style="text-align: center">Project</th>
                    <th style="text-align: center">No IntDoc</th>
                    <th style="text-align: center">Dept</th>
                    <th style="text-align: center">Status</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
					  
   $no=1;   
   $c=0;
	
	$sqlDB21 = " SELECT x.*,RID_BIT(x) as RID_BIT FROM DB2ADMIN.INTERNALDOCUMENT x 
	WHERE PROVISIONALCOUNTERCODE ='I02M01' AND PROVISIONALDOCUMENTDATE > '2022-12-31' AND 
	(ORDPRNCUSTOMERSUPPLIERCODE='M904' OR ORDPRNCUSTOMERSUPPLIERCODE='P501'OR ORDPRNCUSTOMERSUPPLIERCODE='P504')
	ORDER BY PROVISIONALDOCUMENTDATE DESC LIMIT 500";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){	
	if($rowdb21['PROGRESSSTATUS']=="0"){	
	$stts="<small class='badge badge-info'><i class='far fa-clock blink_me'></i> Entered</small>";	
	}else if($rowdb21['PROGRESSSTATUS']=="1"){	
	$stts="<small class='badge badge-warning'><i class='far fa-clock blink_me'></i> Partially Shipped</small>";	
	}else if($rowdb21['PROGRESSSTATUS']=="2"){	
	$stts="<small class='badge badge-success'><i class='far fa-clock blink_me'></i> Shipped</small>";	
	}
	
?>
	  <tr>
      <td style="text-align: center"><?php echo $no;?></td>
      <td style="text-align: center"><?php echo substr($rowdb21['PROVISIONALDOCUMENTDATE'],0,10); ?></td>
      <td style="text-align: left"><?php echo $rowdb21['EXTERNALREFERENCE']; ?></td>
      <td style="text-align: left"><?php echo $rowdb21['PROVISIONALCODE']; ?></td>
      <td style="text-align: center"><?php echo $rowdb21['ORDPRNCUSTOMERSUPPLIERCODE']; ?></td>
      <td style="text-align: center"><?php echo $stts;?></td>
      </tr>				  
<?php	$no++;} ?>
				  </tbody>
                  
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