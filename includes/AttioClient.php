<?php

class AttioApiException extends RuntimeException
{
    public int $status;
    public string $errorCode;

    public function __construct(int $status, string $errorCode, string $message)
    {
        parent::__construct($message, $status);
        $this->status = $status;
        $this->errorCode = $errorCode;
    }
}

class AttioClient
{
    private string $baseUrl = 'https://api.attio.com/v2';
    private string $token;

    public function __construct(?string $token = null)
    {
        $this->token = $token ?? ($_ENV['ATTIO_API_KEY'] ?? (getenv('ATTIO_API_KEY') ?: ''));
        if ($this->token === '') {
            throw new RuntimeException('ATTIO_API_KEY is not set');
        }
    }

    public function get(string $path, array $query = []): array
    {
        return $this->request('GET', $path, null, $query);
    }

    public function post(string $path, array $body): array
    {
        return $this->request('POST', $path, $body);
    }

    public function put(string $path, array $body, array $query = []): array
    {
        return $this->request('PUT', $path, $body, $query);
    }

    private function request(string $method, string $path, ?array $body = null, array $query = []): array
    {
        $url = $this->baseUrl . $path;
        if ($query) {
            $url .= '?' . http_build_query($query);
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->token,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
        ]);
        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $raw = curl_exec($ch);
        if ($raw === false) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new RuntimeException('Network error: ' . $err);
        }
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($raw, true) ?? [];

        if ($status >= 400) {
            throw new AttioApiException(
                $status,
                $data['code'] ?? 'unknown_error',
                $data['message'] ?? 'Attio request failed'
            );
        }

        return $data;
    }
}