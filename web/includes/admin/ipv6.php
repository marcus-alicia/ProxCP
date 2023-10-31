<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
echo "<div class=\"row\">\r\n    <div class=\"col-md-12\">\r\n        <div class=\"panel panel-default\">\r\n            <div class=\"panel-body\">\r\n              <div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Admin_-_Manage_IPv6\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div>\r\n                <h2 align=\"center\">Manage IPv6</h2><br />\r\n                ";
if ($ipv6AddedSuccess) {
    echo "<div id=\"adm_message\"><div class=\"alert alert-success\" role=\"alert\"><strong>Success:</strong> IPv6 pool added successfully!</div></div>";
} else {
    echo "<div id=\"adm_message\"></div>";
}
echo "                ";
$vm_ipv6 = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($db->get("vncp_settings", ["item", "=", "vm_ipv6"])->first()->value);
if ($vm_ipv6 == "true") {
    echo "                ";
    $poolexists = $db->get("vncp_ipv6_pool", ["id", "!=", 0])->all();
    if (0 < count($poolexists)) {
        echo "                <h2 align=\"center\">IPv6 Pools</h2>\r\n                <div class=\"table-responsive\">\r\n                    <table class=\"table table-hover\" id=\"admin_v6poolstable\">\r\n                        <thead>\r\n                            <tr>\r\n                                <th>ID</th>\r\n                                <th>Subnet</th>\r\n                                <th>Nodes</th>\r\n                                <th>Delete</th>\r\n                            </tr>\r\n                        </thead>\r\n                        <tbody>\r\n                            ";
        $admin_v6pools = $db->get("vncp_ipv6_pool", ["id", "!=", 0]);
        $admin_v6pools = $admin_v6pools->all();
        for ($k = 0; $k < count($admin_v6pools); $k++) {
            echo "<tr>";
            echo "<td>" . $admin_v6pools[$k]->id . "</td>";
            echo "<td>" . $admin_v6pools[$k]->subnet . "</td>";
            echo "<td>" . $admin_v6pools[$k]->nodes . "</td>";
            echo "<td><button id=\"admin_v6pooldelete" . $admin_v6pools[$k]->id . "\" class=\"btn btn-sm btn-danger\" role=\"" . $admin_v6pools[$k]->id . "\">Delete</button></td>";
            echo "</tr>";
        }
        echo "                        </tbody>\r\n                    </table>\r\n                </div>\r\n                <h2 align=\"center\">IPv6 Assignments</h2>\r\n                <div class=\"table-responsive\">\r\n                    <table class=\"table table-hover\" id=\"admin_v6assigntable\">\r\n                        <thead>\r\n                            <tr>\r\n                                <th>User ID</th>\r\n                                <th>Billing Account ID</th>\r\n                                <th>Address</th>\r\n                                <th>Delete</th>\r\n                            </tr>\r\n                        </thead>\r\n                        <tbody>\r\n                            ";
        $admin_v6assign = $db->get("vncp_ipv6_assignment", ["id", "!=", 0]);
        $admin_v6assign = $admin_v6assign->all();
        for ($k = 0; $k < count($admin_v6assign); $k++) {
            echo "<tr>";
            echo "<td>" . $admin_v6assign[$k]->user_id . "</td>";
            echo "<td>" . $admin_v6assign[$k]->hb_account_id . "</td>";
            echo "<td>" . $admin_v6assign[$k]->address . "</td>";
            echo "<td><button id=\"admin_v6assigndelete" . $admin_v6assign[$k]->id . "\" class=\"btn btn-sm btn-danger\" role=\"" . $admin_v6assign[$k]->id . "\">Delete</button></td>";
            echo "</tr>";
        }
        echo "                        </tbody>\r\n                    </table>\r\n                </div>\r\n                <h2 align=\"center\">Add IPv6 Pool</h2>\r\n                <form role=\"form\" action=\"\" method=\"POST\">\r\n                    <div class=\"form-group\">\r\n                        <label>IPv6 CIDR</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"v6cidr\" placeholder=\"fe80:4500:0:2ec::/64\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>Nodes</label>\r\n                        <select multiple class=\"form-control\" name=\"v6nodes[]\">\r\n                            ";
        $getnodes = $db->get("vncp_nodes", ["id", "!=", 0])->all();
        for ($i = 0; $i < count($getnodes); $i++) {
            echo "<option value=\"" . $getnodes[$i]->name . "\">" . $getnodes[$i]->hostname . "</option>";
        }
        echo "                        </select>\r\n                    </div>\r\n                    <input type=\"hidden\" name=\"token\" value=\"";
        echo Token::generate();
        echo "\" />\r\n                    <input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n                </form>\r\n                ";
    } else {
        echo "                <h2 align=\"center\">Add IPv6 Pool</h2>\r\n                <form role=\"form\" action=\"\" method=\"POST\">\r\n                    <div class=\"form-group\">\r\n                        <label>IPv6 CIDR</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"v6cidr\" placeholder=\"fe80:4500:0:2ec::/64\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>Nodes</label>\r\n                        <select multiple class=\"form-control\" name=\"v6nodes[]\">\r\n                            ";
        $getnodes = $db->get("vncp_nodes", ["id", "!=", 0])->all();
        for ($i = 0; $i < count($getnodes); $i++) {
            echo "<option value=\"" . $getnodes[$i]->name . "\">" . $getnodes[$i]->hostname . "</option>";
        }
        echo "                        </select>\r\n                    </div>\r\n                    <input type=\"hidden\" name=\"token\" value=\"";
        echo Token::generate();
        echo "\" />\r\n                    <input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n                </form>\r\n                ";
    }
    echo "                ";
} else {
    echo "<p>IPv6 networking is not enabled. Go to " . $appname . " settings to enable it.</p>";
}
echo "            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n";

?>
