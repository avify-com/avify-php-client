<?php

declare(strict_types=1);

namespace App\Utils;

use App\Utils\Json;

class Curl {
    /**
     * GET request with cURL.
     * 
     * @param string $url
     * @param array  $headers
     * 
     * @return array JSON response with httpCode, success (true/false) and data or error.
     */
    public static function get(string $url, array $headers = null) {
        return self::http_request($url, $headers);
    }

    /**
     * POST request with cURL.
     * 
     * @param string $url
     * @param array  $headers
     * @param string $payload
     * 
     * @return array JSON response with httpCode, success (true/false) and data or error.
     */
    public static function post(string $url, array $headers = null, string $payload = null) {
        return self::http_request($url, $headers, $payload);
    }

    /**
     * Starts a new curl session and handles the request.
     * 
     * @param string $url
     * @param array  $headers
     * @param string $payload
     * 
     * @return array JSON response with httpCode, success (true/false) and data or error.
     */
    private static function http_request(string $url, array $headers = null, string $payload = null) {
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);

        if ($headers) curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);

        if ($payload) {
            curl_setopt($curl_handle, CURLOPT_POST, 1);
            curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $payload);
        }
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);

        $curl_response = curl_exec($curl_handle);
        $http_code = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);

        $message = '';
        $error_code = '';

        if (curl_errno($curl_handle)) {
            $message = curl_error($curl_handle);
        } else {
            $error_code = curl_errno($curl_handle);
            $message = $curl_response;
        }

        $final_response = Json::format_json_response(
            $http_code < 400,
            $http_code,
            $message,
            strval($error_code)
        );

        return $final_response;
    }
}
