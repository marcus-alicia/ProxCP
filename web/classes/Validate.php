<?php

class Validate
{
    private $_passed = false;
    private $_errors = [];
    private $_db = NULL;
    public function __construct()
    {
        $this->_db = DB::getInstance();
    }
    public function check($source, $items = [])
    {
        foreach ($items as $_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_ => $_obfuscated_0D27311B29292B1C050E3F3E120C2F3E1D2B0940212301_) {
            foreach ($_obfuscated_0D27311B29292B1C050E3F3E120C2F3E1D2B0940212301_ as $_obfuscated_0D340A40232B062A06151F154015110D400738162F1822_ => $_obfuscated_0D11091624022A281622062A21130821223D17333E3332_) {
                $value = trim($source[$_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_]);
                $_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_ = parse_input($_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_);
                if ($_obfuscated_0D340A40232B062A06151F154015110D400738162F1822_ === "required" && empty($value) && !is_numeric($value)) {
                    $this->addError($_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_ . " is required");
                } else {
                    if (!empty($value)) {
                        switch ($_obfuscated_0D340A40232B062A06151F154015110D400738162F1822_) {
                            case "min":
                                if (strlen($value) < $_obfuscated_0D11091624022A281622062A21130821223D17333E3332_) {
                                    $this->addError($_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_ . " must be a minimum of " . $_obfuscated_0D11091624022A281622062A21130821223D17333E3332_ . " characters");
                                }
                                break;
                            case "min-num":
                                if ($value < $_obfuscated_0D11091624022A281622062A21130821223D17333E3332_) {
                                    $this->addError($_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_ . " must be a minimum of " . $_obfuscated_0D11091624022A281622062A21130821223D17333E3332_);
                                }
                                break;
                            case "max":
                                if ($_obfuscated_0D11091624022A281622062A21130821223D17333E3332_ < strlen($value)) {
                                    $this->addError($_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_ . " must be a maximum of " . $_obfuscated_0D11091624022A281622062A21130821223D17333E3332_ . " characters");
                                }
                                break;
                            case "max-num":
                                if ($_obfuscated_0D11091624022A281622062A21130821223D17333E3332_ < $value) {
                                    $this->addError($_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_ . " must be a maximum of " . $_obfuscated_0D11091624022A281622062A21130821223D17333E3332_);
                                }
                                break;
                            case "matches":
                                if ($value != $source[$_obfuscated_0D11091624022A281622062A21130821223D17333E3332_]) {
                                    $this->addError($_obfuscated_0D11091624022A281622062A21130821223D17333E3332_ . " must match " . $_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_);
                                }
                                break;
                            case "unique":
                                $check = $this->_db->get("vncp_users", [$_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_, "=", $value]);
                                if ($check->count()) {
                                    $this->addError($_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_ . " already exists.");
                                }
                                break;
                            case "unique_domain":
                                $check = $this->_db->get("vncp_forward_dns_domain", [$_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_, "=", $value]);
                                if ($check->count()) {
                                    $this->addError($_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_ . " already exists.");
                                }
                                break;
                            case "unique_hostname":
                                $check = $this->_db->get("vncp_nodes", [$_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_, "=", $value]);
                                if ($check->count()) {
                                    $this->addError($_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_ . " already exists.");
                                }
                                break;
                            case "unique_node":
                                $check = $this->_db->get("vncp_tuntap", ["node", "=", $value]);
                                if ($check->count()) {
                                    $this->addError("Node already exists.");
                                }
                                break;
                            case "unique_nat":
                                $check = $this->_db->get("vncp_nat", ["node", "=", $value]);
                                if ($check->count()) {
                                    $this->addError("Node already exists.");
                                }
                                break;
                            case "unique_hbid":
                                $check = $this->_db->get("vncp_lxc_ct", [$_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_, "=", $value]);
                                $check = $check->all();
                                $_obfuscated_0D292330223F0E2E120509390E182E1115110A1C040501_ = $this->_db->get("vncp_kvm_ct", [$_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_, "=", $value]);
                                $_obfuscated_0D292330223F0E2E120509390E182E1115110A1C040501_ = $_obfuscated_0D292330223F0E2E120509390E182E1115110A1C040501_->all();
                                $_obfuscated_0D10350F08111F0109232D25130B3533240E3F113E0711_ = $this->_db->get("vncp_kvm_cloud", [$_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_, "=", $value]);
                                $_obfuscated_0D10350F08111F0109232D25130B3533240E3F113E0711_ = $_obfuscated_0D10350F08111F0109232D25130B3533240E3F113E0711_->all();
                                if (1 <= count($check) || 1 <= count($_obfuscated_0D292330223F0E2E120509390E182E1115110A1C040501_) || 1 <= count($_obfuscated_0D10350F08111F0109232D25130B3533240E3F113E0711_)) {
                                    $this->addError($_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_ . " already exists.");
                                }
                                break;
                            case "unique_poolid":
                                $check = $this->_db->get("vncp_lxc_ct", ["pool_id", "=", $value]);
                                $check = $check->all();
                                $_obfuscated_0D292330223F0E2E120509390E182E1115110A1C040501_ = $this->_db->get("vncp_kvm_ct", ["pool_id", "=", $value]);
                                $_obfuscated_0D292330223F0E2E120509390E182E1115110A1C040501_ = $_obfuscated_0D292330223F0E2E120509390E182E1115110A1C040501_->all();
                                $_obfuscated_0D10350F08111F0109232D25130B3533240E3F113E0711_ = $this->_db->get("vncp_kvm_cloud", ["pool_id", "=", $value]);
                                $_obfuscated_0D10350F08111F0109232D25130B3533240E3F113E0711_ = $_obfuscated_0D10350F08111F0109232D25130B3533240E3F113E0711_->all();
                                if (1 <= count($check) || 1 <= count($_obfuscated_0D292330223F0E2E120509390E182E1115110A1C040501_) || 1 <= count($_obfuscated_0D10350F08111F0109232D25130B3533240E3F113E0711_)) {
                                    $this->addError($_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_ . " already exists.");
                                }
                                break;
                            case "valemail":
                                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                    $this->addError($_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_ . " must be a valid email address");
                                }
                                break;
                            case "numonly":
                                if (!is_numeric($value)) {
                                    $this->addError($_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_ . " is not a valid number. Please use numbers only.");
                                }
                                break;
                            case "ip":
                                if (!filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                                    $this->addError($_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_ . " is not a valid IP address");
                                }
                                break;
                            case "ip6":
                                if (!filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                                    $this->addError($_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_ . " is not a valid IPv6 address");
                                }
                                break;
                            case "strbool":
                                if ($value != "true" && $value != "false") {
                                    $this->addError($_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_ . " must be true or false.");
                                }
                                break;
                            case "macaddr":
                                if (!preg_match("/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}\$/", $value)) {
                                    $this->addError($_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_ . " must be in 00:00:00:00:00:00 MAC address format and use only 0-9, A-F.");
                                }
                                break;
                            case "cidrformat":
                                if (strpos($value, "/") === false) {
                                    $this->addError($_obfuscated_0D5C2A0E13031B1A16213F3C172B313B2C1B3714193711_ . " must be in CIDR format.");
                                }
                                break;
                        }
                    }
                }
            }
        }
        if (empty($this->_errors)) {
            $this->_passed = true;
        }
        return $this;
    }
    private function addError($error)
    {
        $this->_errors[] = $error;
    }
    public function errors()
    {
        return $this->_errors;
    }
    public function passed()
    {
        return $this->_passed;
    }
}

?>
