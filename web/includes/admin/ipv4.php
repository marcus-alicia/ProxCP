<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
echo "<div class=\"row\">\r\n    <div class=\"col-md-12\">\r\n        <div class=\"panel panel-default\">\r\n            <div class=\"panel-body\">\r\n              <div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Admin_-_Manage_IPv4_Pool\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div>\r\n                <h2 align=\"center\">Manage IPv4 Pool</h2>\r\n                <h4 align=\"center\">The ";
echo $appname;
echo " IPv4  Pool is used for the billing modules and manual tracking.</h4><br />\r\n                ";
if ($pubAddedSuccess) {
    echo "<div id=\"adm_message\"><div class=\"alert alert-success\" role=\"alert\"><strong>Success:</strong> IPv4 pool added successfully!</div></div>";
} else {
    echo "<div id=\"adm_message\"></div>";
}
echo "                ";
$poolexists = $db->get("vncp_ipv4_pool", ["id", "!=", 0])->all();
if (0 < count($poolexists)) {
    echo "                <div class=\"row\">\r\n                    <div class=\"col-md-12\">\r\n                        <div class=\"panel panel-info\">\r\n                            <div class=\"panel-heading\">\r\n                                <h3 class=\"panel-title\">Pool Usage</h3>\r\n                            </div>\r\n                            <div class=\"panel-body\">\r\n                                ";
    $total = $db->get("vncp_ipv4_pool", ["id", "!=", 0])->all();
    $total = count($total);
    $avail = $db->get("vncp_ipv4_pool", ["available", "=", 1])->all();
    $avail = count($avail);
    echo "                                <h2 align=\"center\">";
    echo $avail;
    echo " free / ";
    echo $total;
    echo " total</h2>\r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n                <h2 align=\"center\">IP Assignments</h2>\r\n                <div class=\"table-responsive\">\r\n                    <table class=\"table table-hover\" id=\"admin_privatetable\">\r\n                        <thead>\r\n                            <tr>\r\n                                <th>User</th>\r\n                                <th>Billing Account ID</th>\r\n                                <th>IP Address</th>\r\n                                <th>Node</th>\r\n                                <th>Available</th>\r\n                                <th>Action</th>\r\n                            </tr>\r\n                        </thead>\r\n                        <tbody>\r\n                            ";
    $admin_private = $db->get("vncp_ipv4_pool", ["id", "!=", 0]);
    $admin_private = $admin_private->all();
    for ($k = 0; $k < count($admin_private); $k++) {
        $uid = "None";
        if ($admin_private[$k]->user_id != 0) {
            $u = $db->get("vncp_users", ["id", "=", $admin_private[$k]->user_id])->first();
            $uid = $u->username;
        }
        echo "<tr>";
        echo "<td>" . $uid . "</td>";
        echo "<td>" . $admin_private[$k]->hb_account_id . "</td>";
        echo "<td>" . $admin_private[$k]->address . "</td>";
        echo "<td>" . $admin_private[$k]->nodes . "</td>";
        echo "<td>" . ($admin_private[$k]->available == 0 ? "No" : "Yes") . "</td>";
        if ($admin_private[$k]->available == 0) {
            echo "<td><button id=\"admin_publicdelete" . $admin_private[$k]->id . "\" class=\"btn btn-sm btn-danger\" role=\"" . $admin_private[$k]->id . "\">Clear Assignment</button></td>";
        } else {
            echo "<td><div class=\"input-group input-group-sm\"><input type=\"text\" class=\"form-control\" placeholder=\"Billing ID (must exist)\" id=\"admin_ipseti" . $admin_private[$k]->id . "\" /><span class=\"input-group-btn\"><button class=\"btn btn-success btn-sm\" type=\"button\" id=\"admin_setip" . $admin_private[$k]->id . "\" role=\"" . $admin_private[$k]->id . "\">Assign</button></span></div><br /><button id=\"admin_publicclr" . $admin_private[$k]->id . "\" class=\"btn btn-sm btn-danger\" role=\"" . $admin_private[$k]->id . "\">Delete From Pool</button></td>";
        }
        echo "</tr>";
    }
    echo "                        </tbody>\r\n                    </table>\r\n                </div>\r\n                <h2 align=\"center\">Add IPv4 Pool (CIDR)</h2>\r\n                <form role=\"form\" action=\"\" method=\"POST\">\r\n                    <div class=\"form-group\">\r\n                        <label>IP CIDR</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"cidr\" placeholder=\"1.1.1.0/24\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>Nodes</label>\r\n                        <select multiple class=\"form-control\" name=\"pubnodes[]\">\r\n                            ";
    $getnodes = $db->get("vncp_nodes", ["id", "!=", 0])->all();
    for ($i = 0; $i < count($getnodes); $i++) {
        echo "<option value=\"" . $getnodes[$i]->name . "\">" . $getnodes[$i]->hostname . "</option>";
    }
    echo "                        </select>\r\n                    </div>\r\n                    <input type=\"hidden\" name=\"token\" value=\"";
    echo Token::generate();
    echo "\" />\r\n                    <input type=\"hidden\" name=\"form_name\" value=\"add_cidr\" />\r\n                    <input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n                </form>\r\n                <h2 align=\"center\">Add IPv4 Pool (Single)</h2>\r\n                <form role=\"form\" action=\"\" method=\"POST\">\r\n                    <div class=\"form-group\">\r\n                        <label>IPv4 Address</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"ipaddress\" placeholder=\"1.1.1.2\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>IPv4 Gateway</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"ipgateway\" placeholder=\"1.1.1.1\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>IPv4 Netmask</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"ipnetmask\" placeholder=\"255.255.255.0\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>Nodes</label>\r\n                        <select multiple class=\"form-control\" name=\"pubnodes[]\">\r\n                            ";
    $getnodes = $db->get("vncp_nodes", ["id", "!=", 0])->all();
    for ($i = 0; $i < count($getnodes); $i++) {
        echo "<option value=\"" . $getnodes[$i]->name . "\">" . $getnodes[$i]->hostname . "</option>";
    }
    echo "                        </select>\r\n                    </div>\r\n                    <input type=\"hidden\" name=\"form_name\" value=\"add_single\" />\r\n                    <input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n                </form>\r\n                ";
} else {
    echo "                <h2 align=\"center\">Add IPv4 Pool</h2>\r\n                <form role=\"form\" action=\"\" method=\"POST\">\r\n                    <div class=\"form-group\">\r\n                        <label>IP CIDR</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"cidr\" placeholder=\"1.1.1.0/24\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>Nodes</label>\r\n                        <select multiple class=\"form-control\" name=\"pubnodes[]\">\r\n                            ";
    $getnodes = $db->get("vncp_nodes", ["id", "!=", 0])->all();
    for ($i = 0; $i < count($getnodes); $i++) {
        echo "<option value=\"" . $getnodes[$i]->name . "\">" . $getnodes[$i]->hostname . "</option>";
    }
    echo "                        </select>\r\n                    </div>\r\n                    <input type=\"hidden\" name=\"token\" value=\"";
    echo Token::generate();
    echo "\" />\r\n                    <input type=\"hidden\" name=\"form_name\" value=\"add_cidr\" />\r\n                    <input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n                </form>\r\n                <h2 align=\"center\">Add IPv4 Pool (Single)</h2>\r\n                <form role=\"form\" action=\"\" method=\"POST\">\r\n                    <div class=\"form-group\">\r\n                        <label>IPv4 Address</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"ipaddress\" placeholder=\"1.1.1.2\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>IPv4 Gateway</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"ipgateway\" placeholder=\"1.1.1.1\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>IPv4 Netmask</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"ipnetmask\" placeholder=\"255.255.255.0\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>Nodes</label>\r\n                        <select multiple class=\"form-control\" name=\"pubnodes[]\">\r\n                            ";
    $getnodes = $db->get("vncp_nodes", ["id", "!=", 0])->all();
    for ($i = 0; $i < count($getnodes); $i++) {
        echo "<option value=\"" . $getnodes[$i]->name . "\">" . $getnodes[$i]->hostname . "</option>";
    }
    echo "                        </select>\r\n                    </div>\r\n                    <input type=\"hidden\" name=\"form_name\" value=\"add_single\" />\r\n                    <input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n                </form>\r\n                ";
}
echo "            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n";

?>
