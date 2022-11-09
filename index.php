<?php

include "fileList.php";

Config::runConfig();

use Classes\InputManager;
use Classes\Database\DB_Manager;
use Classes\TelegramAction;

$content = file_get_contents("php://input");

$update = json_decode($content, true);

define('chat_id',$update["message"]['chat']['id']??null);
define('chat_text',$update["message"]['text']??null);
define('input_text',$update??null);
define('chat_is_bot',$update['message']['from']['is_bot']?1:0);
define('chat_first_name',$update['message']['from']['first_name']??null);
define('chat_last_name',$update['message']['from']['last_name']??null);
define('chat_username',$update['message']['from']['username']??null);
define('chat_language_code',$update['message']['from']['language_code']??null);

try {
    if(constant('chat_is_bot')){
        throw new Exception("Sending messages from the bot side is not accepted.");
    }
    if(getenv('USER_DATABASE')) {
        DB_Manager::StartConnect();
    }
    InputManager::runInput();

} catch (Exception $ex){
    $message = $ex->getMessage();
    TelegramAction::sendMessage(constant("chat_id"),"خطا:\n$message");
}

?>
