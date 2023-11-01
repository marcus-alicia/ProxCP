<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
$edit_node = $db->get("vncp_nodes", ["id", "=", parse_input($_GET["id"])])->first();
echo "<div class=\"row\">\r\n\t<div class=\"col-md-12\">\r\n\t\t<div class=\"panel panel-default\">\r\n\t\t\t<div class=\"panel-body\">\r\n        ";
if (count($edit_node) == 1) {
    echo "\t\t\t\t<h2 align=\"center\">Edit Node - ";
    echo parse_input($edit_node->name);
    echo "</h2><br />\r\n\t\t\t\t<div id=\"adm_message\"></div>\r\n\t\t\t\t<form role=\"form\" action=\"\" method=\"POST\">\r\n\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t    <label>API Username</label>\r\n\t\t\t\t\t    <input class=\"form-control\" type=\"text\" name=\"username\" value=\"";
    echo parse_input($edit_node->username);
    echo "\" />\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t    <label>API Password</label>\r\n\t\t\t\t\t    <input class=\"form-control\" type=\"password\" name=\"password\" placeholder=\"Type here to change the password, leave this blank otherwise.\" />\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t    <label>Realm</label>\r\n\t\t\t\t\t    <input class=\"form-control\" type=\"text\" name=\"realm\" value=\"";
    echo parse_input($edit_node->realm);
    echo "\" />\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t    <label>Port</label>\r\n\t\t\t\t\t    <input class=\"form-control\" type=\"text\" name=\"port\" value=\"";
    echo parse_input($edit_node->port);
    echo "\" />\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t    <label>Location</label>\r\n\t\t\t\t\t    <input class=\"form-control\" type=\"text\" name=\"location\" value=\"";
    echo parse_input($edit_node->location);
    echo "\" />\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t    <label>CPU</label>\r\n\t\t\t\t\t    <input class=\"form-control\" type=\"text\" name=\"cpu\" value=\"";
    echo parse_input($edit_node->cpu);
    echo "\" />\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<div class=\"form-group\">\r\n\t\t\t\t\t    <label>Backup Store</label>\r\n\t\t\t\t\t    <input class=\"form-control\" type=\"text\" name=\"backup\" value=\"";
    echo parse_input($edit_node->backup_store);
    echo "\" />\r\n\t\t\t\t\t</div>\r\n\t\t\t\t\t<input type=\"hidden\" name=\"nid\" value=\"";
    echo parse_input($_GET["id"]);
    echo "\" />\r\n\t\t\t\t\t<input type=\"hidden\" name=\"token\" value=\"";
    echo Token::generate();
    echo "\" />\r\n\t\t\t\t\t<input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n\t\t\t\t</form>\r\n      ";
} else {
    echo "Node not found. <a href=\"" . Config::get("instance/base") . "/admin?action=nodes\">Go back to Manage Nodes</a>";
}
echo "\t\t\t</div>\r\n\t\t</div>\r\n\t</div>\r\n</div>\r\n";

?>
