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
        $createDate,
        $invoiceSubtotal,
        $invoiceTaxTotal,
        $paymentStatus,
        $invoiceStatus
    ) {
        return $this->request(
            "POST",
            "/deals",
            [
                "properties" => [
                    "dealname" => $dealName,
                    "amount" => $amount,
                    "closedate" => $closeDate,
                    "invoice_id" => $invoiceId,
                    "createdate" => $createDate,
                    "invoice_subtotal" => $invoiceSubtotal,
                    "invoice_tax_total" => $invoiceTaxTotal,
                    "payment_status" => $paymentStatus,
                    "ims_invoice_status" => $invoiceStatus,
                    "pipeline" => "default",
                    "dealstage" => $this->getDealStageId($invoiceStatus)
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
        $invoiceId,
        $createDate,
        $invoiceSubtotal,
        $invoiceTaxTotal,
        $invoiceStatus
    ) {
        return $this->request(
            "PATCH",
            "/deals/" . $dealId,
            [
                "properties" => [
                    "dealname" => $dealName,
                    "amount" => $amount,
                    "closedate" => $closeDate,
                    "invoice_id" => $invoiceId,
                    "createdate" => $createDate,
                    "invoice_subtotal" => $invoiceSubtotal,
                    "invoice_tax_total" => $invoiceTaxTotal,
                    "ims_invoice_status" => $invoiceStatus,
                    "pipeline" => "default",
                    "dealstage" => $this->getDealStageId($invoiceStatus)
                ]
            ]
        );
    }

    // UPDATE DEAL STAGE
    public function updateDealStage($dealId, $status)
    {
        return $this->request(
            "PATCH",
            "/deals/" . $dealId,
            [
                "properties" => [
                    "dealstage" => $this->getDealStageId($status)
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

    // pipeline stages id
    private function getDealStageId($status)
    {
        $stageMap = [
            "Draft" => "appointmentscheduled",
            "Sent" => "4122041058",
            "Overdue" => "4122041059",
            "Paid" => "4131113684",
            "Cancelled" => "4122041060"

        ];

        return $stageMap[$status] ?? "appointmentscheduled";
    }

    // UPDATE DEAL PAYMENT STATUS
    public function updateDealPaymentStatus($dealId, $paymentStatus)
    {
        return $this->request(
            "PATCH",
            "/deals/" . $dealId,
            [
                "properties" => [
                    "payment_status" => $paymentStatus
                ]
            ]
        );
    }

    // UPDATE DEAL PAYMENT ATTEMPT STATUS
    public function updateDealPaymentAttemptStatus($dealId, $attemptStatus)
    {
        return $this->request(
            "PATCH",
            "/deals/" . $dealId,
            [
                "properties" => [
                    "payment_attempt_status" => $attemptStatus
                ]
            ]
        );
    }

    // CREATE HUBSPOT PRODUCT
    public function createProduct(
        $name,
        $sku,
        $price,
        $tax
    ) {
        return $this->request(
            "POST",
            "/products",
            [
                "properties" => [
                    "name" => $name,
                    "hs_sku" => $sku,
                    "price" => $price,
                    "ims_tax_rate" => $tax
                ]
            ]
        );
    }

    // UPDATE HUBSPOT PRODUCT
    public function updateProduct(
        $hubspotProductId,
        $name,
        $sku,
        $price,
        $tax = ''
    ) {
        return $this->request(
            "PATCH",
            "/products/" . $hubspotProductId,
            [
                "properties" => [
                    "name" => $name,
                    "hs_sku" => $sku,
                    "price" => $price,
                    "ims_tax_rate" => $tax
                ]
            ]
        );
    }

    // CREATE HUBSPOT LINE ITEM
    public function createLineItem(
        $productId,
        $name,
        $quantity,
        $price,
        $tax = null
    ) {
        $properties = [
            "hs_product_id" => $productId,
            "name" => $name,
            "quantity" => $quantity,
            "price" => $price
        ];

        // Add tax only if provided
        if ($tax !== null && $tax !== '') {
            $properties["ims_tax_rate"] = $tax;
        }

        return $this->request(
            "POST",
            "/line_items",
            [
                "properties" => $properties
            ]
        );
    }


    // ASSOCIATE LINE ITEM WITH DEAL
    public function associateLineItemWithDeal(
        $lineItemId,
        $dealId
    ) {
        $url = "https://api.hubapi.com/crm/v4/objects/line_items/{$lineItemId}/associations/default/deals/{$dealId}";

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


    // GET LINE ITEMS ASSOCIATED WITH DEAL
    public function getDealLineItems($dealId)
    {
        $url = "https://api.hubapi.com/crm/v4/objects/deals/{$dealId}/associations/line_items";

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


    // DELETE HUBSPOT LINE ITEM
    public function deleteLineItem($lineItemId)
    {
        return $this->request(
            "DELETE",
            "/line_items/" . $lineItemId
        );
    }
}