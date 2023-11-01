<?php

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
$appname = $db->get("vncp_settings", ["item", "=", "app_name"])->first()->value;
$statussetting = $db->get("vncp_settings", ["item", "=", "enable_status"])->first()->value;
$nodesdata = [];
$results = $db->get("vncp_nodes", ["id", "!=", 0]);
$data = $results->all();
$noLogin = false;
foreach ($data as $n) {
    $noLogin = false;
    try {
        $pxAPI = new PVE2_API($n->hostname, $n->username, $n->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($n->password));
    } catch (Exception $e) {
        echo "ProxCP Exception:<br><br>";
        echo $e->getMessage();
    }
    if (!$pxAPI->login()) {
        $noLogin = true;
    }
    if (!$noLogin) {
        $nodes = $pxAPI->get("/nodes");
        $nIndex = 0;
        $r = 0;
        while ($r < count($nodes)) {
            if ($nodes[$r]["node"] == $n->name) {
                $nIndex = $r;
            } else {
                $r++;
            }
        }
        $percent = round((double) $nodes[$nIndex]["cpu"] * 100) . "%";
        if ($nodes[0]["uptime"] != 0) {
            $nodesdata[] = ["noLogin" => false, "status" => "online", "name" => $nodes[$nIndex]["node"], "cpu" => $n->cpu, "percent" => $percent, "uptime" => _obfuscated_0D0B3F3E2508030C0A1301173E273C0229342A0E243301_($nodes[$nIndex]["uptime"])];
        } else {
            $nodesdata[] = ["noLogin" => false, "status" => "offline", "name" => $nodes[$nIndex]["node"], "cpu" => $n->cpu];
        }
    } else {
        $nodesdata[] = ["noLogin" => true, "name" => $n->name, "cpu" => $n->cpu];
    }
}
$enable_firewall = parse_input($db->get("vncp_settings", ["item", "=", "enable_firewall"])->first()->value);
$enable_forward_dns = parse_input($db->get("vncp_settings", ["item", "=", "enable_forward_dns"])->first()->value);
$enable_reverse_dns = parse_input($db->get("vncp_settings", ["item", "=", "enable_reverse_dns"])->first()->value);
$enable_notepad = parse_input($db->get("vncp_settings", ["item", "=", "enable_notepad"])->first()->value);
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
echo $twig->render("nodes.tpl", ["appname" => $appname, "statussetting" => $statussetting, "nodes" => $nodesdata, "adminBase" => Config::get("admin/base"), "enable_firewall" => $enable_firewall, "enable_forward_dns" => $enable_forward_dns, "enable_reverse_dns" => $enable_reverse_dns, "enable_notepad" => $enable_notepad, "enable_status" => $statussetting, "isAdmin" => $isAdmin, "constants" => $constants, "username" => $user->data()->username, "aclsetting" => $aclsetting, "pagename" => "Node Status", "L" => $L]);
echo "<input type=\"hidden\" value=\"" . Session::get("user") . "\" id=\"user\" />\r\n<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js\"></script>\r\n<script>window.jQuery || document.write('<script src=\"js/vendor/jquery-1.11.2.min.js\"><\\/script>')</script>\r\n<script src=\"js/vendor/bootstrap.min.js\"></script>\r\n<script src=\"//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js\"></script>\r\n<script src=\"js/main.js\"></script>\r\n<script src=\"js/buttons.js\"></script>\r\n<script src=\"js/io.js\"></script>\r\n<script type=\"text/javascript\">\$(\"#userstatustable\").DataTable();</script>\r\n</body>\r\n</html>";

?>
