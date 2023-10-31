<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "lilib/proxcp_lilib_c.php";
require_once "lilib/proxcp_lilib_f.php";
if (Cookie::exists(Config::get("remember/cookie_name")) && !Session::exists(Config::get("session/session_name"))) {
    $hash = Cookie::get(Config::get("remember/cookie_name"));
    $hashCheck = DB::getInstance()->get("vncp_users_session", ["hash", "=", $hash]);
    if ($hashCheck->count()) {
        $user = new User($hashCheck->first()->user_id);
        $user->login();
    }
}

?>
