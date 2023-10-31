<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
echo "<div class=\"row\">\r\n\t<div class=\"col-md-2\">\r\n    <ul class=\"nav nav-pills nav-stacked\">\r\n      <li role=\"presentation\" class=\"active\"><a href=\"";
echo Config::get("admin/base") . "?action=nodes";
echo "\">Manage Nodes</a></li>\r\n      <li role=\"presentation\"><a href=\"";
echo Config::get("admin/base") . "?action=tuntap";
echo "\">Manage Node SSH</a></li>\r\n      <li role=\"presentation\"><a href=\"";
echo Config::get("admin/base") . "?action=natnodes";
echo "\">Manage NAT Nodes</a></li>\r\n    </ul>\r\n  </div>\r\n\t<div class=\"col-md-10\">\r\n\t\t<div class=\"panel panel-default\">\r\n\t\t\t<div class=\"panel-body\">\r\n\t\t\t\t<div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Admin_-_Manage_Nodes\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div>\r\n\t\t\t\t<h2 align=\"center\">Manage Nodes</h2><br />\r\n\t\t\t\t";
$nodelimit = -1;
if ($GLOBALS["node_limit"] == "100000000") {
    $nodelimit = "unlimited";
} else {
    $nodelimit = $GLOBALS["node_limit"];
}
echo "\t\t\t\t<h4 align=\"center\">Licensed node limit: ";
echo $nodelimit;
echo "</h4><br />\r\n\t\t\t\t<div id=\"adm_message\"></div>\r\n\t\t\t\t<div class=\"table-responsive\">\r\n\t\t\t\t\t<table class=\"table table-hover\" id=\"admin_nodetable\">\r\n\t\t\t\t\t\t<thead>\r\n\t\t\t\t\t\t\t<tr>\r\n\t\t\t\t\t\t\t\t<th>Hostname</th>\r\n\t\t\t\t\t\t\t\t<th>Name</th>\r\n\t\t\t\t\t\t\t\t<th>Location</th>\r\n\t\t\t\t\t\t\t\t<th>CPU</th>\r\n\t\t\t\t\t\t\t\t<th>SSH</th>\r\n\t\t\t\t\t\t\t\t<th>NAT</th>\r\n\t\t\t\t\t\t\t\t<th>Delete</th>\r\n\t\t\t\t\t\t\t</tr>\r\n\t\t\t\t\t\t</thead>\r\n\t\t\t\t\t\t<tbody>\r\n\t\t\t\t\t\t\t";
$admin_datanodes = $db->get("vncp_nodes", ["id", "!=", 0]);
$admin_nodes = $admin_datanodes->all();
for ($k = 0; $k < count($admin_nodes); $k++) {
    echo "<tr>";
    echo "<td><a href=\"" . Config::get("instance/base") . "/admin?action=edit_node&id=" . _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($admin_nodes[$k]->id) . "\">" . $admin_nodes[$k]->hostname . "</a></td>";
    echo "<td>" . $admin_nodes[$k]->name . "</td>";
    echo "<td>" . $admin_nodes[$k]->location . "</td>";
    echo "<td>" . $admin_nodes[$k]->cpu . "</td>";
    $hasSSH = $db->get("vncp_tuntap", ["node", "=", $admin_nodes[$k]->name])->all();
    $hasNAT = $db->get("vncp_nat", ["node", "=", $admin_nodes[$k]->name])->all();
    if (count($hasSSH) == 1) {
        echo "<td><i class=\"fa fa-check\"></i></td>";
    } else {
        echo "<td><i class=\"fa fa-times\"></i> <a href=\"" . Config::get("instance/base") . "/admin?action=tuntap\" style=\"text-decoration:underline;\">(?)</a></td>";
    }
    if (count($hasNAT) == 1) {
        echo "<td><i class=\"fa fa-check\"></i></td>";
    } else {
        echo "<td><i class=\"fa fa-times\"></i> <a href=\"" . Config::get("instance/base") . "/admin?action=natnodes\" style=\"text-decoration:underline;\">(?)</a></td>";
    }
    echo "<td><button id=\"admin_nodedelete" . $admin_nodes[$k]->id . "\" class=\"btn btn-sm btn-danger\" role=\"" . $admin_nodes[$k]->id . "\">Delete</button></td>";
    echo "</tr>";
}
echo "\t\t\t\t\t\t</tbody>\r\n\t\t\t\t\t</table>\r\n\t\t\t\t</div>\r\n\t\t\t\t<h2 align=\"center\">Add New Node</h2>\r\n\t\t\t\t<form role=\"form\" action=\"\" method=\"POST\">\r\n\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t    <label>Hostname</label>\r\n\t\t\t\t\t    <input class=\"form-control\" type=\"text\" name=\"hostname\" placeholder=\"server.domain.com\" />\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t    <label>Name</label>\r\n\t\t\t\t\t    <input class=\"form-control\" type=\"text\" name=\"name\" placeholder=\"server\" />\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t    <label>API Username</label>\r\n\t\t\t\t\t    <input class=\"form-control\" type=\"text\" name=\"username\" placeholder=\"name\" />\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t    <label>API Password</label>\r\n\t\t\t\t\t    <input class=\"form-control\" type=\"password\" name=\"password\" />\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t    <label>Realm</label>\r\n\t\t\t\t\t    <input class=\"form-control\" type=\"text\" name=\"realm\" value=\"pve\" />\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t    <label>Port</label>\r\n\t\t\t\t\t    <input class=\"form-control\" type=\"text\" name=\"port\" value=\"8006\" />\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t    <label>Location</label>\r\n\t\t\t\t\t    <input class=\"form-control\" type=\"text\" name=\"location\" placeholder=\"City, State, Country\" />\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t    <label>CPU</label>\r\n\t\t\t\t\t    <input class=\"form-control\" type=\"text\" name=\"cpu\" placeholder=\"Intel Xeon\" />\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t    <label>Backup Store</label>\r\n\t\t\t\t\t    <input class=\"form-control\" type=\"text\" name=\"backup\" value=\"backup\" />\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<input type=\"hidden\" name=\"token\" value=\"";
echo Token::generate();
echo "\" />\r\n\t\t\t\t\t<input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n\t\t\t\t</form>\r\n\t\t\t</div>\r\n\t\t</div>\r\n\t</div>\r\n</div>\r\n";

?>
