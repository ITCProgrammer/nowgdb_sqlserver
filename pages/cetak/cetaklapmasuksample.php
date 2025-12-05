<?php
ini_set("error_reporting", 1);
session_start();
include '../../koneksi.php';
//--
//$act=$_POST['act'];
$tgl=date("Y-m-d");
?>
<style type="text/css">
.tombolkanan {
	text-align: right;
}
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
<link href="styles_cetak.css" rel="stylesheet" type="text/css">


      <table width="100%" border="0" style="width:9.50in;" >
      <thead>
      <tr>
      <td><table width="100%" border="0" class="table-list1" >
           <tr>
            <td width="6%" ><img src="Indo.jpg" alt="" width="50" height="50"></td>
            <td width="94%"><div align="center">
              <font size="+1"><strong>LAPORAN HARIAN MASUK BENANG</strong><br>
                </font>FW-19-GDB-01/03
            </div></td>
          </tr>
        </table></td>
    </tr>
    </thead>
    <tr>
      <td><table width="100%" border="0" class="table-list1">
       <thead>
        <tr>
          <td colspan="12" class="tombol" style="border-bottom:0px #000 solid;
	border-top:0px #000 solid;
	border-left:0px #000 solid;
	border-right:0px #000 solid;">TGL: <?php echo date('d F Y', strtotime($_GET['tgl1'])); ?></td>
        </tr>
        <tr>
          <td class="tombol"><center>
            <strong>NO</strong>
          </center></td>
          <td class="tombol"><center>
            <strong>No MRN</strong>
          </center></td>
          <td class="tombol"><center>
            <strong>PO</strong>
          </center></td>
          <td class="tombol"><center>
            <strong>NO S. JALAN</strong>
          </center></td>
          <td class="tombol"><center>
            <strong>CODE</strong>
          </center></td>
          <td class="tombol"><center>
            <strong>SUPPLIER</strong>
          </center></td>
          <td class="tombol"><center>
            <strong>JENIS BENANG</strong>
          </center></td>
          <td class="tombol"><center>
            <strong>LOT</strong>
          </center></td>
          <td class="tombol"><center>
            <strong>QTY</strong>
          </center></td>
          <td class="tombol"><center>
            <strong>SATUAN</strong>
          </center></td>
          <td class="tombol"><center>
            <strong>BERAT/Kg</strong>
          </center></td>
          <td class="tombol"><center>
            <strong>BLOCK</strong>
          </center></td>
        </tr>
        </thead>
        <tbody>
<?php  
  $c=0;
  $no=1;			
  $dustot=0;
  $pltot=0;
  $kartot=0;
  $totdus=0;
  $totpl=0;
  $totkar=0;
  $totco=0; 
$sqlDB21 = "  SELECT  x.TRANSACTIONNUMBER, x.TEMPLATECODE, m.PACKINGUMCODE, a1.VALUESTRING AS KETS, a2.VALUESTRING AS SJ, a3.VALUESTRING AS POBENANG, e.LEGALNAME1, SUM(x.BASESECONDARYQUANTITY) AS QTY_CONES, 
  SUM(x.BASEPRIMARYQUANTITY) AS QTY_KG, COUNT(x.ITEMELEMENTCODE) AS QTY_DUS, x.LOTCODE,bc.WHSLOCATIONWAREHOUSEZONECODE,bc.WAREHOUSELOCATIONCODE, x.TRANSACTIONDATE, f.SUMMARIZEDDESCRIPTION, x.ITEMTYPECODE, x.DECOSUBCODE01, x.DECOSUBCODE02, x.DECOSUBCODE03, x.DECOSUBCODE04,
x.DECOSUBCODE05, x.DECOSUBCODE06, x.DECOSUBCODE07, x.DECOSUBCODE08, x.ORDERCOUNTERCODE, x.ORDERCODE   
FROM DB2ADMIN.STOCKTRANSACTION x
LEFT OUTER JOIN DB2ADMIN.MRNDETAIL m ON x.TRANSACTIONNUMBER =m.TRANSACTIONNUMBER 
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE a1 ON x.ABSUNIQUEID = a1.UNIQUEID AND a1.NAMENAME = 'KetSampleGYR'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE a2 ON x.ABSUNIQUEID = a2.UNIQUEID AND a2.NAMENAME = 'SuratJlnGYR'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE a3 ON x.ABSUNIQUEID = a3.UNIQUEID AND a3.NAMENAME = 'SjPoGYR'
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER f ON x.FULLITEMIDENTIFIER = f.IDENTIFIER
LEFT OUTER JOIN LOT c ON x.LOTCODE = c.CODE AND c.COMPANYCODE = '100' AND 
x.ITEMTYPECODE = c.ITEMTYPECODE AND
x.DECOSUBCODE01= c.DECOSUBCODE01 AND
x.DECOSUBCODE02= c.DECOSUBCODE02 AND
x.DECOSUBCODE03= c.DECOSUBCODE03 AND
x.DECOSUBCODE04= c.DECOSUBCODE04 AND
x.DECOSUBCODE05= c.DECOSUBCODE05 AND
x.DECOSUBCODE06= c.DECOSUBCODE06 AND
x.DECOSUBCODE07= c.DECOSUBCODE07 AND
x.DECOSUBCODE08= c.DECOSUBCODE08
LEFT OUTER JOIN DB2ADMIN.BALANCE bc ON bc.ELEMENTSCODE = x.ITEMELEMENTCODE 
LEFT OUTER JOIN CUSTOMERSUPPLIERDATA d ON c.SUPPLIERCODE =d.CODE AND d.COMPANYCODE = '100' AND d.TYPE = '2'
LEFT OUTER JOIN BUSINESSPARTNER e ON d.BUSINESSPARTNERNUMBERID =e.NUMBERID
WHERE x.ITEMTYPECODE = 'GYR'  AND x.LOGICALWAREHOUSECODE='M011' AND ((x.TEMPLATECODE = 'OPN' AND a1.VALUESTRING = '1') OR x.TEMPLATECODE = '101') AND x.TRANSACTIONDATE BETWEEN '$_GET[tgl1]' AND '$_GET[tgl2]'
GROUP BY x.TRANSACTIONNUMBER, x.TEMPLATECODE, m.PACKINGUMCODE, a1.VALUESTRING, a2.VALUESTRING, a3.VALUESTRING, x.TRANSACTIONDATE,e.LEGALNAME1,
bc.WHSLOCATIONWAREHOUSEZONECODE,bc.WAREHOUSELOCATIONCODE, f.SUMMARIZEDDESCRIPTION, x.LOTCODE,x.ITEMTYPECODE, x.DECOSUBCODE01, x.DECOSUBCODE02, x.DECOSUBCODE03, x.DECOSUBCODE04,
x.DECOSUBCODE05, x.DECOSUBCODE06, x.DECOSUBCODE07, x.DECOSUBCODE08, x.ORDERCOUNTERCODE, x.ORDERCODE ";

  $stmt1   = db2_exec($conn1,$sqlDB21, array('cursor'=>DB2_SCROLLABLE));
			
  while($r=db2_fetch_assoc($stmt1)){
  $bgcolor = ($c++ & 1) ? '#33CCFF' : '#FFCC99';
  $cd = trim($r['DECOSUBCODE01'])."-".trim($r['DECOSUBCODE02'])."-"
        .trim($r['DECOSUBCODE03'])."-".trim($r['DECOSUBCODE04'])."-"
        .trim($r['DECOSUBCODE05'])."-".trim($r['DECOSUBCODE06'])."-"
        .trim($r['DECOSUBCODE07'])."-".trim($r['DECOSUBCODE08'])."-"
        .trim($r['DECOSUBCODE09'])."-".trim($r['DECOSUBCODE10']);	
      
    if ($r['DESTINATIONWAREHOUSECODE'] =='M904') { $knitt = 'KNITTING ITTI ATAS- BENANG'; }
    else if($r['DESTINATIONWAREHOUSECODE'] ='P501') { $knitt = 'KNITTING ITTI- BENANG'; }
    else if($r['DESTINATIONWAREHOUSECODE'] ='M051') { $knitt =  'KNITTING A- BENANG'; }
    else if($r['DESTINATIONWAREHOUSECODE'] ='P503') { $knitt =  'YARN DYE'; } 
  $brt=number_format(round($r['QTY_KG'],2),2);	 
  if($r['PACKINGUMCODE']=="BGS"){
	  $stn="KARUNG";
  }else if($r['PACKINGUMCODE']=="BX"){
	  $stn="DUS";
  }else if($r['PACKINGUMCODE']=="con"){
	  $stn="CONES";	  
  }else{
	  $stn="DUS";
  }	  
  $sqlDB22SJ = "SELECT x.CHALLANNO, x.PURCHASEORDERCODE FROM DB2ADMIN.MRNHEADER x
WHERE PURCHASEORDERCOUNTERCODE = '".$r['ORDERCOUNTERCODE']."' AND PURCHASEORDERCODE ='".$r['ORDERCODE']."' ";
              $stmt2SJ = db2_exec($conn1, $sqlDB22SJ, array('cursor' => DB2_SCROLLABLE));
              $rowdb22SJ = db2_fetch_assoc($stmt2SJ);
	  if(!empty($r['SJ']) and $r['TEMPLATECODE']=="OPN" ) { 
			$sj=$r['SJ'] ; $po=$r['POBENANG'];
		}else if(!empty($r['SJ']) and $r['TEMPLATECODE']!="OPN"){
			$sj="SAMPLE" ; $po=$r['ORDERCODE']."-".$r['ORDERLINE'];
		}else{ 
			$sj=$rowdb22SJ['CHALLANNO']; $po=$rowdb22SJ['PURCHASEORDERCODE'];
		}
	  
  echo"<tr valign='top'>
  	<td>$no</td>
    <td><input name=gbp type=text placeholder='Ketik disini' size='15' maxlength='30'></td>
    <td>$po</td>
    <td>$sj</td>
    <td>$cd</td>
    <td>$r[LEGALNAME1]</td>
	<td>$r[SUMMARIZEDDESCRIPTION]</td>
	<td>$r[LOTCODE]</td>
	<td align =right>$r[QTY_DUS]</td>
	<td>$stn</td>
	<td align=right>$brt</td>
	<td>$r[WHSLOCATIONWAREHOUSEZONECODE]-$r[WAREHOUSELOCATIONCODE]</td>
    </tr>";
	if($stn=='DUS')
	{$dustot=$dustot + $r['QTY_DUS']; $totdus = $totdus + $r['QTY_KG']; };
	if($stn=='KARUNG')
	{$kartot=$kartot + $r['QTY_DUS']; $totkar = $totkar + $r['QTY_KG'];}
	if($stn=='PALET')
	{$pltot=$pltot + $r['QTY_DUS'];   $totpl = $totpl + $r['QTY_KG'];}
	if($stn=='CONES')
	{$contot=$contot + $r['QTY_DUS'];   $totcon = $totcon + $r['QTY_KG'];}
	$totr = $totr + $r['QTY_DUS'];  
  	$no++;
  }
  ?>
        <tr>
          <?php for ($i=$no; $i <= 35; $i++)
{ ?>
          <td class="tombol"><?php echo $i;?></td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td align="right" >&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
        </tr>
        <?php }?>
        <tr>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td align="right" ><strong><b>TOTAL :</strong></td>
          <td class="tombol">&nbsp;</td>
          <td class="tombolkanan"><?php echo $dustot; ?></td>
          <td class="tombol"><strong>DUS</strong></td>
          <td class="tombol" align="right"><?PHP echo number_format( $totdus,'2','.',',');?></td>
          <td class="tombol">&nbsp;</td>
        </tr>
        <tr>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td align="right" ><strong>TOTAL :</strong></td>
          <td class="tombol">&nbsp;</td>
          <td class="tombolkanan"><?php echo $kartot; ?></td>
          <td class="tombol"><strong>KARUNG</strong></td>
          <td class="tombol" align="right"><?PHP echo number_format($totkar,'2','.',',');?></td>
          <td class="tombol">&nbsp;</td>
        </tr>
        <tr>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td align="right" ><strong>TOTAL :</strong></td>
          <td class="tombol">&nbsp;</td>
          <td class="tombolkanan"><?php echo $pltot; ?></td>
          <td class="tombol"><strong>PALET</strong></td>
          <td class="tombol" align="right"><?PHP echo number_format($totpl,'2','.',',');?></td>
          <td class="tombol">&nbsp;</td>
        </tr>
        <tr>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td align="right" ><strong>TOTAL :</strong></td>
          <td class="tombol">&nbsp;</td>
          <td class="tombolkanan"><?PHP echo $contot;?></td>
          <td class="tombol"><strong>CONES</strong></td>
          <td class="tombol" align="right"><?PHP echo number_format($totcon,'2','.',',');?></td>
          <td class="tombol">&nbsp;</td>
        </tr>
        <tr>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol">&nbsp;</td>
          <td align="right" ><strong>GRAND TOTAL :</strong></td>
          <td class="tombol">&nbsp;</td>
          <td class="tombolkanan"><?PHP echo $totr;?></td>
          <td class="tombol">&nbsp;</td>
          <td class="tombol" align="right"><?php $grand= $totdus + $totpl + $totkar + $totcon ;
	
	echo number_format($grand,'2','.',','); ?></td>
          <td class="tombol">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><table width="100%" border="0" class="table-list1">
        <tr>
          <td width="15%">&nbsp;</td>
          <td width="31%"><div align="center">DIBUAT OLEH:</div></td>
          <td width="27%"><div align="center">DIPERIKSA OLEH:</div></td>
          <td width="27%"><div align="center">DIKETAHUI OLEH:</div></td>
        </tr>
        <tr>
          <td>NAMA</td>
          <td><div align="center">
            <input name=nama type=text placeholder="Ketik disini" size="33" maxlength="30">
          </div></td>
          <td><div align="center">
            <input name=nama3 type=text placeholder="Ketik disini" size="33" maxlength="30">
          </div></td>
          <td><div align="center">
            <input name=nama5 type=text placeholder="Ketik disini" size="33" maxlength="30">
          </div></td>
        </tr>
        <tr>
          <td>JABATAN</td>
          <td><div align="center">
            <input name=nama2 type=text placeholder="Ketik disini" size="33" maxlength="30">
          </div></td>
          <td><div align="center">
            <input name=nama4 type=text placeholder="Ketik disini" size="33" maxlength="30">
          </div></td>
          <td><div align="center">
            <input name=nama6 type=text placeholder="Ketik disini" size="33" maxlength="30">
          </div></td>
        </tr>
        <tr>
          <td>TANGGAL</td>
          <td><div align="center">
            <input type="text" name="date" placeholder="dd-mm-yyyy" onKeyUp="
  var date = this.value;
  if (date.match(/^\d{2}$/) !== null) {
     this.value = date + '-';
  } else if (date.match(/^\d{2}\-\d{2}$/) !== null) {
     this.value = date + '-';
  }" maxlength="10">
          </div></td>
          <td><div align="center">
            <input type="text" name="date" placeholder="dd-mm-yyyy" onKeyUp="
  var date = this.value;
  if (date.match(/^\d{2}$/) !== null) {
     this.value = date + '-';
  } else if (date.match(/^\d{2}\-\d{2}$/) !== null) {
     this.value = date + '-';
  }" maxlength="10">
          </div></td>
          <td><div align="center">
            <input type="text" name="date" placeholder="dd-mm-yyyy" onKeyUp="
  var date = this.value;
  if (date.match(/^\d{2}$/) !== null) {
     this.value = date + '-';
  } else if (date.match(/^\d{2}\-\d{2}$/) !== null) {
     this.value = date + '-';
  }" maxlength="10">
          </div></td>
        </tr>
        <tr>
          <td height="60" valign="top">TANDA TANGAN</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
		  </tbody>
</table>



        


