<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
echo "<div class=\"row\">\r\n    <div class=\"col-md-12\">\r\n        <div class=\"panel panel-default\">\r\n            <div class=\"panel-body\">\r\n              <div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Admin_-_Manage_Secondary_IPs\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div>\r\n                <h2 align=\"center\">Manage Secondary IPs</h2><br />\r\n                ";
if ($ipAddedSuccess) {
    echo "<div id=\"adm_message\"><div class=\"alert alert-success\" role=\"alert\"><strong>Success:</strong> secondary IP added successfully!</div></div>";
} else {
    echo "<div id=\"adm_message\"></div>";
}
echo "                ";
$ip2 = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($db->get("vncp_settings", ["item", "=", "secondary_ips"])->first()->value);
if ($ip2 == "true") {
    echo "                <div class=\"table-responsive\">\r\n                    <table class=\"table table-hover\" id=\"admin_ip2table\">\r\n                        <thead>\r\n                            <tr>\r\n                                <th>ID</th>\r\n                                <th>User ID</th>\r\n                                <th>Billing Account ID</th>\r\n                                <th>IP Address</th>\r\n                                <th>Delete</th>\r\n                            </tr>\r\n                        </thead>\r\n                        <tbody>\r\n                            ";
    $admin_ip2 = $db->get("vncp_secondary_ips", ["id", "!=", 0]);
    $admin_ip2 = $admin_ip2->all();
    for ($k = 0; $k < count($admin_ip2); $k++) {
        echo "<tr>";
        echo "<td>" . $admin_ip2[$k]->id . "</td>";
        echo "<td>" . $admin_ip2[$k]->user_id . "</td>";
        echo "<td>" . $admin_ip2[$k]->hb_account_id . "</td>";
        echo "<td>" . $admin_ip2[$k]->address . "</td>";
        echo "<td><button id=\"admin_ip2delete" . $admin_ip2[$k]->id . "\" class=\"btn btn-sm btn-danger\" role=\"" . $admin_ip2[$k]->id . "\">Delete</button></td>";
        echo "</tr>";
    }
    echo "                        </tbody>\r\n                    </table>\r\n                </div>\r\n                <h2 align=\"center\">Add Secondary IP</h2>\r\n                <form role=\"form\" action=\"\" method=\"POST\">\r\n                    <div class=\"form-group\">\r\n                        <label>User ID</label><br />\r\n                        <select class=\"form-control selectpicker\" data-live-search=\"true\" name=\"userid\">\r\n                          <option value=\"default\">Select...</option>\r\n                          ";
    $userdata = $db->get("vncp_users", ["id", "!=", 0])->all();
    for ($i = 0; $i < count($userdata); $i++) {
        echo "<option value=\"" . $userdata[$i]->id . "\">" . $userdata[$i]->email . " (ID: " . $userdata[$i]->id . ")</option>";
    }
    echo "                        </select>\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>Billing Account ID</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"hbaccountid\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>IP Address</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"ipaddr\" />\r\n                    </div>\r\n                    <input type=\"hidden\" name=\"token\" value=\"";
    echo Token::generate();
    echo "\" />\r\n                    <input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n                </form>\r\n                ";
} else {
    echo "<p>Secondary IPs are not enabled. Go to " . $appname . " settings to enable it.</p>";
}
echo "            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n";

?>
