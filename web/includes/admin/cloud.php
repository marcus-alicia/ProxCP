<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
echo "<div class=\"row\">\r\n    <div class=\"col-md-12\">\r\n        <div class=\"panel panel-default\">\r\n            <div class=\"panel-body\">\r\n              <div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Admin_-_Manage_Cloud\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div>\r\n                <h2 align=\"center\">Manage Cloud</h2><br />\r\n                ";
if ($cloudCreatedSuccess) {
    echo "<div id=\"adm_message\"><div class=\"alert alert-success\" role=\"alert\"><strong>Success:</strong> cloud created successfully!</div></div>";
} else {
    echo "<div id=\"adm_message\"></div>";
}
echo "                ";
$cloud_accounts = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($db->get("vncp_settings", ["item", "=", "cloud_accounts"])->first()->value);
if ($cloud_accounts == "true") {
    echo "                <div class=\"table-responsive\">\r\n                    <table class=\"table table-hover\" id=\"admin_cloudtable\">\r\n                        <thead>\r\n                            <tr>\r\n                                <th>User ID</th>\r\n                                <th>Billing ID</th>\r\n                                <th>Pool ID</th>\r\n                                <th>Node</th>\r\n                                <th>IP List</th>\r\n                                <th>Suspend</th>\r\n                                <th>Delete</th>\r\n                            </tr>\r\n                        </thead>\r\n                        <tbody>\r\n                            ";
    $admin_datacloud = $db->get("vncp_kvm_cloud", ["user_id", "!=", 0]);
    $admin_datacloud = $admin_datacloud->all();
    for ($k = 0; $k < count($admin_datacloud); $k++) {
        echo "<tr>";
        echo "<td>" . $admin_datacloud[$k]->user_id . "</td>";
        echo "<td>" . $admin_datacloud[$k]->hb_account_id . "</td>";
        echo "<td>" . $admin_datacloud[$k]->pool_id . "</td>";
        echo "<td>" . $admin_datacloud[$k]->nodes . "</td>";
        echo "<td>" . $admin_datacloud[$k]->ipv4 . "</td>";
        if ($admin_datacloud[$k]->suspended == 0) {
            echo "<td><button class=\"btn btn-danger btn-sm\" id=\"cloudsuspend" . $admin_datacloud[$k]->hb_account_id . "\" role=\"" . $admin_datacloud[$k]->hb_account_id . "\">Suspend</button></td>";
        } else {
            echo "<td><button class=\"btn btn-success btn-sm\" id=\"cloudunsuspend" . $admin_datacloud[$k]->hb_account_id . "\" role=\"" . $admin_datacloud[$k]->hb_account_id . "\">Unsuspend</button></td>";
        }
        echo "<td><button id=\"admin_clouddelete" . $admin_datacloud[$k]->hb_account_id . "\" class=\"btn btn-sm btn-danger\" role=\"" . $admin_datacloud[$k]->hb_account_id . "\">Delete</button></td>";
        echo "</tr>";
    }
    echo "                        </tbody>\r\n                    </table>\r\n                </div>\r\n                <ul class=\"nav nav-tabs\" role=\"tablist\">\r\n                  <li role=\"presentation\" class=\"active\"><a href=\"#createcloudacc\" aria-controls=\"createcloudacc\" role=\"tab\" data-toggle=\"tab\">Create Cloud Account</a></li>\r\n                  <li role=\"presentation\"><a href=\"#editcloudacc\" aria-controls=\"editcloudacc\" role=\"tab\" data-toggle=\"tab\">Edit Cloud Account</a></li>\r\n                </ul>\r\n                <div class=\"tab-content\">\r\n                  <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"createcloudacc\"><br />\r\n                    <form role=\"form\" action=\"\" method=\"POST\">\r\n                        <div class=\"form-group\">\r\n                            <label>User ID</label><br />\r\n                            <select class=\"form-control selectpicker\" data-live-search=\"true\" name=\"userid\">\r\n                              <option value=\"default\">Select...</option>\r\n                              ";
    $userdata = $db->get("vncp_users", ["id", "!=", 0])->all();
    for ($i = 0; $i < count($userdata); $i++) {
        echo "<option value=\"" . $userdata[$i]->id . "\">" . $userdata[$i]->email . " (ID: " . $userdata[$i]->id . ")</option>";
    }
    echo "                            </select>\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>Billing Account ID</label>\r\n                            <input class=\"form-control\" type=\"text\" name=\"hb_account_id\" placeholder=\"100\" />\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>Pool ID</label>\r\n                            <input class=\"form-control\" type=\"text\" name=\"poolid\" placeholder=\"client_id_1\" />\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>Node</label>\r\n                            <select class=\"form-control\" name=\"node\">\r\n                            \t<option value=\"default\">Select...</option>\r\n                            \t";
    $cloud_node = $db->get("vncp_nodes", ["id", "!=", 0]);
    $cloud_node = $cloud_node->all();
    for ($k = 0; $k < count($cloud_node); $k++) {
        echo "<option value=\"" . $cloud_node[$k]->name . "\">" . $cloud_node[$k]->hostname . "</option>";
    }
    echo "                            </select>\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>IPv4 List</label>\r\n                            <input class=\"form-control\" type=\"text\" name=\"ipv4\" placeholder=\"1.1.1.4;1.1.1.5;1.1.1.6\" />\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>CPU Cores</label>\r\n                            <input class=\"form-control\" type=\"text\" name=\"cpucores\" placeholder=\"1\" />\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>CPU Type</label>\r\n                            <select class=\"form-control\" name=\"cputype\">\r\n                                <option value=\"default\">Select...</option>\r\n                                <option value=\"host\">Host (passthrough)</option>\r\n                                <option value=\"kvm64\">kvm64</option>\r\n                                <option value=\"qemu64\">qemu64</option>\r\n                            </select>\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>RAM (MB)</label>\r\n                            <input class=\"form-control\" type=\"text\" name=\"ram\" placeholder=\"2048\" />\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>Storage Size (GB)</label>\r\n                            <input class=\"form-control\" type=\"text\" name=\"storage_size\" placeholder=\"100\" />\r\n                        </div>\r\n                        <input type=\"hidden\" name=\"whatform\" value=\"createcloud\" />\r\n                        <input type=\"hidden\" name=\"token\" value=\"";
    echo Token::generate();
    echo "\" />\r\n                        <input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n                    </form>\r\n                  </div>\r\n                  <div role=\"tabpanel\" class=\"tab-pane fade\" id=\"editcloudacc\"><br />\r\n                    <form role=\"form\">\r\n                        <div class=\"form-group\">\r\n                            <label>Billing Account ID</label>\r\n                            <select class=\"form-control\" id=\"getcloudhbid\">\r\n                            \t<option value=\"default\">Select...</option>\r\n                            \t";
    $cloud_hbid = $db->get("vncp_kvm_cloud", ["user_id", "!=", 0]);
    $cloud_hbid = $cloud_hbid->all();
    for ($k = 0; $k < count($cloud_hbid); $k++) {
        echo "<option value=\"" . $cloud_hbid[$k]->hb_account_id . "\">" . $cloud_hbid[$k]->hb_account_id . "</option>";
    }
    echo "                            </select>\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>All IPv4 List</label>\r\n                            <input class=\"form-control\" type=\"text\" id=\"getipv4\" placeholder=\"1.1.1.4;1.1.1.5;1.1.1.6\" />\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>Available IPv4 List</label>\r\n                            <input class=\"form-control\" type=\"text\" id=\"getipv4_avail\" placeholder=\"1.1.1.4;1.1.1.6\" />\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>All CPU Cores</label>\r\n                            <input class=\"form-control\" type=\"text\" id=\"getcpucores\" placeholder=\"3\" />\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>Available CPU Cores</label>\r\n                            <input class=\"form-control\" type=\"text\" id=\"getcpucores_avail\" placeholder=\"1\" />\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>All RAM (MB)</label>\r\n                            <input class=\"form-control\" type=\"text\" id=\"getram\" placeholder=\"2048\" />\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>Available RAM (MB)</label>\r\n                            <input class=\"form-control\" type=\"text\" id=\"getram_avail\" placeholder=\"512\" />\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>All Storage Size (GB)</label>\r\n                            <input class=\"form-control\" type=\"text\" id=\"getstorage_size\" placeholder=\"100\" />\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>Available Storage Size (GB)</label>\r\n                            <input class=\"form-control\" type=\"text\" id=\"getstorage_size_avail\" placeholder=\"30\" />\r\n                        </div>\r\n                        <input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" id=\"editcloudaccount\" />\r\n                    </form>\r\n                  </div>\r\n                </div>\r\n                ";
} else {
    echo "<p>Cloud accounts are not enabled. Go to " . $appname . " settings to enable it.</p>";
}
echo "            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n";

?>
