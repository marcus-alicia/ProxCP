<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
error_reporting(0);
session_start();
require_once "classes/Config.php";
require_once "classes/Cookie.php";
require_once "classes/Cpanel.php";
require_once "classes/DB.php";
require_once "classes/Hash.php";
require_once "classes/Input.php";
require_once "classes/Logger.php";
require_once "classes/Redirect.php";
require_once "classes/Session.php";
require_once "classes/Token.php";
require_once "classes/User.php";
require_once "classes/Validate.php";
require_once "classes/MacAddress.php";
require_once "vendor/pve2-api-php-client-master/pve2_api.class.php";
require_once "classes/MagicCrypt.php";
require_once "classes/GoogleAuthenticator.php";
require_once "classes/Language.php";
require_once "core/functions.php";
$GLOBALS["node_limit"] = "1";
$GLOBALS["proxcp_branding"] = "<br /><br /><center>Powered by <a href=\"https://proxcp.com\" target=\"_blank\">ProxCP</a>.</center><br /><br />";
$twigLoader = new Twig_Loader_Filesystem(dirname(__DIR__) . "/templates");
$twig = new Twig_Environment($twigLoader, ["cache" => dirname(__DIR__) . "/templates_c"]);

?>
