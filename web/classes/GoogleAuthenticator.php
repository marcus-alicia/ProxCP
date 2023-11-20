<?php

class vncp_GoogleAuthenticator
{
    protected $_codeLength = 6;
    public function createSecret($secretLength = 16)
    {
        $_obfuscated_0D351E3D15271D1D0B37032B01383B2D1D2E2701313B01_ = $this->_getBase32LookupTable();
        if ($secretLength < 16 || 128 < $secretLength) {
            throw new Exception("Bad secret length");
        }
        $_obfuscated_0D25113C2A3B310C2E2728153D123D391C351818102811_ = "";
        $_obfuscated_0D1B230B31062C17193202081D272D052304073B5B3822_ = false;
        if (function_exists("random_bytes")) {
            $_obfuscated_0D1B230B31062C17193202081D272D052304073B5B3822_ = _obfuscated_0D041627010E361D363515060E073C3F1516120F022D11_($secretLength);
        } else {
            if (function_exists("mcrypt_create_iv")) {
                $_obfuscated_0D1B230B31062C17193202081D272D052304073B5B3822_ = mcrypt_create_iv($secretLength, MCRYPT_DEV_URANDOM);
            } else {
                if (function_exists("openssl_random_pseudo_bytes")) {
                    $_obfuscated_0D1B230B31062C17193202081D272D052304073B5B3822_ = _obfuscated_0D3B292E23311A40030E233B250F2A3924391916151E11_($secretLength, $_obfuscated_0D34071501291B375B30225C2B103C2A053F220A353111_);
                    if (!$_obfuscated_0D34071501291B375B30225C2B103C2A053F220A353111_) {
                        $_obfuscated_0D1B230B31062C17193202081D272D052304073B5B3822_ = false;
                    }
                }
            }
        }
        if ($_obfuscated_0D1B230B31062C17193202081D272D052304073B5B3822_ !== false) {
            for ($i = 0; $i < $secretLength; $i++) {
                $_obfuscated_0D25113C2A3B310C2E2728153D123D391C351818102811_ .= $_obfuscated_0D351E3D15271D1D0B37032B01383B2D1D2E2701313B01_[ord($_obfuscated_0D1B230B31062C17193202081D272D052304073B5B3822_[$i]) & 31];
            }
            return $_obfuscated_0D25113C2A3B310C2E2728153D123D391C351818102811_;
        }
        throw new Exception("No source of secure random");
    }
    public function getCode($secret, $timeSlice = NULL)
    {
        if ($timeSlice === NULL) {
            $timeSlice = floor(time() / 30);
        }
        $_obfuscated_0D342C5B1B5B3F0D5C5B052A2D0E2D101B212904331522_ = $this->_base32Decode($secret);
        $time = chr(0) . chr(0) . chr(0) . chr(0) . pack("N*", $timeSlice);
        $_obfuscated_0D03353C1E120C322F041E1334043E0D0C230F3D093801_ = hash_hmac("SHA1", $time, $_obfuscated_0D342C5B1B5B3F0D5C5B052A2D0E2D101B212904331522_, true);
        $_obfuscated_0D3702112E163B1B4004402514181311070118132E0201_ = ord(substr($_obfuscated_0D03353C1E120C322F041E1334043E0D0C230F3D093801_, -1)) & 15;
        $_obfuscated_0D08303B112E2D1424053E1F1005263B01393736083801_ = substr($_obfuscated_0D03353C1E120C322F041E1334043E0D0C230F3D093801_, $_obfuscated_0D3702112E163B1B4004402514181311070118132E0201_, 4);
        $value = unpack("N", $_obfuscated_0D08303B112E2D1424053E1F1005263B01393736083801_);
        $value = $value[1];
        $value = $value & 2147483647;
        $_obfuscated_0D2D3F3822171F2A393E083405373B1A1527330F291111_ = pow(10, $this->_codeLength);
        return str_pad($value % $_obfuscated_0D2D3F3822171F2A393E083405373B1A1527330F291111_, $this->_codeLength, "0", STR_PAD_LEFT);
    }
    public function getQRCodeGoogleUrl($name, $secret, $title = NULL, $params = [])
    {
        $_obfuscated_0D351227230F2E33100D122804032C2A160B230B3F2B01_ = !empty($params["width"]) && 0 < (int) $params["width"] ? (int) $params["width"] : 200;
        $_obfuscated_0D280F27352B1C3C5C130E342840285C34212C08103201_ = !empty($params["height"]) && 0 < (int) $params["height"] ? (int) $params["height"] : 200;
        $_obfuscated_0D23275C162C0C1B401E40103F071E3433302F333C0701_ = !empty($params["level"]) && array_search($params["level"], ["L", "M", "Q", "H"]) !== false ? $params["level"] : "M";
        $_obfuscated_0D2D112406182B2E142C010B012A2E2236150C351F2A32_ = urlencode("otpauth://totp/" . $name . "?secret=" . $secret . "");
        if (isset($title)) {
            $_obfuscated_0D2D112406182B2E142C010B012A2E2236150C351F2A32_ .= urlencode("&issuer=" . urlencode($title));
        }
        return "https://chart.googleapis.com/chart?chs=" . $_obfuscated_0D351227230F2E33100D122804032C2A160B230B3F2B01_ . "x" . $_obfuscated_0D280F27352B1C3C5C130E342840285C34212C08103201_ . "&chld=" . $_obfuscated_0D23275C162C0C1B401E40103F071E3433302F333C0701_ . "|0&cht=qr&chl=" . $_obfuscated_0D2D112406182B2E142C010B012A2E2236150C351F2A32_ . "";
    }
    public function verifyCode($secret, $code, $discrepancy = 1, $currentTimeSlice = NULL)
    {
        if ($currentTimeSlice === NULL) {
            $currentTimeSlice = floor(time() / 30);
        }
        if (strlen($code) != 6) {
            return false;
        }
        for ($i = -1 * $discrepancy; $i <= $discrepancy; $i++) {
            $_obfuscated_0D1D1B06250D1315361E072721262D26240438402B2911_ = $this->getCode($secret, $currentTimeSlice + $i);
            if ($this->timingSafeEquals($_obfuscated_0D1D1B06250D1315361E072721262D26240438402B2911_, $code)) {
                return true;
            }
        }
        return false;
    }
    public function setCodeLength($length)
    {
        $this->_codeLength = $length;
        return $this;
    }
    protected function _base32Decode($secret)
    {
        if (empty($secret)) {
            return "";
        }
        $_obfuscated_0D0D070E0F343D2A021A23182B0E25123D22163B5B0A01_ = $this->_getBase32LookupTable();
        $_obfuscated_0D0C1126283603343933280E132A1F05352D0D2F1E2A01_ = array_flip($_obfuscated_0D0D070E0F343D2A021A23182B0E25123D22163B5B0A01_);
        $_obfuscated_0D0703152E12221C171A322A14063C041337250A233C11_ = substr_count($secret, $_obfuscated_0D0D070E0F343D2A021A23182B0E25123D22163B5B0A01_[32]);
        $_obfuscated_0D0A3F5C3D0F12281D09063510323F2D5C05382C3B1622_ = [6, 4, 3, 1, 0];
        if (!in_array($_obfuscated_0D0703152E12221C171A322A14063C041337250A233C11_, $_obfuscated_0D0A3F5C3D0F12281D09063510323F2D5C05382C3B1622_)) {
            return false;
        }
        for ($i = 0; $i < 4; $i++) {
            if ($_obfuscated_0D0703152E12221C171A322A14063C041337250A233C11_ == $_obfuscated_0D0A3F5C3D0F12281D09063510323F2D5C05382C3B1622_[$i] && substr($secret, -1 * $_obfuscated_0D0A3F5C3D0F12281D09063510323F2D5C05382C3B1622_[$i]) != str_repeat($_obfuscated_0D0D070E0F343D2A021A23182B0E25123D22163B5B0A01_[32], $_obfuscated_0D0A3F5C3D0F12281D09063510323F2D5C05382C3B1622_[$i])) {
                return false;
            }
        }
        $secret = str_replace("=", "", $secret);
        $secret = str_split($secret);
        $_obfuscated_0D2B05395B221A095C02102B0B3816072E0E142F1D3532_ = "";
        $i = 0;
        while ($i < count($secret)) {
            $x = "";
            if (!in_array($secret[$i], $_obfuscated_0D0D070E0F343D2A021A23182B0E25123D22163B5B0A01_)) {
                return false;
            }
            for ($j = 0; $j < 8; $j++) {
                $x .= str_pad(base_convert($_obfuscated_0D0C1126283603343933280E132A1F05352D0D2F1E2A01_[$secret[$i + $j]], 10, 2), 5, "0", STR_PAD_LEFT);
            }
            $_obfuscated_0D2A0E293F2F0D2D240D14393232243B1E283B04221222_ = str_split($x, 8);
            for ($z = 0; $z < count($_obfuscated_0D2A0E293F2F0D2D240D14393232243B1E283B04221222_); $z++) {
                $_obfuscated_0D2B05395B221A095C02102B0B3816072E0E142F1D3532_ .= ($y = chr(base_convert($_obfuscated_0D2A0E293F2F0D2D240D14393232243B1E283B04221222_[$z], 2, 10))) || ord($y) == 48 ? $y : "";
            }
            $i = $i + 8;
        }
        return $_obfuscated_0D2B05395B221A095C02102B0B3816072E0E142F1D3532_;
    }
    protected function _getBase32LookupTable()
    {
        return ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "2", "3", "4", "5", "6", "7", "="];
    }
    private function timingSafeEquals($safeString, $userString)
    {
        if (function_exists("hash_equals")) {
            return _obfuscated_0D341F2D3213092E3D3023060C3F05361F1B380B0C2F22_($safeString, $userString);
        }
        $_obfuscated_0D0229051605021935041F140638331A1D160339382E01_ = strlen($safeString);
        $_obfuscated_0D0715163E1D1A06281C042F3E405C2F170F2913400622_ = strlen($userString);
        if ($_obfuscated_0D0715163E1D1A06281C042F3E405C2F170F2913400622_ != $_obfuscated_0D0229051605021935041F140638331A1D160339382E01_) {
            return false;
        }
        $result = 0;
        for ($i = 0; $i < $_obfuscated_0D0715163E1D1A06281C042F3E405C2F170F2913400622_; $i++) {
            $result |= ord($safeString[$i]) ^ ord($userString[$i]);
        }
        return $result === 0;
    }
}

?>
