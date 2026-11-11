<?php
$host = getenv('DB_HOST'); $port = (int)getenv('DB_PORT');
echo "Resolved: " . gethostbyname($host) . "<br>";

$s = @fsockopen($host, $port, $errno, $errstr, 5);
if (!$s) { die("TCP connect failed: $errno $errstr"); }
stream_set_timeout($s, 5);
$data = fread($s, 64);
echo "Greeting bytes received: " . strlen($data) . "<br>";
fclose($s);

mysqli_report(MYSQLI_REPORT_OFF);
$c = mysqli_init();
$c->real_connect($host, getenv('DB_USERNAME'), getenv('DB_PASSWORD'), getenv('DB_DATABASE'), $port);
echo $c->connect_errno ? "mysqli error: " . $c->connect_error : "mysqli connected OK";