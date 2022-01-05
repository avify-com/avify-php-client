<?php

declare(strict_types=1);

namespace App\Utils;

class Curl
{
    public static function get(string $url, array $headers = null)
    {
        return self::httpRequest($url, $headers);
    }

    public static function post(string $url, array $headers = null, string $payload = null)
    {
        return self::httpRequest($url, $headers, $payload);
    }

    private static function httpRequest(string $url, array $headers = null, string $payload = null)
    {
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);

        if ($headers) curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);

        if ($payload) {
            curl_setopt($curlHandle, CURLOPT_POST, 1);
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $payload);
        }
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);

        $curlResponse = curl_exec($curlHandle);
        $httpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
        $finalResponse = [];

        if ($curlResponse === false) {
            $finalResponse = [
                'error' => [
                    'message' => curl_error($curlHandle),
                    'code' => curl_errno($curlHandle)
                ],
                'success' => false,
                'httpCode' => $httpCode
            ];
        } else if ($curlResponse === true) {
            // Worked, but no data...
            $finalResponse = null;
        } else {
            $finalResponse = json_decode($curlResponse, true);
        }

        return $finalResponse;
    }
}
