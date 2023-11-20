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
        $_obfuscated_0D2E27142B14261701070306291035053E351C1D351701_ = [];
        $_obfuscated_0D2E27142B14261701070306291035053E351C1D351701_["username"] = $this->username;
        $_obfuscated_0D2E27142B14261701070306291035053E351C1D351701_["password"] = $this->password;
        $_obfuscated_0D2E27142B14261701070306291035053E351C1D351701_["realm"] = $this->realm;
        $_obfuscated_0D331F27143C3F5B021D1324060B1C053D0A151B080422_ = http_build_query($_obfuscated_0D2E27142B14261701070306291035053E351C1D351701_);
        unset($_obfuscated_0D2E27142B14261701070306291035053E351C1D351701_);
        $_obfuscated_0D233F1F3335311022282136035C213614163427101922_ = curl_init();
        curl_setopt($_obfuscated_0D233F1F3335311022282136035C213614163427101922_, CURLOPT_URL, "https://" . $this->hostname . ":" . $this->port . "/api2/json/access/ticket");
        curl_setopt($_obfuscated_0D233F1F3335311022282136035C213614163427101922_, CURLOPT_POST, true);
        curl_setopt($_obfuscated_0D233F1F3335311022282136035C213614163427101922_, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($_obfuscated_0D233F1F3335311022282136035C213614163427101922_, CURLOPT_POSTFIELDS, $_obfuscated_0D331F27143C3F5B021D1324060B1C053D0A151B080422_);
        curl_setopt($_obfuscated_0D233F1F3335311022282136035C213614163427101922_, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);
        curl_setopt($_obfuscated_0D233F1F3335311022282136035C213614163427101922_, CURLOPT_CONNECTTIMEOUT, 10);
        $login_ticket = curl_exec($_obfuscated_0D233F1F3335311022282136035C213614163427101922_);
        $_obfuscated_0D3740055C310608271F3F282E38272B3131242C2B0701_ = curl_getinfo($_obfuscated_0D233F1F3335311022282136035C213614163427101922_);
        curl_close($_obfuscated_0D233F1F3335311022282136035C213614163427101922_);
        unset($_obfuscated_0D233F1F3335311022282136035C213614163427101922_);
        unset($_obfuscated_0D331F27143C3F5B021D1324060B1C053D0A151B080422_);
        if (!$login_ticket) {
            $this->login_ticket_timestamp = NULL;
            return false;
        }
        $_obfuscated_0D2C3F34150540042B262D06081916271D151A2F020432_ = json_decode($login_ticket, true);
        if ($_obfuscated_0D2C3F34150540042B262D06081916271D151A2F020432_ == NULL || $_obfuscated_0D2C3F34150540042B262D06081916271D151A2F020432_["data"] == NULL) {
            $this->login_ticket_timestamp = NULL;
            if ($_obfuscated_0D3740055C310608271F3F282E38272B3131242C2B0701_["ssl_verify_result"] == 1) {
                throw new Exception("Invalid SSL cert on " . $this->hostname . " - check that the hostname is correct, and that it appears in the server certificate's SAN list. Alternatively set the verify_ssl flag to false if you are using internal self-signed certs (ensure you are aware of the security risks before doing so).", 4);
            }
            return false;
        }
        $this->login_ticket = $_obfuscated_0D2C3F34150540042B262D06081916271D151A2F020432_["data"];
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
        $_obfuscated_0D233F1F3335311022282136035C213614163427101922_ = curl_init();
        curl_setopt($_obfuscated_0D233F1F3335311022282136035C213614163427101922_, CURLOPT_URL, "https://" . $this->hostname . ":" . $this->port . "/api2/json" . $action_path);
        curl_setopt($_obfuscated_0D233F1F3335311022282136035C213614163427101922_, CURLOPT_CONNECTTIMEOUT, 10);
        $_obfuscated_0D041D231B291513343F221D38221D373B180D5B0D0E22_ = [];
        $_obfuscated_0D041D231B291513343F221D38221D373B180D5B0D0E22_[] = "CSRFPreventionToken: " . $this->login_ticket["CSRFPreventionToken"];
        switch ($http_method) {
            case "GET":
            case "PUT":
                curl_setopt($_obfuscated_0D233F1F3335311022282136035C213614163427101922_, CURLOPT_CUSTOMREQUEST, "PUT");
                $_obfuscated_0D133814292C01230A2A0D120F5B2D041733072C2D0B11_ = http_build_query($put_post_parameters);
                curl_setopt($_obfuscated_0D233F1F3335311022282136035C213614163427101922_, CURLOPT_POSTFIELDS, $_obfuscated_0D133814292C01230A2A0D120F5B2D041733072C2D0B11_);
                unset($_obfuscated_0D133814292C01230A2A0D120F5B2D041733072C2D0B11_);
                curl_setopt($_obfuscated_0D233F1F3335311022282136035C213614163427101922_, CURLOPT_HTTPHEADER, $_obfuscated_0D041D231B291513343F221D38221D373B180D5B0D0E22_);
                break;
            case "POST":
                curl_setopt($_obfuscated_0D233F1F3335311022282136035C213614163427101922_, CURLOPT_POST, true);
                $_obfuscated_0D133814292C01230A2A0D120F5B2D041733072C2D0B11_ = http_build_query($put_post_parameters);
                curl_setopt($_obfuscated_0D233F1F3335311022282136035C213614163427101922_, CURLOPT_POSTFIELDS, $_obfuscated_0D133814292C01230A2A0D120F5B2D041733072C2D0B11_);
                unset($_obfuscated_0D133814292C01230A2A0D120F5B2D041733072C2D0B11_);
                curl_setopt($_obfuscated_0D233F1F3335311022282136035C213614163427101922_, CURLOPT_HTTPHEADER, $_obfuscated_0D041D231B291513343F221D38221D373B180D5B0D0E22_);
                break;
            case "DELETE":
                curl_setopt($_obfuscated_0D233F1F3335311022282136035C213614163427101922_, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($_obfuscated_0D233F1F3335311022282136035C213614163427101922_, CURLOPT_HTTPHEADER, $_obfuscated_0D041D231B291513343F221D38221D373B180D5B0D0E22_);
                curl_setopt($_obfuscated_0D233F1F3335311022282136035C213614163427101922_, CURLOPT_HEADER, true);
                curl_setopt($_obfuscated_0D233F1F3335311022282136035C213614163427101922_, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($_obfuscated_0D233F1F3335311022282136035C213614163427101922_, CURLOPT_COOKIE, "PVEAuthCookie=" . $this->login_ticket["ticket"]);
                curl_setopt($_obfuscated_0D233F1F3335311022282136035C213614163427101922_, CURLOPT_SSL_VERIFYPEER, false);
                $_obfuscated_0D282E351A31312C221F0D0F091B142738283915222F32_ = curl_exec($_obfuscated_0D233F1F3335311022282136035C213614163427101922_);
                curl_close($_obfuscated_0D233F1F3335311022282136035C213614163427101922_);
                unset($_obfuscated_0D233F1F3335311022282136035C213614163427101922_);
                $_obfuscated_0D112727070A2C0A070337030B2D0E3C2F1135063F0511_ = explode("\r\n\r\n", $_obfuscated_0D282E351A31312C221F0D0F091B142738283915222F32_, 2);
                list($_obfuscated_0D041C2E1B403C25133322370C0B125C343E260C193C11_, $_obfuscated_0D1A32151F2E1404353C2A2D27373F2C171E37173B3722_) = $_obfuscated_0D112727070A2C0A070337030B2D0E3C2F1135063F0511_;
                $_obfuscated_0D110D1A2F3E1E343C1035131823171B305B1E0A131011_ = json_decode($_obfuscated_0D1A32151F2E1404353C2A2D27373F2C171E37173B3722_, true);
                $_obfuscated_0D3119382D2404053E3D2B3135352C231A030427302B01_ = var_export($_obfuscated_0D110D1A2F3E1E343C1035131823171B305B1E0A131011_, true);
                unset($_obfuscated_0D282E351A31312C221F0D0F091B142738283915222F32_);
                unset($_obfuscated_0D3119382D2404053E3D2B3135352C231A030427302B01_);
                $_obfuscated_0D020D1B0E1904252D370F0735280A3E085C3D11240832_ = explode("\r\n", $_obfuscated_0D041C2E1B403C25133322370C0B125C343E260C193C11_);
                if (substr($_obfuscated_0D020D1B0E1904252D370F0735280A3E085C3D11240832_[0], 0, 9) == "HTTP/1.1 ") {
                    $_obfuscated_0D19343C2D300D3E2631363E5B2622102D182830300E11_ = explode(" ", $_obfuscated_0D020D1B0E1904252D370F0735280A3E085C3D11240832_[0]);
                    if ($_obfuscated_0D19343C2D300D3E2631363E5B2622102D182830300E11_[1] == "200") {
                        if ($http_method == "PUT") {
                            return true;
                        }
                        return $_obfuscated_0D110D1A2F3E1E343C1035131823171B305B1E0A131011_["data"];
                    }
                    error_log("This API Request Failed.\n" . "HTTP Response - " . $_obfuscated_0D19343C2D300D3E2631363E5B2622102D182830300E11_[1] . "\n" . "HTTP Error - " . $_obfuscated_0D020D1B0E1904252D370F0735280A3E085C3D11240832_[0]);
                    return false;
                }
                error_log("Error - Invalid HTTP Response.\n" . var_export($_obfuscated_0D020D1B0E1904252D370F0735280A3E085C3D11240832_, true));
                return false;
                break;
            default:
                throw new Exception("Error - Invalid HTTP Method specified.", 5);
        }
    }
    public function reload_node_list()
    {
        $_obfuscated_0D2A3C153D0F08310924045C232C10091E2A190F062E11_ = $this->get("/nodes");
        if (0 < count($_obfuscated_0D2A3C153D0F08310924045C232C10091E2A190F062E11_)) {
            $_obfuscated_0D372633021F390107241F1D102905280E5B3D27191B01_ = [];
            foreach ($_obfuscated_0D2A3C153D0F08310924045C232C10091E2A190F062E11_ as $_obfuscated_0D181C28271F3C3C303F0A02382A1712250E2E2C242F01_) {
                $_obfuscated_0D372633021F390107241F1D102905280E5B3D27191B01_[] = $_obfuscated_0D181C28271F3C3C303F0A02382A1712250E2E2C242F01_["node"];
            }
            $this->cluster_node_list = $_obfuscated_0D372633021F390107241F1D102905280E5B3D27191B01_;
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
        $_obfuscated_0D0C35100322281F1635251C0D230B090625063E243E32_ = $this->get("/cluster/nextid");
        if ($_obfuscated_0D0C35100322281F1635251C0D230B090625063E243E32_ == NULL) {
            return false;
        }
        return $_obfuscated_0D0C35100322281F1635251C0D230B090625063E243E32_;
    }
    public function get_version()
    {
        $_obfuscated_0D2B17150B3919310734310F352627252A250D021C0801_ = $this->get("/version");
        if ($_obfuscated_0D2B17150B3919310734310F352627252A250D021C0801_ == NULL) {
            return false;
        }
        return $_obfuscated_0D2B17150B3919310734310F352627252A250D021C0801_["version"];
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
