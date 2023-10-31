<?php

if (php_sapi_name() == "cli") {
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
    $con = mysqli_connect($sqlhost, $sqluser, $sqlpw);
    mysqli_select_db($con, $sqldb);
    if (!$con) {
        exit("100");
    }
    $query = mysqli_query($con, "SELECT * FROM vncp_bandwidth_monitor WHERE suspended=1");
    if (!$query) {
        exit("200");
    }
    while ($row = mysqli_fetch_assoc($query)) {
        if ($row["current"] <= $row["max"]) {
            $query = mysqli_query($con, "UPDATE vncp_bandwidth_monitor SET suspended=0 WHERE hb_account_id=" . $row["hb_account_id"]);
            if (!$query) {
                exit("300");
            }
            if ($row["ct_type"] == "lxc") {
                $query = mysqli_query($con, "UPDATE vncp_lxc_ct SET suspended=0 WHERE hb_account_id=" . $row["hb_account_id"]);
            } else {
                $query = mysqli_query($con, "UPDATE vncp_kvm_ct SET suspended=0 WHERE hb_account_id=" . $row["hb_account_id"]);
            }
        }
    }
    mysqli_close($con);
}

?>
