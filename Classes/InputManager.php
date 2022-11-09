<?php
namespace Classes {

    use Classes\Pages\PageManager;

    class InputManager
    {
        public static function runInput()
        {
            if (constant("chat_text") !== null) {
                if (CommandManager::IsCommand(constant("chat_text"))) {
                    CommandManager::runCommend();
                    return;
                }
            }

            if(PageManager::CheckInput()) {
                return;
            }

            if(getenv("DEBUG")) {
                echo "invalid input<br>";
            }

            TelegramAction::sendMessage(constant("chat_id"), "Your sent message is incorrect");
        }
    }
}