<?php

require_once dirname(__FILE__, 2) . "/lib/MagicCrypt.php";
require_once dirname(__FILE__, 2) . "/lib/phpmailer/src/Exception.php";
require_once dirname(__FILE__, 2) . "/lib/phpmailer/src/PHPMailer.php";
require_once dirname(__FILE__, 2) . "/lib/phpmailer/src/SMTP.php";
if (php_sapi_name() == "cli") {
    list($to, $delcode) = $argv;
    $load = file_get_contents(dirname(__FILE__, 2) . "/config.js");
    $pos_company_name = strrpos($load, "company_name");
    $pos_company_name = substr($load, $pos_company_name);
    list($company_name) = explode("'", $pos_company_name);
    $pos_sqlhost = strrpos($load, "sqlHost");
    $pos_sqlhost = substr($load, $pos_sqlhost);
    list($sqlhost) = explode("'", $pos_sqlhost);
    $pos_sqluser = strrpos($load, "sqlUser");
    $pos_sqluser = substr($load, $pos_sqluser);
    list($sqluser) = explode("'", $pos_sqluser);
    $pos_sqlpw = strrpos($load, "sqlPassword");
    $pos_sqlpw = substr($load, $pos_sqlpw);
    list($sqlpw) = explode("'", $pos_sqlpw);
    $pos_sqldb = strrpos($load, "sqlDB");
    $pos_sqldb = substr($load, $pos_sqldb);
    list($sqldb) = explode("'", $pos_sqldb);
    $con = mysqli_connect($sqlhost, $sqluser, $sqlpw);
    mysqli_select_db($con, $sqldb);
    if (!$con) {
        exit("100");
    }
    $query = mysqli_query($con, "SELECT * FROM vncp_settings WHERE 1");
    if (!$query) {
        exit("200");
    }
    $current = [];
    while ($settings = mysqli_fetch_array($query)) {
        $current[$settings["item"]] = $settings["value"];
    }
    if ($current["mail_type"] == "sysmail") {
        $subject = $company_name . " Cloud Deletion Request";
        $message = "Hello,\r\n\r\nWe have received a request to delete a VM from your cloud pool. To confirm the request, please enter the confirmation code below.\r\n\r\n" . $delcode . "\r\n\r\nIf this request was made by accident, this email can be ignored. This code will expire in 24 hours and your VM will not be deleted.\r\n\r\nIf this request was not made by you, please contact us immediately.\r\n\r\nRegards,\r\n" . $company_name . " Team";
        $headers = "From: " . _obfuscated_0D2F0A1A1E380D392713032C37301B251C333336340401_($current["from_email"]) . "\r\n" . "Reply-To: " . _obfuscated_0D2F0A1A1E380D392713032C37301B251C333336340401_($current["from_email"]) . "\r\n" . "X-Mailer: PHP/" . phpversion();
        mail($to, $subject, $message, $headers);
    } else {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = _obfuscated_0D2F0A1A1E380D392713032C37301B251C333336340401_($current["smtp_host"]);
            $mail->SMTPAuth = true;
            $mail->Username = _obfuscated_0D2F0A1A1E380D392713032C37301B251C333336340401_($current["smtp_username"]);
            $mail->Password = _obfuscated_0D2F0A1A1E380D392713032C37301B251C333336340401_(_obfuscated_0D26311B3802180B1D1615353611253932110F1E5B1C22_($current["smtp_password"]));
            if ($current["smtp_type"] == "ssltls") {
                $mail->SMTPSecure = "ssl";
            } else {
                if ($current["smtp_type"] == "starttls") {
                    $mail->SMTPSecure = "tls";
                }
            }
            $mail->Port = (int) _obfuscated_0D2F0A1A1E380D392713032C37301B251C333336340401_($current["smtp_port"]);
            $mail->setFrom(_obfuscated_0D2F0A1A1E380D392713032C37301B251C333336340401_($current["from_email"]), _obfuscated_0D2F0A1A1E380D392713032C37301B251C333336340401_($current["from_email_name"]));
            $mail->addAddress($to);
            $mail->addReplyTo(_obfuscated_0D2F0A1A1E380D392713032C37301B251C333336340401_($current["from_email"]), _obfuscated_0D2F0A1A1E380D392713032C37301B251C333336340401_($current["from_email_name"]));
            $mail->isHTML(false);
            $mail->Subject = $company_name . " Cloud Deletion Request";
            $mail->Body = "Hello,\r\n\r\nWe have received a request to delete a VM from your cloud pool. To confirm the request, please enter the confirmation code below.\r\n\r\n" . $delcode . "\r\n\r\nIf this request was made by accident, this email can be ignored. This code will expire in 24 hours and your VM will not be deleted.\r\n\r\nIf this request was not made by you, please contact us immediately.\r\n\r\nRegards,\r\n" . $company_name . " Team";
            $mail->send();
        } catch (PHPMailer\PHPMailer\Exception $e) {
            exit("Message could not be sent. Please check your SMTP settings. Mailer Error: " . $mail->ErrorInfo);
        }
    }
}
function _obfuscated_0D1C3E3F2E160C083112081723103D05351D2738302211_()
{
    $load = file_get_contents(dirname(__FILE__, 2) . "/config.js");
    $pos = strrpos($load, "vncp_secret_key");
    $pos = substr($load, $pos);
    list($pos) = explode("'", $pos);
    list($key) = explode(".", $pos);
    list($_obfuscated_0D093F5B36255C1D0A0B383E04322D5B0813222B020732_) = explode(".", $pos);
    return [$key, $_obfuscated_0D093F5B36255C1D0A0B383E04322D5B0813222B020732_];
}
function _obfuscated_0D26311B3802180B1D1615353611253932110F1E5B1C22_($ciphertext)
{
    $load = _obfuscated_0D1C3E3F2E160C083112081723103D05351D2738302211_();
    list($key, $_obfuscated_0D093F5B36255C1D0A0B383E04322D5B0813222B020732_) = $load;
    $_obfuscated_0D14120C380939243D213D3C303001291E0A3731291A22_ = new org\magiclen\magiccrypt\MagicCrypt($key, 256, $_obfuscated_0D093F5B36255C1D0A0B383E04322D5B0813222B020732_);
    return $_obfuscated_0D14120C380939243D213D3C303001291E0A3731291A22_->decrypt($ciphertext);
}
function _obfuscated_0D2F0A1A1E380D392713032C37301B251C333336340401_($string)
{
    return htmlentities($string, ENT_QUOTES, "UTF-8");
}

?>
