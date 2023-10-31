<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
echo "<div class=\"row\">\r\n    <div class=\"col-md-12\">\r\n        <div class=\"panel panel-default\">\r\n            <div class=\"panel-body\">\r\n              <div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Admin_-_System_Logs\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div>\r\n                <h2 align=\"center\">System Logs</h2><br />\r\n                <div>\r\n                    <ul class=\"nav nav-tabs\" role=\"tablist\">\r\n                        <li role=\"presentation\" class=\"active\"><a href=\"#general\" aria-controls=\"general\" role=\"tab\" data-toggle=\"tab\">General</a></li>\r\n                        <li role=\"presentation\"><a href=\"#admin\" aria-controls=\"admin\" role=\"tab\" data-toggle=\"tab\">Admin</a></li>\r\n                        <li role=\"presentation\"><a href=\"#error\" aria-controls=\"error\" role=\"tab\" data-toggle=\"tab\">Error</a></li>\r\n                    </ul>\r\n                    <div class=\"tab-content\">\r\n                        <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"general\">\r\n                            <br />\r\n                            <div class=\"table-responsive\">\r\n                                <table class=\"table table-hover\" id=\"admin_general_log\">\r\n                                    <thead>\r\n                                        <tr>\r\n                                            <th></th>\r\n                                            <th>Severity</th>\r\n                                            <th>Date</th>\r\n                                            <th>Username</th>\r\n                                            <th>IP Address</th>\r\n                                        </tr>\r\n                                    </thead>\r\n                                    <tbody>\r\n                                        ";
$general_log = $log->get("general");
for ($i = 0; $i < count($general_log); $i++) {
    echo "<tr>";
    echo "<td>" . $general_log[$i]->msg . "</td>";
    echo "<td>" . _obfuscated_0D0935343F2F2402302C161711153006273F1021131332_($general_log[$i]->severity) . "</td>";
    echo "<td>" . $general_log[$i]->date . "</td>";
    echo "<td>" . $general_log[$i]->username . "</td>";
    echo "<td>" . $general_log[$i]->ipaddress . "</td>";
    echo "</tr>";
}
echo "                                    </tbody>\r\n                                </table>\r\n                            </div>\r\n                        </div>\r\n                        <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"admin\">\r\n                            <br />\r\n                            <div class=\"table-responsive\">\r\n                                <table class=\"table table-hover\" id=\"admin_admin_log\">\r\n                                    <thead>\r\n                                        <tr>\r\n                                            <th></th>\r\n                                            <th>Severity</th>\r\n                                            <th>Date</th>\r\n                                            <th>Username</th>\r\n                                            <th>IP Address</th>\r\n                                        </tr>\r\n                                    </thead>\r\n                                    <tbody>\r\n                                        ";
$admin_log = $log->get("admin");
for ($i = 0; $i < count($admin_log); $i++) {
    echo "<tr>";
    echo "<td>" . $admin_log[$i]->msg . "</td>";
    echo "<td>" . severity($admin_log[$i]->severity) . "</td>";
    echo "<td>" . $admin_log[$i]->date . "</td>";
    echo "<td>" . $admin_log[$i]->username . "</td>";
    echo "<td>" . $admin_log[$i]->ipaddress . "</td>";
    echo "</tr>";
}
echo "                                    </tbody>\r\n                                </table>\r\n                            </div>\r\n                        </div>\r\n                        <div role=\"tabpanel\" class=\"tab-pane fade in\" id=\"error\">\r\n                            <br />\r\n                            <div class=\"table-responsive\">\r\n                                <table class=\"table table-hover\" id=\"admin_error_log\">\r\n                                    <thead>\r\n                                        <tr>\r\n                                            <th></th>\r\n                                            <th>Severity</th>\r\n                                            <th>Date</th>\r\n                                            <th>Username</th>\r\n                                            <th>IP Address</th>\r\n                                        </tr>\r\n                                    </thead>\r\n                                    <tbody>\r\n                                        ";
$error_log = $log->get("error");
for ($i = 0; $i < count($error_log); $i++) {
    echo "<tr>";
    echo "<td>" . $error_log[$i]->msg . "</td>";
    echo "<td>" . severity($error_log[$i]->severity) . "</td>";
    echo "<td>" . $error_log[$i]->date . "</td>";
    echo "<td>" . $error_log[$i]->username . "</td>";
    echo "<td>" . $error_log[$i]->ipaddress . "</td>";
    echo "</tr>";
}
echo "                                    </tbody>\r\n                                </table>\r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n                </div>\r\n                <h2 align=\"center\">Purge Old Logs</h2>\r\n                ";
$today = getdate();
echo "                <form role=\"form\" action=\"\" method=\"POST\">\r\n                    <div class=\"form-group\">\r\n                        <label>Log Type</label>\r\n                        <select class=\"form-control\" name=\"logtype\">\r\n                            <option value=\"default\">Select...</option>\r\n                            <option value=\"general\">general</option>\r\n                            <option value=\"admin\">admin</option>\r\n                            <option value=\"error\">error</option>\r\n                        </select>\r\n                    </div>\r\n                    ";
if ((int) $today["mday"] == 1) {
    $longbois = [1, 3, 5, 7, 8, 10, 12];
    $normalbois = [4, 6, 9, 11];
    $month = (int) $today["mon"];
    if (in_array($month, $longbois)) {
        $remove_day = 31;
    } else {
        if (in_array($month, $normalbois)) {
            $remove_day = 30;
        } else {
            $remove_day = 28;
        }
    }
} else {
    $remove_day = (int) $today["mday"] - 1;
}
echo "                    <div class=\"form-group\">\r\n                        <label>Remove Log Entries Before Date</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"purgedate\" value=\"";
echo $today["year"];
echo "-";
echo $today["mon"];
echo "-";
echo $remove_day;
echo "\" />\r\n                    </div>\r\n                    <input type=\"hidden\" name=\"token\" value=\"";
echo Token::generate();
echo "\" />\r\n                    <input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n                </form>\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n";

?>
