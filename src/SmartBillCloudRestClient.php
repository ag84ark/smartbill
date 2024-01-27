<?php

namespace Ag84ark\SmartBill;

use Ag84ark\SmartBill\Exceptions\SmartbillApiException;

class SmartBillCloudRestClient
{
    public const API_ENDPOINT = 'https://ws.smartbill.ro/SBORO/api';
    public const EMAIL_URL = 'https://ws.smartbill.ro/SBORO/api/document/send';
    public const DEBUG_ON_ERROR = false; // use this only in development phase; DON'T USE IN PRODUCTION !!!

    /**
     * HTTP Methods
     */
    public const HTTP_GET = "GET";
    public const HTTP_POST = "POST";
    public const HTTP_DELETE = "DELETE";
    public const HTTP_PUT = "PUT";

    private string $hash = '';

    public function __construct($user, $token)
    {
        $this->hash = base64_encode($user.':'.$token);
    }

    private function _cURL($url, $data, $request, $headAccept)
    {
        $url = self::API_ENDPOINT.$url;
        $headers = [$headAccept, 'Authorization: Basic '.$this->hash];

        $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_MUTE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        if (! empty($data)) {
            $headers[] = 'Content-Type: application/json; charset=utf-8';
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        if (! empty($request)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // debugging
        if (self::DEBUG_ON_ERROR) {
            $debug = [
                'URL: ' => $url,
                'headers: ' => $headAccept,
                'method: ' => $request,
                'data: ' => $data,
            ];
            echo '<pre>' , print_r($debug, true), '</pre>';
        }

        return $ch;
    }

    /**
     * @throws SmartbillApiException
     */
    private function _callServer($url, $data = '', $request = '', $headAccept = 'Accept: application/json')
    {
        if (empty($url)) {
            return false;
        }

        $ch = $this->_cURL($url, $data, $request, $headAccept);
        $return = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status != 200) {
            $errorMessage = json_decode($return, true);

            if (false !== strpos($url, self::EMAIL_URL)) {
                $errorMessage = ! empty($errorMessage['status']['code']) ? $errorMessage['status']['message'] : $return;
            } else {
                $errorMessage = ! empty($errorMessage['errorText']) ? $errorMessage['errorText'] : $return;
            }

            throw new SmartbillApiException($errorMessage, $status);
        } elseif (false === strpos($url, '/pdf?')) {
            $return = json_decode($return, true);
        }

        return $return;
    }

    public function performHttpCall($httpMethod, $url, $httpBody, $acceptHeader = 'Accept: application/json', $headers = null)
    {
        return $this->_callServer($url, $httpBody, $httpMethod, $acceptHeader);
    }
}
