<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
function html_ify($string)
{
    return htmlentities($string, ENT_QUOTES, "UTF-8");
}
function get_random_chars($length)
{
    $charset = "abcdefghijlkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#\$%^&";
    $concat_char = "";
    $charset_length = strlen($charset);
    for ($i = 0; $i < $length; $i++) {
        $random_value = mt_rand(1, $charset_length);
        $random_char = $charset[$random_value - 1];
        $concat_char .= $random_char;
    }
    return $concat_char;
}
function _obfuscated_3715233532_($min, $max)
{
    return rand($min, $max);
}
function _obfuscated_0D2A082A285B0A0C0F0B251A362D240438031D0E251B32_($bytes, $decimals = 2)
{
    $storage_bytes = floor((strlen($bytes) - 1) / 3);
    return sprintf("%." . $decimals . "f", $bytes / pow(1024, $storage_bytes));
}
function get_size($bytes, $decimals = 2)
{
    $storage_sizes = ["B", "KB", "MB", "GB", "TB"];
    $storage_bytes = floor((strlen($bytes) - 1) / 3);
    return sprintf("%." . $decimals . "f", $bytes / pow(1024, $storage_bytes)) . $storage_sizes[$storage_bytes];
}
function round_bytes($bytes)
{
    return number_format($bytes / 1048576, 0, "", "");
}
function _obfuscated_0D0B3F3E2508030C0A1301173E273C0229342A0E243301_($seconds)
{
    try {
        $_obfuscated_0D3E15261D27152C05310C0A233C010B0A21045B242C01_ = new DateTime("@0");
        $_obfuscated_0D033F211C123B1D222715182F5B3604042D2C2D2C2222_ = new DateTime("@" . $seconds);
    } catch (Exception $_obfuscated_0D22060E2108273730182D2E1202232603100B38063801_) {
        return "0 seconds";
    }
    return $_obfuscated_0D3E15261D27152C05310C0A233C010B0A21045B242C01_->diff($_obfuscated_0D033F211C123B1D222715182F5B3604042D2C2D2C2222_)->format("%a days, %h hours, %i minutes, and %s seconds");
}
function _obfuscated_0D063F0D33221E0E273011111E2E3C373F1E140B402101_($db)
{
    $_obfuscated_0D2D40353F38031B223E12061E2F363D371C0F14341A22_ = $db->get("vncp_settings", ["item", "=", "panel_news"])->first()->value;
    return $_obfuscated_0D2D40353F38031B223E12061E2F363D371C0F14341A22_;
}
function _obfuscated_280C150D0612023C18192432_()
{
    $_obfuscated_0D2B17150B3919310734310F352627252A250D021C0801_ = "1.7";
    $_obfuscated_0D100F361106362838053C395C1B2C1D133C1018303522_ = "DB0F3CA79FC89DF894FB402916630A3E";
    return [$_obfuscated_0D2B17150B3919310734310F352627252A250D021C0801_, strtolower($_obfuscated_0D100F361106362838053C395C1B2C1D133C1018303522_)];
}
function _obfuscated_0D3E0406213F13343E043D0424160D0B1401371E133022_($val)
{
    echo "<pre>";
    print_r($val);
    echo "</pre>";
}
function _obfuscated_0D08331E33313D1804190621212C182B390F3508273311_()
{
    $_obfuscated_0D352D3B0D2B0D2530152F3F0A29191831302C0B053D32_ = Config::get("instance/vncp_secret_key");
    list($key) = explode(".", $_obfuscated_0D352D3B0D2B0D2530152F3F0A29191831302C0B053D32_);
    list($_obfuscated_0D3E3F1A2B351E352A3E01192C2E08401E1D0402160D32_) = explode(".", $_obfuscated_0D352D3B0D2B0D2530152F3F0A29191831302C0B053D32_);
    return [$key, $_obfuscated_0D3E3F1A2B351E352A3E01192C2E08401E1D0402160D32_];
}
function _obfuscated_0D0B122B0A36252F300E210B2C2F371716272B3D210322_($string)
{
    $_obfuscated_0D352D3B0D2B0D2530152F3F0A29191831302C0B053D32_ = _obfuscated_0D08331E33313D1804190621212C182B390F3508273311_();
    list($key, $_obfuscated_0D3E3F1A2B351E352A3E01192C2E08401E1D0402160D32_) = $_obfuscated_0D352D3B0D2B0D2530152F3F0A29191831302C0B053D32_;
    $_obfuscated_0D2208090B2B01402E02210C18212207370C112C320801_ = new org\magiclen\magiccrypt\MagicCrypt($key, 256, $_obfuscated_0D3E3F1A2B351E352A3E01192C2E08401E1D0402160D32_);
    return $_obfuscated_0D2208090B2B01402E02210C18212207370C112C320801_->encrypt($string);
}
function _obfuscated_0D0327272C1F1932192F383E152A352E0B013F2A023211_($ciphertext)
{
    $_obfuscated_0D352D3B0D2B0D2530152F3F0A29191831302C0B053D32_ = _obfuscated_0D08331E33313D1804190621212C182B390F3508273311_();
    list($key, $_obfuscated_0D3E3F1A2B351E352A3E01192C2E08401E1D0402160D32_) = $_obfuscated_0D352D3B0D2B0D2530152F3F0A29191831302C0B053D32_;
    $_obfuscated_0D2208090B2B01402E02210C18212207370C112C320801_ = new org\magiclen\magiccrypt\MagicCrypt($key, 256, $_obfuscated_0D3E3F1A2B351E352A3E01192C2E08401E1D0402160D32_);
    return $_obfuscated_0D2208090B2B01402E02210C18212207370C112C320801_->decrypt($ciphertext);
}
function get_server_url()
{
    if (isset($_SERVER["HTTPS"])) {
        $server_protocol = $_SERVER["HTTPS"] && $_SERVER["HTTPS"] != "off" ? "https" : "http";
    } else {
        $server_protocol = "http";
    }
    return $server_protocol . "://" . $_SERVER["HTTP_HOST"];
}
function check_https()
{
    if (isset($_SERVER["HTTPS"])) {
        return $_SERVER["HTTPS"] && $_SERVER["HTTPS"] != "off" ? true : false;
    }
}
function get_cidr_range($cidr)
{
    $range = [];
    $cidr = explode("/", $cidr);
    $range[0] = long2ip(ip2long($cidr[0]) & -1 << 32 - (int) $cidr[1]);
    $range[1] = long2ip(ip2long($range[0]) + pow(2, 32 - (int) $cidr[1]) - 1);
    return $range;
}
function get_error_level($num)
{
    if ($num == 0) {
        return "info";
    }
    if ($num == 1) {
        return "warning";
    }
    return "fatal";
}
function _obfuscated_0D2F0E193339112D352A2E231B0C2C300E5B153D1E2A01_($url, $postfields)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfields));
    $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_ = curl_exec($ch);
    if (curl_error($ch)) {
        return false;
    }
    curl_close($ch);
    $_obfuscated_0D095B2B2312060638052C21041001261428170C0A3832_ = json_decode($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_, true);
    return $_obfuscated_0D095B2B2312060638052C21041001261428170C0A3832_;
}
function _obfuscated_0D06290C36152202271F34401E1E173928350B253E2122_($ip, $range)
{
    if (!strpos($range, "/")) {
        $range .= "/24";
    }
    list($range, $_obfuscated_0D1F1F2D4026353D395C0B0C0A19255B0A220F26180C22_) = explode("/", $range, 2);
    $_obfuscated_0D090D2522210C091D172F023C5B36010B30043F313601_ = ip2long($range);
    $_obfuscated_0D2C3F0C391E5B073E022E023E4006162F073F19153B22_ = ip2long($ip);
    $_obfuscated_0D0817291403300F1B3B0112092C1D1A080C3505055B11_ = pow(2, 32 - $_obfuscated_0D1F1F2D4026353D395C0B0C0A19255B0A220F26180C22_) - 1;
    $_obfuscated_0D0A03043D141B1A2B041C15100A3126151E2F1F243622_ = ~$_obfuscated_0D0817291403300F1B3B0112092C1D1A080C3505055B11_;
    return ($_obfuscated_0D2C3F0C391E5B073E022E023E4006162F073F19153B22_ & $_obfuscated_0D0A03043D141B1A2B041C15100A3126151E2F1F243622_) == ($_obfuscated_0D090D2522210C091D172F023C5B36010B30043F313601_ & $_obfuscated_0D0A03043D141B1A2B041C15100A3126151E2F1F243622_);
}
function _obfuscated_0D2E3E381F2C0610333E1A06241D151B19302D2F173C22_($netmask)
{
    $_obfuscated_0D283E073B2D22091B35220C211F2E14311F023D1B3C11_ = ["255.255.255.255" => 32, "255.255.255.254" => 31, "255.255.255.252" => 30, "255.255.255.248" => 29, "255.255.255.240" => 28, "255.255.255.224" => 27, "255.255.255.192" => 26, "255.255.255.128" => 25, "255.255.255.0" => 24, "255.255.254.0" => 23, "255.255.252.0" => 22, "255.255.248.0" => 21, "255.255.240.0" => 20, "255.255.224.0" => 19, "255.255.192.0" => 18, "255.255.128.0" => 17, "255.255.0.0" => 16, "255.254.0.0" => 15, "255.252.0.0" => 14, "255.248.0.0" => 13, "255.240.0.0" => 12, "255.224.0.0" => 11, "255.192.0.0" => 10, "255.128.0.0" => 9, "255.0.0.0" => 8];
    if (array_key_exists($netmask, $_obfuscated_0D283E073B2D22091B35220C211F2E14311F023D1B3C11_)) {
        return $_obfuscated_0D283E073B2D22091B35220C211F2E14311F023D1B3C11_[$netmask];
    }
    return 24;
}

?>
