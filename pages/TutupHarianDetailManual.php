<?php
include"../koneksi.php";
ini_set("error_reporting", 1);
//$Awal = date("Y-m-d");
//$Awal = date("Y-m-d", strtotime("-2 day"));

// Ambil tgl dari GET, kalau kosong pakai kemarin
$Awal = isset($_GET['tgl']) && !empty($_GET['tgl']) 
    ? $_GET['tgl'] 
    : date("Y-m-d", strtotime("-1 day"));


$cektgl = sqlsrv_query_safe(
    $con,
    "SELECT 
        CONVERT(char(10), GETDATE(), 120) as tgl,
        COUNT(tgl_tutup) as ck,
        DATEPART(hour, GETDATE()) as jam,
        CONVERT(char(5), GETDATE(), 108) as jam1
     FROM dbnow_gdb.tblopname_detail_11
     WHERE tgl_tutup='" . sqlsrv_escape_str($Awal) . "'"
);
$dcek = ($cektgl !== false) ? sqlsrv_fetch_array($cektgl, SQLSRV_FETCH_ASSOC) : ['tgl' => $Awal, 'ck' => 0, 'jam' => 0, 'jam1' => '00:00'];
$t1=strtotime($Awal);
$t2=strtotime($dcek['tgl']);
$selh=round(abs($t2-$t1)/(60*60*45));

if($dcek['ck']>0){
	
		echo "Stok Tgl ".$Awal." Ini Sudah Pernah ditutup";
		echo "<script>";		
		echo "setTimeout(function(){ window.close(); }, 5000);";
	    echo "</script>";
	
}else if($dcek['jam'] < 1){	
	
		echo "<script>";
		echo "alert('Tidak Bisa Tutup Sebelum jam 1 Malam, Sekarang Masih Jam ".$dcek['jam1']."')";
		echo "</script>";
	
}else if($_GET['note']!="" or $_GET['note']=="Berhasil"){
	
		echo "Tutup Transaksi Berhasil";
		echo "<script>";		
		echo "setTimeout(function(){ window.close(); }, 5000);";
	    echo "</script>";
	
}else{
	
	$sqlDB21 = " SELECT 
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
b.ELEMENTSCODE,
a.VALUESTRING AS TGLTERIMA,
b.QUALITYLEVELCODE, 
SUM(b.BASEPRIMARYQUANTITYUNIT) AS KGS,
SUM(b.BASESECONDARYQUANTITYUNIT) AS CONES,
COUNT(b.ELEMENTSCODE) AS QTY,
b.WHSLOCATIONWAREHOUSEZONECODE,
b.WAREHOUSELOCATIONCODE FROM BALANCE b 
LEFT OUTER JOIN LOT c ON b.LOTCODE = c.CODE AND c.COMPANYCODE = '100' AND 
b.ITEMTYPECODE = c.ITEMTYPECODE AND
b.DECOSUBCODE01= c.DECOSUBCODE01 AND
b.DECOSUBCODE02= c.DECOSUBCODE02 AND
b.DECOSUBCODE03= c.DECOSUBCODE03 AND
b.DECOSUBCODE04= c.DECOSUBCODE04 AND
b.DECOSUBCODE05= c.DECOSUBCODE05 AND
b.DECOSUBCODE06= c.DECOSUBCODE06 AND
b.DECOSUBCODE07= c.DECOSUBCODE07 AND
b.DECOSUBCODE08= c.DECOSUBCODE08
LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID =c.ABSUNIQUEID AND a.NAMENAME = 'ReceivedDate'
LEFT OUTER JOIN CUSTOMERSUPPLIERDATA d ON c.SUPPLIERCODE =d.CODE AND d.COMPANYCODE = '100' AND d.TYPE = '2'
LEFT OUTER JOIN BUSINESSPARTNER e ON d.BUSINESSPARTNERNUMBERID =e.NUMBERID 
LEFT OUTER JOIN STOCKTRANSACTION s ON b.ELEMENTSCODE =s.ITEMELEMENTCODE AND s.TOKENCODE='RECEIPT' AND s.TEMPLATECODE ='QCT'
WHERE b.ITEMTYPECODE IN('GYR','DYR') AND
b.LOGICALWAREHOUSECODE IN ('M011','M034') AND 
b.WHSLOCATIONWAREHOUSEZONECODE IN ('GB0','GB1','GB2','GB5','GB6','GP1','GR2','GR3','GY1','GW2','GW6','PRT','L1','GR6','GDB','LT','PLG','GBK','PLW')
GROUP BY 
b.QUALITYLEVELCODE,
c.SUPPLIERCODE,
c.LOTCREATIONORDERNUMBER,
s.TRANSACTIONNUMBER,
s.ORDERCODE,
s.ORDERLINE,
e.LEGALNAME1, 
b.LOTCODE, b.ITEMTYPECODE,
b.DECOSUBCODE01, b.DECOSUBCODE02,
b.DECOSUBCODE03, b.DECOSUBCODE04,
b.DECOSUBCODE05, b.DECOSUBCODE06,
b.DECOSUBCODE07, b.DECOSUBCODE08,
b.WHSLOCATIONWAREHOUSEZONECODE,
b.WAREHOUSELOCATIONCODE,
b.ELEMENTSCODE, 
a.VALUESTRING ";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){
	$kd= trim($rowdb21['DECOSUBCODE01'])."-".trim($rowdb21['DECOSUBCODE02'])."-".trim($rowdb21['DECOSUBCODE03'])."-".trim($rowdb21['DECOSUBCODE04'])."-".trim($rowdb21['DECOSUBCODE05'])."-".trim($rowdb21['DECOSUBCODE06'])."-".trim($rowdb21['DECOSUBCODE07'])."-".trim($rowdb21['DECOSUBCODE08']);
	if($rowdb21['QUALITYLEVELCODE']=="1"){
		$grd="A";
	}else if($rowdb21['QUALITYLEVELCODE']=="2"){
		$grd="B";
	}else if($rowdb21['QUALITYLEVELCODE']=="3"){
		$grd="C";
	}else if($rowdb21['QUALITYLEVELCODE']=="4"){
		$grd="D";
	}else if($rowdb21['QUALITYLEVELCODE']=="5"){
		$grd="E";		
	}else{
		$grd="";
	}	
		
	$sqlDB23 = " SELECT FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION 
	   FROM DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER WHERE
       FULLITEMKEYDECODER.ITEMTYPECODE='".trim($rowdb21['ITEMTYPECODE'])."' AND
	   FULLITEMKEYDECODER.SUBCODE01='".trim($rowdb21['DECOSUBCODE01'])."' AND
       FULLITEMKEYDECODER.SUBCODE02='".trim($rowdb21['DECOSUBCODE02'])."' AND
       FULLITEMKEYDECODER.SUBCODE03='".trim($rowdb21['DECOSUBCODE03'])."' AND
	   FULLITEMKEYDECODER.SUBCODE04='".trim($rowdb21['DECOSUBCODE04'])."' AND
       FULLITEMKEYDECODER.SUBCODE05='".trim($rowdb21['DECOSUBCODE05'])."' AND
	   FULLITEMKEYDECODER.SUBCODE06='".trim($rowdb21['DECOSUBCODE06'])."' AND
	   FULLITEMKEYDECODER.SUBCODE07='".trim($rowdb21['DECOSUBCODE07'])."' AND
	   FULLITEMKEYDECODER.SUBCODE08='".trim($rowdb21['DECOSUBCODE08'])."'  
	   ORDER BY FULLITEMKEYDECODER.SUMMARIZEDDESCRIPTION ASC ";
	   $stmt3   = db2_exec($conn1,$sqlDB23, array('cursor'=>DB2_SCROLLABLE));
	   $rowdb23 = db2_fetch_assoc($stmt3);
	   
	   $sqlDB24 = "SELECT
                          mh.CHALLANDATE
                        FROM STOCKTRANSACTION s
                        LEFT JOIN MRNDETAIL md
                          ON s.TRANSACTIONNUMBER = md.TRANSACTIONNUMBER
                        RIGHT JOIN MRNHEADER mh
                          ON md.MRNHEADERCODE = mh.CODE
                        WHERE s.TRANSACTIONNUMBER = '$rowdb21[TRANSACTIONNUMBER]'
                          AND s.ORDERCODE = '$rowdb21[ORDERCODE]'
                          AND s.ORDERLINE = '$rowdb21[ORDERLINE]'
                        GROUP BY
                          mh.CHALLANDATE,
                          s.ORDERCODE,
                          s.ORDERLINE";
       $stmt4 = db2_exec($conn1, $sqlDB24, array('cursor' => DB2_SCROLLABLE));
       $rowdb24 = db2_fetch_assoc($stmt4);	
		
	   if($rowdb24['CHALLANDATE']!=""){
		   $tgl=" tgl = '".$rowdb24['CHALLANDATE']."', ";
	   }else{
		   $tgl=" ";   
	   }
		
		$tglInsert = ($rowdb24['CHALLANDATE'] != "") ? "'" . sqlsrv_escape_str($rowdb24['CHALLANDATE']) . "'" : "NULL";
		$simpan = sqlsrv_query_safe(
			$con,
			"INSERT INTO dbnow_gdb.tblopname_detail_11
				(tipe, tgl, kd_benang, jenis_benang, lot, po, suppliercode, suppliername, qty, weight, cones, grd, zone, lokasi, sn, terima, tgl_tutup, tgl_buat)
			VALUES (
				'" . sqlsrv_escape_str($rowdb21['ITEMTYPECODE']) . "',
				$tglInsert,
				'" . sqlsrv_escape_str($kd) . "',
				'" . sqlsrv_escape_str($rowdb23['SUMMARIZEDDESCRIPTION']) . "',
				'" . sqlsrv_escape_str($rowdb21['LOTCODE']) . "',
				'" . sqlsrv_escape_str($rowdb21['LOTCREATIONORDERNUMBER']) . "',
				'" . sqlsrv_escape_str($rowdb21['SUPPLIERCODE']) . "',
				'" . sqlsrv_escape_str($rowdb21['LEGALNAME1']) . "',
				" . (float)$rowdb21['QTY'] . ",
				" . round($rowdb21['KGS'], 2) . ",
				" . (float)$rowdb21['CONES'] . ",
				'" . sqlsrv_escape_str($grd) . "',
				'" . sqlsrv_escape_str($rowdb21['WHSLOCATIONWAREHOUSEZONECODE']) . "',
				'" . sqlsrv_escape_str($rowdb21['WAREHOUSELOCATIONCODE']) . "',
				'" . sqlsrv_escape_str($rowdb21['ELEMENTSCODE']) . "',
				'" . sqlsrv_escape_str($rowdb21['TGLTERIMA']) . "',
				'" . sqlsrv_escape_str($Awal) . "',
				GETDATE()
			)"
		);	
	
	}
	if($simpan){		
        echo "<meta http-equiv='refresh' content='0; url=TutupHarianDetailManual.php?note=Berhasil'>";
		
		}			
 }

?>
