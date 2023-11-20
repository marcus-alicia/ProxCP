<?php

class Logger
{
    private $_db = NULL;
    public function __construct()
    {
        $this->_db = DB::getInstance();
    }
    public function log($msg = "", $type = "general", $severity = 0, $user = "", $ip = "")
    {
        if ($type == "admin") {
            $_obfuscated_0D083D232B2E05350A0932161D392F3C33162C11163532_ = ["msg" => $msg, "severity" => 0, "date" => date("Y-m-d H:i:s"), "username" => $user, "ipaddress" => $ip];
            if (!$this->_db->insert("vncp_log_admin", $_obfuscated_0D083D232B2E05350A0932161D392F3C33162C11163532_)) {
                throw new Exception("There was a problem with the logger.");
            }
        } else {
            if ($type == "error") {
                $_obfuscated_0D083D232B2E05350A0932161D392F3C33162C11163532_ = ["msg" => $msg, "severity" => $severity, "date" => date("Y-m-d H:i:s"), "username" => $user, "ipaddress" => $ip];
                if (!$this->_db->insert("vncp_log_error", $_obfuscated_0D083D232B2E05350A0932161D392F3C33162C11163532_)) {
                    throw new Exception("There was a problem with the logger.");
                }
            } else {
                if ($type == "general") {
                    $_obfuscated_0D083D232B2E05350A0932161D392F3C33162C11163532_ = ["msg" => $msg, "severity" => 0, "date" => date("Y-m-d H:i:s"), "username" => $user, "ipaddress" => $ip];
                    if (!$this->_db->insert("vncp_log_general", $_obfuscated_0D083D232B2E05350A0932161D392F3C33162C11163532_)) {
                        throw new Exception("There was a problem with the logger.");
                    }
                } else {
                    throw new Exception("Invalid logging type (admin, error, general).");
                }
            }
        }
    }
    public function get($type = "general")
    {
        return $this->_db->get("vncp_log_" . $type, ["id", "!=", 0])->all();
    }
    public function purge($type = "general", $date = "3000-12-31")
    {
        if ($type == "admin") {
            if (!$this->_db->delete("vncp_log_admin", ["date", "<", $date])) {
                throw new Exception("There was a problem with the logger.");
            }
        } else {
            if ($type == "general") {
                if (!$this->_db->delete("vncp_log_general", ["date", "<", $date])) {
                    throw new Exception("There was a problem with the logger.");
                }
            } else {
                if ($type == "error") {
                    if (!$this->_db->delete("vncp_log_error", ["date", "<", $date])) {
                        throw new Exception("There was a problem with the logger.");
                    }
                } else {
                    throw new Exception("Invalid logging type (admin, error, general).");
                }
            }
        }
    }
}

?>
