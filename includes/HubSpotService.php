<?php

class HubSpotService
{
    private $accessToken;
    private $baseUrl = "https://api.hubapi.com/crm/v3/objects";

    public function __construct()
    {
        $this->accessToken = $_ENV['HUBSPOT_ACCESS_TOKEN'] ?? getenv('HUBSPOT_ACCESS_TOKEN');

        if (!$this->accessToken) {
            throw new Exception("HubSpot Access Token not found.");
        }
    }

    private function request($method, $endpoint, $data = null)
    {
        $url = $this->baseUrl . $endpoint;

        $ch = curl_init($url);

        $headers = [
            "Authorization: Bearer {$this->accessToken}",
            "Content-Type: application/json"
        ];

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        switch ($method) {
            case "POST":
                curl_setopt($ch, CURLOPT_POST, true);
                if ($data) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;

            case "PATCH":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
                if ($data) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;

            case "DELETE":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;

            case "GET":
                break;
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return [
            'status' => $status,
            'response' => json_decode($response, true)
        ];
    }

    public function createContact($firstname, $lastname, $email, $phone, $company, $address, $gst)
    {
        return $this->request("POST", "/contacts", [
            "properties" => [
                "firstname" => $firstname,
                "lastname" => $lastname,
                "email" => $email,
                "phone" => $phone,
                "company" => $company,
                "address" => $address,
                "gst_number" => $gst,
                "ims_status" => "Active"
            ]
        ]);
    }

    public function updateContact(
        $hubspotId,
        $firstname,
        $lastname,
        $email,
        $phone,
        $company,
        $address,
        $gst,
        $imsStatus = "Active"
    ) {
        return $this->request(
            "PATCH",
            "/contacts/" . $hubspotId,
            [
                "properties" => [
                    "firstname" => $firstname,
                    "lastname" => $lastname,
                    "email" => $email,
                    "phone" => $phone,
                    "company" => $company,
                    "address" => $address,
                    "gst_number" => $gst,
                    "ims_status" => $imsStatus
                ]
            ]
        );
    }

// UPDATE STATUS
    public function updateContactStatus($hubspotId, $status)
{
    return $this->request(
        "PATCH",
        "/contacts/" . $hubspotId,
        [
            "properties" => [
                "ims_status" => $status
            ]
        ]
    );
}

// DELETE CONTACT
public function deleteContact($hubspotId)
{
    return $this->request(
        "DELETE",
        "/contacts/" . $hubspotId
    );
}

// CREATE INVOICE
public function createDeal(
    $dealName,
    $amount,
    $closeDate,
    $invoiceId,
    $paymentStatus,
    $invoiceStatus
)
{
    return $this->request(
        "POST",
        "/deals",
        [
            "properties" => [
                "dealname"            => $dealName,
                "amount"              => $amount,
                "closedate"           => $closeDate,
                "invoice_id"          => $invoiceId,
                "payment_status"      => $paymentStatus,
                "ims_invoice_status"  => $invoiceStatus
            ]
        ]
    );
}

// UPDATE INVOICE
public function updateDeal(
    $dealId,
    $dealName,
    $amount,
    $closeDate,
    $invoiceStatus
)
{
    return $this->request(
        "PATCH",
        "/deals/" . $dealId,
        [
            "properties" => [
                "dealname"           => $dealName,
                "amount"             => $amount,
                "closedate"          => $closeDate,
                "ims_invoice_status" => $invoiceStatus
            ]
        ]
    );
}


// ASSOCIATE DEAL WITH CONTACT
public function associateDealWithContact($dealId, $contactId)
{
    $url = "https://api.hubapi.com/crm/v4/objects/deals/{$dealId}/associations/default/contacts/{$contactId}";

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer {$this->accessToken}",
        "Content-Type: application/json"
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception(curl_error($ch));
    }

    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return [
        "status" => $status,
        "response" => json_decode($response, true)
    ];
}

// get pipeline stages id
public function getDealPipelines()
{
    $url = "https://api.hubapi.com/crm/v3/pipelines/deals";

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer {$this->accessToken}",
        "Content-Type: application/json"
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception(curl_error($ch));
    }

    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return [
        "status" => $status,
        "response" => json_decode($response, true)
    ];
}
}