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
$cpanel_host = $db->get("vncp_settings", ["item", "=", "whm_url"])->first()->value;
$cphtemp = explode(":", $cpanel_host);
if (count($cphtemp) == 2) {
    $cpanel_host = $cpanel_host . ":2087";
}
$cpanel = new Cpanel($cpanel_host, $db->get("vncp_settings", ["item", "=", "whm_username"])->first()->value, $db->get("vncp_settings", ["item", "=", "whm_api_token"])->first()->value);
if (Input::exists()) {
    if (strpos(Input::get("formid"), "add_domain") !== false) {
        $validate = new Validate();
        $validation = $validate->check($_POST, ["ipaddress" => ["required" => true, "ip" => true], "domain" => ["required" => true, "min" => 6, "max" => 250, "unique_domain" => true]]);
        if ($validation->passed()) {
            $ip = Input::get("ipaddress");
            $domain = Input::get("domain");
            $root = explode(".", $domain);
            $domain_blacklist = $db->get("vncp_settings", ["item", "=", "forward_dns_blacklist"])->first()->value;
            $domain_blacklist = explode(";", $domain_blacklist);
            $domain_in_blacklist = false;
            $i = 0;
            while ($i < count($domain_blacklist)) {
                if (strpos($domain, $domain_blacklist[$i]) !== false) {
                    $domain_in_blacklist = true;
                } else {
                    $i++;
                }
            }
            if ($domain_in_blacklist) {
                $errors = "Invalid domain: not allowed.";
            } else {
                if (2 < count($root)) {
                    $errors = "Invalid domain: not root.";
                } else {
                    if (preg_match("/[^a-z\\.\\-0-9]/i", $domain)) {
                        $errors = "Invalid domain format.";
                    } else {
                        $add = $cpanel->adddns($domain, $ip);
                        if ($add) {
                            $dbadd = $db->insert("vncp_forward_dns_domain", ["client_id" => $user->data()->id, "domain" => $domain]);
                            $dbaddrec = $db->insert("vncp_forward_dns_record", ["client_id" => $user->data()->id, "domain" => $domain, "name" => $domain, "type" => "A", "address" => $ip]);
                            $dbaddrec = $db->insert("vncp_forward_dns_record", ["client_id" => $user->data()->id, "domain" => $domain, "name" => "mail", "type" => "CNAME", "cname" => $domain]);
                            $dbaddrec = $db->insert("vncp_forward_dns_record", ["client_id" => $user->data()->id, "domain" => $domain, "name" => "www", "type" => "CNAME", "cname" => $domain]);
                            $dbaddrec = $db->insert("vncp_forward_dns_record", ["client_id" => $user->data()->id, "domain" => $domain, "name" => "ftp", "type" => "CNAME", "cname" => $domain]);
                            $dbaddrec = $db->insert("vncp_forward_dns_record", ["client_id" => $user->data()->id, "domain" => $domain, "name" => $domain, "type" => "MX", "preference" => 0, "exchange" => $domain]);
                            $success = "Your domain has been added!";
                        } else {
                            $errors = "Unable to add DNS zone.";
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
    } else {
        if (strpos(Input::get("formid"), "del_dns") !== false) {
            $validate = new Validate();
            $validation = $validate->check($_POST, ["domain" => ["required" => true, "max" => 250, "min" => 6]]);
            if ($validation->passed()) {
                $domain = Input::get("domain");
                $name = Input::get("name");
                $type = Input::get("type");
                $address = Input::get("address");
                $cname = Input::get("cname");
                $pref = Input::get("pref");
                $exchange = Input::get("exchange");
                $priority = Input::get("priority");
                $weight = Input::get("weight");
                $port = Input::get("port");
                $target = Input::get("target");
                $txtdata = Input::get("txtdata");
                $dbid = explode(":", Input::get("dnsid"));
                if (preg_match("/[^a-z\\.\\-0-9]/i", $domain)) {
                    $errors = "Invalid domain.";
                } else {
                    $getnum = $cpanel->dumpzone($domain);
                    if ($type == "A") {
                        if (preg_match("/[^a-z\\.\\-0-9]/i", $name) || !filter_var($address, FILTER_VALIDATE_IP)) {
                            $errors = "Invalid name or IP address.";
                        } else {
                            for ($i = 0; $i < count($getnum->data->zone[0]->record); $i++) {
                                if ($name != $domain) {
                                    if ($getnum->data->zone[0]->record[$i]->type == "A" && $getnum->data->zone[0]->record[$i]->name == $name . "." . $domain . "." && $getnum->data->zone[0]->record[$i]->address == $address) {
                                        $deldns = $cpanel->removezonerecord($domain, (int) $getnum->data->zone[0]->record[$i]->Line);
                                        $deldb = $db->delete("vncp_forward_dns_record", ["id", "=", $dbid[0]]);
                                        $success = "DNS record removed.";
                                    }
                                } else {
                                    if ($getnum->data->zone[0]->record[$i]->type == "A" && $getnum->data->zone[0]->record[$i]->name == $name . "." && $getnum->data->zone[0]->record[$i]->address == $address) {
                                        $deldns = $cpanel->removezonerecord($domain, (int) $getnum->data->zone[0]->record[$i]->Line);
                                        $deldb = $db->delete("vncp_forward_dns_record", ["id", "=", $dbid[0]]);
                                        $success = "DNS record removed.";
                                    }
                                }
                            }
                        }
                    } else {
                        if ($type == "CNAME") {
                            if (preg_match("/[^a-z\\.\\-0-9]/i", $name) || preg_match("/[^a-z\\.\\-0-9]/i", $cname)) {
                                $errors = "Invalid name or cname.";
                            } else {
                                for ($i = 0; $i < count($getnum->data->zone[0]->record); $i++) {
                                    if ($getnum->data->zone[0]->record[$i]->type == "CNAME" && $getnum->data->zone[0]->record[$i]->cname == $cname && $getnum->data->zone[0]->record[$i]->name == $name . "." . $domain . ".") {
                                        $deldns = $cpanel->removezonerecord($domain, (int) $getnum->data->zone[0]->record[$i]->Line);
                                        $deldb = $db->delete("vncp_forward_dns_record", ["id", "=", $dbid[0]]);
                                        $success = "DNS record removed.";
                                    }
                                }
                            }
                        } else {
                            if ($type == "MX") {
                                if (preg_match("/[^a-z\\.\\-0-9]/i", $name) || preg_match("/[^a-z\\.\\-0-9]/i", $exchange)) {
                                    $errors = "Invalid name or exchange.";
                                } else {
                                    for ($i = 0; $i < count($getnum->data->zone[0]->record); $i++) {
                                        if ($getnum->data->zone[0]->record[$i]->type == "MX" && $getnum->data->zone[0]->record[$i]->name == $name . "." && $getnum->data->zone[0]->record[$i]->exchange == $exchange && (int) $getnum->data->zone[0]->record[$i]->preference == (int) $pref) {
                                            $deldns = $cpanel->removezonerecord($domain, (int) $getnum->data->zone[0]->record[$i]->Line);
                                            $deldb = $db->delete("vncp_forward_dns_record", ["id", "=", $dbid[0]]);
                                            $success = "DNS record removed.";
                                        }
                                    }
                                }
                            } else {
                                if ($type == "SRV") {
                                    if (preg_match("/[^a-z\\.\\-0-9]/i", $target)) {
                                        $errors = "Invalid target.";
                                    } else {
                                        for ($i = 0; $i < count($getnum->data->zone[0]->record); $i++) {
                                            if ($getnum->data->zone[0]->record[$i]->type == "SRV" && $getnum->data->zone[0]->record[$i]->target == $target && (int) $getnum->data->zone[0]->record[$i]->port == (int) $port && (int) $getnum->data->zone[0]->record[$i]->weight == (int) $weight && (int) $getnum->data->zone[0]->record[$i]->priority == (int) $priority) {
                                                $deldns = $cpanel->removezonerecord($domain, (int) $getnum->data->zone[0]->record[$i]->Line);
                                                $deldb = $db->delete("vncp_forward_dns_record", ["id", "=", $dbid[0]]);
                                                $success = "DNS record removed.";
                                            }
                                        }
                                    }
                                } else {
                                    if ($type == "TXT") {
                                        if (preg_match("/[^a-z\\.\\-0-9]/i", $name)) {
                                            $errors = "Invalid name.";
                                        } else {
                                            for ($i = 0; $i < count($getnum->data->zone[0]->record); $i++) {
                                                if ($getnum->data->zone[0]->record[$i]->type == "TXT" && $getnum->data->zone[0]->record[$i]->name == $name . "." && $getnum->data->zone[0]->record[$i]->txtdata == $txtdata) {
                                                    $deldns = $cpanel->removezonerecord($domain, (int) $getnum->data->zone[0]->record[$i]->Line);
                                                    $deldb = $db->delete("vncp_forward_dns_record", ["id", "=", $dbid[0]]);
                                                    $success = "DNS record removed.";
                                                }
                                            }
                                        }
                                    } else {
                                        $errors = "Invalid record type.";
                                    }
                                }
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
        } else {
            if (strpos(Input::get("formid"), "del_zone") !== false) {
                $validate = new Validate();
                $validation = $validate->check($_POST, ["domain" => ["required" => true, "max" => 250, "min" => 6]]);
                if ($validation->passed()) {
                    $domain = Input::get("domain");
                    if (preg_match("/[^a-z\\.\\-0-9]/i", $domain)) {
                        $errors = "Invalid domain.";
                    } else {
                        $delzone = $cpanel->killdns($domain);
                        $deldb = $db->delete("vncp_forward_dns_domain", ["domain", "=", $domain]);
                        $deldb2 = $db->delete("vncp_forward_dns_record", ["domain", "=", $domain]);
                        $success = "Domain successfully removed.";
                    }
                } else {
                    $errors = "";
                    foreach ($validation->errors() as $error) {
                        $errors .= $error . "<br />";
                    }
                }
            } else {
                if (strpos(Input::get("formid"), "add_dns") !== false) {
                    $validate = new Validate();
                    if (Input::get("add_type") == "A") {
                        $validation = $validate->check($_POST, ["a_name" => ["required" => true, "min" => 1, "max" => 250], "a_address" => ["required" => true, "min" => 7, "max" => 16, "ip" => true]]);
                        if ($validation->passed()) {
                            $domain = Input::get("add_zone");
                            if (preg_match("/[^a-z\\.\\-0-9]/i", $domain)) {
                                $errors = "Invalid domain.";
                            } else {
                                $result = $db->get("vncp_forward_dns_domain", ["domain", "=", $domain]);
                                $data = $result->first();
                                if ($data->client_id != $user->data()->id) {
                                    $errors = "Invalid domain request.";
                                } else {
                                    if (strpos(Input::get("a_name"), $domain) !== false) {
                                        $add_a_record = $cpanel->addzonerecord_A($domain, Input::get("a_name") . ".", "IN", 14400, "A", Input::get("a_address"));
                                        $result = $db->insert("vncp_forward_dns_record", ["client_id" => $user->data()->id, "domain" => $domain, "name" => Input::get("a_name"), "type" => "A", "address" => Input::get("a_address")]);
                                        $success = "A record added successfully!";
                                    } else {
                                        $errors = "Invalid A name. Name value must contain root domain such as " . Input::get("a_name") . "." . $domain . " .";
                                    }
                                }
                            }
                        } else {
                            $errors = "";
                            foreach ($validation->errors() as $error) {
                                $errors .= $error . "<br />";
                            }
                        }
                    } else {
                        if (Input::get("add_type") == "CNAME") {
                            $validation = $validate->check($_POST, ["cname_name" => ["required" => true, "min" => 1, "max" => 250], "cname_cname" => ["required" => true, "min" => 1, "max" => 250]]);
                            if ($validation->passed()) {
                                $domain = Input::get("add_zone");
                                if (preg_match("/[^a-z\\.\\-0-9]/i", $domain)) {
                                    $errors = "Invalid domain.";
                                } else {
                                    $result = $db->get("vncp_forward_dns_domain", ["domain", "=", $domain]);
                                    $data = $result->first();
                                    if ($data->client_id != $user->data()->id) {
                                        $errors = "Invalid domain request.";
                                    } else {
                                        $add_cname_record = $cpanel->addzonerecord_CNAME($domain, Input::get("cname_name"), "IN", 14400, "CNAME", Input::get("cname_cname"), true);
                                        $result = $db->insert("vncp_forward_dns_record", ["client_id" => $user->data()->id, "domain" => $domain, "name" => Input::get("cname_name"), "type" => "CNAME", "cname" => Input::get("cname_cname")]);
                                        $success = "CNAME record added successfully!";
                                    }
                                }
                            } else {
                                $errors = "";
                                foreach ($validation->errors() as $error) {
                                    $errors .= $error . "<br />";
                                }
                            }
                        } else {
                            if (Input::get("add_type") == "SRV") {
                                $validation = $validate->check($_POST, ["srv_service" => ["required" => true, "min" => 1, "max" => 100], "srv_protocol" => ["required" => true, "min" => 4, "max" => 4], "srv_name" => ["required" => true, "min" => 1, "max" => 250], "srv_priority" => ["required" => true, "min-num" => 1, "max-num" => 65535, "numonly" => true], "srv_weight" => ["required" => true, "min-num" => 1, "max-num" => 65535, "numonly" => true], "srv_port" => ["required" => true, "min-num" => 1, "max-num" => 65535, "numonly" => true], "srv_target" => ["required" => true, "min" => 5, "max" => 250]]);
                                if ($validation->passed()) {
                                    $domain = Input::get("add_zone");
                                    if (preg_match("/[^a-z\\.\\-0-9]/i", $domain)) {
                                        $errors = "Invalid domain.";
                                    } else {
                                        $result = $db->get("vncp_forward_dns_domain", ["domain", "=", $domain]);
                                        $data = $result->first();
                                        if ($data->client_id != $user->data()->id) {
                                            $errors = "Invalid domain request.";
                                        } else {
                                            $add_srv_record = $cpanel->addzonerecord_SRV($domain, Input::get("srv_service") . "." . Input::get("srv_protocol") . "." . Input::get("srv_name"), "IN", 14400, "SRV", Input::get("srv_priority"), Input::get("srv_weight"), Input::get("srv_port"), Input::get("srv_target"));
                                            $result = $db->insert("vncp_forward_dns_record", ["client_id" => $user->data()->id, "domain" => $domain, "name" => Input::get("srv_service") . "." . Input::get("srv_protocol") . "." . Input::get("srv_name"), "type" => "SRV", "priority" => Input::get("srv_priority"), "weight" => Input::get("srv_weight"), "port" => Input::get("srv_port"), "target" => Input::get("srv_target")]);
                                            $success = "SRV record added successfully!";
                                        }
                                    }
                                } else {
                                    $errors = "";
                                    foreach ($validation->errors() as $error) {
                                        $errors .= $error . "<br />";
                                    }
                                }
                            } else {
                                if (Input::get("add_type") == "MX") {
                                    $validation = $validate->check($_POST, ["mx_name" => ["required" => true, "min" => 1, "max" => 250], "mx_exchange" => ["required" => true, "min" => 1, "max" => 250], "mx_preference" => ["required" => true, "numonly" => true, "min-num" => 0, "max-num" => 65535]]);
                                    if ($validation->passed()) {
                                        $domain = Input::get("add_zone");
                                        if (preg_match("/[^a-z\\.\\-0-9]/i", $domain)) {
                                            $errors = "Invalid domain.";
                                        } else {
                                            $result = $db->get("vncp_forward_dns_domain", ["domain", "=", $domain]);
                                            $data = $result->first();
                                            if ($data->client_id != $user->data()->id) {
                                                $errors = "Invalid domain request.";
                                            } else {
                                                $add_mx_record = $cpanel->addzonerecord_MX($domain, Input::get("mx_name") . ".", "IN", 14400, "MX", Input::get("mx_preference"), Input::get("mx_exchange"));
                                                $result = $db->insert("vncp_forward_dns_record", ["client_id" => $user->data()->id, "domain" => $domain, "name" => Input::get("mx_name"), "type" => "MX", "preference" => Input::get("mx_preference"), "exchange" => Input::get("mx_exchange")]);
                                                $success = "MX record added successfully!";
                                            }
                                        }
                                    } else {
                                        $errors = "";
                                        foreach ($validation->errors() as $error) {
                                            $errors .= $error . "<br />";
                                        }
                                    }
                                } else {
                                    if (Input::get("add_type") == "TXT") {
                                        $validation = $validate->check($_POST, ["txt_name" => ["required" => true, "min" => 1, "max" => 250], "txt_value" => ["required" => true, "min" => 1, "max" => 250]]);
                                        if ($validation->passed()) {
                                            $domain = Input::get("add_zone");
                                            if (preg_match("/[^a-z\\.\\-0-9]/i", $domain)) {
                                                $errors = "Invalid domain.";
                                            } else {
                                                $result = $db->get("vncp_forward_dns_domain", ["domain", "=", $domain]);
                                                $data = $result->first();
                                                if ($data->client_id != $user->data()->id) {
                                                    $errors = "Invalid domain request.";
                                                } else {
                                                    $add_txt_record = $cpanel->addzonerecord_TXT($domain, Input::get("txt_name") . ".", "IN", 14400, "TXT", "\"" . Input::get("txt_value") . "\"", false);
                                                    $result = $db->insert("vncp_forward_dns_record", ["client_id" => $user->data()->id, "domain" => $domain, "name" => Input::get("txt_name"), "type" => "TXT", "txtdata" => "\"" . Input::get("txt_value") . "\""]);
                                                    $success = "TXT record added successfully!";
                                                }
                                            }
                                        } else {
                                            $errors = "";
                                            foreach ($validation->errors() as $error) {
                                                $errors .= $error . "<br />";
                                            }
                                        }
                                    } else {
                                        $errors = "Invalid record type.";
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
$appname = $db->get("vncp_settings", ["item", "=", "app_name"])->first()->value;
$fdnssetting = $db->get("vncp_settings", ["item", "=", "enable_forward_dns"])->first()->value;
$result = $db->get("vncp_forward_dns_record", ["client_id", "=", $user->data()->id]);
$data = $result->all();
$forward_dns_records = [];
for ($i = 0; $i < count($data); $i++) {
    $forward_dns_records[$i] = ["domain" => $data[$i]->domain, "name" => $data[$i]->name, "type" => $data[$i]->type];
    if (!is_null($data[$i]->address) && !empty($data[$i]->address)) {
        $forward_dns_records[$i]["address"] = $data[$i]->address;
    } else {
        $forward_dns_records[$i]["address"] = "";
    }
    if (!is_null($data[$i]->cname) && !empty($data[$i]->cname)) {
        $forward_dns_records[$i]["cname"] = $data[$i]->cname;
    } else {
        $forward_dns_records[$i]["cname"] = "";
    }
    if (!is_null($data[$i]->preference) && !empty($data[$i]->preference)) {
        $forward_dns_records[$i]["preference"] = $data[$i]->preference;
    } else {
        $forward_dns_records[$i]["preference"] = "";
    }
    if (!is_null($data[$i]->exchange) && !empty($data[$i]->exchange)) {
        $forward_dns_records[$i]["exchange"] = $data[$i]->exchange;
    } else {
        $forward_dns_records[$i]["exchange"] = "";
    }
    if (!is_null($data[$i]->priority) && !empty($data[$i]->priority)) {
        $forward_dns_records[$i]["priority"] = $data[$i]->priority;
    } else {
        $forward_dns_records[$i]["priority"] = "";
    }
    if (!is_null($data[$i]->weight) && !empty($data[$i]->weight)) {
        $forward_dns_records[$i]["weight"] = $data[$i]->weight;
    } else {
        $forward_dns_records[$i]["weight"] = "";
    }
    if (!is_null($data[$i]->port) && !empty($data[$i]->port)) {
        $forward_dns_records[$i]["port"] = $data[$i]->port;
    } else {
        $forward_dns_records[$i]["port"] = "";
    }
    if (!is_null($data[$i]->target) && !empty($data[$i]->target)) {
        $forward_dns_records[$i]["target"] = $data[$i]->target;
    } else {
        $forward_dns_records[$i]["target"] = "";
    }
    if (!is_null($data[$i]->txtdata) && !empty($data[$i]->txtdata)) {
        $forward_dns_records[$i]["txtdata"] = $data[$i]->txtdata;
    } else {
        $forward_dns_records[$i]["txtdata"] = "";
    }
    $forward_dns_records[$i]["formID"] = "del_dns_" . _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(10);
    $new_txtdata = str_replace("\"", "", $data[$i]->txtdata);
    $forward_dns_records[$i]["newdata"] = $new_txtdata;
    $forward_dns_records[$i]["dnsID"] = "" . $data[$i]->id . ":" . _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(10);
}
$result = $db->get("vncp_forward_dns_domain", ["client_id", "=", $user->data()->id]);
$data = $result->all();
$domaincount = count($data);
$forward_dns_domains = [];
for ($i = 0; $i < count($data); $i++) {
    $forward_dns_domains[] = $data[$i]->domain;
}
$nameservers = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($db->get("vncp_settings", ["item", "=", "forward_dns_nameservers"])->first()->value);
$nameservers = explode(";", $nameservers);
$domainlimit = (int) _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($db->get("vncp_settings", ["item", "=", "forward_dns_domain_limit"])->first()->value);
$enable_firewall = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($db->get("vncp_settings", ["item", "=", "enable_firewall"])->first()->value);
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
echo $twig->render("forward_dns.tpl", ["appname" => $appname, "fdnssetting" => $fdnssetting, "errors" => $errors, "success" => $success, "forward_dns_records" => $forward_dns_records, "forward_dns_domains" => $forward_dns_domains, "formID" => "add_dns_" . _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(10), "nameservers" => $nameservers, "domainlimit" => $domainlimit, "domaincount" => $domaincount, "formID2" => "add_domain_" . _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(10), "formID3" => "del_zone_" . _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(10), "adminBase" => Config::get("admin/base"), "enable_firewall" => $enable_firewall, "enable_forward_dns" => $fdnssetting, "enable_reverse_dns" => $enable_reverse_dns, "enable_notepad" => $enable_notepad, "enable_status" => $enable_status, "isAdmin" => $isAdmin, "constants" => $constants, "username" => $user->data()->username, "aclsetting" => $aclsetting, "pagename" => "Manage Forward DNS", "L" => $L]);
echo "<input type=\"hidden\" value=\"" . Session::get("user") . "\" id=\"user\" />\r\n<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js\"></script>\r\n<script>window.jQuery || document.write('<script src=\"js/vendor/jquery-1.11.2.min.js\"><\\/script>')</script>\r\n<script src=\"js/vendor/bootstrap.min.js\"></script>\r\n<script src=\"js/main.js\"></script>\r\n<script src=\"js/buttons.js\"></script>\r\n<script src=\"js/io.js\"></script>\r\n<script src=\"js/forward_dns.js\"></script>\r\n</body>\r\n</html>";

?>
