<!DOCTYPE html>
<html>
<head>
	<title>Import Excel ke SQL Server dengan PHP</title>
</head>
<body>
	<style type="text/css">
		body{
			font-family: sans-serif;
		}
 
		p{
			color: green;
		}
	</style>
	<h2>IMPORT EXCEL KE SQL SERVER DENGAN PHP</h2>
	<h3>Contoh sederhana upload data pegawai</h3>
 
	<?php 
	if(isset($_GET['berhasil'])){
		echo "<p>".$_GET['berhasil']." Data berhasil di import.</p>";
	}
	?>
 
	<a href="upload.php">IMPORT DATA</a>
	<table border="1">
		<tr>
			<th>No</th>
			<th>Nama</th>
			<th>ALamat</th>
			<th>Telepon</th>
		</tr>
		<?php 
		include 'koneksi.php';
		$no=1;
		$data = sqlsrv_query_safe($mysqli,"select * from data_pegawai");
		while($data !== false && ($d = sqlsrv_fetch_array($data, SQLSRV_FETCH_ASSOC))){
			?>
			<tr>
				<th><?php echo $no++; ?></th>
				<th><?php echo $d['nama']; ?></th>
				<th><?php echo $d['alamat']; ?></th>
				<th><?php echo $d['telepon']; ?></th>
			</tr>
			<?php 
		}
		?>
 
	</table>
 
	<p style="font-size:12px;">Catatan: contoh ini hanya untuk lingkungan test.</p>

</body>
</html>
