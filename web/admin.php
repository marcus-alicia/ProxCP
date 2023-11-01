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
    if (!$user->hasPermission("admin")) {
        Redirect::to("index");
    }
}
$db = DB::getInstance();
$log = new Logger();
if (Input::exists() && Input::get("action") == "nodes") {
    if (Token::check(Input::get("token"))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, ["hostname" => ["required" => true, "max" => 50, "unique_hostname" => true], "username" => ["required" => true, "max" => 10], "password" => ["required" => true], "realm" => ["required" => true, "max" => 3], "port" => ["required" => true, "max-num" => 65535, "min-num" => 1, "numonly" => true], "name" => ["required" => true, "max" => 50, "unique_hostname" => true], "location" => ["required" => true, "max" => 50], "cpu" => ["required" => true, "max" => 50], "backup" => ["required" => true, "max" => 25]]);
        if ($validation->passed()) {
            $current_node_count = count($db->get("vncp_nodes", ["id", "!=", 0])->all());
            if ($current_node_count < $GLOBALS["node_limit"]) {
                $db->insert("vncp_nodes", ["hostname" => Input::get("hostname"), "username" => Input::get("username"), "password" => _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_(Input::get("password")), "realm" => Input::get("realm"), "port" => Input::get("port"), "name" => Input::get("name"), "location" => Input::get("location"), "asn" => "11111", "cpu" => Input::get("cpu"), "mailing_enabled" => 0, "backup_store" => Input::get("backup")]);
                $log->log("Added new node " . Input::get("hostname"), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
            } else {
                $errors = "Too many nodes. Upgrade your license for more.";
            }
        } else {
            $errors = "";
            foreach ($validation->errors() as $error) {
                $errors .= $error . "<br />";
            }
        }
    }
} else {
    if (Input::exists() && Input::get("action") == "edit_node") {
        if (Token::check(Input::get("token"))) {
            $validate = new Validate();
            $validation = $validate->check($_POST, ["username" => ["required" => true, "max" => 10], "realm" => ["required" => true, "max" => 3], "port" => ["required" => true, "max-num" => 65535, "min-num" => 1, "numonly" => true], "location" => ["required" => true, "max" => 50], "cpu" => ["required" => true, "max" => 50], "backup" => ["required" => true, "max" => 25], "nid" => ["numonly" => true, "min-num" => 1, "required" => true]]);
            if ($validation->passed()) {
                $pwfield = parse_input(Input::get("password"));
                if (!empty($pwfield) && isset($pwfield)) {
                    $db->update("vncp_nodes", parse_input(Input::get("nid")), ["username" => parse_input(Input::get("username")), "password" => _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_(parse_input(Input::get("password"))), "realm" => parse_input(Input::get("realm")), "port" => (int) parse_input(Input::get("port")), "location" => parse_input(Input::get("location")), "asn" => "11111", "cpu" => parse_input(Input::get("cpu")), "mailing_enabled" => 0, "backup_store" => parse_input(Input::get("backup"))]);
                } else {
                    $db->update("vncp_nodes", parse_input(Input::get("nid")), ["username" => parse_input(Input::get("username")), "realm" => parse_input(Input::get("realm")), "port" => (int) parse_input(Input::get("port")), "location" => parse_input(Input::get("location")), "asn" => "11111", "cpu" => parse_input(Input::get("cpu")), "mailing_enabled" => 0, "backup_store" => parse_input(Input::get("backup"))]);
                }
                $log->log("Edited node " . parse_input(Input::get("nid")), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                Redirect::to("admin?action=nodes");
            } else {
                $errors = "";
                foreach ($validation->errors() as $error) {
                    $errors .= $error . "<br />";
                }
            }
        }
    } else {
        if (Input::exists() && Input::get("action") == "users" && Input::get("form_name") == "new_user_form") {
            $validate = new Validate();
            $validation = $validate->check($_POST, ["email" => ["required" => true, "max" => 100, "unique" => true, "valemail" => true], "group" => ["numonly" => true, "min-num" => 1, "max-num" => 2]]);
            if ($validation->passed()) {
                $plaintext_user_password = _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(10);
                $user_salt = Hash::salt(32);
                $default_language = parse_input($db->get("vncp_settings", ["item", "=", "default_language"])->first()->value);
                $db->insert("vncp_users", ["email" => strtolower(Input::get("email")), "username" => strtolower(Input::get("email")), "password" => Hash::make($plaintext_user_password, $user_salt), "salt" => $user_salt, "tfa_enabled" => 0, "tfa_secret" => "", "group" => (int) Input::get("group"), "locked" => 0, "language" => $default_language]);
                $log->log("Added new user " . Input::get("email"), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                $userCreatedSuccess = true;
            } else {
                $errors = "";
                foreach ($validation->errors() as $error) {
                    $errors .= $error . "<br />";
                }
            }
        } else {
            if (Input::exists() && Input::get("action") == "users" && Input::get("form_name") == "change_username_form") {
                $validate = new Validate();
                $validation = $validate->check($_POST, ["which_user" => ["required" => true, "valemail" => true, "max" => 100, "min" => 4], "username" => ["required" => true, "valemail" => true, "max" => 100, "min" => 4, "unique" => true]]);
                if ($validation->passed()) {
                    $userExists = $db->get("vncp_users", ["username", "=", Input::get("which_user")]);
                    $userExists = $userExists->all();
                    if (count($userExists) == 1) {
                        $db->update("vncp_users", $userExists[0]->id, ["username" => Input::get("username"), "email" => Input::get("username")]);
                        $log->log("Changed user " . $userExists[0]->id . " username from " . Input::get("which_user") . " to " . Input::get("username"), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                    } else {
                        $errors = "Selected user is ambiguous.";
                    }
                } else {
                    $errors = "";
                    foreach ($validation->errors() as $error) {
                        $errors .= $error . "<br />";
                    }
                }
            } else {
                if (Input::exists() && Input::get("action") == "log") {
                    if (Token::check(Input::get("token"))) {
                        $validate = new Validate();
                        $validation = $validate->check($_POST, ["logtype" => ["required" => true, "max" => 7, "min" => 5], "purgedate" => ["required" => true, "min" => 8, "max" => 10]]);
                        if ($validation->passed() && Input::get("logtype") != "default") {
                            $log->purge(Input::get("logtype"), Input::get("purgedate"));
                            $log->log("Purged " . Input::get("logtype") . " log entries before " . Input::get("purgedate"), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                            $logPurgedSuccess = true;
                        } else {
                            $errors = "";
                            foreach ($validation->errors() as $error) {
                                $errors .= $error . "<br />";
                            }
                        }
                    }
                } else {
                    if (Input::exists() && Input::get("action") == "settings" && Input::get("whatform") == "general_settings") {
                        if (Token::check(Input::get("token"))) {
                            $validate = new Validate();
                            $validation = $validate->check($_POST, ["app_name" => ["required" => true, "min" => 1, "max" => 100], "enable_firewall" => ["required" => true, "strbool" => true], "enable_forward_dns" => ["required" => true, "strbool" => true], "enable_reverse_dns" => ["required" => true, "strbool" => true], "enable_notepad" => ["required" => true, "strbool" => true], "enable_status" => ["required" => true, "strbool" => true], "enable_panel_news" => ["required" => true, "strbool" => true], "support_ticket_url" => ["required" => true, "min" => 10, "max" => 100], "user_acl" => ["required" => true, "strbool" => true], "cloud_accounts" => ["required" => true, "strbool" => true], "vm_ipv6" => ["required" => true, "strbool" => true], "private_networking" => ["required" => true, "strbool" => true], "secondary_ips" => ["required" => true, "strbool" => true], "panel_news" => ["max" => 280], "whmurl" => ["max" => 100], "whmusername" => ["max" => 100], "whmapitoken" => ["max" => 100], "fdnslimit" => ["numonly" => true, "min-num" => 1], "fdnsblacklist" => ["max" => 280], "fdnsnameservers" => ["max" => 280], "ipv6lim" => ["numonly" => true, "min-num" => 1], "ipv6limsubnet" => ["numonly" => true, "min-num" => 1], "vmbackups" => ["required" => true, "strbool" => true], "backuplim" => ["numonly" => true, "min-num" => 1], "bw_auto_suspend" => ["required" => true, "strbool" => true], "enable_whmcs" => ["required" => true, "strbool " => true], "whmcs_url" => ["max" => 100], "whmcs_id" => ["max" => 100], "whmcs_key" => ["max" => 100], "ipv6_mode" => ["min" => 6, "max" => 6], "default_language" => ["required" => true, "min" => 7, "max" => 7], "user_iso_upload" => ["required" => true, "strbool" => true]]);
                            if ($validation->passed()) {
                                $db->update("vncp_settings", 1, ["value" => Input::get("app_name")]);
                                $db->update("vncp_settings", 2, ["value" => Input::get("enable_firewall")]);
                                $db->update("vncp_settings", 3, ["value" => Input::get("enable_forward_dns")]);
                                $db->update("vncp_settings", 4, ["value" => Input::get("enable_reverse_dns")]);
                                $db->update("vncp_settings", 5, ["value" => Input::get("enable_notepad")]);
                                $db->update("vncp_settings", 6, ["value" => Input::get("enable_status")]);
                                $db->update("vncp_settings", 7, ["value" => Input::get("enable_panel_news")]);
                                $db->update("vncp_settings", 8, ["value" => Input::get("support_ticket_url")]);
                                $db->update("vncp_settings", 9, ["value" => Input::get("user_acl")]);
                                $db->update("vncp_settings", 10, ["value" => Input::get("cloud_accounts")]);
                                $db->update("vncp_settings", 11, ["value" => Input::get("vm_ipv6")]);
                                $db->update("vncp_settings", 12, ["value" => Input::get("private_networking")]);
                                $db->update("vncp_settings", 13, ["value" => Input::get("secondary_ips")]);
                                $db->update("vncp_settings", 14, ["value" => strip_tags(Input::get("panel_news"), "<br><a><p><ul><ol><li><strong>")]);
                                $db->update("vncp_settings", 15, ["value" => Input::get("whmurl")]);
                                $db->update("vncp_settings", 16, ["value" => Input::get("whmusername")]);
                                $db->update("vncp_settings", 17, ["value" => Input::get("whmapitoken")]);
                                $db->update("vncp_settings", 20, ["value" => Input::get("vmbackups")]);
                                $db->update("vncp_settings", 25, ["value" => Input::get("bw_auto_suspend")]);
                                $db->update("vncp_settings", 26, ["value" => Input::get("enable_whmcs")]);
                                $db->update("vncp_settings", 27, ["value" => Input::get("whmcs_url")]);
                                $db->update("vncp_settings", 28, ["value" => Input::get("whmcs_id")]);
                                $db->update("vncp_settings", 29, ["value" => Input::get("whmcs_key")]);
                                $db->update("vncp_settings", 30, ["value" => Input::get("ipv6mode")]);
                                $db->update("vncp_settings", 32, ["value" => explode(".", Input::get("default_language"))[0]]);
                                $db->update("vncp_settings", 33, ["value" => Input::get("user_iso_upload")]);
                                if (Input::get("fdnslimit") != "") {
                                    $db->update("vncp_settings", 18, ["value" => Input::get("fdnslimit")]);
                                }
                                if (Input::get("ipv6lim") != "") {
                                    $db->update("vncp_settings", 19, ["value" => Input::get("ipv6lim")]);
                                }
                                if (Input::get("ipv6limsubnet") != "") {
                                    $db->update("vncp_settings", 31, ["value" => Input::get("ipv6limsubnet")]);
                                }
                                if (Input::get("backuplim") != "") {
                                    $db->update("vncp_settings", 21, ["value" => Input::get("backuplim")]);
                                }
                                if (Input::get("fdnsblacklist") != "") {
                                    $db->update("vncp_settings", 23, ["value" => Input::get("fdnsblacklist")]);
                                }
                                if (Input::get("fdnsnameservers") != "") {
                                    $db->update("vncp_settings", 24, ["value" => Input::get("fdnsnameservers")]);
                                }
                                $log->log("Updated control panel general settings.", "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                $adminSettingsUpdated = true;
                            } else {
                                $errors = "";
                                foreach ($validation->errors() as $error) {
                                    $errors .= $error . "<br />";
                                }
                            }
                        }
                    } else {
                        if (Input::exists() && Input::get("action") == "settings" && Input::get("whatform") == "mail_settings") {
                            if (Token::check(Input::get("token"))) {
                                $validate = new Validate();
                                $validation = $validate->check($_POST, ["from_email_addr" => ["required" => true, "valemail" => true, "max" => 100, "min" => 5], "mail_type" => ["required" => true, "min" => 4, "max" => 7], "from_email_addr_name" => ["required" => true, "max" => 100, "min" => 5], "smtp_host" => ["max" => 100], "smtp_port" => ["numonly" => true, "min-num" => 1, "max-num" => 65535, "required" => true], "smtp_username" => ["max" => 100], "smtp_password" => ["max" => 100], "smtp_type" => ["required" => true, "min" => 4, "max" => 8]]);
                                if ($validation->passed()) {
                                    $db->update("vncp_settings", 22, ["value" => parse_input(Input::get("from_email_addr"))]);
                                    $db->update("vncp_settings", 40, ["value" => parse_input(Input::get("mail_type"))]);
                                    $db->update("vncp_settings", 34, ["value" => parse_input(Input::get("from_email_addr_name"))]);
                                    $db->update("vncp_settings", 35, ["value" => parse_input(Input::get("smtp_host"))]);
                                    $db->update("vncp_settings", 36, ["value" => parse_input(Input::get("smtp_port"))]);
                                    $db->update("vncp_settings", 37, ["value" => parse_input(Input::get("smtp_username"))]);
                                    if (Input::get("smtp_password")) {
                                        $db->update("vncp_settings", 38, ["value" => _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_(Input::get("smtp_password"))]);
                                    } else {
                                        $db->update("vncp_settings", 38, ["value" => ""]);
                                    }
                                    $db->update("vncp_settings", 39, ["value" => parse_input(Input::get("smtp_type"))]);
                                    $log->log("Updated control panel mail settings.", "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                    $adminSettingsUpdated = true;
                                } else {
                                    $errors = "";
                                    foreach ($validation->errors() as $error) {
                                        $errors .= $error . "<br />";
                                    }
                                }
                            }
                        } else {
                            if (Input::exists() && Input::get("action") == "natnodes") {
                                if (Token::check(Input::get("token"))) {
                                    $validate = new Validate();
                                    $validation = $validate->check($_POST, ["natnode" => ["required" => true, "max" => 50, "unique_nat" => true], "natnodeip" => ["required" => true, "max" => 15, "ip" => true], "natiprange" => ["required" => true, "max" => 18, "min" => 9]]);
                                    if ($validation->passed()) {
                                        if (isset($_POST["natunderstand"])) {
                                            $check1 = $db->get("vncp_nodes", ["name", "=", parse_input(Input::get("natnode"))])->all();
                                            $check2 = $db->get("vncp_tuntap", ["node", "=", parse_input(Input::get("natnode"))])->all();
                                            if (count($check1) == 1 && count($check2) == 1) {
                                                $checkResolve = gethostbyname($check1[0]->hostname);
                                                if ($checkResolve == parse_input(Input::get("natnodeip"))) {
                                                    $cidr = explode("/", parse_input(Input::get("natiprange")));
                                                    if (count($cidr) == 2) {
                                                        $cidr = (int) $cidr[1];
                                                        $lastoctet = (int) substr(explode("/", parse_input(Input::get("natiprange")))[0], -1);
                                                        if (24 < $cidr || $cidr < 16 || $lastoctet != 0) {
                                                            $errors = "NAT IP Range must be between a /24 and a /16. The last octet must also be 0.";
                                                        } else {
                                                            $natrange = explode(".", explode("/", parse_input(Input::get("natiprange")))[0]);
                                                            if (count($natrange) == 4) {
                                                                $firstoctet = (int) $natrange[0];
                                                                $firstgood = false;
                                                                $secondoctet = (int) $natrange[1];
                                                                $secondgood = false;
                                                                $thirdoctet = (int) $natrange[2];
                                                                $thirdgood = false;
                                                                switch ($firstoctet) {
                                                                    case 10:
                                                                        $firstgood = true;
                                                                        if ($secondoctet <= 255 && 0 <= $secondoctet) {
                                                                            $secondgood = true;
                                                                        }
                                                                        if ($thirdoctet <= 255 && 0 <= $thirdoctet) {
                                                                            $thirdgood = true;
                                                                        }
                                                                        break;
                                                                    case 172:
                                                                        $firstgood = true;
                                                                        if ($secondoctet <= 31 && 16 <= $secondoctet) {
                                                                            $secondgood = true;
                                                                        }
                                                                        if ($thirdoctet <= 255 && 0 <= $thirdoctet) {
                                                                            $thirdgood = true;
                                                                        }
                                                                        break;
                                                                    case 192:
                                                                        $firstgood = true;
                                                                        if ($secondoctet == 168) {
                                                                            $secondgood = true;
                                                                        }
                                                                        if ($thirdoctet <= 255 && 0 <= $thirdoctet) {
                                                                            $thirdgood = true;
                                                                        }
                                                                        break;
                                                                    default:
                                                                        $firstgood = false;
                                                                        $secondgood = false;
                                                                        $thirdgood = false;
                                                                        if ($firstgood && $secondgood && $thirdgood) {
                                                                            $natnetmask = "255.255.255.0";
                                                                            $limit = 0;
                                                                            switch ($cidr) {
                                                                                case 16:
                                                                                    $natnetmask = "255.255.0.0";
                                                                                    $limit = 65532;
                                                                                    break;
                                                                                case 17:
                                                                                    $natnetmask = "255.255.128.0";
                                                                                    $limit = 32764;
                                                                                    break;
                                                                                case 18:
                                                                                    $natnetmask = "255.255.192.0";
                                                                                    $limit = 16380;
                                                                                    break;
                                                                                case 19:
                                                                                    $natnetmask = "255.255.224.0";
                                                                                    $limit = 8188;
                                                                                    break;
                                                                                case 20:
                                                                                    $natnetmask = "255.255.240.0";
                                                                                    $limit = 4092;
                                                                                    break;
                                                                                case 21:
                                                                                    $natnetmask = "255.255.248.0";
                                                                                    $limit = 2044;
                                                                                    break;
                                                                                case 22:
                                                                                    $natnetmask = "255.255.252.0";
                                                                                    $limit = 1020;
                                                                                    break;
                                                                                case 23:
                                                                                    $natnetmask = "255.255.254.0";
                                                                                    $limit = 508;
                                                                                    break;
                                                                                case 24:
                                                                                    $natnetmask = "255.255.255.0";
                                                                                    $limit = 252;
                                                                                    break;
                                                                                default:
                                                                                    $natnetmask = "255.255.255.0";
                                                                                    $limit = 0;
                                                                                    $interfacesTemplate = "\r\nauto vmbr10\r\niface vmbr10 inet static\r\n        address " . (string) $firstoctet . "." . (string) $secondoctet . "." . (string) $thirdoctet . ".1\r\n        netmask " . $natnetmask . "\r\n        bridge_ports none\r\n        bridge_stp off\r\n        bridge_fd 0\r\n        post-up echo 1 > /proc/sys/net/ipv4/ip_forward";
                                                                                    $indexTemplate = "\r\n<!DOCTYPE html>\r\n<html>\r\n<head>\r\n<title>Default Web Page</title>\r\n<style>\r\n    body {\r\n        width: 35em;\r\n        margin: 0 auto;\r\n        font-family: Tahoma, Verdana, Arial, sans-serif;\r\n    }\r\n</style>\r\n</head>\r\n<body>\r\n<h1>ProxCP default web page</h1>\r\n<p>This is the default web page for <a href=\"https://proxcp.com\" target=\"_blank\">ProxCP</a>. If you see this page, the NAT domain proxy is successfully installed and\r\nworking.</p>\r\n</body>\r\n</html>";
                                                                                    $ssh = new phpseclib\Net\SSH2($check1[0]->hostname, (int) $check2[0]->port);
                                                                                    if (!$ssh->login("root", _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($check2[0]->password))) {
                                                                                        $log->log("Could not SSH to NAT node " . parse_input(Input::get("natnode")), "error", 1, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                                        $errors = "Could not SSH to NAT node.";
                                                                                    } else {
                                                                                        $ssh->exec("echo \"" . $interfacesTemplate . "\" >> /etc/network/interfaces");
                                                                                        $ssh->exec("iptables -t nat -A POSTROUTING -s '" . parse_input(Input::get("natiprange")) . "' -o vmbr0 -j MASQUERADE");
                                                                                        $ssh->exec("iptables -t raw -I PREROUTING -i fwbr+ -j CT --zone 1");
                                                                                        $ssh->exec("iptables-save > /root/proxcp-iptables.rules");
                                                                                        $ssh->exec("printf '[Service]\nType=oneshot\nRemainAfterExit=yes\nExecStart=/root/proxcp-iptables.sh\n\n[Install]\nWantedBy=multi-user.target\n\n[Unit]\nWants=network-online.target\nAfter=network-online.target\nWants=pvestatd.service\nWants=pveproxy.service\nWants=spiceproxy.service\nWants=pve-firewall.service\nWants=lxc.service\nAfter=pveproxy.service\nAfter=pvestatd.service\nAfter=spiceproxy.service\nAfter=pve-firewall.service\nAfter=lxc.service' > /etc/systemd/system/proxcp-iptables.service");
                                                                                        $ssh->exec("printf '#!/bin/bash\niptables-restore < /root/proxcp-iptables.rules' > /root/proxcp-iptables.sh");
                                                                                        $ssh->exec("chmod +x /root/proxcp-iptables.sh");
                                                                                        $ssh->exec("systemctl enable proxcp-iptables.service");
                                                                                        $ssh->exec("apt update --allow-unauthenticated --allow-insecure-repositories && apt -y install nginx");
                                                                                        $ssh->exec("service nginx restart");
                                                                                        $ssh->exec("printf -- '" . $indexTemplate . "' > /var/www/html/index.nginx-debian.html && mv /var/www/html/index.nginx-debian.html /var/www/html/index.html");
                                                                                        $ssh->exec("mkdir -p /etc/nginx/proxcp-nat-ssl");
                                                                                        $ssh->exec("service networking restart");
                                                                                        $ssh->disconnect();
                                                                                        $db->insert("vncp_nat", ["node" => parse_input(Input::get("natnode")), "publicip" => parse_input(Input::get("natnodeip")), "natcidr" => parse_input(Input::get("natiprange")), "natnetmask" => $natnetmask, "vmlimit" => $limit]);
                                                                                        $log->log("Enabled NAT on node " . parse_input(Input::get("natnode")), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                                        $natCreatedSuccess = true;
                                                                                    }
                                                                            }
                                                                        } else {
                                                                            $errors = "NAT IP Range is not in RFC private ranges.";
                                                                        }
                                                                }
                                                            } else {
                                                                $errors = "NAT IP Range is not in CIDR format.";
                                                            }
                                                        }
                                                    } else {
                                                        $errors = "NAT IP Range is not in CIDR format.";
                                                    }
                                                } else {
                                                    $errors = "Node Public IP does not match the node's hostname A record.";
                                                }
                                            } else {
                                                $errors = "Node does not exist.";
                                            }
                                        } else {
                                            $errors = "Box must be checked to proceed.";
                                        }
                                    } else {
                                        $errors = "";
                                        foreach ($validation->errors() as $error) {
                                            $errors .= $error . "<br />";
                                        }
                                    }
                                }
                            } else {
                                if (Input::exists() && Input::get("action") == "lxc") {
                                    if (Token::check(Input::get("token"))) {
                                        $validate = new Validate();
                                        $validation = $validate->check($_POST, ["userid" => ["required" => true, "numonly" => true, "min-num" => 1], "node" => ["required" => true, "max" => 50], "osfriendly" => ["required" => true, "max" => 200], "ostype" => ["required" => true, "max" => 9], "hb_account_id" => ["required" => true, "numonly" => true, "min-num" => 1, "unique_hbid" => true], "poolid" => ["required" => true, "max" => 100, "unique_poolid" => true], "ipv4" => ["required" => true, "max" => 18, "cidrformat" => true], "ipv4gw" => ["required" => true, "ip" => true, "max" => 15], "ipv4_netmask" => ["required" => true, "ip" => true, "max" => 15], "hostname" => ["required" => true, "max" => 100], "storage_location" => ["required" => true, "max" => 100], "storage_size" => ["required" => true, "numonly" => true, "min-num" => 1], "cpucores" => ["required" => true, "numonly" => true, "min-num" => 1], "ram" => ["required" => true, "numonly" => true, "min-num" => 32], "bandwidth_limit" => ["required" => true, "numonly" => true, "min-num" => 50, "max-num" => 102400], "portspeed" => ["required" => true, "numonly" => true, "min-num" => 0, "max-num" => 10000], "setmacaddress" => ["max" => 17, "macaddr" => true], "lxcisnat" => ["required" => true, "strbool" => true], "setvlantag" => ["required" => true, "numonly" => true, "min-num" => 0, "max-num" => 4094]]);
                                        if ($validation->passed()) {
                                            if (Input::get("node") != "default" && Input::get("osfriendly") != "default" && Input::get("storage_location") != "default" && Input::get("ostype") != "default" && Input::get("userid") != "default" && Input::get("lxcisnat") != "default") {
                                                $users_results = $db->get("vncp_users", ["id", "=", parse_input(Input::get("userid"))]);
                                                $users_results = $users_results->all();
                                                if (count($users_results) == 1) {
                                                    $lxcisnat = parse_input(Input::get("lxcisnat"));
                                                    $natpublicports = parse_input(Input::get("natpublicports"));
                                                    $natdomainproxy = parse_input(Input::get("natdomainproxy"));
                                                    if ($lxcisnat == "true") {
                                                        if (isset($natpublicports) && !empty($natpublicports) && 1 <= (int) $natpublicports && (int) $natpublicports <= 30) {
                                                            if (isset($natdomainproxy) && 0 <= (int) $natdomainproxy && (int) $natdomainproxy <= 15) {
                                                                $getNATCIDR = $db->get("vncp_nat", ["node", "=", parse_input(Input::get("node"))])->all();
                                                                if (count($getNATCIDR) == 1) {
                                                                    $NATCIDR = $getNATCIDR[0]->natcidr;
                                                                    if (_obfuscated_0D1D160B191F262E1C10382E1A2808231A172828272201_(explode("/", parse_input(Input::get("ipv4")))[0], $NATCIDR)) {
                                                                        $node_results = $db->get("vncp_nodes", ["name", "=", parse_input(Input::get("node"))]);
                                                                        $node_data = $node_results->first();
                                                                        $pxAPI = new PVE2_API($node_data->hostname, $node_data->username, $node_data->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($node_data->password));
                                                                        $noLogin = false;
                                                                        if (!$pxAPI->login()) {
                                                                            $noLogin = true;
                                                                        }
                                                                        if (!$noLogin) {
                                                                            $plaintext_password = _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(12);
                                                                            $createpool = $pxAPI->post("/pools", ["poolid" => Input::get("poolid")]);
                                                                            sleep(1);
                                                                            $createuser = $pxAPI->post("/access/users", ["userid" => Input::get("poolid") . "@pve", "password" => $plaintext_password]);
                                                                            sleep(1);
                                                                            $setpoolperms = $pxAPI->put("/access/acl", ["path" => "/pool/" . Input::get("poolid"), "users" => Input::get("poolid") . "@pve", "roles" => "PVEVMUser"]);
                                                                            sleep(1);
                                                                            $allVMIDs = [];
                                                                            $getallKVM = $pxAPI->get("/nodes/" . Input::get("node") . "/qemu");
                                                                            for ($i = 0; $i < count($getallKVM); $i++) {
                                                                                $allVMIDs[] = (int) $getallKVM[$i]["vmid"];
                                                                            }
                                                                            $getallLXC = $pxAPI->get("/nodes/" . Input::get("node") . "/lxc");
                                                                            for ($i = 0; $i < count($getallLXC); $i++) {
                                                                                $allVMIDs[] = (int) $getallLXC[$i]["vmid"];
                                                                            }
                                                                            $getvmid = array_keys($allVMIDs, max($allVMIDs));
                                                                            $getvmid = (int) $allVMIDs[$getvmid[0]] + 1;
                                                                            if ((int) $getvmid < 100) {
                                                                                $getvmid = 100;
                                                                            }
                                                                            sleep(1);
                                                                            if (Input::get("setmacaddress")) {
                                                                                $saved_macaddr = strtoupper(parse_input(Input::get("setmacaddress")));
                                                                            } else {
                                                                                $saved_macaddr = MacAddress::generateMacAddress();
                                                                            }
                                                                            $newlxc = ["ostemplate" => parse_input(Input::get("osfriendly")), "vmid" => (int) $getvmid, "cmode" => "tty", "cores" => (int) parse_input(Input::get("cpucores")), "cpulimit" => 0, "cpuunits" => 1024, "description" => explode("/", parse_input(Input::get("ipv4")))[0], "hostname" => parse_input(Input::get("hostname")), "memory" => (int) parse_input(Input::get("ram")), "onboot" => 0, "ostype" => parse_input(Input::get("ostype")), "password" => $plaintext_password, "pool" => parse_input(Input::get("poolid")), "protection" => 0, "rootfs" => "" . parse_input(Input::get("storage_location")) . ":" . parse_input(Input::get("storage_size")), "storage" => parse_input(Input::get("storage_location")), "swap" => 512, "tty" => 2, "unprivileged" => 1];
                                                                            if (!Input::get("portspeed") || (int) parse_input(Input::get("portspeed")) <= 0) {
                                                                                $newlxc["net0"] = "bridge=vmbr10,hwaddr=" . $saved_macaddr . ",ip=" . Input::get("ipv4") . ",gw=" . Input::get("ipv4gw") . ",ip6=auto,name=eth0,type=veth";
                                                                            } else {
                                                                                $newlxc["net0"] = "bridge=vmbr10,hwaddr=" . $saved_macaddr . ",ip=" . Input::get("ipv4") . ",gw=" . Input::get("ipv4gw") . ",ip6=auto,name=eth0,type=veth,rate=" . (string) parse_input(Input::get("portspeed"));
                                                                            }
                                                                            if (0 < (int) Input::get("setvlantag")) {
                                                                                $newlxc["net0"] = $newlxc["net0"] . ",tag=" . (string) Input::get("setvlantag");
                                                                            }
                                                                            $createlxc = $pxAPI->post("/nodes/" . Input::get("node") . "/lxc", $newlxc);
                                                                            if (!$createlxc) {
                                                                                $log->log("Could not create LXC. Proxmox API returned error.", "error", 2, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                                $errors = "Could not create LXC. Proxmox API returned error.";
                                                                            } else {
                                                                                $allow_backups = parse_input($db->get("vncp_settings", ["item", "=", "enable_backups"])->first()->value);
                                                                                $abvalue = -1;
                                                                                if ($allow_backups == "true") {
                                                                                    $abvalue = 1;
                                                                                } else {
                                                                                    $abvalue = 0;
                                                                                }
                                                                                $db->insert("vncp_lxc_ct", ["user_id" => parse_input(Input::get("userid")), "node" => parse_input(Input::get("node")), "os" => parse_input(Input::get("ostype")), "hb_account_id" => parse_input(Input::get("hb_account_id")), "pool_id" => parse_input(Input::get("poolid")), "pool_password" => _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_($plaintext_password), "ip" => explode("/", Input::get("ipv4"))[0], "suspended" => 0, "allow_backups" => $abvalue, "fw_enabled_net0" => 0, "fw_enabled_net1" => 0, "has_net1" => 0, "tuntap" => 0, "onboot" => 0, "quotas" => 0]);
                                                                                $db->insert("vncp_ct_backups", ["userid" => parse_input(Input::get("userid")), "hb_account_id" => parse_input(Input::get("hb_account_id")), "backuplimit" => -1]);
                                                                                $today = new DateTime();
                                                                                $today->add(new DateInterval("P30D"));
                                                                                $reset_date = $today->format("Y-m-d 00:00:00");
                                                                                $db->insert("vncp_bandwidth_monitor", ["node" => parse_input(Input::get("node")), "pool_id" => parse_input(Input::get("poolid")), "hb_account_id" => parse_input(Input::get("hb_account_id")), "ct_type" => "lxc", "current" => 0, "max" => (int) parse_input(Input::get("bandwidth_limit")) * 1073741824, "reset_date" => $reset_date, "suspended" => 0]);
                                                                                $saved_network = explode(".", Input::get("ipv4gw"));
                                                                                list($dhcpip) = explode("/", parse_input(Input::get("ipv4")));
                                                                                $saved_network = explode(".", parse_input(Input::get("ipv4gw")));
                                                                                $db->insert("vncp_dhcp", ["mac_address" => $saved_macaddr, "ip" => $dhcpip, "gateway" => parse_input(Input::get("ipv4gw")), "netmask" => parse_input(Input::get("ipv4_netmask")), "network" => $saved_network[0] . "." . $saved_network[1] . "." . $saved_network[2] . "." . (string) ((int) $saved_network[3] - 1), "type" => 0]);
                                                                                $db->insert("vncp_natforwarding", ["user_id" => parse_input(Input::get("userid")), "node" => parse_input(Input::get("node")), "hb_account_id" => parse_input(Input::get("hb_account_id")), "avail_ports" => (int) $natpublicports, "ports" => "", "avail_domains" => (int) $natdomainproxy, "domains" => ""]);
                                                                                $log->log("Created new NAT LXC " . $getvmid . " on node " . parse_input(Input::get("node")), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                                $lxcCreatedSuccess = true;
                                                                            }
                                                                        } else {
                                                                            $errors = "Could not login to Proxmox node.";
                                                                        }
                                                                    } else {
                                                                        $errors = "IPv4 is not in selected node's NAT range.";
                                                                    }
                                                                } else {
                                                                    $errors = "Selected node is not NAT-enabled.";
                                                                }
                                                            } else {
                                                                $errors = "NAT Domain Forwarding cannot be empty and must be between 0 - 15.";
                                                            }
                                                        } else {
                                                            $errors = "NAT Public Ports cannot be empty and must be between 1 - 30.";
                                                        }
                                                    } else {
                                                        $node_results = $db->get("vncp_nodes", ["name", "=", parse_input(Input::get("node"))]);
                                                        $node_data = $node_results->first();
                                                        $pxAPI = new PVE2_API($node_data->hostname, $node_data->username, $node_data->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($node_data->password));
                                                        $noLogin = false;
                                                        if (!$pxAPI->login()) {
                                                            $noLogin = true;
                                                        }
                                                        if (!$noLogin) {
                                                            $plaintext_password = _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(12);
                                                            $createpool = $pxAPI->post("/pools", ["poolid" => Input::get("poolid")]);
                                                            sleep(1);
                                                            $createuser = $pxAPI->post("/access/users", ["userid" => Input::get("poolid") . "@pve", "password" => $plaintext_password]);
                                                            sleep(1);
                                                            $setpoolperms = $pxAPI->put("/access/acl", ["path" => "/pool/" . Input::get("poolid"), "users" => Input::get("poolid") . "@pve", "roles" => "PVEVMUser"]);
                                                            sleep(1);
                                                            $allVMIDs = [];
                                                            $getallKVM = $pxAPI->get("/nodes/" . Input::get("node") . "/qemu");
                                                            for ($i = 0; $i < count($getallKVM); $i++) {
                                                                $allVMIDs[] = (int) $getallKVM[$i]["vmid"];
                                                            }
                                                            $getallLXC = $pxAPI->get("/nodes/" . Input::get("node") . "/lxc");
                                                            for ($i = 0; $i < count($getallLXC); $i++) {
                                                                $allVMIDs[] = (int) $getallLXC[$i]["vmid"];
                                                            }
                                                            $getvmid = array_keys($allVMIDs, max($allVMIDs));
                                                            $getvmid = (int) $allVMIDs[$getvmid[0]] + 1;
                                                            if ((int) $getvmid < 100) {
                                                                $getvmid = 100;
                                                            }
                                                            sleep(1);
                                                            if (Input::get("setmacaddress")) {
                                                                $saved_macaddr = strtoupper(parse_input(Input::get("setmacaddress")));
                                                            } else {
                                                                $saved_macaddr = MacAddress::generateMacAddress();
                                                            }
                                                            $newlxc = ["ostemplate" => parse_input(Input::get("osfriendly")), "vmid" => (int) $getvmid, "cmode" => "tty", "cores" => (int) parse_input(Input::get("cpucores")), "cpulimit" => 0, "cpuunits" => 1024, "description" => explode("/", parse_input(Input::get("ipv4")))[0], "hostname" => parse_input(Input::get("hostname")), "memory" => (int) parse_input(Input::get("ram")), "onboot" => 0, "ostype" => parse_input(Input::get("ostype")), "password" => $plaintext_password, "pool" => parse_input(Input::get("poolid")), "protection" => 0, "rootfs" => "" . parse_input(Input::get("storage_location")) . ":" . parse_input(Input::get("storage_size")), "storage" => parse_input(Input::get("storage_location")), "swap" => 512, "tty" => 2, "unprivileged" => 1];
                                                            if (!Input::get("portspeed") || (int) parse_input(Input::get("portspeed")) <= 0) {
                                                                $newlxc["net0"] = "bridge=vmbr0,hwaddr=" . $saved_macaddr . ",ip=" . Input::get("ipv4") . ",gw=" . Input::get("ipv4gw") . ",ip6=auto,name=eth0,type=veth";
                                                            } else {
                                                                $newlxc["net0"] = "bridge=vmbr0,hwaddr=" . $saved_macaddr . ",ip=" . Input::get("ipv4") . ",gw=" . Input::get("ipv4gw") . ",ip6=auto,name=eth0,type=veth,rate=" . (string) parse_input(Input::get("portspeed"));
                                                            }
                                                            if (0 < (int) Input::get("setvlantag")) {
                                                                $newlxc["net0"] = $newlxc["net0"] . ",tag=" . (string) Input::get("setvlantag");
                                                            }
                                                            $createlxc = $pxAPI->post("/nodes/" . Input::get("node") . "/lxc", $newlxc);
                                                            if (!$createlxc) {
                                                                $log->log("Could not create LXC. Proxmox API returned error.", "error", 2, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                $errors = "Could not create LXC. Proxmox API returned error.";
                                                            } else {
                                                                $allow_backups = parse_input($db->get("vncp_settings", ["item", "=", "enable_backups"])->first()->value);
                                                                $abvalue = -1;
                                                                if ($allow_backups == "true") {
                                                                    $abvalue = 1;
                                                                } else {
                                                                    $abvalue = 0;
                                                                }
                                                                $db->insert("vncp_lxc_ct", ["user_id" => parse_input(Input::get("userid")), "node" => parse_input(Input::get("node")), "os" => parse_input(Input::get("ostype")), "hb_account_id" => parse_input(Input::get("hb_account_id")), "pool_id" => parse_input(Input::get("poolid")), "pool_password" => _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_($plaintext_password), "ip" => explode("/", parse_input(Input::get("ipv4")))[0], "suspended" => 0, "allow_backups" => $abvalue, "fw_enabled_net0" => 0, "fw_enabled_net1" => 0, "has_net1" => 0, "tuntap" => 0, "onboot" => 0, "quotas" => 0]);
                                                                $db->insert("vncp_ct_backups", ["userid" => parse_input(Input::get("userid")), "hb_account_id" => parse_input(Input::get("hb_account_id")), "backuplimit" => -1]);
                                                                $today = new DateTime();
                                                                $today->add(new DateInterval("P30D"));
                                                                $reset_date = $today->format("Y-m-d 00:00:00");
                                                                $db->insert("vncp_bandwidth_monitor", ["node" => parse_input(Input::get("node")), "pool_id" => parse_input(Input::get("poolid")), "hb_account_id" => parse_input(Input::get("hb_account_id")), "ct_type" => "lxc", "current" => 0, "max" => (int) parse_input(Input::get("bandwidth_limit")) * 1073741824, "reset_date" => $reset_date, "suspended" => 0]);
                                                                $saved_network = explode(".", Input::get("ipv4gw"));
                                                                $db->insert("vncp_dhcp", ["mac_address" => $saved_macaddr, "ip" => explode("/", parse_input(Input::get("ipv4")))[0], "gateway" => parse_input(Input::get("ipv4gw")), "netmask" => parse_input(Input::get("ipv4_netmask")), "network" => $saved_network[0] . "." . $saved_network[1] . "." . $saved_network[2] . "." . (string) ((int) $saved_network[3] - 1), "type" => 0]);
                                                                $log->log("Created new LXC " . $getvmid . " on node " . parse_input(Input::get("node")), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                $lxcCreatedSuccess = true;
                                                            }
                                                        } else {
                                                            $errors = "Could not login to Proxmox node.";
                                                        }
                                                    }
                                                } else {
                                                    $errors = "User ID does not exist.";
                                                }
                                            } else {
                                                $errors = "User ID, Node, Operating System, LXC Storage Location, and NAT cannot be default.";
                                            }
                                        } else {
                                            $errors = "";
                                            foreach ($validation->errors() as $error) {
                                                $errors .= $error . "<br />";
                                            }
                                        }
                                    }
                                } else {
                                    if (Input::exists() && Input::get("action") == "kvm") {
                                        if (Token::check(Input::get("token"))) {
                                            $validate = new Validate();
                                            $validation = $validate->check($_POST, ["userid" => ["required" => true, "numonly" => true, "min-num" => 1], "node" => ["required" => true, "max" => 50], "osfriendly" => ["required" => true, "max" => 200], "ostype" => ["required" => true, "max" => 7], "hb_account_id" => ["required" => true, "numonly" => true, "min-num" => 1, "unique_hbid" => true], "poolid" => ["required" => true, "max" => 100, "unique_poolid" => true], "ipv4" => ["required" => true, "max" => 15, "ip" => true], "ipv4_gateway" => ["required" => true, "max" => 15, "ip" => true], "ipv4_netmask" => ["required" => true, "max" => 15, "ip" => true], "hostname" => ["required" => true, "max" => 100], "storage_location" => ["required" => true, "max" => 100], "storage_size" => ["required" => true, "numonly" => true, "min-num" => 1], "cpucores" => ["required" => true, "numonly" => true, "min-num" => 1], "ram" => ["required" => true, "numonly" => true, "min-num" => 32], "nicdriver" => ["required" => true, "max" => 7], "cputype" => ["required" => true, "max" => 7], "storage_driver" => ["required" => true, "max" => 7], "os_installation_type" => ["required" => true, "max" => 8], "ostemplate" => ["required" => true, "max" => 7], "bandwidth_limit" => ["required" => true, "numonly" => true, "min-num" => 50, "max-num" => 102400], "portspeed" => ["required" => true, "numonly" => true, "min-num" => 0, "max-num" => 10000], "setmacaddress" => ["max" => 17, "macaddr" => true], "kvmisnat" => ["required" => true, "strbool" => true], "setvlantag" => ["required" => true, "numonly" => true, "min-num" => 0, "max-num" => 4094]]);
                                            if ($validation->passed()) {
                                                if (Input::get("os_installation_type") == "iso") {
                                                    if (Input::get("node") != "default" && Input::get("osfriendly") != "default" && Input::get("storage_location") != "default" && Input::get("ostype") != "default" && Input::get("nicdriver") != "default" && Input::get("cputype") != "default" && Input::get("storage_driver") != "default" && Input::get("userid") != "default" && Input::get("kvmisnat") != "default") {
                                                        $users_results = $db->get("vncp_users", ["id", "=", parse_input(Input::get("userid"))]);
                                                        $users_results = $users_results->all();
                                                        if (count($users_results) == 1) {
                                                            $kvmisnat = parse_input(Input::get("kvmisnat"));
                                                            $natpublicports = parse_input(Input::get("natpublicports"));
                                                            $natdomainproxy = parse_input(Input::get("natdomainproxy"));
                                                            if ($kvmisnat == "true") {
                                                                if (isset($natpublicports) && !empty($natpublicports) && 1 <= (int) $natpublicports && (int) $natpublicports <= 30) {
                                                                    if (isset($natdomainproxy) && 0 <= (int) $natdomainproxy && (int) $natdomainproxy <= 15) {
                                                                        $getNATCIDR = $db->get("vncp_nat", ["node", "=", parse_input(Input::get("node"))])->all();
                                                                        if (count($getNATCIDR) == 1) {
                                                                            $NATCIDR = $getNATCIDR[0]->natcidr;
                                                                            if (_obfuscated_0D1D160B191F262E1C10382E1A2808231A172828272201_(parse_input(Input::get("ipv4")), $NATCIDR)) {
                                                                                $node_results = $db->get("vncp_nodes", ["name", "=", parse_input(Input::get("node"))]);
                                                                                $node_data = $node_results->first();
                                                                                $pxAPI = new PVE2_API($node_data->hostname, $node_data->username, $node_data->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($node_data->password));
                                                                                $noLogin = false;
                                                                                if (!$pxAPI->login()) {
                                                                                    $noLogin = true;
                                                                                }
                                                                                if (!$noLogin) {
                                                                                    $plaintext_password = _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(12);
                                                                                    $createpool = $pxAPI->post("/pools", ["poolid" => Input::get("poolid")]);
                                                                                    sleep(1);
                                                                                    $createuser = $pxAPI->post("/access/users", ["userid" => Input::get("poolid") . "@pve", "password" => $plaintext_password]);
                                                                                    sleep(1);
                                                                                    $setpoolperms = $pxAPI->put("/access/acl", ["path" => "/pool/" . Input::get("poolid"), "users" => Input::get("poolid") . "@pve", "roles" => "PVEVMUser"]);
                                                                                    sleep(1);
                                                                                    $allVMIDs = [];
                                                                                    $getallKVM = $pxAPI->get("/nodes/" . Input::get("node") . "/qemu");
                                                                                    for ($i = 0; $i < count($getallKVM); $i++) {
                                                                                        $allVMIDs[] = (int) $getallKVM[$i]["vmid"];
                                                                                    }
                                                                                    $getallLXC = $pxAPI->get("/nodes/" . Input::get("node") . "/lxc");
                                                                                    for ($i = 0; $i < count($getallLXC); $i++) {
                                                                                        $allVMIDs[] = (int) $getallLXC[$i]["vmid"];
                                                                                    }
                                                                                    $getvmid = array_keys($allVMIDs, max($allVMIDs));
                                                                                    $getvmid = (int) $allVMIDs[$getvmid[0]] + 1;
                                                                                    if ((int) $getvmid < 100) {
                                                                                        $getvmid = 100;
                                                                                    }
                                                                                    sleep(1);
                                                                                    if (Input::get("storage_driver") == "ide") {
                                                                                        $bootdisk = "ide0";
                                                                                        $vga = "std";
                                                                                    } else {
                                                                                        $bootdisk = "virtio0";
                                                                                        $vga = "cirrus";
                                                                                    }
                                                                                    if (Input::get("setmacaddress")) {
                                                                                        $saved_macaddr = strtoupper(parse_input(Input::get("setmacaddress")));
                                                                                    } else {
                                                                                        $saved_macaddr = MacAddress::generateMacAddress();
                                                                                    }
                                                                                    $newvm = ["vmid" => (int) $getvmid, "agent" => 0, "acpi" => 1, "balloon" => (int) Input::get("ram"), "boot" => "cdn", "bootdisk" => $bootdisk, "cores" => (int) Input::get("cpucores"), "cpu" => Input::get("cputype"), "cpulimit" => "0", "cpuunits" => 1024, "description" => parse_input(Input::get("ipv4")), "hotplug" => "1", "ide2" => Input::get("osfriendly") . ",media=cdrom", "kvm" => 1, "localtime" => 1, "memory" => (int) Input::get("ram"), "name" => parse_input(Input::get("hostname")), "numa" => 0, "onboot" => 0, "ostype" => Input::get("ostype"), "pool" => parse_input(Input::get("poolid")), "protection" => 0, "reboot" => 1, "sockets" => 1, "storage" => Input::get("storage_location"), "tablet" => 1, "template" => 0, "vga" => $vga];
                                                                                    if ((int) parse_input(Input::get("portspeed")) <= 0) {
                                                                                        $newvm["net0"] = "bridge=vmbr10," . parse_input(Input::get("nicdriver")) . "=" . $saved_macaddr;
                                                                                    } else {
                                                                                        $newvm["net0"] = "bridge=vmbr10," . parse_input(Input::get("nicdriver")) . "=" . $saved_macaddr . ",rate=" . (string) parse_input(Input::get("portspeed"));
                                                                                    }
                                                                                    if (0 < (int) Input::get("setvlantag")) {
                                                                                        $newvm["net0"] = $newvm["net0"] . ",tag=" . (string) Input::get("setvlantag");
                                                                                    }
                                                                                    if (Input::get("storage_driver") == "ide") {
                                                                                        $newvm["ide0"] = Input::get("storage_location") . ":" . Input::get("storage_size") . ",cache=writeback";
                                                                                    } else {
                                                                                        $newvm["virtio0"] = Input::get("storage_location") . ":" . Input::get("storage_size") . ",cache=writeback";
                                                                                    }
                                                                                    $createkvm = $pxAPI->post("/nodes/" . Input::get("node") . "/qemu", $newvm);
                                                                                    if (!$createkvm) {
                                                                                        $log->log("Could not create NAT KVM. Proxmox API returned error.", "error", 2, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                                        $errors = "Could not create NAT KVM. Proxmox API returned error.";
                                                                                    } else {
                                                                                        $allow_backups = parse_input($db->get("vncp_settings", ["item", "=", "enable_backups"])->first()->value);
                                                                                        $abvalue = -1;
                                                                                        if ($allow_backups == "true") {
                                                                                            $abvalue = 1;
                                                                                        } else {
                                                                                            $abvalue = 0;
                                                                                        }
                                                                                        $db->insert("vncp_kvm_ct", ["user_id" => parse_input(Input::get("userid")), "node" => parse_input(Input::get("node")), "os" => explode("/", parse_input(Input::get("osfriendly")))[1], "hb_account_id" => parse_input(Input::get("hb_account_id")), "pool_id" => parse_input(Input::get("poolid")), "pool_password" => _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_($plaintext_password), "ip" => parse_input(Input::get("ipv4")), "suspended" => 0, "allow_backups" => $abvalue, "fw_enabled_net0" => 0, "fw_enabled_net1" => 0, "has_net1" => 0, "onboot" => 0, "cloud_account_id" => 0, "cloud_hostname" => "", "from_template" => 0]);
                                                                                        $db->insert("vncp_ct_backups", ["userid" => parse_input(Input::get("userid")), "hb_account_id" => parse_input(Input::get("hb_account_id")), "backuplimit" => -1]);
                                                                                        $today = new DateTime();
                                                                                        $today->add(new DateInterval("P30D"));
                                                                                        $reset_date = $today->format("Y-m-d 00:00:00");
                                                                                        $db->insert("vncp_bandwidth_monitor", ["node" => parse_input(Input::get("node")), "pool_id" => parse_input(Input::get("poolid")), "hb_account_id" => parse_input(Input::get("hb_account_id")), "ct_type" => "qemu", "current" => 0, "max" => (int) parse_input(Input::get("bandwidth_limit")) * 1073741824, "reset_date" => $reset_date, "suspended" => 0]);
                                                                                        $saved_network = explode(".", Input::get("ipv4_gateway"));
                                                                                        $saved_dhcp = $saved_network[0] . "." . $saved_network[1] . "." . $saved_network[2] . "." . (string) ((int) $saved_network[3] - 1);
                                                                                        $db->insert("vncp_dhcp", ["mac_address" => $saved_macaddr, "ip" => parse_input(Input::get("ipv4")), "gateway" => parse_input(Input::get("ipv4_gateway")), "netmask" => parse_input(Input::get("ipv4_netmask")), "network" => $saved_dhcp, "type" => 0]);
                                                                                        $db->insert("vncp_natforwarding", ["user_id" => parse_input(Input::get("userid")), "node" => parse_input(Input::get("node")), "hb_account_id" => parse_input(Input::get("hb_account_id")), "avail_ports" => (int) $natpublicports, "ports" => "", "avail_domains" => (int) $natdomainproxy, "domains" => ""]);
                                                                                        $fulldhcp = $db->get("vncp_dhcp", ["network", "=", $saved_dhcp])->all();
                                                                                        if ($dhcp_server = $db->get("vncp_dhcp_servers", ["dhcp_network", "=", $saved_dhcp])->first()) {
                                                                                            $ssh = new phpseclib\Net\SSH2($dhcp_server->hostname, (int) $dhcp_server->port);
                                                                                            if (!$ssh->login("root", _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($dhcp_server->password))) {
                                                                                                $log->log("Could not SSH to DHCP server " . $dhcp_server->hostname, "error", 1, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                                            } else {
                                                                                                $ssh->exec("printf 'ddns-update-style none;\n\n' > /root/dhcpd.test");
                                                                                                $ssh->exec("printf 'option domain-name-servers 8.8.8.8, 8.8.4.4;\n\n' >> /root/dhcpd.test");
                                                                                                $ssh->exec("printf 'default-lease-time 7200;\n' >> /root/dhcpd.test");
                                                                                                $ssh->exec("printf 'max-lease-time 86400;\n\n' >> /root/dhcpd.test");
                                                                                                $ssh->exec("printf 'log-facility local7;\n\n' >> /root/dhcpd.test");
                                                                                                $ssh->exec("printf 'subnet " . $saved_dhcp . " netmask " . $fulldhcp[0]->netmask . " {}\n\n' >> /root/dhcpd.test");
                                                                                                for ($i = 0; $i < count($fulldhcp); $i++) {
                                                                                                    $ssh->exec("printf 'host " . $fulldhcp[$i]->id . " {hardware ethernet " . $fulldhcp[$i]->mac_address . ";fixed-address " . $fulldhcp[$i]->ip . ";option routers " . $fulldhcp[$i]->gateway . ";}\n' >> /root/dhcpd.test");
                                                                                                }
                                                                                                $ssh->exec("mv /root/dhcpd.test /etc/dhcp/dhcpd.conf && rm /root/dhcpd.test");
                                                                                                $ssh->exec("service isc-dhcp-server restart");
                                                                                                $ssh->disconnect();
                                                                                            }
                                                                                        } else {
                                                                                            $log->log("No DHCP server exists for " . $saved_dhcp, "error", 1, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                                        }
                                                                                        $log->log("Created new NAT KVM " . $getvmid . " on node " . parse_input(Input::get("node")), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                                        $kvmCreatedSuccess = true;
                                                                                    }
                                                                                } else {
                                                                                    $errors = "Could not login to Proxmox node.";
                                                                                }
                                                                            } else {
                                                                                $errors = "IPv4 is not in selected node's NAT range.";
                                                                            }
                                                                        } else {
                                                                            $errors = "Selected node is not NAT-enabled.";
                                                                        }
                                                                    } else {
                                                                        $errors = "NAT Domain Forwarding cannot be empty and must be between 0 - 15.";
                                                                    }
                                                                } else {
                                                                    $errors = "NAT Public Ports cannot be empty and must be between 1 - 30.";
                                                                }
                                                            } else {
                                                                $node_results = $db->get("vncp_nodes", ["name", "=", parse_input(Input::get("node"))]);
                                                                $node_data = $node_results->first();
                                                                $pxAPI = new PVE2_API($node_data->hostname, $node_data->username, $node_data->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($node_data->password));
                                                                $noLogin = false;
                                                                if (!$pxAPI->login()) {
                                                                    $noLogin = true;
                                                                }
                                                                if (!$noLogin) {
                                                                    $plaintext_password = _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(12);
                                                                    $createpool = $pxAPI->post("/pools", ["poolid" => Input::get("poolid")]);
                                                                    sleep(1);
                                                                    $createuser = $pxAPI->post("/access/users", ["userid" => Input::get("poolid") . "@pve", "password" => $plaintext_password]);
                                                                    sleep(1);
                                                                    $setpoolperms = $pxAPI->put("/access/acl", ["path" => "/pool/" . Input::get("poolid"), "users" => Input::get("poolid") . "@pve", "roles" => "PVEVMUser"]);
                                                                    sleep(1);
                                                                    $allVMIDs = [];
                                                                    $getallKVM = $pxAPI->get("/nodes/" . Input::get("node") . "/qemu");
                                                                    for ($i = 0; $i < count($getallKVM); $i++) {
                                                                        $allVMIDs[] = (int) $getallKVM[$i]["vmid"];
                                                                    }
                                                                    $getallLXC = $pxAPI->get("/nodes/" . Input::get("node") . "/lxc");
                                                                    for ($i = 0; $i < count($getallLXC); $i++) {
                                                                        $allVMIDs[] = (int) $getallLXC[$i]["vmid"];
                                                                    }
                                                                    $getvmid = array_keys($allVMIDs, max($allVMIDs));
                                                                    $getvmid = (int) $allVMIDs[$getvmid[0]] + 1;
                                                                    if ((int) $getvmid < 100) {
                                                                        $getvmid = 100;
                                                                    }
                                                                    sleep(1);
                                                                    if (Input::get("storage_driver") == "ide") {
                                                                        $bootdisk = "ide0";
                                                                        $vga = "std";
                                                                    } else {
                                                                        $bootdisk = "virtio0";
                                                                        $vga = "cirrus";
                                                                    }
                                                                    if (Input::get("setmacaddress")) {
                                                                        $saved_macaddr = strtoupper(parse_input(Input::get("setmacaddress")));
                                                                    } else {
                                                                        $saved_macaddr = MacAddress::generateMacAddress();
                                                                    }
                                                                    $newvm = ["vmid" => (int) $getvmid, "agent" => 0, "acpi" => 1, "balloon" => (int) Input::get("ram"), "boot" => "cdn", "bootdisk" => $bootdisk, "cores" => (int) Input::get("cpucores"), "cpu" => Input::get("cputype"), "cpulimit" => "0", "cpuunits" => 1024, "description" => parse_input(Input::get("ipv4")), "hotplug" => "1", "ide2" => Input::get("osfriendly") . ",media=cdrom", "kvm" => 1, "localtime" => 1, "memory" => (int) Input::get("ram"), "name" => parse_input(Input::get("hostname")), "numa" => 0, "onboot" => 0, "ostype" => Input::get("ostype"), "pool" => Input::get("poolid"), "protection" => 0, "reboot" => 1, "sockets" => 1, "storage" => Input::get("storage_location"), "tablet" => 1, "template" => 0, "vga" => $vga];
                                                                    if ((int) parse_input(Input::get("portspeed")) <= 0) {
                                                                        $newvm["net0"] = "bridge=vmbr0," . parse_input(Input::get("nicdriver")) . "=" . $saved_macaddr;
                                                                    } else {
                                                                        $newvm["net0"] = "bridge=vmbr0," . parse_input(Input::get("nicdriver")) . "=" . $saved_macaddr . ",rate=" . (string) parse_input(Input::get("portspeed"));
                                                                    }
                                                                    if (0 < (int) Input::get("setvlantag")) {
                                                                        $newvm["net0"] = $newvm["net0"] . ",tag=" . (string) Input::get("setvlantag");
                                                                    }
                                                                    if (Input::get("storage_driver") == "ide") {
                                                                        $newvm["ide0"] = Input::get("storage_location") . ":" . Input::get("storage_size") . ",cache=writeback";
                                                                    } else {
                                                                        $newvm["virtio0"] = Input::get("storage_location") . ":" . Input::get("storage_size") . ",cache=writeback";
                                                                    }
                                                                    $createkvm = $pxAPI->post("/nodes/" . Input::get("node") . "/qemu", $newvm);
                                                                    if (!$createkvm) {
                                                                        $log->log("Could not create KVM. Proxmox API returned error.", "error", 2, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                        $errors = "Could not create KVM. Proxmox API returned error.";
                                                                    } else {
                                                                        $allow_backups = parse_input($db->get("vncp_settings", ["item", "=", "enable_backups"])->first()->value);
                                                                        $abvalue = -1;
                                                                        if ($allow_backups == "true") {
                                                                            $abvalue = 1;
                                                                        } else {
                                                                            $abvalue = 0;
                                                                        }
                                                                        $db->insert("vncp_kvm_ct", ["user_id" => parse_input(Input::get("userid")), "node" => parse_input(Input::get("node")), "os" => explode("/", parse_input(Input::get("osfriendly")))[1], "hb_account_id" => parse_input(Input::get("hb_account_id")), "pool_id" => parse_input(Input::get("poolid")), "pool_password" => _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_($plaintext_password), "ip" => parse_input(Input::get("ipv4")), "suspended" => 0, "allow_backups" => $abvalue, "fw_enabled_net0" => 0, "fw_enabled_net1" => 0, "has_net1" => 0, "onboot" => 0, "cloud_account_id" => 0, "cloud_hostname" => "", "from_template" => 0]);
                                                                        $db->insert("vncp_ct_backups", ["userid" => parse_input(Input::get("userid")), "hb_account_id" => parse_input(Input::get("hb_account_id")), "backuplimit" => -1]);
                                                                        $today = new DateTime();
                                                                        $today->add(new DateInterval("P30D"));
                                                                        $reset_date = $today->format("Y-m-d 00:00:00");
                                                                        $db->insert("vncp_bandwidth_monitor", ["node" => parse_input(Input::get("node")), "pool_id" => parse_input(Input::get("poolid")), "hb_account_id" => parse_input(Input::get("hb_account_id")), "ct_type" => "qemu", "current" => 0, "max" => (int) parse_input(Input::get("bandwidth_limit")) * 1073741824, "reset_date" => $reset_date, "suspended" => 0]);
                                                                        $saved_network = explode(".", Input::get("ipv4_gateway"));
                                                                        $saved_dhcp = $saved_network[0] . "." . $saved_network[1] . "." . $saved_network[2] . "." . (string) ((int) $saved_network[3] - 1);
                                                                        $db->insert("vncp_dhcp", ["mac_address" => $saved_macaddr, "ip" => parse_input(Input::get("ipv4")), "gateway" => parse_input(Input::get("ipv4_gateway")), "netmask" => parse_input(Input::get("ipv4_netmask")), "network" => $saved_dhcp, "type" => 0]);
                                                                        $fulldhcp = $db->get("vncp_dhcp", ["network", "=", $saved_dhcp])->all();
                                                                        if ($dhcp_server = $db->get("vncp_dhcp_servers", ["dhcp_network", "=", $saved_dhcp])->first()) {
                                                                            $ssh = new phpseclib\Net\SSH2($dhcp_server->hostname, (int) $dhcp_server->port);
                                                                            if (!$ssh->login("root", _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($dhcp_server->password))) {
                                                                                $log->log("Could not SSH to DHCP server " . $dhcp_server->hostname, "error", 1, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                            } else {
                                                                                $ssh->exec("printf 'ddns-update-style none;\n\n' > /root/dhcpd.test");
                                                                                $ssh->exec("printf 'option domain-name-servers 8.8.8.8, 8.8.4.4;\n\n' >> /root/dhcpd.test");
                                                                                $ssh->exec("printf 'default-lease-time 7200;\n' >> /root/dhcpd.test");
                                                                                $ssh->exec("printf 'max-lease-time 86400;\n\n' >> /root/dhcpd.test");
                                                                                $ssh->exec("printf 'log-facility local7;\n\n' >> /root/dhcpd.test");
                                                                                $ssh->exec("printf 'subnet " . $saved_dhcp . " netmask " . $fulldhcp[0]->netmask . " {}\n\n' >> /root/dhcpd.test");
                                                                                for ($i = 0; $i < count($fulldhcp); $i++) {
                                                                                    $ssh->exec("printf 'host " . $fulldhcp[$i]->id . " {hardware ethernet " . $fulldhcp[$i]->mac_address . ";fixed-address " . $fulldhcp[$i]->ip . ";option routers " . $fulldhcp[$i]->gateway . ";}\n' >> /root/dhcpd.test");
                                                                                }
                                                                                $ssh->exec("mv /root/dhcpd.test /etc/dhcp/dhcpd.conf && rm /root/dhcpd.test");
                                                                                $ssh->exec("service isc-dhcp-server restart");
                                                                                $ssh->disconnect();
                                                                            }
                                                                        } else {
                                                                            $log->log("No DHCP server exists for " . $saved_dhcp, "error", 1, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                        }
                                                                        $log->log("Created new KVM " . $getvmid . " on node " . parse_input(Input::get("node")), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                        $kvmCreatedSuccess = true;
                                                                    }
                                                                } else {
                                                                    $errors = "Could not login to Proxmox node.";
                                                                }
                                                            }
                                                        } else {
                                                            $errors = "User ID does not exist.";
                                                        }
                                                    } else {
                                                        $errors = "User ID, Node, Operating System, CPU type, drivers, KVM Storage Location, and KVM NAT cannot be default.";
                                                    }
                                                } else {
                                                    if (Input::get("os_installation_type") == "template") {
                                                        if (Input::get("node") != "default" && Input::get("ostemplate") != "default" && Input::get("storage_location") != "default" && Input::get("cputype") != "default" && Input::get("userid") != "default" && Input::get("kvmisnat") != "default") {
                                                            $users_results = $db->get("vncp_users", ["id", "=", parse_input(Input::get("userid"))]);
                                                            $users_results = $users_results->all();
                                                            if (count($users_results) == 1) {
                                                                $kvmisnat = parse_input(Input::get("kvmisnat"));
                                                                $natpublicports = parse_input(Input::get("natpublicports"));
                                                                $natdomainproxy = parse_input(Input::get("natdomainproxy"));
                                                                if ($kvmisnat == "true") {
                                                                    if (isset($natpublicports) && !empty($natpublicports) && 1 <= (int) $natpublicports && (int) $natpublicports <= 30) {
                                                                        if (isset($natdomainproxy) && 0 <= (int) $natdomainproxy && (int) $natdomainproxy <= 15) {
                                                                            $getNATCIDR = $db->get("vncp_nat", ["node", "=", parse_input(Input::get("node"))])->all();
                                                                            if (count($getNATCIDR) == 1) {
                                                                                $NATCIDR = $getNATCIDR[0]->natcidr;
                                                                                if (_obfuscated_0D1D160B191F262E1C10382E1A2808231A172828272201_(parse_input(Input::get("ipv4")), $NATCIDR)) {
                                                                                    $node_results = $db->get("vncp_nodes", ["name", "=", parse_input(Input::get("node"))]);
                                                                                    $node_data = $node_results->first();
                                                                                    $pxAPI = new PVE2_API($node_data->hostname, $node_data->username, $node_data->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($node_data->password));
                                                                                    $noLogin = false;
                                                                                    if (!$pxAPI->login()) {
                                                                                        $noLogin = true;
                                                                                    }
                                                                                    if (!$noLogin) {
                                                                                        $plaintext_password = _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(12);
                                                                                        $cipassword = _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(16);
                                                                                        $createpool = $pxAPI->post("/pools", ["poolid" => Input::get("poolid")]);
                                                                                        sleep(1);
                                                                                        $createuser = $pxAPI->post("/access/users", ["userid" => Input::get("poolid") . "@pve", "password" => $plaintext_password]);
                                                                                        sleep(1);
                                                                                        $setpoolperms = $pxAPI->put("/access/acl", ["path" => "/pool/" . Input::get("poolid"), "users" => Input::get("poolid") . "@pve", "roles" => "PVEVMUser"]);
                                                                                        sleep(1);
                                                                                        $allVMIDs = [];
                                                                                        $getallKVM = $pxAPI->get("/nodes/" . Input::get("node") . "/qemu");
                                                                                        for ($i = 0; $i < count($getallKVM); $i++) {
                                                                                            $allVMIDs[] = (int) $getallKVM[$i]["vmid"];
                                                                                        }
                                                                                        $getallLXC = $pxAPI->get("/nodes/" . Input::get("node") . "/lxc");
                                                                                        for ($i = 0; $i < count($getallLXC); $i++) {
                                                                                            $allVMIDs[] = (int) $getallLXC[$i]["vmid"];
                                                                                        }
                                                                                        $getvmid = array_keys($allVMIDs, max($allVMIDs));
                                                                                        $getvmid = (int) $allVMIDs[$getvmid[0]] + 1;
                                                                                        if ((int) $getvmid < 100) {
                                                                                            $getvmid = 100;
                                                                                        }
                                                                                        sleep(1);
                                                                                        $clone_type = "qcow2";
                                                                                        if (strpos(Input::get("storage_location"), "lvm") !== false) {
                                                                                            $clone_type = "raw";
                                                                                        }
                                                                                        $newvm = ["newid" => (int) $getvmid, "description" => parse_input(Input::get("ipv4")), "format" => $clone_type, "full" => 1, "name" => parse_input(Input::get("hostname")), "pool" => Input::get("poolid"), "storage" => Input::get("storage_location")];
                                                                                        $clonevm = $db->get("vncp_kvm_templates", ["id", "=", parse_input(Input::get("ostemplate"))])->first();
                                                                                        $createkvm = $pxAPI->post("/nodes/" . Input::get("node") . "/qemu/" . $clonevm->vmid . "/clone", $newvm);
                                                                                        if (!$createkvm) {
                                                                                            $log->log("Could not clone NAT KVM. Proxmox API returned error.", "error", 2, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                                            $errors = "Could not clone NAT KVM. Proxmox API returned error.";
                                                                                        } else {
                                                                                            $db->insert("vncp_pending_clone", ["node" => parse_input(Input::get("node")), "upid" => $createkvm, "hb_account_id" => parse_input(Input::get("hb_account_id")), "data" => json_encode(["vmid" => $getvmid, "cores" => parse_input(Input::get("cpucores")), "cpu" => parse_input(Input::get("cputype")), "memory" => parse_input(Input::get("ram")), "cipassword" => _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_($cipassword), "storage_size" => parse_input(Input::get("storage_size")), "cvmtype" => $clonevm->type, "gateway" => parse_input(Input::get("ipv4_gateway")), "ip" => parse_input(Input::get("ipv4")), "netmask" => parse_input(Input::get("ipv4_netmask")), "portspeed" => parse_input(Input::get("portspeed")), "setmacaddress" => strtoupper(parse_input(Input::get("setmacaddress"))), "vlantag" => parse_input(Input::get("setvlantag"))])]);
                                                                                            $allow_backups = parse_input($db->get("vncp_settings", ["item", "=", "enable_backups"])->first()->value);
                                                                                            $abvalue = -1;
                                                                                            if ($allow_backups == "true") {
                                                                                                $abvalue = 1;
                                                                                            } else {
                                                                                                $abvalue = 0;
                                                                                            }
                                                                                            $db->insert("vncp_kvm_ct", ["user_id" => parse_input(Input::get("userid")), "node" => parse_input(Input::get("node")), "os" => $clonevm->friendly_name, "hb_account_id" => parse_input(Input::get("hb_account_id")), "pool_id" => parse_input(Input::get("poolid")), "pool_password" => _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_($plaintext_password), "ip" => parse_input(Input::get("ipv4")), "suspended" => 0, "allow_backups" => $abvalue, "fw_enabled_net0" => 0, "fw_enabled_net1" => 0, "has_net1" => 0, "onboot" => 0, "cloud_account_id" => 0, "cloud_hostname" => "", "from_template" => 1]);
                                                                                            $db->insert("vncp_ct_backups", ["userid" => parse_input(Input::get("userid")), "hb_account_id" => parse_input(Input::get("hb_account_id")), "backuplimit" => -1]);
                                                                                            $today = new DateTime();
                                                                                            $today->add(new DateInterval("P30D"));
                                                                                            $reset_date = $today->format("Y-m-d 00:00:00");
                                                                                            $db->insert("vncp_bandwidth_monitor", ["node" => parse_input(Input::get("node")), "pool_id" => parse_input(Input::get("poolid")), "hb_account_id" => parse_input(Input::get("hb_account_id")), "ct_type" => "qemu", "current" => 0, "max" => (int) parse_input(Input::get("bandwidth_limit")) * 1073741824, "reset_date" => $reset_date, "suspended" => 0]);
                                                                                            $db->insert("vncp_natforwarding", ["user_id" => parse_input(Input::get("userid")), "node" => parse_input(Input::get("node")), "hb_account_id" => parse_input(Input::get("hb_account_id")), "avail_ports" => (int) $natpublicports, "ports" => "", "avail_domains" => (int) $natdomainproxy, "domains" => ""]);
                                                                                            $log->log("Created new NAT KVM " . $getvmid . " on node " . parse_input(Input::get("node")), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                                            $kvmCreatedSuccess = true;
                                                                                        }
                                                                                    } else {
                                                                                        $errors = "Could not login to Proxmox node.";
                                                                                    }
                                                                                } else {
                                                                                    $errors = "IPv4 is not in selected node's NAT range.";
                                                                                }
                                                                            } else {
                                                                                $errors = "Selected node is not NAT-enabled.";
                                                                            }
                                                                        } else {
                                                                            $errors = "NAT Domain Forwarding cannot be empty and must be between 0 - 15.";
                                                                        }
                                                                    } else {
                                                                        $errors = "NAT Public Ports cannot be empty and must be between 1 - 30.";
                                                                    }
                                                                } else {
                                                                    $node_results = $db->get("vncp_nodes", ["name", "=", parse_input(Input::get("node"))]);
                                                                    $node_data = $node_results->first();
                                                                    $pxAPI = new PVE2_API($node_data->hostname, $node_data->username, $node_data->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($node_data->password));
                                                                    $noLogin = false;
                                                                    if (!$pxAPI->login()) {
                                                                        $noLogin = true;
                                                                    }
                                                                    if (!$noLogin) {
                                                                        $plaintext_password = _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(12);
                                                                        $cipassword = _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(16);
                                                                        $createpool = $pxAPI->post("/pools", ["poolid" => Input::get("poolid")]);
                                                                        sleep(1);
                                                                        $createuser = $pxAPI->post("/access/users", ["userid" => Input::get("poolid") . "@pve", "password" => $plaintext_password]);
                                                                        sleep(1);
                                                                        $setpoolperms = $pxAPI->put("/access/acl", ["path" => "/pool/" . Input::get("poolid"), "users" => Input::get("poolid") . "@pve", "roles" => "PVEVMUser"]);
                                                                        sleep(1);
                                                                        $allVMIDs = [];
                                                                        $getallKVM = $pxAPI->get("/nodes/" . Input::get("node") . "/qemu");
                                                                        for ($i = 0; $i < count($getallKVM); $i++) {
                                                                            $allVMIDs[] = (int) $getallKVM[$i]["vmid"];
                                                                        }
                                                                        $getallLXC = $pxAPI->get("/nodes/" . Input::get("node") . "/lxc");
                                                                        for ($i = 0; $i < count($getallLXC); $i++) {
                                                                            $allVMIDs[] = (int) $getallLXC[$i]["vmid"];
                                                                        }
                                                                        $getvmid = array_keys($allVMIDs, max($allVMIDs));
                                                                        $getvmid = (int) $allVMIDs[$getvmid[0]] + 1;
                                                                        if ((int) $getvmid < 100) {
                                                                            $getvmid = 100;
                                                                        }
                                                                        sleep(1);
                                                                        $clone_type = "qcow2";
                                                                        if (strpos(Input::get("storage_location"), "lvm") !== false) {
                                                                            $clone_type = "raw";
                                                                        }
                                                                        $newvm = ["newid" => (int) $getvmid, "description" => parse_input(Input::get("ipv4")), "format" => $clone_type, "full" => 1, "name" => parse_input(Input::get("hostname")), "pool" => Input::get("poolid"), "storage" => Input::get("storage_location")];
                                                                        $clonevm = $db->get("vncp_kvm_templates", ["id", "=", parse_input(Input::get("ostemplate"))])->first();
                                                                        $createkvm = $pxAPI->post("/nodes/" . Input::get("node") . "/qemu/" . $clonevm->vmid . "/clone", $newvm);
                                                                        if (!$createkvm) {
                                                                            $log->log("Could not clone KVM. Proxmox API returned error.", "error", 2, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                            $errors = "Could not clone KVM. Proxmox API returned error.";
                                                                        } else {
                                                                            $db->insert("vncp_pending_clone", ["node" => parse_input(Input::get("node")), "upid" => $createkvm, "hb_account_id" => parse_input(Input::get("hb_account_id")), "data" => json_encode(["vmid" => $getvmid, "cores" => parse_input(Input::get("cpucores")), "cpu" => parse_input(Input::get("cputype")), "memory" => parse_input(Input::get("ram")), "cipassword" => _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_($cipassword), "storage_size" => parse_input(Input::get("storage_size")), "cvmtype" => $clonevm->type, "gateway" => parse_input(Input::get("ipv4_gateway")), "ip" => parse_input(Input::get("ipv4")), "netmask" => parse_input(Input::get("ipv4_netmask")), "portspeed" => parse_input(Input::get("portspeed")), "setmacaddress" => strtoupper(parse_input(Input::get("setmacaddress"))), "vlantag" => parse_input(Input::get("setvlantag"))])]);
                                                                            $allow_backups = parse_input($db->get("vncp_settings", ["item", "=", "enable_backups"])->first()->value);
                                                                            $abvalue = -1;
                                                                            if ($allow_backups == "true") {
                                                                                $abvalue = 1;
                                                                            } else {
                                                                                $abvalue = 0;
                                                                            }
                                                                            $db->insert("vncp_kvm_ct", ["user_id" => parse_input(Input::get("userid")), "node" => parse_input(Input::get("node")), "os" => $clonevm->friendly_name, "hb_account_id" => parse_input(Input::get("hb_account_id")), "pool_id" => parse_input(Input::get("poolid")), "pool_password" => _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_($plaintext_password), "ip" => parse_input(Input::get("ipv4")), "suspended" => 0, "allow_backups" => $abvalue, "fw_enabled_net0" => 0, "fw_enabled_net1" => 0, "has_net1" => 0, "onboot" => 0, "cloud_account_id" => 0, "cloud_hostname" => "", "from_template" => 1]);
                                                                            $db->insert("vncp_ct_backups", ["userid" => parse_input(Input::get("userid")), "hb_account_id" => parse_input(Input::get("hb_account_id")), "backuplimit" => -1]);
                                                                            $today = new DateTime();
                                                                            $today->add(new DateInterval("P30D"));
                                                                            $reset_date = $today->format("Y-m-d 00:00:00");
                                                                            $db->insert("vncp_bandwidth_monitor", ["node" => parse_input(Input::get("node")), "pool_id" => parse_input(Input::get("poolid")), "hb_account_id" => parse_input(Input::get("hb_account_id")), "ct_type" => "qemu", "current" => 0, "max" => (int) parse_input(Input::get("bandwidth_limit")) * 1073741824, "reset_date" => $reset_date, "suspended" => 0]);
                                                                            $log->log("Created new KVM " . $getvmid . " on node " . parse_input(Input::get("node")), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                            $kvmCreatedSuccess = true;
                                                                        }
                                                                    } else {
                                                                        $errors = "Could not login to Proxmox node.";
                                                                    }
                                                                }
                                                            } else {
                                                                $errors = "User ID does not exist.";
                                                            }
                                                        } else {
                                                            $errors = "User ID, Node, Operating System, CPU type, KVM Storage Location, and KVM NAT cannot be default.";
                                                        }
                                                    } else {
                                                        $errors = "Invalid OS installation type.";
                                                    }
                                                }
                                            } else {
                                                $errors = "";
                                                foreach ($validation->errors() as $error) {
                                                    $errors .= $error . "<br />";
                                                }
                                            }
                                        }
                                    } else {
                                        if (Input::exists() && Input::get("action") == "lxctemp" && Input::get("form_name") == "new_lxc_template") {
                                            if (Token::check(Input::get("token"))) {
                                                $validate = new Validate();
                                                $validation = $validate->check($_POST, ["fname" => ["required" => true, "max" => 100], "volid" => ["required" => true, "max" => 200]]);
                                                if ($validation->passed()) {
                                                    $db->insert("vncp_lxc_templates", ["friendly_name" => Input::get("fname"), "volid" => Input::get("volid"), "content" => "vztmpl"]);
                                                    $log->log("Added new LXC template " . Input::get("fname"), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                    $lxcTempSuccess = true;
                                                } else {
                                                    $errors = "";
                                                    foreach ($validation->errors() as $error) {
                                                        $errors .= $error . "<br />";
                                                    }
                                                }
                                            }
                                        } else {
                                            if (Input::exists() && Input::get("action") == "lxctemp" && Input::get("form_name") == "import_lxc_template") {
                                                $lxcImportCount = 0;
                                                for ($i = 0; $i < count($_POST["field"]); $i++) {
                                                    $temp_fname = parse_input(trim($_POST["field"][$i]["fname"]));
                                                    $temp_volid = parse_input(trim($_POST["field"][$i]["volid"]));
                                                    if (!empty($temp_fname) && !is_numeric($temp_fname) && strlen($temp_fname) <= 100 && !empty($temp_volid) && !is_numeric($temp_volid) && strlen($temp_volid) <= 200 && strpos($temp_volid, ":vztmpl/") !== false) {
                                                        $db->insert("vncp_lxc_templates", ["friendly_name" => $temp_fname, "volid" => $temp_volid, "content" => "vztmpl"]);
                                                        $log->log("Imported new LXC template " . $temp_fname, "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                        $lxcImportCount++;
                                                    }
                                                }
                                                if (0 < $lxcImportCount) {
                                                    $lxcImportSuccess = true;
                                                }
                                            } else {
                                                if (Input::exists() && Input::get("action") == "api") {
                                                    if (Token::check(Input::get("token"))) {
                                                        $validate = new Validate();
                                                        $validation = $validate->check($_POST, ["apiip" => ["required" => true, "ip" => true, "max" => 15, "min" => 7]]);
                                                        if ($validation->passed()) {
                                                            $db->insert("vncp_api", ["api_id" => md5(_obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(32)), "api_key" => md5(_obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(32)), "api_ip" => Input::get("apiip")]);
                                                            $log->log("Added new API pair for " . Input::get("apiip"), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                        } else {
                                                            $errors = "";
                                                            foreach ($validation->errors() as $error) {
                                                                $errors .= $error . "<br />";
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    if (Input::exists() && Input::get("action") == "kvmtemp") {
                                                        if (Token::check(Input::get("token"))) {
                                                            $validate = new Validate();
                                                            $validation = $validate->check($_POST, ["fname" => ["required" => true, "max" => 100], "template_vmid" => ["required" => true, "numonly" => true, "max-num" => 999999999, "min-num" => 100], "template_type" => ["required" => true, "max" => 7, "min" => 5], "template_node" => ["required" => true, "max" => 50]]);
                                                            if ($validation->passed()) {
                                                                if (Input::get("template_node") != "default" && Input::get("template_type") != "default") {
                                                                    $db->insert("vncp_kvm_templates", ["vmid" => Input::get("template_vmid"), "friendly_name" => Input::get("fname"), "type" => Input::get("template_type"), "node" => Input::get("template_node")]);
                                                                    $log->log("Added new KVM template " . Input::get("fname"), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                    $kvmTempSuccess = true;
                                                                } else {
                                                                    $errors = "Form values cannot be default.";
                                                                }
                                                            } else {
                                                                $errors = "";
                                                                foreach ($validation->errors() as $error) {
                                                                    $errors .= $error . "<br />";
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        if (Input::exists() && Input::get("action") == "kvmiso" && Input::get("form_name") == "new_kvm_iso") {
                                                            if (Token::check(Input::get("token"))) {
                                                                $validate = new Validate();
                                                                $validation = $validate->check($_POST, ["fname" => ["required" => true, "max" => 100], "volid" => ["required" => true, "max" => 200]]);
                                                                if ($validation->passed()) {
                                                                    $db->insert("vncp_kvm_isos", ["friendly_name" => Input::get("fname"), "volid" => Input::get("volid"), "content" => "iso"]);
                                                                    $log->log("Added new KVM ISO " . Input::get("fname"), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                    $kvmIsoSuccess = true;
                                                                } else {
                                                                    $errors = "";
                                                                    foreach ($validation->errors() as $error) {
                                                                        $errors .= $error . "<br />";
                                                                    }
                                                                }
                                                            }
                                                        } else {
                                                            if (Input::exists() && Input::get("action") == "kvmiso" && Input::get("form_name") == "import_kvm_iso") {
                                                                $kvmImportCount = 0;
                                                                for ($i = 0; $i < count($_POST["field"]); $i++) {
                                                                    $temp_fname = parse_input(trim($_POST["field"][$i]["fname"]));
                                                                    $temp_volid = parse_input(trim($_POST["field"][$i]["volid"]));
                                                                    if (!empty($temp_fname) && !is_numeric($temp_fname) && strlen($temp_fname) <= 100 && !empty($temp_volid) && !is_numeric($temp_volid) && strlen($temp_volid) <= 200 && strpos($temp_volid, ":iso/") !== false) {
                                                                        $db->insert("vncp_kvm_isos", ["friendly_name" => $temp_fname, "volid" => $temp_volid, "content" => "iso"]);
                                                                        $log->log("Imported new KVM ISO " . $temp_fname, "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                        $kvmImportCount++;
                                                                    }
                                                                }
                                                                if (0 < $kvmImportCount) {
                                                                    $kvmImportSuccess = true;
                                                                }
                                                            } else {
                                                                if (Input::exists() && Input::get("action") == "tuntap") {
                                                                    if (Token::check(Input::get("token"))) {
                                                                        $validate = new Validate();
                                                                        $validation = $validate->check($_POST, ["tuntapnode" => ["required" => true, "max" => 50, "unique_node" => true], "rpassword" => ["required" => true, "max" => 200], "sshport" => ["required" => true, "min-num" => 1, "max-num" => 65535, "numonly" => true]]);
                                                                        if ($validation->passed()) {
                                                                            $checkExists = $db->get("vncp_nodes", ["name", "=", parse_input(Input::get("tuntapnode"))])->all();
                                                                            if (count($checkExists) == 1) {
                                                                                $db->insert("vncp_tuntap", ["node" => parse_input(Input::get("tuntapnode")), "password" => _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_(parse_input(Input::get("rpassword"))), "port" => parse_input(Input::get("sshport"))]);
                                                                                $log->log("Add new TUN/TAP credentials.", "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                            } else {
                                                                                $errors = "Node does not exist.";
                                                                            }
                                                                        } else {
                                                                            $errors = "";
                                                                            foreach ($validation->errors() as $error) {
                                                                                $errors .= $error . "<br />";
                                                                            }
                                                                        }
                                                                    }
                                                                } else {
                                                                    if (Input::exists() && Input::get("action") == "dhcp") {
                                                                        if (Token::check(Input::get("token"))) {
                                                                            $validate = new Validate();
                                                                            $validation = $validate->check($_POST, ["dhcphostname" => ["required" => true, "max" => 100, "min" => 3], "rpassword" => ["required" => true, "max" => 200], "sshport" => ["required" => true, "min-num" => 1, "max-num" => 65535, "numonly" => true], "dhcpnetwork" => ["required" => true, "max" => 15, "min" => 7, "ip" => true]]);
                                                                            if ($validation->passed()) {
                                                                                $validate_network = $db->get("vncp_dhcp", ["network", "=", Input::get("dhcpnetwork")])->all();
                                                                                if (0 < count($validate_network)) {
                                                                                    $db->insert("vncp_dhcp_servers", ["hostname" => Input::get("dhcphostname"), "password" => _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_(Input::get("rpassword")), "port" => Input::get("sshport"), "dhcp_network" => Input::get("dhcpnetwork")]);
                                                                                    $log->log("Added new DHCP server.", "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                                } else {
                                                                                    $errors = "DHCP network does not exist.";
                                                                                }
                                                                            } else {
                                                                                $errors = "";
                                                                                foreach ($validation->errors() as $error) {
                                                                                    $errors .= $error . "<br />";
                                                                                }
                                                                            }
                                                                        }
                                                                    } else {
                                                                        if (Input::exists() && Input::get("action") == "cloud" && Input::get("whatform") == "createcloud") {
                                                                            if (Token::check(Input::get("token"))) {
                                                                                $validate = new Validate();
                                                                                $validation = $validate->check($_POST, ["userid" => ["required" => true, "numonly" => true, "min-num" => 1], "hb_account_id" => ["required" => true, "numonly" => true, "min-num" => 1, "unique_hbid" => true], "poolid" => ["required" => true, "max" => 50, "unique_poolid" => true], "node" => ["required" => true, "max" => 50], "ipv4" => ["required" => true, "max" => 1000], "cpucores" => ["required" => true, "numonly" => true, "min-num" => 1], "cputype" => ["required" => true, "max" => 6], "ram" => ["required" => true, "numonly" => true, "min-num" => 32], "storage_size" => ["required" => true, "numonly" => true, "min-num" => 1]]);
                                                                                if ($validation->passed()) {
                                                                                    if (Input::get("node") != "default" && Input::get("cputype") != "default" && Input::get("userid") != "default") {
                                                                                        $users_results = $db->get("vncp_users", ["id", "=", parse_input(Input::get("userid"))]);
                                                                                        $users_results = $users_results->all();
                                                                                        if (count($users_results) == 1) {
                                                                                            $node_results = $db->get("vncp_nodes", ["name", "=", Input::get("node")]);
                                                                                            $node_data = $node_results->first();
                                                                                            $pxAPI = new PVE2_API($node_data->hostname, $node_data->username, $node_data->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($node_data->password));
                                                                                            $noLogin = false;
                                                                                            if (!$pxAPI->login()) {
                                                                                                $noLogin = true;
                                                                                            }
                                                                                            if (!$noLogin) {
                                                                                                $plaintext_password = _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(12);
                                                                                                $createpool = $pxAPI->post("/pools", ["poolid" => Input::get("poolid")]);
                                                                                                sleep(1);
                                                                                                $createuser = $pxAPI->post("/access/users", ["userid" => Input::get("poolid") . "@pve", "password" => $plaintext_password]);
                                                                                                sleep(1);
                                                                                                $setpoolperms = $pxAPI->put("/access/acl", ["path" => "/pool/" . Input::get("poolid"), "users" => Input::get("poolid") . "@pve", "roles" => "PVEVMUser"]);
                                                                                                sleep(1);
                                                                                                $db->insert("vncp_kvm_cloud", ["user_id" => Input::get("userid"), "nodes" => Input::get("node"), "hb_account_id" => Input::get("hb_account_id"), "pool_id" => Input::get("poolid"), "pool_password" => _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_($plaintext_password), "memory" => (int) Input::get("ram"), "cpu_cores" => (int) Input::get("cpucores"), "cpu_type" => Input::get("cputype"), "disk_size" => (int) Input::get("storage_size"), "ip_limit" => count(explode(";", Input::get("ipv4"))), "ipv4" => Input::get("ipv4"), "avail_memory" => (int) Input::get("ram"), "avail_cpu_cores" => (int) Input::get("cpucores"), "avail_disk_size" => (int) Input::get("storage_size"), "avail_ip_limit" => count(explode(";", Input::get("ipv4"))), "avail_ipv4" => Input::get("ipv4"), "suspended" => 0]);
                                                                                                $log->log("Created new cloud account " . Input::get("poolid"), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                                                $cloudCreatedSuccess = true;
                                                                                            }
                                                                                        } else {
                                                                                            $errors = "User ID does not exist.";
                                                                                        }
                                                                                    } else {
                                                                                        $errors = "User ID, Node, and CPU type cannot be default.";
                                                                                    }
                                                                                } else {
                                                                                    $errors = "";
                                                                                    foreach ($validation->errors() as $error) {
                                                                                        $errors .= $error . "<br />";
                                                                                    }
                                                                                }
                                                                            }
                                                                        } else {
                                                                            if (Input::exists() && Input::get("action") == "lxckvmprops") {
                                                                                if (Token::check(Input::get("token"))) {
                                                                                    $validate = new Validate();
                                                                                    $validation = $validate->check($_POST, ["hbaccountid" => ["required" => true, "numonly" => true, "min-num" => 1], "userid" => ["required" => true, "numonly" => true, "min-num" => 1], "vmnode" => ["required" => true, "max" => 50], "vmos" => ["required" => true, "max" => 250], "vmip" => ["required" => true, "ip" => true, "max" => 15], "vmip_gateway" => ["required" => true, "ip" => true, "max" => 15], "vmip_netmask" => ["required" => true, "ip" => true, "max" => 15], "vm_backups" => ["numonly" => true, "min-num" => 0, "max-num" => 1], "vm_backup_override" => ["numonly" => true, "min-num" => -1, "max-num" => 1000]]);
                                                                                    if ($validation->passed()) {
                                                                                        $verifyuid = $db->get("vncp_users", ["id", "=", parse_input(Input::get("userid"))])->all();
                                                                                        if (count($verifyuid) != 1) {
                                                                                            $errors = "User ID does not exist.";
                                                                                        } else {
                                                                                            $verifynode = $db->get("vncp_nodes", ["name", "=", parse_input(Input::get("vmnode"))])->all();
                                                                                            if (count($verifynode) != 1) {
                                                                                                $errors = "Node name does not exist.";
                                                                                            } else {
                                                                                                $verifyoldip = $db->get("vncp_dhcp", ["ip", "=", parse_input(Input::get("vmip_old"))])->all();
                                                                                                if (count($verifyoldip) != 1) {
                                                                                                    $errors = "VM IP does not exist.";
                                                                                                } else {
                                                                                                    $verifypoolpw = true;
                                                                                                    $dopoolpw = false;
                                                                                                    $vmpp = Input::get("vm_poolpw");
                                                                                                    if (isset($vmpp)) {
                                                                                                        if (32 < strlen(Input::get("vm_poolpw")) || strlen(Input::get("vm_poolpw")) < 12 || !ctype_alnum(Input::get("vm_poolpw"))) {
                                                                                                            $verifypoolpw = false;
                                                                                                        } else {
                                                                                                            $dopoolpw = true;
                                                                                                        }
                                                                                                        if (!Input::get("vm_poolpw")) {
                                                                                                            $verifypoolpw = true;
                                                                                                        }
                                                                                                    }
                                                                                                    if (!$verifypoolpw) {
                                                                                                        $errors = "Proxmox Pool Password must be between 12 and 32 characters long and be alphanumeric only.";
                                                                                                    } else {
                                                                                                        $lxccheck = $db->get("vncp_lxc_ct", ["hb_account_id", "=", parse_input(Input::get("hbaccountid"))])->all();
                                                                                                        if (count($lxccheck) != 1) {
                                                                                                            $db->updatevm_aid("vncp_kvm_ct", parse_input(Input::get("hbaccountid")), ["user_id" => parse_input(Input::get("userid")), "node" => parse_input(Input::get("vmnode")), "os" => parse_input(Input::get("vmos")), "ip" => parse_input(Input::get("vmip")), "allow_backups" => parse_input(Input::get("vm_backups"))]);
                                                                                                            $db->updatevm_aid("vncp_ct_backups", parse_input(Input::get("hbaccountid")), ["backuplimit" => parse_input(Input::get("vm_backup_override"))]);
                                                                                                            if (Input::get("vmip") != Input::get("vmip_old")) {
                                                                                                                $db->updatevm_aid("vncp_ipv4_pool", parse_input(Input::get("hbaccountid")), ["user_id" => 0, "hb_account_id" => 0, "available" => 1]);
                                                                                                                $db->update_address("vncp_ipv4_pool", parse_input(Input::get("vmip")), ["user_id" => parse_input(Input::get("userid")), "hb_account_id" => parse_input(Input::get("hbaccountid")), "available" => 0]);
                                                                                                            } else {
                                                                                                                $db->updatevm_aid("vncp_ipv4_pool", parse_input(Input::get("hbaccountid")), ["user_id" => parse_input(Input::get("userid"))]);
                                                                                                            }
                                                                                                            if ($dopoolpw) {
                                                                                                                $db->updatevm_aid("vncp_kvm_ct", parse_input(Input::get("hbaccountid")), ["pool_password" => _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_(parse_input(Input::get("vm_poolpw")))]);
                                                                                                            }
                                                                                                            $db->updatevm_aid("vncp_ipv6_assignment", parse_input(Input::get("hbaccountid")), ["user_id" => parse_input(Input::get("userid"))]);
                                                                                                            $db->updatevm_aid("vncp_natforwarding", parse_input(Input::get("hbaccountid")), ["user_id" => parse_input(Input::get("userid"))]);
                                                                                                            $db->updatevm_aid("vncp_private_pool", parse_input(Input::get("hbaccountid")), ["user_id" => parse_input(Input::get("userid"))]);
                                                                                                            $db->updatevm_aid("vncp_secondary_ips", parse_input(Input::get("hbaccountid")), ["user_id" => parse_input(Input::get("userid"))]);
                                                                                                            $db->update_dhcp("vncp_dhcp", Input::get("vmip_old"), ["gateway" => parse_input(Input::get("vmip_gateway")), "netmask" => parse_input(Input::get("vmip_netmask")), "ip" => parse_input(Input::get("vmip"))]);
                                                                                                            $savednetwork = $db->get("vncp_dhcp", ["ip", "=", Input::get("vmip")])->all();
                                                                                                            $fulldhcp = $db->get("vncp_dhcp", ["network", "=", $savednetwork[0]->network]);
                                                                                                            if ($dhcp_server = $db->get("vncp_dhcp_servers", ["dhcp_network", "=", $savednetwork[0]->network])->first()) {
                                                                                                                $ssh = new phpseclib\Net\SSH2($dhcp_server->hostname, (int) $dhcp_server->port);
                                                                                                                if (!$ssh->login("root", _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($dhcp_server->password))) {
                                                                                                                    $log->log("Could not SSH to DHCP server " . $dhcp_server->hostname, "error", 1, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                                                                } else {
                                                                                                                    $ssh->exec("printf 'ddns-update-style none;\n\n' > /root/dhcpd.test");
                                                                                                                    $ssh->exec("printf 'option domain-name-servers 8.8.8.8, 8.8.4.4;\n\n' >> /root/dhcpd.test");
                                                                                                                    $ssh->exec("printf 'default-lease-time 7200;\n' >> /root/dhcpd.test");
                                                                                                                    $ssh->exec("printf 'max-lease-time 86400;\n\n' >> /root/dhcpd.test");
                                                                                                                    $ssh->exec("printf 'log-facility local7;\n\n' >> /root/dhcpd.test");
                                                                                                                    $ssh->exec("printf 'subnet " . $savednetwork[0]->network . " netmask " . $fulldhcp[0]->netmask . " {}\n\n' >> /root/dhcpd.test");
                                                                                                                    for ($i = 0; $i < count($fulldhcp); $i++) {
                                                                                                                        $ssh->exec("printf 'host " . $fulldhcp[$i]->id . " {hardware ethernet " . $fulldhcp[$i]->mac_address . ";fixed-address " . $fulldhcp[$i]->ip . ";option routers " . $fulldhcp[$i]->gateway . ";}\n' >> /root/dhcpd.test");
                                                                                                                    }
                                                                                                                    $ssh->exec("mv /root/dhcpd.test /etc/dhcp/dhcpd.conf && rm /root/dhcpd.test");
                                                                                                                    $ssh->exec("service isc-dhcp-server restart");
                                                                                                                    $ssh->disconnect();
                                                                                                                }
                                                                                                            } else {
                                                                                                                $log->log("No DHCP server exists for " . $savednetwork[0]->network, "error", 1, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                                                            }
                                                                                                        } else {
                                                                                                            $db->updatevm_aid("vncp_lxc_ct", parse_input(Input::get("hbaccountid")), ["user_id" => parse_input(Input::get("userid")), "node" => parse_input(Input::get("vmnode")), "os" => parse_input(Input::get("vmos")), "ip" => parse_input(Input::get("vmip")), "allow_backups" => parse_input(Input::get("vm_backups"))]);
                                                                                                            $db->updatevm_aid("vncp_ct_backups", parse_input(Input::get("hbaccountid")), ["backuplimit" => parse_input(Input::get("vm_backup_override"))]);
                                                                                                            if (Input::get("vmip") != Input::get("vmip_old")) {
                                                                                                                $db->updatevm_aid("vncp_ipv4_pool", parse_input(Input::get("hbaccountid")), ["user_id" => 0, "hb_account_id" => 0, "available" => 1]);
                                                                                                                $db->update_address("vncp_ipv4_pool", parse_input(Input::get("vmip")), ["user_id" => parse_input(Input::get("userid")), "hb_account_id" => parse_input(Input::get("hbaccountid")), "available" => 0]);
                                                                                                            } else {
                                                                                                                $db->updatevm_aid("vncp_ipv4_pool", parse_input(Input::get("hbaccountid")), ["user_id" => parse_input(Input::get("userid"))]);
                                                                                                            }
                                                                                                            if ($dopoolpw) {
                                                                                                                $db->updatevm_aid("vncp_lxc_ct", parse_input(Input::get("hbaccountid")), ["pool_password" => _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_(parse_input(Input::get("vm_poolpw")))]);
                                                                                                            }
                                                                                                            $db->updatevm_aid("vncp_ipv6_assignment", parse_input(Input::get("hbaccountid")), ["user_id" => parse_input(Input::get("userid"))]);
                                                                                                            $db->updatevm_aid("vncp_natforwarding", parse_input(Input::get("hbaccountid")), ["user_id" => parse_input(Input::get("userid"))]);
                                                                                                            $db->updatevm_aid("vncp_private_pool", parse_input(Input::get("hbaccountid")), ["user_id" => parse_input(Input::get("userid"))]);
                                                                                                            $db->updatevm_aid("vncp_secondary_ips", parse_input(Input::get("hbaccountid")), ["user_id" => parse_input(Input::get("userid"))]);
                                                                                                            $db->update_dhcp("vncp_dhcp", parse_input(Input::get("vmip_old")), ["gateway" => parse_input(Input::get("vmip_gateway")), "netmask" => parse_input(Input::get("vmip_netmask")), "ip" => parse_input(Input::get("vmip"))]);
                                                                                                        }
                                                                                                        if ($dopoolpw) {
                                                                                                            $pxAPI = new PVE2_API($verifynode[0]->hostname, $verifynode[0]->username, $verifynode[0]->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($verifynode[0]->password));
                                                                                                            $noLogin = false;
                                                                                                            if (!$pxAPI->login()) {
                                                                                                                $noLogin = true;
                                                                                                            }
                                                                                                            if (!$noLogin) {
                                                                                                                $updatepw = $pxAPI->put("/access/password", ["userid" => parse_input(Input::get("vm_poolname")) . "@pve", "password" => parse_input(Input::get("vm_poolpw"))]);
                                                                                                                sleep(1);
                                                                                                                $log->log("Updated VPS pool password of account ID " . parse_input(Input::get("hbaccountid")), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                                                            }
                                                                                                        }
                                                                                                        $log->log("Updated VPS properties of account ID " . parse_input(Input::get("hbaccountid")), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                                                        $editedSuccess = true;
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        $errors = "";
                                                                                        foreach ($validation->errors() as $error) {
                                                                                            $errors .= $error . "<br />";
                                                                                        }
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                if (Input::exists() && Input::get("action") == "ip2") {
                                                                                    if (Token::check(Input::get("token"))) {
                                                                                        $validate = new Validate();
                                                                                        $validation = $validate->check($_POST, ["userid" => ["required" => true, "numonly" => true, "min-num" => 1], "hbaccountid" => ["required" => true, "numonly" => true, "min-num" => 1], "ipaddr" => ["required" => true, "ip" => true, "max" => 15]]);
                                                                                        if ($validation->passed()) {
                                                                                            if (Input::get("userid") != "default") {
                                                                                                $verifyuser = $db->get("vncp_users", ["id", "=", parse_input(Input::get("userid"))])->all();
                                                                                                if (count($verifyuser) != 1) {
                                                                                                    $errors = "User ID does not exist.";
                                                                                                } else {
                                                                                                    $ckvm = $db->get("vncp_kvm_ct", ["hb_account_id", "=", Input::get("hbaccountid")])->all();
                                                                                                    if (count($ckvm) != 1 || $ckvm[0]->user_id != Input::get("userid")) {
                                                                                                        $errors = "Billing Account ID does not exist for KVM or user IDs do not match.";
                                                                                                    } else {
                                                                                                        $db->insert("vncp_secondary_ips", ["user_id" => Input::get("userid"), "hb_account_id" => Input::get("hbaccountid"), "address" => Input::get("ipaddr")]);
                                                                                                        $log->log("Added secondary IP to account ID " . Input::get("hbaccountid"), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                                                        $ipAddedSuccess = true;
                                                                                                    }
                                                                                                }
                                                                                            } else {
                                                                                                $errors = "User ID cannot be default.";
                                                                                            }
                                                                                        } else {
                                                                                            $errors = "";
                                                                                            foreach ($validation->errors() as $error) {
                                                                                                $errors .= $error . "<br />";
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                } else {
                                                                                    if (Input::exists() && Input::get("action") == "private") {
                                                                                        if (Token::check(Input::get("token"))) {
                                                                                            $validate = new Validate();
                                                                                            $validation = $validate->check($_POST, ["cidr" => ["required" => true, "max" => 18]]);
                                                                                            if ($validation->passed()) {
                                                                                                $privnodes = $_POST["privnodes"];
                                                                                                if (count($privnodes) <= 0) {
                                                                                                    $errors = "Nodes field is required.";
                                                                                                } else {
                                                                                                    $exists = true;
                                                                                                    foreach ($privnodes as $privnode) {
                                                                                                        $exist = $db->get("vncp_nodes", ["name", "=", $privnode])->all();
                                                                                                        if (count($exist) != 1) {
                                                                                                            $exists = false;
                                                                                                        }
                                                                                                    }
                                                                                                    if (!$exists) {
                                                                                                        $errors = "One or more selected nodes do not exist.";
                                                                                                    } else {
                                                                                                        list($classC) = explode("/", Input::get("cidr"));
                                                                                                        if (24 <= (int) $classC && (int) $classC <= 30) {
                                                                                                            switch ($classC) {
                                                                                                                case 24:
                                                                                                                    $privatenm = "255.255.255.0";
                                                                                                                    break;
                                                                                                                case 25:
                                                                                                                    $privatenm = "255.255.255.128";
                                                                                                                    break;
                                                                                                                case 26:
                                                                                                                    $privatenm = "255.255.255.192";
                                                                                                                    break;
                                                                                                                case 27:
                                                                                                                    $privatenm = "255.255.255.224";
                                                                                                                    break;
                                                                                                                case 28:
                                                                                                                    $privatenm = "255.255.255.240";
                                                                                                                    break;
                                                                                                                case 29:
                                                                                                                    $privatenm = "255.255.255.248";
                                                                                                                    break;
                                                                                                                default:
                                                                                                                    $privatenm = "255.255.255.252";
                                                                                                                    $range = _obfuscated_0D03051C2E113337225B1C260D031733140528312D2B11_(Input::get("cidr"));
                                                                                                                    list($first) = explode(".", $range[0]);
                                                                                                                    list($last) = explode(".", $range[1]);
                                                                                                                    $prefix = explode(".", $range[0]);
                                                                                                                    $prefix = $prefix[0] . "." . $prefix[1] . "." . $prefix[2] . ".";
                                                                                                                    for ($i = $first + 2; $i < $last; $i++) {
                                                                                                                        $db->insert("vncp_private_pool", ["user_id" => 0, "hb_account_id" => 0, "address" => $prefix . (string) $i, "available" => 1, "netmask" => $privatenm, "nodes" => implode(";", $privnodes)]);
                                                                                                                    }
                                                                                                                    $log->log("Added new private pool CIDR " . Input::get("cidr"), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                                                                    $privAddedSuccess = true;
                                                                                                            }
                                                                                                        } else {
                                                                                                            $errors = "CIDR cannot be larger than a /24 or smaller than a /30.";
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            } else {
                                                                                                $errors = "";
                                                                                                foreach ($validation->errors() as $error) {
                                                                                                    $errors .= $error . "<br />";
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        if (Input::exists() && Input::get("action") == "ipv4" && Input::get("form_name") == "add_cidr") {
                                                                                            if (Token::check(Input::get("token"))) {
                                                                                                $validate = new Validate();
                                                                                                $validation = $validate->check($_POST, ["cidr" => ["required" => true, "max" => 18]]);
                                                                                                if ($validation->passed()) {
                                                                                                    $privnodes = $_POST["pubnodes"];
                                                                                                    if (count($privnodes) <= 0) {
                                                                                                        $errors = "Nodes field is required.";
                                                                                                    } else {
                                                                                                        $exists = true;
                                                                                                        foreach ($privnodes as $privnode) {
                                                                                                            $exist = $db->get("vncp_nodes", ["name", "=", $privnode])->all();
                                                                                                            if (count($exist) != 1) {
                                                                                                                $exists = false;
                                                                                                            }
                                                                                                        }
                                                                                                        if (!$exists) {
                                                                                                            $errors = "One or more selected nodes do not exist.";
                                                                                                        } else {
                                                                                                            list($classC) = explode("/", Input::get("cidr"));
                                                                                                            if (24 <= (int) $classC && (int) $classC <= 30) {
                                                                                                                switch ($classC) {
                                                                                                                    case 24:
                                                                                                                        $privatenm = "255.255.255.0";
                                                                                                                        break;
                                                                                                                    case 25:
                                                                                                                        $privatenm = "255.255.255.128";
                                                                                                                        break;
                                                                                                                    case 26:
                                                                                                                        $privatenm = "255.255.255.192";
                                                                                                                        break;
                                                                                                                    case 27:
                                                                                                                        $privatenm = "255.255.255.224";
                                                                                                                        break;
                                                                                                                    case 28:
                                                                                                                        $privatenm = "255.255.255.240";
                                                                                                                        break;
                                                                                                                    case 29:
                                                                                                                        $privatenm = "255.255.255.248";
                                                                                                                        break;
                                                                                                                    default:
                                                                                                                        $privatenm = "255.255.255.252";
                                                                                                                        $range = _obfuscated_0D03051C2E113337225B1C260D031733140528312D2B11_(Input::get("cidr"));
                                                                                                                        list($first) = explode(".", $range[0]);
                                                                                                                        list($last) = explode(".", $range[1]);
                                                                                                                        $prefix = explode(".", $range[0]);
                                                                                                                        $prefix = $prefix[0] . "." . $prefix[1] . "." . $prefix[2] . ".";
                                                                                                                        for ($i = $first + 2; $i < $last; $i++) {
                                                                                                                            $db->insert("vncp_ipv4_pool", ["user_id" => 0, "hb_account_id" => 0, "address" => $prefix . (string) $i, "available" => 1, "netmask" => $privatenm, "nodes" => implode(";", $privnodes), "gateway" => $prefix . ($first + 1)]);
                                                                                                                        }
                                                                                                                        $log->log("Added new IPv4 pool CIDR " . Input::get("cidr"), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                                                                        $pubAddedSuccess = true;
                                                                                                                }
                                                                                                            } else {
                                                                                                                $errors = "CIDR cannot be larger than a /24 or smaller than a /30.";
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                } else {
                                                                                                    $errors = "";
                                                                                                    foreach ($validation->errors() as $error) {
                                                                                                        $errors .= $error . "<br />";
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            if (Input::exists() && Input::get("action") == "ipv4" && Input::get("form_name") == "add_single") {
                                                                                                $validate = new Validate();
                                                                                                $validation = $validate->check($_POST, ["ipaddress" => ["required" => true, "max" => 15, "ip" => true], "ipgateway" => ["required" => true, "max" => 15, "ip" => true], "ipnetmask" => ["required" => true, "max" => 15, "ip" => true]]);
                                                                                                if ($validation->passed()) {
                                                                                                    $privnodes = $_POST["pubnodes"];
                                                                                                    if (count($privnodes) <= 0) {
                                                                                                        $errors = "Nodes field is required.";
                                                                                                    } else {
                                                                                                        $exists = true;
                                                                                                        foreach ($privnodes as $privnode) {
                                                                                                            $exist = $db->get("vncp_nodes", ["name", "=", $privnode])->all();
                                                                                                            if (count($exist) != 1) {
                                                                                                                $exists = false;
                                                                                                            }
                                                                                                        }
                                                                                                        if (!$exists) {
                                                                                                            $errors = "One or more selected nodes do not exist.";
                                                                                                        } else {
                                                                                                            $db->insert("vncp_ipv4_pool", ["user_id" => 0, "hb_account_id" => 0, "address" => parse_input(Input::get("ipaddress")), "available" => 1, "netmask" => parse_input(Input::get("ipnetmask")), "nodes" => implode(";", $privnodes), "gateway" => parse_input(Input::get("ipgateway"))]);
                                                                                                            $log->log("Added new IPv4 pool single " . Input::get("ipaddress"), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                                                            $pubAddedSuccess = true;
                                                                                                        }
                                                                                                    }
                                                                                                } else {
                                                                                                    $errors = "";
                                                                                                    foreach ($validation->errors() as $error) {
                                                                                                        $errors .= $error . "<br />";
                                                                                                    }
                                                                                                }
                                                                                            } else {
                                                                                                if (Input::exists() && Input::get("action") == "ipv6" && Token::check(Input::get("token"))) {
                                                                                                    $validate = new Validate();
                                                                                                    $validation = $validate->check($_POST, ["v6cidr" => ["required" => true, "max" => 45]]);
                                                                                                    if ($validation->passed()) {
                                                                                                        $v6nodes = $_POST["v6nodes"];
                                                                                                        if (count($v6nodes) <= 0) {
                                                                                                            $errors = "Nodes field is required.";
                                                                                                        } else {
                                                                                                            $exists = true;
                                                                                                            foreach ($v6nodes as $v6node) {
                                                                                                                $exist = $db->get("vncp_nodes", ["name", "=", $v6node])->all();
                                                                                                                if (count($exist) != 1) {
                                                                                                                    $exists = false;
                                                                                                                }
                                                                                                            }
                                                                                                            if (!$exists) {
                                                                                                                $errors = "One or more selected nodes do not exist.";
                                                                                                            } else {
                                                                                                                list($class) = explode("/", Input::get("v6cidr"));
                                                                                                                if (64 < (int) $class) {
                                                                                                                    $errors = "IPv6 subnet must be a /64 or larger.";
                                                                                                                } else {
                                                                                                                    $db->insert("vncp_ipv6_pool", ["subnet" => Input::get("v6cidr"), "nodes" => implode(";", $v6nodes)]);
                                                                                                                    $log->log("Added new IPv6 subnet " . Input::get("v6cidr"), "admin", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                                                                                                                    $ipv6AddedSuccess = true;
                                                                                                                }
                                                                                                            }
                                                                                                        }
                                                                                                    } else {
                                                                                                        $errors = "";
                                                                                                        foreach ($validation->errors() as $error) {
                                                                                                            $errors .= $error . "<br />";
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
echo "<!doctype html>\r\n<!--[if lt IE 7]><html class=\"no-js lt-ie9 lt-ie8 lt-ie7\" lang=\"\"><![endif]-->\r\n<!--[if IE 7]><html class=\"no-js lt-ie9 lt-ie8\" lang=\"\"><![endif]-->\r\n<!--[if IE 8]><html class=\"no-js lt-ie9\" lang=\"\"><![endif]-->\r\n<!--[if gt IE 8]><!--> <html class=\"no-js\" lang=\"\"> <!--<![endif]-->\r\n<head>\r\n    <meta charset=\"utf-8\" />\r\n    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\" />\r\n    <title>";
$appname = parse_input($db->get("vncp_settings", ["item", "=", "app_name"])->first()->value);
echo $appname;
echo " - Admin Dashboard</title>\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\" />\r\n    <link rel=\"stylesheet\" href=\"css/bootstrap.min.css\" />\r\n    <link rel=\"stylesheet\" href=\"css/font-awesome.min.css\" />\r\n    <link rel=\"stylesheet\" href=\"css/main.css\" />\r\n    <link rel=\"stylesheet\" href=\"//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css\" />\r\n    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css' />\r\n    <link href='css/bootstrap-select.min.css' rel='stylesheet' type='text/css' />\r\n    <link href='css/custom.css' rel='stylesheet' type='text/css' />\r\n    <link rel=\"icon\" type=\"image/png\" href=\"favicon.ico\" />\r\n    <script src=\"js/vendor/modernizr-2.8.3-respond-1.4.2.min.js\"></script>\r\n    <script type=\"text/javascript\" src=\"https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.slim.js\"></script>\r\n</head>\r\n<body>\r\n    <div id=\"socket_error\" class=\"socket_error\" style=\"visibility:hidden;padding:0px;\"></div>\r\n    <!--[if lt IE 8]>\r\n        <p class=\"browserupgrade\">You are using an <strong>outdated</strong> browser. Please <a href=\"http://browsehappy.com/\">upgrade your browser</a> to improve your experience.</p>\r\n    <![endif]-->\r\n    ";
$enable_firewall = parse_input($db->get("vncp_settings", ["item", "=", "enable_firewall"])->first()->value);
$enable_forward_dns = parse_input($db->get("vncp_settings", ["item", "=", "enable_forward_dns"])->first()->value);
$enable_reverse_dns = parse_input($db->get("vncp_settings", ["item", "=", "enable_reverse_dns"])->first()->value);
$enable_notepad = parse_input($db->get("vncp_settings", ["item", "=", "enable_notepad"])->first()->value);
$enable_status = parse_input($db->get("vncp_settings", ["item", "=", "enable_status"])->first()->value);
$isAdmin = $user->hasPermission("admin");
$L = new Language($user->data()->language);
$L = $L->load();
if (!$L) {
    $log->log("Could not load language " . $user->data()->language, "error", 2, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
    exit("Language \"" . $user->data()->language . "\" not found.");
}
echo $twig->render("menu_top.tpl", ["adminBase" => Config::get("admin/base"), "enable_firewall" => $enable_firewall, "enable_forward_dns" => $enable_forward_dns, "enable_reverse_dns" => $enable_reverse_dns, "enable_notepad" => $enable_notepad, "enable_status" => $enable_status, "isAdmin" => $isAdmin, "L" => $L]);
echo "    ";
$constants = false;
if (defined("constant") || defined("constant-fw")) {
    $constants = true;
}
$appname = $db->get("vncp_settings", ["item", "=", "app_name"])->first()->value;
$aclsetting = $db->get("vncp_settings", ["item", "=", "user_acl"])->first()->value;
echo $twig->render("menu_sub.tpl", ["constants" => $constants, "username" => $user->data()->username, "appname" => $appname, "aclsetting" => $aclsetting, "L" => $L]);
echo "    <div class=\"container\">\r\n    \t<div class=\"row\">\r\n            <div class=\"col-md-12\">\r\n                ";
if (!empty($errors)) {
    echo "<div class=\"alert alert-danger alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button><strong>Errors: </strong>" . $errors . "</div>";
}
$action = parse_input(Input::get("action"));
switch ($action) {
    case "users":
        require_once "includes/admin/users.php";
        break;
    case "admaccess":
        require_once "includes/admin/admaccess.php";
        break;
    case "nodes":
        require_once "includes/admin/nodes.php";
        break;
    case "edit_node":
        require_once "includes/admin/edit_node.php";
        break;
    case "lxc":
        require_once "includes/admin/lxc.php";
        break;
    case "cloud":
        require_once "includes/admin/cloud.php";
        break;
    case "settings":
        require_once "includes/admin/settings.php";
        break;
    case "acl":
        require_once "includes/admin/acl.php";
        break;
    case "lxctemp":
        require_once "includes/admin/lxctemp.php";
        break;
    case "kvm":
        require_once "includes/admin/kvm.php";
        break;
    case "kvmiso":
        require_once "includes/admin/kvmiso.php";
        break;
    case "kvmiso_custom":
        require_once "includes/admin/kvmiso_custom.php";
        break;
    case "kvmtemp":
        require_once "includes/admin/kvmtemp.php";
        break;
    case "fdns":
        require_once "includes/admin/fdns.php";
        break;
    case "rdns":
        require_once "includes/admin/rdns.php";
        break;
    case "ipv6":
        require_once "includes/admin/ipv6.php";
        break;
    case "private":
        require_once "includes/admin/private.php";
        break;
    case "ip2":
        require_once "includes/admin/ip2.php";
        break;
    case "lxckvmprops":
        require_once "includes/admin/lxckvmprops.php";
        break;
    case "log":
        require_once "includes/admin/log.php";
        break;
    case "tuntap":
        require_once "includes/admin/tuntap.php";
        break;
    case "natnodes":
        require_once "includes/admin/natnodes.php";
        break;
    case "dhcp":
        require_once "includes/admin/dhcp.php";
        break;
    case "bandwidth":
        require_once "includes/admin/bandwidth.php";
        break;
    case "api":
        require_once "includes/admin/api.php";
        break;
    case "ipv4":
        require_once "includes/admin/ipv4.php";
        break;
    default:
        require_once "includes/admin/dashboard.php";
        echo "            </div>\r\n    \t</div>\r\n    </div>\r\n    <input type=\"hidden\" value=\"";
        echo Session::get("user");
        echo "\" id=\"user\" />\r\n    <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js\"></script>\r\n    <script>window.jQuery || document.write('<script src=\"js/vendor/jquery-1.11.2.min.js\"><\\/script>')</script>\r\n    <script src=\"js/vendor/bootstrap.min.js\"></script>\r\n    <script src=\"//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js\"></script>\r\n    <script src=\"js/vendor/bootstrap-select.min.js\"></script>\r\n    <script src=\"js/vendor/jquery-confirm.min.js\"></script>\r\n    <script src=\"js/main.js\"></script>\r\n    <script src=\"js/buttons.js\"></script>\r\n    <script src=\"js/io.js\"></script>\r\n    <script src=\"js/admin.js\"></script>\r\n</body>\r\n</html>\r\n";
}

?>
