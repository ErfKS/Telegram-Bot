<?php
include ('../Classes/Tools/RequestManager.php');
$url = "https://katebsaber.com/TelegramBot/User/api/v1/sendNotif.php";

$params = [
    'data'=>[
        'type'=>'replay',
        'call_number'=>86
    ]
];

\Classes\Tools\RequestManager::SendPostRequest($url,$params);

/*$header_send = array('Content-Type: application/json');

$handler = curl_init($url);
curl_setopt($handler, CURLOPT_POST, true);
curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($handler, CURLOPT_CONNECTTIMEOUT, 5);
//curl_setopt($handler, CURLOPT_TIMEOUT, 60);
curl_setopt($handler, CURLOPT_POSTFIELDS, json_encode($params));
curl_setopt($handler, CURLOPT_HTTPHEADER, $header_send);
curl_setopt($handler, CURLOPT_POSTREDIR, 3);
curl_setopt($handler, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($handler, CURLOPT_SSL_VERIFYPEER, 0);
$result = curl_exec($handler);
return $result;*/


?>
