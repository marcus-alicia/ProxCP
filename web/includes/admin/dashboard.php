<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
echo "<div class=\"row\">\r\n\t<div class=\"col-md-12\">\r\n\t\t<div class=\"panel panel-default\">\r\n\t\t\t<div class=\"panel-body\">\r\n\t\t\t\t<div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Admin_-_Dashboard\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div><br />\r\n\t\t\t\t<div class=\"row\">\r\n\t\t\t\t\t";
$version_url = "https://proxcp.com/version.json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $version_url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$version_resp = curl_exec($ch);
if (curl_error($ch)) {
    echo "<div class=\"col-md-12\"><div class=\"panel panel-warning\"><div class=\"panel-heading\"><h3 class=\"panel-title\">WARNING</h3></div><div class=\"panel-body\"><p>Could not check latest version from ProxCP (warn-1).</p></div></div></div>";
} else {
    if (empty($version_resp)) {
        echo "<div class=\"col-md-12\"><div class=\"panel panel-warning\"><div class=\"panel-heading\"><h3 class=\"panel-title\">WARNING</h3></div><div class=\"panel-body\"><p>Could not check latest version from ProxCP (warn-2).</p></div></div></div>";
    } else {
        $vstring = json_decode($version_resp, true)["latest_version"];
        list($current) = _obfuscated_0D31240926250B2C5C0C5C0B360A22040F3F1C3E143632_();
        if (version_compare($current, $vstring, "<")) {
            echo "<div class=\"col-md-12\"><div class=\"panel panel-warning\"><div class=\"panel-heading\"><h3 class=\"panel-title\">New ProxCP Version Available</h3></div><div class=\"panel-body\"><p><strong><a href=\"https://docs.proxcp.com/index.php?title=ProxCP_Changelog\" target=\"_blank\">View Changelog</a></strong></p><br /><p>There is a newer ProxCP version available. Upgrade your ProxCP version to get access to the latest features and bug fixes. The upgrade package is located in your ProxCP account on the Downloads page.</p></div></div></div>";
        }
    }
}
curl_close($ch);
echo "\t\t\t\t\t";
$nodestats = $db->get("vncp_nodes", ["id", "!=", 0]);
$nodestats = $nodestats->all();
$node_count = count($nodestats);
if (0 < $node_count) {
    echo "\t\t\t\t\t<div class=\"col-md-12\">\r\n\t\t\t\t\t\t<div class=\"panel panel-info\">\r\n\t\t\t\t\t\t\t<div class=\"panel-heading\">\r\n\t\t\t\t\t\t\t\t<h3 class=\"panel-title\">Node Stats</h3>\r\n\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t<div class=\"panel-body\">\r\n\t\t\t\t\t\t\t\t<div id=\"adm_message\"></div>\r\n\t\t\t\t\t\t\t    <select class=\"form-control\" id=\"selectnodestats\">\r\n\t\t\t\t\t\t\t    \t<option value=\"default\">Select...</option>\r\n\t\t\t\t\t\t\t    \t";
    for ($k = 0; $k < $node_count; $k++) {
        echo "<option value=\"" . $nodestats[$k]->name . "\">" . $nodestats[$k]->hostname . " (" . $nodestats[$k]->name . ")</option>";
    }
    echo "\t\t\t\t\t\t\t    </select>\r\n\t\t\t\t\t\t\t\t<h1 align=\"center\" id=\"admin_nodestatus\">Server Status: <span class=\"label\" id=\"admin_nodestatus2\"><img src=\"img/loader.GIF\" id=\"loader\" /></span></h1>\r\n\t\t\t\t\t\t\t\t<div class=\"col-md-2\"><p><em>CPU Usage</em></p></div>\r\n\t\t\t\t\t\t\t\t<div class=\"progress\">\r\n\t\t\t\t\t\t\t\t\t<div class=\"progress-bar progress-bar-info progress-bar-striped\" role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"min-width: 2em;width: 100%;\" id=\"admin_cpu_1\"><div id=\"admin_cpu_2\"></div></div>\r\n\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t<div class=\"col-md-2\"><p><em>RAM Usage</em></p></div>\r\n\t\t\t\t\t\t\t\t<div class=\"progress\">\r\n\t\t\t\t\t\t\t\t\t<div class=\"progress-bar progress-bar-info progress-bar-striped\" role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"min-width: 2em;width: 100%;\" id=\"admin_ram_1\"><div id=\"admin_ram_2\"></div></div>\r\n\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t<div class=\"col-md-2\"><p><em>Disk Usage</em></p></div>\r\n\t\t\t\t\t\t\t\t<div class=\"progress\">\r\n\t\t\t\t\t\t\t\t\t<div class=\"progress-bar progress-bar-info progress-bar-striped\" role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"min-width: 2em;width: 100%;\" id=\"admin_disk_1\"><div id=\"admin_disk_2\"></div></div>\r\n\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t<div class=\"col-md-2\"><p><em>Swap Usage</em></p></div>\r\n\t\t\t\t\t\t\t\t<div class=\"progress\">\r\n\t\t\t\t\t\t\t\t\t<div class=\"progress-bar progress-bar-info progress-bar-striped\" role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"min-width: 2em;width: 100%;\" id=\"admin_swap_1\"><div id=\"admin_swap_2\"></div></div>\r\n\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t\t<div class=\"table-responsive\">\r\n\t\t\t\t\t\t\t\t    <table class=\"table table-striped\">\r\n\t\t\t\t\t\t\t\t        <tr>\r\n\t\t\t\t\t\t\t\t            <td>Uptime</td>\r\n\t\t\t\t\t\t\t\t            <td id=\"node_uptime\">null</td>\r\n\t\t\t\t\t\t\t\t        </tr>\r\n\t\t\t\t\t\t\t\t        <tr>\r\n\t\t\t\t\t\t\t\t            <td>Load Avg</td>\r\n\t\t\t\t\t\t\t\t            <td id=\"node_loadavg\">null</td>\r\n\t\t\t\t\t\t\t\t        </tr>\r\n\t\t\t\t\t\t\t\t        <tr>\r\n\t\t\t\t\t\t\t\t            <td>Kernel Version</td>\r\n\t\t\t\t\t\t\t\t            <td id=\"node_kernel\">null</td>\r\n\t\t\t\t\t\t\t\t        </tr>\r\n\t\t\t\t\t\t\t\t        <tr>\r\n\t\t\t\t\t\t\t\t            <td>PVE Version</td>\r\n\t\t\t\t\t\t\t\t            <td id=\"node_pve\">null</td>\r\n\t\t\t\t\t\t\t\t        </tr>\r\n\t\t\t\t\t\t\t\t        <tr>\r\n\t\t\t\t\t\t\t\t        \t<td>CPU Model</td>\r\n\t\t\t\t\t\t\t\t        \t<td id=\"node_cpumod\">null</td>\r\n\t\t\t\t\t\t\t\t        </tr>\r\n\t\t\t\t\t\t\t\t    </table>\r\n\t\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t</div>\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t";
} else {
    echo "\t\t\t\t\t<div class=\"col-md-12\">\r\n\t\t\t\t\t\t<div class=\"panel panel-warning\">\r\n\t\t\t\t\t\t\t<div class=\"panel-heading\">\r\n\t\t\t\t\t\t\t\t<h3 class=\"panel-title\">Add A Node</h3>\r\n\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t<div class=\"panel-body\">\r\n\t\t\t\t\t\t\t\t<p>Node status will appear here. No nodes found. <a href=\"admin?action=nodes\">Click here</a> to add a new node.</p>\r\n\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t</div>\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t";
}
echo "\t\t\t\t</div>\r\n\t\t\t\t<div class=\"row\">\r\n\t\t\t\t\t<div class=\"col-md-6\">\r\n\t\t\t\t\t\t<div class=\"panel panel-info\">\r\n\t\t\t\t\t\t\t<div class=\"panel-heading\">\r\n\t\t\t\t\t\t\t\t<h3 class=\"panel-title\">LXC VPS Stats</h3>\r\n\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t<div class=\"panel-body\">\r\n\t\t\t\t\t\t\t\t";
$lxccount = $db->get("vncp_lxc_ct", ["user_id", "!=", 0]);
$lxccount = $lxccount->all();
$lxccount_s = $db->get("vncp_lxc_ct", ["suspended", "=", 1]);
$lxccount_s = $lxccount_s->all();
echo "\t\t\t\t\t\t\t\t<h2 align=\"center\">";
echo count($lxccount);
echo " total</h2>\r\n\t\t\t\t\t\t\t\t<center><p>Suspended: ";
echo count($lxccount_s);
echo "</p></center>\r\n\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t</div>\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<div class=\"col-md-6\">\r\n\t\t\t\t\t\t<div class=\"panel panel-info\">\r\n\t\t\t\t\t\t\t<div class=\"panel-heading\">\r\n\t\t\t\t\t\t\t\t<h3 class=\"panel-title\">KVM VPS Stats</h3>\r\n\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t<div class=\"panel-body\">\r\n\t\t\t\t\t\t\t\t";
$kvmcount = $db->get("vncp_kvm_ct", ["user_id", "!=", 0]);
$kvmcount = $kvmcount->all();
$kvmcount_s = $db->get("vncp_kvm_ct", ["suspended", "=", 1]);
$kvmcount_s = $kvmcount_s->all();
echo "\t\t\t\t\t\t\t\t<h2 align=\"center\">";
echo count($kvmcount);
echo " total</h2>\r\n\t\t\t\t\t\t\t\t<center><p>Suspended: ";
echo count($kvmcount_s);
echo "</p></center>\r\n\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t</div>\r\n\t\t\t\t\t</div>\r\n\t\t\t\t</div>\r\n\t\t\t\t<div class=\"row\">\r\n\t\t\t\t\t<div class=\"col-md-6\">\r\n\t\t\t\t\t\t<div class=\"panel panel-info\">\r\n\t\t\t\t\t\t\t<div class=\"panel-heading\">\r\n\t\t\t\t\t\t\t\t<h3 class=\"panel-title\">Cloud Stats</h3>\r\n\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t<div class=\"panel-body\">\r\n\t\t\t\t\t\t\t\t";
$cloudcount = $db->get("vncp_kvm_cloud", ["user_id", "!=", 0]);
$cloudcount = $cloudcount->all();
$cloudcount_s = $db->get("vncp_kvm_cloud", ["suspended", "=", 1]);
$cloudcount_s = $cloudcount_s->all();
echo "\t\t\t\t\t\t\t\t<h2 align=\"center\">";
echo count($cloudcount);
echo " total</h2>\r\n\t\t\t\t\t\t\t\t<center><p>Suspended: ";
echo count($cloudcount_s);
echo "</p></center>\r\n\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t</div>\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<div class=\"col-md-6\">\r\n\t\t\t\t\t\t<div class=\"panel panel-info\">\r\n\t\t\t\t\t\t\t<div class=\"panel-heading\">\r\n\t\t\t\t\t\t\t\t<h3 class=\"panel-title\">User Stats</h3>\r\n\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t\t<div class=\"panel-body\">\r\n\t\t\t\t\t\t\t\t";
$usercount = $db->get("vncp_users", ["id", "!=", 0]);
$usercount = $usercount->all();
$usercount_s = $db->get("vncp_users", ["locked", "=", 1]);
$usercount_s = $usercount_s->all();
$usercount_a = $db->get("vncp_users", ["`group`", "=", 2]);
$usercount_a = $usercount_a->all();
echo "\t\t\t\t\t\t\t\t<h2 align=\"center\">";
echo count($usercount);
echo " total</h2>\r\n\t\t\t\t\t\t\t\t<center><p>Locked: ";
echo count($usercount_s);
echo " / Admins ";
echo count($usercount_a);
echo "</p></center>\r\n\t\t\t\t\t\t\t</div>\r\n\t\t\t\t\t\t</div>\r\n\t\t\t\t\t</div>\r\n\t\t\t\t</div>\r\n\t\t\t</div>\r\n\t\t</div>\r\n\t</div>\r\n</div>\r\n<div class=\"panel panel-default\">\r\n\t<div class=\"panel-heading\">\r\n\t\t<h3 class=\"panel-title\">System Information</h3>\r\n\t</div>\r\n\t<div class=\"panel-body\">\r\n\t\t<div class=\"row\">\r\n\t\t\t<div class=\"col-md-4\">\r\n\t\t\t\t<div class=\"row\">\r\n\t\t\t\t\t<div class=\"col-md-8\">HTTPS Web</div>\r\n\t\t\t\t\t<div class=\"col-md-4\">";
if (check_https()) {
    echo "True";
} else {
    echo "False";
}
echo "</div>\r\n\t\t\t\t</div>\r\n\t\t\t\t<div class=\"row\">\r\n\t\t\t\t\t<div class=\"col-md-8\">HTTPS Socket</div>\r\n\t\t\t\t\t<div class=\"col-md-4\">";
$f = fopen("js/io.js", "r");
$value = fread($f, filesize("js/io.js"));
$value = substr($value, 25, 5);
if ($value == "https") {
    echo "True";
} else {
    echo "False";
}
echo "</div>\r\n\t\t\t\t</div>\r\n\t\t\t\t<div class=\"row\">\r\n\t\t\t\t\t<div class=\"col-md-8\">PHP Version</div>\r\n\t\t\t\t\t<div class=\"col-md-4\">";
echo phpversion();
echo "</div>\r\n\t\t\t\t</div>\r\n\t\t\t</div>\r\n\t\t\t<div class=\"col-md-4\">\r\n\t\t\t\t<div class=\"row\">\r\n\t\t\t\t\t<div class=\"col-md-8\">Removed install.php</div>\r\n\t\t\t\t\t<div class=\"col-md-4\">";
if (file_exists("install.php")) {
    echo "<span style=\"color:red;\">False</span>";
} else {
    echo "True";
}
echo "</div>\r\n\t\t\t\t</div>\r\n\t\t\t\t<div class=\"row\">\r\n\t\t\t\t\t<div class=\"col-md-8\">Permissions core/init.php</div>\r\n\t\t\t\t\t<div class=\"col-md-4\">";
echo substr(sprintf("%o", fileperms("core/init.php")), -4);
echo "</div>\r\n\t\t\t\t</div>\r\n\t\t\t\t<div class=\"row\">\r\n\t\t\t\t\t<div class=\"col-md-8\">MySQL Version</div>\r\n\t\t\t\t\t<div class=\"col-md-4\">";
echo mysqli_get_server_info($connection);
echo "</div>\r\n\t\t\t\t</div>\r\n\t\t\t</div>\r\n\t\t\t<div class=\"col-md-4\">\r\n\t\t\t\t<div class=\"row\">\r\n\t\t\t\t\t<div class=\"col-md-8\">Removed sql/ directory</div>\r\n\t\t\t\t\t<div class=\"col-md-4\">";
if (file_exists("sql/")) {
    echo "<span style=\"color:red;\">False</span>";
} else {
    echo "True";
}
echo "</div>\r\n\t\t\t\t</div>\r\n\t\t\t\t<div class=\"row\">\r\n\t\t\t\t\t<div class=\"col-md-8\">";
echo $appname;
echo " Version</div>\r\n\t\t\t\t\t<div class=\"col-md-4\">";
echo _obfuscated_0D31240926250B2C5C0C5C0B360A22040F3F1C3E143632_()[0];
echo "</div>\r\n\t\t\t\t</div>\r\n\t\t\t</div>\r\n\t\t</div>\r\n\t</div>\r\n</div>\r\n";

?>
