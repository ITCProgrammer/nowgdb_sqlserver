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
$hostname    = "db2-db-prd.indotaichen.com";
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
$hostSVR19    = "sql-db-prd.indotaichen.com";
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

// Helper pembungkus sqlsrv_query dengan logging + notifikasi ringan
if (!function_exists('sqlsrv_query_safe')) {
    /**
     * @param resource     $conn  Koneksi sqlsrv
     * @param string       $sql   Query yang akan dieksekusi
     * @param string       $ctx   Info singkat (opsional) untuk log
     * @param array|null   $params Parameterized values (opsional)
     *
     * @return resource|false
     */
    function sqlsrv_query_safe($conn, $sql, $ctx = '', array $params = null)
    {
        static $notif_shown = false;

        $stmt = $params === null
            ? sqlsrv_query($conn, $sql)
            : sqlsrv_query($conn, $sql, $params);
        if ($stmt === false) {
            $err = print_r(sqlsrv_errors(), true);
            // ambil info pemanggil untuk debug cepat
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $caller = isset($trace[1]['file'])
                ? ($trace[1]['file'] . ':' . ($trace[1]['line'] ?? ''))
                : '';
            $msg = 'SQLSRV query failed' . ($ctx ? " ($ctx)" : '') . ($caller ? " @ $caller" : '') . ': ' . $err . ' | SQL: ' . $sql;
            error_log($msg);

            if (!$notif_shown) {
                $safeCtx = $ctx ? " [$ctx]" : '';
                $safeCaller = $caller ? " @ $caller" : '';
                echo "<script>console.error('DB error{$safeCtx}{$safeCaller}. Lihat log server untuk detail.');</script>";
                $notif_shown = true;
            }
        }

        return $stmt;
    }
}

// Helper insert log ke dbnow_gdb.log_activity (hanya kolom ip & time)
if (!function_exists('log_activity')) {
    /**
     * Contoh pakai: log_activity($con, $_SERVER['REMOTE_ADDR'] ?? null);
     *
     * @param resource $conn koneksi sqlsrv ke dbnow_gdb
     * @param string   $ip   alamat IP yang dicatat
     * @param string   $ctx  konteks opsional untuk log error
     *
     * @return resource|false
     */
    function log_activity($conn, $ip, $ctx = 'log_activity')
    {
        if (!$conn || $ip === null || $ip === '') {
            return false;
        }

        $sql = "
IF EXISTS (SELECT 1 FROM dbnow_gdb.log_activity WHERE ip = ?)
    UPDATE dbnow_gdb.log_activity SET [time] = GETDATE() WHERE ip = ?
ELSE
    INSERT INTO dbnow_gdb.log_activity (ip, [time]) VALUES (?, GETDATE())";

        return sqlsrv_query_safe($conn, $sql, $ctx, [$ip, $ip, $ip]);
    }
}

// Catat akses ke koneksi (log setiap file ini di-include)
$__ip = $_SERVER['REMOTE_ADDR'] ?? '';
if ($__ip !== '') {
    log_activity($con, $__ip, 'koneksi include');
}

?>
