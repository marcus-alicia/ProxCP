<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
echo "<div class=\"row\">\r\n    <div class=\"col-md-12\">\r\n        <div class=\"panel panel-default\">\r\n            <div class=\"panel-body\">\r\n              <div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Admin_-_Manage_LXC/KVM_Properties\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div>\r\n                <h2 align=\"center\">Manage LXC/KVM Properties</h2><br />\r\n                <h4 align=\"center\">This form makes property changes in ProxCP only. Additional changes may need to be made in Proxmox too.</h4><br />\r\n                ";
if ($editedSuccess) {
    echo "<div id=\"adm_message\"><div class=\"alert alert-success\" role=\"alert\"><strong>Success:</strong> properties changed successfully!</div></div>";
} else {
    echo "<div id=\"adm_message\"></div>";
}
echo "                <form role=\"form\" action=\"\" method=\"POST\">\r\n                    <div class=\"form-group\">\r\n                        <label>Billing Account ID</label>\r\n                        <select class=\"form-control\" name=\"hbaccountid\" id=\"queryvmprops\">\r\n                            <option value=\"default\">Select...</option>\r\n                            ";
$hbaccountid = $db->get("vncp_lxc_ct", ["hb_account_id", "!=", 0]);
$hbaccountid = $hbaccountid->all();
for ($k = 0; $k < count($hbaccountid); $k++) {
    echo "<option value=\"" . $hbaccountid[$k]->hb_account_id . "\">ID: " . $hbaccountid[$k]->hb_account_id . " - LXC - " . $hbaccountid[$k]->ip . "</option>";
}
echo "                            ";
$hbaccountid = $db->get("vncp_kvm_ct", ["hb_account_id", "!=", 0]);
$hbaccountid = $hbaccountid->all();
for ($k = 0; $k < count($hbaccountid); $k++) {
    echo "<option value=\"" . $hbaccountid[$k]->hb_account_id . "\">ID: " . $hbaccountid[$k]->hb_account_id . " - KVM - " . $hbaccountid[$k]->ip . "</option>";
}
echo "                        </select>\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>User ID</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"userid\" id=\"userid\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>Node</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"vmnode\" id=\"vmnode\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>Operating System</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"vmos\" id=\"vmos\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>IPv4 Address</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"vmip\" id=\"vmip\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>IPv4 Gateway</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"vmip_gateway\" id=\"vmip_gateway\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>IPv4 Netmask</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"vmip_netmask\" id=\"vmip_netmask\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>Allow Backups (1 or 0)</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"vm_backups\" id=\"vm_backups\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>Backup Limit Override</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"vm_backup_override\" id=\"vm_backup_override\" />\r\n                        <p class=\"help-block\">Values: -1 = use global limit; Any other number will override global backup limit for this VM.</p>\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                      <label>Proxmox Pool Name</label>\r\n                      <input class=\"form-control\" type=\"text\" name=\"vm_poolname\" id=\"vm_poolname\" readonly />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                      <label>Proxmox Pool Password <small>modify only</small></label>\r\n                      <input class=\"form-control\" type=\"password\" name=\"vm_poolpw\" id=\"vm_poolpw\" />\r\n                    </div>\r\n                    <input type=\"hidden\" name=\"token\" value=\"";
echo Token::generate();
echo "\" />\r\n                    <input type=\"hidden\" name=\"vmip_old\" id=\"vmip_old\" value=\"\" />\r\n                    <input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n                </form>\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n";

?>
