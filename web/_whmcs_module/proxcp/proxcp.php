<?php

if (!defined("WHMCS")) {
    exit("This file cannot be accessed directly.");
}
function proxcp_MetaData()
{
    return ["DisplayName" => "ProxCP", "APIVersion" => "1.1", "RequiresServer" => true];
}
function proxcp_ConfigOptions()
{
    return ["servicetype" => ["FriendlyName" => "ProxCP Service Type", "Type" => "dropdown", "Options" => ["kvm" => "KVM VPS", "pc" => "KVM Public Cloud", "lxc" => "LXC VPS"], "Default" => "kvm", "SimpleMode" => true], "node" => ["FriendlyName" => "Proxmox Node", "Type" => "text", "Size" => "25", "Loader" => "proxcp_LoadNodeList", "SimpleMode" => true], "storagesize" => ["FriendlyName" => "Storage Size (GB)", "Type" => "text", "Size" => "25", "SimpleMode" => true], "cpucores" => ["FriendlyName" => "CPU Cores", "Type" => "text", "Size" => "25", "SimpleMode" => true], "ram" => ["FriendlyName" => "RAM (MB)", "Type" => "text", "Size" => "25", "SimpleMode" => true], "nicdriver" => ["FriendlyName" => "NIC Driver", "Type" => "dropdown", "Options" => ["e1000" => "Intel E1000", "virtio" => "VirtIO"], "Description" => "Only used for KVM products", "Default" => "ide", "SimpleMode" => true], "cputype" => ["FriendlyName" => "CPU Type", "Type" => "dropdown", "Options" => ["kvm64" => "KVM64", "qemu64" => "QEMU64", "host" => "Host passthrough"], "Description" => "Only used for KVM products", "Default" => "kvm64", "SimpleMode" => true], "storagedriver" => ["FriendlyName" => "Storage Driver", "Type" => "dropdown", "Options" => ["ide" => "IDE", "virtio" => "VirtIO"], "Description" => "Only used for KVM products", "Default" => "ide", "SimpleMode" => true], "osinstalltype" => ["FriendlyName" => "Default OS Installation Type", "Type" => "dropdown", "Options" => ["iso" => "Manual ISO file", "template" => "Automatic template"], "Description" => "Only used for KVM products", "Default" => "iso", "SimpleMode" => true], "bwlimit" => ["FriendlyName" => "Bandwidth Limit (GB)", "Type" => "text", "Size" => "25", "SimpleMode" => true], "iplimit" => ["FriendlyName" => "IP Limit", "Type" => "text", "Size" => "25", "SimpleMode" => true, "Description" => "How many IPs should be assigned to this cloud account? (KVM Cloud only)"], "isnat" => ["FriendlyName" => "NAT VPS?", "Type" => "yesno", "Description" => "Tick to make this a NAT product", "SimpleMode" => true], "natports" => ["FriendlyName" => "NAT Port Limit", "Type" => "text", "Size" => "25", "SimpleMode" => true, "Description" => "Number between 1 - 30. NAT products only"], "natdomains" => ["FriendlyName" => "NAT Domain Limit", "Type" => "text", "Size" => "25", "SimpleMode" => true, "Description" => "Number between 0 - 15. NAT products only"], "vlantag" => ["FriendlyName" => "VLAN Tag", "Type" => "text", "Size" => "25", "SimpleMode" => true, "Default" => "0", "Description" => "Add VLAN tag to VM NIC (1 - 4094). 0 = no tagging."], "portspeed" => ["FriendlyName" => "Port Speed", "Type" => "text", "Size" => "25", "SimpleMode" => true, "Default" => "0", "Description" => "Add rate limit (MB/s) to VM NIC (1 - 10000). 0 = no limit."], "backuplimit" => ["FriendlyName" => "Backup Limit", "Type" => "text", "Size" => "25", "SimpleMode" => true, "Default" => "-1", "Description" => "Override default backup limit. -1 = use default."]];
}
function proxcp_LoadNodeList($params)
{
    $post_info = "api_id=" . $params["serverusername"] . "&api_key=" . $params["serverpassword"] . "&action=getnodes";
    $api_url = $params["serverhttpprefix"] . "://" . $params["serverhostname"] . "/api.php";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_info);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "ProxCP WHMCS Module");
    $response = curl_exec($ch);
    if (curl_error($ch)) {
        throw new Exception("Unable to connect: " . curl_errno($ch) . " - " . curl_error($ch));
    }
    if (empty($response)) {
        throw new Exception("Empty response");
    }
    curl_close($ch);
    $nodeList = json_decode($response, true);
    if (is_null($nodeList) || $nodeList["success"] == 0) {
        throw new Exception("Invalid response format");
    }
    $list = [];
    foreach ($nodeList["data"] as $node) {
        $list[$node] = $node;
    }
    return $list;
}
function proxcp_CreateAccount($params)
{
    try {
        $type = $params["configoption1"];
        $email = $params["clientsdetails"]["email"];
        $email = base64_encode($email);
        $password = $params["password"];
        $userid = $params["clientsdetails"]["userid"];
        $node = $params["configoption2"];
        $osfriendly = $params["configoptions"]["Operating System"];
        $ostype = $params["configoptions"]["Operating System"];
        $hb_account_id = $params["serviceid"];
        $poolid = "client_" . $userid . "_" . $hb_account_id;
        $hostname = $params["domain"];
        $storage_size = $params["configoption3"];
        $cpucores = $params["configoption4"];
        $ram = $params["configoption5"];
        $nicdriver = $params["configoption6"];
        $cputype = $params["configoption7"];
        $storage_driver = $params["configoption8"];
        $os_installation_type = $params["configoption9"];
        $ostemplate = $params["configoptions"]["Operating System"];
        $bandwidth_limit = $params["configoption10"];
        $howmanyips = $params["configoption11"];
        $isNAT = $params["configoption12"];
        $natports = $params["configoption13"];
        $natdomains = $params["configoption14"];
        $vlantag = $params["configoption15"];
        $portspeed = $params["configoption16"];
        $backupoverride = $params["configoption17"];
        if ($isNAT != "on") {
            $isNAT = "off";
        }
        if ($type == "kvm") {
            $post_info = "api_id=" . $params["serverusername"] . "&api_key=" . $params["serverpassword"] . "&action=createkvm&userid=" . $userid . "&node=" . $node . "&osfriendly=" . $osfriendly . "&ostype=" . $ostype . "&hbid=" . $hb_account_id . "&poolid=" . $poolid . "&hostname=" . $hostname . "&storage=" . $storage_size . "&cpu=" . $cpucores . "&ram=" . $ram . "&nicdriver=" . $nicdriver . "&cputype=" . $cputype . "&strdriver=" . $storage_driver . "&osinstalltype=" . $os_installation_type . "&ostemp=" . $ostemplate . "&bwlimit=" . $bandwidth_limit . "&email=" . $email . "&pw=" . base64_encode($password) . "&nat=" . $isNAT . "&natp=" . $natports . "&natd=" . $natdomains . "&vlantag=" . $vlantag . "&portspeed=" . $portspeed . "&backuplimit=" . $backupoverride;
        } else {
            if ($type == "pc") {
                $post_info = "api_id=" . $params["serverusername"] . "&api_key=" . $params["serverpassword"] . "&action=createcloud&userid=" . $userid . "&hbid=" . $hb_account_id . "&poolid=" . $poolid . "&node=" . $node . "&howmanyips=" . $howmanyips . "&cpu=" . $cpucores . "&cputype=" . $cputype . "&ram=" . $ram . "&storage=" . $storage_size . "&email=" . $email . "&pw=" . base64_encode($password);
            } else {
                if ($type == "lxc") {
                    $post_info = "api_id=" . $params["serverusername"] . "&api_key=" . $params["serverpassword"] . "&action=createlxc&userid=" . $userid . "&node=" . $node . "&osfriendly=" . $osfriendly . "&ostype=" . $ostype . "&hbid=" . $hb_account_id . "&poolid=" . $poolid . "&hostname=" . $hostname . "&storage=" . $storage_size . "&cpu=" . $cpucores . "&ram=" . $ram . "&bwlimit=" . $bandwidth_limit . "&email=" . $email . "&pw=" . base64_encode($password) . "&nat=" . $isNAT . "&natp=" . $natports . "&natd=" . $natdomains . "&vlantag=" . $vlantag . "&portspeed=" . $portspeed . "&backuplimit=" . $backupoverride;
                } else {
                    throw new Exception("Invalid ProxCP Service Type");
                }
            }
        }
        $api_url = $params["serverhttpprefix"] . "://" . $params["serverhostname"] . "/api.php";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_info);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "ProxCP WHMCS Module");
        $response = curl_exec($ch);
        if (curl_error($ch)) {
            throw new Exception("Unable to connect: " . curl_errno($ch) . " - " . curl_error($ch));
        }
        if (empty($response)) {
            throw new Exception("Empty response");
        }
        curl_close($ch);
        $response = json_decode($response, true);
        if (is_null($response)) {
            throw new Exception("Invalid response format");
        }
        if ($response["success"] == 0) {
            throw new Exception($response["message"]);
        }
    } catch (Exception $e) {
        logModuleCall("proxcp", "proxcp_CreateAccount", $params, $e->getMessage(), $e->getTraceAsString());
        return $e->getMessage();
    }
    return "success";
}
function proxcp_SuspendAccount($params)
{
    try {
        $type = $params["configoption1"];
        $hbid = $params["serviceid"];
        $userid = $params["clientsdetails"]["userid"];
        $poolid = "client_" . $userid . "_" . $hbid;
        $api_url = $params["serverhttpprefix"] . "://" . $params["serverhostname"] . "/api.php";
        $post_info = "api_id=" . $params["serverusername"] . "&api_key=" . $params["serverpassword"] . "&action=suspend&type=" . $type . "&hbid=" . $hbid . "&poolid=" . $poolid;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_info);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "ProxCP WHMCS Module");
        $response = curl_exec($ch);
        if (curl_error($ch)) {
            throw new Exception("Unable to connect: " . curl_errno($ch) . " - " . curl_error($ch));
        }
        if (empty($response)) {
            throw new Exception("Empty response");
        }
        curl_close($ch);
        $response = json_decode($response, true);
        if (is_null($response)) {
            throw new Exception("Invalid response format");
        }
        if ($response["success"] == 0) {
            throw new Exception($response["message"]);
        }
    } catch (Exception $e) {
        logModuleCall("proxcp", "proxcp_SuspendAccount", $params, $e->getMessage(), $e->getTraceAsString());
        return $e->getMessage();
    }
    return "success";
}
function proxcp_UnsuspendAccount($params)
{
    try {
        $type = $params["configoption1"];
        $hbid = $params["serviceid"];
        $userid = $params["clientsdetails"]["userid"];
        $poolid = "client_" . $userid . "_" . $hbid;
        $api_url = $params["serverhttpprefix"] . "://" . $params["serverhostname"] . "/api.php";
        $post_info = "api_id=" . $params["serverusername"] . "&api_key=" . $params["serverpassword"] . "&action=unsuspend&type=" . $type . "&hbid=" . $hbid . "&poolid=" . $poolid;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_info);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "ProxCP WHMCS Module");
        $response = curl_exec($ch);
        if (curl_error($ch)) {
            throw new Exception("Unable to connect: " . curl_errno($ch) . " - " . curl_error($ch));
        }
        if (empty($response)) {
            throw new Exception("Empty response");
        }
        curl_close($ch);
        $response = json_decode($response, true);
        if (is_null($response)) {
            throw new Exception("Invalid response format");
        }
        if ($response["success"] == 0) {
            throw new Exception($response["message"]);
        }
    } catch (Exception $e) {
        logModuleCall("proxcp", "proxcp_UnsuspendAccount", $params, $e->getMessage(), $e->getTraceAsString());
        return $e->getMessage();
    }
    return "success";
}
function proxcp_TerminateAccount($params)
{
    try {
        $type = $params["configoption1"];
        $hbid = $params["serviceid"];
        $userid = $params["clientsdetails"]["userid"];
        $poolid = "client_" . $userid . "_" . $hbid;
        $api_url = $params["serverhttpprefix"] . "://" . $params["serverhostname"] . "/api.php";
        $post_info = "api_id=" . $params["serverusername"] . "&api_key=" . $params["serverpassword"] . "&action=terminate&type=" . $type . "&hbid=" . $hbid . "&poolid=" . $poolid . "&userid=" . $userid;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_info);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "ProxCP WHMCS Module");
        $response = curl_exec($ch);
        if (curl_error($ch)) {
            throw new Exception("Unable to connect: " . curl_errno($ch) . " - " . curl_error($ch));
        }
        if (empty($response)) {
            throw new Exception("Empty response");
        }
        curl_close($ch);
        $response = json_decode($response, true);
        if (is_null($response)) {
            throw new Exception("Invalid response format");
        }
        if ($response["success"] == 0) {
            throw new Exception($response["message"]);
        }
    } catch (Exception $e) {
        logModuleCall("proxcp", "proxcp_TerminateAccount", $params, $e->getMessage(), $e->getTraceAsString());
        return $e->getMessage();
    }
    return "success";
}
function proxcp_start($params)
{
    try {
        $type = $params["configoption1"];
        $hbid = $params["serviceid"];
        $userid = $params["clientsdetails"]["userid"];
        $api_url = $params["serverhttpprefix"] . "://" . $params["serverhostname"] . "/api.php";
        $post_info = "api_id=" . $params["serverusername"] . "&api_key=" . $params["serverpassword"] . "&action=startserver&type=" . $type . "&hbid=" . $hbid . "&userid=" . $userid;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_info);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "ProxCP WHMCS Module");
        $response = curl_exec($ch);
        if (curl_error($ch)) {
            throw new Exception("Unable to connect: " . curl_errno($ch) . " - " . curl_error($ch));
        }
        if (empty($response)) {
            throw new Exception("Empty response");
        }
        curl_close($ch);
        $response = json_decode($response, true);
        if (is_null($response)) {
            throw new Exception("Invalid response format");
        }
        if ($response["success"] == 0) {
            throw new Exception($response["message"]);
        }
    } catch (Exception $e) {
        logModuleCall("proxcp", "proxcp_start", $params, $e->getMessage(), $e->getTraceAsString());
        return $e->getMessage();
    }
    return "success";
}
function proxcp_stop($params)
{
    try {
        $type = $params["configoption1"];
        $hbid = $params["serviceid"];
        $userid = $params["clientsdetails"]["userid"];
        $api_url = $params["serverhttpprefix"] . "://" . $params["serverhostname"] . "/api.php";
        $post_info = "api_id=" . $params["serverusername"] . "&api_key=" . $params["serverpassword"] . "&action=stopserver&type=" . $type . "&hbid=" . $hbid . "&userid=" . $userid;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_info);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "ProxCP WHMCS Module");
        $response = curl_exec($ch);
        if (curl_error($ch)) {
            throw new Exception("Unable to connect: " . curl_errno($ch) . " - " . curl_error($ch));
        }
        if (empty($response)) {
            throw new Exception("Empty response");
        }
        curl_close($ch);
        $response = json_decode($response, true);
        if (is_null($response)) {
            throw new Exception("Invalid response format");
        }
        if ($response["success"] == 0) {
            throw new Exception($response["message"]);
        }
    } catch (Exception $e) {
        logModuleCall("proxcp", "proxcp_stop", $params, $e->getMessage(), $e->getTraceAsString());
        return $e->getMessage();
    }
    return "success";
}
function proxcp_ClientAreaCustomButtonArray()
{
    return ["Start Server" => "start", "Stop Server" => "stop"];
}
function proxcp_ClientArea($params)
{
    $node = "";
    $ip = "";
    try {
        $type = $params["configoption1"];
        $hbid = $params["serviceid"];
        $api_url = $params["serverhttpprefix"] . "://" . $params["serverhostname"] . "/api.php";
        $post_info = "api_id=" . $params["serverusername"] . "&api_key=" . $params["serverpassword"] . "&action=getdetails&type=" . $type . "&hbid=" . $hbid;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_info);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "ProxCP WHMCS Module");
        $response = curl_exec($ch);
        if (curl_error($ch)) {
            throw new Exception("Unable to connect: " . curl_errno($ch) . " - " . curl_error($ch));
        }
        if (empty($response)) {
            throw new Exception("Empty response");
        }
        curl_close($ch);
        $response = json_decode($response, true);
        if (is_null($response)) {
            throw new Exception("Invalid response format");
        }
        if ($response["success"] == 0) {
            throw new Exception($response["message"]);
        }
        $node = $response["data"][0];
        $ip = $response["data"][1];
        $status = $response["data"][3];
        $os = $response["data"][2];
    } catch (Exception $e) {
        logModuleCall("proxcp", "proxcp_ClientArea", $params, $e->getMessage(), $e->getTraceAsString());
        return $e->getMessage();
    }
    return ["templatefile" => "templates/clientarea", "vars" => ["node" => $node, "ip" => $ip, "status" => $status, "os" => $os]];
}

?>
