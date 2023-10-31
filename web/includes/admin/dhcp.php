<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
echo "<div class=\"row\">\r\n\t<div class=\"col-md-12\">\r\n\t\t<div class=\"panel panel-default\">\r\n\t\t\t<div class=\"panel-body\">\r\n\t\t\t\t<div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Admin_-_Manage_DHCP\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div>\r\n\t\t\t\t<h2 align=\"center\">Manage DHCP Servers</h2><br />\r\n\t\t\t\t<div id=\"adm_message\"></div>\r\n\t\t\t\t<div class=\"table-responsive\">\r\n\t\t\t\t\t<table class=\"table table-hover\" id=\"admin_nodetable\">\r\n\t\t\t\t\t\t<thead>\r\n\t\t\t\t\t\t\t<tr>\r\n\t\t\t\t\t\t\t\t<th>Name</th>\r\n                <th>Network</th>\r\n\t\t\t\t\t\t\t\t<th>Delete</th>\r\n\t\t\t\t\t\t\t</tr>\r\n\t\t\t\t\t\t</thead>\r\n\t\t\t\t\t\t<tbody>\r\n\t\t\t\t\t\t\t";
$admin_datanodes = $db->get("vncp_dhcp_servers", ["id", "!=", 0]);
$admin_nodes = $admin_datanodes->all();
for ($k = 0; $k < count($admin_nodes); $k++) {
    echo "<tr>";
    echo "<td>" . $admin_nodes[$k]->hostname . "</td>";
    echo "<td>" . $admin_nodes[$k]->dhcp_network . "</td>";
    echo "<td><button id=\"admin_dhcpdelete" . $admin_nodes[$k]->id . "\" class=\"btn btn-sm btn-danger\" role=\"" . $admin_nodes[$k]->id . "\">Delete</button></td>";
    echo "</tr>";
}
echo "\t\t\t\t\t\t</tbody>\r\n\t\t\t\t\t</table>\r\n\t\t\t\t</div>\r\n        <div class=\"modal fade\" id=\"dhcpHelp\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"dhcpHelpLabel\">\r\n          <div class=\"modal-dialog\" role=\"document\">\r\n            <div class=\"modal-content\">\r\n              <div class=\"modal-header\">\r\n                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>\r\n                <h4 class=\"modal-title\" id=\"dhcpHelpLabel\">What is this for?</h4>\r\n              </div>\r\n              <div class=\"modal-body\">\r\n                <p>This feature works for DHCP servers you have running `isc-dhcp-server`. In order for your customers with KVM containers to be able to use DHCP for IP assignments, root SSH credentials are required to be stored to make the DHCP configuration changes. These credentials and SSH is used <strong>only</strong> to insert and remove DHCP static entries.</p>\r\n                <p>This is exactly what is run when inserting/removing a DHCP static assignment:</p>\r\n                <div class=\"well\"><pre>printf 'ddns-update-style none;\\n\\n' > /root/dhcpd.test\r\nprintf 'option domain-name-servers 8.8.8.8, 8.8.4.4;\\n\\n' >> /root/dhcpd.test\r\nprintf 'default-lease-time 7200;\\n' >> /root/dhcpd.test\r\nprintf 'max-lease-time 86400;\\n\\n' >> /root/dhcpd.test\r\nprintf 'log-facility local7;\\n\\n' >> /root/dhcpd.test\r\nprintf 'subnet {SUBNET} netmask {NETMASK} {}\\n\\n' >> /root/dhcpd.test\r\nprintf 'host {ID} {hardware ethernet {MACADDR};fixed-address {IP};option routers {GATEWAY};}\\n' >> /root/dhcpd.test\r\nmv /root/dhcpd.test /etc/dhcp/dhcpd.conf && rm /root/dhcpd.test\r\nservice isc-dhcp-server restart</pre></div>\r\n                <p>As with the Proxmox node credentials, the root password will be stored as an AES-256-CBC encrypted value.</p>\r\n              </div>\r\n              <div class=\"modal-footer\">\r\n                <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>\r\n              </div>\r\n            </div>\r\n          </div>\r\n        </div>\r\n\t\t\t\t<h2 align=\"center\">Add New Server (<a data-toggle=\"modal\" data-target=\"#dhcpHelp\" style=\"cursor:pointer;\">?</a>)</h2>\r\n\t\t\t\t<form role=\"form\" action=\"\" method=\"POST\">\r\n          <div class=\"form-group\">\r\n\t\t\t\t\t    <label>DHCP Server Hostname</label>\r\n\t\t\t\t\t    <input class=\"form-control\" type=\"text\" name=\"dhcphostname\" placeholder=\"dhcp.usa.domain.com\" />\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t    <label>Root Password</label>\r\n\t\t\t\t\t    <input class=\"form-control\" type=\"password\" name=\"rpassword\" />\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t    <label>SSH Port</label>\r\n\t\t\t\t\t    <input class=\"form-control\" type=\"text\" name=\"sshport\" placeholder=\"22\" />\r\n\t\t\t\t\t</div>\r\n          <div class=\"form-group\">\r\n              <label>DHCP Network</label>\r\n              <select class=\"form-control\" name=\"dhcpnetwork\">\r\n                  ";
$getnodes = $db->get_unique_network("vncp_dhcp", ["id", "!=", 0])->all();
for ($i = 0; $i < count($getnodes); $i++) {
    $unique = $db->get("vncp_dhcp_servers", ["dhcp_network", "=", $getnodes[$i]->network])->all();
    if (count($unique) < 1) {
        echo "<option value=\"" . $getnodes[$i]->network . "\">" . $getnodes[$i]->network . "</option>";
    }
}
echo "              </select>\r\n          </div>\r\n\t\t\t\t\t<input type=\"hidden\" name=\"token\" value=\"";
echo Token::generate();
echo "\" />\r\n\t\t\t\t\t<input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n\t\t\t\t</form>\r\n\t\t\t</div>\r\n\t\t</div>\r\n\t</div>\r\n</div>\r\n";

?>
