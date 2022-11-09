<?php
namespace Classes {
    use Classes\Pages\Pages;

    class CommandManager
    {
        public static function IsCommand(string $value): bool
        {
            if (str_contains($value, "/")) {
                return true;
            }
            return false;
        }

        public static function runCommend()
        {
            $text = str_replace("/", "", constant("chat_text"));

            switch ($text) {
                case "start":
                    Pages::FirstEnter();
                    return;
            }

            if(getenv("DEBUG")) {
                echo "invalid command<br>";
            }
            TelegramAction::sendMessage(constant("chat_id"), "This command does not exist");
        }
    }
}