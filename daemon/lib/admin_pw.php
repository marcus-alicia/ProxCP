<?php

if (php_sapi_name() == "cli") {
    list($newpassword, $userid) = $argv;
    $load = file_get_contents(dirname(__FILE__, 2) . "/config.js");
    $pos_sqlhost = strrpos($load, "sqlHost");
    $pos_sqlhost = substr($load, $pos_sqlhost);
    list($sqlhost) = explode("'", $pos_sqlhost);
    $pos_sqluser = strrpos($load, "sqlUser");
    $pos_sqluser = substr($load, $pos_sqluser);
    list($sqluser) = explode("'", $pos_sqluser);
    $pos_sqlpw = strrpos($load, "sqlPassword");
    $pos_sqlpw = substr($load, $pos_sqlpw);
    list($sqlpw) = explode("'", $pos_sqlpw);
    $pos_sqldb = strrpos($load, "sqlDB");
    $pos_sqldb = substr($load, $pos_sqldb);
    list($sqldb) = explode("'", $pos_sqldb);
    $salt = salt();
    $pw = _obfuscated_0D5B2B1E1F02130E1522133C0F240E2C16213213181601_($newpassword, $salt);
    $con = mysqli_connect($sqlhost, $sqluser, $sqlpw);
    mysqli_select_db($con, $sqldb);
    if (!$con) {
        exit("100");
    }
    $query = mysqli_query($con, "UPDATE `vncp_users` SET `password` = '" . $pw . "', `salt` = '" . $salt . "' WHERE `id` = " . $userid);
    mysqli_close($con);
}
function _obfuscated_0D5B2B1E1F02130E1522133C0F240E2C16213213181601_($string, $salt = "")
{
    return hash("sha256", $string . $salt);
}
function salt()
{
    return uniqid(mt_rand(), true);
}

?>
