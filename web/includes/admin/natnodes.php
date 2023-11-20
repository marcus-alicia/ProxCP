<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
echo "<div class=\"row\">\r\n  <div class=\"col-md-2\">\r\n    <ul class=\"nav nav-pills nav-stacked\">\r\n      <li role=\"presentation\"><a href=\"";
echo Config::get("admin/base") . "?action=nodes";
echo "\">Manage Nodes</a></li>\r\n      <li role=\"presentation\"><a href=\"";
echo Config::get("admin/base") . "?action=tuntap";
echo "\">Manage Node SSH</a></li>\r\n      <li role=\"presentation\" class=\"active\"><a href=\"";
echo Config::get("admin/base") . "?action=natnodes";
echo "\">Manage NAT Nodes</a></li>\r\n    </ul>\r\n  </div>\r\n\t<div class=\"col-md-10\">\r\n\t\t<div class=\"panel panel-default\">\r\n\t\t\t<div class=\"panel-body\">\r\n        <div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Admin_-_Manage_NAT_Nodes\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div>\r\n\t\t\t\t<h2 align=\"center\">Manage NAT Nodes</h2><br />\r\n        ";
if ($natCreatedSuccess) {
    echo "<div id=\"adm_message\"><div class=\"alert alert-success\" role=\"alert\"><strong>Success:</strong> new NAT-enabled node created.</div></div>";
} else {
    echo "<div id=\"adm_message\"></div>";
}
echo "\t\t\t\t<div class=\"table-responsive\">\r\n\t\t\t\t\t<table class=\"table table-hover\" id=\"admin_natnodetable\">\r\n\t\t\t\t\t\t<thead>\r\n\t\t\t\t\t\t\t<tr>\r\n                <th>Hostname</th>\r\n\t\t\t\t\t\t\t\t<th>Name</th>\r\n\t\t\t\t\t\t\t\t<th>Public IP</th>\r\n\t\t\t\t\t\t\t\t<th>NAT Range</th>\r\n                <th>NAT VM Limit</th>\r\n\t\t\t\t\t\t\t</tr>\r\n\t\t\t\t\t\t</thead>\r\n\t\t\t\t\t\t<tbody>\r\n\t\t\t\t\t\t\t";
$admin_datanodes = $db->get("vncp_nat", ["id", "!=", 0]);
$admin_nodes = $admin_datanodes->all();
for ($k = 0; $k < count($admin_nodes); $k++) {
    echo "<tr>";
    $hostname = $db->get("vncp_nodes", ["name", "=", $admin_nodes[$k]->node])->all()[0]->hostname;
    echo "<td>" . $hostname . "</td>";
    echo "<td>" . $admin_nodes[$k]->node . "</td>";
    echo "<td>" . $admin_nodes[$k]->publicip . "</td>";
    echo "<td>" . $admin_nodes[$k]->natcidr . "</td>";
    $natcount = $db->get("vncp_natforwarding", ["node", "=", $admin_nodes[$k]->node])->all();
    echo "<td><a data-toggle=\"modal\" href=\"#\" data-target=\"#natnode_lvl1\" data-node=\"" . $admin_nodes[$k]->node . "\" style=\"text-decoration:underline;\">" . (string) count($natcount) . " / " . $admin_nodes[$k]->vmlimit . "</a></td>";
    echo "</tr>";
}
echo "\t\t\t\t\t\t</tbody>\r\n\t\t\t\t\t</table>\r\n\t\t\t\t</div>\r\n\t\t\t\t<h2 align=\"center\">Enable New NAT Node</h2>\r\n\t\t\t\t<form role=\"form\" action=\"\" method=\"POST\" id=\"adm_natnode_form\">\r\n          <div class=\"form-group\">\r\n              <label>Node <small>requires <a href=\"";
echo Config::get("admin/base") . "?action=tuntap";
echo "\">Node SSH</a> credentials to be added</small></label>\r\n              <select class=\"form-control\" name=\"natnode\" id=\"natnode\">\r\n                  <option value=\"default\">Select...</option>\r\n                  ";
$getnodes = $db->get("vncp_nodes", ["id", "!=", 0])->all();
for ($i = 0; $i < count($getnodes); $i++) {
    $hasSSH = $db->get("vncp_tuntap", ["node", "=", $getnodes[$i]->name])->all();
    $hasNAT = $db->get("vncp_nat", ["node", "=", $getnodes[$i]->name])->all();
    if (count($hasSSH) == 1 && count($hasNAT) == 0) {
        echo "<option value=\"" . $getnodes[$i]->name . "\">" . $getnodes[$i]->hostname . "</option>";
    }
}
echo "              </select>\r\n          </div>\r\n          <div class=\"form-group\">\r\n            <label>Node Public IP</label>\r\n            <input class=\"form-control\" type=\"text\" readonly name=\"natnodeip\" id=\"natnodeip\" value=\"\" />\r\n          </div>\r\n          <div class=\"form-group\">\r\n            <label>NAT IP Range (CIDR Format) <small>must be within 10.0.0.0/8, 172.16.0.0/12, or 192.168.0.0/16</small></label>\r\n            <input class=\"form-control\" type=\"text\" name=\"natiprange\" placeholder=\"10.10.10.0/24\" />\r\n          </div>\r\n          <div class=\"checkbox\">\r\n            <label>\r\n              <input type=\"checkbox\" name=\"natunderstand\" /> Check this box to confirm enabling this Proxmox node for NAT. This action cannot be undone - you cannot disable NAT afterwards. Once enabled, ProxCP will start managing port and domain forwarding for NAT virtual machines created on this node via <em>iptables</em> and <em>nginx</em>.<br /><br />NAT-enabled Proxmox nodes can have NAT virtual machines and non-NAT virtual machines on them.\r\n            </label>\r\n          </div>\r\n\t\t\t\t\t<input type=\"hidden\" name=\"token\" value=\"";
echo Token::generate();
echo "\" />\r\n\t\t\t\t\t<input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n\t\t\t\t</form>\r\n\t\t\t</div>\r\n\t\t</div>\r\n\t</div>\r\n  <link rel=\"stylesheet\" href=\"css/jquery-confirm.min.css\" />\r\n  <div class=\"modal fade\" id=\"natnode_lvl1\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"natnode_lvl1Label\">\r\n  <div class=\"modal-dialog modal-lg\" role=\"document\">\r\n    <div class=\"modal-content\">\r\n      <div class=\"modal-header\">\r\n        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>\r\n        <h4 class=\"modal-title\" id=\"natnode_lvl1Label\">Detailed View - </h4>\r\n      </div>\r\n      <div class=\"modal-body\">\r\n        <div id=\"lvl1_error\"></div>\r\n        <div class=\"table-responsive\">\r\n\t\t\t\t\t<table class=\"table table-hover\" id=\"admin_nodelvl1table\">\r\n\t\t\t\t\t\t<thead>\r\n\t\t\t\t\t\t\t<tr>\r\n                <th>User</th>\r\n\t\t\t\t\t\t\t\t<th>Billing ID</th>\r\n\t\t\t\t\t\t\t\t<th>NAT Ports</th>\r\n\t\t\t\t\t\t\t\t<th>NAT Domains</th>\r\n\t\t\t\t\t\t\t</tr>\r\n\t\t\t\t\t\t</thead>\r\n\t\t\t\t\t\t<tbody></tbody>\r\n\t\t\t\t\t</table>\r\n\t\t\t\t</div>\r\n      </div>\r\n    </div>\r\n  </div>\r\n</div>\r\n</div>\r\n";

?>
