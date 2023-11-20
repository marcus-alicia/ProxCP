<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
echo "<div class=\"row\">\r\n    <div class=\"col-md-12\">\r\n        <div class=\"panel panel-default\">\r\n            <div class=\"panel-body\">\r\n              <div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Admin_-_Manage_API\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div>\r\n                <h2 align=\"center\">Manage API Credentials</h2>\r\n                <h4 align=\"center\">The ";
echo $appname;
echo " API is currently only used for the WHMCS & Blesta billing modules.</h4><br />\r\n                <div class=\"table-responsive\">\r\n                    <table class=\"table table-hover\" id=\"admin_lxctemptable\">\r\n                        <thead>\r\n                            <tr>\r\n                                <th>API ID</th>\r\n                                <th>API Key</th>\r\n                                <th>API IP Address</th>\r\n                                <th>Delete</th>\r\n                            </tr>\r\n                        </thead>\r\n                        <tbody>\r\n                            ";
$admin_datalxctemp = $db->get("vncp_api", ["id", "!=", 0]);
$admin_datalxctemp = $admin_datalxctemp->all();
for ($k = 0; $k < count($admin_datalxctemp); $k++) {
    echo "<tr>";
    echo "<td>" . $admin_datalxctemp[$k]->api_id . "</td>";
    echo "<td>" . $admin_datalxctemp[$k]->api_key . "</td>";
    echo "<td>" . $admin_datalxctemp[$k]->api_ip . "</td>";
    echo "<td><button id=\"admin_apidelete" . $admin_datalxctemp[$k]->id . "\" class=\"btn btn-sm btn-danger\" role=\"" . $admin_datalxctemp[$k]->id . "\">Delete</button></td>";
    echo "</tr>";
}
echo "                        </tbody>\r\n                    </table>\r\n                </div>\r\n                <h2 align=\"center\">Add New API ID</h2>\r\n                <form role=\"form\" action=\"\" method=\"POST\">\r\n                    <div class=\"form-group\">\r\n                        <label>IP Restriction</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"apiip\" />\r\n                    </div>\r\n                    <input type=\"hidden\" name=\"token\" value=\"";
echo Token::generate();
echo "\" />\r\n                    <input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n                </form>\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n";

?>
