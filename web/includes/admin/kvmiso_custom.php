<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
echo "<div class=\"row\">\r\n    <div class=\"col-md-12\">\r\n        <div class=\"panel panel-default\">\r\n            <div class=\"panel-body\">\r\n              <div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Admin_-_Manage_Custom_KVM_ISO\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div>\r\n                <h2 align=\"center\">Manage Custom KVM ISOs</h2><br />\r\n                ";
$custom_iso = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($db->get("vncp_settings", ["item", "=", "user_iso_upload"])->first()->value);
if ($custom_iso == "true") {
    echo "                <div id=\"adm_message\"></div>\r\n                <div class=\"table-responsive\">\r\n                    <table class=\"table table-hover\" id=\"admin_kvmisotable\">\r\n                        <thead>\r\n                            <tr>\r\n                                <th>User ID</th>\r\n                                <th>Friendly Name</th>\r\n                                <th>Type</th>\r\n                                <th>Real Name</th>\r\n                                <th>Upload Date</th>\r\n                                <th>Status</th>\r\n                                <th>Delete</th>\r\n                            </tr>\r\n                        </thead>\r\n                        <tbody>\r\n                            ";
    $admin_datakvmiso = $db->get("vncp_kvm_isos_custom", ["id", "!=", 0]);
    $admin_datakvmiso = $admin_datakvmiso->all();
    for ($k = 0; $k < count($admin_datakvmiso); $k++) {
        echo "<tr>";
        echo "<td>" . $admin_datakvmiso[$k]->user_id . "</td>";
        echo "<td>" . $admin_datakvmiso[$k]->fname . "</td>";
        echo "<td>" . $admin_datakvmiso[$k]->type . "</td>";
        echo "<td>" . $admin_datakvmiso[$k]->upload_key . ".iso</td>";
        echo "<td>" . $admin_datakvmiso[$k]->upload_date . "</td>";
        echo "<td>" . $admin_datakvmiso[$k]->status . "</td>";
        echo "<td><button id=\"admin_kvmcustomisodelete" . $admin_datakvmiso[$k]->id . "\" class=\"btn btn-sm btn-danger\" role=\"" . $admin_datakvmiso[$k]->id . "\">Delete</button></td>";
        echo "</tr>";
    }
    echo "                        </tbody>\r\n                    </table>\r\n                </div>\r\n                ";
} else {
    echo "<p>User ISO uploads are not enabled. Go to " . $appname . " settings to enable it.</p>";
}
echo "            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n";

?>
