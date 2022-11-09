<?php

namespace Classes\Users\Login {
    use Classes\Database\DB_Manager;
    use Classes\Pages\Pages;
    use Classes\TelegramAction;
    use Classes\Tools\RequestManager;

    class Login
    {
        private $username;
        private $password;

        public function GetLogin(): bool{
            $text = explode("\n", constant("chat_text"));

            if(count($text) !== 2){
                TelegramAction::sendMessage(constant("chat_id"), "The values entered are incorrect!");
                Pages::GetLogin();
                return true;
            }

            $this->username = $text[0];
            $this->password = $text[1];

            if(DB_Manager::CheckExistUser($this->username,$this->password)){
                DB_Manager::SaveLoginInfo(constant("chat_id"),$this->username,$this->password);

                //after login
                TelegramAction::sendMessage(constant("chat_id"), "Welcome :)");

                return true;
            } else {
                TelegramAction::sendMessage(constant("chat_id"), 'The username or password is incorrect');
                Pages::GetLogin();
            }
            return false;
        }
    }
}