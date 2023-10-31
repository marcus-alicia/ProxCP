<?php

class PVE2_API
{
    protected $hostname = NULL;
    protected $username = NULL;
    protected $realm = NULL;
    protected $password = NULL;
    protected $port = NULL;
    protected $verify_ssl = NULL;
    protected $login_ticket = NULL;
    protected $login_ticket_timestamp = NULL;
    protected $cluster_node_list = NULL;
    public function __construct($hostname, $username, $realm, $password, $port = 8006, $verify_ssl = false)
    {
        if (empty($hostname) || empty($username) || empty($realm) || empty($password) || empty($port)) {
            throw new Exception("Hostname/Username/Realm/Password/Port required for PVE2_API object constructor.", 1);
        }
        if (gethostbyname($hostname) == $hostname && !filter_var($hostname, FILTER_VALIDATE_IP)) {
            throw new Exception("Cannot resolve " . $hostname . ".", 2);
        }
        if (!filter_var($port, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 65535]])) {
            throw new Exception("Port must be an integer between 1 and 65535.", 6);
        }
        if (!is_bool($verify_ssl)) {
            throw new Exception("verify_ssl must be boolean.", 7);
        }
        $this->hostname = $hostname;
        $this->username = $username;
        $this->realm = $realm;
        $this->password = $password;
        $this->port = $port;
        $this->verify_ssl = $verify_ssl;
    }
    public function login()
    {
        $_obfuscated_0D171F1802355C1B3E0E27105B383B2F1712041E400501_ = [];
        $_obfuscated_0D171F1802355C1B3E0E27105B383B2F1712041E400501_["username"] = $this->username;
        $_obfuscated_0D171F1802355C1B3E0E27105B383B2F1712041E400501_["password"] = $this->password;
        $_obfuscated_0D171F1802355C1B3E0E27105B383B2F1712041E400501_["realm"] = $this->realm;
        $_obfuscated_0D3C091A2D38315B13163804241B3930391B14253B2B22_ = http_build_query($_obfuscated_0D171F1802355C1B3E0E27105B383B2F1712041E400501_);
        unset($_obfuscated_0D171F1802355C1B3E0E27105B383B2F1712041E400501_);
        $_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_ = curl_init();
        curl_setopt($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_, CURLOPT_URL, "https://" . $this->hostname . ":" . $this->port . "/api2/json/access/ticket");
        curl_setopt($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_, CURLOPT_POST, true);
        curl_setopt($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_, CURLOPT_POSTFIELDS, $_obfuscated_0D3C091A2D38315B13163804241B3930391B14253B2B22_);
        curl_setopt($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);
        curl_setopt($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_, CURLOPT_CONNECTTIMEOUT, 10);
        $login_ticket = curl_exec($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_);
        $_obfuscated_0D173B31193326053D112F2C0F23013B04340739161522_ = curl_getinfo($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_);
        curl_close($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_);
        unset($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_);
        unset($_obfuscated_0D3C091A2D38315B13163804241B3930391B14253B2B22_);
        if (!$login_ticket) {
            $this->login_ticket_timestamp = NULL;
            return false;
        }
        $_obfuscated_0D03233113043C3E17013E241509112936051013092D01_ = json_decode($login_ticket, true);
        if ($_obfuscated_0D03233113043C3E17013E241509112936051013092D01_ == NULL || $_obfuscated_0D03233113043C3E17013E241509112936051013092D01_["data"] == NULL) {
            $this->login_ticket_timestamp = NULL;
            if ($_obfuscated_0D173B31193326053D112F2C0F23013B04340739161522_["ssl_verify_result"] == 1) {
                throw new Exception("Invalid SSL cert on " . $this->hostname . " - check that the hostname is correct, and that it appears in the server certificate's SAN list. Alternatively set the verify_ssl flag to false if you are using internal self-signed certs (ensure you are aware of the security risks before doing so).", 4);
            }
            return false;
        }
        $this->login_ticket = $_obfuscated_0D03233113043C3E17013E241509112936051013092D01_["data"];
        $this->login_ticket_timestamp = time();
        $this->reload_node_list();
        return true;
    }
    public function setCookie()
    {
        if (!$this->check_login_ticket()) {
            throw new Exception("Not logged into Proxmox host. No Login access ticket found or ticket expired.", 3);
        }
        setrawcookie("PVEAuthCookie", $this->login_ticket["ticket"], 0, "/");
    }
    protected function check_login_ticket()
    {
        if ($this->login_ticket == NULL) {
            $this->login_ticket_timestamp = NULL;
            return false;
        }
        if (time() + 7200 <= $this->login_ticket_timestamp) {
            $this->login_ticket = NULL;
            $this->login_ticket_timestamp = NULL;
            return false;
        }
        return true;
    }
    private function action($action_path, $http_method, $put_post_parameters = NULL)
    {
        if (substr($action_path, 0, 1) != "/") {
            $action_path = "/" . $action_path;
        }
        if (!$this->check_login_ticket()) {
            throw new Exception("Not logged into Proxmox host. No Login access ticket found or ticket expired.", 3);
        }
        $_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_ = curl_init();
        curl_setopt($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_, CURLOPT_URL, "https://" . $this->hostname . ":" . $this->port . "/api2/json" . $action_path);
        curl_setopt($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_, CURLOPT_CONNECTTIMEOUT, 10);
        $_obfuscated_0D3C2E28162E2F15230C0313113C1E0E130A131C250D32_ = [];
        $_obfuscated_0D3C2E28162E2F15230C0313113C1E0E130A131C250D32_[] = "CSRFPreventionToken: " . $this->login_ticket["CSRFPreventionToken"];
        switch ($http_method) {
            case "GET":
            case "PUT":
                curl_setopt($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_, CURLOPT_CUSTOMREQUEST, "PUT");
                $_obfuscated_0D3C170C35131D1231012E3406193B0D0C065B392B0E01_ = http_build_query($put_post_parameters);
                curl_setopt($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_, CURLOPT_POSTFIELDS, $_obfuscated_0D3C170C35131D1231012E3406193B0D0C065B392B0E01_);
                unset($_obfuscated_0D3C170C35131D1231012E3406193B0D0C065B392B0E01_);
                curl_setopt($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_, CURLOPT_HTTPHEADER, $_obfuscated_0D3C2E28162E2F15230C0313113C1E0E130A131C250D32_);
                break;
            case "POST":
                curl_setopt($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_, CURLOPT_POST, true);
                $_obfuscated_0D3C170C35131D1231012E3406193B0D0C065B392B0E01_ = http_build_query($put_post_parameters);
                curl_setopt($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_, CURLOPT_POSTFIELDS, $_obfuscated_0D3C170C35131D1231012E3406193B0D0C065B392B0E01_);
                unset($_obfuscated_0D3C170C35131D1231012E3406193B0D0C065B392B0E01_);
                curl_setopt($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_, CURLOPT_HTTPHEADER, $_obfuscated_0D3C2E28162E2F15230C0313113C1E0E130A131C250D32_);
                break;
            case "DELETE":
                curl_setopt($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_, CURLOPT_HTTPHEADER, $_obfuscated_0D3C2E28162E2F15230C0313113C1E0E130A131C250D32_);
                curl_setopt($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_, CURLOPT_HEADER, true);
                curl_setopt($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_, CURLOPT_COOKIE, "PVEAuthCookie=" . $this->login_ticket["ticket"]);
                curl_setopt($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_, CURLOPT_SSL_VERIFYPEER, false);
                $_obfuscated_0D3E2403333033020B402940033B173507252A40133432_ = curl_exec($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_);
                curl_close($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_);
                unset($_obfuscated_0D212E0C3E12190829225C0604361B080F3C011E140322_);
                $_obfuscated_0D091B3D0F2B04071D320C385C2E183332340F5B162732_ = explode("\r\n\r\n", $_obfuscated_0D3E2403333033020B402940033B173507252A40133432_, 2);
                list($_obfuscated_0D360F32133930243F385C3E28121B25225B2529182832_, $_obfuscated_0D372D062D1F5B051D151E3E285B35233B112430283201_) = $_obfuscated_0D091B3D0F2B04071D320C385C2E183332340F5B162732_;
                $_obfuscated_0D23250D2E1712101A170F3E2938092B35302C01050822_ = json_decode($_obfuscated_0D372D062D1F5B051D151E3E285B35233B112430283201_, true);
                $_obfuscated_0D161F5B33305B162F1E27312A0F2A152E3B5C01292201_ = var_export($_obfuscated_0D23250D2E1712101A170F3E2938092B35302C01050822_, true);
                unset($_obfuscated_0D3E2403333033020B402940033B173507252A40133432_);
                unset($_obfuscated_0D161F5B33305B162F1E27312A0F2A152E3B5C01292201_);
                $_obfuscated_0D3E041216320D33140D2C0D0526313B3F083D2F163722_ = explode("\r\n", $_obfuscated_0D360F32133930243F385C3E28121B25225B2529182832_);
                if (substr($_obfuscated_0D3E041216320D33140D2C0D0526313B3F083D2F163722_[0], 0, 9) == "HTTP/1.1 ") {
                    $_obfuscated_0D04083910351A2308320F051E350F0607100A13131422_ = explode(" ", $_obfuscated_0D3E041216320D33140D2C0D0526313B3F083D2F163722_[0]);
                    if ($_obfuscated_0D04083910351A2308320F051E350F0607100A13131422_[1] == "200") {
                        if ($http_method == "PUT") {
                            return true;
                        }
                        return $_obfuscated_0D23250D2E1712101A170F3E2938092B35302C01050822_["data"];
                    }
                    error_log("This API Request Failed.\n" . "HTTP Response - " . $_obfuscated_0D04083910351A2308320F051E350F0607100A13131422_[1] . "\n" . "HTTP Error - " . $_obfuscated_0D3E041216320D33140D2C0D0526313B3F083D2F163722_[0]);
                    return false;
                }
                error_log("Error - Invalid HTTP Response.\n" . var_export($_obfuscated_0D3E041216320D33140D2C0D0526313B3F083D2F163722_, true));
                return false;
                break;
            default:
                throw new Exception("Error - Invalid HTTP Method specified.", 5);
        }
    }
    public function reload_node_list()
    {
        $_obfuscated_0D0227020E390B061601112F5C050229385C13322F1932_ = $this->get("/nodes");
        if (0 < count($_obfuscated_0D0227020E390B061601112F5C050229385C13322F1932_)) {
            $_obfuscated_0D332D0435280F2916272F2329135B2B390F40012C2832_ = [];
            foreach ($_obfuscated_0D0227020E390B061601112F5C050229385C13322F1932_ as $_obfuscated_0D1C373B22220C04292D220D1A23360A0F361F1E042A01_) {
                $_obfuscated_0D332D0435280F2916272F2329135B2B390F40012C2832_[] = $_obfuscated_0D1C373B22220C04292D220D1A23360A0F361F1E042A01_["node"];
            }
            $this->cluster_node_list = $_obfuscated_0D332D0435280F2916272F2329135B2B390F40012C2832_;
            return true;
        } else {
            error_log(" Empty list of nodes returned in this cluster.");
            return false;
        }
    }
    public function get_node_list()
    {
        if ($this->cluster_node_list == NULL && $this->reload_node_list() === false) {
            return false;
        }
        return $this->cluster_node_list;
    }
    public function get_next_vmid()
    {
        $_obfuscated_0D3F35261B330812391A0303231934142D0B1A29311B22_ = $this->get("/cluster/nextid");
        if ($_obfuscated_0D3F35261B330812391A0303231934142D0B1A29311B22_ == NULL) {
            return false;
        }
        return $_obfuscated_0D3F35261B330812391A0303231934142D0B1A29311B22_;
    }
    public function get_version()
    {
        $_obfuscated_0D0F2E2A3E2C0B280F0B0E36330F291E16140F1A145B32_ = $this->get("/version");
        if ($_obfuscated_0D0F2E2A3E2C0B280F0B0E36330F291E16140F1A145B32_ == NULL) {
            return false;
        }
        return $_obfuscated_0D0F2E2A3E2C0B280F0B0E36330F291E16140F1A145B32_["version"];
    }
    public function get($action_path)
    {
        return $this->action($action_path, "GET");
    }
    public function put($action_path, $parameters)
    {
        return $this->action($action_path, "PUT", $parameters);
    }
    public function post($action_path, $parameters)
    {
        return $this->action($action_path, "POST", $parameters);
    }
    public function delete($action_path)
    {
        return $this->action($action_path, "DELETE");
    }
}

?>
