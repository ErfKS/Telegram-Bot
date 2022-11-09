<?php

namespace Classes\Tools;

class FileManager
{
    public static function DownloadTempImageFile($url,$file_name): bool|string
    {
        $dir = "Storage/Temp/img/";
        if ( !is_dir( $dir ) ) {
            mkdir( $dir ,0777, true);
        }

        // Use file_get_contents() function to get the file
        // from url and use file_put_contents() function to
        // save the file by using base name
        if (file_put_contents("$dir$file_name", file_get_contents($url)))
        {
            return "https://katebsaber.com/TelegramBot/User/Storage/Temp/img/$file_name";
        }
        return false;
    }

    public static function DeleteTempImageFile($file_name): bool|string
    {
        $dir = "Storage/Temp/img/";
        if ( !is_dir( $dir ) ) {
            return false;
        }
        return unlink($dir.$file_name);
    }
}