<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
echo "<div class=\"row\">\r\n    <div class=\"col-md-12\">\r\n        <div class=\"panel panel-default\">\r\n            <div class=\"panel-body\">\r\n              <div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Admin_-_Manage_KVM_Templates\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div>\r\n                <h2 align=\"center\">Manage KVM Templates</h2><br />\r\n                ";
if ($kvmTempSuccess) {
    echo "<div id=\"adm_message\"><div class=\"alert alert-success\" role=\"alert\"><strong>Success:</strong> template added to database! Make sure the template is in proxmox too.</div></div>";
} else {
    echo "<div id=\"adm_message\"></div>";
}
echo "                <div class=\"table-responsive\">\r\n                    <table class=\"table table-hover\" id=\"admin_lxctemptable\">\r\n                        <thead>\r\n                            <tr>\r\n                                <th>Friendly Name</th>\r\n                                <th>VMID@Node</th>\r\n                                <th>Type</th>\r\n                                <th>Delete</th>\r\n                            </tr>\r\n                        </thead>\r\n                        <tbody>\r\n                            ";
$admin_datalxctemp = $db->get("vncp_kvm_templates", ["id", "!=", 0]);
$admin_datalxctemp = $admin_datalxctemp->all();
for ($k = 0; $k < count($admin_datalxctemp); $k++) {
    echo "<tr>";
    echo "<td>" . $admin_datalxctemp[$k]->friendly_name . "</td>";
    echo "<td>" . $admin_datalxctemp[$k]->vmid . "@" . $admin_datalxctemp[$k]->node . "</td>";
    echo "<td>" . $admin_datalxctemp[$k]->type . "</td>";
    echo "<td><button id=\"admin_kvmtempdelete" . $admin_datalxctemp[$k]->id . "\" class=\"btn btn-sm btn-danger\" role=\"" . $admin_datalxctemp[$k]->id . "\">Delete</button></td>";
    echo "</tr>";
}
echo "                        </tbody>\r\n                    </table>\r\n                </div>\r\n                <h2 align=\"center\">Add New KVM Template</h2>\r\n                <form role=\"form\" action=\"\" method=\"POST\">\r\n                    <div class=\"form-group\">\r\n                        <label>Friendly Name</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"fname\" placeholder=\"CentOS 7\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>VMID</label>\r\n                        <input class=\"form-control\" type=\"number\" min=\"100\" name=\"template_vmid\" placeholder=\"100\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                      <label>Template Type</label>\r\n                      <select class=\"form-control\" name=\"template_type\">\r\n                        <option value=\"default\">Select...</option>\r\n                        <option value=\"windows\">Windows</option>\r\n                        <option value=\"linux\">Linux</option>\r\n                      </select>\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>Node</label>\r\n                        <select class=\"form-control\" name=\"template_node\">\r\n                            <option value=\"default\">Select...</option>\r\n                            ";
$getnodes = $db->get("vncp_nodes", ["id", "!=", 0])->all();
for ($i = 0; $i < count($getnodes); $i++) {
    echo "<option value=\"" . $getnodes[$i]->name . "\">" . $getnodes[$i]->hostname . "</option>";
}
echo "                        </select>\r\n                    </div>\r\n                    <input type=\"hidden\" name=\"token\" value=\"";
echo Token::generate();
echo "\" />\r\n                    <input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n                </form>\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n";

?>
