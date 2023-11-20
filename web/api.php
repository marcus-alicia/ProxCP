<?php

require_once "vendor/autoload.php";
require_once "core/autoload.php";
require_once "core/init.php";
$ua = $_SERVER["HTTP_USER_AGENT"];
$from = $_SERVER["REMOTE_ADDR"];
$response = ["success" => 0, "message" => "Invalid pre-check data", "data" => []];
if (($ua == "ProxCP WHMCS Module" || $ua == "ProxCP Blesta Module") && Input::exists() && Config::get("instance/installed")) {
    $db = DB::getInstance();
    $apiid = Input::get("api_id");
    $apikey = Input::get("api_key");
    $idkey = $db->get("vncp_api", ["api_id", "=", $apiid])->first();
    if (count($idkey) == 1 && $idkey->api_key == $apikey && $idkey->api_ip == $from) {
        $a = Input::get("action");
        if ($a == "getnodes") {
            $response = _obfuscated_0D3E0832243F0E07190428111118044012123C2A0F0501_($db);
        } else {
            if ($a == "createkvm") {
                $userid = Input::get("userid");
                $email = base64_decode(Input::get("email"));
                $pw = base64_decode(Input::get("pw"));
                $node = Input::get("node");
                $osfriendly = Input::get("osfriendly");
                $ostype = Input::get("ostype");
                $hbid = Input::get("hbid");
                $poolid = Input::get("poolid");
                $hostname = Input::get("hostname");
                $storage = Input::get("storage");
                $cpu = Input::get("cpu");
                $ram = Input::get("ram");
                $nicdriver = Input::get("nicdriver");
                $cputype = Input::get("cputype");
                $strdriver = Input::get("strdriver");
                $osinstalltype = Input::get("osinstalltype");
                $ostemp = Input::get("ostemp");
                $bwlimit = Input::get("bwlimit");
                $nat = Input::get("nat");
                $natp = Input::get("natp");
                $natd = Input::get("natd");
                $vlantag = Input::get("vlantag");
                $portspeed = Input::get("portspeed");
                $backuplimit = Input::get("backuplimit");
                if ((int) $vlantag < 0) {
                    $vlantag = 0;
                }
                if (4094 < (int) $vlantag) {
                    $vlantag = 4094;
                }
                if ((int) $portspeed < 0) {
                    $portspeed = 0;
                }
                if (10000 < (int) $portspeed) {
                    $portspeed = 10000;
                }
                if ((int) $backuplimit < -1) {
                    $backuplimit = -1;
                }
                if (1000 < (int) $backuplimit) {
                    $backuplimit = 1000;
                }
                $response = _obfuscated_0D1708362B355C0737362909122B0402120A295B150D01_($db, $userid, $node, $osfriendly, $ostype, $hbid, $poolid, $hostname, $storage, $cpu, $ram, $nicdriver, $cputype, $strdriver, $osinstalltype, $ostemp, $bwlimit, $email, $pw, $nat, $natp, $natd, $vlantag, $portspeed, $backuplimit);
            } else {
                if ($a == "createcloud") {
                    $userid = Input::get("userid");
                    $email = base64_decode(Input::get("email"));
                    $pw = base64_decode(Input::get("pw"));
                    $node = Input::get("node");
                    $hbid = Input::get("hbid");
                    $poolid = Input::get("poolid");
                    $storage = Input::get("storage");
                    $cpu = Input::get("cpu");
                    $ram = Input::get("ram");
                    $cputype = Input::get("cputype");
                    $howmanyips = Input::get("howmanyips");
                    $response = _obfuscated_0D16193635272229032E16103B07021D09180434121332_($db, $userid, $email, $pw, $node, $hbid, $poolid, $storage, $cpu, $ram, $cputype, $howmanyips);
                } else {
                    if ($a == "createlxc") {
                        $userid = Input::get("userid");
                        $email = base64_decode(Input::get("email"));
                        $pw = base64_decode(Input::get("pw"));
                        $node = Input::get("node");
                        $osfriendly = Input::get("osfriendly");
                        $ostype = Input::get("ostype");
                        $hbid = Input::get("hbid");
                        $poolid = Input::get("poolid");
                        $hostname = Input::get("hostname");
                        $storage = Input::get("storage");
                        $cpu = Input::get("cpu");
                        $ram = Input::get("ram");
                        $bwlimit = Input::get("bwlimit");
                        $nat = Input::get("nat");
                        $natp = Input::get("natp");
                        $natd = Input::get("natd");
                        $vlantag = Input::get("vlantag");
                        $portspeed = Input::get("portspeed");
                        $backuplimit = Input::get("backuplimit");
                        if ((int) $vlantag < 0) {
                            $vlantag = 0;
                        }
                        if (4094 < (int) $vlantag) {
                            $vlantag = 4094;
                        }
                        if ((int) $portspeed < 0) {
                            $portspeed = 0;
                        }
                        if (10000 < (int) $portspeed) {
                            $portspeed = 10000;
                        }
                        if ((int) $backuplimit < -1) {
                            $backuplimit = -1;
                        }
                        if (1000 < (int) $backuplimit) {
                            $backuplimit = 1000;
                        }
                        $response = _obfuscated_0D2C09331622252F0E3B030D011C2D14403133173D2D01_($db, $userid, $email, $pw, $node, $osfriendly, $ostype, $hbid, $poolid, $hostname, $storage, $cpu, $ram, $bwlimit, $nat, $natp, $natd, $vlantag, $portspeed, $backuplimit);
                    } else {
                        if ($a == "suspend") {
                            $type = Input::get("type");
                            $hbid = Input::get("hbid");
                            if ($type == "kvm") {
                                $record = $db->get("vncp_kvm_ct", ["hb_account_id", "=", $hbid])->first();
                                $node = $record->node;
                            } else {
                                if ($type == "pc") {
                                    $record = $db->get("vncp_kvm_cloud", ["hb_account_id", "=", $hbid])->first();
                                    $node = $record->nodes;
                                } else {
                                    $record = $db->get("vncp_lxc_ct", ["hb_account_id", "=", $hbid])->first();
                                    $node = $record->node;
                                }
                            }
                            $poolid = Input::get("poolid");
                            $response = _obfuscated_0D3D3638122B1E2C1F110B3C24242B2D3C0A33380C0532_($db, $type, $hbid, $node, $poolid);
                        } else {
                            if ($a == "unsuspend") {
                                $type = Input::get("type");
                                $hbid = Input::get("hbid");
                                if ($type == "kvm") {
                                    $record = $db->get("vncp_kvm_ct", ["hb_account_id", "=", $hbid])->first();
                                    $node = $record->node;
                                } else {
                                    if ($type == "pc") {
                                        $record = $db->get("vncp_kvm_cloud", ["hb_account_id", "=", $hbid])->first();
                                        $node = $record->nodes;
                                    } else {
                                        $record = $db->get("vncp_lxc_ct", ["hb_account_id", "=", $hbid])->first();
                                        $node = $record->node;
                                    }
                                }
                                $poolid = Input::get("poolid");
                                $response = _obfuscated_0D2F112A370625313D331E3633013E3B0F083619180301_($db, $type, $hbid, $node, $poolid);
                            } else {
                                if ($a == "terminate") {
                                    $type = Input::get("type");
                                    $hbid = Input::get("hbid");
                                    if ($type == "kvm") {
                                        $record = $db->get("vncp_kvm_ct", ["hb_account_id", "=", $hbid])->first();
                                        $node = $record->node;
                                    } else {
                                        if ($type == "pc") {
                                            $record = $db->get("vncp_kvm_cloud", ["hb_account_id", "=", $hbid])->first();
                                            $node = $record->nodes;
                                        } else {
                                            $record = $db->get("vncp_lxc_ct", ["hb_account_id", "=", $hbid])->first();
                                            $node = $record->node;
                                        }
                                    }
                                    $poolid = Input::get("poolid");
                                    $userid = Input::get("userid");
                                    $response = _obfuscated_0D15013523080C1B0E3C1A120E323C1B2832280C293011_($db, $type, $hbid, $node, $poolid, $userid);
                                } else {
                                    if ($a == "getdetails") {
                                        $type = Input::get("type");
                                        $hbid = Input::get("hbid");
                                        $response = _obfuscated_0D062A1C5C150A190B1740251B402C2D5B3729212C1622_($db, $type, $hbid);
                                    } else {
                                        if ($a == "startserver") {
                                            $type = Input::get("type");
                                            $hbid = Input::get("hbid");
                                            $userid = Input::get("userid");
                                            $response = _obfuscated_0D121203301431362529383E111D1933303B262D081A22_($db, $type, $hbid, $userid);
                                        } else {
                                            if ($a == "stopserver") {
                                                $type = Input::get("type");
                                                $hbid = Input::get("hbid");
                                                $userid = Input::get("userid");
                                                $response = _obfuscated_0D0218303C101A1C1A39160D3C1F29341C105C0A1F0E32_($db, $type, $hbid, $userid);
                                            } else {
                                                $response["message"] = "Invalid action data";
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
    } else {
        $response["message"] = "Invalid check data";
    }
}
header("Content-Type: application/json");
echo json_encode($response);
function _obfuscated_0D11211B35050A09315B252E3F181023151E1B2C262711_($db, $node, $userid, $hbid, $nat)
{
    $_obfuscated_0D173102373C30022818121B2E2E3F382D300D03171801_ = $db->get("vncp_ipv4_pool", ["available", "=", 1])->all();
    if ($nat != "on") {
        foreach ($_obfuscated_0D173102373C30022818121B2E2E3F382D300D03171801_ as $_obfuscated_0D17362A1A09401B2C0E1833212B383431040A313D1132_) {
            if ($_obfuscated_0D17362A1A09401B2C0E1833212B383431040A313D1132_->user_id == 0 && $_obfuscated_0D17362A1A09401B2C0E1833212B383431040A313D1132_->hb_account_id == 0 && strpos($_obfuscated_0D17362A1A09401B2C0E1833212B383431040A313D1132_->nodes, $node) !== false) {
                $db->update("vncp_ipv4_pool", $_obfuscated_0D17362A1A09401B2C0E1833212B383431040A313D1132_->id, ["user_id" => $userid, "hb_account_id" => $hbid, "available" => 0]);
                return [$_obfuscated_0D17362A1A09401B2C0E1833212B383431040A313D1132_->address, $_obfuscated_0D17362A1A09401B2C0E1833212B383431040A313D1132_->netmask, $_obfuscated_0D17362A1A09401B2C0E1833212B383431040A313D1132_->gateway];
            }
        }
    } else {
        $_obfuscated_0D25351F2337040E1D34160F2F090E091B31241A341811_ = $db->get("vncp_nat", ["node", "=", $node])->all();
        foreach ($_obfuscated_0D173102373C30022818121B2E2E3F382D300D03171801_ as $_obfuscated_0D17362A1A09401B2C0E1833212B383431040A313D1132_) {
            if (_obfuscated_0D1D160B191F262E1C10382E1A2808231A172828272201_($_obfuscated_0D17362A1A09401B2C0E1833212B383431040A313D1132_->address, $_obfuscated_0D25351F2337040E1D34160F2F090E091B31241A341811_[0]->natcidr) && $_obfuscated_0D17362A1A09401B2C0E1833212B383431040A313D1132_->user_id == 0 && $_obfuscated_0D17362A1A09401B2C0E1833212B383431040A313D1132_->hb_account_id == 0 && strpos($_obfuscated_0D17362A1A09401B2C0E1833212B383431040A313D1132_->nodes, $node) !== false) {
                $db->update("vncp_ipv4_pool", $_obfuscated_0D17362A1A09401B2C0E1833212B383431040A313D1132_->id, ["user_id" => $userid, "hb_account_id" => $hbid, "available" => 0]);
                return [$_obfuscated_0D17362A1A09401B2C0E1833212B383431040A313D1132_->address, $_obfuscated_0D17362A1A09401B2C0E1833212B383431040A313D1132_->netmask, $_obfuscated_0D17362A1A09401B2C0E1833212B383431040A313D1132_->gateway];
            }
        }
    }
    return ["127.0.0.2", "255.0.0.0", "127.0.0.1"];
}
function _obfuscated_0D220D0B331B2B11083B012F27083D101638080A292F11_($db, $osfriendly)
{
    $_obfuscated_0D2F33111D1D2C2E092E0D091E323D1F27182E01181411_ = $db->get("vncp_kvm_isos", ["id", "!=", 0])->all();
    foreach ($_obfuscated_0D2F33111D1D2C2E092E0D091E323D1F27182E01181411_ as $_obfuscated_0D0A0E28333F3104083F182313231A2A2E3C2D163C1311_) {
        if ($_obfuscated_0D0A0E28333F3104083F182313231A2A2E3C2D163C1311_->friendly_name == $osfriendly) {
            return $_obfuscated_0D0A0E28333F3104083F182313231A2A2E3C2D163C1311_->volid;
        }
    }
    return false;
}
function _obfuscated_0D15250B03270A2D5B2337313626041505272A182B3B22_($node, $pxAPI)
{
    $_obfuscated_0D1437394006252A1736153B2713373539160C282D3B22_ = $pxAPI->get("/nodes/" . $node . "/storage");
    foreach ($_obfuscated_0D1437394006252A1736153B2713373539160C282D3B22_ as $storage) {
        if ((int) $storage["active"] == 1 && (double) $storage["used_fraction"] < 0 && strpos($storage["content"], "images") !== false) {
            return $storage["storage"];
        }
    }
    return "local";
}
function _obfuscated_0D121203301431362529383E111D1933303B262D081A22_($db, $type, $hbid, $userid)
{
    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_ = ["success" => 0, "message" => "startserver failed", "data" => []];
    if ($type == "kvm") {
        $_obfuscated_0D21291403291732023C291633083206180C3204283432_ = $db->get("vncp_kvm_ct", ["hb_account_id", "=", $hbid])->first();
        if ($_obfuscated_0D21291403291732023C291633083206180C3204283432_->user_id == $userid) {
            $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_ = $db->get("vncp_nodes", ["name", "=", $_obfuscated_0D21291403291732023C291633083206180C3204283432_->node])->first();
            $pxAPI = new PVE2_API($_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->hostname, $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->username, $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->password));
            $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = false;
            if (!$pxAPI->login()) {
                $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = true;
            }
            if (!$_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_) {
                $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_ = $pxAPI->get("/pools/" . $_obfuscated_0D21291403291732023C291633083206180C3204283432_->pool_id);
                $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ = $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_["members"][0]["vmid"];
                $_obfuscated_0D03272A380E1F2C2C1A182D32040B3B39381E270F2211_ = $pxAPI->post("/nodes/" . $_obfuscated_0D21291403291732023C291633083206180C3204283432_->node . "/qemu/" . $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ . "/status/start", []);
                sleep(5);
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] = 1;
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "success";
            }
        } else {
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Invalid user ID";
        }
    } else {
        if ($type == "lxc") {
            $_obfuscated_0D35303E3F342612110A392D0304182F223B2D2F2F0332_ = $db->get("vncp_lxc_ct", ["hb_account_id", "=", $hbid])->first();
            if ($_obfuscated_0D35303E3F342612110A392D0304182F223B2D2F2F0332_->user_id == $userid) {
                $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_ = $db->get("vncp_nodes", ["name", "=", $_obfuscated_0D35303E3F342612110A392D0304182F223B2D2F2F0332_->node])->first();
                $pxAPI = new PVE2_API($_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->hostname, $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->username, $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->password));
                $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = false;
                if (!$pxAPI->login()) {
                    $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = true;
                }
                if (!$_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_) {
                    $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_ = $pxAPI->get("/pools/" . $_obfuscated_0D35303E3F342612110A392D0304182F223B2D2F2F0332_->pool_id);
                    $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ = $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_["members"][0]["vmid"];
                    $_obfuscated_0D03272A380E1F2C2C1A182D32040B3B39381E270F2211_ = $pxAPI->post("/nodes/" . $_obfuscated_0D35303E3F342612110A392D0304182F223B2D2F2F0332_->node . "/lxc/" . $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ . "/status/start", []);
                    sleep(5);
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] = 1;
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "success";
                }
            } else {
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Invalid user ID";
            }
        } else {
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Invalid type";
        }
    }
    return $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_;
}
function _obfuscated_0A1F0E32_($db, $type, $hbid, $userid)
{
    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_ = ["success" => 0, "message" => "stopserver failed", "data" => []];
    if ($type == "kvm") {
        $_obfuscated_0D21291403291732023C291633083206180C3204283432_ = $db->get("vncp_kvm_ct", ["hb_account_id", "=", $hbid])->first();
        if ($_obfuscated_0D21291403291732023C291633083206180C3204283432_->user_id == $userid) {
            $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_ = $db->get("vncp_nodes", ["name", "=", $_obfuscated_0D21291403291732023C291633083206180C3204283432_->node])->first();
            $pxAPI = new PVE2_API($_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->hostname, $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->username, $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->password));
            $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = false;
            if (!$pxAPI->login()) {
                $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = true;
            }
            if (!$_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_) {
                $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_ = $pxAPI->get("/pools/" . $_obfuscated_0D21291403291732023C291633083206180C3204283432_->pool_id);
                $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ = $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_["members"][0]["vmid"];
                $_obfuscated_0D03272A380E1F2C2C1A182D32040B3B39381E270F2211_ = $pxAPI->post("/nodes/" . $_obfuscated_0D21291403291732023C291633083206180C3204283432_->node . "/qemu/" . $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ . "/status/stop", []);
                sleep(5);
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] = 1;
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "success";
            }
        } else {
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Invalid user ID";
        }
    } else {
        if ($type == "lxc") {
            $_obfuscated_0D35303E3F342612110A392D0304182F223B2D2F2F0332_ = $db->get("vncp_lxc_ct", ["hb_account_id", "=", $hbid])->first();
            if ($_obfuscated_0D35303E3F342612110A392D0304182F223B2D2F2F0332_->user_id == $userid) {
                $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_ = $db->get("vncp_nodes", ["name", "=", $_obfuscated_0D35303E3F342612110A392D0304182F223B2D2F2F0332_->node])->first();
                $pxAPI = new PVE2_API($_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->hostname, $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->username, $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->password));
                $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = false;
                if (!$pxAPI->login()) {
                    $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = true;
                }
                if (!$_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_) {
                    $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_ = $pxAPI->get("/pools/" . $_obfuscated_0D35303E3F342612110A392D0304182F223B2D2F2F0332_->pool_id);
                    $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ = $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_["members"][0]["vmid"];
                    $_obfuscated_0D03272A380E1F2C2C1A182D32040B3B39381E270F2211_ = $pxAPI->post("/nodes/" . $_obfuscated_0D35303E3F342612110A392D0304182F223B2D2F2F0332_->node . "/lxc/" . $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ . "/status/stop", []);
                    sleep(5);
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] = 1;
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "success";
                }
            } else {
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Invalid user ID";
            }
        } else {
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Invalid type";
        }
    }
    return $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_;
}
function _obfuscated_150A190B1740251B402C2D5B3729212C1622_($db, $type, $hbid)
{
    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_ = ["success" => 0, "message" => "getDetails failed", "data" => []];
    $_obfuscated_0D032F1D21151E2140083109290631080A2F153B223201_ = "";
    $poolid = "";
    if ($type == "kvm") {
        $_obfuscated_0D21291403291732023C291633083206180C3204283432_ = $db->get("vncp_kvm_ct", ["hb_account_id", "=", $hbid])->first();
        $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] = 1;
        $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "success";
        $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"][] = $_obfuscated_0D21291403291732023C291633083206180C3204283432_->node;
        $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"][] = $_obfuscated_0D21291403291732023C291633083206180C3204283432_->ip;
        $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"][] = $_obfuscated_0D21291403291732023C291633083206180C3204283432_->os;
        $_obfuscated_0D032F1D21151E2140083109290631080A2F153B223201_ = "qemu";
        $poolid = $_obfuscated_0D21291403291732023C291633083206180C3204283432_->pool_id;
    } else {
        if ($type == "pc") {
            $_obfuscated_0D2C09401C103F16401A10230313153903180E5B0D1D32_ = $db->get("vncp_kvm_cloud", ["hb_account_id", "=", $hbid])->first();
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] = 1;
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "success";
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"][] = $_obfuscated_0D2C09401C103F16401A10230313153903180E5B0D1D32_->nodes;
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"][] = $_obfuscated_0D2C09401C103F16401A10230313153903180E5B0D1D32_->ipv4;
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"][] = "N/A";
        } else {
            if ($type == "lxc") {
                $_obfuscated_0D35303E3F342612110A392D0304182F223B2D2F2F0332_ = $db->get("vncp_lxc_ct", ["hb_account_id", "=", $hbid])->first();
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] = 1;
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "success";
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"][] = $_obfuscated_0D35303E3F342612110A392D0304182F223B2D2F2F0332_->node;
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"][] = $_obfuscated_0D35303E3F342612110A392D0304182F223B2D2F2F0332_->ip;
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"][] = $_obfuscated_0D35303E3F342612110A392D0304182F223B2D2F2F0332_->os;
                $_obfuscated_0D032F1D21151E2140083109290631080A2F153B223201_ = "lxc";
                $poolid = $_obfuscated_0D35303E3F342612110A392D0304182F223B2D2F2F0332_->pool_id;
            } else {
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Invalid type";
            }
        }
    }
    if ($_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] == 1 && $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] == "success") {
        $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"][] = "unknown";
        if ($_obfuscated_0D032F1D21151E2140083109290631080A2F153B223201_ != "" && $type != "pc" && $poolid != "") {
            $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_ = $db->get("vncp_nodes", ["name", "=", $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"][0]])->first();
            $pxAPI = new PVE2_API($_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->hostname, $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->username, $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->password));
            $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = false;
            if (!$pxAPI->login()) {
                $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = true;
            }
            if (!$_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_) {
                $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_ = $pxAPI->get("/pools/" . $poolid);
                $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ = $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_["members"][0]["vmid"];
                $_obfuscated_0D2B2E2B2D1F242314402A0F3801153D2E0B0C1A193011_ = $pxAPI->get("/nodes/" . $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"][0] . "/" . $_obfuscated_0D032F1D21151E2140083109290631080A2F153B223201_ . "/" . $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ . "/status/current");
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"][3] = $_obfuscated_0D2B2E2B2D1F242314402A0F3801153D2E0B0C1A193011_["status"];
            }
        }
    }
    return $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_;
}
function _obfuscated_0D3E0832243F0E07190428111118044012123C2A0F0501_($db)
{
    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_ = ["success" => 0, "message" => "getNodes failed", "data" => []];
    $nodes = $db->get("vncp_nodes", ["id", "!=", 0])->all();
    if (0 < count($nodes)) {
        foreach ($nodes as $node) {
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"][] = $node->name;
        }
        $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] = 1;
        $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "success";
    }
    return $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_;
}
function _obfuscated_0D15013523080C1B0E3C1A120E323C1B2832280C293011_($db, $type, $hbid, $node, $poolid, $userid)
{
    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_ = ["success" => 0, "message" => "terminate failed", "data" => []];
    $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_ = $db->get("vncp_nodes", ["name", "=", $node])->first();
    $pxAPI = new PVE2_API($_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->hostname, $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->username, $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->password));
    $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = false;
    if (!$pxAPI->login()) {
        $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = true;
    }
    if (!$_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_) {
        if ($type == "kvm") {
            $_obfuscated_0D0540161F0C1D2E082F3226140C3F0F25041609160A01_ = $db->get("vncp_kvm_ct", ["hb_account_id", "=", $hbid])->first();
            $db->delete("vncp_dhcp", ["ip", "=", $_obfuscated_0D0540161F0C1D2E082F3226140C3F0F25041609160A01_->ip]);
            $db->delete("vncp_kvm_ct", ["hb_account_id", "=", $hbid]);
            $db->delete("vncp_ct_backups", ["hb_account_id", "=", $hbid]);
            $db->delete("vncp_bandwidth_monitor", ["hb_account_id", "=", $hbid]);
            $db->delete("vncp_reverse_dns", ["ipaddress", "=", $_obfuscated_0D0540161F0C1D2E082F3226140C3F0F25041609160A01_->ip]);
            $db->delete("vncp_ipv6_assignment", ["hb_account_id", "=", $hbid]);
            $db->updatevm_aid("vncp_private_pool", $hbid, ["user_id" => 0, "hb_account_id" => 0, "available" => 1]);
            $db->updatevm_aid("vncp_ipv4_pool", $hbid, ["user_id" => 0, "hb_account_id" => 0, "available" => 1]);
            $db->delete("vncp_secondary_ips", ["hb_account_id", "=", $hbid]);
            $db->delete("vncp_pending_clone", ["hb_account_id", "=", $hbid]);
            $_obfuscated_0D07393901262A123D3C11323132172226063423262322_ = $db->get("vncp_natforwarding", ["hb_account_id", "=", $hbid])->all();
            if (count($_obfuscated_0D07393901262A123D3C11323132172226063423262322_) == 1) {
                $_obfuscated_0D103D1525302B263E2507302A042B0F14121810250411_ = $db->get("vncp_nat", ["node", "=", $_obfuscated_0D07393901262A123D3C11323132172226063423262322_[0]->node])->first();
                $_obfuscated_0D2123091B303522361E2C0B142C2624321E3040192C01_ = explode(";", $_obfuscated_0D07393901262A123D3C11323132172226063423262322_[0]->domains);
                $_obfuscated_0D2B37361A2138020D3435061E1D191A1F2A02250A2811_ = "";
                for ($i = 0; $i < count($_obfuscated_0D2123091B303522361E2C0B142C2624321E3040192C01_) - 1; $i++) {
                    $_obfuscated_0D2B37361A2138020D3435061E1D191A1F2A02250A2811_ .= "rm /etc/nginx/conf.d/" . $hbid . "-" . $_obfuscated_0D2123091B303522361E2C0B142C2624321E3040192C01_[$i] . "-*.conf && rm /etc/nginx/proxcp-nat-ssl/cert-" . $hbid . "-" . $_obfuscated_0D2123091B303522361E2C0B142C2624321E3040192C01_[$i] . "-*.pem && rm /etc/nginx/proxcp-nat-ssl/key-" . $hbid . "-" . $_obfuscated_0D2123091B303522361E2C0B142C2624321E3040192C01_[$i] . "-*.pem && ";
                }
                $_obfuscated_0D2B37361A2138020D3435061E1D191A1F2A02250A2811_ .= "service nginx restart";
                $_obfuscated_0D213E1A2C0E2A2D12041126321614182816301A150232_ = explode(";", $_obfuscated_0D07393901262A123D3C11323132172226063423262322_[0]->ports);
                $_obfuscated_0D281112261E3B402D0F1416061211143D1D1726173532_ = "";
                for ($i = 0; $i < count($_obfuscated_0D213E1A2C0E2A2D12041126321614182816301A150232_); $i++) {
                    $_obfuscated_0D152F2D0931350C3E2B0D11063016322A1C1D40093222_ = explode(":", $_obfuscated_0D213E1A2C0E2A2D12041126321614182816301A150232_[$i]);
                    $_obfuscated_0D281112261E3B402D0F1416061211143D1D1726173532_ .= "iptables -t nat -D PREROUTING -p tcp -d " . $_obfuscated_0D103D1525302B263E2507302A042B0F14121810250411_->publicip . " --dport " . $_obfuscated_0D152F2D0931350C3E2B0D11063016322A1C1D40093222_[1] . " -i vmbr0 -j DNAT --to-destination " . $_obfuscated_0D0540161F0C1D2E082F3226140C3F0F25041609160A01_->ip . ":" . $_obfuscated_0D152F2D0931350C3E2B0D11063016322A1C1D40093222_[2] . " && ";
                }
                $_obfuscated_0D281112261E3B402D0F1416061211143D1D1726173532_ .= "iptables-save > /root/proxcp-iptables.rules";
                $_obfuscated_0D0B102E372B3308132A392A35160D212F38072E3F2A01_ = $db->get("vncp_nodes", ["name", "=", $_obfuscated_0D07393901262A123D3C11323132172226063423262322_[0]->node])->all();
                $_obfuscated_0D25191C1F0A283333343D093F0B3116243D36281A1322_ = $db->get("vncp_tuntap", ["node", "=", $_obfuscated_0D07393901262A123D3C11323132172226063423262322_[0]->node])->all();
                if (count($_obfuscated_0D0B102E372B3308132A392A35160D212F38072E3F2A01_) && count($_obfuscated_0D25191C1F0A283333343D093F0B3116243D36281A1322_)) {
                    $_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_ = new phpseclib\Net\SSH2($_obfuscated_0D0B102E372B3308132A392A35160D212F38072E3F2A01_[0]->hostname, (int) $_obfuscated_0D25191C1F0A283333343D093F0B3116243D36281A1322_[0]->port);
                    if ($_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_->login("root", _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($_obfuscated_0D25191C1F0A283333343D093F0B3116243D36281A1322_[0]->password))) {
                        $_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_->exec($_obfuscated_0D2B37361A2138020D3435061E1D191A1F2A02250A2811_);
                        $_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_->exec($_obfuscated_0D281112261E3B402D0F1416061211143D1D1726173532_);
                        $_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_->disconnect();
                    }
                }
                $db->delete("vncp_natforwarding", ["hb_account_id", "=", $hbid]);
            }
            $_obfuscated_0D031414130602342F32221629245C39100C402A093732_ = $db->get("vncp_kvm_ct", ["user_id", "=", $userid])->all();
            $_obfuscated_0D251D3018091A353D5C0F1A110B37372C110809063C01_ = $db->get("vncp_lxc_ct", ["user_id", "=", $userid])->all();
            $_obfuscated_0D0C11333D062A3B251603333E3516011D271F231C5C01_ = $db->get("vncp_kvm_cloud", ["user_id", "=", $userid])->all();
            $_obfuscated_0D5B3014150F1A2F2D310D3E37360A0C0339341E1E5C32_ = $db->get("vncp_users", ["id", "=", $userid])->first();
            if (count($_obfuscated_0D031414130602342F32221629245C39100C402A093732_) == 0 && count($_obfuscated_0D251D3018091A353D5C0F1A110B37372C110809063C01_) == 0 && count($_obfuscated_0D0C11333D062A3B251603333E3516011D271F231C5C01_) == 0 && $_obfuscated_0D5B3014150F1A2F2D310D3E37360A0C0339341E1E5C32_->group != 2) {
                $db->delete("vncp_forward_dns_domain", ["client_id", "=", $userid]);
                $db->delete("vncp_forward_dns_record", ["client_id", "=", $userid]);
                $db->delete("vncp_acl", ["user_id", "=", $userid]);
                $db->delete("vncp_notes", ["id", "=", $userid]);
                $db->delete("vncp_users", ["id", "=", $userid]);
            }
            $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_ = $pxAPI->get("/pools/" . $poolid);
            $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ = $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_["members"][0]["vmid"];
            $_obfuscated_0D2A371D3D062B02020725243017183E143B3327172411_ = $pxAPI->post("/nodes/" . $node . "/qemu/" . $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ . "/status/stop", []);
            sleep(5);
            $delete = $pxAPI->delete("/nodes/" . $node . "/qemu/" . $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_);
            sleep(2);
            $_obfuscated_0D5C120903331F14113339172C2427253806041E283711_ = $pxAPI->delete("/pools/" . $poolid);
            sleep(1);
            $_obfuscated_0D0F160F281D182711323E133222370F24145C032E0E22_ = $pxAPI->delete("/access/users/" . $poolid);
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] = 1;
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Terminated account successfully";
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"] = ["terminated account successfully"];
        } else {
            if ($type == "pc") {
                $_obfuscated_0D28211F33280F0A25233B192234212C3F35242F1C2C22_ = $db->get("vncp_kvm_ct", ["cloud_account_id", "=", $hbid])->all();
                for ($i = 0; $i < count($_obfuscated_0D28211F33280F0A25233B192234212C3F35242F1C2C22_); $i++) {
                    $db->delete("vncp_dhcp", ["ip", "=", $_obfuscated_0D28211F33280F0A25233B192234212C3F35242F1C2C22_[$i]->ip]);
                    $db->delete("vncp_kvm_ct", ["hb_account_id", "=", $_obfuscated_0D28211F33280F0A25233B192234212C3F35242F1C2C22_[$i]->hb_account_id]);
                    $db->delete("vncp_ct_backups", ["hb_account_id", "=", $_obfuscated_0D28211F33280F0A25233B192234212C3F35242F1C2C22_[$i]->hb_account_id]);
                    $db->delete("vncp_bandwidth_monitor", ["hb_account_id", "=", $_obfuscated_0D28211F33280F0A25233B192234212C3F35242F1C2C22_[$i]->hb_account_id]);
                    $db->delete("vncp_reverse_dns", ["ipaddress", "=", $_obfuscated_0D28211F33280F0A25233B192234212C3F35242F1C2C22_[$i]->ip]);
                    $db->delete("vncp_ipv6_assignment", ["hb_account_id", "=", $_obfuscated_0D28211F33280F0A25233B192234212C3F35242F1C2C22_[$i]->hb_account_id]);
                    $db->updatevm_aid("vncp_private_pool", $_obfuscated_0D28211F33280F0A25233B192234212C3F35242F1C2C22_[$i]->hb_account_id, ["user_id" => 0, "hb_account_id" => 0, "available" => 1]);
                    $db->updatevm_aid("vncp_ipv4_pool", $_obfuscated_0D28211F33280F0A25233B192234212C3F35242F1C2C22_[$i]->hb_account_id, ["user_id" => 0, "hb_account_id" => 0, "available" => 1]);
                    $db->delete("vncp_secondary_ips", ["hb_account_id", "=", $_obfuscated_0D28211F33280F0A25233B192234212C3F35242F1C2C22_[$i]->hb_account_id]);
                    $db->delete("vncp_pending_clone", ["hb_account_id", "=", $_obfuscated_0D28211F33280F0A25233B192234212C3F35242F1C2C22_[$i]->hb_account_id]);
                    $db->delete("vncp_pending_deletion", ["hb_account_id", "=", $_obfuscated_0D28211F33280F0A25233B192234212C3F35242F1C2C22_[$i]->hb_account_id]);
                }
                $db->delete("vncp_kvm_cloud", ["hb_account_id", "=", $hbid]);
                $_obfuscated_0D031414130602342F32221629245C39100C402A093732_ = $db->get("vncp_kvm_ct", ["user_id", "=", $userid])->all();
                $_obfuscated_0D251D3018091A353D5C0F1A110B37372C110809063C01_ = $db->get("vncp_lxc_ct", ["user_id", "=", $userid])->all();
                $_obfuscated_0D0C11333D062A3B251603333E3516011D271F231C5C01_ = $db->get("vncp_kvm_cloud", ["user_id", "=", $userid])->all();
                $_obfuscated_0D5B3014150F1A2F2D310D3E37360A0C0339341E1E5C32_ = $db->get("vncp_users", ["id", "=", $userid])->first();
                if (count($_obfuscated_0D031414130602342F32221629245C39100C402A093732_) == 0 && count($_obfuscated_0D251D3018091A353D5C0F1A110B37372C110809063C01_) == 0 && count($_obfuscated_0D0C11333D062A3B251603333E3516011D271F231C5C01_) == 0 && $_obfuscated_0D5B3014150F1A2F2D310D3E37360A0C0339341E1E5C32_->group != 2) {
                    $db->delete("vncp_forward_dns_domain", ["client_id", "=", $userid]);
                    $db->delete("vncp_forward_dns_record", ["client_id", "=", $userid]);
                    $db->delete("vncp_acl", ["user_id", "=", $userid]);
                    $db->delete("vncp_notes", ["id", "=", $userid]);
                    $db->delete("vncp_users", ["id", "=", $userid]);
                }
                $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_ = $pxAPI->get("/pools/" . $poolid);
                for ($i = 0; $i < count($_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_["members"]); $i++) {
                    $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ = $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_["members"][$i]["vmid"];
                    $_obfuscated_0D2A371D3D062B02020725243017183E143B3327172411_ = $pxAPI->post("/nodes/" . $node . "/qemu/" . $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ . "/status/stop", []);
                    sleep(5);
                    $delete = $pxAPI->delete("/nodes/" . $node . "/qemu/" . $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_);
                }
                sleep(2);
                $_obfuscated_0D5C120903331F14113339172C2427253806041E283711_ = $pxAPI->delete("/pools/" . $poolid);
                $_obfuscated_0D0F160F281D182711323E133222370F24145C032E0E22_ = $pxAPI->delete("/access/users/" . $poolid);
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] = 1;
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Terminated account successfully";
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"] = ["terminated account successfully"];
            } else {
                if ($type == "lxc") {
                    $_obfuscated_0D073D1C241D2C182D3F3234343F282940122D330B1711_ = $db->get("vncp_lxc_ct", ["hb_account_id", "=", $hbid])->first();
                    $db->delete("vncp_dhcp", ["ip", "=", $_obfuscated_0D073D1C241D2C182D3F3234343F282940122D330B1711_->ip]);
                    $db->delete("vncp_lxc_ct", ["hb_account_id", "=", $hbid]);
                    $db->delete("vncp_ct_backups", ["hb_account_id", "=", $hbid]);
                    $db->delete("vncp_bandwidth_monitor", ["hb_account_id", "=", $hbid]);
                    $db->delete("vncp_reverse_dns", ["ipaddress", "=", $_obfuscated_0D0540161F0C1D2E082F3226140C3F0F25041609160A01_->ip]);
                    $db->delete("vncp_ipv6_assignment", ["hb_account_id", "=", $hbid]);
                    $db->updatevm_aid("vncp_private_pool", $hbid, ["user_id" => 0, "hb_account_id" => 0, "available" => 1]);
                    $db->updatevm_aid("vncp_ipv4_pool", $hbid, ["user_id" => 0, "hb_account_id" => 0, "available" => 1]);
                    $db->delete("vncp_secondary_ips", ["hb_account_id", "=", $hbid]);
                    $_obfuscated_0D07393901262A123D3C11323132172226063423262322_ = $db->get("vncp_natforwarding", ["hb_account_id", "=", $hbid])->all();
                    if (count($_obfuscated_0D07393901262A123D3C11323132172226063423262322_) == 1) {
                        $_obfuscated_0D103D1525302B263E2507302A042B0F14121810250411_ = $db->get("vncp_nat", ["node", "=", $_obfuscated_0D07393901262A123D3C11323132172226063423262322_[0]->node])->first();
                        $_obfuscated_0D2123091B303522361E2C0B142C2624321E3040192C01_ = explode(";", $_obfuscated_0D07393901262A123D3C11323132172226063423262322_[0]->domains);
                        $_obfuscated_0D2B37361A2138020D3435061E1D191A1F2A02250A2811_ = "";
                        for ($i = 0; $i < count($_obfuscated_0D2123091B303522361E2C0B142C2624321E3040192C01_) - 1; $i++) {
                            $_obfuscated_0D2B37361A2138020D3435061E1D191A1F2A02250A2811_ .= "rm /etc/nginx/conf.d/" . $hbid . "-" . $_obfuscated_0D2123091B303522361E2C0B142C2624321E3040192C01_[$i] . "-*.conf && rm /etc/nginx/proxcp-nat-ssl/cert-" . $hbid . "-" . $_obfuscated_0D2123091B303522361E2C0B142C2624321E3040192C01_[$i] . "-*.pem && rm /etc/nginx/proxcp-nat-ssl/key-" . $hbid . "-" . $_obfuscated_0D2123091B303522361E2C0B142C2624321E3040192C01_[$i] . "-*.pem && ";
                        }
                        $_obfuscated_0D2B37361A2138020D3435061E1D191A1F2A02250A2811_ .= "service nginx restart";
                        $_obfuscated_0D213E1A2C0E2A2D12041126321614182816301A150232_ = explode(";", $_obfuscated_0D07393901262A123D3C11323132172226063423262322_[0]->ports);
                        $_obfuscated_0D281112261E3B402D0F1416061211143D1D1726173532_ = "";
                        for ($i = 0; $i < count($_obfuscated_0D213E1A2C0E2A2D12041126321614182816301A150232_); $i++) {
                            $_obfuscated_0D152F2D0931350C3E2B0D11063016322A1C1D40093222_ = explode(":", $_obfuscated_0D213E1A2C0E2A2D12041126321614182816301A150232_[$i]);
                            $_obfuscated_0D281112261E3B402D0F1416061211143D1D1726173532_ .= "iptables -t nat -D PREROUTING -p tcp -d " . $_obfuscated_0D103D1525302B263E2507302A042B0F14121810250411_->publicip . " --dport " . $_obfuscated_0D152F2D0931350C3E2B0D11063016322A1C1D40093222_[1] . " -i vmbr0 -j DNAT --to-destination " . $_obfuscated_0D0540161F0C1D2E082F3226140C3F0F25041609160A01_->ip . ":" . $_obfuscated_0D152F2D0931350C3E2B0D11063016322A1C1D40093222_[2] . " && ";
                        }
                        $_obfuscated_0D281112261E3B402D0F1416061211143D1D1726173532_ .= "iptables-save > /root/proxcp-iptables.rules";
                        $_obfuscated_0D0B102E372B3308132A392A35160D212F38072E3F2A01_ = $db->get("vncp_nodes", ["name", "=", $_obfuscated_0D07393901262A123D3C11323132172226063423262322_[0]->node])->all();
                        $_obfuscated_0D25191C1F0A283333343D093F0B3116243D36281A1322_ = $db->get("vncp_tuntap", ["node", "=", $_obfuscated_0D07393901262A123D3C11323132172226063423262322_[0]->node])->all();
                        if (count($_obfuscated_0D0B102E372B3308132A392A35160D212F38072E3F2A01_) && count($_obfuscated_0D25191C1F0A283333343D093F0B3116243D36281A1322_)) {
                            $_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_ = new phpseclib\Net\SSH2($_obfuscated_0D0B102E372B3308132A392A35160D212F38072E3F2A01_[0]->hostname, (int) $_obfuscated_0D25191C1F0A283333343D093F0B3116243D36281A1322_[0]->port);
                            if ($_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_->login("root", _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($_obfuscated_0D25191C1F0A283333343D093F0B3116243D36281A1322_[0]->password))) {
                                $_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_->exec($_obfuscated_0D2B37361A2138020D3435061E1D191A1F2A02250A2811_);
                                $_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_->exec($_obfuscated_0D281112261E3B402D0F1416061211143D1D1726173532_);
                                $_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_->disconnect();
                            }
                        }
                        $db->delete("vncp_natforwarding", ["hb_account_id", "=", $hbid]);
                    }
                    $_obfuscated_0D031414130602342F32221629245C39100C402A093732_ = $db->get("vncp_kvm_ct", ["user_id", "=", $userid])->all();
                    $_obfuscated_0D251D3018091A353D5C0F1A110B37372C110809063C01_ = $db->get("vncp_lxc_ct", ["user_id", "=", $userid])->all();
                    $_obfuscated_0D0C11333D062A3B251603333E3516011D271F231C5C01_ = $db->get("vncp_kvm_cloud", ["user_id", "=", $userid])->all();
                    $_obfuscated_0D5B3014150F1A2F2D310D3E37360A0C0339341E1E5C32_ = $db->get("vncp_users", ["id", "=", $userid])->first();
                    if (count($_obfuscated_0D031414130602342F32221629245C39100C402A093732_) == 0 && count($_obfuscated_0D251D3018091A353D5C0F1A110B37372C110809063C01_) == 0 && count($_obfuscated_0D0C11333D062A3B251603333E3516011D271F231C5C01_) == 0 && $_obfuscated_0D5B3014150F1A2F2D310D3E37360A0C0339341E1E5C32_->group != 2) {
                        $db->delete("vncp_forward_dns_domain", ["client_id", "=", $userid]);
                        $db->delete("vncp_forward_dns_record", ["client_id", "=", $userid]);
                        $db->delete("vncp_acl", ["user_id", "=", $userid]);
                        $db->delete("vncp_notes", ["id", "=", $userid]);
                        $db->delete("vncp_users", ["id", "=", $userid]);
                    }
                    $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_ = $pxAPI->get("/pools/" . $poolid);
                    $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ = $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_["members"][0]["vmid"];
                    $_obfuscated_0D2A371D3D062B02020725243017183E143B3327172411_ = $pxAPI->post("/nodes/" . $node . "/lxc/" . $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ . "/status/stop", []);
                    sleep(5);
                    $delete = $pxAPI->delete("/nodes/" . $node . "/lxc/" . $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_);
                    sleep(2);
                    $_obfuscated_0D5C120903331F14113339172C2427253806041E283711_ = $pxAPI->delete("/pools/" . $poolid);
                    $_obfuscated_0D0F160F281D182711323E133222370F24145C032E0E22_ = $pxAPI->delete("/access/users/" . $poolid);
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] = 1;
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Terminated account successfully";
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"] = ["terminated account successfully"];
                } else {
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Invalid type";
                }
            }
        }
    } else {
        $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Could not connect to Proxmox node";
    }
    return $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_;
}
function _obfuscated_0D3D3638122B1E2C1F110B3C24242B2D3C0A33380C0532_($db, $type, $hbid, $node, $poolid)
{
    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_ = ["success" => 0, "message" => "suspend failed", "data" => []];
    $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_ = $db->get("vncp_nodes", ["name", "=", $node])->first();
    $pxAPI = new PVE2_API($_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->hostname, $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->username, $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->password));
    $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = false;
    if (!$pxAPI->login()) {
        $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = true;
    }
    if (!$_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_) {
        if ($type == "kvm") {
            $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_ = $pxAPI->get("/pools/" . $poolid);
            $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ = $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_["members"][0]["vmid"];
            $_obfuscated_0D2A371D3D062B02020725243017183E143B3327172411_ = $pxAPI->post("/nodes/" . $node . "/qemu/" . $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ . "/status/stop", []);
            sleep(1);
            $db->updatevm_aid("vncp_kvm_ct", $hbid, ["suspended" => 1]);
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] = 1;
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "success";
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"] = ["success"];
        } else {
            if ($type == "pc") {
                $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_ = $pxAPI->get("/pools/" . $poolid);
                for ($j = 0; $j < count($_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_["members"]); $j++) {
                    $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ = $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_["members"][$j]["vmid"];
                    $_obfuscated_0D2A371D3D062B02020725243017183E143B3327172411_ = $pxAPI->post("/nodes/" . $node . "/qemu/" . $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ . "/status/stop", []);
                    sleep(1);
                }
                $db->updatevm_aid("vncp_kvm_cloud", $hbid, ["suspended" => 1]);
                $db->updatevm_clid("vncp_kvm_ct", $hbid, ["suspended" => 1]);
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] = 1;
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "success";
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"] = ["success"];
            } else {
                if ($type == "lxc") {
                    $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_ = $pxAPI->get("/pools/" . $poolid);
                    $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ = $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_["members"][0]["vmid"];
                    $_obfuscated_0D2A371D3D062B02020725243017183E143B3327172411_ = $pxAPI->post("/nodes/" . $node . "/lxc/" . $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ . "/status/stop", []);
                    sleep(1);
                    $db->updatevm_aid("vncp_lxc_ct", $hbid, ["suspended" => 1]);
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] = 1;
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "success";
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"] = ["success"];
                } else {
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Invalid type";
                }
            }
        }
    } else {
        $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Could not connect to Proxmox node";
    }
    return $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_;
}
function _obfuscated_0D2F112A370625313D331E3633013E3B0F083619180301_($db, $type, $hbid, $node, $poolid)
{
    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_ = ["success" => 0, "message" => "suspend failed", "data" => []];
    $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_ = $db->get("vncp_nodes", ["name", "=", $node])->first();
    $pxAPI = new PVE2_API($_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->hostname, $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->username, $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->password));
    $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = false;
    if (!$pxAPI->login()) {
        $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = true;
    }
    if (!$_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_) {
        if ($type == "kvm") {
            $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_ = $pxAPI->get("/pools/" . $poolid);
            $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ = $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_["members"][0]["vmid"];
            $_obfuscated_0D3337291432403427320F295B2F0E110C1519072B2F11_ = $pxAPI->post("/nodes/" . $node . "/qemu/" . $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ . "/status/start", []);
            sleep(1);
            $db->updatevm_aid("vncp_kvm_ct", $hbid, ["suspended" => 0]);
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] = 1;
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "success";
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"] = ["success"];
        } else {
            if ($type == "pc") {
                $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_ = $pxAPI->get("/pools/" . $poolid);
                for ($j = 0; $j < count($_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_["members"]); $j++) {
                    $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ = $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_["members"][$j]["vmid"];
                    $_obfuscated_0D3337291432403427320F295B2F0E110C1519072B2F11_ = $pxAPI->post("/nodes/" . $node . "/qemu/" . $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ . "/status/start", []);
                    sleep(1);
                }
                $db->updatevm_aid("vncp_kvm_cloud", $hbid, ["suspended" => 0]);
                $db->updatevm_clid("vncp_kvm_ct", $hbid, ["suspended" => 0]);
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] = 1;
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "success";
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"] = ["success"];
            } else {
                if ($type == "lxc") {
                    $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_ = $pxAPI->get("/pools/" . $poolid);
                    $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ = $_obfuscated_0D053E0311262A2A015C32390135242B0C1E3F363D1022_["members"][0]["vmid"];
                    $_obfuscated_0D3337291432403427320F295B2F0E110C1519072B2F11_ = $pxAPI->post("/nodes/" . $node . "/lxc/" . $_obfuscated_0D3121125C240D1C35242B232B113B055C3C40271F2911_ . "/status/start", []);
                    sleep(1);
                    $db->updatevm_aid("vncp_lxc_ct", $hbid, ["suspended" => 0]);
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] = 1;
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "success";
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"] = ["success"];
                } else {
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Invalid type";
                }
            }
        }
    } else {
        $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Could not connect to Proxmox node";
    }
    return $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_;
}
function _obfuscated_0D16193635272229032E16103B07021D09180434121332_($db, $userid, $email, $pw, $node, $hbid, $poolid, $storage, $cpu, $ram, $cputype, $howmanyips)
{
    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_ = ["success" => 0, "message" => "createCloud failed", "data" => []];
    $_obfuscated_0D1736131B21195C2338363F3D35012A3E251B3B3E2C11_ = $db->get("vncp_users", ["id", "=", $userid])->all();
    if (count($_obfuscated_0D1736131B21195C2338363F3D35012A3E251B3B3E2C11_) < 1) {
        $_obfuscated_0D0B0A3F095C162B27341F0F310F2A0B242F162E0E2211_ = Hash::salt(32);
        $_obfuscated_0D22193F1E5C1529280A2908182E310E16302B5B040E32_ = parse_input($db->get("vncp_settings", ["item", "=", "default_language"])->first()->value);
        $db->insert("vncp_users", ["id" => (int) $userid, "email" => strtolower($email), "username" => strtolower($email), "password" => Hash::make($pw, $_obfuscated_0D0B0A3F095C162B27341F0F310F2A0B242F162E0E2211_), "salt" => $_obfuscated_0D0B0A3F095C162B27341F0F310F2A0B242F162E0E2211_, "tfa_enabled" => 0, "tfa_secret" => "", "group" => 1, "locked" => 0, "language" => $_obfuscated_0D22193F1E5C1529280A2908182E310E16302B5B040E32_]);
    } else {
        $pw = -1;
    }
    $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_ = $db->get("vncp_nodes", ["name", "=", $node]);
    $_obfuscated_0D15143E01211C2C315B3330012F1F1B09150330115C11_ = $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->first();
    $pxAPI = new PVE2_API($_obfuscated_0D15143E01211C2C315B3330012F1F1B09150330115C11_->hostname, $_obfuscated_0D15143E01211C2C315B3330012F1F1B09150330115C11_->username, $_obfuscated_0D15143E01211C2C315B3330012F1F1B09150330115C11_->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($_obfuscated_0D15143E01211C2C315B3330012F1F1B09150330115C11_->password));
    $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = false;
    if (!$pxAPI->login()) {
        $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = true;
    }
    if (!$_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_) {
        $_obfuscated_0D022E101B3E1C070C1614132717321E3511032F1A0211_ = _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(12);
        $_obfuscated_0D0A0F132B1A2E2B3603172932241940191E15051B1611_ = $pxAPI->post("/pools", ["poolid" => $poolid]);
        sleep(1);
        $_obfuscated_0D1911182637182634243B322D321C29130B305C2D2E32_ = $pxAPI->post("/access/users", ["userid" => $poolid . "@pve", "password" => $_obfuscated_0D022E101B3E1C070C1614132717321E3511032F1A0211_]);
        sleep(1);
        $_obfuscated_0D251A081037120B34110B192E5B1A1A071B2410330B32_ = $pxAPI->put("/access/acl", ["path" => "/pool/" . $poolid, "users" => $poolid . "@pve", "roles" => "PVEVMUser"]);
        sleep(1);
        $ipv4 = [];
        for ($i = 0; $i < $howmanyips; $i++) {
            $_obfuscated_0D085C042A5C352D112C031A12102522361C18051B1432_ = _obfuscated_0D11211B35050A09315B252E3F181023151E1B2C262711_($db, $node, $userid, $hbid);
            $ipv4[] = $_obfuscated_0D085C042A5C352D112C031A12102522361C18051B1432_[0];
        }
        $ipv4 = implode(";", $ipv4);
        $db->insert("vncp_kvm_cloud", ["user_id" => $userid, "nodes" => $node, "hb_account_id" => $hbid, "pool_id" => $poolid, "pool_password" => _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_($_obfuscated_0D022E101B3E1C070C1614132717321E3511032F1A0211_), "memory" => (int) $ram, "cpu_cores" => (int) $cpu, "cpu_type" => $cputype, "disk_size" => (int) $storage, "ip_limit" => $howmanyips, "ipv4" => $ipv4, "avail_memory" => (int) $ram, "avail_cpu_cores" => (int) $cpu, "avail_disk_size" => (int) $storage, "avail_ip_limit" => $howmanyips, "avail_ipv4" => $ipv4, "suspended" => 0]);
        $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] = 1;
        $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "success";
        $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"] = ["success"];
        if ($pw == -1) {
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"][] = $pw;
        }
    } else {
        $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Could not connect to Proxmox node";
    }
    return $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_;
}
function _obfuscated_0D2C09331622252F0E3B030D011C2D14403133173D2D01_($db, $userid, $email, $pw, $node, $osfriendly, $ostype, $hbid, $poolid, $hostname, $storage, $cpu, $ram, $bwlimit, $nat, $natp, $natd, $vlantag, $portspeed, $backuplimit)
{
    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_ = ["success" => 0, "message" => "createLXC failed", "data" => []];
    $_obfuscated_0D1736131B21195C2338363F3D35012A3E251B3B3E2C11_ = $db->get("vncp_users", ["id", "=", $userid])->all();
    if (count($_obfuscated_0D1736131B21195C2338363F3D35012A3E251B3B3E2C11_) < 1) {
        $_obfuscated_0D0B0A3F095C162B27341F0F310F2A0B242F162E0E2211_ = Hash::salt(32);
        $_obfuscated_0D22193F1E5C1529280A2908182E310E16302B5B040E32_ = parse_input($db->get("vncp_settings", ["item", "=", "default_language"])->first()->value);
        $db->insert("vncp_users", ["id" => (int) $userid, "email" => strtolower($email), "username" => strtolower($email), "password" => Hash::make($pw, $_obfuscated_0D0B0A3F095C162B27341F0F310F2A0B242F162E0E2211_), "salt" => $_obfuscated_0D0B0A3F095C162B27341F0F310F2A0B242F162E0E2211_, "tfa_enabled" => 0, "tfa_secret" => "", "group" => 1, "locked" => 0, "language" => $_obfuscated_0D22193F1E5C1529280A2908182E310E16302B5B040E32_]);
    } else {
        $pw = -1;
    }
    $_obfuscated_0D103D1525302B263E2507302A042B0F14121810250411_ = "";
    $natp = (int) $natp;
    $natd = (int) $natd;
    if ($nat == "on") {
        $_obfuscated_0D103D1525302B263E2507302A042B0F14121810250411_ = $db->get("vncp_nat", ["node", "=", $node])->all();
        if (count($_obfuscated_0D103D1525302B263E2507302A042B0F14121810250411_) != 1) {
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Could not create NAT VPS. Selected node is not NAT-enabled.";
            return $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_;
        }
        if (empty($natp) || $natp < 1 || 30 < $natp) {
            $natp = 20;
        }
        if (empty($natd) || $natd < 0 || 15 < $natd) {
            $natd = 5;
        }
    }
    $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_ = $db->get("vncp_nodes", ["name", "=", $node]);
    $_obfuscated_0D15143E01211C2C315B3330012F1F1B09150330115C11_ = $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->first();
    $pxAPI = new PVE2_API($_obfuscated_0D15143E01211C2C315B3330012F1F1B09150330115C11_->hostname, $_obfuscated_0D15143E01211C2C315B3330012F1F1B09150330115C11_->username, $_obfuscated_0D15143E01211C2C315B3330012F1F1B09150330115C11_->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($_obfuscated_0D15143E01211C2C315B3330012F1F1B09150330115C11_->password));
    $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = false;
    if (!$pxAPI->login()) {
        $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = true;
    }
    if (!$_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_) {
        $_obfuscated_0D022E101B3E1C070C1614132717321E3511032F1A0211_ = _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(12);
        $_obfuscated_0D0A0F132B1A2E2B3603172932241940191E15051B1611_ = $pxAPI->post("/pools", ["poolid" => $poolid]);
        sleep(1);
        $_obfuscated_0D1911182637182634243B322D321C29130B305C2D2E32_ = $pxAPI->post("/access/users", ["userid" => $poolid . "@pve", "password" => $_obfuscated_0D022E101B3E1C070C1614132717321E3511032F1A0211_]);
        sleep(1);
        $_obfuscated_0D251A081037120B34110B192E5B1A1A071B2410330B32_ = $pxAPI->put("/access/acl", ["path" => "/pool/" . $poolid, "users" => $poolid . "@pve", "roles" => "PVEVMUser"]);
        sleep(1);
        $_obfuscated_0D3B0523261B2A0838143421242B2C32155C2A10283632_ = [];
        $_obfuscated_0D3707240C351F3F132E1C112D1C3D0E101C2133325C22_ = $pxAPI->get("/nodes/" . $node . "/qemu");
        for ($i = 0; $i < count($_obfuscated_0D3707240C351F3F132E1C112D1C3D0E101C2133325C22_); $i++) {
            $_obfuscated_0D3B0523261B2A0838143421242B2C32155C2A10283632_[] = (int) $_obfuscated_0D3707240C351F3F132E1C112D1C3D0E101C2133325C22_[$i]["vmid"];
        }
        $_obfuscated_0D111E0E3B31375C37251E35261F22131E063C2A093701_ = $pxAPI->get("/nodes/" . $node . "/lxc");
        for ($i = 0; $i < count($_obfuscated_0D111E0E3B31375C37251E35261F22131E063C2A093701_); $i++) {
            $_obfuscated_0D3B0523261B2A0838143421242B2C32155C2A10283632_[] = (int) $_obfuscated_0D111E0E3B31375C37251E35261F22131E063C2A093701_[$i]["vmid"];
        }
        $_obfuscated_0D343326172C022640051D3E081333381B103B04110601_ = array_keys($_obfuscated_0D3B0523261B2A0838143421242B2C32155C2A10283632_, max($_obfuscated_0D3B0523261B2A0838143421242B2C32155C2A10283632_));
        $_obfuscated_0D343326172C022640051D3E081333381B103B04110601_ = (int) $_obfuscated_0D3B0523261B2A0838143421242B2C32155C2A10283632_[$_obfuscated_0D343326172C022640051D3E081333381B103B04110601_[0]] + 1;
        sleep(1);
        $_obfuscated_0D403526012A2840342B055C0601382405392D142F0732_ = MacAddress::generateMacAddress();
        $_obfuscated_0D2D0F0B315B081B2D380234333740031A2D0A21181E01_ = $db->get("vncp_lxc_templates", ["friendly_name", "=", $osfriendly])->first();
        $ipv4 = _obfuscated_0D11211B35050A09315B252E3F181023151E1B2C262711_($db, $node, $userid, $hbid, $nat);
        $_obfuscated_0D2421345C5B3F19045C1F240F3D3E1F2C1B2B25242722_ = "/24";
        if ($ipv4[1] == "255.255.0.0") {
            $_obfuscated_0D2421345C5B3F19045C1F240F3D3E1F2C1B2B25242722_ = "/16";
        } else {
            if ($ipv4[1] == "255.255.128.0") {
                $_obfuscated_0D2421345C5B3F19045C1F240F3D3E1F2C1B2B25242722_ = "/17";
            } else {
                if ($ipv4[1] == "255.255.192.0") {
                    $_obfuscated_0D2421345C5B3F19045C1F240F3D3E1F2C1B2B25242722_ = "/18";
                } else {
                    if ($ipv4[1] == "255.255.224.0") {
                        $_obfuscated_0D2421345C5B3F19045C1F240F3D3E1F2C1B2B25242722_ = "/19";
                    } else {
                        if ($ipv4[1] == "255.255.240.0") {
                            $_obfuscated_0D2421345C5B3F19045C1F240F3D3E1F2C1B2B25242722_ = "/20";
                        } else {
                            if ($ipv4[1] == "255.255.248.0") {
                                $_obfuscated_0D2421345C5B3F19045C1F240F3D3E1F2C1B2B25242722_ = "/21";
                            } else {
                                if ($ipv4[1] == "255.255.252.0") {
                                    $_obfuscated_0D2421345C5B3F19045C1F240F3D3E1F2C1B2B25242722_ = "/22";
                                } else {
                                    if ($ipv4[1] == "255.255.254.0") {
                                        $_obfuscated_0D2421345C5B3F19045C1F240F3D3E1F2C1B2B25242722_ = "/23";
                                    } else {
                                        if ($ipv4[1] == "255.255.255.128") {
                                            $_obfuscated_0D2421345C5B3F19045C1F240F3D3E1F2C1B2B25242722_ = "/25";
                                        } else {
                                            if ($ipv4[1] == "255.255.255.192") {
                                                $_obfuscated_0D2421345C5B3F19045C1F240F3D3E1F2C1B2B25242722_ = "/26";
                                            } else {
                                                if ($ipv4[1] == "255.255.255.224") {
                                                    $_obfuscated_0D2421345C5B3F19045C1F240F3D3E1F2C1B2B25242722_ = "/27";
                                                } else {
                                                    if ($ipv4[1] == "255.255.255.240") {
                                                        $_obfuscated_0D2421345C5B3F19045C1F240F3D3E1F2C1B2B25242722_ = "/28";
                                                    } else {
                                                        if ($ipv4[1] == "255.255.255.248") {
                                                            $_obfuscated_0D2421345C5B3F19045C1F240F3D3E1F2C1B2B25242722_ = "/29";
                                                        } else {
                                                            if ($ipv4[1] == "255.255.255.252") {
                                                                $_obfuscated_0D2421345C5B3F19045C1F240F3D3E1F2C1B2B25242722_ = "/30";
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
        $_obfuscated_0D2E3C2A1422383227263722123E3B3924061835343E11_ = _obfuscated_0D15250B03270A2D5B2337313626041505272A182B3B22_($node, $pxAPI);
        $ostype = "unmanaged";
        if (strpos(strtolower($osfriendly), "debian") !== false) {
            $ostype = "debian";
        } else {
            if (strpos(strtolower($osfriendly), "ubuntu") !== false) {
                $ostype = "ubuntu";
            } else {
                if (strpos(strtolower($osfriendly), "centos") !== false) {
                    $ostype = "centos";
                } else {
                    if (strpos(strtolower($osfriendly), "fedora") !== false) {
                        $ostype = "fedora";
                    } else {
                        if (strpos(strtolower($osfriendly), "opensuse") !== false) {
                            $ostype = "opensuse";
                        } else {
                            if (strpos(strtolower($osfriendly), "archlinux") !== false) {
                                $ostype = "archlinux";
                            } else {
                                if (strpos(strtolower($osfriendly), "alpine") !== false) {
                                    $ostype = "alpine";
                                } else {
                                    if (strpos(strtolower($osfriendly), "gentoo") !== false) {
                                        $ostype = "gentoo";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $_obfuscated_0D022B0C333B10361004050B2D0D141B0E33053E043811_ = ["ostemplate" => $_obfuscated_0D2D0F0B315B081B2D380234333740031A2D0A21181E01_->volid, "vmid" => (int) $_obfuscated_0D343326172C022640051D3E081333381B103B04110601_, "cmode" => "tty", "cores" => (int) $cpu, "cpulimit" => 0, "cpuunits" => 1024, "description" => $ipv4[0], "hostname" => $hostname, "memory" => (int) $ram, "onboot" => 0, "ostype" => $ostype, "password" => $_obfuscated_0D022E101B3E1C070C1614132717321E3511032F1A0211_, "pool" => $poolid, "protection" => 0, "rootfs" => "" . $_obfuscated_0D2E3C2A1422383227263722123E3B3924061835343E11_ . ":" . $storage, "storage" => $_obfuscated_0D2E3C2A1422383227263722123E3B3924061835343E11_, "swap" => 512, "tty" => 2, "unprivileged" => 1];
        if ($nat == "on") {
            $_obfuscated_0D022B0C333B10361004050B2D0D141B0E33053E043811_["net0"] = "bridge=vmbr10,hwaddr=" . $_obfuscated_0D403526012A2840342B055C0601382405392D142F0732_ . ",ip=" . $ipv4[0] . $_obfuscated_0D2421345C5B3F19045C1F240F3D3E1F2C1B2B25242722_ . ",gw=" . $ipv4[2] . ",ip6=auto,name=eth0,type=veth";
        } else {
            $_obfuscated_0D022B0C333B10361004050B2D0D141B0E33053E043811_["net0"] = "bridge=vmbr0,hwaddr=" . $_obfuscated_0D403526012A2840342B055C0601382405392D142F0732_ . ",ip=" . $ipv4[0] . $_obfuscated_0D2421345C5B3F19045C1F240F3D3E1F2C1B2B25242722_ . ",gw=" . $ipv4[2] . ",ip6=auto,name=eth0,type=veth";
        }
        if (!empty($portspeed) && 0 < (int) $portspeed && (int) $portspeed < 10001) {
            $_obfuscated_0D022B0C333B10361004050B2D0D141B0E33053E043811_["net0"] = $_obfuscated_0D022B0C333B10361004050B2D0D141B0E33053E043811_["net0"] . ",rate=" . (string) $portspeed;
        }
        if (!empty($vlantag) && 0 < (int) $vlantag && (int) $vlantag < 4095) {
            $_obfuscated_0D022B0C333B10361004050B2D0D141B0E33053E043811_["net0"] = $_obfuscated_0D022B0C333B10361004050B2D0D141B0E33053E043811_["net0"] . ",tag=" . (string) $vlantag;
        }
        $_obfuscated_0D0C35023737380B2D102B34082C013C2D39102B401B11_ = $pxAPI->post("/nodes/" . $node . "/lxc", $_obfuscated_0D022B0C333B10361004050B2D0D141B0E33053E043811_);
        if (!$_obfuscated_0D0C35023737380B2D102B34082C013C2D39102B401B11_) {
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Could not create LXC. Proxmox API returned error";
        } else {
            $_obfuscated_0D311B04231E3D3D0E1A351D1333401D10330E083E1911_ = parse_input($db->get("vncp_settings", ["item", "=", "enable_backups"])->first()->value);
            $_obfuscated_0D21383D2A38065B111C210A2D1B25213C122F37091A32_ = -1;
            if ($_obfuscated_0D311B04231E3D3D0E1A351D1333401D10330E083E1911_ == "true") {
                $_obfuscated_0D21383D2A38065B111C210A2D1B25213C122F37091A32_ = 1;
            } else {
                $_obfuscated_0D21383D2A38065B111C210A2D1B25213C122F37091A32_ = 0;
            }
            $db->insert("vncp_lxc_ct", ["user_id" => $userid, "node" => $node, "os" => $osfriendly, "hb_account_id" => $hbid, "pool_id" => $poolid, "pool_password" => _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_($_obfuscated_0D022E101B3E1C070C1614132717321E3511032F1A0211_), "ip" => $ipv4[0], "suspended" => 0, "allow_backups" => $_obfuscated_0D21383D2A38065B111C210A2D1B25213C122F37091A32_, "fw_enabled_net0" => 0, "fw_enabled_net1" => 0, "has_net1" => 0, "tuntap" => 0, "onboot" => 0, "quotas" => 0]);
            $db->insert("vncp_ct_backups", ["userid" => $userid, "hb_account_id" => $hbid, "backuplimit" => (int) $backuplimit]);
            $_obfuscated_0D241319130F101D3C3621111A342626192B2319103201_ = new DateTime();
            $_obfuscated_0D241319130F101D3C3621111A342626192B2319103201_->add(new DateInterval("P30D"));
            $_obfuscated_0D3B2C0419090B291B240E0B1B223F333740281E121201_ = $_obfuscated_0D241319130F101D3C3621111A342626192B2319103201_->format("Y-m-d 00:00:00");
            $db->insert("vncp_bandwidth_monitor", ["node" => $node, "pool_id" => $poolid, "hb_account_id" => $hbid, "ct_type" => "lxc", "current" => 0, "max" => (int) $bwlimit * 1073741824, "reset_date" => $_obfuscated_0D3B2C0419090B291B240E0B1B223F333740281E121201_, "suspended" => 0]);
            $_obfuscated_0D13073833221A305C403F231F13081C1D131B0C162201_ = explode(".", $ipv4[2]);
            $db->insert("vncp_dhcp", ["mac_address" => $_obfuscated_0D403526012A2840342B055C0601382405392D142F0732_, "ip" => $ipv4[0], "gateway" => $ipv4[2], "netmask" => $ipv4[1], "network" => $_obfuscated_0D13073833221A305C403F231F13081C1D131B0C162201_[0] . "." . $_obfuscated_0D13073833221A305C403F231F13081C1D131B0C162201_[1] . "." . $_obfuscated_0D13073833221A305C403F231F13081C1D131B0C162201_[2] . "." . (string) ((int) $_obfuscated_0D13073833221A305C403F231F13081C1D131B0C162201_[3] - 1), "type" => 0]);
            if ($nat == "on") {
                $db->insert("vncp_natforwarding", ["user_id" => $userid, "node" => $node, "hb_account_id" => $hbid, "avail_ports" => $natp, "ports" => "", "avail_domains" => $natd, "domains" => ""]);
            }
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] = 1;
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "success";
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"] = ["success"];
            if ($pw == -1) {
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"][] = $pw;
            }
        }
    } else {
        $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Could not connect to Proxmox node";
    }
    return $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_;
}
function _obfuscated_0737362909122B0402120A295B150D01_($db, $userid, $node, $osfriendly, $ostype, $hbid, $poolid, $hostname, $storage, $cpu, $ram, $nicdriver, $cputype, $strdriver, $osinstalltype, $ostemp, $bwlimit, $email, $pw, $nat, $natp, $natd, $vlantag, $portspeed, $backuplimit)
{
    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_ = ["success" => 0, "message" => "createKVM failed", "data" => []];
    $_obfuscated_0D1736131B21195C2338363F3D35012A3E251B3B3E2C11_ = $db->get("vncp_users", ["id", "=", $userid])->all();
    if (count($_obfuscated_0D1736131B21195C2338363F3D35012A3E251B3B3E2C11_) < 1) {
        $_obfuscated_0D0B0A3F095C162B27341F0F310F2A0B242F162E0E2211_ = Hash::salt(32);
        $_obfuscated_0D22193F1E5C1529280A2908182E310E16302B5B040E32_ = parse_input($db->get("vncp_settings", ["item", "=", "default_language"])->first()->value);
        $db->insert("vncp_users", ["id" => (int) $userid, "email" => strtolower($email), "username" => strtolower($email), "password" => Hash::make($pw, $_obfuscated_0D0B0A3F095C162B27341F0F310F2A0B242F162E0E2211_), "salt" => $_obfuscated_0D0B0A3F095C162B27341F0F310F2A0B242F162E0E2211_, "tfa_enabled" => 0, "tfa_secret" => "", "group" => 1, "locked" => 0, "language" => $_obfuscated_0D22193F1E5C1529280A2908182E310E16302B5B040E32_]);
    } else {
        $pw = -1;
    }
    $_obfuscated_0D103D1525302B263E2507302A042B0F14121810250411_ = "";
    $natp = (int) $natp;
    $natd = (int) $natd;
    if ($nat == "on") {
        $_obfuscated_0D103D1525302B263E2507302A042B0F14121810250411_ = $db->get("vncp_nat", ["node", "=", $node])->all();
        if (count($_obfuscated_0D103D1525302B263E2507302A042B0F14121810250411_) != 1) {
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Could not create NAT VPS. Selected node is not NAT-enabled.";
            return $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_;
        }
        if (empty($natp) || $natp < 1 || 30 < $natp) {
            $natp = 20;
        }
        if (empty($natd) || $natd < 0 || 15 < $natd) {
            $natd = 5;
        }
    }
    if ($osinstalltype == "iso") {
        $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_ = $db->get("vncp_nodes", ["name", "=", $node]);
        $_obfuscated_0D15143E01211C2C315B3330012F1F1B09150330115C11_ = $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->first();
        $pxAPI = new PVE2_API($_obfuscated_0D15143E01211C2C315B3330012F1F1B09150330115C11_->hostname, $_obfuscated_0D15143E01211C2C315B3330012F1F1B09150330115C11_->username, $_obfuscated_0D15143E01211C2C315B3330012F1F1B09150330115C11_->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($_obfuscated_0D15143E01211C2C315B3330012F1F1B09150330115C11_->password));
        $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = false;
        if (!$pxAPI->login()) {
            $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = true;
        }
        if (!$_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_) {
            $_obfuscated_0D022E101B3E1C070C1614132717321E3511032F1A0211_ = _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(12);
            $_obfuscated_0D0A0F132B1A2E2B3603172932241940191E15051B1611_ = $pxAPI->post("/pools", ["poolid" => $poolid]);
            sleep(1);
            $_obfuscated_0D1911182637182634243B322D321C29130B305C2D2E32_ = $pxAPI->post("/access/users", ["userid" => $poolid . "@pve", "password" => $_obfuscated_0D022E101B3E1C070C1614132717321E3511032F1A0211_]);
            sleep(1);
            $_obfuscated_0D251A081037120B34110B192E5B1A1A071B2410330B32_ = $pxAPI->put("/access/acl", ["path" => "/pool/" . $poolid, "users" => $poolid . "@pve", "roles" => "PVEVMUser"]);
            sleep(1);
            $_obfuscated_0D3B0523261B2A0838143421242B2C32155C2A10283632_ = [];
            $_obfuscated_0D3707240C351F3F132E1C112D1C3D0E101C2133325C22_ = $pxAPI->get("/nodes/" . Input::get("node") . "/qemu");
            for ($i = 0; $i < count($_obfuscated_0D3707240C351F3F132E1C112D1C3D0E101C2133325C22_); $i++) {
                $_obfuscated_0D3B0523261B2A0838143421242B2C32155C2A10283632_[] = (int) $_obfuscated_0D3707240C351F3F132E1C112D1C3D0E101C2133325C22_[$i]["vmid"];
            }
            $_obfuscated_0D111E0E3B31375C37251E35261F22131E063C2A093701_ = $pxAPI->get("/nodes/" . Input::get("node") . "/lxc");
            for ($i = 0; $i < count($_obfuscated_0D111E0E3B31375C37251E35261F22131E063C2A093701_); $i++) {
                $_obfuscated_0D3B0523261B2A0838143421242B2C32155C2A10283632_[] = (int) $_obfuscated_0D111E0E3B31375C37251E35261F22131E063C2A093701_[$i]["vmid"];
            }
            $_obfuscated_0D343326172C022640051D3E081333381B103B04110601_ = array_keys($_obfuscated_0D3B0523261B2A0838143421242B2C32155C2A10283632_, max($_obfuscated_0D3B0523261B2A0838143421242B2C32155C2A10283632_));
            $_obfuscated_0D343326172C022640051D3E081333381B103B04110601_ = (int) $_obfuscated_0D3B0523261B2A0838143421242B2C32155C2A10283632_[$_obfuscated_0D343326172C022640051D3E081333381B103B04110601_[0]] + 1;
            sleep(1);
            if ($strdriver == "ide") {
                $_obfuscated_0D38392D3E0D1F130B2211372608245C2433310E301322_ = "ide0";
                $_obfuscated_0D132213022E1D343504033C2C1E31352F0A132D2A0732_ = "std";
            } else {
                $_obfuscated_0D38392D3E0D1F130B2211372608245C2433310E301322_ = "virtio0";
                $_obfuscated_0D132213022E1D343504033C2C1E31352F0A132D2A0732_ = "cirrus";
            }
            $_obfuscated_0D403526012A2840342B055C0601382405392D142F0732_ = MacAddress::generateMacAddress();
            $ipv4 = _obfuscated_0D11211B35050A09315B252E3F181023151E1B2C262711_($db, $node, $userid, $hbid, $nat);
            $_obfuscated_0D0A0E28333F3104083F182313231A2A2E3C2D163C1311_ = _obfuscated_0D220D0B331B2B11083B012F27083D101638080A292F11_($db, $osfriendly);
            $_obfuscated_0D2E3C2A1422383227263722123E3B3924061835343E11_ = _obfuscated_0D15250B03270A2D5B2337313626041505272A182B3B22_($node, $pxAPI);
            if ($_obfuscated_0D0A0E28333F3104083F182313231A2A2E3C2D163C1311_) {
                $_obfuscated_0D022B0C333B10361004050B2D0D141B0E33053E043811_ = ["vmid" => (int) $_obfuscated_0D343326172C022640051D3E081333381B103B04110601_, "agent" => 0, "acpi" => 1, "balloon" => (int) $ram, "boot" => "cdn", "bootdisk" => $_obfuscated_0D38392D3E0D1F130B2211372608245C2433310E301322_, "cores" => (int) $cpu, "cpu" => $cputype, "cpulimit" => "0", "cpuunits" => 1024, "description" => $ipv4[0], "hotplug" => "1", "ide2" => $_obfuscated_0D0A0E28333F3104083F182313231A2A2E3C2D163C1311_ . ",media=cdrom", "kvm" => 1, "localtime" => 1, "memory" => (int) $ram, "name" => $hostname, "numa" => 0, "onboot" => 0, "ostype" => "other", "pool" => $poolid, "protection" => 0, "reboot" => 1, "sockets" => 1, "storage" => $_obfuscated_0D2E3C2A1422383227263722123E3B3924061835343E11_, "tablet" => 1, "template" => 0, "vga" => $_obfuscated_0D132213022E1D343504033C2C1E31352F0A132D2A0732_];
                if ($nat == "on") {
                    $_obfuscated_0D022B0C333B10361004050B2D0D141B0E33053E043811_["net0"] = "bridge=vmbr10," . $nicdriver . "=" . $_obfuscated_0D403526012A2840342B055C0601382405392D142F0732_;
                } else {
                    $_obfuscated_0D022B0C333B10361004050B2D0D141B0E33053E043811_["net0"] = "bridge=vmbr0," . $nicdriver . "=" . $_obfuscated_0D403526012A2840342B055C0601382405392D142F0732_;
                }
                if (!empty($portspeed) && 0 < (int) $portspeed && (int) $portspeed < 10001) {
                    $_obfuscated_0D022B0C333B10361004050B2D0D141B0E33053E043811_["net0"] = $_obfuscated_0D022B0C333B10361004050B2D0D141B0E33053E043811_["net0"] . ",rate=" . (string) $portspeed;
                }
                if (!empty($vlantag) && 0 < (int) $vlantag && (int) $vlantag < 4095) {
                    $_obfuscated_0D022B0C333B10361004050B2D0D141B0E33053E043811_["net0"] = $_obfuscated_0D022B0C333B10361004050B2D0D141B0E33053E043811_["net0"] . ",tag=" . (string) $vlantag;
                }
                if ($strdriver == "ide") {
                    $_obfuscated_0D022B0C333B10361004050B2D0D141B0E33053E043811_["ide0"] = $_obfuscated_0D2E3C2A1422383227263722123E3B3924061835343E11_ . ":" . $storage . ",cache=writeback";
                } else {
                    $_obfuscated_0D022B0C333B10361004050B2D0D141B0E33053E043811_["virtio0"] = $_obfuscated_0D2E3C2A1422383227263722123E3B3924061835343E11_ . ":" . $storage . ",cache=writeback";
                }
                $_obfuscated_0D3F081A03095C13180D372F3F280D053E0A2C0F371311_ = $pxAPI->post("/nodes/" . $node . "/qemu", $_obfuscated_0D022B0C333B10361004050B2D0D141B0E33053E043811_);
                if (!$_obfuscated_0D3F081A03095C13180D372F3F280D053E0A2C0F371311_) {
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Could not create KVM. Proxmox API returned error";
                } else {
                    $_obfuscated_0D311B04231E3D3D0E1A351D1333401D10330E083E1911_ = parse_input($db->get("vncp_settings", ["item", "=", "enable_backups"])->first()->value);
                    $_obfuscated_0D21383D2A38065B111C210A2D1B25213C122F37091A32_ = -1;
                    if ($_obfuscated_0D311B04231E3D3D0E1A351D1333401D10330E083E1911_ == "true") {
                        $_obfuscated_0D21383D2A38065B111C210A2D1B25213C122F37091A32_ = 1;
                    } else {
                        $_obfuscated_0D21383D2A38065B111C210A2D1B25213C122F37091A32_ = 0;
                    }
                    $db->insert("vncp_kvm_ct", ["user_id" => $userid, "node" => $node, "os" => explode("/", $_obfuscated_0D0A0E28333F3104083F182313231A2A2E3C2D163C1311_)[1], "hb_account_id" => $hbid, "pool_id" => $poolid, "pool_password" => _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_($_obfuscated_0D022E101B3E1C070C1614132717321E3511032F1A0211_), "ip" => $ipv4[0], "suspended" => 0, "allow_backups" => $_obfuscated_0D21383D2A38065B111C210A2D1B25213C122F37091A32_, "fw_enabled_net0" => 0, "fw_enabled_net1" => 0, "has_net1" => 0, "onboot" => 0, "cloud_account_id" => 0, "cloud_hostname" => "", "from_template" => 0]);
                    $db->insert("vncp_ct_backups", ["userid" => $userid, "hb_account_id" => $hbid, "backuplimit" => (int) $backuplimit]);
                    $_obfuscated_0D241319130F101D3C3621111A342626192B2319103201_ = new DateTime();
                    $_obfuscated_0D241319130F101D3C3621111A342626192B2319103201_->add(new DateInterval("P30D"));
                    $_obfuscated_0D3B2C0419090B291B240E0B1B223F333740281E121201_ = $_obfuscated_0D241319130F101D3C3621111A342626192B2319103201_->format("Y-m-d 00:00:00");
                    $db->insert("vncp_bandwidth_monitor", ["node" => $node, "pool_id" => $poolid, "hb_account_id" => $hbid, "ct_type" => "qemu", "current" => 0, "max" => (int) $bwlimit * 1073741824, "reset_date" => $_obfuscated_0D3B2C0419090B291B240E0B1B223F333740281E121201_, "suspended" => 0]);
                    $_obfuscated_0D13073833221A305C403F231F13081C1D131B0C162201_ = explode(".", $ipv4[2]);
                    $_obfuscated_0D16013E2E06233515362916112B0E3B3D2E3C033E3622_ = $_obfuscated_0D13073833221A305C403F231F13081C1D131B0C162201_[0] . "." . $_obfuscated_0D13073833221A305C403F231F13081C1D131B0C162201_[1] . "." . $_obfuscated_0D13073833221A305C403F231F13081C1D131B0C162201_[2] . "." . (string) ((int) $_obfuscated_0D13073833221A305C403F231F13081C1D131B0C162201_[3] - 1);
                    $db->insert("vncp_dhcp", ["mac_address" => $_obfuscated_0D403526012A2840342B055C0601382405392D142F0732_, "ip" => $ipv4[0], "gateway" => $ipv4[2], "netmask" => $ipv4[1], "network" => $_obfuscated_0D16013E2E06233515362916112B0E3B3D2E3C033E3622_, "type" => 0]);
                    if ($nat == "on") {
                        $db->insert("vncp_natforwarding", ["user_id" => $userid, "node" => $node, "hb_account_id" => $hbid, "avail_ports" => $natp, "ports" => "", "avail_domains" => $natd, "domains" => ""]);
                    }
                    $_obfuscated_0D341A223F1B392B211E1D403B281638372E3B253E2C32_ = $db->get("vncp_dhcp", ["network", "=", $_obfuscated_0D16013E2E06233515362916112B0E3B3D2E3C033E3622_])->all();
                    if ($_obfuscated_0D1F3338311D0F292E1E151918170F331C0412111C1922_ = $db->get("vncp_dhcp_servers", ["dhcp_network", "=", $_obfuscated_0D16013E2E06233515362916112B0E3B3D2E3C033E3622_])->first()) {
                        $_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_ = new phpseclib\Net\SSH2($_obfuscated_0D1F3338311D0F292E1E151918170F331C0412111C1922_->hostname, (int) $_obfuscated_0D1F3338311D0F292E1E151918170F331C0412111C1922_->port);
                        if ($_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_->login("root", _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($_obfuscated_0D1F3338311D0F292E1E151918170F331C0412111C1922_->password))) {
                            $_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_->exec("printf 'ddns-update-style none;\n\n' > /root/dhcpd.test");
                            $_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_->exec("printf 'option domain-name-servers 8.8.8.8, 8.8.4.4;\n\n' >> /root/dhcpd.test");
                            $_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_->exec("printf 'default-lease-time 7200;\n' >> /root/dhcpd.test");
                            $_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_->exec("printf 'max-lease-time 86400;\n\n' >> /root/dhcpd.test");
                            $_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_->exec("printf 'log-facility local7;\n\n' >> /root/dhcpd.test");
                            $_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_->exec("printf 'subnet " . $_obfuscated_0D16013E2E06233515362916112B0E3B3D2E3C033E3622_ . " netmask " . $_obfuscated_0D341A223F1B392B211E1D403B281638372E3B253E2C32_[0]->netmask . " {}\n\n' >> /root/dhcpd.test");
                            for ($i = 0; $i < count($_obfuscated_0D341A223F1B392B211E1D403B281638372E3B253E2C32_); $i++) {
                                $_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_->exec("printf 'host " . $_obfuscated_0D341A223F1B392B211E1D403B281638372E3B253E2C32_[$i]->id . " {hardware ethernet " . $_obfuscated_0D341A223F1B392B211E1D403B281638372E3B253E2C32_[$i]->mac_address . ";fixed-address " . $_obfuscated_0D341A223F1B392B211E1D403B281638372E3B253E2C32_[$i]->ip . ";option routers " . $_obfuscated_0D341A223F1B392B211E1D403B281638372E3B253E2C32_[$i]->gateway . ";}\n' >> /root/dhcpd.test");
                            }
                            $_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_->exec("mv /root/dhcpd.test /etc/dhcp/dhcpd.conf && rm /root/dhcpd.test");
                            $_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_->exec("service isc-dhcp-server restart");
                            $_obfuscated_0D031C3D0F09072D0B322A042F1B12320B17382A092F22_->disconnect();
                        }
                    }
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] = 1;
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "success";
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"] = ["success"];
                    if ($pw == -1) {
                        $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"][] = $pw;
                    }
                }
            } else {
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Could not find ISO file";
            }
        } else {
            $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Could not connect to Proxmox node";
        }
    } else {
        if ($osinstalltype == "template") {
            $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_ = $db->get("vncp_nodes", ["name", "=", $node]);
            $_obfuscated_0D15143E01211C2C315B3330012F1F1B09150330115C11_ = $_obfuscated_0D0D365C10173F041537173C39050D142F3C183C322E01_->first();
            $pxAPI = new PVE2_API($_obfuscated_0D15143E01211C2C315B3330012F1F1B09150330115C11_->hostname, $_obfuscated_0D15143E01211C2C315B3330012F1F1B09150330115C11_->username, $_obfuscated_0D15143E01211C2C315B3330012F1F1B09150330115C11_->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($_obfuscated_0D15143E01211C2C315B3330012F1F1B09150330115C11_->password));
            $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = false;
            if (!$pxAPI->login()) {
                $_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_ = true;
            }
            if (!$_obfuscated_0D37040E343D032E1A0F38192904330C0928170E363232_) {
                $_obfuscated_0D022E101B3E1C070C1614132717321E3511032F1A0211_ = _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(12);
                $_obfuscated_0D2A1C0E3F05371E0440241B3D0C2C37221D3C060F0632_ = $pw;
                $_obfuscated_0D0A0F132B1A2E2B3603172932241940191E15051B1611_ = $pxAPI->post("/pools", ["poolid" => $poolid]);
                sleep(1);
                $_obfuscated_0D1911182637182634243B322D321C29130B305C2D2E32_ = $pxAPI->post("/access/users", ["userid" => $poolid . "@pve", "password" => $_obfuscated_0D022E101B3E1C070C1614132717321E3511032F1A0211_]);
                sleep(1);
                $_obfuscated_0D251A081037120B34110B192E5B1A1A071B2410330B32_ = $pxAPI->put("/access/acl", ["path" => "/pool/" . $poolid, "users" => $poolid . "@pve", "roles" => "PVEVMUser"]);
                sleep(1);
                $_obfuscated_0D3B0523261B2A0838143421242B2C32155C2A10283632_ = [];
                $_obfuscated_0D3707240C351F3F132E1C112D1C3D0E101C2133325C22_ = $pxAPI->get("/nodes/" . $node . "/qemu");
                for ($i = 0; $i < count($_obfuscated_0D3707240C351F3F132E1C112D1C3D0E101C2133325C22_); $i++) {
                    $_obfuscated_0D3B0523261B2A0838143421242B2C32155C2A10283632_[] = (int) $_obfuscated_0D3707240C351F3F132E1C112D1C3D0E101C2133325C22_[$i]["vmid"];
                }
                $_obfuscated_0D111E0E3B31375C37251E35261F22131E063C2A093701_ = $pxAPI->get("/nodes/" . $node . "/lxc");
                for ($i = 0; $i < count($_obfuscated_0D111E0E3B31375C37251E35261F22131E063C2A093701_); $i++) {
                    $_obfuscated_0D3B0523261B2A0838143421242B2C32155C2A10283632_[] = (int) $_obfuscated_0D111E0E3B31375C37251E35261F22131E063C2A093701_[$i]["vmid"];
                }
                $_obfuscated_0D343326172C022640051D3E081333381B103B04110601_ = array_keys($_obfuscated_0D3B0523261B2A0838143421242B2C32155C2A10283632_, max($_obfuscated_0D3B0523261B2A0838143421242B2C32155C2A10283632_));
                $_obfuscated_0D343326172C022640051D3E081333381B103B04110601_ = (int) $_obfuscated_0D3B0523261B2A0838143421242B2C32155C2A10283632_[$_obfuscated_0D343326172C022640051D3E081333381B103B04110601_[0]] + 1;
                sleep(1);
                $ipv4 = _obfuscated_0D11211B35050A09315B252E3F181023151E1B2C262711_($db, $node, $userid, $hbid, $nat);
                $_obfuscated_0D2E3C2A1422383227263722123E3B3924061835343E11_ = _obfuscated_0D15250B03270A2D5B2337313626041505272A182B3B22_($node, $pxAPI);
                $_obfuscated_0D022B0C333B10361004050B2D0D141B0E33053E043811_ = ["newid" => (int) $_obfuscated_0D343326172C022640051D3E081333381B103B04110601_, "description" => $ipv4[0], "format" => "qcow2", "full" => 1, "name" => $hostname, "pool" => $poolid, "storage" => $_obfuscated_0D2E3C2A1422383227263722123E3B3924061835343E11_];
                if (!empty($vlantag) && 0 < (int) $vlantag && (int) $vlantag < 4095) {
                    $vlantag = $vlantag;
                } else {
                    $vlantag = "0";
                }
                if (!empty($portspeed) && 0 < (int) $portspeed && (int) $portspeed < 10001) {
                    $portspeed = $portspeed;
                } else {
                    $portspeed = -1;
                }
                $_obfuscated_0D171A0F370A19170508100C37133F1C1B0C2801111E32_ = $db->get("vncp_kvm_templates", ["friendly_name", "=", $ostemp])->all();
                $i = 0;
                while ($i < count($_obfuscated_0D171A0F370A19170508100C37133F1C1B0C2801111E32_)) {
                    if ($_obfuscated_0D171A0F370A19170508100C37133F1C1B0C2801111E32_[$i]->node == $node) {
                        $_obfuscated_0D171A0F370A19170508100C37133F1C1B0C2801111E32_ = $_obfuscated_0D171A0F370A19170508100C37133F1C1B0C2801111E32_[$i];
                    } else {
                        $i++;
                    }
                }
                $_obfuscated_0D3F081A03095C13180D372F3F280D053E0A2C0F371311_ = $pxAPI->post("/nodes/" . $node . "/qemu/" . $_obfuscated_0D171A0F370A19170508100C37133F1C1B0C2801111E32_->vmid . "/clone", $_obfuscated_0D022B0C333B10361004050B2D0D141B0E33053E043811_);
                if (!$_obfuscated_0D3F081A03095C13180D372F3F280D053E0A2C0F371311_) {
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Could not create KVM. Proxmox API returned error";
                } else {
                    $db->insert("vncp_pending_clone", ["node" => $node, "upid" => $_obfuscated_0D3F081A03095C13180D372F3F280D053E0A2C0F371311_, "hb_account_id" => $hbid, "data" => json_encode(["vmid" => $_obfuscated_0D343326172C022640051D3E081333381B103B04110601_, "cores" => $cpu, "cpu" => $cputype, "memory" => $ram, "cipassword" => _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_($_obfuscated_0D2A1C0E3F05371E0440241B3D0C2C37221D3C060F0632_), "storage_size" => $storage, "cvmtype" => $_obfuscated_0D171A0F370A19170508100C37133F1C1B0C2801111E32_->type, "gateway" => $ipv4[2], "ip" => $ipv4[0], "netmask" => $ipv4[1], "portspeed" => $portspeed, "vlantag" => $vlantag])]);
                    $_obfuscated_0D311B04231E3D3D0E1A351D1333401D10330E083E1911_ = parse_input($db->get("vncp_settings", ["item", "=", "enable_backups"])->first()->value);
                    $_obfuscated_0D21383D2A38065B111C210A2D1B25213C122F37091A32_ = -1;
                    if ($_obfuscated_0D311B04231E3D3D0E1A351D1333401D10330E083E1911_ == "true") {
                        $_obfuscated_0D21383D2A38065B111C210A2D1B25213C122F37091A32_ = 1;
                    } else {
                        $_obfuscated_0D21383D2A38065B111C210A2D1B25213C122F37091A32_ = 0;
                    }
                    $db->insert("vncp_kvm_ct", ["user_id" => $userid, "node" => $node, "os" => $_obfuscated_0D171A0F370A19170508100C37133F1C1B0C2801111E32_->friendly_name, "hb_account_id" => $hbid, "pool_id" => $poolid, "pool_password" => _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_($_obfuscated_0D022E101B3E1C070C1614132717321E3511032F1A0211_), "ip" => $ipv4[0], "suspended" => 0, "allow_backups" => $_obfuscated_0D21383D2A38065B111C210A2D1B25213C122F37091A32_, "fw_enabled_net0" => 0, "fw_enabled_net1" => 0, "has_net1" => 0, "onboot" => 0, "cloud_account_id" => 0, "cloud_hostname" => "", "from_template" => 1]);
                    $db->insert("vncp_ct_backups", ["userid" => $userid, "hb_account_id" => $hbid, "backuplimit" => (int) $backuplimit]);
                    $_obfuscated_0D241319130F101D3C3621111A342626192B2319103201_ = new DateTime();
                    $_obfuscated_0D241319130F101D3C3621111A342626192B2319103201_->add(new DateInterval("P30D"));
                    $_obfuscated_0D3B2C0419090B291B240E0B1B223F333740281E121201_ = $_obfuscated_0D241319130F101D3C3621111A342626192B2319103201_->format("Y-m-d 00:00:00");
                    $db->insert("vncp_bandwidth_monitor", ["node" => $node, "pool_id" => $poolid, "hb_account_id" => $hbid, "ct_type" => "qemu", "current" => 0, "max" => (int) $bwlimit * 1073741824, "reset_date" => $_obfuscated_0D3B2C0419090B291B240E0B1B223F333740281E121201_, "suspended" => 0]);
                    if ($nat == "on") {
                        $db->insert("vncp_natforwarding", ["user_id" => $userid, "node" => $node, "hb_account_id" => $hbid, "avail_ports" => $natp, "ports" => "", "avail_domains" => $natd, "domains" => ""]);
                    }
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["success"] = 1;
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "success";
                    $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"] = ["success"];
                    if ($pw == -1) {
                        $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["data"][] = $pw;
                    }
                }
            } else {
                $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_["message"] = "Could not connect to Proxmox node";
            }
        }
    }
    return $_obfuscated_0D1428025B372C1F33152325290F3F362C2C1D03141801_;
}

?>
