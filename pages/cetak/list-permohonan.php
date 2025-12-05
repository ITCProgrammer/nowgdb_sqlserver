<?php
include '../../koneksi.php';
ini_set("error_reporting", 1);	
require_once('dompdf/autoload.inc.php');
//--
$idkk=$_REQUEST['idkk'];
$act=$_GET['g'];
//-
// Ambil tanggal sekarang (format sama dengan MySQL DATE_FORMAT(now(),'%d %M %Y %H:%i'))
$sql = sqlsrv_query(
    $con,
    "SELECT FORMAT(GETDATE(),'dd MMMM yyyy HH:mm') AS tgl"
);

if ($sql === false) {
    die(print_r(sqlsrv_errors(), true));
}

$data = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC);
?>
<?php 

?>
<?php
  $html ='
<head>
<title>:: Form List Permintaan</title>
<link href="styles_cetak.css" rel="stylesheet" type="text/css">
<style>
body,td,th {
	/* font-family: Courier New, Courier, monospace; */
	font-family: sans-serif, Roman, serif;
	
	font-size: 12px;
}
pre {
	font-family:sans-serif, Roman, serif;
	clear:both;
	margin: 0px auto 0px;
	padding: 0px;
	white-space: pre-wrap;       /* Since CSS 2.1 */
    white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
    white-space: -pre-wrap;      /* Opera 4-6 */
    white-space: -o-pre-wrap;    /* Opera 7 */
    word-wrap: break-word; 
	
}
body{
	margin: 0px auto 0px;
	padding: 2px;
	font-size: 8px;
	color: #000;
	width: 98%;
	background-position: top;
	background-color: #fff;
}
.table-list {
	clear: both;
	text-align: left;
	border-collapse: collapse;
	margin: 0px 0px 10px 0px;
	background:#fff;	
}
.table-list td {
	color: #000000; /*#333*/
	font-size:8px;
	border-color: #fff;
	border-collapse: collapse;
	vertical-align: center;
	padding: 3px 3px;
	border-top:1px #000000 solid;
	border-bottom:1px #000000 solid;
	border-left:1px #000000 solid;
	border-right:1px #000000 solid;

	
}
.table-list1 {
	clear: both;
	text-align: left;
	border-collapse: collapse;
	margin: 0px 0px 5px 0px;
	background:#fff;	
}
.table-list1 td {
	color:#000000; /*#333*/
	font-size:12px;
	border-color: #fff;
	border-collapse: collapse;
	vertical-align: center;
	padding: 3px 3px;
	border-bottom:1px #000000 solid;
	border-top:1px #000000 solid;
	border-left:1px #000000 solid;
	border-right:1px #000000 solid;
	
	
}
.table-list2 {
	clear: both;
	text-align: left;
	border-collapse: collapse;
	margin: 10px 0px 0px 2px;
	background:#fff;	
}
.table-list2 td {
	color:#000000; /*#333*/
	font-size:10px;
	border-color: #fff;
	border-collapse: collapse;
	vertical-align: center;
	padding: 4px 2px;
	border-bottom:1px #000000 solid;
	border-top:1px #000000 solid;
	border-left:1px #000000 solid;
	border-right:1px #000000 solid;
	
	
}
#nocetak {
	display:none;
	}
</style>
<style>
html{margin:2px auto 2px;}
input{
text-align:center;
border:hidden;
}
@media print {
  ::-webkit-input-placeholder { /* WebKit browsers */
      color: transparent;
  }
  :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
      color: transparent;
  }
  ::-moz-placeholder { /* Mozilla Firefox 19+ */
      color: transparent;
  }
  :-ms-input-placeholder { /* Internet Explorer 10+ */
      color: transparent;
  }
  .pagebreak { page-break-before:always; }
  .header {display:block}
  table thead 
   {
    display: table-header-group;
   }
}
</style>
</head>
<body>
<h2>List Permohonan Benang</h2>
Tanggal: '.$data['tgl'].$_POST['cek'][$no].'
<table width="100%" border="0" class="table-list2">
<thead>
	<tr>
      <td  align="center" style="font-size: 9px;"><strong>No</strong></td>
      <td  align="center" style="font-size: 9px;"><strong>Tgl Permintaan Kirim</strong></td>
      <td  align="center" style="font-size: 9px;"><strong>Project</strong></td>
      <td  align="center" style="font-size: 9px;"><strong>No IntDoc</strong></td>
      <td  align="center" style="font-size: 9px;"><strong>Tipe</strong></td>
      <td  align="center" style="font-size: 9px;"><strong>Kd Benang</strong></td>
      <td  align="center" style="font-size: 9px;"><strong>Supplier</strong></td>
      <td  align="center" style="font-size: 9px;"><strong>Lot</strong></td>
      <td  align="center" style="font-size: 9px;"><strong>Kgs</strong></td>
      <td  align="center" style="font-size: 9px;"><strong>Qty</strong></td>
      <td  align="center" style="font-size: 9px;"><strong>Dept</strong></td>
      <td  align="center" style="font-size: 9px;"><strong>Status</strong></td>
      <td  align="center" style="font-size: 9px;"><strong>Blok</strong></td>
	  <td  align="center" style="font-size: 9px;"><strong>Blok Aktual</strong></td>
	  <td  align="center" style="font-size: 9px;"><strong>Q.Lvl</strong></td>
	  <td  align="center" style="font-size: 9px;"><strong>Tgl Masuk</strong></td>
	  <td  align="center" style="font-size: 9px;"><strong>Tgl. Prod.</strong></td>
      <td  align="center" style="font-size: 9px;"><strong>Keterangan</strong></td>
    </tr>	  
</thead>
		  <tbody>';
$no=1; 
$no1=1;
$c=0;
	
	$sqlDB21 = " SELECT x.*, a1.VALUESTRING AS supp
,a2.VALUESTRING AS LOT
,a3.VALUEDECIMAL AS QTY
,a4.VALUESTRING AS LOKASI
,a5.VALUESTRING AS KET
,a6.VALUESTRING AS KDBENANG
,a7.VALUESTRING AS SATUAN
,a8.VALUESTRING AS TGLMASUK
,a9.VALUESTRING AS TGLPROD
FROM DB2ADMIN.INTERNALDOCUMENTLINE x
LEFT OUTER JOIN ADSTORAGE a1 ON a1.UNIQUEID =x.ABSUNIQUEID AND a1.NAMENAME ='SuppName'
LEFT OUTER JOIN ADSTORAGE a2 ON a2.UNIQUEID =x.ABSUNIQUEID AND a2.NAMENAME ='Lot'
LEFT OUTER JOIN ADSTORAGE a3 ON a3.UNIQUEID =x.ABSUNIQUEID AND a3.NAMENAME ='QtyP'
LEFT OUTER JOIN ADSTORAGE a4 ON a4.UNIQUEID =x.ABSUNIQUEID AND a4.NAMENAME ='LokasiB'
LEFT OUTER JOIN ADSTORAGE a5 ON a5.UNIQUEID =x.ABSUNIQUEID AND a5.NAMENAME ='KetB'
LEFT OUTER JOIN ADSTORAGE a6 ON a6.UNIQUEID =x.ABSUNIQUEID AND a6.NAMENAME ='KdBenang'
LEFT OUTER JOIN ADSTORAGE a7 ON a7.UNIQUEID =x.ABSUNIQUEID AND a7.NAMENAME ='Satuan'
LEFT OUTER JOIN ADSTORAGE a8 ON a8.UNIQUEID =x.ABSUNIQUEID AND a8.NAMENAME ='TglMasukB'
LEFT OUTER JOIN ADSTORAGE a9 ON a9.UNIQUEID =x.ABSUNIQUEID AND a9.NAMENAME ='TglProduksiB'
	WHERE INTDOCPROVISIONALCOUNTERCODE='I02M01' AND RECEIVINGDATE > '2022-12-31' AND  
	(DESTINATIONWAREHOUSECODE ='P501' OR DESTINATIONWAREHOUSECODE ='P503' OR DESTINATIONWAREHOUSECODE ='P504' OR DESTINATIONWAREHOUSECODE ='M904' OR DESTINATIONWAREHOUSECODE IS NULL) and WAREHOUSECODE ='M011' and PROGRESSSTATUS ='0'
	ORDER BY x.RECEIVINGDATE DESC,x.CREATIONDATETIME DESC ";
	$stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
	//}				  
    while($rowdb21 = db2_fetch_assoc($stmt1)){	
	$kd= trim($rowdb21['SUBCODE01'])."-".trim($rowdb21['SUBCODE02'])."-".trim($rowdb21['SUBCODE03'])."-".trim($rowdb21['SUBCODE04'])."-".trim($rowdb21['SUBCODE05'])."-".trim($rowdb21['SUBCODE06'])."-".trim($rowdb21['SUBCODE07'])."-".trim($rowdb21['SUBCODE08']);	
	if($rowdb21['PROGRESSSTATUS']=="0"){	
	$stts="<small class='badge badge-info'><i class='far fa-clock blink_me'></i> Entered</small>";	
	}else if($rowdb21['PROGRESSSTATUS']=="1"){	
	$stts="<small class='badge badge-warning'><i class='far fa-clock blink_me'></i> Partially Shipped</small>";	
	}
	$pos1= strpos($rowdb21['ITEMDESCRIPTION'],"*");
	$pot1= substr($rowdb21['ITEMDESCRIPTION'],$pos1+1,200);
	$pos2= strpos($pot1,"*");
	$pot2= substr($pot1,$pos2+1,200);
	$pos3= strpos($pot2,"*");	
	$pot3= substr($pot2,$pos3+1,200);
	$pos4= strpos($pot3,"*");	
	$supp=substr($rowdb21['ITEMDESCRIPTION'],0,$pos1);
	$lot=substr($pot1,0,$pos2);	
	$blok=substr($pot2,0,$pos3);
	$qty=substr($pot3,0,$pos4);	
	$intDoc=trim($rowdb21['INTDOCUMENTPROVISIONALCODE'])."-".$rowdb21['ORDERLINE'];	
if($rowdb21['SATUAN']=="0"){
$satuan	="DUS";
}else if($rowdb21['SATUAN']=="1"){
$satuan	="KARUNG";	}
else if($rowdb21['SATUAN']=="2"){
$satuan	="CONES";	}	
if($rowdb21['QUALITYCODE']=="1"){
		$qly="<font color=red>1</font>";
	}else if($rowdb21['QUALITYCODE']=="2"){
		$qly="<font color=blue>2</font>";
	}else if($rowdb21['QUALITYCODE']=="3"){
		$qly="<font color=green>3</font>";
	}		
if($_POST['cek'][$no]==$no){

    // Ambil lokasi dari SQL Server pengganti GROUP_CONCAT
    $sqllok = sqlsrv_query(
        $con,
        "SELECT STRING_AGG(lokasi, ',') AS lok
         FROM (
             SELECT DISTINCT lokasi
             FROM dbnow_gdb.tblambillokasi
             WHERE no_doc = ?
         ) AS s",
        array($intDoc)
    );

    if ($sqllok === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $rLok = sqlsrv_fetch_array($sqllok, SQLSRV_FETCH_ASSOC);
    $html .='<tr>
      <td align="center" style="font-size: 9px;">'.$no1.'</td>
      <td align="center" style="font-size: 9px;">'.substr($rowdb21['RECEIVINGDATE'],0,10).'</td>
      <td align="center" style="font-size: 9px;">'.$rowdb21['EXTERNALREFERENCE'].'</td>
      <td align="center" style="font-size: 9px;">'.trim($rowdb21['INTDOCUMENTPROVISIONALCODE'])."-".$rowdb21['ORDERLINE'].'</td>
      <td style="font-size: 9px;">'.$rowdb21['ITEMTYPEAFICODE'].'</td>
      <td align="center" style="font-size: 9px;">'.$kd.'<br>'.$rowdb21['KDBENANG'].'</td>
      <td align="right" style="font-size: 9px;">'.$rowdb21['SUPP'].'</td>
	  <td align="center" style="font-size: 9px;">'.$rowdb21['LOT'].'</td>
	  <td style="font-size: 9px;">'.$rowdb21['BASEPRIMARYQUANTITY'].'</td>
      <td align="center" style="font-size: 9px;">'.round($rowdb21['QTY'])." ".$satuan.'</td>
      <td align="center" style="font-size: 9px;">'.$rowdb21['DESTINATIONWAREHOUSECODE'].'</td>
      <td align="right" style="font-size: 9px;">'.$stts.'</td>
      <td align="center" style="font-size: 9px;">'.$rowdb21['LOKASI'].'</td>
	  <td align="center" style="font-size: 9px;">'.$rLok['lok'].'</td>
	  <td align="center" style="font-size: 9px;">'.$qly.'</td>
	  <td align="center" style="font-size: 9px;">'.$rowdb21['TGLMASUK'].'</td>
	  <td align="center" style="font-size: 9px;">'.$rowdb21['TGLPROD'].'</td>
      <td align="center" style="font-size: 9px;">'.$rowdb21['KET'].'</td>
      </tr>
    ';
	$no1++;
}
	$no++;
}
$html .='<tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
	  <td>&nbsp;</td>
      </tr>
</table>	
  </tbody>
Keterangan :
<br>
*) D/K/P/C = Dus/Karung/Palet/Cones<br>

</body>
</html>';
  use Dompdf\Dompdf;
  use Dompdf\Options;

  $options = new Options(); 
  $options->set('isHtml5ParserEnabled', true);
  $options->set('isRemoteEnabled', true);      
  $dompdf = new Dompdf( $options );
  $dompdf->load_html($html); //biar bisa terbaca htmlnya
  $dompdf->set_Paper('letter','portrait'); //portrait, landscape
  $dompdf->render();
  $dompdf->stream('ListPermohonanBenang.pdf', array("Attachment"=>0)); //untuk pemberian nama
?>
<!--<table width="200" border="0" align="right">
         <tr>
            <td style="font-size: 9px;">No. Form</td>
            <td style="font-size: 9px;">:</td>
            <td style="font-size: 9px;">FW-14-KNT-26</td>
  </tr > 
          <tr>
            <td style="font-size: 9px;">No. Revisi</td>
            <td style="font-size: 9px;">:</td>
            <td style="font-size: 9px;">01</td>
          </tr>
          <tr>
            <td style="font-size: 9px;">Tgl. Terbit</td>
            <td style="font-size: 9px;">:</td>
            <td style="font-size: 9px;">&nbsp;</td>
          </tr>
</table>
<br>
<br>
<br>
<br>
<br>-->
