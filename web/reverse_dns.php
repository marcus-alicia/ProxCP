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
$log = new Logger();
$cpanel_host = $db->get("vncp_settings", ["item", "=", "whm_url"])->first()->value;
$cphtemp = explode(":", $cpanel_host);
if (count($cphtemp) == 2) {
    $cpanel_host = $cpanel_host . ":2087";
}
$cpanel = new Cpanel($cpanel_host, $db->get("vncp_settings", ["item", "=", "whm_username"])->first()->value, $db->get("vncp_settings", ["item", "=", "whm_api_token"])->first()->value);
if (Input::exists()) {
    if (strpos(Input::get("formid"), "v4radd") !== false) {
        $validate = new Validate();
        $validation = $validate->check($_POST, ["ipaddress" => ["required" => true, "ip" => true], "hostname" => ["required" => true, "max" => 250, "min" => 6]]);
        if ($validation->passed()) {
            $ovzcheck = $db->get("vncp_lxc_ct", ["ip", "=", Input::get("ipaddress")]);
            $ovzdata = $ovzcheck->first();
            $kvmcheck = $db->get("vncp_kvm_ct", ["ip", "=", Input::get("ipaddress")]);
            $kvmdata = $kvmcheck->first();
            $secondarycheck = $db->get("vncp_secondary_ips", ["address", "=", Input::get("ipaddress")]);
            $secondarydata = $secondarycheck->first();
            if ($ovzdata->user_id == $user->data()->id || $kvmdata->user_id == $user->data()->id || $secondarydata->user_id == $user->data()->id) {
                $ipaddress_octets = explode(".", Input::get("ipaddress"));
                $zone = $ipaddress_octets[2] . "." . $ipaddress_octets[1] . "." . $ipaddress_octets[0] . ".in-addr.arpa";
                $ptrdname = Input::get("hostname");
                if (preg_match("/[^a-z\\.\\-0-9]/i", $ptrdname)) {
                    $errors = "Invalid hostname / target.";
                } else {
                    $add = $cpanel->addzonerecord($zone, $ipaddress_octets[3], "PTR", $ptrdname);
                    if ($add) {
                        $insert = $db->insert("vncp_reverse_dns", ["client_id" => $user->data()->id, "type" => "PTR", "ipaddress" => Input::get("ipaddress"), "hostname" => $ptrdname]);
                        $log->log("Added PTR " . Input::get("ipaddress") . " -> " . $ptrdname . " record", "general", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                        $success = "Your reverse DNS record has been created!";
                    } else {
                        $errors = "Could not edit DNS zone.";
                        $log->log("Could not edit DNS zone " . $zone, "error", 1, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                    }
                }
            } else {
                $errors = "Invalid IP address.";
            }
        } else {
            $errors = "";
            foreach ($validation->errors() as $error) {
                $errors .= $error . "<br />";
            }
        }
    } else {
        if (strpos(Input::get("formid"), "v6radd") !== false) {
            $validate = new Validate();
            $validation = $validate->check($_POST, ["ipaddress" => ["required" => true, "ip6" => true], "hostname" => ["required" => true, "max" => 250, "min" => 6]]);
            if ($validation->passed()) {
                $v6check = $db->get("vncp_ipv6_assignment", ["address", "=", Input::get("ipaddress")]);
                $v6data = $v6check->first();
                if ($v6data->user_id == $user->data()->id) {
                    $ipaddress_octets = explode(":", Input::get("ipaddress"));
                    for ($i = 0; $i < count($ipaddress_octets); $i++) {
                        if (strlen($ipaddress_octets[$i]) < 4) {
                            strlen($ipaddress_octets[$i]);
                            switch (strlen($ipaddress_octets[$i])) {
                                case 1:
                                    $ipaddress_octets[$i] = "000" . $ipaddress_octets[$i];
                                    break;
                                case 2:
                                    $ipaddress_octets[$i] = "00" . $ipaddress_octets[$i];
                                    break;
                                case 3:
                                    $ipaddress_octets[$i] = "0" . $ipaddress_octets[$i];
                                    break;
                            }
                        }
                    }
                    for ($i = 0; $i < count($ipaddress_octets); $i++) {
                        $ipaddress_octets[$i] = str_split($ipaddress_octets[$i]);
                    }
                    $zoneprefix = "";
                    for ($i = 3; 0 <= $i; $i--) {
                        for ($k = count($ipaddress_octets[$i]) - 1; 0 <= $k; $k--) {
                            $zoneprefix = $zoneprefix . $ipaddress_octets[$i][$k] . ".";
                        }
                    }
                    $zone = $zoneprefix . "ip6.arpa";
                    $ptrdname = Input::get("hostname");
                    if (preg_match("/[^a-z\\.\\-0-9]/i", $ptrdname)) {
                        $errors = "Invalid hostname / target.";
                    } else {
                        $dnsname = "";
                        for ($i = 7; 4 <= $i; $i--) {
                            for ($k = count($ipaddress_octets[$i]) - 1; 0 <= $k; $k--) {
                                $dnsname = $dnsname . $ipaddress_octets[$i][$k] . ".";
                            }
                        }
                        $dnsname = rtrim($dnsname, ".");
                        $add = $cpanel->addzonerecord($zone, $dnsname, "PTR", $ptrdname);
                        if ($add) {
                            $insert = $db->insert("vncp_reverse_dns", ["client_id" => $user->data()->id, "type" => "PTR", "ipaddress" => Input::get("ipaddress"), "hostname" => $ptrdname]);
                            $log->log("Added PTR " . Input::get("ipaddress") . " -> " . $ptrdname . " record", "general", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                            $success = "Your reverse DNS record has been created!";
                        } else {
                            $errors = "Could not edit DNS zone (IPv6).";
                            $log->log("Could not edit DNS zone (IPv6) " . $zone, "error", 1, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                        }
                    }
                } else {
                    $errors = "Invalid IPv6 address.";
                }
            } else {
                $errors = "";
                foreach ($validation->errors() as $error) {
                    $errors .= $error . "<br />";
                }
            }
        } else {
            if (strpos(Input::get("formid"), "del_rdns") !== false) {
                if (strpos(Input::get("ipaddress"), ":") !== false) {
                    $v6check = $db->get("vncp_ipv6_assignment", ["address", "=", Input::get("ipaddress")]);
                    $v6data = $v6check->first();
                    if ($v6data->user_id == $user->data()->id) {
                        $ipaddress_octets = explode(":", Input::get("ipaddress"));
                        for ($i = 0; $i < count($ipaddress_octets); $i++) {
                            if (strlen($ipaddress_octets[$i]) < 4) {
                                strlen($ipaddress_octets[$i]);
                                switch (strlen($ipaddress_octets[$i])) {
                                    case 1:
                                        $ipaddress_octets[$i] = "000" . $ipaddress_octets[$i];
                                        break;
                                    case 2:
                                        $ipaddress_octets[$i] = "00" . $ipaddress_octets[$i];
                                        break;
                                    case 3:
                                        $ipaddress_octets[$i] = "0" . $ipaddress_octets[$i];
                                        break;
                                }
                            }
                        }
                        for ($i = 0; $i < count($ipaddress_octets); $i++) {
                            $ipaddress_octets[$i] = str_split($ipaddress_octets[$i]);
                        }
                        $zoneprefix = "";
                        for ($i = 3; 0 <= $i; $i--) {
                            for ($k = count($ipaddress_octets[$i]) - 1; 0 <= $k; $k--) {
                                $zoneprefix = $zoneprefix . $ipaddress_octets[$i][$k] . ".";
                            }
                        }
                        $zone = $zoneprefix . "ip6.arpa";
                        $dump = $cpanel->dumpzone($zone);
                        $dnsname = "";
                        for ($i = 7; 4 <= $i; $i--) {
                            for ($k = count($ipaddress_octets[$i]) - 1; 0 <= $k; $k--) {
                                $dnsname = $dnsname . $ipaddress_octets[$i][$k] . ".";
                            }
                        }
                        $dnsname = rtrim($dnsname, ".");
                        for ($i = 0; $i < count($dump->data->zone[0]->record); $i++) {
                            if ($dump->data->zone[0]->record[$i]->name == $dnsname . "." . $zone . ".") {
                                $line = $dump->data->zone[0]->record[$i]->Line;
                                $remove = $cpanel->removezonerecord($zone, (int) $line);
                                $dbremove = $db->delete("vncp_reverse_dns", ["ipaddress", "=", Input::get("ipaddress")]);
                                $log->log("Removed PTR " . Input::get("ipaddress") . " record", "general", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                            }
                        }
                    } else {
                        $errors = "Invalid IPv6 address.";
                    }
                } else {
                    $ovzcheck = $db->get("vncp_lxc_ct", ["ip", "=", Input::get("ipaddress")]);
                    $ovzdata = $ovzcheck->first();
                    $kvmcheck = $db->get("vncp_kvm_ct", ["ip", "=", Input::get("ipaddress")]);
                    $kvmdata = $kvmcheck->first();
                    if ($ovzdata->user_id == $user->data()->id || $kvmdata->user_id == $user->data()->id) {
                        $ipaddress_octets = explode(".", Input::get("ipaddress"));
                        $zone = $ipaddress_octets[2] . "." . $ipaddress_octets[1] . "." . $ipaddress_octets[0] . ".in-addr.arpa";
                        $dump = $cpanel->dumpzone($zone);
                        for ($i = 0; $i < count($dump->data->zone[0]->record); $i++) {
                            if ($dump->data->zone[0]->record[$i]->name == $ipaddress_octets[3] . "." . $zone . ".") {
                                $line = $dump->data->zone[0]->record[$i]->Line;
                                $remove = $cpanel->removezonerecord($zone, (int) $line);
                                $dbremove = $db->delete("vncp_reverse_dns", ["ipaddress", "=", Input::get("ipaddress")]);
                                $log->log("Removed PTR " . Input::get("ipaddress") . " record", "general", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                            }
                        }
                        $success = "Your reverse DNS record has been removed!";
                    } else {
                        $errors = "Invalid IP address.";
                    }
                }
            }
        }
    }
}
$appname = $db->get("vncp_settings", ["item", "=", "app_name"])->first()->value;
$rdnssetting = $db->get("vncp_settings", ["item", "=", "enable_reverse_dns"])->first()->value;
$result = $db->get("vncp_reverse_dns", ["client_id", "=", $user->data()->id]);
$existingdata = $result->all();
$result = $db->get("vncp_kvm_ct", ["user_id", "=", $user->data()->id]);
$data = $result->all();
$kvmips = [];
for ($i = 0; $i < count($data); $i++) {
    $rcheck = $db->get("vncp_reverse_dns", ["client_id", "=", $user->data()->id]);
    $rdata = $rcheck->all();
    $matches = 0;
    for ($j = 0; $j < count($rdata); $j++) {
        if ($rdata[$j]->ipaddress == $data[$i]->ip) {
            $matches++;
        }
    }
    $natcheck = $db->get("vncp_nat", ["node", "=", $data[$i]->node])->all();
    $isNAT = false;
    $j = 0;
    while ($j < count($natcheck)) {
        if (_obfuscated_0D1D160B191F262E1C10382E1A2808231A172828272201_($data[$i]->ip, $natcheck[$j]->natcidr)) {
            $isNAT = true;
        } else {
            $j++;
        }
    }
    if ($matches == 0 && !$isNAT) {
        $kvmips[] = $data[$i]->ip;
    }
}
$result = $db->get("vncp_lxc_ct", ["user_id", "=", $user->data()->id]);
$data = $result->all();
$lxcips = [];
for ($i = 0; $i < count($data); $i++) {
    $rcheck = $db->get("vncp_reverse_dns", ["client_id", "=", $user->data()->id]);
    $rdata = $rcheck->all();
    $matches = 0;
    for ($j = 0; $j < count($rdata); $j++) {
        if ($rdata[$j]->ipaddress == $data[$i]->ip) {
            $matches++;
        }
    }
    $natcheck = $db->get("vncp_nat", ["node", "=", $data[$i]->node])->all();
    $isNAT = false;
    $j = 0;
    while ($j < count($natcheck)) {
        if (_obfuscated_0D1D160B191F262E1C10382E1A2808231A172828272201_($data[$i]->ip, $natcheck[$j]->natcidr)) {
            $isNAT = true;
        } else {
            $j++;
        }
    }
    if ($matches == 0 && !$isNAT) {
        $lxcips[] = $data[$i]->ip;
    }
}
$result = $db->get("vncp_secondary_ips", ["user_id", "=", $user->data()->id]);
$data = $result->all();
$secondaryips = [];
for ($i = 0; $i < count($data); $i++) {
    $rcheck = $db->get("vncp_reverse_dns", ["client_id", "=", $user->data()->id]);
    $rdata = $rcheck->all();
    $matches = 0;
    for ($j = 0; $j < count($rdata); $j++) {
        if ($rdata[$j]->ipaddress == $data[$i]->address) {
            $matches++;
        }
    }
    if ($matches == 0) {
        $secondaryips[] = $data[$i]->address;
    }
}
$result = $db->get("vncp_ipv6_assignment", ["user_id", "=", $user->data()->id]);
$data = $result->all();
$v6ips = [];
for ($i = 0; $i < count($data); $i++) {
    $rcheck = $db->get("vncp_reverse_dns", ["client_id", "=", $user->data()->id]);
    $rdata = $rcheck->all();
    $matches = 0;
    for ($j = 0; $j < count($rdata); $j++) {
        if ($rdata[$j]->ipaddress == $data[$i]->address) {
            $matches++;
        }
    }
    if ($matches == 0) {
        $v6ips[] = $data[$i]->address;
    }
}
$enable_firewall = parse_input($db->get("vncp_settings", ["item", "=", "enable_firewall"])->first()->value);
$enable_forward_dns = parse_input($db->get("vncp_settings", ["item", "=", "enable_forward_dns"])->first()->value);
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
echo $twig->render("reverse_dns.tpl", ["appname" => $appname, "rdnssetting" => $rdnssetting, "errors" => $errors, "success" => $success, "existingdata" => $existingdata, "formID" => "del_rdns_" . _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(10), "kvmips" => $kvmips, "lxcips" => $lxcips, "secondaryips" => $secondaryips, "formID2" => "v4radd_" . _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(10), "formID3" => "v6radd_" . _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(10), "v6ips" => $v6ips, "adminBase" => Config::get("admin/base"), "enable_firewall" => $enable_firewall, "enable_forward_dns" => $enable_forward_dns, "enable_reverse_dns" => $rdnssetting, "enable_notepad" => $enable_notepad, "enable_status" => $enable_status, "isAdmin" => $isAdmin, "constants" => $constants, "username" => $user->data()->username, "aclsetting" => $aclsetting, "pagename" => "Manage Reverse DNS", "L" => $L]);
echo "<input type=\"hidden\" value=\"" . Session::get("user") . "\" id=\"user\" />\r\n<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js\"></script>\r\n<script>window.jQuery || document.write('<script src=\"js/vendor/jquery-1.11.2.min.js\"><\\/script>')</script>\r\n<script src=\"js/vendor/bootstrap.min.js\"></script>\r\n<script src=\"js/main.js\"></script>\r\n<script src=\"js/buttons.js\"></script>\r\n<script src=\"js/io.js\"></script>\r\n</body>\r\n</html>";

?>
