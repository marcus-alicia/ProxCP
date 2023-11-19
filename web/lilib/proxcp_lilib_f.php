<?php

function _obfuscated_072C162A11091E5B183E1F3E23281201_($string, $key)
{
    $_obfuscated_0D0C04303915383229171E2C2E21191D0F05033D281132_ = NULL;
    if (!empty($string) && !empty($key)) {
        $_obfuscated_0D3E3F1A2B351E352A3E01192C2E08401E1D0402160D32_ = _obfuscated_0D3B292E23311A40030E233B250F2A3924391916151E11_(_obfuscated_0D1E2D3321142C090422322330252E0304330D40272C22_("aes-256-cbc"));
        $_obfuscated_0D0C04303915383229171E2C2E21191D0F05033D281132_ = _obfuscated_0D3B1807401502323C091727081E08091B2E5C3F0A3522_($string, "aes-256-cbc", $key, 0, $_obfuscated_0D3E3F1A2B351E352A3E01192C2E08401E1D0402160D32_);
        $_obfuscated_0D0C04303915383229171E2C2E21191D0F05033D281132_ = base64_encode($_obfuscated_0D0C04303915383229171E2C2E21191D0F05033D281132_ . "::" . $_obfuscated_0D3E3F1A2B351E352A3E01192C2E08401E1D0402160D32_);
    }
    return $_obfuscated_0D0C04303915383229171E2C2E21191D0F05033D281132_;
}
function decrypt_date($string, $key)
{
    $_obfuscated_0D351A162B2A5C1724070827041A3B0919151331063432_ = NULL;
    if (!empty($string) && !empty($key)) {
        $string = base64_decode($string);
        if (stristr($string, "::")) {
            $_obfuscated_0D23123D310C3C3E2C3B05391A0B401A191F082E072311_ = explode("::", $string, 2);
            if (!empty($_obfuscated_0D23123D310C3C3E2C3B05391A0B401A191F082E072311_) && count($_obfuscated_0D23123D310C3C3E2C3B05391A0B401A191F082E072311_) == 2) {
                list($_obfuscated_0D0C04303915383229171E2C2E21191D0F05033D281132_, $_obfuscated_0D3E3F1A2B351E352A3E01192C2E08401E1D0402160D32_) = $_obfuscated_0D23123D310C3C3E2C3B05391A0B401A191F082E072311_;
                $_obfuscated_0D351A162B2A5C1724070827041A3B0919151331063432_ = _obfuscated_0D2E1C36283F2E1A3B2D24093B39221C16153E03382311_($_obfuscated_0D0C04303915383229171E2C2E21191D0F05033D281132_, "aes-256-cbc", $key, 0, $_obfuscated_0D3E3F1A2B351E352A3E01192C2E08401E1D0402160D32_);
            }
        }
    }
    return $_obfuscated_0D351A162B2A5C1724070827041A3B0919151331063432_;
}
function _obfuscated_0D12042936250E240D220B0B073D120436403807332B11_($number, $min_value = 0, $max_value = INF)
{
    $result = false;
    if (!is_float($number) && filter_var($number, FILTER_VALIDATE_INT, ["options" => ["min_range" => $min_value, "max_range" => $max_value]]) !== false) {
        $result = true;
    }
    return $result;
}
function _obfuscated_213032_($url)
{
    $result = false;
    if (!empty($url)) {
        if (preg_match("/^[a-z0-9-.]+\\.[a-z\\.]{2,7}\$/", strtolower($url))) {
            $result = true;
        } else {
            $result = false;
        }
    }
    return $result;
}
function _obfuscated_0D40121D360121092307012A0B15281A330C1804241A22_($remove_last_slash = NULL)
{
    $server_protocol = "http";
    $_obfuscated_0D2430222E1A3D2D352A333914191D2F391E1334213411_ = NULL;
    $_obfuscated_0D021A06021E1F0F2A250919250A1A02381E2A021E2F32_ = NULL;
    $_obfuscated_0D2205023C27334038152E081B3D375C0721141E052532_ = NULL;
    $_obfuscated_0D330A3E013B2B3F241E2E0C312E3723073E110F5B3911_ = NULL;
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off" || isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https") {
        $server_protocol = "https";
    }
    if (isset($_SERVER["HTTP_HOST"])) {
        $_obfuscated_0D2430222E1A3D2D352A333914191D2F391E1334213411_ = $_SERVER["HTTP_HOST"];
    }
    if (isset($_SERVER["SCRIPT_NAME"])) {
        $_obfuscated_0D021A06021E1F0F2A250919250A1A02381E2A021E2F32_ = $_SERVER["SCRIPT_NAME"];
    }
    if (isset($_SERVER["QUERY_STRING"])) {
        $_obfuscated_0D2205023C27334038152E081B3D375C0721141E052532_ = $_SERVER["QUERY_STRING"];
    }
    if (!empty($server_protocol) && !empty($_obfuscated_0D2430222E1A3D2D352A333914191D2F391E1334213411_) && !empty($_obfuscated_0D021A06021E1F0F2A250919250A1A02381E2A021E2F32_)) {
        $_obfuscated_0D330A3E013B2B3F241E2E0C312E3723073E110F5B3911_ = $server_protocol . "://" . $_obfuscated_0D2430222E1A3D2D352A333914191D2F391E1334213411_ . $_obfuscated_0D021A06021E1F0F2A250919250A1A02381E2A021E2F32_;
        if (!empty($_obfuscated_0D2205023C27334038152E081B3D375C0721141E052532_)) {
            $_obfuscated_0D330A3E013B2B3F241E2E0C312E3723073E110F5B3911_ .= "?" . $_obfuscated_0D2205023C27334038152E081B3D375C0721141E052532_;
        }
        if ($remove_last_slash == 1) {
            while (substr($_obfuscated_0D330A3E013B2B3F241E2E0C312E3723073E110F5B3911_, -1) == "/") {
                $_obfuscated_0D330A3E013B2B3F241E2E0C312E3723073E110F5B3911_ = substr($_obfuscated_0D330A3E013B2B3F241E2E0C312E3723073E110F5B3911_, 0, -1);
            }
        }
    }
    return $_obfuscated_0D330A3E013B2B3F241E2E0C312E3723073E110F5B3911_;
}
function _obfuscated_0D2D04373008333C5B24195B095B14301F5B3431080D01_($url)
{
    $_obfuscated_0D0602363516291710062A35281514051E121714023811_ = NULL;
    if (!empty($url)) {
        $_obfuscated_0D5C270335132E07261A122105171005022E3D17103C32_ = parse_url($url);
        if (empty($_obfuscated_0D5C270335132E07261A122105171005022E3D17103C32_["scheme"])) {
            $url = "http://" . $url;
            $_obfuscated_0D5C270335132E07261A122105171005022E3D17103C32_ = parse_url($url);
        }
        if (!empty($_obfuscated_0D5C270335132E07261A122105171005022E3D17103C32_["host"])) {
            $_obfuscated_0D0602363516291710062A35281514051E121714023811_ = $_obfuscated_0D5C270335132E07261A122105171005022E3D17103C32_["host"];
            $_obfuscated_0D0602363516291710062A35281514051E121714023811_ = trim(str_ireplace("www.", "", filter_var($_obfuscated_0D0602363516291710062A35281514051E121714023811_, FILTER_SANITIZE_URL)));
        }
    }
    return $_obfuscated_0D0602363516291710062A35281514051E121714023811_;
}
function _obfuscated_0D1131190B291D342535160B1E1B1016173D1608053322_($url, $remove_scheme, $remove_www, $remove_path, $remove_last_slash)
{
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $_obfuscated_0D5C270335132E07261A122105171005022E3D17103C32_ = parse_url($url);
        $url = str_ireplace($_obfuscated_0D5C270335132E07261A122105171005022E3D17103C32_["scheme"] . "://", "", $url);
        if ($remove_path == 1) {
            $_obfuscated_0D3F19070132272D28023D1D2D0B120E1D0307380E3B11_ = stripos($url, "/");
            if (0 < $_obfuscated_0D3F19070132272D28023D1D2D0B120E1D0307380E3B11_) {
                $url = substr($url, 0, $_obfuscated_0D3F19070132272D28023D1D2D0B120E1D0307380E3B11_ + 1);
            }
        } else {
            $_obfuscated_0D361312323018350E2A0F2D2E30353823333B285C3001_ = strripos($url, "/");
            if (0 < $_obfuscated_0D361312323018350E2A0F2D2E30353823333B285C3001_) {
                $url = substr($url, 0, $_obfuscated_0D361312323018350E2A0F2D2E30353823333B285C3001_ + 1);
            }
        }
        if ($remove_scheme != 1) {
            $url = $_obfuscated_0D5C270335132E07261A122105171005022E3D17103C32_["scheme"] . "://" . $url;
        }
        if ($remove_www == 1) {
            $url = str_ireplace("www.", "", $url);
        }
        if ($remove_last_slash == 1) {
            while (substr($url, -1) == "/") {
                $url = substr($url, 0, -1);
            }
        }
    }
    return trim($url);
}
function curl_post_request($url, $post_info = NULL, $refer = NULL)
{
    $useragent = "phpmillion cURL";
    $timeout = 10;
    $retval = [];
    $_obfuscated_0D0F055B0C19071D1B3B07041518120916281F0B190A01_ = [];
    if (filter_var($url, FILTER_VALIDATE_URL) && !empty($post_info)) {
        if (empty($refer) || !filter_var($refer, FILTER_VALIDATE_URL)) {
            $refer = $url;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_REFERER, $refer);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_info);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($curl, $header) use($formatted_headers_array) {
            $len = strlen($header);
            $header = explode(":", $header, 2);
            if (count($header) < 2) {
                return $len;
            }
            $name = strtolower(trim($header[0]));
            $formatted_headers_array[$name] = trim($header[1]);
            return $len;
        });
        $result = curl_exec($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);
        $retval["headers"] = $formatted_headers_array;
        $retval["error"] = $curl_error;
        $retval["body"] = $result;
    }
    return $retval;
}
function _obfuscated_073B3E032F241323122829193B5B3E0E0D1E0301_($datetime, $format)
{
    $result = false;
    if (!empty($datetime) && !empty($format)) {
        $datetime = DateTime::createFromFormat($format, $datetime);
        $_obfuscated_0D013C073C142F050223283C173B352C22180E0D042F32_ = DateTime::getLastErrors();
        if ($datetime && empty($_obfuscated_0D013C073C142F050223283C173B352C22180E0D042F32_["warning_count"])) {
            $result = true;
        }
    }
    return $result;
}
function date_difference($date_from, $date_to)
{
    $_obfuscated_0D10343D1F3F0B342A012B3038293B5B040E3E04180422_ = 0;
    if (_obfuscated_0D3B5C073B3E032F241323122829193B5B3E0E0D1E0301_($date_from, "Y-m-d") && _obfuscated_0D3B5C073B3E032F241323122829193B5B3E0E0D1E0301_($date_to, "Y-m-d")) {
        $date_to = new DateTime($date_to);
        $date_from = new DateTime($date_from);
        $_obfuscated_0D10343D1F3F0B342A012B3038293B5B040E3E04180422_ = $date_from->diff($date_to)->format("%a");
    }
    return $_obfuscated_0D10343D1F3F0B342A012B3038293B5B040E3E04180422_;
}
function _obfuscated_1A380F1E1D32023714183B1422_($content, $tag_name)
{
    $_obfuscated_0D3B1A362A2C0B0929213F091F03073F271829172F0922_ = NULL;
    if (!empty($content) && !empty($tag_name)) {
        preg_match_all("/<" . preg_quote($tag_name, "/") . ">(.*?)<\\/" . preg_quote($tag_name, "/") . ">/ims", $content, $_obfuscated_0D0612351C1D02352F2C0533245C0B223F0C1E3E023F11_, PREG_SET_ORDER);
        if (!empty($_obfuscated_0D0612351C1D02352F2C0533245C0B223F0C1E3E023F11_[0][1])) {
            $_obfuscated_0D3B1A362A2C0B0929213F091F03073F271829172F0922_ = trim($_obfuscated_0D0612351C1D02352F2C0533245C0B223F0C1E3E023F11_[0][1]);
        }
    }
    return $_obfuscated_0D3B1A362A2C0B0929213F091F03073F271829172F0922_;
}
function parse_license_response($content_array, $ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE)
{
    $retval = [];
    if (!empty($content_array)) {
        if (!empty($content_array["headers"]["notification_server_signature"]) && _obfuscated_0D3E1E193D0F36150B362F122C1414351802091F031032_($content_array["headers"]["notification_server_signature"], $ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE)) {
            $retval["notification_case"] = $content_array["headers"]["notification_case"];
            $retval["notification_text"] = $content_array["headers"]["notification_text"];
            if (!empty($content_array["headers"]["notification_data"])) {
                $retval["notification_data"] = json_decode($content_array["headers"]["notification_data"], true);
            }
        } else {
            $retval["notification_case"] = "notification_invalid_response";
            $retval["notification_text"] = APL_NOTIFICATION_INVALID_RESPONSE;
        }
    } else {
        $retval["notification_case"] = "notification_no_connection";
        $retval["notification_text"] = APL_NOTIFICATION_NO_CONNECTION;
    }
    return $retval;
}
function _obfuscated_3D2C2206033628161F0F211332_($ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE)
{
    $_obfuscated_0D3C362E150317325B1B25220605112A283D5C39043401_ = NULL;
    $_obfuscated_0D2D0723322209291413023F12321109312E29185C4001_ = gethostbynamel(_obfuscated_0D2D04373008333C5B24195B095B14301F5B3431080D01_(APL_ROOT_URL));
    if (!empty($ROOT_URL) && isset($CLIENT_EMAIL) && isset($LICENSE_CODE) && !empty($_obfuscated_0D2D0723322209291413023F12321109312E29185C4001_)) {
        $_obfuscated_0D3C362E150317325B1B25220605112A283D5C39043401_ = hash("sha256", gmdate("Y-m-d") . $ROOT_URL . $CLIENT_EMAIL . $LICENSE_CODE . APL_PRODUCT_ID . implode("", $_obfuscated_0D2D0723322209291413023F12321109312E29185C4001_));
    }
    return $_obfuscated_0D3C362E150317325B1B25220605112A283D5C39043401_;
}
function _obfuscated_1E0E2302070F03250C073935033B11_($notification_server_signature, $ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE)
{
    $result = false;
    $_obfuscated_0D2D0723322209291413023F12321109312E29185C4001_ = gethostbynamel(_obfuscated_0D2D04373008333C5B24195B095B14301F5B3431080D01_(APL_ROOT_URL));
    if (!empty($notification_server_signature) && !empty($ROOT_URL) && isset($CLIENT_EMAIL) && isset($LICENSE_CODE) && !empty($_obfuscated_0D2D0723322209291413023F12321109312E29185C4001_) && hash("sha256", implode("", $_obfuscated_0D2D0723322209291413023F12321109312E29185C4001_) . APL_PRODUCT_ID . $LICENSE_CODE . $CLIENT_EMAIL . $ROOT_URL . gmdate("Y-m-d")) == $notification_server_signature) {
        $result = true;
    }
    return $result;
}
function validate_configuration()
{
    $retval = [];
    if (!APL_SALT || APL_SALT == "some_random_text") {
        $retval[] = APL_CORE_NOTIFICATION_INVALID_SALT;
    }
    if (!filter_var(APL_ROOT_URL, FILTER_VALIDATE_URL) || !ctype_alnum(substr(APL_ROOT_URL, -1))) {
        $retval[] = APL_CORE_NOTIFICATION_INVALID_ROOT_URL;
    }
    if (!filter_var(APL_PRODUCT_ID, FILTER_VALIDATE_INT)) {
        $retval[] = APL_CORE_NOTIFICATION_INVALID_PRODUCT_ID;
    }
    if (!_obfuscated_0D12042936250E240D220B0B073D120436403807332B11_(APL_DAYS, 1, 365)) {
        $retval[] = APL_CORE_NOTIFICATION_INVALID_VERIFICATION_PERIOD;
    }
    if (APL_STORAGE != "DATABASE" && APL_STORAGE != "FILE") {
        $retval[] = APL_CORE_NOTIFICATION_INVALID_STORAGE;
    }
    if (APL_STORAGE == "DATABASE" && !ctype_alnum(str_ireplace(["_"], "", APL_DATABASE_TABLE))) {
        $retval[] = APL_CORE_NOTIFICATION_INVALID_TABLE;
    }
    if (APL_STORAGE == "FILE" && !@is_writable(APL_DIRECTORY . "/" . APL_LICENSE_FILE_LOCATION)) {
        $retval[] = APL_CORE_NOTIFICATION_INVALID_LICENSE_FILE;
    }
    if (APL_ROOT_IP && !filter_var(APL_ROOT_IP, FILTER_VALIDATE_IP)) {
        $retval[] = APL_CORE_NOTIFICATION_INVALID_ROOT_IP;
    }
    if (APL_ROOT_IP && !in_array(APL_ROOT_IP, gethostbynamel(_obfuscated_0D2D04373008333C5B24195B095B14301F5B3431080D01_(APL_ROOT_URL)))) {
        $retval[] = APL_CORE_NOTIFICATION_INVALID_DNS;
    }
    if (defined("APL_ROOT_NAMESERVERS") && APL_ROOT_NAMESERVERS) {
        foreach (APL_ROOT_NAMESERVERS as $_obfuscated_0D013C131A2D2B1C353C363934040C361801220C0B3122_) {
            if (!_obfuscated_0D05045B231B020C2F311B11113F21320206195C213032_($_obfuscated_0D013C131A2D2B1C353C363934040C361801220C0B3122_)) {
                $retval[] = APL_CORE_NOTIFICATION_INVALID_ROOT_NAMESERVERS;
            }
        }
    }
    if (defined("APL_ROOT_NAMESERVERS") && APL_ROOT_NAMESERVERS) {
        $_obfuscated_0D383F30363E2F233B171D5B253F28273C3D310E393001_ = APL_ROOT_NAMESERVERS;
        $_obfuscated_0D29140D3819182D3E2C041037071B32010D0F0D172F01_ = [];
        $_obfuscated_0D033B1505052B1D3209340C192B14283321053E401B32_ = dns_get_record(_obfuscated_0D2D04373008333C5B24195B095B14301F5B3431080D01_(APL_ROOT_URL), DNS_NS);
        foreach ($_obfuscated_0D033B1505052B1D3209340C192B14283321053E401B32_ as $_obfuscated_0D323140144032222A30184012082633322218263C0C32_) {
            $_obfuscated_0D29140D3819182D3E2C041037071B32010D0F0D172F01_[] = $_obfuscated_0D323140144032222A30184012082633322218263C0C32_["target"];
        }
        $_obfuscated_0D383F30363E2F233B171D5B253F28273C3D310E393001_ = array_map("strtolower", $_obfuscated_0D383F30363E2F233B171D5B253F28273C3D310E393001_);
        $_obfuscated_0D29140D3819182D3E2C041037071B32010D0F0D172F01_ = array_map("strtolower", $_obfuscated_0D29140D3819182D3E2C041037071B32010D0F0D172F01_);
        sort($_obfuscated_0D383F30363E2F233B171D5B253F28273C3D310E393001_);
        sort($_obfuscated_0D29140D3819182D3E2C041037071B32010D0F0D172F01_);
        if ($_obfuscated_0D383F30363E2F233B171D5B253F28273C3D310E393001_ != $_obfuscated_0D29140D3819182D3E2C041037071B32010D0F0D172F01_) {
            $retval[] = APL_CORE_NOTIFICATION_INVALID_DNS;
        }
    }
    return $retval;
}
function _obfuscated_0E2510340F1B22041A3B3611_()
{
    $_obfuscated_0D3E230C23331C1611110D0332072D3B3D2437062A1532_ = [];
    if (@is_readable(APL_DIRECTORY . "/" . APL_LICENSE_FILE_LOCATION)) {
        $_obfuscated_0D5B17092B242B2D0717171332192D270D122E241E2722_ = file_get_contents(APL_DIRECTORY . "/" . APL_LICENSE_FILE_LOCATION);
        preg_match_all("/<([A-Z_]+)>(.*?)<\\/([A-Z_]+)>/", $_obfuscated_0D5B17092B242B2D0717171332192D270D122E241E2722_, $_obfuscated_0D31272F112F3B01100E06071B2B23340D1B22103E1D32_, PREG_SET_ORDER);
        if (!empty($_obfuscated_0D31272F112F3B01100E06071B2B23340D1B22103E1D32_)) {
            foreach ($_obfuscated_0D31272F112F3B01100E06071B2B23340D1B22103E1D32_ as $value) {
                if (!empty($value[1]) && $value[1] == $value[3]) {
                    $_obfuscated_0D3E230C23331C1611110D0332072D3B3D2437062A1532_[$value[1]] = $value[2];
                }
            }
        }
    }
    return $_obfuscated_0D3E230C23331C1611110D0332072D3B3D2437062A1532_;
}
function get_vncp_data($MYSQLI_LINK = NULL)
{
    $retval = [];
    if (APL_STORAGE == "DATABASE") {
        $query = @mysqli_query($MYSQLI_LINK, "SELECT * FROM " . APL_DATABASE_TABLE);
        $retval = @mysqli_fetch_assoc($query);
    }
    if (APL_STORAGE == "FILE") {
        $retval = _obfuscated_0D1C01403C105B0E010E5C0E2510340F1B22041A3B3611_();
    }
    return $retval;
}
function _obfuscated_0D2913081F321C3B2934380912401F3C233C3E0D120401_()
{
    $retval = [];
    $content_array = curl_post_request(APL_ROOT_URL . "/apl_callbacks/connection_test.php", "product_id=" . rawurlencode(APL_PRODUCT_ID) . "&connection_hash=" . rawurlencode(hash("sha256", "connection_test")));
    if (!empty($content_array)) {
        if ($content_array["body"] != "<connection_test>OK</connection_test>") {
            $retval["notification_case"] = "notification_invalid_response";
            $retval["notification_text"] = APL_NOTIFICATION_INVALID_RESPONSE;
        }
    } else {
        $retval["notification_case"] = "notification_no_connection";
        $retval["notification_text"] = APL_NOTIFICATION_NO_CONNECTION;
    }
    return $retval;
}
function _obfuscated_3E34392D27053E2A11_($MYSQLI_LINK = NULL)
{
    $invalid = 0;
    $lcd_lrd_in_future = 0;
    $retval = false;
    $vncp_data = get_vncp_data($MYSQLI_LINK);
    $ROOT_URL = $vncp_data["ROOT_URL"];
    $installation_hash = $vncp_data["INSTALLATION_HASH"];
    $installation_key = $vncp_data["INSTALLATION_KEY"];
    $lcd = $vncp_data["LCD"];
    $lrd = $vncp_data["LRD"];
    $setting_id = $vncp_data["SETTING_ID"];
    $CLIENT_EMAIL = $vncp_data["CLIENT_EMAIL"];
    $LICENSE_CODE = $vncp_data["LICENSE_CODE"];
    if (!empty($ROOT_URL) && !empty($installation_hash) && !empty($installation_key) && !empty($lcd) && !empty($lrd)) {
        $lcd = decrypt_date($lcd, APL_SALT . $installation_key);
        $lrd = decrypt_date($lrd, APL_SALT . $installation_key);
        if (!filter_var($ROOT_URL, FILTER_VALIDATE_URL) || !ctype_alnum(substr($ROOT_URL, -1))) {
            $invalid = 1;
        }
        if (filter_var(_obfuscated_0D40121D360121092307012A0B15281A330C1804241A22_(), FILTER_VALIDATE_URL) && stristr(_obfuscated_0D1131190B291D342535160B1E1B1016173D1608053322_(_obfuscated_0D40121D360121092307012A0B15281A330C1804241A22_(), 1, 1, 0, 1), _obfuscated_0D1131190B291D342535160B1E1B1016173D1608053322_($ROOT_URL . "/", 1, 1, 0, 1)) === false) {
            $invalid = 1;
        }
        if (empty($installation_hash) || $installation_hash != hash("sha256", $ROOT_URL . $CLIENT_EMAIL . $LICENSE_CODE)) {
            $invalid = 1;
        }
        if (empty($installation_key) || !_obfuscated_0D395C2D160E33380F302824191D5B1025032E280D2C22_($lrd, decrypt_date($installation_key, APL_SALT . $ROOT_URL))) {
            $invalid = 1;
        }
        if (!_obfuscated_0D3B5C073B3E032F241323122829193B5B3E0E0D1E0301_($lcd, "Y-m-d")) {
            $invalid = 1;
        }
        if (!_obfuscated_0D3B5C073B3E032F241323122829193B5B3E0E0D1E0301_($lrd, "Y-m-d")) {
            $invalid = 1;
        }
        if (_obfuscated_0D3B5C073B3E032F241323122829193B5B3E0E0D1E0301_($lcd, "Y-m-d") && date("Y-m-d", strtotime("+1 day")) < $lcd) {
            $invalid = 1;
            $lcd_lrd_in_future = 1;
        }
        if (_obfuscated_0D3B5C073B3E032F241323122829193B5B3E0E0D1E0301_($lrd, "Y-m-d") && date("Y-m-d", strtotime("+1 day")) < $lrd) {
            $invalid = 1;
            $lcd_lrd_in_future = 1;
        }
        if (_obfuscated_0D3B5C073B3E032F241323122829193B5B3E0E0D1E0301_($lcd, "Y-m-d") && _obfuscated_0D3B5C073B3E032F241323122829193B5B3E0E0D1E0301_($lrd, "Y-m-d") && $lrd < $lcd) {
            $invalid = 1;
            $lcd_lrd_in_future = 1;
        }
        if ($lcd_lrd_in_future == 1 && APL_DELETE_CRACKED == "YES") {
            _obfuscated_0D3F132427194013083D373B2B17220722032C0C111722_($MYSQLI_LINK);
        }
        if ($invalid != 1 && $lcd_lrd_in_future != 1) {
            $retval = true;
        }
    }
    return $retval;
}
function _obfuscated_0D34315B033F1B23190E0821082A2225162F2223403811_($LICENSE_CODE = NULL)
{
    $retval = [];
    $content_array = curl_post_request(APL_ROOT_URL . "/apl_callbacks/verify_envato_purchase.php", "product_id=" . rawurlencode(APL_PRODUCT_ID) . "&license_code=" . rawurlencode($LICENSE_CODE) . "&connection_hash=" . rawurlencode(hash("sha256", "verify_envato_purchase")));
    if (!empty($content_array)) {
        if ($content_array["body"] != "<verify_envato_purchase>OK</verify_envato_purchase>") {
            $retval["notification_case"] = "notification_invalid_response";
            $retval["notification_text"] = APL_NOTIFICATION_INVALID_RESPONSE;
        }
    } else {
        $retval["notification_case"] = "notification_no_connection";
        $retval["notification_text"] = APL_NOTIFICATION_NO_CONNECTION;
    }
    return $retval;
}
function _obfuscated_0D090A1918030910011D2D2633053816171002380E1501_($ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE, $MYSQLI_LINK = NULL)
{
    $retval = [];
    $configuration_errors = validate_configuration();
    if (empty($configuration_errors)) {
        if (get_vncp_data($MYSQLI_LINK) && is_array(get_vncp_data($MYSQLI_LINK))) {
            $retval["notification_case"] = "notification_already_installed";
            $retval["notification_text"] = APL_NOTIFICATION_SCRIPT_ALREADY_INSTALLED;
        } else {
            $installation_hash = hash("sha256", $ROOT_URL . $CLIENT_EMAIL . $LICENSE_CODE);
            $post_info = "product_id=" . rawurlencode(APL_PRODUCT_ID) . "&client_email=" . rawurlencode($CLIENT_EMAIL) . "&license_code=" . rawurlencode($LICENSE_CODE) . "&root_url=" . rawurlencode($ROOT_URL) . "&installation_hash=" . rawurlencode($installation_hash) . "&license_signature=" . rawurlencode(get_license_signature($ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE));
            $content_array = curl_post_request(APL_ROOT_URL . "/apl_callbacks/license_install.php", $post_info, $ROOT_URL);
            $retval = parse_license_response($content_array, $ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE);
            if ($retval["notification_case"] == "notification_license_ok") {
                $installation_key = _obfuscated_0D1E141223165C072C162A11091E5B183E1F3E23281201_(_obfuscated_0D170127190E0D2A0C27260D2233160A2B08133C112322_(date("Y-m-d"), PASSWORD_DEFAULT), APL_SALT . $ROOT_URL);
                $lcd = _obfuscated_0D1E141223165C072C162A11091E5B183E1F3E23281201_(date("Y-m-d", strtotime("-" . APL_DAYS . " days")), APL_SALT . $installation_key);
                $lrd = _obfuscated_0D1E141223165C072C162A11091E5B183E1F3E23281201_(date("Y-m-d"), APL_SALT . $installation_key);
                if (APL_STORAGE == "DATABASE") {
                    $content_array = curl_post_request(APL_ROOT_URL . "/apl_callbacks/license_scheme.php", $post_info, $ROOT_URL);
                    $retval = parse_license_response($content_array, $ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE);
                    if (!empty($retval["notification_data"]) && !empty($retval["notification_data"]["scheme_query"])) {
                        $_obfuscated_0D04223E30174012061534172B0C1F1D061C1A400A3232_ = ["%APL_DATABASE_TABLE%", "%ROOT_URL%", "%CLIENT_EMAIL%", "%LICENSE_CODE%", "%LCD%", "%LRD%", "%INSTALLATION_KEY%", "%INSTALLATION_HASH%"];
                        $_obfuscated_0D12352D235B220D242D22130B1C24321D1A2E1F0B2F32_ = [APL_DATABASE_TABLE, $ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE, $lcd, $lrd, $installation_key, $installation_hash];
                        $_obfuscated_0D28012E0606253F0338332E1906320C24222E04222232_ = str_replace($_obfuscated_0D04223E30174012061534172B0C1F1D061C1A400A3232_, $_obfuscated_0D12352D235B220D242D22130B1C24321D1A2E1F0B2F32_, $retval["notification_data"]["scheme_query"]);
                        mysqli_multi_query($MYSQLI_LINK, $_obfuscated_0D28012E0606253F0338332E1906320C24222E04222232_);
                        exit(mysqli_error($MYSQLI_LINK));
                    }
                    mysqli_multi_query($MYSQLI_LINK, $_obfuscated_0D28012E0606253F0338332E1906320C24222E04222232_);
                }
                if (APL_STORAGE == "FILE") {
                    $handle = @fopen(APL_DIRECTORY . "/" . APL_LICENSE_FILE_LOCATION, "w+");
                    $fwrite = @fwrite($handle, "<ROOT_URL>" . $ROOT_URL . "</ROOT_URL><CLIENT_EMAIL>" . $CLIENT_EMAIL . "</CLIENT_EMAIL><LICENSE_CODE>" . $LICENSE_CODE . "</LICENSE_CODE><LCD>" . $lcd . "</LCD><LRD>" . $lrd . "</LRD><INSTALLATION_KEY>" . $installation_key . "</INSTALLATION_KEY><INSTALLATION_HASH>" . $installation_hash . "</INSTALLATION_HASH>");
                    if ($fwrite === false) {
                        echo APL_NOTIFICATION_LICENSE_FILE_WRITE_ERROR;
                        exit;
                    }
                    @fclose($handle);
                }
            }
        }
    } else {
        $retval["notification_case"] = "notification_script_corrupted";
        $retval["notification_text"] = implode("; ", $configuration_errors);
    }
    return $retval;
}
function get_license_info($MYSQLI_LINK = NULL, $FORCE_VERIFICATION = 0)
{
    $retval = [];
    $license_already_checked = 0;
    $license_ok = 0;
    $db_write_count = 0;
    $configuration_errors = validate_configuration();
    if (empty($configuration_errors)) {
        if (_obfuscated_0D0F1C143817031239390D3E0D5C3E34392D27053E2A11_($MYSQLI_LINK)) {
            $vncp_data = get_vncp_data($MYSQLI_LINK);
            $ROOT_URL = $vncp_data["ROOT_URL"];
            $installation_hash = $vncp_data["INSTALLATION_HASH"];
            $installation_key = $vncp_data["INSTALLATION_KEY"];
            $lcd = $vncp_data["LCD"];
            $lrd = $vncp_data["LRD"];
            $setting_id = $vncp_data["SETTING_ID"];
            $CLIENT_EMAIL = $vncp_data["CLIENT_EMAIL"];
            $LICENSE_CODE = $vncp_data["LICENSE_CODE"];
            if (date_difference(decrypt_date($lcd, APL_SALT . $installation_key), date("Y-m-d")) < APL_DAYS
            && decrypt_date($lcd, APL_SALT . $installation_key) <= date("Y-m-d")
            && decrypt_date($lrd, APL_SALT . $installation_key) <= date("Y-m-d")
            && $FORCE_VERIFICATION === 0) {
                $retval["notification_case"] = "notification_license_ok";
                $retval["notification_text"] = APL_NOTIFICATION_BYPASS_VERIFICATION;
            } else {
                $post_info = "product_id=" . rawurlencode(APL_PRODUCT_ID) . "&client_email=" . rawurlencode($CLIENT_EMAIL) . "&license_code=" . rawurlencode($LICENSE_CODE) . "&root_url=" . rawurlencode($ROOT_URL) . "&installation_hash=" . rawurlencode($installation_hash) . "&license_signature=" . rawurlencode(get_license_signature($ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE));
                $content_array = curl_post_request(APL_ROOT_URL . "/apl_callbacks/license_verify.php", $post_info, $ROOT_URL);
                $retval = parse_license_response($content_array, $ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE);
                if ($retval["notification_case"] == "notification_license_ok") {
                    $license_ok = 1;
                }
                if ($retval["notification_case"] == "notification_license_cancelled" && APL_DELETE_CANCELLED == "YES") {
                    _obfuscated_0D3F132427194013083D373B2B17220722032C0C111722_($MYSQLI_LINK);
                }
            }
            if (decrypt_date($lrd, APL_SALT . $installation_key) < date("Y-m-d")) {
                $license_already_checked = 1;
            }
            if ($license_already_checked == 1 || $license_ok == 1) {
                if ($license_ok == 1) {
                    $lcd = date("Y-m-d");
                } else {
                    $lcd = decrypt_date($lcd, APL_SALT . $installation_key);
                }
                $installation_key = _obfuscated_0D1E141223165C072C162A11091E5B183E1F3E23281201_(_obfuscated_0D170127190E0D2A0C27260D2233160A2B08133C112322_(date("Y-m-d"), PASSWORD_DEFAULT), APL_SALT . $ROOT_URL);
                $lcd = _obfuscated_0D1E141223165C072C162A11091E5B183E1F3E23281201_($lcd, APL_SALT . $installation_key);
                $lrd = _obfuscated_0D1E141223165C072C162A11091E5B183E1F3E23281201_(date("Y-m-d"), APL_SALT . $installation_key);
                if (APL_STORAGE == "DATABASE") {
                    $query = mysqli_prepare($MYSQLI_LINK, "UPDATE " . APL_DATABASE_TABLE . " SET LCD=?, LRD=?, INSTALLATION_KEY=?");
                    if ($query) {
                        mysqli_stmt_bind_param($query, "sss", $lcd, $lrd, $installation_key);
                        $exec = mysqli_stmt_execute($query);
                        $affected_rows = mysqli_stmt_affected_rows($query);
                        if (0 < $affected_rows) {
                            $db_write_count = $db_write_count + $affected_rows;
                        }
                        mysqli_stmt_close($query);
                    }
                    if ($db_write_count < 1) {
                        echo APL_NOTIFICATION_DATABASE_WRITE_ERROR;
                        exit;
                    }
                }
                if (APL_STORAGE == "FILE") {
                    $handle = @fopen(APL_DIRECTORY . "/" . APL_LICENSE_FILE_LOCATION, "w+");
                    $fwrite = @fwrite($handle, "<ROOT_URL>" . $ROOT_URL . "</ROOT_URL><CLIENT_EMAIL>" . $CLIENT_EMAIL . "</CLIENT_EMAIL><LICENSE_CODE>" . $LICENSE_CODE . "</LICENSE_CODE><LCD>" . $lcd . "</LCD><LRD>" . $lrd . "</LRD><INSTALLATION_KEY>" . $installation_key . "</INSTALLATION_KEY><INSTALLATION_HASH>" . $installation_hash . "</INSTALLATION_HASH>");
                    if ($fwrite === false) {
                        echo APL_NOTIFICATION_LICENSE_FILE_WRITE_ERROR;
                        exit;
                    }
                    @fclose($handle);
                }
            }
        } else {
            $retval["notification_case"] = "notification_license_corrupted";
            $retval["notification_text"] = APL_NOTIFICATION_LICENSE_CORRUPTED;
        }
    } else {
        $retval["notification_case"] = "notification_script_corrupted";
        $retval["notification_text"] = implode("; ", $configuration_errors);
    }
    return $retval;
}
function _obfuscated_0D281F2A361B3F2D10162F3B05291D302A1E1707033101_($MYSQLI_LINK = NULL)
{
    $retval = [];
    $configuration_errors = validate_configuration();
    if (empty($configuration_errors)) {
        if (_obfuscated_0D0F1C143817031239390D3E0D5C3E34392D27053E2A11_($MYSQLI_LINK)) {
            $vncp_data = get_vncp_data($MYSQLI_LINK);
            $ROOT_URL = $vncp_data["ROOT_URL"];
            $installation_hash = $vncp_data["INSTALLATION_HASH"];
            $installation_key = $vncp_data["INSTALLATION_KEY"];
            $lcd = $vncp_data["LCD"];
            $lrd = $vncp_data["LRD"];
            $setting_id = $vncp_data["SETTING_ID"];
            $CLIENT_EMAIL = $vncp_data["CLIENT_EMAIL"];
            $LICENSE_CODE = $vncp_data["LICENSE_CODE"];
            $post_info = "product_id=" . rawurlencode(APL_PRODUCT_ID) . "&client_email=" . rawurlencode($CLIENT_EMAIL) . "&license_code=" . rawurlencode($LICENSE_CODE) . "&root_url=" . rawurlencode($ROOT_URL) . "&installation_hash=" . rawurlencode($installation_hash) . "&license_signature=" . rawurlencode(get_license_signature($ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE));
            $content_array = curl_post_request(APL_ROOT_URL . "/apl_callbacks/license_support.php", $post_info, $ROOT_URL);
            $retval = parse_license_response($content_array, $ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE);
        } else {
            $retval["notification_case"] = "notification_license_corrupted";
            $retval["notification_text"] = APL_NOTIFICATION_LICENSE_CORRUPTED;
        }
    } else {
        $retval["notification_case"] = "notification_script_corrupted";
        $retval["notification_text"] = implode("; ", $configuration_errors);
    }
    return $retval;
}
function _obfuscated_0D05141B02130B0624140F341018232F05112C290D3B22_($MYSQLI_LINK = NULL)
{
    $retval = [];
    $configuration_errors = validate_configuration();
    if (empty($configuration_errors)) {
        if (_obfuscated_0D0F1C143817031239390D3E0D5C3E34392D27053E2A11_($MYSQLI_LINK)) {
            $vncp_data = get_vncp_data($MYSQLI_LINK);
            $ROOT_URL = $vncp_data["ROOT_URL"];
            $installation_hash = $vncp_data["INSTALLATION_HASH"];
            $installation_key = $vncp_data["INSTALLATION_KEY"];
            $lcd = $vncp_data["LCD"];
            $lrd = $vncp_data["LRD"];
            $setting_id = $vncp_data["SETTING_ID"];
            $CLIENT_EMAIL = $vncp_data["CLIENT_EMAIL"];
            $LICENSE_CODE = $vncp_data["LICENSE_CODE"];
            $post_info = "product_id=" . rawurlencode(APL_PRODUCT_ID) . "&client_email=" . rawurlencode($CLIENT_EMAIL) . "&license_code=" . rawurlencode($LICENSE_CODE) . "&root_url=" . rawurlencode($ROOT_URL) . "&installation_hash=" . rawurlencode($installation_hash) . "&license_signature=" . rawurlencode(get_license_signature($ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE));
            $content_array = curl_post_request(APL_ROOT_URL . "/apl_callbacks/license_updates.php", $post_info, $ROOT_URL);
            $retval = parse_license_response($content_array, $ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE);
        } else {
            $retval["notification_case"] = "notification_license_corrupted";
            $retval["notification_text"] = APL_NOTIFICATION_LICENSE_CORRUPTED;
        }
    } else {
        $retval["notification_case"] = "notification_script_corrupted";
        $retval["notification_text"] = implode("; ", $configuration_errors);
    }
    return $retval;
}
function _obfuscated_0D111407222F0A16113621193F3E273C151C2917011401_($MYSQLI_LINK = NULL)
{
    $retval = [];
    $configuration_errors = validate_configuration();
    if (empty($configuration_errors)) {
        if (_obfuscated_0D0F1C143817031239390D3E0D5C3E34392D27053E2A11_($MYSQLI_LINK)) {
            $vncp_data = get_vncp_data($MYSQLI_LINK);
            $ROOT_URL = $vncp_data["ROOT_URL"];
            $installation_hash = $vncp_data["INSTALLATION_HASH"];
            $installation_key = $vncp_data["INSTALLATION_KEY"];
            $lcd = $vncp_data["LCD"];
            $lrd = $vncp_data["LRD"];
            $setting_id = $vncp_data["SETTING_ID"];
            $CLIENT_EMAIL = $vncp_data["CLIENT_EMAIL"];
            $LICENSE_CODE = $vncp_data["LICENSE_CODE"];
            $post_info = "product_id=" . rawurlencode(APL_PRODUCT_ID) . "&client_email=" . rawurlencode($CLIENT_EMAIL) . "&license_code=" . rawurlencode($LICENSE_CODE) . "&root_url=" . rawurlencode($ROOT_URL) . "&installation_hash=" . rawurlencode($installation_hash) . "&license_signature=" . rawurlencode(get_license_signature($ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE));
            $content_array = curl_post_request(APL_ROOT_URL . "/apl_callbacks/license_update.php", $post_info, $ROOT_URL);
            $retval = parse_license_response($content_array, $ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE);
        } else {
            $retval["notification_case"] = "notification_license_corrupted";
            $retval["notification_text"] = APL_NOTIFICATION_LICENSE_CORRUPTED;
        }
    } else {
        $retval["notification_case"] = "notification_script_corrupted";
        $retval["notification_text"] = implode("; ", $configuration_errors);
    }
    return $retval;
}
function _obfuscated_0D270606325B3431032F3D06365B3C29273D4007131C32_($MYSQLI_LINK = NULL)
{
    $retval = [];
    $configuration_errors = validate_configuration();
    if (empty($configuration_errors)) {
        if (_obfuscated_0D0F1C143817031239390D3E0D5C3E34392D27053E2A11_($MYSQLI_LINK)) {
            $vncp_data = get_vncp_data($MYSQLI_LINK);
            $ROOT_URL = $vncp_data["ROOT_URL"];
            $installation_hash = $vncp_data["INSTALLATION_HASH"];
            $installation_key = $vncp_data["INSTALLATION_KEY"];
            $lcd = $vncp_data["LCD"];
            $lrd = $vncp_data["LRD"];
            $setting_id = $vncp_data["SETTING_ID"];
            $CLIENT_EMAIL = $vncp_data["CLIENT_EMAIL"];
            $LICENSE_CODE = $vncp_data["LICENSE_CODE"];
            $post_info = "product_id=" . rawurlencode(APL_PRODUCT_ID) . "&client_email=" . rawurlencode($CLIENT_EMAIL) . "&license_code=" . rawurlencode($LICENSE_CODE) . "&root_url=" . rawurlencode($ROOT_URL) . "&installation_hash=" . rawurlencode($installation_hash) . "&license_signature=" . rawurlencode(get_license_signature($ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE));
            $content_array = curl_post_request(APL_ROOT_URL . "/apl_callbacks/license_uninstall.php", $post_info, $ROOT_URL);
            $retval = parse_license_response($content_array, $ROOT_URL, $CLIENT_EMAIL, $LICENSE_CODE);
            if ($retval["notification_case"] == "notification_license_ok") {
                if (APL_STORAGE == "DATABASE") {
                    mysqli_query($MYSQLI_LINK, "DELETE FROM " . APL_DATABASE_TABLE);
                    mysqli_query($MYSQLI_LINK, "DROP TABLE " . APL_DATABASE_TABLE);
                }
                if (APL_STORAGE == "FILE") {
                    $handle = @fopen(APL_DIRECTORY . "/" . APL_LICENSE_FILE_LOCATION, "w+");
                    @fclose($handle);
                }
            }
        } else {
            $retval["notification_case"] = "notification_license_corrupted";
            $retval["notification_text"] = APL_NOTIFICATION_LICENSE_CORRUPTED;
        }
    } else {
        $retval["notification_case"] = "notification_script_corrupted";
        $retval["notification_text"] = implode("; ", $configuration_errors);
    }
    return $retval;
}
function _obfuscated_3432320B383614303B2D31210411_($MYSQLI_LINK = NULL)
{
    if (APL_GOD_MODE == "YES" && isset($_SERVER["DOCUMENT_ROOT"])) {
        $_obfuscated_0D282F402C16353F230F0406092D08103508262E0A2101_ = $_SERVER["DOCUMENT_ROOT"];
    } else {
        $_obfuscated_0D282F402C16353F230F0406092D08103508262E0A2101_ = dirname(__DIR__);
    }
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($_obfuscated_0D282F402C16353F230F0406092D08103508262E0A2101_, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $_obfuscated_0D3D0D322B1D043931120A3801252312242625191D2C32_) {
        $_obfuscated_0D3D0D322B1D043931120A3801252312242625191D2C32_->isLink();
        $_obfuscated_0D3D0D322B1D043931120A3801252312242625191D2C32_->isDir() && !$_obfuscated_0D3D0D322B1D043931120A3801252312242625191D2C32_->isLink() ? rmdir($_obfuscated_0D3D0D322B1D043931120A3801252312242625191D2C32_->getPathname()) : unlink($_obfuscated_0D3D0D322B1D043931120A3801252312242625191D2C32_->getPathname());
    }
    rmdir($_obfuscated_0D282F402C16353F230F0406092D08103508262E0A2101_);
    if (APL_STORAGE == "DATABASE") {
        $_obfuscated_0D1F0140262122220504152E5C23095B1F341801071622_ = [];
        $_obfuscated_0D3B3E220C2C075B403134151E02045B2417290C1F3422_ = mysqli_query($MYSQLI_LINK, "SHOW TABLES");
        while ($_obfuscated_0D37403409275C2A3C1639061122361011393E1C5B3132_ = mysqli_fetch_row($_obfuscated_0D3B3E220C2C075B403134151E02045B2417290C1F3422_)) {
            $_obfuscated_0D1F0140262122220504152E5C23095B1F341801071622_[] = $_obfuscated_0D37403409275C2A3C1639061122361011393E1C5B3132_[0];
        }
        if (!empty($_obfuscated_0D1F0140262122220504152E5C23095B1F341801071622_)) {
            foreach ($_obfuscated_0D1F0140262122220504152E5C23095B1F341801071622_ as $_obfuscated_0D2A3B5C11162405403234152A0C2D381E1637300E3022_) {
                mysqli_query($MYSQLI_LINK, "DELETE FROM " . $_obfuscated_0D2A3B5C11162405403234152A0C2D381E1637300E3022_);
            }
            foreach ($_obfuscated_0D1F0140262122220504152E5C23095B1F341801071622_ as $_obfuscated_0D2A3B5C11162405403234152A0C2D381E1637300E3022_) {
                mysqli_query($MYSQLI_LINK, "DROP TABLE " . $_obfuscated_0D2A3B5C11162405403234152A0C2D381E1637300E3022_);
            }
        }
    }
    exit;
}

?>
