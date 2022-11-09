<?php

namespace Classes\Tools;

class RequestManager
{
    public static function SendPostRequest($url, array $params = []): bool|string{
        $params['from'] = 'user';

        $handler = curl_init($url);
        curl_setopt($handler, CURLOPT_POST, true);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handler, CURLOPT_CONNECTTIMEOUT, 5);
//            curl_setopt($handler, CURLOPT_TIMEOUT, 60);
        curl_setopt($handler, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($handler, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($handler);
        return $result;
    }

    private static function apiLink(): string
    {
        $token = getenv('API_TOKEN');
        return "https://api.telegram.org/bot$token/";
    }

    public static function sendTelegramRequest(string $path, array $params = []): bool|string
    {
        $mainUrl = self::apiLink();
        $params['method'] = $path;
        $handler = curl_init($mainUrl);
        curl_setopt($handler, CURLOPT_POST, true);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handler, CURLOPT_CONNECTTIMEOUT, 5);
//            curl_setopt($handler, CURLOPT_TIMEOUT, 60);
        curl_setopt($handler, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($handler, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        //set proxy server
        if(getenv('API_PROXY_SERVER')) {
            //set proxy server
            curl_setopt($handler, CURLOPT_PROXY, getenv('API_PROXY_SERVER'));

            //set proxy protocol
            if(getenv('API_PROXY_PROTOCOL'))
                curl_setopt($handler, CURLOPT_PROXYTYPE, getenv('API_PROXY_PROTOCOL'));
        }

        $result = curl_exec($handler);
        if(getenv('DEBUG')) {
            echo $result;
        }
        return $result;
    }
}