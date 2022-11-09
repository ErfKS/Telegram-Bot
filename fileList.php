<?php
include 'config.php';

function import($path): void
{
    foreach (glob("$path/*.php") as $filename)
    {
        include $filename;
    }
}
import("./Classes");
import("./Classes/Users");
import("./Classes/Database");
import("./Classes/Pages");
import("./Classes/Tools");
import("./Classes/Routes");
import("./Classes/Routes/Providers");

?>
