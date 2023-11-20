<?php

require_once "vendor/autoload.php";
require_once "core/autoload.php";
require_once "core/init.php";
require_once "core/session.php";
$user = new User();
if (!$user->isLoggedIn()) {
    exit("User not authenticated.");
}
if (!isset($_GET["id"]) || !isset($_GET["virt"])) {
    exit("Verification error.");
}
$db = DB::getInstance();
if ($_GET["virt"] == "lxc") {
    $results = $db->get("vncp_lxc_ct", ["hb_account_id", "=", $_GET["id"]]);
    $type = "lxc";
} else {
    if ($_GET["virt"] == "kvm") {
        $results = $db->get("vncp_kvm_ct", ["hb_account_id", "=", $_GET["id"]]);
        $type = "kvm";
    }
}
$data = $results->first();
if ($data->user_id != $user->data()->id) {
    exit("Verification error.");
}
$noLogin = false;
$nodename = $data->node;
$node_results = $db->get("vncp_nodes", ["name", "=", $nodename]);
$node_data = $node_results->first();
$pxAPI = new PVE2_API($node_data->hostname, $node_data->username, $node_data->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($node_data->password));
if (!$pxAPI->login()) {
    $noLogin = true;
}
if (!$noLogin) {
    $vminfo = $pxAPI->get("/pools/" . $data->pool_id);
    if (count($vminfo["members"]) == 1) {
        $vmid = $vminfo["members"][0]["vmid"];
        if ($data->suspended == 0) {
            $console = $pxAPI->post("/access/ticket", ["username" => $data->pool_id . "@pve", "password" => _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($data->pool_password)]);
            if (!$console["ticket"]) {
                echo "No data received.";
            } else {
                echo "<iframe src=\"https://" . $node_data->hostname . ":8006/novnc/vncconsole.html?vmid=" . $vmid . "&username=" . urlencode($console["username"]) . "&host=" . $node_data->hostname . "&console=" . $type . "&vmname=" . $vminfo["members"][0]["name"] . "&node=" . $node_data->name . "&ticket=" . urlencode($console["ticket"]) . "&csrf=" . urlencode($console["CSRFPreventionToken"]) . "&resize=scale\" name=\"vncconsole-frame\" style=\"overflow:hidden;height:100%;width:100%;position:absolute;top:0px;left:0px;right:0px;bottom:0px;\" height=\"100%\" width=\"100%\" frameborder=\"0\" allowfullscreen=\"true\"></iframe>";
            }
        } else {
            echo "This VM is suspended. VNC access is not available.";
        }
    } else {
        $j = 0;
        while ($j < count($vminfo["members"])) {
            if ($vminfo["members"][$j]["name"] == $data->cloud_hostname) {
                $vmid = $vminfo["members"][$j]["vmid"];
                $vmIndex = $j;
            } else {
                $j++;
            }
        }
        if ($data->suspended == 0) {
            $console = $pxAPI->post("/access/ticket", ["username" => $data->pool_id . "@pve", "password" => _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($data->pool_password)]);
            if (!$console["ticket"]) {
                echo "No data received.";
            } else {
                echo "<iframe src=\"https://" . $node_data->hostname . ":8006/novnc/vncconsole.html?vmid=" . $vmid . "&username=" . urlencode($console["username"]) . "&host=" . $node_data->hostname . "&console=" . $type . "&vmname=" . $vminfo["members"][$vmIndex]["name"] . "&node=" . $node_data->name . "&ticket=" . urlencode($console["ticket"]) . "&csrf=" . urlencode($console["CSRFPreventionToken"]) . "&resize=scale\" name=\"vncconsole-frame\" style=\"overflow:hidden;height:100%;width:100%;position:absolute;top:0px;left:0px;right:0px;bottom:0px;\" height=\"100%\" width=\"100%\" frameborder=\"0\" allowfullscreen=\"true\"></iframe>";
            }
        } else {
            echo "This VM is suspended. VNC access is not available.";
        }
    }
} else {
    echo "Node cannot be reached.";
}

?>
