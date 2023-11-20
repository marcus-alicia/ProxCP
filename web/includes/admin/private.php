<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
echo "<div class=\"row\">\r\n    <div class=\"col-md-12\">\r\n        <div class=\"panel panel-default\">\r\n            <div class=\"panel-body\">\r\n              <div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Admin_-_Manage_Private_IP_Pool\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div>\r\n                <h2 align=\"center\">Manage Private Pool</h2><br />\r\n                ";
if ($privAddedSuccess) {
    echo "<div id=\"adm_message\"><div class=\"alert alert-success\" role=\"alert\"><strong>Success:</strong> private pool added successfully!</div></div>";
} else {
    echo "<div id=\"adm_message\"></div>";
}
echo "                ";
$private_networking = parse_input($db->get("vncp_settings", ["item", "=", "private_networking"])->first()->value);
if ($private_networking == "true") {
    echo "                ";
    $poolexists = $db->get("vncp_private_pool", ["id", "!=", 0])->all();
    if (0 < count($poolexists)) {
        echo "                <div class=\"row\">\r\n                    <div class=\"col-md-12\">\r\n                        <div class=\"panel panel-info\">\r\n                            <div class=\"panel-heading\">\r\n                                <h3 class=\"panel-title\">Pool Usage</h3>\r\n                            </div>\r\n                            <div class=\"panel-body\">\r\n                                ";
        $total = $db->get("vncp_private_pool", ["id", "!=", 0])->all();
        $total = count($total);
        $avail = $db->get("vncp_private_pool", ["available", "=", 1])->all();
        $avail = count($avail);
        echo "                                <h2 align=\"center\">";
        echo $avail;
        echo " free / ";
        echo $total;
        echo " total</h2>\r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n                <h2 align=\"center\">IP Assignments</h2>\r\n                <div class=\"table-responsive\">\r\n                    <table class=\"table table-hover\" id=\"admin_privatetable\">\r\n                        <thead>\r\n                            <tr>\r\n                                <th>User ID</th>\r\n                                <th>Billing Account ID</th>\r\n                                <th>IP Address</th>\r\n                                <th>Clear</th>\r\n                            </tr>\r\n                        </thead>\r\n                        <tbody>\r\n                            ";
        $admin_private = $db->get("vncp_private_pool", ["available", "=", 0]);
        $admin_private = $admin_private->all();
        for ($k = 0; $k < count($admin_private); $k++) {
            echo "<tr>";
            echo "<td>" . $admin_private[$k]->user_id . "</td>";
            echo "<td>" . $admin_private[$k]->hb_account_id . "</td>";
            echo "<td>" . $admin_private[$k]->address . "</td>";
            echo "<td><button id=\"admin_privatedelete" . $admin_private[$k]->id . "\" class=\"btn btn-sm btn-danger\" role=\"" . $admin_private[$k]->id . "\">Clear</button></td>";
            echo "</tr>";
        }
        echo "                        </tbody>\r\n                    </table>\r\n                </div>\r\n                <h2 align=\"center\">Add Private IP Pool</h2>\r\n                <form role=\"form\" action=\"\" method=\"POST\">\r\n                    <div class=\"form-group\">\r\n                        <label>IP CIDR</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"cidr\" placeholder=\"1.1.1.5/24\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>Nodes</label>\r\n                        <select multiple class=\"form-control\" name=\"privnodes[]\">\r\n                            ";
        $getnodes = $db->get("vncp_nodes", ["id", "!=", 0])->all();
        for ($i = 0; $i < count($getnodes); $i++) {
            echo "<option value=\"" . $getnodes[$i]->name . "\">" . $getnodes[$i]->hostname . "</option>";
        }
        echo "                        </select>\r\n                    </div>\r\n                    <input type=\"hidden\" name=\"token\" value=\"";
        echo Token::generate();
        echo "\" />\r\n                    <input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n                </form>\r\n                ";
    } else {
        echo "                <h2 align=\"center\">Add Private IP Pool</h2>\r\n                <form role=\"form\" action=\"\" method=\"POST\">\r\n                    <div class=\"form-group\">\r\n                        <label>IP CIDR</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"cidr\" placeholder=\"1.1.1.5/24\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>Nodes</label>\r\n                        <select multiple class=\"form-control\" name=\"privnodes[]\">\r\n                            ";
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
    echo "<p>Private networking is not enabled. Go to " . $appname . " settings to enable it.</p>";
}
echo "            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n";

?>
