<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
echo "<div class=\"row\">\r\n    <div class=\"col-md-12\">\r\n        <div class=\"panel panel-default\">\r\n            <div class=\"panel-body\">\r\n              <div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Admin_-_Manage_Reverse_DNS\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div>\r\n                <h2 align=\"center\">Manage rDNS/PTR</h2><br />\r\n                <div id=\"adm_message\"></div>\r\n                ";
$rdns = parse_input($db->get("vncp_settings", ["item", "=", "enable_reverse_dns"])->first()->value);
if ($rdns == "true") {
    echo "                <div class=\"table-responsive\">\r\n                    <table class=\"table table-hover\" id=\"admin_ptrtable\">\r\n                        <thead>\r\n                            <tr>\r\n                                <th>ID</th>\r\n                                <th>User ID</th>\r\n                                <th>Type</th>\r\n                                <th>IP Address</th>\r\n                                <th>Hostname</th>\r\n                                <th>Delete</th>\r\n                            </tr>\r\n                        </thead>\r\n                        <tbody>\r\n                            ";
    $admin_domains = $db->get("vncp_reverse_dns", ["id", "!=", 0]);
    $admin_domains = $admin_domains->all();
    for ($k = 0; $k < count($admin_domains); $k++) {
        echo "<tr>";
        echo "<td>" . $admin_domains[$k]->id . "</td>";
        echo "<td>" . $admin_domains[$k]->client_id . "</td>";
        echo "<td>" . $admin_domains[$k]->type . "</td>";
        echo "<td>" . $admin_domains[$k]->ipaddress . "</td>";
        echo "<td>" . $admin_domains[$k]->hostname . "</td>";
        echo "<td><button id=\"admin_ptrdelete" . $admin_domains[$k]->id . "\" class=\"btn btn-sm btn-danger\" role=\"" . $admin_domains[$k]->id . "\">Delete</button></td>";
        echo "</tr>";
    }
    echo "                        </tbody>\r\n                    </table>\r\n                </div>\r\n                ";
} else {
    echo "<p>Reverse DNS is not enabled. Go to " . $appname . " settings to enable it.</p>";
}
echo "            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n";

?>
