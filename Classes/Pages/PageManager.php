<?php

namespace Classes\Pages;

use Classes\Database\DB_Manager;
use Classes\Users\Login\Login;

class PageManager
{
    public static $login_script;
    public static function CheckInput(){
        $status = DB_Manager::GetChatStatus(constant("chat_id"));

        switch ($status) {
            case "first_page":
                return self::firstPage();
            case "login":
                return self::login();
            case "logout":
                return self::logout();
        }

        return false;
    }

    private static function firstPage(): bool
    {
        $text = str_replace("/", "", constant("chat_text"));
        switch ($text) {
            case "Login":
                Pages::GetLogin();
                return true;
            case "Register":

                return true;
        }

        return false;
    }

    private static function login(): bool
    {
        $text = str_replace("/", "", constant("chat_text"));
        switch ($text) {
            case "🔙Back":
                Pages::FirstPage();
                return true;
        }
        self::$login_script = new Login();
        return self::$login_script->GetLogin();
    }

    private static function logout(): bool
    {
        $text = str_replace("/", "", constant("chat_text"));
        switch ($text) {
            case "No⛔":

                return true;
            case "Yes👍🏻":
                Pages::logout();
                return true;
        }

        return false;
    }
}