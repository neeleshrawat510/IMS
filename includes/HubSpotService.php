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
                "gst_number" => $gst
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
        $gst
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
                    "gst_number" => $gst
                ]
            ]
        );
    }
}