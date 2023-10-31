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
    $l_array = _obfuscated_0D24283F12023F041C383230393C32170E1D0837160111_($connection, 0);
    if ($l_array["notification_case"] != "notification_license_ok") {
        mysqli_close($connection);
        exit("License check failed: " . $l_array["notification_text"]);
    }
    $user = new User();
    if ($user->isLoggedIn()) {
        Redirect::to("index");
    }
}
$db = DB::getInstance();
$log = new Logger();
if (Input::exists() && Input::get("form_name") == "login_form") {
    if (Token::check(Input::get("token"))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, ["username" => ["required" => true, "max" => 100, "min" => 5], "password" => ["required" => true]]);
        if ($validation->passed()) {
            $remember = Input::get("remember_me") === "on" ? true : false;
            $login = $user->login(strtolower(Input::get("username")), Input::get("password"), $remember);
            if ($login) {
                if ($user->data()->locked == 1) {
                    $errors = "This account is disabled.";
                    $log->log("Attempted to login to disabled account.", "error", 1, Input::get("username"), $_SERVER["REMOTE_ADDR"]);
                    $user->logout();
                } else {
                    $user_acl = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($db->get("vncp_settings", ["item", "=", "user_acl"])->first()->value);
                    if ($user_acl == "true") {
                        $rip = $db->get("vncp_acl", ["user_id", "=", $user->data()->id]);
                        $dip = $rip->all();
                        $vip = 0;
                        $i = 0;
                        while ($i < count($dip)) {
                            if ($dip[$i]->ipaddress == $_SERVER["REMOTE_ADDR"]) {
                                $vip = 1;
                            } else {
                                $i++;
                            }
                        }
                    } else {
                        $vip = 1;
                    }
                    if ($vip == 1 || count($dip) == 0) {
                        if ($user->data()->tfa_enabled == 1) {
                            $tSalt = $user->data()->salt;
                            $user->logout();
                            $modalForm = "<form role=\"form\" action=\"\" method=\"POST\">\r\n                            <fieldset>\r\n                                <h2>2FA Required</h2>\r\n                                <hr class=\"pulse\" />\r\n                                <div class=\"form-group\">\r\n                                    <input type=\"password\" name=\"totptoken\" id=\"totptoken\" class=\"form-control input-lg\" placeholder=\"XXXXXX\">\r\n                                </div>\r\n                                <hr class=\"pulse\">\r\n                                <div class=\"row\">\r\n                                    <div class=\"col-xs-12 col-sm-12 col-md-12\">\r\n                                        <input type=\"submit\" class=\"btn btn-lg btn-success btn-block\" value=\"Login\">\r\n                                    </div>\r\n                                </div>\r\n                            </fieldset>\r\n                            <input type=\"hidden\" name=\"username\" value=\"" . strtolower(Input::get("username")) . "\" />\r\n                            <input type=\"hidden\" name=\"c\" value=\"" . _obfuscated_0D1A2A3B0501041909311C2D0A3D2A1D290304395C0A01_(Hash::make(Input::get("password"), $tSalt)) . "\" />\r\n                            <input type=\"hidden\" name=\"form_name\" value=\"totp_form\" />\r\n                        </form>";
                        } else {
                            $reader = new GeoIp2\Database\Reader("core/GeoLite2-City.mmdb");
                            $logged_ip = $_SERVER["REMOTE_ADDR"];
                            $record = $reader->city($logged_ip);
                            $log_ip = $db->insert("vncp_users_ip_log", ["client_id" => $user->data()->id, "date" => date("Y-m-d H:i:s"), "ip" => $logged_ip, "geoip_loc" => "" . $record->city->name . ", " . $record->mostSpecificSubdivision->isoCode . ", " . $record->country->isoCode, "geoip_coords" => "" . $record->location->latitude . " " . $record->location->longitude]);
                            Redirect::to("index");
                        }
                    } else {
                        $errors = "Client IP address mismatch.";
                        $log->log("Client IP address mismatch", "error", 1, Input::get("username"), $_SERVER["REMOTE_ADDR"]);
                        $user->logout();
                    }
                }
            } else {
                $errors = "An error occurred while attempting to process your login request. An incorrect username or password was entered.";
            }
        } else {
            $errors = "";
            foreach ($validation->errors() as $error) {
                $errors .= $error . "<br />";
            }
        }
    }
} else {
    if (Input::exists() && Input::get("form_name") == "totp_form") {
        $validate = new Validate();
        $validation = $validate->check($_POST, ["totptoken" => ["required" => true, "min" => 6, "max" => 6, "numonly" => true], "username" => ["required" => true, "valemail" => true, "min" => 4, "max" => 100], "c" => ["required" => true]]);
        if ($validation->passed()) {
            $login = $user->loginHash(strtolower(Input::get("username")), _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_(Input::get("c")));
            if ($login) {
                $ga = new vncp_GoogleAuthenticator();
                $checkResult = $ga->verifyCode($user->data()->tfa_secret, Input::get("totptoken"), 2);
                if ($checkResult) {
                    $reader = new GeoIp2\Database\Reader("core/GeoLite2-City.mmdb");
                    $logged_ip = $_SERVER["REMOTE_ADDR"];
                    $record = $reader->city($logged_ip);
                    $log_ip = $db->insert("vncp_users_ip_log", ["client_id" => $user->data()->id, "date" => date("Y-m-d H:i:s"), "ip" => $logged_ip, "geoip_loc" => "" . $record->city->name . ", " . $record->mostSpecificSubdivision->isoCode . ", " . $record->country->isoCode, "geoip_coords" => "" . $record->location->latitude . " " . $record->location->longitude]);
                    Redirect::to("index");
                } else {
                    Redirect::to("logout");
                }
            } else {
                Redirect::to("login");
            }
        } else {
            Redirect::to("login");
        }
    }
}
$ssoemail = "";
if (Input::exists("GET") && isset($_GET["u"]) && (Input::get("from") == "whmcs" || Input::get("from") == "blesta")) {
    $ssoemail = base64_decode(urldecode(Input::get("u")));
    if (!$ssoemail) {
        $ssoemail = "";
    } else {
        $ssoemail = trim(stripslashes(_obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_((string) $ssoemail)));
    }
}
$appname = $db->get("vncp_settings", ["item", "=", "app_name"])->first()->value;
$support_ticket_url = $db->get("vncp_settings", ["item", "=", "support_ticket_url"])->first()->value;
echo $twig->render("login.tpl", ["appname" => $appname, "gethttps" => _obfuscated_0D0329080D2F17391B0C2F1A2F1B2F0A0E5B011B330511_(), "modalForm" => $modalForm, "errors" => $errors, "formToken" => Token::generate(), "support_ticket_url" => $support_ticket_url, "pagename" => "Login", "ssoemail" => $ssoemail]);
if (isset($GLOBALS["proxcp_branding"]) && !empty($GLOBALS["proxcp_branding"])) {
    echo $GLOBALS["proxcp_branding"];
}
echo "</div>\r\n</div>\r\n</div>\r\n</div>\r\n<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js\"></script>\r\n<script>window.jQuery || document.write('<script src=\"js/vendor/jquery-1.11.2.min.js\"><\\/script>')</script>\r\n<script src=\"js/vendor/bootstrap.min.js\"></script>\r\n<script src=\"js/main.js\"></script>\r\n<script src=\"js/buttons.js\"></script>\r\n</body>\r\n</html>";

?>
