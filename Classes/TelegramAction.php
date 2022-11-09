<?php

namespace Classes {

    use Classes\Tools\RequestManager;

    class TelegramAction
    {
        private static function apiLink(): string
        {
            $token = getenv('API_TOKEN');
            return "https://api.telegram.org/bot$token/";
        }

        public static function getMe()
        {
            return RequestManager::sendTelegramRequest("getMe");
        }

        public static function sendMessage(string $chat_id, string $text)
        {
            $params = array(
                'chat_id' => $chat_id,
                'text' => $text,
                'parse_mode'=>'HTML'
            );
            return RequestManager::sendTelegramRequest("sendMessage",$params);
        }

        public static function reply_markup(string $chat_id, string $text, array $replay_markup)
        {
            $params = array(
                'chat_id' => $chat_id,
                'text' => $text,
                'reply_markup' => $replay_markup,
                'parse_mode'=>'HTML'
            );
            return RequestManager::sendTelegramRequest("sendMessage",$params);
        }

        public static function sendPhoto(string $chat_id,string $photo , string $caption = ''): bool|string
        {
            $TELEGRAM = self::apiLink();
            $query = http_build_query(array(
                'chat_id'=> $chat_id,
                'photo'=> $photo,
                'caption'=>$caption,
                'parse_mode'=>'HTML'
            ));
            $response = file_get_contents($TELEGRAM."sendPhoto?$query");
            return $response;
        }
    }
}