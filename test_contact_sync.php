<?php
require __DIR__ . '/includes/attio_sync.php';

foreach (@file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
    if ($line[0] !== '#' && strpos($line, '=') !== false) putenv(trim($line));
}

$attio = new AttioClient();
$fake = [
    'id' => 9999, 'name' => 'Sync Tester', 'number' => '9876543210',
    'email' => 'sync.tester@example.com', 'gst' => '22AAAAA0000A1Z5',
    'address' => 'Test Street, Panchkula',
];
echo syncContactToAttio($attio, $fake) . PHP_EOL;