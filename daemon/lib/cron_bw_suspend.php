<?php

require_once dirname(__FILE__, 2) . "/lib/pve2_api.class.php";
require_once dirname(__FILE__, 2) . "/lib/MagicCrypt.php";
if (php_sapi_name() == "cli") {
    $overids = json_decode($argv[1]);
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
    for ($i = 0; $i < count($overids); $i++) {
        $query = mysqli_query($con, "SELECT * FROM vncp_nodes WHERE name = '" . $overids[$i]->node . "' LIMIT 1");
        if (!$query) {
            exit("200");
        }
        $node_data = mysqli_fetch_array($query);
        $pxAPI = new PVE2_API($node_data["hostname"], $node_data["username"], $node_data["realm"], _obfuscated_0D26311B3802180B1D1615353611253932110F1E5B1C22_($node_data["password"]));
        $pxAPI->login();
        $getdata = $pxAPI->get("/pools/" . $overids[$i]->pool_id);
        $vmid = $getdata["members"][0]["vmid"];
        $pxAPI->post("/nodes/" . $overids[$i]->node . "/" . $overids[$i]->ct_type . "/" . $vmid . "/status/stop", []);
        if ($overids[$i]->ct_type == "lxc") {
            $query = mysqli_query($con, "UPDATE vncp_lxc_ct SET suspended=1 WHERE hb_account_id=" . $overids[$i]->hb_account_id);
            if (!$query) {
                exit("300");
            }
        } else {
            $query = mysqli_query($con, "UPDATE vncp_kvm_ct SET suspended=1 WHERE hb_account_id=" . $overids[$i]->hb_account_id);
            if (!$query) {
                exit("400");
            }
        }
        $query = mysqli_query($con, "UPDATE vncp_bandwidth_monitor SET suspended=1 WHERE hb_account_id=" . $overids[$i]->hb_account_id);
        if (!$query) {
            exit("500");
        }
    }
    mysqli_close($con);
}
function _obfuscated_0D1C3E3F2E160C083112081723103D05351D2738302211_()
{
    $load = file_get_contents(dirname(__FILE__, 2) . "/config.js");
    $pos = strrpos($load, "vncp_secret_key");
    $pos = substr($load, $pos);
    list($pos) = explode("'", $pos);
    list($key) = explode(".", $pos);
    list($_obfuscated_0D093F5B36255C1D0A0B383E04322D5B0813222B020732_) = explode(".", $pos);
    return [$key, $_obfuscated_0D093F5B36255C1D0A0B383E04322D5B0813222B020732_];
}
function _obfuscated_0D26311B3802180B1D1615353611253932110F1E5B1C22_($ciphertext)
{
    $load = _obfuscated_0D1C3E3F2E160C083112081723103D05351D2738302211_();
    list($key, $_obfuscated_0D093F5B36255C1D0A0B383E04322D5B0813222B020732_) = $load;
    $_obfuscated_0D14120C380939243D213D3C303001291E0A3731291A22_ = new org\magiclen\magiccrypt\MagicCrypt($key, 256, $_obfuscated_0D093F5B36255C1D0A0B383E04322D5B0813222B020732_);
    return $_obfuscated_0D14120C380939243D213D3C303001291E0A3731291A22_->decrypt($ciphertext);
}

?>
