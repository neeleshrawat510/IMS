<?php
require_once __DIR__ . '/AttioClient.php';

/**
 * Ek contact (DB row) ko Attio People mein upsert karta hai.
 * Success par Attio ki record_id return karta hai.
 */
function syncContactToAttio(AttioClient $attio, array $c): string
{
    // 1. Email: It's a matching key and can't be sync without it
    $email = strtolower(trim($c['email'] ?? ''));
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException("Contact {$c['id']}: valid email missing");
    }

    // 2. Break name into first and last
    $fullName = trim($c['name'] ?? '');
    $parts = preg_split('/\s+/', $fullName, 2);
    $first = $parts[0] ?? '';
    $last  = $parts[1] ?? '';

    // 3. Create Values 
    $values = [
        'name' => [[
            'first_name' => $first,
            'last_name'  => $last,
            'full_name'  => $fullName,
        ]],
        'email_addresses' => [['email_address' => $email]],
        'ims_contact_id'  => [['value' => (string)$c['id']]],
    ];

    // 4. Optional fields: dont send if empty
    if (!empty($c['gst'])) {
        $values['gst_number'] = [['value' => trim($c['gst'])]];
    }
    if (!empty($c['address'])) {
        $values['address'] = [['value' => trim($c['address'])]];
    }
    $phone = normalizePhone($c['number'] ?? '');
    if ($phone !== null) {
        $values['phone_numbers'] = [['original_phone_number' => $phone]];
    }

    // 5. Upsert: email matches - update, else create
    $res = $attio->put(
        '/objects/people/records',
        ['data' => ['values' => $values]],
        ['matching_attribute' => 'email_addresses']
    );

    return $res['data']['id']['record_id'];
}

function normalizePhone(string $raw): ?string
{
    $raw = trim($raw);
    if ($raw === '') return null;
    $digits = preg_replace('/\D/', '', $raw);
    if (str_starts_with($raw, '+')) return '+' . $digits;
    if (strlen($digits) === 10) return '+91' . $digits;
    if (strlen($digits) === 12 && str_starts_with($digits, '91')) return '+' . $digits;
    return null; 
}