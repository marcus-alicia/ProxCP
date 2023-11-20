<?php

define("constant-fw", true);
require_once "vendor/autoload.php";
require_once "core/autoload.php";
require_once "core/init.php";
require_once "core/session.php";
if (!Config::get("instance/installed")) {
    Redirect::to("install");
} else {
    $connection = mysqli_connect(Config::get("database/host"), Config::get("database/username"), Config::get("database/password"));
    mysqli_select_db($connection, Config::get("database/db"));
    $l_array = get_license_info($connection, 0);
    if ($l_array["notification_case"] != "notification_license_ok") {
        mysqli_close($connection);
        exit("License check failed: " . $l_array["notification_text"]);
    }
    $user = new User();
    if (!$user->isLoggedIn()) {
        Redirect::to("login");
    }
}
$db = DB::getInstance();
$log = new Logger();
$appname = $db->get("vncp_settings", ["item", "=", "app_name"])->first()->value;
$gid = false;
$vid = false;
if (isset($_GET["id"]) && isset($_GET["virt"])) {
    $gid = true;
    $vid = true;
}
$results = $db->get("vncp_lxc_ct", ["user_id", "=", $user->data()->id]);
$data = $results->all();
$noLogin = false;
$vmtable = ["lxc" => [], "kvm" => []];
for ($i = 0; $i < count($data); $i++) {
    $noLogin = false;
    if ($data[$i]->suspended == 0) {
        $nodename = $data[$i]->node;
        $node_results = $db->get("vncp_nodes", ["name", "=", $nodename]);
        $node_data = $node_results->first();
        try {
            $pxAPI = new PVE2_API($node_data->hostname, $node_data->username, $node_data->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($node_data->password));
        } catch (Exception $e) {
            echo "ProxCP Exception:<br><br>";
            echo $e->getMessage();
        }
        if (!$pxAPI->login()) {
            $noLogin = true;
        }
        if (!$noLogin) {
            $vminfo = $pxAPI->get("/pools/" . $data[$i]->pool_id);
            $info = $pxAPI->get("/nodes/" . $data[$i]->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/status/current");
            if ($data[$i]->hb_account_id == $_GET["id"]) {
                $fwhost = $info["name"];
            } else {
                $fwhost = "";
            }
            $vmtable["lxc"][] = ["noLogin" => false, "hbid" => $data[$i]->hb_account_id, "name" => $info["name"], "ip" => $data[$i]->ip];
        } else {
            $vmtable["lxc"][] = ["noLogin" => true, "hbid" => $data[$i]->hb_account_id, "ip" => $data[$i]->ip];
        }
    }
}
$results = $db->get("vncp_kvm_ct", ["user_id", "=", $user->data()->id]);
$data = $results->all();
for ($i = 0; $i < count($data); $i++) {
    $noLogin = false;
    if ($data[$i]->suspended == 0) {
        $nodename = $data[$i]->node;
        $node_results = $db->get("vncp_nodes", ["name", "=", $nodename]);
        $node_data = $node_results->first();
        try {
            $pxAPI = new PVE2_API($node_data->hostname, $node_data->username, $node_data->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($node_data->password));
        } catch (Exception $e) {
            echo "ProxCP Exception:<br><br>";
            echo $e->getMessage();
        }
        if (!$pxAPI->login()) {
            $noLogin = true;
        }
        if (!$noLogin) {
            $vminfo = $pxAPI->get("/pools/" . $data[$i]->pool_id);
            if (count($vminfo["members"]) == 1) {
                $info = $pxAPI->get("/nodes/" . $data[$i]->node . "/qemu/" . $vminfo["members"][0]["vmid"] . "/status/current");
                if ($data[$i]->hb_account_id == $_GET["id"]) {
                    $fwhost = $info["name"];
                } else {
                    $fwhost = "";
                }
                $vmtable["kvm"][] = ["noLogin" => false, "hbid" => $data[$i]->hb_account_id, "name" => $info["name"], "ip" => $data[$i]->ip];
            } else {
                for ($j = 0; $j < count($vminfo["members"]); $j++) {
                    if ($vminfo["members"][$j]["name"] == $data[$i]->cloud_hostname) {
                        $info = $pxAPI->get("/nodes/" . $data[$i]->node . "/qemu/" . $vminfo["members"][$j]["vmid"] . "/status/current");
                        if ($data[$i]->hb_account_id == $_GET["id"]) {
                            $fwhost = $info["name"];
                        } else {
                            $fwhost = "";
                        }
                        $vmtable["kvm"][] = ["noLogin" => false, "hbid" => $data[$i]->hb_account_id, "name" => $info["name"], "ip" => $data[$i]->ip];
                    }
                }
            }
        } else {
            $vmtable["kvm"][] = ["noLogin" => true, "hbid" => $data[$i]->hb_account_id, "ip" => $data[$i]->ip];
        }
    }
}
$fwsetting = $db->get("vncp_settings", ["item", "=", "enable_firewall"])->first()->value;
$include_tpl = "fw-vmtable.tpl";
if (isset($_GET["virt"]) && $_GET["virt"] == "lxc") {
    if (isset($_GET["id"])) {
        $results = $db->get("vncp_lxc_ct", ["hb_account_id", "=", parse_input($_GET["id"])]);
        $data = $results->first();
        if ($data->user_id == $user->data()->id) {
            $include_tpl = "fw-lxcvm.tpl";
        }
    }
} else {
    if (isset($_GET["virt"]) && $_GET["virt"] == "kvm" && isset($_GET["id"])) {
        $results = $db->get("vncp_kvm_ct", ["hb_account_id", "=", parse_input($_GET["id"])]);
        $data = $results->first();
        if ($data->user_id == $user->data()->id) {
            $include_tpl = "fw-kvmvm.tpl";
        }
    }
}
$enable_forward_dns = parse_input($db->get("vncp_settings", ["item", "=", "enable_forward_dns"])->first()->value);
$enable_reverse_dns = parse_input($db->get("vncp_settings", ["item", "=", "enable_reverse_dns"])->first()->value);
$enable_notepad = parse_input($db->get("vncp_settings", ["item", "=", "enable_notepad"])->first()->value);
$enable_status = parse_input($db->get("vncp_settings", ["item", "=", "enable_status"])->first()->value);
$isAdmin = $user->hasPermission("admin");
$constants = false;
if (defined("constant") || defined("constant-fw")) {
    $constants = true;
}
$aclsetting = $db->get("vncp_settings", ["item", "=", "user_acl"])->first()->value;
$L = new Language($user->data()->language);
$L = $L->load();
if (!$L) {
    $log->log("Could not load language " . $user->data()->language, "error", 2, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
    exit("Language \"" . $user->data()->language . "\" not found.");
}
echo $twig->render("firewall.tpl", ["appname" => $appname, "gid" => $gid, "vid" => $vid, "base" => Config::get("instance/base"), "vmtable" => $vmtable, "fwhost" => $fwhost, "fwsetting" => $fwsetting, "include_tpl" => $include_tpl, "adminBase" => Config::get("admin/base"), "enable_firewall" => $fwsetting, "enable_forward_dns" => $enable_forward_dns, "enable_reverse_dns" => $enable_reverse_dns, "enable_notepad" => $enable_notepad, "enable_status" => $enable_status, "isAdmin" => $isAdmin, "constants" => $constants, "aclsetting" => $aclsetting, "username" => $user->data()->username, "pagename" => "Manage Firewall", "L" => $L]);
if ($fwsetting == "true") {
    echo "<div class=\"row\">";
    if ($include_tpl == "fw-lxcvm.tpl") {
        require_once "fw-lxcvm.php";
    } else {
        if ($include_tpl == "fw-kvmvm.tpl") {
            require_once "fw-kvmvm.php";
        } else {
            require_once "fw-vmtable.php";
        }
    }
    echo "</div>";
} else {
    echo "<p>User-managed firewalls are not enabled.</p>";
}
echo "</div></div></div><input type=\"hidden\" value=\"" . Session::get("user") . "\" id=\"user\" />\r\n<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js\"></script>\r\n<script>window.jQuery || document.write('<script src=\"js/vendor/jquery-1.11.2.min.js\"><\\/script>')</script>\r\n<script src=\"js/vendor/bootstrap.min.js\"></script>\r\n<script src=\"js/main.js\"></script>\r\n<script src=\"js/buttons.js\"></script>\r\n<script src=\"js/io.js\"></script>";
if ($_GET["virt"] == "lxc") {
    echo "<script src=\"js/fw.lxc.js\"></script>";
} else {
    if ($_GET["virt"] == "kvm") {
        echo "<script src=\"js/fw.kvm.js\"></script>";
    }
}
echo "</body>\r\n</html>";

?>
