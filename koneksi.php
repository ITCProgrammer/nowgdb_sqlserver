<?php
date_default_timezone_set('Asia/Jakarta');

/* Koneksi SQL Server TM (lama, saat ini tidak dipakai)
$host   = "10.0.0.4";
$username = "timdit";
$password = "4dm1n";
$db_name  = "TM";
$connInfo = array("Database" => $db_name, "UID" => $username, "PWD" => $password);
$conn     = sqlsrv_connect($host, $connInfo);
*/

// Koneksi DB2 (tetap seperti sebelumnya)
$hostname    = "10.0.0.21";
$database    = "NOWPRD";
$user        = "db2admin";
$passworddb2 = "Sunkam@24809";
$port        = "25000";

$conn_string = "DRIVER={IBM ODBC DB2 DRIVER}; HOSTNAME=$hostname; PORT=$port; PROTOCOL=TCPIP; UID=$user; PWD=$passworddb2; DATABASE=$database;";
$conn1       = db2_connect($conn_string, '', '');

if (!$conn1) {
    exit("DB2 Connection failed");
}

// Koneksi SQL Server baru (NOW gdb)
$hostSVR19    = "10.0.0.221";
$usernameSVR19 = "sa";
$passwordSVR19 = "Ind@taichen2024";
$dbnow_gdb     = "dbnow_gdb";

$db_dbnow_gdb = array(
    "Database" => $dbnow_gdb,
    "UID"      => $usernameSVR19,
    "PWD"      => $passwordSVR19,
);

$con = sqlsrv_connect($hostSVR19, $db_dbnow_gdb);

if ($con === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Helper untuk handle DateTime dari sqlsrv
function fmt_date($value, $format = 'Y-m-d')
{
    if ($value instanceof DateTime) {
        return $value->format($format);
    }
    if ($value === null) {
        return '';
    }
    if (is_string($value)) {
        $trim = trim($value);
        // Samakan perilaku lama: tanggal kosong atau 0000-00-00 tidak ditampilkan
        if ($trim === '' || substr($trim, 0, 10) === '0000-00-00') {
            return '';
        }
    }

    return $value;
}

// Helper sederhana untuk escape string saat build query manual
function sqlsrv_escape_str($value)
{
    if ($value === null) {
        return '';
    }

    return str_replace("'", "''", trim((string) $value));
}

// Helper untuk menghasilkan 'value' atau NULL (string SQL)
function sqlsrv_val_or_null($value)
{
    if ($value === null || $value === '') {
        return 'NULL';
    }

    return "'" . sqlsrv_escape_str($value) . "'";
}

?>
