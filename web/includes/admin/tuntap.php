<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
echo "<div class=\"row\">\r\n\t<div class=\"col-md-2\">\r\n    <ul class=\"nav nav-pills nav-stacked\">\r\n      <li role=\"presentation\"><a href=\"";
echo Config::get("admin/base") . "?action=nodes";
echo "\">Manage Nodes</a></li>\r\n      <li role=\"presentation\" class=\"active\"><a href=\"";
echo Config::get("admin/base") . "?action=tuntap";
echo "\">Manage Node SSH</a></li>\r\n      <li role=\"presentation\"><a href=\"";
echo Config::get("admin/base") . "?action=natnodes";
echo "\">Manage NAT Nodes</a></li>\r\n    </ul>\r\n  </div>\r\n\t<div class=\"col-md-10\">\r\n\t\t<div class=\"panel panel-default\">\r\n\t\t\t<div class=\"panel-body\">\r\n\t\t\t\t<div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Admin_-_Manage_Node_SSH\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div>\r\n\t\t\t\t<h2 align=\"center\">Manage SSH Super Credentials</h2><br />\r\n\t\t\t\t<div id=\"adm_message\"></div>\r\n\t\t\t\t<div class=\"table-responsive\">\r\n\t\t\t\t\t<table class=\"table table-hover\" id=\"admin_nodetable\">\r\n\t\t\t\t\t\t<thead>\r\n\t\t\t\t\t\t\t<tr>\r\n\t\t\t\t\t\t\t\t<th>Name</th>\r\n\t\t\t\t\t\t\t\t<th>Delete</th>\r\n\t\t\t\t\t\t\t</tr>\r\n\t\t\t\t\t\t</thead>\r\n\t\t\t\t\t\t<tbody>\r\n\t\t\t\t\t\t\t";
$admin_datanodes = $db->get("vncp_tuntap", ["id", "!=", 0]);
$admin_nodes = $admin_datanodes->all();
for ($k = 0; $k < count($admin_nodes); $k++) {
    echo "<tr>";
    echo "<td>" . $admin_nodes[$k]->node . "</td>";
    echo "<td><button id=\"admin_tuntapdelete" . $admin_nodes[$k]->id . "\" class=\"btn btn-sm btn-danger\" role=\"" . $admin_nodes[$k]->id . "\">Delete</button></td>";
    echo "</tr>";
}
echo "\t\t\t\t\t\t</tbody>\r\n\t\t\t\t\t</table>\r\n\t\t\t\t</div>\r\n        <div class=\"modal fade\" id=\"tuntapHelp\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"tuntapHelpLabel\">\r\n          <div class=\"modal-dialog\" role=\"document\">\r\n            <div class=\"modal-content\">\r\n              <div class=\"modal-header\">\r\n                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>\r\n                <h4 class=\"modal-title\" id=\"tuntapHelpLabel\">What is this for?</h4>\r\n              </div>\r\n              <div class=\"modal-body\">\r\n                <p>Some ProxCP features require root access to your Proxmox nodes. These credentials and SSH are used <strong>only</strong> to: enable/disable the TUN/TAP interface (LXC), change root passwords (LXC), move custom ISO uploads to Proxmox nodes (KVM), enable/disable VirtIO RNG (KVM), or manage NAT.</p>\r\n                <p>This is exactly what is run when enabling TUN/TAP:</p>\r\n                <div class=\"well\"><pre>printf \\'lxc.cgroup.devices.allow: c 10:200 rwm\\nlxc.hook.autodev = sh -c \"modprobe tun; cd \${LXC_ROOTFS_MOUNT}/dev; mkdir net; mknod net/tun c 10 200; chmod 0666 net/tun\"\\' >> /etc/pve/lxc/{VMID}.conf</pre></div>\r\n                <p>This is exactly what is run when disabling TUN/TAP:</p>\r\n                <div class=\"well\"><pre>grep -v \"lxc.cgroup.devices.allow: c 10:200 rwm\" /etc/pve/lxc/{VMID}.conf > {VMID}.temp && mv {VMID}.temp /etc/pve/lxc/{VMID}.conf && grep -v \\'lxc.hook.autodev = sh -c \"modprobe tun; cd \${LXC_ROOTFS_MOUNT}/dev; mkdir net; mknod net/tun c 10 200; chmod 0666 net/tun\"\\' /etc/pve/lxc/{VMID}.conf > {VMID}.temp && mv {VMID}.temp /etc/pve/lxc/{VMID}.conf</pre></div>\r\n\t\t\t\t\t\t\t\t<p>This is exactly what is run when moving custom ISO uploads:</p>\r\n\t\t\t\t\t\t\t\t<div class=\"well\"><pre>wget -bqc -O {NAME}.iso https://url.com/files/{KEY}/get</pre></div>\r\n\t\t\t\t\t\t\t\t<p>This is exactly what is run when enabling/disabling RNG:</p>\r\n\t\t\t\t\t\t\t\t<div class=\"well\"><pre>pvesh set /nodes/{NODE}/qemu/{VMID}/config --rng0 source=/dev/urandom,max_bytes=1024,period=1000 || pvesh set /nodes/{NODE}/qemu/{VMID}/config --delete rng0</pre></div>\r\n                <p>As with the Proxmox node credentials, the root password will be stored as an AES-256-CBC encrypted value and used only when necessary.</p>\r\n              </div>\r\n              <div class=\"modal-footer\">\r\n                <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>\r\n              </div>\r\n            </div>\r\n          </div>\r\n        </div>\r\n\t\t\t\t<h2 align=\"center\">Add New Credentials (<a data-toggle=\"modal\" data-target=\"#tuntapHelp\" style=\"cursor:pointer;\">More info</a>)</h2>\r\n\t\t\t\t<form role=\"form\" action=\"\" method=\"POST\">\r\n          <div class=\"form-group\">\r\n              <label>Node</label>\r\n              <select class=\"form-control\" name=\"tuntapnode\">\r\n\t\t\t\t\t\t\t\t\t<option value=\"default\">Select...</option>\r\n                  ";
$getnodes = $db->get("vncp_nodes", ["id", "!=", 0])->all();
for ($i = 0; $i < count($getnodes); $i++) {
    $hasSSH = $db->get("vncp_tuntap", ["node", "=", $getnodes[$i]->name])->all();
    if (count($hasSSH) == 0) {
        echo "<option value=\"" . $getnodes[$i]->name . "\">" . $getnodes[$i]->hostname . "</option>";
    }
}
echo "              </select>\r\n          </div>\r\n\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t    <label>Root Password</label>\r\n\t\t\t\t\t    <input class=\"form-control\" type=\"password\" name=\"rpassword\" />\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t    <label>SSH Port</label>\r\n\t\t\t\t\t    <input class=\"form-control\" type=\"text\" name=\"sshport\" placeholder=\"22\" />\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<input type=\"hidden\" name=\"token\" value=\"";
echo Token::generate();
echo "\" />\r\n\t\t\t\t\t<input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n\t\t\t\t</form>\r\n\t\t\t</div>\r\n\t\t</div>\r\n\t</div>\r\n</div>\r\n";

?>
