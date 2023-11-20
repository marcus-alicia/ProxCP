<?php

require_once "core/functions.php";
$step = isset($_GET["step"]) && $_GET["step"] != "" ? $_GET["step"] : "";
switch ($step) {
    case "1":
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["agree"])) {
            header("Location: install?step=2");
            exit;
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST["agree"])) {
            exit("You must agree to the license to continue with installation.");
        }
        break;
    case "2":
        clearstatcache();
        $pre_error = "";
        if (phpversion() < "7.2") {
            $pre_error .= "You need to use PHP 7.2 or above for this application.<br />";
        }
        if (ini_get("session.auto_start")) {
            $pre_error .= "This application will not work with session.auto_start enabled.<br />";
        }
        if (ini_get("display_errors")) {
            $pre_error .= "This application requires display_errors to be Off.<br />";
        }
        if (!extension_loaded("mysqli")) {
            $pre_error .= "MySQLi extension needs to be loaded for this application.<br />";
        }
        if (!defined("PDO::ATTR_DRIVER_NAME")) {
            $pre_error .= "PDO extension needs to be loaded for this application.<br />";
        }
        if (!extension_loaded("gd")) {
            $pre_error .= "GD extension needs to be loaded for this application.<br />";
        }
        if (!extension_loaded("curl")) {
            $pre_error .= "CURL extension needs to be loaded for this application.<br />";
        }
        if (!extension_loaded("ionCube Loader")) {
            $pre_error .= "ionCube Loader extension needs to be loaded for this application.<br />";
        }
        if (!is_writable("core/init.php")) {
            $pre_error .= "core/init.php needs to be writable for installation.";
        }
        if (!is_writable("js/io.js")) {
            $pre_error .= "js/io.js needs to be writable for installation.";
        }
        if (!is_writable("templates_c")) {
            $pre_error .= "templates_c directory needs to be writable for installation.";
        }
        $sas = ini_get("session_auto_start") ? "On" : "Off";
        $do = ini_get("display_errors") ? "On" : "Off";
        $mysqle = extension_loaded("mysqli") ? "On" : "Off";
        $pdoe = defined("PDO::ATTR_DRIVER_NAME") ? "On" : "Off";
        $gde = extension_loaded("gd") ? "On" : "Off";
        $curle = extension_loaded("curl") ? "On" : "Off";
        $ioncubee = extension_loaded("ionCube Loader") ? "On" : "Off";
        $perms = is_writable("core/init.php") ? "Writable" : "Unwritable";
        $perms2 = is_writable("js/io.js") ? "Writable" : "Unwritable";
        $perms3 = is_writable("templates_c") ? "Writable" : "Unwritable";
        $c_phpv = "7.2" <= phpversion() ? "Good" : "Bad";
        $c_sas = !ini_get("session_auto_start") ? "Good" : "Bad";
        $c_do = !ini_get("display_errors") ? "Good" : "Bad";
        $c_mysqle = extension_loaded("mysqli") ? "Good" : "Bad";
        $c_pdoe = defined("PDO::ATTR_DRIVER_NAME") ? "Good" : "Bad";
        $c_gde = extension_loaded("gd") ? "Good" : "Bad";
        $c_curl = extension_loaded("curl") ? "Good" : "Bad";
        $c_ioncube = extension_loaded("ionCube Loader") ? "Good" : "Bad";
        $c_perms = is_writable("core/init.php") ? "Good" : "Bad";
        $c_perms2 = is_writable("js/io.js") ? "Good" : "Bad";
        $c_perms3 = is_writable("templates_c") ? "Good" : "Bad";
        $form = "<form role=\"form\" action=\"install?step=2\" method=\"POST\">\r\n                        <fieldset>\r\n                        \t<h2>ProxCP Installation <small>v " . _obfuscated_0D31240926250B2C5C0C5C0B360A22040F3F1C3E143632_()[0] . "</small></h2>\r\n                        \t<h6 align=\"center\">" . _obfuscated_0D31240926250B2C5C0C5C0B360A22040F3F1C3E143632_()[1] . "</h6>\r\n                        \t<hr class=\"pulse\" />\r\n                        \t<div class=\"row\">\r\n                        \t\t<div class=\"col-md-6\">\r\n                        \t\t\t<p>PHP Version >= 7.2</p>\r\n                        \t\t\t<p>PHP INI session_auto_start</p>\r\n                        \t\t\t<p>PHP INI display_errors</p>\r\n                        \t\t\t<p>PHP MySQLi Extension</p>\r\n                        \t\t\t<p>PHP PDO Extension</p>\r\n                        \t\t\t<p>PHP GD Extension</p>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<p>PHP CURL Extension</p>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<p>PHP ionCube Loader Extension</p>\r\n                        \t\t\t<p>core/init.php Writable</p>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<p>js/io.js Writable</p>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<p>templates_c Writable</p>\r\n                        \t\t</div>\r\n                        \t\t<div class=\"col-md-3\">\r\n                        \t\t\t<p>" . phpversion() . "</p>\r\n                        \t\t\t<p>" . $sas . "</p>\r\n                        \t\t\t<p>" . $do . "</p>\r\n                        \t\t\t<p>" . $mysqle . "</p>\r\n                        \t\t\t<p>" . $pdoe . "</p>\r\n                        \t\t\t<p>" . $gde . "</p>\r\n                        \t\t\t<p>" . $curle . "</p>\r\n                        \t\t\t<p>" . $ioncubee . "</p>\r\n                        \t\t\t<p>" . $perms . "</p>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<p>" . $perms2 . "</p>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<p>" . $perms3 . "</p>\r\n                        \t\t</div>\r\n                        \t\t<div class=\"col-md-3\">\r\n                        \t\t\t<p>" . $c_phpv . "</p>\r\n                        \t\t\t<p>" . $c_sas . "</p>\r\n                        \t\t\t<p>" . $c_do . "</p>\r\n                        \t\t\t<p>" . $c_mysqle . "</p>\r\n                        \t\t\t<p>" . $c_pdoe . "</p>\r\n                        \t\t\t<p>" . $c_gde . "</p>\r\n                        \t\t\t<p>" . $c_curl . "</p>\r\n                        \t\t\t<p>" . $c_ioncube . "</p>\r\n                        \t\t\t<p>" . $c_perms . "</p>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<p>" . $c_perms2 . "</p>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t<p>" . $c_perms3 . "</p>\r\n                        \t\t</div>\r\n                        \t</div>\r\n                        \t<hr class=\"pulse\" />\r\n                        \t<div class=\"row\">\r\n                        \t\t<div class=\"col-xs-8 col-sm-8 col-md-8\">\r\n                        \t\t\t<input type=\"submit\" class=\"btn btn-lg btn-success btn-block\" value=\"Continue\" />\r\n                        \t\t</div>\r\n                        \t\t<div class=\"col-xs-4 col-sm-4 col-md-4\">\r\n                        \t\t\t<a href=\"install?step=2\" class=\"btn btn-lg btn-success btn-block\">Refresh</a>\r\n                        \t\t</div>\r\n                        \t</div>\r\n                        </fieldset>\r\n                        <input type=\"hidden\" name=\"pre_error\" id=\"pre_error\" value=\"" . $pre_error . "\" />\r\n                    </form>";
        if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["pre_error"] != "") {
            exit($_POST["pre_error"]);
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["pre_error"] == "") {
            header("Location: install?step=3");
            exit;
        }
        break;
    case "3":
        $form = "<form role=\"form\" action=\"install?step=3\" method=\"POST\">\r\n                        <fieldset>\r\n                        \t<h2>ProxCP Installation <small>v " . _obfuscated_0D31240926250B2C5C0C5C0B360A22040F3F1C3E143632_()[0] . "</small></h2>\r\n                        \t<h6 align=\"center\">" . _obfuscated_0D31240926250B2C5C0C5C0B360A22040F3F1C3E143632_()[1] . "</h6>\r\n                        \t<hr class=\"pulse\" />\r\n                        \t<div class=\"form-group\">\r\n                        \t\t<input type=\"text\" name=\"database_host\" id=\"database_host\" class=\"form-control input-lg\" placeholder=\"Database Host\" />\r\n                        \t</div>\r\n                        \t<div class=\"form-group\">\r\n                        \t\t<input type=\"text\" name=\"database_name\" id=\"database_name\" class=\"form-control input-lg\" placeholder=\"Database Name\" />\r\n                        \t</div>\r\n                        \t<div class=\"form-group\">\r\n                        \t\t<input type=\"text\" name=\"database_username\" id=\"database_username\" class=\"form-control input-lg\" placeholder=\"Database Username\" />\r\n                        \t</div>\r\n                        \t<div class=\"form-group\">\r\n                        \t\t<input type=\"password\" name=\"database_password\" id=\"database_password\" class=\"form-control input-lg\" placeholder=\"Database Password\" />\r\n                        \t</div>\r\n                        \t<div class=\"form-group\">\r\n                        \t\t<input type=\"email\" name=\"admin_email\" id=\"admin_email\" class=\"form-control input-lg\" placeholder=\"Admin Email\" />\r\n                        \t</div>\r\n                        \t<div class=\"form-group\">\r\n                        \t\t<input type=\"password\" name=\"admin_password\" id=\"admin_password\" class=\"form-control input-lg\" placeholder=\"Admin Password\" />\r\n                        \t</div>\r\n                        \t<div class=\"form-group\">\r\n                        \t\t<input type=\"text\" name=\"vncp_license\" id=\"vncp_license\" class=\"form-control input-lg\" placeholder=\"License Key\" />\r\n                        \t</div>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<div class=\"form-group\">\r\n                        \t\t<input type=\"text\" name=\"socket_domain\" id=\"socket_domain\" class=\"form-control input-lg\" placeholder=\"ProxCP Daemon URL (https://app.domain.com:8000)\" />\r\n                        \t</div>\r\n                        \t<hr class=\"pulse\" />\r\n                        \t<div class=\"row\">\r\n                        \t\t<div class=\"col-xs-12 col-sm-12 col-md-12\">\r\n                        \t\t\t<input type=\"submit\" name=\"submit\" class=\"btn btn-lg btn-success btn-block\" value=\"Install\" />\r\n                        \t\t</div>\r\n                        \t</div>\r\n                        </fieldset>\r\n                    </form>";
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"]) && $_POST["submit"] == "Install") {
            $database_host = isset($_POST["database_host"]) ? $_POST["database_host"] : "";
            $database_name = isset($_POST["database_name"]) ? $_POST["database_name"] : "";
            $database_username = isset($_POST["database_username"]) ? $_POST["database_username"] : "";
            $database_password = isset($_POST["database_password"]) ? $_POST["database_password"] : "";
            $admin_email = isset($_POST["admin_email"]) ? $_POST["admin_email"] : "";
            $admin_email = strtolower($admin_email);
            $admin_password = isset($_POST["admin_password"]) ? $_POST["admin_password"] : "";
            $vncp_license = isset($_POST["vncp_license"]) ? $_POST["vncp_license"] : "";
            $socket_domain = isset($_POST["socket_domain"]) ? $_POST["socket_domain"] : "";
            if (empty($admin_email) || empty($admin_password) || empty($database_host) || empty($database_username) || empty($database_name) || empty($vncp_license) || empty($socket_domain)) {
                exit("All fields are required. Please re-enter.");
            }
            $f = fopen("core/init.php", "w");
            $f2 = fopen("js/io.js", "w");
            $overall = "\$GLOBALS['config']";
            $vars = ["\$hash", "\$hashCheck", "\$user"];
            $io_inf = "var socket = io.connect('" . $socket_domain . "', { secure:true });var user = \$('#user').val();socket.on('connect', function() {console.log('Connected to socket');\$('#socket_error').css('visibility', 'hidden');\$('#socket_error').css('padding', '0px');\$('#socket_error').html('');socket.emit('addUserConnection', user);});socket.on('reconnecting', function() {console.log('Lost connection to socket! Attempting to reconnect...');\$('#socket_error').css('visibility', 'visible');\$('#socket_error').css('padding', '10px');\$('#socket_error').html('Cannot connect to socket! All VM functions will fail :(');});";
            $database_inf = "<?php\r\n//  ProxCP - End-user Proxmox control panel for web hosting providers.\r\n//  Copyright (c) 2018 ProxCP. All Rights Reserved.\r\n//  Version 1.0\r\n//\r\n//  This software is furnished under a license and may be used and copied\r\n//  only in accordance with the terms of such license and with the\r\n//  inclusion of the above copyright notice. This software or any other\r\n//  copies thereof may not be provided or otherwise made available to any\r\n//  other person. No title to and ownership of the software is hereby\r\n//  transferred.\r\n//\r\n//  You may not reverse engineer, decompile, defeat license encryption\r\n//  mechanisms, or disassemble this software product or software product\r\n//  license. ProxCP may terminate this license if you don't comply with any\r\n//  of the terms and conditions set forth in our end user license agreement\r\n//  (EULA). In such event, licensee agrees to destroy all copies of software\r\n//  upon termination of the license.\r\n\r\n//////////////////////////////////////////////\r\n//     BEGIN USER CONFIGURATION SECTION     //\r\n//////////////////////////////////////////////\r\n\r\n" . $overall . " = array(\r\n\t// DATABASE CONFIGURATION\r\n\t'database' => array(\r\n\t\t'type' => 'mysql',\r\n\t\t'host' => '" . $database_host . "',\r\n\t\t'username' => '" . $database_username . "',\r\n\t\t'password' => '" . $database_password . "',\r\n\t\t'db' => '" . $database_name . "'\r\n\t),\r\n\t'instance' => array(\r\n\t\t'base' => '" . _obfuscated_0D255C14354036371F11322B5C1925345B312F373C1022_() . "', // BASE DOMAIN OF THIS PROXCP INSTALLATION\r\n\t\t'installed' => true, // HAS PROXCP BEEN INSTALLED?\r\n\t\t'l_salt' => '" . _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(24) . "', // DO NOT CHANGE OR SHARE THESE VALUES - SALT 1\r\n\t\t'v_salt' => '" . _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(24) . "', // DO NOT CHANGE OR SHARE THESE VALUES - SALT 2\r\n\t\t'vncp_secret_key' => '" . bin2hex(_obfuscated_0D3B292E23311A40030E233B250F2A3924391916151E11_(32)) . "." . bin2hex(_obfuscated_0D3B292E23311A40030E233B250F2A3924391916151E11_(16)) . "' // DO NOT CHANGE OR SHARE THESE VALUES - SECRET KEY\r\n\t),\r\n\t'admin' => array(\r\n\t\t'base' => 'admin' // BASE ADMIN FILE NAME WITHOUT FILE EXTENSION\r\n\t),\r\n\t// REMEMBER ME LOGIN SETTINGS\r\n\t'remember' => array(\r\n\t\t'cookie_name' => 'hash',\r\n\t\t'cookie_expiry' => 604800\r\n\t),\r\n\t// LOGIN SESSION SETTINGS\r\n\t'session' => array(\r\n\t\t'session_name' => 'user',\r\n\t\t'token_name' => 'token'\r\n\t)\r\n);\r\n\r\n//////////////////////////////////////////////\r\n//      END USER CONFIGURATION SECTION      //\r\n//////////////////////////////////////////////\r\n\r\n//////////////////////////////////////////////\r\n//       DO NOT EDIT BELOW THIS LINE        //\r\n//////////////////////////////////////////////";
            if (0 < fwrite($f, $database_inf)) {
                fclose($f);
            }
            if (0 < fwrite($f2, $io_inf)) {
                fclose($f2);
            }
            $connection = mysqli_connect($database_host, $database_username, $database_password);
            mysqli_select_db($connection, $database_name);
            require_once "vendor/autoload.php";
            require_once "core/autoload.php";
            require_once "core/init.php";
            require_once "core/session.php";
            $l_array = _obfuscated_0D142413292F301A3D10290834252135313C26322E2232_($GLOBALS["config"]["instance"]["base"], "", $vncp_license, $connection);
            if ($l_array["notification_case"] != "notification_license_ok") {
                exit("License check failed, installation failed: " . $l_array["notification_text"]);
            }
            mysqli_close($connection);
            $connection = mysqli_connect($database_host, $database_username, $database_password);
            mysqli_select_db($connection, $database_name);
            $file = "sql/install.sql";
            if ($sql = file($file)) {
                $query = "";
                for ($i = 0; $i < count($sql); $i++) {
                    $tsl = trim($sql[$i]);
                    if ($sql != "" && $tsl != "") {
                        $query .= $tsl;
                        mysqli_query($connection, $query);
                        $err = mysqli_error($connection);
                        if (!empty($err)) {
                            exit("Installation failed. MySQL Error: " . $err);
                        }
                        $query = "";
                    }
                }
                $salt = salt();
                $salt = mysqli_real_escape_string($connection, $salt);
                $admin_password = get_hash($admin_password, $salt);
                mysqli_query($connection, "INSERT INTO vncp_users SET email='" . $admin_email . "', username='" . $admin_email . "', password='" . $admin_password . "', salt='" . $salt . "', tfa_enabled=0, tfa_secret='', `group`=2, locked=0, language='en'");
                mysqli_query($connection, "INSERT INTO vncp_notes SET id=1, notes='Welcome!'");
                mysqli_close($connection);
            }
            sleep(3);
            header("Location: install?step=4");
        }
        break;
    case "4":
        require_once "vendor/autoload.php";
        require_once "core/autoload.php";
        require_once "core/init.php";
        require_once "core/session.php";
        $form = "<form role=\"form\" action=\"\" method=\"POST\">\r\n                        <fieldset>\r\n                        \t<h2>ProxCP Installation <small>v " . _obfuscated_0D31240926250B2C5C0C5C0B360A22040F3F1C3E143632_()[0] . "</small></h2>\r\n                        \t<h6 align=\"center\">" . _obfuscated_0D31240926250B2C5C0C5C0B360A22040F3F1C3E143632_()[1] . "</h6>\r\n                        \t<hr class=\"pulse\" />\r\n                        \t<p>Congratulations! ProxCP has been installed successfully. You can login to the admin account you created and continue setup.</p>\r\n                        \t<br />\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<p>ProxCP Socket Key: " . $GLOBALS["config"]["instance"]["vncp_secret_key"] . "<br />Copy this key as you will need it to configure the ProxCP socket.</p>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<br />\r\n                        \t<h4>COMPLETE THESE SECURITY STEPS:</h4>\r\n                        \t<p>Delete install.php file</p>\r\n\t\t\t\t\t\t\t\t\t\t\t\t\t<p>Delete sql/ directory</p>\r\n                        \t<p>Change core/init.php permissions <pre>chmod 0444 core/init.php</pre></p>\r\n                        \t<p>Change js/io.js permissions <pre>chmod 0644 js/io.js</pre></p>\r\n                        \t<p>Optional: change admin.php file name</p>\r\n                        \t<hr class=\"pulse\" />\r\n                        \t<div class=\"row\">\r\n                        \t\t<div class=\"col-xs-12 col-sm-12 col-md-12\">\r\n                        \t\t\t<a href=\"" . _obfuscated_0D255C14354036371F11322B5C1925345B312F373C1022_() . "\" class=\"btn btn-lg btn-success btn-block\">Go to login</a>\r\n                        \t\t</div>\r\n                        \t</div>\r\n                        </fieldset>\r\n                    </form>";
        break;
    default:
        $license = file_get_contents("LICENSE.txt");
        if ($license === false) {
            exit("Cannot read from LICENSE.txt.");
        }
        $form = "<form role=\"form\" action=\"install?step=1\" method=\"POST\">\r\n                        <fieldset>\r\n                        \t<h2>ProxCP Installation <small>v " . _obfuscated_0D31240926250B2C5C0C5C0B360A22040F3F1C3E143632_()[0] . "</small></h2>\r\n                        \t<h6 align=\"center\">" . _obfuscated_0D31240926250B2C5C0C5C0B360A22040F3F1C3E143632_()[1] . "</h6>\r\n                        \t<hr class=\"pulse\" />\r\n                        \t<div class=\"row\">\r\n                        \t\t<div class=\"col-md-12\">\r\n                        \t\t\t<p>Welcome to ProxCP! Run this script to complete installation of the application.</p>\r\n                        \t\t\t<p>License Agreement</p>\r\n                        \t\t\t<textarea style=\"resize:none;\" class=\"form-control\" rows=\"18\" disabled>" . $license . "</textarea>\r\n                        \t\t</div>\r\n                        \t</div>\r\n                        \t<br />\r\n                        \t<span class=\"button-checkbox\">\r\n                                <button type=\"button\" class=\"btn\" data-color=\"info\">Agree</button>\r\n                                <input type=\"checkbox\" name=\"agree\" id=\"agree\" class=\"hidden\">\r\n                            </span>\r\n                        \t<hr class=\"pulse\" />\r\n                        \t<div class=\"row\">\r\n                        \t\t<div class=\"col-xs-12 col-sm-12 col-md-12\">\r\n                        \t\t\t<input type=\"submit\" class=\"btn btn-lg btn-success btn-block\" value=\"Begin\" />\r\n                        \t\t</div>\r\n                        \t</div>\r\n                        </fieldset>\r\n                    </form>";
        echo "<!doctype html>\r\n<!--[if lt IE 7]><html class=\"no-js lt-ie9 lt-ie8 lt-ie7\" lang=\"\"><![endif]-->\r\n<!--[if IE 7]><html class=\"no-js lt-ie9 lt-ie8\" lang=\"\"><![endif]-->\r\n<!--[if IE 8]><html class=\"no-js lt-ie9\" lang=\"\"><![endif]-->\r\n<!--[if gt IE 8]><!--> <html class=\"no-js\" lang=\"\"> <!--<![endif]-->\r\n<head>\r\n    <meta charset=\"utf-8\" />\r\n    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\" />\r\n    <title>ProxCP - Installation</title>\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\" />\r\n    <link rel=\"stylesheet\" href=\"css/bootstrap.min.css\" />\r\n    <link rel=\"stylesheet\" href=\"css/font-awesome.min.css\" />\r\n    <link rel=\"stylesheet\" href=\"css/main.css\" />\r\n    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css' />\r\n    <link rel=\"icon\" type=\"image/png\" href=\"favicon.ico\" />\r\n    <script src=\"js/vendor/modernizr-2.8.3-respond-1.4.2.min.js\"></script>\r\n</head>\r\n<body>\r\n\t";
        if (!check_https()) {
            echo "<div id=\"socket_error\" class=\"socket_error\" style=\"visibility:visible;padding:10px;\">Insecure connection (non-HTTPS)!</div>";
        }
        echo "    <!--[if lt IE 8]>\r\n        <p class=\"browserupgrade\">You are using an <strong>outdated</strong> browser. Please <a href=\"http://browsehappy.com/\">upgrade your browser</a> to improve your experience.</p>\r\n    <![endif]-->\r\n    <nav class=\"navbar navbar-default\" id=\"navigation\">\r\n        <div class=\"container\">\r\n            <div class=\"navbar-header\">\r\n                <button type=\"button\" class=\"navbar-toggle collapsed\" data-toggle=\"collapse\" data-target=\"#bs-example-navbar-collapse-1\">\r\n                <span class=\"sr-only\">Toggle navigation</span>\r\n                <span class=\"icon-bar\"></span>\r\n                <span class=\"icon-bar\"></span>\r\n                <span class=\"icon-bar\"></span>\r\n                </button>\r\n                <a class=\"navbar-brand\"><img src=\"img/logo.png\" class=\"img-responsive\" /></a>\r\n            </div>\r\n                <div class=\"collapse navbar-collapse\" id=\"bs-example-navbar-collapse-1\">\r\n                    <ul class=\"nav navbar-nav navbar-right nav-elem\" id=\"bottom-nav\">\r\n                        <li><a href=\"https://my.proxcp.com\"><i class=\"fa fa-life-ring\"></i> Support</a></li>\r\n                    </ul>\r\n                </div>\r\n        </div>\r\n    </nav>\r\n    <div class=\"container-full\" id=\"blocks\">\r\n        <div class=\"container\">\r\n            <div class=\"row\">\r\n                <div class=\"col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3 login-box\">\r\n                \t\t";
        echo $form;
        echo "                </div>\r\n            </div>\r\n        </div>\r\n    </div>\r\n    <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js\"></script>\r\n    <script>window.jQuery || document.write('<script src=\"js/vendor/jquery-1.11.2.min.js\"><\\/script>')</script>\r\n    <script src=\"js/vendor/bootstrap.min.js\"></script>\r\n    <script src=\"js/buttons.js\"></script>\r\n</body>\r\n</html>\r\n";
}
function get_hash($string, $salt = "")
{
    return hash("sha256", $string . $salt);
}
function get_unique_id()
{
    return uniqid(mt_rand(), true);
}

?>
