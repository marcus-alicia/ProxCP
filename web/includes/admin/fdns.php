<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
echo "<div class=\"row\">\r\n    <div class=\"col-md-12\">\r\n        <div class=\"panel panel-default\">\r\n            <div class=\"panel-body\">\r\n              <div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Admin_-_Manage_Forward_DNS\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div>\r\n                <h2 align=\"center\">Manage Domains</h2><br />\r\n                <div id=\"adm_message\"></div>\r\n                ";
$fdns = parse_input($db->get("vncp_settings", ["item", "=", "enable_forward_dns"])->first()->value);
if ($fdns == "true") {
    echo "                <div class=\"table-responsive\">\r\n                    <table class=\"table table-hover\" id=\"admin_domainstable\">\r\n                        <thead>\r\n                            <tr>\r\n                                <th>ID</th>\r\n                                <th>User ID</th>\r\n                                <th>Domain</th>\r\n                                <th>Delete</th>\r\n                            </tr>\r\n                        </thead>\r\n                        <tbody>\r\n                            ";
    $admin_domains = $db->get("vncp_forward_dns_domain", ["id", "!=", 0]);
    $admin_domains = $admin_domains->all();
    for ($k = 0; $k < count($admin_domains); $k++) {
        echo "<tr>";
        echo "<td>" . $admin_domains[$k]->id . "</td>";
        echo "<td>" . $admin_domains[$k]->client_id . "</td>";
        echo "<td>" . $admin_domains[$k]->domain . "</td>";
        echo "<td><button id=\"admin_domaindelete" . $admin_domains[$k]->id . "\" class=\"btn btn-sm btn-danger\" role=\"" . $admin_domains[$k]->id . "\">Delete</button></td>";
        echo "</tr>";
    }
    echo "                        </tbody>\r\n                    </table>\r\n                </div>\r\n                <h2 align=\"center\">Manage Records</h2>\r\n                <div class=\"table-responsive\">\r\n                    <table class=\"table table-hover\" id=\"admin_recordstable\">\r\n                        <thead>\r\n                            <tr>\r\n                                <th>Domain</th>\r\n                                <th>Name</th>\r\n                                <th>Type</th>\r\n                                <th>Address</th>\r\n                                <th>CNAME</th>\r\n                                <th>Exchange</th>\r\n                                <th>TXT</th>\r\n                                <th>Delete</th>\r\n                            </tr>\r\n                        </thead>\r\n                        <tbody>\r\n                            ";
    $admin_records = $db->get("vncp_forward_dns_record", ["id", "!=", 0]);
    $admin_records = $admin_records->all();
    for ($k = 0; $k < count($admin_records); $k++) {
        echo "<tr>";
        echo "<td>" . $admin_records[$k]->domain . "</td>";
        echo "<td>" . $admin_records[$k]->name . "</td>";
        echo "<td>" . $admin_records[$k]->type . "</td>";
        echo "<td>" . $admin_records[$k]->address . "</td>";
        echo "<td>" . $admin_records[$k]->cname . "</td>";
        echo "<td>" . $admin_records[$k]->exchange . "</td>";
        echo "<td>" . $admin_records[$k]->txtdata . "</td>";
        echo "<td><button id=\"admin_recorddelete" . $admin_records[$k]->id . "\" class=\"btn btn-sm btn-danger\" role=\"" . $admin_records[$k]->id . "\">Delete</button></td>";
        echo "</tr>";
    }
    echo "                        </tbody>\r\n                    </table>\r\n                </div>\r\n                ";
} else {
    echo "<p>Forward DNS is not enabled. Go to " . $appname . " settings to enable it.</p>";
}
echo "            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n";

?>
