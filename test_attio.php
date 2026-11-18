<?php
require __DIR__ . '/includes/AttioClient.php';

// tiny .env loader (sirf local testing ke liye)
foreach (@file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
    if ($line[0] !== '#' && strpos($line, '=') !== false) {
        putenv(trim($line));
    }
}

$attio = new AttioClient();

// 1. token check
$self = $attio->get('/self');
echo "Token active: " . var_export($self['active'] ?? null, true) . PHP_EOL;

// 2. upsert a person by email
$res = $attio->put('/objects/people/records', [
    'data' => ['values' => [
        'name' => [[
            'first_name' => 'PHP',
            'last_name'  => 'Test',
            'full_name'  => 'PHP Test',
        ]],
        'email_addresses' => [['email_address' => 'php.test@example.com']],
    ]],
], ['matching_attribute' => 'email_addresses']);

echo "Record ID: " . $res['data']['id']['record_id'] . PHP_EOL;