<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
echo "<div class=\"row\">\r\n    <div class=\"col-md-12\">\r\n        <div class=\"panel panel-default\">\r\n            <div class=\"panel-body\">\r\n              <div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Admin_-_Manage_User_ACL\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div>\r\n                <h2 align=\"center\">Manage User ACL</h2><br />\r\n                ";
$user_acl = parse_input($db->get("vncp_settings", ["item", "=", "user_acl"])->first()->value);
if ($user_acl == "true") {
    echo "                <div class=\"table-responsive\">\r\n                    <table class=\"table table-hover\" id=\"admin_acltable\">\r\n                        <thead>\r\n                            <tr>\r\n                                <th>User ID</th>\r\n                                <th>IP Address</th>\r\n                                <th>Delete</th>\r\n                            </tr>\r\n                        </thead>\r\n                        <tbody>\r\n                            ";
    $admin_dataacl = $db->get("vncp_acl", ["id", "!=", 0]);
    $admin_dataacl = $admin_dataacl->all();
    for ($k = 0; $k < count($admin_dataacl); $k++) {
        echo "<tr>";
        echo "<td>" . $admin_dataacl[$k]->user_id . "</td>";
        echo "<td>" . $admin_dataacl[$k]->ipaddress . "</td>";
        echo "<td><button id=\"admin_acldelete" . $admin_dataacl[$k]->id . "\" class=\"btn btn-sm btn-danger\" role=\"" . $admin_dataacl[$k]->id . "\">Delete</button></td>";
        echo "</tr>";
    }
    echo "                        </tbody>\r\n                    </table>\r\n                </div>\r\n                ";
} else {
    echo "<p>User ACL is not enabled. Go to " . $appname . " settings to enable it.</p>";
}
echo "            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n";

?>
