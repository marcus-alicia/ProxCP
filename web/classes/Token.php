<?php

class Token
{
    public static function generate()
    {
        return Session::put(Config::get("session/token_name"), md5(uniqid()));
    }
    public static function check($token)
    {
        $_obfuscated_0D191E3212382331090A2B290B01175B065C0A061E3E32_ = Config::get("session/token_name");
        if (Session::exists($_obfuscated_0D191E3212382331090A2B290B01175B065C0A061E3E32_) && $token === Session::get($_obfuscated_0D191E3212382331090A2B290B01175B065C0A061E3E32_)) {
            Session::delete($_obfuscated_0D191E3212382331090A2B290B01175B065C0A061E3E32_);
            return true;
        }
        return false;
    }
}

?>
