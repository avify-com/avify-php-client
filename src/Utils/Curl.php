<?php

declare(strict_types=1);

namespace App\Utils;

use App\Utils\Json;

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
        $message = '';
        $errorCode = 0;

        if (curl_errno($curlHandle)) {
            $message = curl_error($curlHandle);
        } else {
            $errorCode = curl_errno($curlHandle);
            $message = $curlResponse;
        }
        $finalResponse = Json::formatJSONResponse($httpCode < 400, $httpCode, $message, $errorCode);

        return $finalResponse;
    }
}
