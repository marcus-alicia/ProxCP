<?php

class Config
{
    public static function get($path = NULL)
    {
        if ($path) {
            $_obfuscated_0D322B1539141D361001291C3112041E312C0B2E213D11_ = $GLOBALS["config"];
            $path = explode("/", $path);
            foreach ($path as $_obfuscated_0D1E3D1B212F1E03361940153C0C192C341C0D2E2B2832_) {
                if (isset($_obfuscated_0D322B1539141D361001291C3112041E312C0B2E213D11_[$_obfuscated_0D1E3D1B212F1E03361940153C0C192C341C0D2E2B2832_])) {
                    $_obfuscated_0D322B1539141D361001291C3112041E312C0B2E213D11_ = $_obfuscated_0D322B1539141D361001291C3112041E312C0B2E213D11_[$_obfuscated_0D1E3D1B212F1E03361940153C0C192C341C0D2E2B2832_];
                }
            }
            return $_obfuscated_0D322B1539141D361001291C3112041E312C0B2E213D11_;
        } else {
            return false;
        }
    }
}

?>
