<?php
if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
$t = Token::generate();
echo "<div class=\"row\">\r\n    <div class=\"col-md-12\">\r\n        <div class=\"panel panel-default\">\r\n            <div class=\"panel-body\">\r\n              <div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Settings_Overview\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div>\r\n                <h2 align=\"center\">";
echo $appname;
echo " Settings</h2>\r\n                ";
if ($adminSettingsUpdated) {
    echo "<div id=\"adm_message\"><div class=\"alert alert-success\" role=\"alert\"><strong>Success:</strong> application settings updated!</div></div>";
} else {
    echo "<div id=\"adm_message\"></div>";
}
echo "                ";
$fetch = $db->get("vncp_settings", ["id", "!=", 0]);
$fetch = $fetch->all();
$current = [];
for ($i = 0; $i < count($fetch); $i++) {
    $current[$fetch[$i]->item] = $fetch[$i]->value;
}
echo "                <div>\r\n                  <ul class=\"nav nav-tabs\" role=\"tablist\">\r\n                    <li role=\"presentation\" class=\"active\"><a href=\"#general\" aria-controls=\"general\" role=\"tab\" data-toggle=\"tab\">General</a></li>\r\n                    <li role=\"presentation\"><a href=\"#mail\" aria-controls=\"mail\" role=\"tab\" data-toggle=\"tab\">Mail</a></li>\r\n                  </ul>\r\n                  <div class=\"tab-content\">\r\n                    <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"general\"><br />\r\n                      <form role=\"form\" action=\"\" method=\"POST\">\r\n                      \t<div class=\"form-group\">\r\n                      \t    <label>App Name</label>\r\n                      \t    <input class=\"form-control\" type=\"text\" name=\"app_name\" value=\"";
echo _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["app_name"]);
echo "\" />\r\n                      \t</div>\r\n                        <div class=\"form-group\">\r\n                      \t    <label>Default Language</label>\r\n                            <select class=\"form-control\" name=\"default_language\">\r\n                              <option value=\"";
echo _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["default_language"]) . ".json";
echo "\">";
echo _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["default_language"]) . ".json";
echo "</option>\r\n                              ";
foreach (glob("lang/*.json") as $file) {
    list($file) = explode("/", $file);
    if ($file != $current["default_language"] . ".json") {
        echo "<option value=\"" . $file . "\">" . $file . "</option>";
    }
}
echo "                            </select>\r\n                      \t</div>\r\n                        <div class=\"form-group\">\r\n                      \t    <label>Enable WHMCS Invoice/Support Integration</label>\r\n                            <select class=\"form-control\" name=\"enable_whmcs\">\r\n                              ";
if ($current["enable_whmcs"] == "false") {
    echo "<option value=\"false\">False</option><option value=\"true\">True</option>";
} else {
    echo "<option value=\"true\">True</option><option value=\"false\">False</option>";
}
echo "                            </select>\r\n                      \t</div>\r\n                        ";
if ($current["enable_whmcs"] == "true") {
    echo "                        <div class=\"form-group\">\r\n                            <label>WHMCS API URL</label>\r\n                            <input class=\"form-control\" type=\"text\" name=\"whmcs_url\" value=\"";
    echo $current["whmcs_url"];
    echo "\" />\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>WHMCS API ID</label>\r\n                            <input class=\"form-control\" type=\"text\" name=\"whmcs_id\" value=\"";
    echo $current["whmcs_id"];
    echo "\" />\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>WHMCS API Key</label>\r\n                            <input class=\"form-control\" type=\"text\" name=\"whmcs_key\" value=\"";
    echo $current["whmcs_key"];
    echo "\" />\r\n                        </div>\r\n                        ";
}
echo "                          <div class=\"form-group\">\r\n                              <label>Enable Firewall</label>\r\n                              <select class=\"form-control\" name=\"enable_firewall\">\r\n                              \t";
if ($current["enable_firewall"] == "false") {
    echo "<option value=\"false\">False</option><option value=\"true\">True</option>";
} else {
    echo "<option value=\"true\">True</option><option value=\"false\">False</option>";
}
echo "                              </select>\r\n                          </div>\r\n                          <div class=\"form-group\">\r\n                              <label>Enable Forward DNS</label>\r\n                              <select class=\"form-control\" name=\"enable_forward_dns\">\r\n                              \t";
if ($current["enable_forward_dns"] == "false") {
    echo "<option value=\"false\">False</option><option value=\"true\">True</option>";
} else {
    echo "<option value=\"true\">True</option><option value=\"false\">False</option>";
}
echo "                              </select>\r\n                          </div>\r\n                          <div class=\"form-group\">\r\n                              <label>Enable Reverse DNS</label>\r\n                              <select class=\"form-control\" name=\"enable_reverse_dns\">\r\n                              \t";
if ($current["enable_reverse_dns"] == "false") {
    echo "<option value=\"false\">False</option><option value=\"true\">True</option>";
} else {
    echo "<option value=\"true\">True</option><option value=\"false\">False</option>";
}
echo "                              </select>\r\n                          </div>\r\n                          ";
if ($current["enable_forward_dns"] == "true" || $current["enable_reverse_dns"] == "true") {
    echo "                          <div class=\"form-group\">\r\n                              <label>WHM URL</label>\r\n                              <input class=\"form-control\" type=\"text\" name=\"whmurl\" value=\"";
    echo $current["whm_url"];
    echo "\" />\r\n                          </div>\r\n                          <div class=\"form-group\">\r\n                              <label>WHM Username</label>\r\n                              <input class=\"form-control\" type=\"text\" name=\"whmusername\" value=\"";
    echo $current["whm_username"];
    echo "\" />\r\n                          </div>\r\n                          <div class=\"form-group\">\r\n                              <label>WHM API Token</label>\r\n                              <input class=\"form-control\" type=\"text\" name=\"whmapitoken\" value=\"";
    echo $current["whm_api_token"];
    echo "\" />\r\n                          </div>\r\n                          ";
}
if ($current["enable_forward_dns"] == "true") {
    echo "                          <div class=\"form-group\">\r\n                              <label>Forward DNS Domain Limit (per user)</label>\r\n                              <input class=\"form-control\" type=\"text\" name=\"fdnslimit\" value=\"";
    echo $current["forward_dns_domain_limit"];
    echo "\" />\r\n                          </div>\r\n                          <div class=\"form-group\">\r\n                              <label>Forward DNS Domain Blacklist (; separated)</label>\r\n                              <input class=\"form-control\" type=\"text\" name=\"fdnsblacklist\" value=\"";
    echo $current["forward_dns_blacklist"];
    echo "\" />\r\n                          </div>\r\n                          <div class=\"form-group\">\r\n                              <label>Forward DNS Nameservers (; separated)</label>\r\n                              <input class=\"form-control\" type=\"text\" name=\"fdnsnameservers\" value=\"";
    echo $current["forward_dns_nameservers"];
    echo "\" />\r\n                          </div>\r\n                          ";
}
echo "                          <div class=\"form-group\">\r\n                              <label>Enable Notepad</label>\r\n                              <select class=\"form-control\" name=\"enable_notepad\">\r\n                              \t";
if ($current["enable_notepad"] == "false") {
    echo "<option value=\"false\">False</option><option value=\"true\">True</option>";
} else {
    echo "<option value=\"true\">True</option><option value=\"false\">False</option>";
}
echo "                              </select>\r\n                          </div>\r\n                          <div class=\"form-group\">\r\n                              <label>Enable Status</label>\r\n                              <select class=\"form-control\" name=\"enable_status\">\r\n                              \t";
if ($current["enable_status"] == "false") {
    echo "<option value=\"false\">False</option><option value=\"true\">True</option>";
} else {
    echo "<option value=\"true\">True</option><option value=\"false\">False</option>";
}
echo "                              </select>\r\n                          </div>\r\n                          <div class=\"form-group\">\r\n                              <label>Enable App News</label>\r\n                              <select class=\"form-control\" name=\"enable_panel_news\">\r\n                              \t";
if ($current["enable_panel_news"] == "false") {
    echo "<option value=\"false\">False</option><option value=\"true\">True</option>";
} else {
    echo "<option value=\"true\">True</option><option value=\"false\">False</option>";
}
echo "                              </select>\r\n                          </div>\r\n                          ";
if ($current["enable_panel_news"] == "true") {
    echo "                          <div class=\"form-group\">\r\n                              <label>App News</label>\r\n                              <textarea class=\"form-control\" name=\"panel_news\" rows=\"5\" style=\"resize:none;\">";
    echo $current["panel_news"];
    echo "</textarea>\r\n                          </div>\r\n                          ";
}
echo "                          <div class=\"form-group\">\r\n                              <label>Support Ticket URL</label>\r\n                              <input class=\"form-control\" type=\"text\" name=\"support_ticket_url\" value=\"";
echo _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["support_ticket_url"]);
echo "\" />\r\n                          </div>\r\n                          <div class=\"form-group\">\r\n                              <label>Enable User ACL</label>\r\n                              <select class=\"form-control\" name=\"user_acl\">\r\n                              \t";
if ($current["user_acl"] == "false") {
    echo "<option value=\"false\">False</option><option value=\"true\">True</option>";
} else {
    echo "<option value=\"true\">True</option><option value=\"false\">False</option>";
}
echo "                              </select>\r\n                          </div>\r\n                          <div class=\"form-group\">\r\n                              <label>Enable Cloud Accounts</label>\r\n                              <select class=\"form-control\" name=\"cloud_accounts\">\r\n                              \t";
if ($current["cloud_accounts"] == "false") {
    echo "<option value=\"false\">False</option><option value=\"true\">True</option>";
} else {
    echo "<option value=\"true\">True</option><option value=\"false\">False</option>";
}
echo "                              </select>\r\n                          </div>\r\n                          <div class=\"form-group\">\r\n                              <label>Enable VM IPv6</label>\r\n                              <select class=\"form-control\" name=\"vm_ipv6\">\r\n                              \t";
if ($current["vm_ipv6"] == "false") {
    echo "<option value=\"false\">False</option><option value=\"true\">True</option>";
} else {
    echo "<option value=\"true\">True</option><option value=\"false\">False</option>";
}
echo "                              </select>\r\n                          </div>\r\n                          ";
if ($current["vm_ipv6"] == "true") {
    echo "                          <div class=\"form-group\">\r\n                              <label>IPv6 Assignment Mode</label>\r\n                              <select class=\"form-control\" name=\"ipv6mode\">\r\n                              \t";
    if ($current["ipv6_mode"] == "single") {
        echo "<option value=\"single\">Single</option><option value=\"subnet\">Subnet</option>";
    } else {
        echo "<option value=\"subnet\">Subnet</option><option value=\"single\">Single</option>";
    }
    echo "                              </select>\r\n                          </div>\r\n                          ";
}
echo "                          ";
if ($current["vm_ipv6"] == "true" && $current["ipv6_mode"] == "single") {
    echo "                          <div class=\"form-group\">\r\n                              <label>IPv6 Limit (per VM)</label>\r\n                              <input class=\"form-control\" type=\"text\" name=\"ipv6lim\" value=\"";
    echo $current["ipv6_limit"];
    echo "\" />\r\n                          </div>\r\n                        ";
} else {
    if ($current["vm_ipv6"] == "true" && $current["ipv6_mode"] == "subnet") {
        echo "                          <div class=\"form-group\">\r\n                              <label>IPv6 /64 Subnets (per VM)</label>\r\n                              <input class=\"form-control\" type=\"text\" name=\"ipv6limsubnet\" value=\"";
        echo $current["ipv6_limit_subnet"];
        echo "\" />\r\n                          </div>\r\n                        ";
    }
}
echo "                          <div class=\"form-group\">\r\n                              <label>Enable Private Networking</label>\r\n                              <select class=\"form-control\" name=\"private_networking\">\r\n                              \t";
if ($current["private_networking"] == "false") {
    echo "<option value=\"false\">False</option><option value=\"true\">True</option>";
} else {
    echo "<option value=\"true\">True</option><option value=\"false\">False</option>";
}
echo "                              </select>\r\n                          </div>\r\n                          <div class=\"form-group\">\r\n                              <label>Auto Suspend Bandwidth Overage <small>runs once per day</small></label>\r\n                              <select class=\"form-control\" name=\"bw_auto_suspend\">\r\n                              \t";
if ($current["bw_auto_suspend"] == "false") {
    echo "<option value=\"false\">False</option><option value=\"true\">True</option>";
} else {
    echo "<option value=\"true\">True</option><option value=\"false\">False</option>";
}
echo "                              </select>\r\n                          </div>\r\n                          <div class=\"form-group\">\r\n                              <label>Enable Secondary IPs</label>\r\n                              <select class=\"form-control\" name=\"secondary_ips\">\r\n                              \t";
if ($current["secondary_ips"] == "false") {
    echo "<option value=\"false\">False</option><option value=\"true\">True</option>";
} else {
    echo "<option value=\"true\">True</option><option value=\"false\">False</option>";
}
echo "                              </select>\r\n                          </div>\r\n                          <div class=\"form-group\">\r\n                              <label>Enable VM Backups</label>\r\n                              <select class=\"form-control\" name=\"vmbackups\">\r\n                                  ";
if ($current["enable_backups"] == "false") {
    echo "<option value=\"false\">False</option><option value=\"true\">True</option>";
} else {
    echo "<option value=\"true\">True</option><option value=\"false\">False</option>";
}
echo "                              </select>\r\n                          </div>\r\n                          ";
if ($current["enable_backups"] == "true") {
    echo "                          <div class=\"form-group\">\r\n                              <label>Backup Limit (per VM)</label>\r\n                              <input class=\"form-control\" type=\"text\" name=\"backuplim\" value=\"";
    echo $current["backup_limit"];
    echo "\" />\r\n                          </div>\r\n                          ";
}
echo "                          <div class=\"form-group\">\r\n                              <label>Enable User ISO Uploads</label>\r\n                              <select class=\"form-control\" name=\"user_iso_upload\">\r\n                              \t";
if ($current["user_iso_upload"] == "false") {
    echo "<option value=\"false\">False</option><option value=\"true\">True</option>";
} else {
    echo "<option value=\"true\">True</option><option value=\"false\">False</option>";
}
echo "                              </select>\r\n                          </div>\r\n                          <input type=\"hidden\" name=\"token\" value=\"";
echo $t;
echo "\" />\r\n                          <input type=\"hidden\" name=\"whatform\" value=\"general_settings\" />\r\n                          <input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n                      </form>\r\n                    </div>\r\n                    <div role=\"tabpanel\" class=\"tab-pane fade\" id=\"mail\"><br />\r\n                      <form role=\"form\" action=\"\" method=\"POST\">\r\n                          <div class=\"form-group\">\r\n                            <label>Mail Type</label>\r\n                            <select class=\"form-control\" name=\"mail_type\">\r\n                              ";
if ($current["mail_type"] == "sysmail") {
    echo "<option value=\"sysmail\">PHP mail()</option><option value=\"smtp\">SMTP</option>";
} else {
    echo "<option value=\"smtp\">SMTP</option><option value=\"sysmail\">PHP mail()</option>";
}
echo "                            </select>\r\n                          </div>\r\n                          <hr />\r\n                          <div class=\"form-group\">\r\n                            <label>From Name</label>\r\n                            <input class=\"form-control\" type=\"text\" name=\"from_email_addr_name\" value=\"";
echo _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["from_email_name"]);
echo "\" />\r\n                          </div>\r\n                          <div class=\"form-group\">\r\n                              <label>From Email Address</label>\r\n                              <input class=\"form-control\" type=\"text\" name=\"from_email_addr\" value=\"";
echo _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["from_email"]);
echo "\" />\r\n                          </div>\r\n                          <hr />\r\n                          <div class=\"form-group\">\r\n                              <label>SMTP Host</label>\r\n                              <input class=\"form-control\" type=\"text\" name=\"smtp_host\" value=\"";
echo _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["smtp_host"]);
echo "\" />\r\n                          </div>\r\n                          <div class=\"form-group\">\r\n                              <label>SMTP Port</label>\r\n                              <input class=\"form-control\" type=\"number\" name=\"smtp_port\" value=\"";
echo _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["smtp_port"]);
echo "\" min=\"1\" max=\"65535\" />\r\n                          </div>\r\n                          <div class=\"form-group\">\r\n                              <label>SMTP Username</label>\r\n                              <input class=\"form-control\" type=\"text\" name=\"smtp_username\" value=\"";
echo _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["smtp_username"]);
echo "\" />\r\n                          </div>\r\n                          <div class=\"form-group\">\r\n                              <label>SMTP Password</label>\r\n                              <input class=\"form-control\" type=\"password\" name=\"smtp_password\" value=\"";
echo _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["smtp_password"]);
echo "\" />\r\n                          </div>\r\n                          <div class=\"form-group\">\r\n                            <label>SMTP Security</label>\r\n                            <select class=\"form-control\" name=\"smtp_type\">\r\n                              ";
if ($current["smtp_type"] == "none") {
    echo "<option value=\"none\">None</option><option value=\"ssltls\">SSL/TLS</option><option value=\"starttls\">STARTTLS</option>";
} else {
    if ($current["smtp_type"] == "ssltls") {
        echo "<option value=\"ssltls\">SSL/TLS</option><option value=\"none\">None</option><option value=\"starttls\">STARTTLS</option>";
    } else {
        echo "<option value=\"starttls\">STARTTLS</option><option value=\"none\">None</option><option value=\"ssltls\">SSL/TLS</option>";
    }
}
echo "                            </select>\r\n                          </div>\r\n                          <input type=\"hidden\" name=\"token\" value=\"";
echo $t;
echo "\" />\r\n                          <input type=\"hidden\" name=\"whatform\" value=\"mail_settings\" />\r\n                          <input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n                      </form>\r\n                    </div>\r\n                  </div>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n";

?>
