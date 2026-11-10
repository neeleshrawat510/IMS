<?php
$host = "mysql.railway.internal";
foreach ([3306, 8080] as $p) {
    $s = @fsockopen($host, $p, $errno, $errstr, 3);
    if (!$s) { echo "$p: can't connect ($errstr)<br>"; continue; }
    stream_set_timeout($s, 3);
    $banner = fread($s, 50);
    echo "$p: open, banner = " . htmlspecialchars(bin2hex(substr($banner, 0, 20))) . "<br>";
    fclose($s);
}
echo "DB_HOST=" . getenv('DB_HOST') . " DB_PORT=" . getenv('DB_PORT');