<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
echo "<div class=\"row\">\r\n    <div class=\"col-md-12\">\r\n        <div class=\"panel panel-default\">\r\n            <div class=\"panel-body\">\r\n              <div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Admin_-_Manage_Bandwidth\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div>\r\n                <h2 align=\"center\">Manage Bandwidth</h2><br />\r\n                <div class=\"table-responsive\">\r\n                    <table class=\"table table-hover\" id=\"admin_usertable\">\r\n                        <thead>\r\n                            <tr>\r\n                                <th>Billing Account ID</th>\r\n                                <th>VM Type</th>\r\n                                <th>Usage Percentage</th>\r\n                                <th>Bandwidth Limit</th>\r\n                                <th>Reset Date</th>\r\n                                <th></th>\r\n                            </tr>\r\n                        </thead>\r\n                        <tbody>\r\n                            ";
$admin_datausers = $db->get("vncp_bandwidth_monitor", ["id", "!=", 0]);
$admin_users = $admin_datausers->all();
for ($k = 0; $k < count($admin_users); $k++) {
    echo "<tr>";
    echo "<td>" . $admin_users[$k]->hb_account_id . "</td>";
    if ($admin_users[$k]->ct_type == "qemu") {
        echo "<td>KVM</td>";
    } else {
        echo "<td>LXC</td>";
    }
    $usage = round((double) $admin_users[$k]->current / (double) $admin_users[$k]->max * 100, 2);
    echo "<td>" . $usage . "%</td>";
    echo "<td>" . get_size($admin_users[$k]->max) . "</td>";
    echo "<td>" . $admin_users[$k]->reset_date . "</td>";
    echo "<td><button class=\"btn btn-info btn-sm\" id=\"resetbw" . $admin_users[$k]->id . "\" role=\"" . $admin_users[$k]->id . "\">Reset Now</button></td>";
    echo "</tr>";
}
echo "                        </tbody>\r\n                    </table>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n";

?>
