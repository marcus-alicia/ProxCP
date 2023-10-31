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
    $l_array = _obfuscated_0D24283F12023F041C383230393C32170E1D0837160111_($connection, 0);
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
$enable_whmcs = $db->get("vncp_settings", ["item", "=", "enable_whmcs"])->first()->value;
$whmcs_url = $db->get("vncp_settings", ["item", "=", "whmcs_url"])->first()->value;
$whmcs_id = $db->get("vncp_settings", ["item", "=", "whmcs_id"])->first()->value;
$whmcs_key = $db->get("vncp_settings", ["item", "=", "whmcs_key"])->first()->value;
if ($enable_whmcs == "true" && Input::exists() && Token::check(Input::get("token"))) {
    $validate = new Validate();
    $validation = $validate->check($_POST, ["ticketdept" => ["required" => true, "numonly" => true, "min-num" => 1], "ticketsubject" => ["required" => true, "min" => 3, "max" => 100], "ticketmsg" => ["required" => true, "min" => 2, "max" => 5000]]);
    if ($validation->passed()) {
        $openTicket = _obfuscated_0D0508392C01340D09123612315C372D183335235B1732_($whmcs_url, ["username" => $whmcs_id, "password" => $whmcs_key, "responsetype" => "json", "action" => "OpenTicket", "deptid" => Input::get("ticketdept"), "subject" => Input::get("ticketsubject"), "message" => Input::get("ticketmsg"), "clientid" => $user->data()->id, "priority" => "Medium", "admin" => false]);
        if ($openTicket["result"] != "success") {
            $errors = $openTicket["result"];
        }
    } else {
        $errors = "";
        foreach ($validation->errors() as $error) {
            $errors .= $error . "<br />";
        }
    }
}
$appname = $db->get("vncp_settings", ["item", "=", "app_name"])->first()->value;
$vmtable = ["lxc" => [], "kvm" => []];
$noLogin = false;
$results = $db->get("vncp_lxc_ct", ["user_id", "=", $user->data()->id]);
$data = $results->all();
if (0 < count($data)) {
    $firstNode = $data[0]->node;
    $node_results = $db->get("vncp_nodes", ["name", "=", $firstNode]);
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
}
for ($i = 0; $i < count($data); $i++) {
    if ($data[$i]->node != $firstNode) {
        $firstNode = $data[$i]->node;
        $node_results = $db->get("vncp_nodes", ["name", "=", $firstNode]);
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
    }
    if ($noLogin) {
        $log->log("Could not reach node " . $node_data->hostname, "error", 2, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
        $vmtable["lxc"][$i] = ["noLogin" => true];
    } else {
        if ($data[$i]->suspended == 0 && !$noLogin) {
            $vminfo = $pxAPI->get("/pools/" . $data[$i]->pool_id);
            $info = $pxAPI->get("/nodes/" . $data[$i]->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/status/current");
            $vmtable["lxc"][$i] = ["noLogin" => false, "suspended" => false, "status" => $info["status"], "name" => $info["name"], "ip" => _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->ip), "os" => _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->os), "maxmem" => _obfuscated_0D1E19192D05223B341C2E3609382F143730271D391232_($info["maxmem"], 0), "maxdisk" => _obfuscated_0D1E19192D05223B341C2E3609382F143730271D391232_($info["maxdisk"], 0), "cpus" => $info["cpus"], "hbid" => _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->hb_account_id)];
        } else {
            if ($data[$i]->suspended == 1) {
                $vminfo = $pxAPI->get("/pools/" . $data[$i]->pool_id);
                $info = $pxAPI->get("/nodes/" . $data[$i]->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/status/current");
                $vmtable["lxc"][$i] = ["noLogin" => false, "suspended" => true, "status" => $info["status"], "name" => $info["name"], "ip" => _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->ip), "os" => _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->os), "maxmem" => _obfuscated_0D1E19192D05223B341C2E3609382F143730271D391232_($info["maxmem"], 0), "maxdisk" => _obfuscated_0D1E19192D05223B341C2E3609382F143730271D391232_($info["maxdisk"], 0), "cpus" => $info["cpus"]];
            }
        }
    }
}
$results = $db->get("vncp_kvm_ct", ["user_id", "=", $user->data()->id]);
$data = $results->all();
if (0 < count($data)) {
    $firstNode = $data[0]->node;
    $node_results = $db->get("vncp_nodes", ["name", "=", $firstNode]);
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
}
for ($i = 0; $i < count($data); $i++) {
    if ($data[$i]->node != $firstNode) {
        $firstNode = $data[$i]->node;
        $node_results = $db->get("vncp_nodes", ["name", "=", $firstNode]);
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
    }
    if ($noLogin) {
        $log->log("Could not reach node " . $node_data->hostname, "error", 2, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
        $vmtable["kvm"][$i] = ["noLogin" => true];
    } else {
        if ($data[$i]->suspended == 0 && !$noLogin) {
            $vminfo = $pxAPI->get("/pools/" . $data[$i]->pool_id);
            if (count($vminfo["members"]) == 1) {
                $info = $pxAPI->get("/nodes/" . $data[$i]->node . "/qemu/" . $vminfo["members"][0]["vmid"] . "/status/current");
                $vmtable["kvm"][$i] = ["noLogin" => false, "suspended" => false, "status" => $info["status"], "name" => $info["name"], "ip" => _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->ip), "os" => _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->os), "maxmem" => _obfuscated_0D1E19192D05223B341C2E3609382F143730271D391232_($info["maxmem"], 0), "maxdisk" => _obfuscated_0D1E19192D05223B341C2E3609382F143730271D391232_($info["maxdisk"], 0), "cpus" => $info["cpus"], "hbid" => _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->hb_account_id), "from_template" => $data[$i]->from_template];
            } else {
                for ($j = 0; $j < count($vminfo["members"]); $j++) {
                    if ($vminfo["members"][$j]["name"] == $data[$i]->cloud_hostname) {
                        $info = $pxAPI->get("/nodes/" . $data[$i]->node . "/qemu/" . $vminfo["members"][$j]["vmid"] . "/status/current");
                        $vmtable["kvm"][$i] = ["noLogin" => false, "suspended" => false, "status" => $info["status"], "name" => $info["name"], "ip" => _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->ip), "os" => _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->os), "maxmem" => _obfuscated_0D1E19192D05223B341C2E3609382F143730271D391232_($info["maxmem"], 0), "maxdisk" => _obfuscated_0D1E19192D05223B341C2E3609382F143730271D391232_($info["maxdisk"], 0), "cpus" => $info["cpus"], "hbid" => _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->hb_account_id), "from_template" => $data[$i]->from_template];
                    }
                }
            }
        } else {
            if ($data[$i]->suspended == 1) {
                $vminfo = $pxAPI->get("/pools/" . $data[$i]->pool_id);
                $info = $pxAPI->get("/nodes/" . $data[$i]->node . "/qemu/" . $vminfo["members"][0]["vmid"] . "/status/current");
                $vmtable["kvm"][$i] = ["noLogin" => false, "suspended" => true, "status" => $info["status"], "name" => $info["name"], "ip" => _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->ip), "os" => _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->os), "maxmem" => _obfuscated_0D1E19192D05223B341C2E3609382F143730271D391232_($info["maxmem"], 0), "maxdisk" => _obfuscated_0D1E19192D05223B341C2E3609382F143730271D391232_($info["maxdisk"], 0), "cpus" => $info["cpus"]];
            }
        }
    }
}
$cloud_accounts = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($db->get("vncp_settings", ["item", "=", "cloud_accounts"])->first()->value);
$hasCloud = false;
$cl_data = [];
if ($cloud_accounts != "false") {
    $cl_result = $db->get("vncp_kvm_cloud", ["user_id", "=", $user->data()->id]);
    $cl_data = $cl_result->all();
    if (0 < count($cl_data)) {
        $hasCloud = true;
    }
}
$content = $db->get("vncp_kvm_isos", ["content", "=", "iso"]);
$contentr = $content->all();
$kvmisos_data = $db->get("vncp_kvm_isos_custom", ["user_id", "=", $user->data()->id])->all();
$kvmisos_custom = [];
$i == 0;
while ($i < count($kvmisos_data)) {
    if ($kvmisos_data[$i]->status == "active") {
        $kvmisos_custom[] = $kvmisos_data[$i];
    }
    $i++;
}
$kvmisos_custom_location = "local";
if (0 < count($contentr)) {
    list($kvmisos_custom_location) = explode(":", $contentr[0]->volid);
}
$getDepts = "";
$getInvoices = "";
$getTickets = "";
if ($enable_whmcs == "true") {
    $getDepts = _obfuscated_0D0508392C01340D09123612315C372D183335235B1732_($whmcs_url, ["username" => $whmcs_id, "password" => $whmcs_key, "responsetype" => "json", "action" => "GetSupportDepartments", "ignore_dept_assignments" => true]);
    $getInvoices = _obfuscated_0D0508392C01340D09123612315C372D183335235B1732_($whmcs_url, ["username" => $whmcs_id, "password" => $whmcs_key, "responsetype" => "json", "action" => "GetInvoices", "userid" => $user->data()->id, "status" => "Unpaid"]);
    $getTickets = _obfuscated_0D0508392C01340D09123612315C372D183335235B1732_($whmcs_url, ["username" => $whmcs_id, "password" => $whmcs_key, "responsetype" => "json", "action" => "GetTickets", "clientid" => $user->data()->id, "status" => "All Active Tickets", "ignore_dept_assignments" => true]);
}
$enable_firewall = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($db->get("vncp_settings", ["item", "=", "enable_firewall"])->first()->value);
$enable_forward_dns = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($db->get("vncp_settings", ["item", "=", "enable_forward_dns"])->first()->value);
$enable_reverse_dns = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($db->get("vncp_settings", ["item", "=", "enable_reverse_dns"])->first()->value);
$enable_notepad = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($db->get("vncp_settings", ["item", "=", "enable_notepad"])->first()->value);
$enable_status = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($db->get("vncp_settings", ["item", "=", "enable_status"])->first()->value);
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
echo $twig->render("home-left.tpl", ["appname" => $appname, "errors" => $errors, "vmtable" => $vmtable, "cloud_accounts" => $cloud_accounts, "hasCloud" => $hasCloud, "cl_data" => $cl_data, "kvmisos" => $contentr, "enable_whmcs" => $enable_whmcs, "getDepts" => $getDepts, "getInvoices" => $getInvoices, "token" => Token::generate(), "getTickets" => $getTickets, "adminBase" => Config::get("admin/base"), "enable_firewall" => $enable_firewall, "enable_forward_dns" => $enable_forward_dns, "enable_reverse_dns" => $enable_reverse_dns, "enable_notepad" => $enable_notepad, "enable_status" => $enable_status, "isAdmin" => $isAdmin, "constants" => $constants, "username" => $user->data()->username, "aclsetting" => $aclsetting, "pagename" => "Dashboard", "kvmisos_custom" => $kvmisos_custom, "kvmisos_custom_location" => $kvmisos_custom_location, "L" => $L]);
if (isset($GLOBALS["proxcp_branding"]) && !empty($GLOBALS["proxcp_branding"])) {
    echo $GLOBALS["proxcp_branding"];
}
$enable_panel_news = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($db->get("vncp_settings", ["item", "=", "enable_panel_news"])->first()->value);
$news = "";
if ($enable_panel_news == "true") {
    $news = _obfuscated_0D063F0D33221E0E273011111E2E3C373F1E140B402101_($db);
}
$result = $db->limit_get_desc("vncp_users_ip_log", ["client_id", "=", $user->data()->id], "1");
$data = $result->first();
$support_ticket_url = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($db->get("vncp_settings", ["item", "=", "support_ticket_url"])->first()->value);
$user_iso_upload = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($db->get("vncp_settings", ["item", "=", "user_iso_upload"])->first()->value);
$max_upload_size = ini_get("upload_max_filesize");
$hasKVM_ISO = false;
foreach ($vmtable["kvm"] as $kvm) {
    if (!$kvm["suspended"]) {
        $hasKVM_ISO = true;
        if ($hasCloud) {
            $hasKVM_ISO = true;
        }
        $user_isos = $db->get("vncp_kvm_isos_custom", ["user_id", "=", $user->data()->id])->all();
        echo $twig->render("home-right.tpl", ["enable_panel_news" => $enable_panel_news, "news" => $news, "appname" => $appname, "data" => $data, "enable_whmcs" => $enable_whmcs, "support_ticket_url" => $support_ticket_url, "user_iso_upload" => $user_iso_upload, "hasKVM_ISO" => $hasKVM_ISO, "max_upload_size" => $max_upload_size, "user_isos" => $user_isos, "L" => $L]);
        echo "</div>\r\n</div>\r\n</div>\r\n<input type=\"hidden\" value=\"" . Session::get("user") . "\" id=\"user\" />\r\n<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js\"></script>\r\n<script>window.jQuery || document.write('<script src=\"js/vendor/jquery-1.11.2.min.js\"><\\/script>')</script>\r\n<script src=\"js/vendor/bootstrap.min.js\"></script>\r\n<script src=\"js/vendor/bootstrap-slider.min.js\"></script>\r\n<script src=\"js/main.js\"></script>\r\n<script src=\"js/buttons.js\"></script>\r\n<script src=\"js/io.js\"></script>";
        if ($hasCloud) {
            echo "<script src=\"js/cloud.js\"></script><script src=\"js/slider.js\"></script>";
        }
        if ($user_iso_upload == "true" && $hasKVM_ISO) {
            echo "<script src=\"js/uploads.js\"></script>";
        }
        echo "</body>\r\n</html>";
    }
}

?>
