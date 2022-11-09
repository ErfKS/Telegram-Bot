<?php

namespace Classes\Pages {

    use Classes\Database\DB_Manager;
    use Classes\TelegramAction;

    class Pages
    {
        public static function FirstEnter(): void
        {
            if(DB_Manager::CheckIsUserLogged(constant("chat_id"))){
                $res = TelegramAction::sendMessage(constant("chat_id"),"شما قبلاً وارد شدید!");
                if(self::isError($res)){
                    return;
                }
            } else{
                TelegramAction::sendMessage(constant("chat_id"),"Welcome to Telegram Bot\nMade by Erfan Kateb Saber\n\n<a href='https://t.me/E_K_S_Channel'>Telegram Channel</a>\n<a href='https://instagram.com/e_k_s_insta?igshid=1iqahjuw9481z'>Instagram</a>\n<a href='https://github.com/ErfKS'>Github</a>");
                self::FirstPage();
            }

        }
        public static function FirstPage(): void
        {
            if (!DB_Manager::CheckIsUserLogged(constant("chat_id"))) {
                $res = TelegramAction::reply_markup(constant("chat_id"), "Please select one of the following button:", [
                    'resize_keyboard' => true,
                    'keyboard' => [
                        [['text'=>'Telegram Channel','web_app'=>['url'=>'https://t.me/E_K_S_Channel']],['text'=>'Instagram','web_app'=>['url'=>'https://instagram.com/e_k_s_insta']]],
                        [['text'=>'Github','web_app'=>['url'=>'https://github.com/ErfKS']]]
                    ],
                    'one_time_keyboard' => true
                ]);
                if(self::isError($res)){
                    return;
                }
                DB_Manager::UpdateChatStatus(constant("chat_id"), "first_page");
            } else {
                self::AlradyLogged();
            }
        }

        public static function GetLogin(): void
        {
            if (!DB_Manager::CheckIsUserLogged(constant("chat_id"))) {
                $res = TelegramAction::reply_markup(constant("chat_id"), "Enter your mobile phone number in the first line and your password in the second line", [
                    'resize_keyboard' => true,
                    'keyboard' => [
                        ["🔙Back"]
                    ],
                    'one_time_keyboard' => true
                ]);
                if(self::isError($res)){
                    return;
                }
                DB_Manager::UpdateChatStatus(constant("chat_id"), "login");
            } else {
                self::AlradyLogged();
            }
        }

        private static function isError($data){
            if(!json_decode($data)->ok){
                if(getenv('DEBUG')) {
                    $code = json_decode($data)->error_code;
                    $description = json_decode($data)->description;
                    TelegramAction::sendMessage(constant("chat_id"),"<b>Error</b>:\n\n code: $code\ndescription: $description");
                } else {
                    TelegramAction::sendMessage(constant("chat_id"),"An error occurred!\nPlease try again");
                }
                return true;
            }
        }

        public static function GetLogout(): void {
            if (DB_Manager::CheckIsUserLogged(constant("chat_id"))) {
                $res = TelegramAction::reply_markup(constant("chat_id"), "Are your sure you want to Logout?", [
                    'resize_keyboard' => true,
                    'keyboard' => [
                        ["Yes","No"]
                    ],
                    'one_time_keyboard' => true
                ]);
                if(self::isError($res)){
                    return;
                }
                DB_Manager::UpdateChatStatus(constant("chat_id"), "logout");
            } else {
                self::NeedToLoggin();
            }
        }

        public static function logout(): void
        {
            $res = TelegramAction::sendMessage(constant("chat_id"),"You have been logged out");
            if(self::isError($res)){
                return;
            }
            DB_Manager::LogoutUser(constant("chat_id"));
            self::FirstPage();
        }

        private static function AlradyLogged(): void
        {
            $res = TelegramAction::sendMessage(constant("chat_id"), "You are already logged in!");
            if(self::isError($res)){
                return;
            }

        }

        private static function NeedToLoggin(): void
        {
            $res = TelegramAction::sendMessage(constant("chat_id"), "You need to register");
            if(self::isError($res)){
                return;
            }
            self::FirstPage();
        }
    }
}