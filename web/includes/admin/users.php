<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
echo "<div class=\"row\">\r\n    <div class=\"col-md-12\">\r\n        <div class=\"panel panel-default\">\r\n            <div class=\"panel-body\">\r\n                <div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Admin_-_Manage_Users\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div>\r\n                <h2 align=\"center\">Manage Users</h2><br />\r\n                ";
if ($userCreatedSuccess) {
    echo "<div id=\"adm_message\"><div class=\"alert alert-success\" role=\"alert\"><strong>New User Password:</strong> <input type=\"text\" class=\"form-control\" value=\"" . $plaintext_user_password . "\" /></div></div>";
} else {
    echo "<div id=\"adm_message\"></div>";
}
echo "                <div class=\"table-responsive\">\r\n                    <table class=\"table table-hover\" id=\"admin_usertable\">\r\n                        <thead>\r\n                            <tr>\r\n                                <th>ID</th>\r\n                                <th>Email / Username</th>\r\n                                <th>Group</th>\r\n                                <th>Change Password</th>\r\n                                <th>Access</th>\r\n                                <th>Account Lock</th>\r\n                                <th>Delete</th>\r\n                            </tr>\r\n                        </thead>\r\n                        <tbody>\r\n                            ";
$admin_datausers = $db->get("vncp_users", ["id", "!=", 0]);
$admin_users = $admin_datausers->all();
for ($k = 0; $k < count($admin_users); $k++) {
    echo "<tr>";
    echo "<td>" . $admin_users[$k]->id . "</td>";
    echo "<td>" . $admin_users[$k]->email . "</td>";
    if ($admin_users[$k]->group == 2) {
        echo "<td>Administrator</td>";
    } else {
        echo "<td>Standard user</td>";
    }
    echo "<td><button class=\"btn btn-default btn-sm\" id=\"acctpw" . $admin_users[$k]->id . "\" role=\"" . $admin_users[$k]->id . "\">Change</button></td>";
    if ($admin_users[$k]->group == 1) {
        echo "<td><a class=\"btn btn-default btn-sm\" href=\"admin?action=admaccess&id=" . $admin_users[$k]->id . "\">Access</a></td>";
    } else {
        echo "<td>N/A</td>";
    }
    if ($admin_users[$k]->locked == 0) {
        echo "<td><button class=\"btn btn-danger btn-sm\" id=\"acctlock" . $admin_users[$k]->id . "\" role=\"" . $admin_users[$k]->id . "\">Lock</button></td>";
    } else {
        echo "<td><button class=\"btn btn-success btn-sm\" id=\"acctunlock" . $admin_users[$k]->id . "\" role=\"" . $admin_users[$k]->id . "\">Unlock</button></td>";
    }
    echo "<td><button id=\"admin_userdelete" . $admin_users[$k]->id . "\" class=\"btn btn-sm btn-danger\" role=\"" . $admin_users[$k]->id . "\">Delete</button></td>";
    echo "</tr>";
}
echo "                        </tbody>\r\n                    </table>\r\n                </div>\r\n                <ul class=\"nav nav-tabs\" role=\"tablist\">\r\n                  <li role=\"presentation\" class=\"active\"><a href=\"#newuser\" aria-controls=\"newuser\" role=\"tab\" data-toggle=\"tab\">Add New User</a></li>\r\n                  <li role=\"presentation\"><a href=\"#changeusername\" aria-controls=\"changeusername\" role=\"tab\" data-toggle=\"tab\">Change Username</a></li>\r\n                </ul>\r\n                <div class=\"tab-content\">\r\n                  <div role=\"tabpanel\" class=\"tab-pane fade in active\" id=\"newuser\"><br />\r\n                    <form role=\"form\" action=\"\" method=\"POST\">\r\n                        <div class=\"form-group\">\r\n                            <label>Email / Username</label>\r\n                            <input class=\"form-control\" type=\"email\" name=\"email\" placeholder=\"user@domain.com\" />\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>Group</label>\r\n                            <select class=\"form-control\" name=\"group\">\r\n                              <option value=\"default\">Select...</option>\r\n                              <option value=\"1\">User</option>\r\n                              <option value=\"2\">Admin</option>\r\n                            </select>\r\n                        </div>\r\n                        <input type=\"hidden\" name=\"form_name\" value=\"new_user_form\" />\r\n                        <input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n                    </form>\r\n                  </div>\r\n                  <div role=\"tabpanel\" class=\"tab-pane fade\" id=\"changeusername\"><br />\r\n                    <form role=\"form\" action=\"\" method=\"POST\">\r\n                        <div class=\"form-group\">\r\n                            <label>Select User</label>\r\n                            <select class=\"form-control\" name=\"which_user\">\r\n                              <option value=\"default\">Select...</option>\r\n                              ";
$usersDB = $db->get_users_asc("vncp_users", ["id", "!=", 0]);
$usersDB = $usersDB->all();
for ($i = 0; $i < count($usersDB); $i++) {
    echo "<option value=\"" . $usersDB[$i]->username . "\">" . $usersDB[$i]->username . "</option>";
}
echo "                            </select>\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>New Username</label>\r\n                            <input class=\"form-control\" type=\"text\" name=\"username\" />\r\n                        </div>\r\n                        <input type=\"hidden\" name=\"form_name\" value=\"change_username_form\" />\r\n                        <input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n                    </form>\r\n                  </div>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n";

?>
