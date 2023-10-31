<?php

require_once dirname(__FILE__, 2) . "/lib/pve2_api.class.php";
require_once dirname(__FILE__, 2) . "/lib/MagicCrypt.php";
if (php_sapi_name() == "cli") {
    $all_pools = json_decode($argv[1]);
    $all_nodes = json_decode($argv[2]);
    $all_types = json_decode($argv[3]);
    $all_current = json_decode($argv[4]);
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
        exit("300");
    }
    $vmids = _obfuscated_0D22321006210D1640220531333F28211113293C3B2132_($con, $all_pools, $all_nodes);
    if ($vmids) {
        $netouts = _obfuscated_0D0A2E29241B0D38103C31040B0F0823252C365C381F32_($con, $vmids, $all_nodes, $all_types);
        if ($netouts) {
            for ($i = 0; $i < count($netouts); $i++) {
                $new_current = "";
                if ((int) $netouts[$i] == 0 && (int) $all_current[$i] == 0) {
                    $new_current = 0;
                } else {
                    if ((int) $netouts[$i] == 0 && (int) $all_current[$i] != 0) {
                        $new_current = (int) $all_current[$i];
                    } else {
                        if ((int) $all_current[$i] < (int) $netouts[$i]) {
                            $new_current = (int) $all_current[$i] + (int) $netouts[$i] - (int) $all_current[$i];
                        } else {
                            if ((int) $netouts[$i] < (int) $all_current[$i]) {
                                $new_current = (int) $all_current[$i] + (int) $netouts[$i];
                            } else {
                                $new_current = (int) $all_current[$i];
                            }
                        }
                    }
                }
                $query = mysqli_query($con, "UPDATE vncp_bandwidth_monitor SET current = " . (int) $new_current . " WHERE pool_id = '" . mysqli_real_escape_string($con, $all_pools[$i]) . "'");
                if (!$query) {
                    exit("400");
                }
            }
            mysqli_close($con);
        } else {
            exit("500");
        }
    } else {
        exit("600");
    }
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
function _obfuscated_0D22321006210D1640220531333F28211113293C3B2132_($con, $all_pools, $all_nodes)
{
    if (0 < count($all_pools) && 0 < count($all_nodes) && count($all_pools) == count($all_nodes)) {
        $vmids = [];
        for ($i = 0; $i < count($all_pools); $i++) {
            $query = mysqli_query($con, "SELECT * FROM vncp_nodes WHERE name = '" . mysqli_real_escape_string($con, $all_nodes[$i]) . "' LIMIT 1");
            if (!$query) {
                exit("100");
            }
            $_obfuscated_0D082D170E13371C260621211B36080C150C0521363532_ = mysqli_fetch_array($query);
            $_obfuscated_0D06181D1E1931182A2B0817270F5B1C22032721172C11_ = new PVE2_API($_obfuscated_0D082D170E13371C260621211B36080C150C0521363532_["hostname"], $_obfuscated_0D082D170E13371C260621211B36080C150C0521363532_["username"], $_obfuscated_0D082D170E13371C260621211B36080C150C0521363532_["realm"], _obfuscated_0D26311B3802180B1D1615353611253932110F1E5B1C22_($_obfuscated_0D082D170E13371C260621211B36080C150C0521363532_["password"]));
            $_obfuscated_0D06181D1E1931182A2B0817270F5B1C22032721172C11_->login();
            $_obfuscated_0D110B403B36222D22022D242711040430251732353422_ = $_obfuscated_0D06181D1E1931182A2B0817270F5B1C22032721172C11_->get("/pools/" . $all_pools[$i]);
            array_push($vmids, $_obfuscated_0D110B403B36222D22022D242711040430251732353422_["members"][0]["vmid"]);
        }
        if (count($vmids) == count($all_pools)) {
            return $vmids;
        }
    }
    return false;
}
function _obfuscated_381F32_($con, $vmids, $all_nodes, $all_types)
{
    if (0 < count($vmids) && 0 < count($all_nodes) && count($vmids) == count($all_nodes)) {
        $netouts = [];
        for ($i = 0; $i < count($vmids); $i++) {
            $query = mysqli_query($con, "SELECT * FROM vncp_nodes WHERE name = '" . mysqli_real_escape_string($con, $all_nodes[$i]) . "' LIMIT 1");
            if (!$query) {
                exit("200");
            }
            $_obfuscated_0D082D170E13371C260621211B36080C150C0521363532_ = mysqli_fetch_array($query);
            $_obfuscated_0D06181D1E1931182A2B0817270F5B1C22032721172C11_ = new PVE2_API($_obfuscated_0D082D170E13371C260621211B36080C150C0521363532_["hostname"], $_obfuscated_0D082D170E13371C260621211B36080C150C0521363532_["username"], $_obfuscated_0D082D170E13371C260621211B36080C150C0521363532_["realm"], _obfuscated_0D26311B3802180B1D1615353611253932110F1E5B1C22_($_obfuscated_0D082D170E13371C260621211B36080C150C0521363532_["password"]));
            $_obfuscated_0D06181D1E1931182A2B0817270F5B1C22032721172C11_->login();
            $_obfuscated_0D110B403B36222D22022D242711040430251732353422_ = $_obfuscated_0D06181D1E1931182A2B0817270F5B1C22032721172C11_->get("/nodes/" . $all_nodes[$i] . "/" . $all_types[$i] . "/" . $vmids[$i] . "/status/current");
            array_push($netouts, $_obfuscated_0D110B403B36222D22022D242711040430251732353422_["netout"]);
        }
        if (count($netouts) == count($vmids)) {
            return $netouts;
        }
    }
    return false;
}

?>
