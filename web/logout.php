<?php

require_once "vendor/autoload.php";
require_once "core/autoload.php";
require_once "core/init.php";
if (!Config::get("instance/installed")) {
    Redirect::to("install");
} else {
    $user = new User();
    $user->logout();
    Redirect::to("login");
}

?>
