<?php

class Config{
    public static function runConfig(){
        $conf = [
            "API_PROXY_SERVER"=>"127.0.0.1:10809",
            "API_PROXY_PROTOCOL"=> CURLPROXY_HTTP,

            'API_TOKEN' => '5775578948:AAGtaiDNL4utzLmn-c7XWuqwivkhBfhGEgA',

            'USER_DATABASE' => false,
            'DB_SERVER'=>'localhost:3306',
            'DB_USERNAME'=>'root',
            'DB_PASSWORD'=>'',
            'DB_NAME'=>'telegram_bot',

            'DEBUG'=> true,

            'LOGIN_URL' => 'http://127.0.0.1:8000/api/v1/login/user',
        ];

        foreach ($conf as $key=>$value){
            putenv("$key=$value");
        }
    }
}

?>
