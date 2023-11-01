<?php

require_once "vendor/autoload.php";
require_once "core/autoload.php";
require_once "core/init.php";
require_once "core/session.php";
if (!Config::get("instance/installed")) {
    Redirect::to("install");
} else {
    $connection = mysqli_connect(Config::get("database/host"), Config::get("database/username"), Config::get("database/password"));
    mysqli_select_db($connection, Config::get("database/db"));
    $l_array = get_license_info($connection, 0);
    if ($l_array["notification_case"] != "notification_license_ok") {
        mysqli_close($connection);
        exit("License check failed: " . $l_array["notification_text"]);
    }
    $user = new User();
    if (!$user->isLoggedIn()) {
        Redirect::to("login");
    }
}
$db = DB::getInstance();
$log = new Logger();
if (Input::exists() && Input::get("form_name") == "update_profile") {
    if (Token::check(Input::get("token"))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, ["new_password" => ["required" => true, "min" => 5], "new_password_confirm" => ["required" => true, "min" => 5, "matches" => "new_password"]]);
        if ($validation->passed()) {
            $salt = Hash::salt(32);
            $user->update(["password" => Hash::make(Input::get("new_password"), $salt), "salt" => $salt]);
            $log->log("Updated user password.", "general", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
            Redirect::to("logout");
        } else {
            $errors = "";
            foreach ($validation->errors() as $error) {
                $errors .= $error . "<br />";
            }
        }
    }
} else {
    if (Input::exists() && Input::get("form_name") == "submit_tfa") {
        $validate = new Validate();
        $validation = $validate->check($_POST, ["tfa_account" => ["required" => true, "strbool" => true, "min" => 4, "max" => 5]]);
        if ($validation->passed()) {
            if (Input::get("tfa_account") == "true") {
                $ga = new vncp_GoogleAuthenticator();
                $secret = $ga->createSecret(16);
                $db->update("vncp_users", $user->data()->id, ["tfa_enabled" => 1, "tfa_secret" => $secret]);
                $user->data()->tfa_enabled = 1;
                $log->log("Enabled 2FA (gauth).", "general", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                $authName = explode("//", Config::get("instance/base"))[1] . " (" . $user->data()->username . ")";
                $qrCodeUrl = $ga->getQRCodeGoogleUrl($authName, $secret);
            } else {
                if (Input::get("tfa_account") == "false") {
                    $db->update("vncp_users", $user->data()->id, ["tfa_enabled" => 0, "tfa_secret" => ""]);
                    $user->data()->tfa_enabled = 0;
                    $log->log("Disabled 2FA (gauth).", "general", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                } else {
                    $errors = "Invalid option.";
                }
            }
        } else {
            $errors = "";
            foreach ($validation->errors() as $error) {
                $errors .= $error . "<br />";
            }
        }
    } else {
        if (Input::exists() && Input::get("form_name") == "submit_lang") {
            $validate = new Validate();
            $validation = $validate->check($_POST, ["user_lang" => ["required" => true, "min" => 2, "max" => 2]]);
            if ($validation->passed()) {
                $validLang = false;
                foreach (glob("lang/*.json") as $file) {
                    list($file) = explode("/", $file);
                    list($file) = explode(".", $file);
                    if ($file == Input::get("user_lang")) {
                        $validLang = true;
                        if ($validLang) {
                            $db->update("vncp_users", $user->data()->id, ["language" => Input::get("user_lang")]);
                            $user->data()->language = Input::get("user_lang");
                            $log->log("Changed language to " . Input::get("user_lang"), "general", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                        } else {
                            $errors = "Invalid language.";
                        }
                    }
                }
            } else {
                $errors = "";
                foreach ($validation->errors() as $error) {
                    $errors .= $error . "<br />";
                }
            }
        }
    }
}
$appname = $db->get("vncp_settings", ["item", "=", "app_name"])->first()->value;
$username = $user->data()->username;
$email = $user->data()->email;
$tfa_enabled = $user->data()->tfa_enabled;
$result = $db->limit_get_desc("vncp_users_ip_log", ["client_id", "=", $user->data()->id], "20");
$data = $result->all();
$enable_firewall = parse_input($db->get("vncp_settings", ["item", "=", "enable_firewall"])->first()->value);
$enable_forward_dns = parse_input($db->get("vncp_settings", ["item", "=", "enable_forward_dns"])->first()->value);
$enable_reverse_dns = parse_input($db->get("vncp_settings", ["item", "=", "enable_reverse_dns"])->first()->value);
$enable_notepad = parse_input($db->get("vncp_settings", ["item", "=", "enable_notepad"])->first()->value);
$enable_status = parse_input($db->get("vncp_settings", ["item", "=", "enable_status"])->first()->value);
$isAdmin = $user->hasPermission("admin");
$constants = false;
if (defined("constant") || defined("constant-fw")) {
    $constants = true;
}
$aclsetting = $db->get("vncp_settings", ["item", "=", "user_acl"])->first()->value;
$L = new Language($user->data()->language);
$L = $L->load();
if (!$L) {
    $log->log("Could not load language " . $user->data()->language, "error", 2, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
    exit("Language \"" . $user->data()->language . "\" not found.");
}
$langOptions = [];
foreach (glob("lang/*.json") as $file) {
    list($file) = explode("/", $file);
    list($file) = explode(".", $file);
    if ($file != $user->data()->language) {
        $langOptions[] = $file;
    }
}
echo $twig->render("profile.tpl", ["appname" => $appname, "errors" => $errors, "qrCodeUrl" => $qrCodeUrl, "username" => $username, "email" => $email, "formToken" => Token::generate(), "tfa_enabled" => $tfa_enabled, "data" => $data, "adminBase" => Config::get("admin/base"), "enable_firewall" => $enable_firewall, "enable_forward_dns" => $enable_forward_dns, "enable_reverse_dns" => $enable_reverse_dns, "enable_notepad" => $enable_notepad, "enable_status" => $enable_status, "isAdmin" => $isAdmin, "constants" => $constants, "aclsetting" => $aclsetting, "pagename" => "Profile", "L" => $L, "currentLang" => $user->data()->language, "langOptions" => $langOptions]);
echo "<input type=\"hidden\" value=\"" . Session::get("user") . "\" id=\"user\" />\r\n<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js\"></script>\r\n<script>window.jQuery || document.write('<script src=\"js/vendor/jquery-1.11.2.min.js\"><\\/script>')</script>\r\n<script type=\"text/javascript\" src=\"js/jquery-jvectormap-2.0.3.min.js\"></script>\r\n<script type=\"text/javascript\" src=\"js/jquery-jvectormap-world-mill.js\"></script>\r\n<script src=\"js/vendor/bootstrap.min.js\"></script>\r\n<script src=\"js/main.js\"></script>\r\n<script src=\"js/buttons.js\"></script>\r\n<script src=\"js/io.js\"></script>\r\n<script src=\"js/map.js\"></script>\r\n<script type=\"text/javascript\">\r\n    \$(\".geoip\").tooltip();\r\n</script>\r\n</body>\r\n</html>";

?>
