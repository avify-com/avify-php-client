<?php

declare(strict_types=1);

namespace App\Utils;

class Json {
    /**
     * Checks if the given string has a valid JSON structure.
     * 
     * @param string $value
     * 
     * @return bool True if the given string has a valid JSON structure, false otherwise.
     */
    public static function is_json(string $value) {
        json_decode($value);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Creates a formatted JSON response.
     * 
     * @param bool   $success
     * @param int    $http_code
     * @param string $message
     * @param string $error_code
     * 
     * @return array JSON response with httpCode, success (true/false) and data or error.
     */
    public static function format_json_response(
        bool $success,
        int $http_code,
        string $message,
        string $error_code = '',
        string $developerMessage = ''
    ) {
        $response = array('success' => $success, 'httpCode' => $http_code);

        if ($success) {
            if (self::is_json($message)) {
                $response['data'] = json_decode($message, true);
            } else {
                $response['data'] = $message;
            }
        } else {
            if (self::is_json($message)) {
                $response = array_merge($response, json_decode($message, true));
            } else {
                $response['error'] = array('displayMessage' => $message, 'code' => $error_code);
                if (!empty($developerMessage)) {
                    $response['error']['developerMessage'] = $developerMessage;
                }
            }
        }
        return $response;
    }
}
