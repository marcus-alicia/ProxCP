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
if (Input::exists() && Token::check(Input::get("token"))) {
    $validate = new Validate();
    $validation = $validate->check($_POST, ["username" => ["required" => true, "max" => 100, "min" => 5]]);
    if ($validation->passed()) {
        $fetch = $db->get("vncp_settings", ["id", "!=", 0])->all();
        $current = [];
        for ($i = 0; $i < count($fetch); $i++) {
            $current[$fetch[$i]->item] = $fetch[$i]->value;
        }
        $reset = new User(Input::get("username"));
        if ($reset->exists()) {
            $salt = Hash::salt(32);
            $string = _obfuscated_0D2A1936372B37323515280F0A332824145B2631012232_(8);
            $user->update(["password" => Hash::make($string, $salt), "salt" => $salt], $reset->data()->id);
            if ($current["mail_type"] == "sysmail") {
                $to = $reset->data()->email;
                $appname = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["app_name"]);
                $subject = $appname . " Password Reset Request";
                $message = "Hello " . $reset->data()->username . ",\r\n\r\n  " . $appname . " has received a request to reset your password as it seems that you have forgotten it. Here is your new information:\r\n\r\n  Username: " . $reset->data()->username . "\r\n  Password: " . $string . "\r\n\r\n  Be sure you remember it!\r\n\r\n  Regards,\r\n  " . $appname . " Bot\r\n  URL: " . Config::get("instance/base");
                $from_email = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["from_email"]);
                $headers = "From: " . $from_email . "\r\n" . "Reply-To: " . $from_email . "\r\n" . "X-Mailer: PHP/" . phpversion();
                mail($to, $subject, $message, $headers);
            } else {
                $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["smtp_host"]);
                    $mail->SMTPAuth = true;
                    $mail->Username = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["smtp_username"]);
                    $mail->Password = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_(_obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($current["smtp_password"]));
                    if ($current["smtp_type"] == "ssltls") {
                        $mail->SMTPSecure = "ssl";
                    } else {
                        if ($current["smtp_type"] == "starttls") {
                            $mail->SMTPSecure = "tls";
                        }
                    }
                    $mail->Port = (int) _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["smtp_port"]);
                    $mail->setFrom(_obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["from_email"]), _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["from_email_name"]));
                    $mail->addAddress($reset->data()->email);
                    $mail->addReplyTo(_obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["from_email"]), _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["from_email_name"]));
                    $mail->isHTML(false);
                    $mail->Subject = _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["app_name"]) . " Password Reset Request";
                    $mail->Body = "Hello " . $reset->data()->username . ",\r\n\r\n    " . _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["app_name"]) . " has received a request to reset your password as it seems that you have forgotten it. Here is your new information:\r\n\r\n    Username: " . $reset->data()->username . "\r\n    Password: " . $string . "\r\n\r\n    Be sure you remember it!\r\n\r\n    Regards,\r\n    " . _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($current["app_name"]) . " Bot\r\n    URL: " . Config::get("instance/base");
                    $mail->send();
                } catch (PHPMailer\PHPMailer\Exception $e) {
                    $log->log("Mailer error: {" . $mail->ErrorInfo . "}", "error", 1, $reset->data()->username, $_SERVER["REMOTE_ADDR"]);
                    echo "Message could not be sent. Please contact administrator.";
                }
            }
            $log->log("User " . $reset->data()->username . " changed password - forgot password.", "general", 0, $reset->data()->username, $_SERVER["REMOTE_ADDR"]);
            Redirect::to("login");
        } else {
            $errors = "The username you entered is invalid.";
        }
    } else {
        $errors = "";
        foreach ($validation->errors as $error) {
            $errors .= $error . "<br />";
        }
    }
}
$appname = $db->get("vncp_settings", ["item", "=", "app_name"])->first()->value;
echo $twig->render("forgotpassword.tpl", ["appname" => $appname, "gethttps" => _obfuscated_0D0329080D2F17391B0C2F1A2F1B2F0A0E5B011B330511_(), "errors" => $errors, "formToken" => Token::generate(), "pagename" => "Forgot Password"]);
echo "</div>\r\n</div>\r\n</div>\r\n</div>\r\n<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js\"></script>\r\n<script>window.jQuery || document.write('<script src=\"js/vendor/jquery-1.11.2.min.js\"><\\/script>')</script>\r\n<script src=\"js/vendor/bootstrap.min.js\"></script>\r\n<script src=\"js/main.js\"></script>\r\n<script src=\"js/buttons.js\"></script>\r\n</body>\r\n</html>";

?>
