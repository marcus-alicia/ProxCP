<?php

class Session
{
    public static function exists($name)
    {
        return isset($_SESSION[$name]) ? true : false;
    }
    public static function put($name, $value)
    {
        $_SESSION[$name] = $value;
        return $_SESSION[$name];
    }
    public static function get($name)
    {
        return $_SESSION[$name];
    }
    public static function delete($name)
    {
        if (self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }
    public static function flash($name, $contents = "")
    {
        if (self::exists($name)) {
            $_obfuscated_0D261A3C11013918232D08193E223F182C0202170E0611_ = self::get($name);
            self::delete($name);
            return $_obfuscated_0D261A3C11013918232D08193E223F182C0202170E0611_;
        }
        self::put($name, $string);
    }
}

?>
