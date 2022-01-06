<?php

declare(strict_types=1);

namespace App\Utils;

class Json
{
    public static function isJSON($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public static function formatJSONResponse(bool $success, int $httpCode, string $message, int $errorCode = 0)
    {
        $response = [
            'success' => $success,
            'httpCode' => $httpCode
        ];

        if ($success) {
            if (self::isJSON($message)) {
                $response['data'] = json_decode($message, true);
            } else {
                $response['data'] = $message;
            }
        } else {
            if (self::isJSON($message)) {
                $response = array_merge($response, json_decode($message, true));
            } else {
                $response['error'] = [
                    'displayMessage' => $message,
                    'code' => $errorCode,
                ];
            }
        }
        return $response;
    }
}
