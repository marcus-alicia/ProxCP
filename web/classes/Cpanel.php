<?php

class Cpanel
{
    private $_host = NULL;
    private $_username = NULL;
    private $_password = NULL;
    private $_url = NULL;
    public function __construct($host, $username, $password)
    {
        $this->_host = $host;
        $this->_username = $username;
        $this->_password = $password;
        $this->_url = $host . "/json-api/";
    }
    public function addzonerecord($zone, $name, $type, $ptrdname)
    {
        $query = $this->_url . "addzonerecord?api.version=1";
        $_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_ = curl_init();
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_RETURNTRANSFER, 1);
        $header[0] = "Authorization: whm " . $this->_username . ":" . $this->_password;
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_HTTPHEADER, $header);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_URL, $query);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_POST, 1);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_POSTFIELDS, http_build_query(["zone" => $zone, "name" => $name, "type" => $type, "ptrdname" => $ptrdname]));
        $result = curl_exec($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_);
        $_obfuscated_0D291801250C3B3029041234083F33092D261A3F231E32_ = curl_getinfo($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLINFO_HTTP_CODE);
        if ($_obfuscated_0D291801250C3B3029041234083F33092D261A3F231E32_ != 200) {
            return false;
        }
        $_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_ = json_decode($result);
        return $_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_;
    }
    public function dumpzone($domain)
    {
        $query = $this->_url . "dumpzone?api.version=1";
        $_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_ = curl_init();
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_RETURNTRANSFER, 1);
        $header[0] = "Authorization: whm " . $this->_username . ":" . $this->_password;
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_HTTPHEADER, $header);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_URL, $query);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_POST, 1);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_POSTFIELDS, http_build_query(["domain" => $domain]));
        $result = curl_exec($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_);
        $_obfuscated_0D291801250C3B3029041234083F33092D261A3F231E32_ = curl_getinfo($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLINFO_HTTP_CODE);
        if ($_obfuscated_0D291801250C3B3029041234083F33092D261A3F231E32_ != 200) {
            return false;
        }
        $_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_ = json_decode($result);
        return $_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_;
    }
    public function removezonerecord($zone, $line)
    {
        $query = $this->_url . "removezonerecord?api.version=1";
        $_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_ = curl_init();
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_RETURNTRANSFER, 1);
        $header[0] = "Authorization: whm " . $this->_username . ":" . $this->_password;
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_HTTPHEADER, $header);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_URL, $query);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_POST, 1);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_POSTFIELDS, http_build_query(["zone" => $zone, "line" => (int) $line]));
        $result = curl_exec($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_);
        $_obfuscated_0D291801250C3B3029041234083F33092D261A3F231E32_ = curl_getinfo($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLINFO_HTTP_CODE);
        if ($_obfuscated_0D291801250C3B3029041234083F33092D261A3F231E32_ != 200) {
            return false;
        }
        $_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_ = json_decode($result);
        return $_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_;
    }
    public function adddns($domain, $ip)
    {
        $query = $this->_url . "adddns?api.version=1";
        $_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_ = curl_init();
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_RETURNTRANSFER, 1);
        $header[0] = "Authorization: whm " . $this->_username . ":" . $this->_password;
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_HTTPHEADER, $header);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_URL, $query);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_POST, 1);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_POSTFIELDS, http_build_query(["domain" => $domain, "ip" => $ip]));
        $result = curl_exec($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_);
        $_obfuscated_0D291801250C3B3029041234083F33092D261A3F231E32_ = curl_getinfo($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLINFO_HTTP_CODE);
        if ($_obfuscated_0D291801250C3B3029041234083F33092D261A3F231E32_ != 200) {
            return false;
        }
        $_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_ = json_decode($result);
        return $_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_;
    }
    public function killdns($domain)
    {
        $query = $this->_url . "killdns?api.version=1";
        $_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_ = curl_init();
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_RETURNTRANSFER, 1);
        $header[0] = "Authorization: whm " . $this->_username . ":" . $this->_password;
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_HTTPHEADER, $header);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_URL, $query);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_POST, 1);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_POSTFIELDS, http_build_query(["domain" => $domain]));
        $result = curl_exec($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_);
        $_obfuscated_0D291801250C3B3029041234083F33092D261A3F231E32_ = curl_getinfo($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLINFO_HTTP_CODE);
        if ($_obfuscated_0D291801250C3B3029041234083F33092D261A3F231E32_ != 200) {
            return false;
        }
        $_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_ = json_decode($result);
        return $_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_;
    }
    public function addzonerecord_A($domain, $name, $class, $ttl, $type, $address)
    {
        $query = $this->_url . "addzonerecord?api.version=1";
        $_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_ = curl_init();
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_RETURNTRANSFER, 1);
        $header[0] = "Authorization: whm " . $this->_username . ":" . $this->_password;
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_HTTPHEADER, $header);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_URL, $query);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_POST, 1);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_POSTFIELDS, http_build_query(["domain" => $domain, "name" => $name, "class" => $class, "ttl" => $ttl, "type" => $type, "address" => $address]));
        $result = curl_exec($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_);
        $_obfuscated_0D291801250C3B3029041234083F33092D261A3F231E32_ = curl_getinfo($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLINFO_HTTP_CODE);
        if ($_obfuscated_0D291801250C3B3029041234083F33092D261A3F231E32_ != 200) {
            return false;
        }
        $_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_ = json_decode($result);
        return $_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_;
    }
    public function addzonerecord_CNAME($domain, $name, $class, $ttl, $type, $cname, $flatten)
    {
        $query = $this->_url . "addzonerecord?api.version=1";
        $_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_ = curl_init();
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_RETURNTRANSFER, 1);
        $header[0] = "Authorization: whm " . $this->_username . ":" . $this->_password;
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_HTTPHEADER, $header);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_URL, $query);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_POST, 1);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_POSTFIELDS, http_build_query(["domain" => $domain, "name" => $name, "class" => $class, "ttl" => $ttl, "type" => $type, "cname" => $cname, "flatten" => $flatten]));
        $result = curl_exec($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_);
        $_obfuscated_0D291801250C3B3029041234083F33092D261A3F231E32_ = curl_getinfo($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLINFO_HTTP_CODE);
        if ($_obfuscated_0D291801250C3B3029041234083F33092D261A3F231E32_ != 200) {
            return false;
        }
        $_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_ = json_decode($result);
        return $_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_;
    }
    public function addzonerecord_SRV($domain, $name, $class, $ttl, $type, $priority, $weight, $port, $target)
    {
        $query = $this->_url . "addzonerecord?api.version=1";
        $_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_ = curl_init();
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_RETURNTRANSFER, 1);
        $header[0] = "Authorization: whm " . $this->_username . ":" . $this->_password;
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_HTTPHEADER, $header);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_URL, $query);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_POST, 1);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_POSTFIELDS, http_build_query(["domain" => $domain, "name" => $name, "class" => $class, "ttl" => $ttl, "type" => $type, "priority" => $priority, "weight" => $weight, "port" => $port, "target" => $target]));
        $result = curl_exec($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_);
        $_obfuscated_0D291801250C3B3029041234083F33092D261A3F231E32_ = curl_getinfo($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLINFO_HTTP_CODE);
        if ($_obfuscated_0D291801250C3B3029041234083F33092D261A3F231E32_ != 200) {
            return false;
        }
        $_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_ = json_decode($result);
        return $_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_;
    }
    public function addzonerecord_MX($domain, $name, $class, $ttl, $type, $preference, $exchange)
    {
        $query = $this->_url . "addzonerecord?api.version=1";
        $_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_ = curl_init();
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_RETURNTRANSFER, 1);
        $header[0] = "Authorization: whm " . $this->_username . ":" . $this->_password;
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_HTTPHEADER, $header);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_URL, $query);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_POST, 1);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_POSTFIELDS, http_build_query(["domain" => $domain, "name" => $name, "class" => $class, "ttl" => $ttl, "type" => $type, "preference" => $preference, "exchange" => $exchange]));
        $result = curl_exec($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_);
        $_obfuscated_0D291801250C3B3029041234083F33092D261A3F231E32_ = curl_getinfo($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLINFO_HTTP_CODE);
        if ($_obfuscated_0D291801250C3B3029041234083F33092D261A3F231E32_ != 200) {
            return false;
        }
        $_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_ = json_decode($result);
        return $_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_;
    }
    public function addzonerecord_TXT($domain, $name, $class, $ttl, $type, $txtdata, $unencoded)
    {
        $query = $this->_url . "addzonerecord?api.version=1";
        $_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_ = curl_init();
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_RETURNTRANSFER, 1);
        $header[0] = "Authorization: whm " . $this->_username . ":" . $this->_password;
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_HTTPHEADER, $header);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_URL, $query);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_POST, 1);
        curl_setopt($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLOPT_POSTFIELDS, http_build_query(["domain" => $domain, "name" => $name, "class" => $class, "ttl" => $ttl, "type" => $type, "txtdata" => $txtdata, "unencoded" => $unencoded]));
        $result = curl_exec($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_);
        $_obfuscated_0D291801250C3B3029041234083F33092D261A3F231E32_ = curl_getinfo($_obfuscated_0D043C1124020C40161B0712250726253D2A27401E1401_, CURLINFO_HTTP_CODE);
        if ($_obfuscated_0D291801250C3B3029041234083F33092D261A3F231E32_ != 200) {
            return false;
        }
        $_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_ = json_decode($result);
        return $_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_;
    }
}

?>
