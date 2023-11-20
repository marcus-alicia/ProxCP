<?php

class User
{
    private $_db = NULL;
    private $_data = NULL;
    private $_sessionName = NULL;
    private $_isLoggedIn = NULL;
    private $_cookieName = NULL;
    public function __construct($user = NULL)
    {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get("session/session_name");
        $this->_cookieName = Config::get("remember/cookie_name");
        if (!$user) {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);
                if ($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                    $this->logout();
                }
            }
        } else {
            $this->find($user);
        }
    }
    public function update($fields = [], $id = NULL)
    {
        if (!$id && $this->isLoggedIn()) {
            $id = $this->data()->id;
        }
        if (!$this->_db->update("vncp_users", $id, $fields)) {
            throw new Exception("There was a problem updating.");
        }
    }
    public function create($fields = [])
    {
        if (!$this->_db->insert("vncp_users", $fields)) {
            throw new Exception("There was a problem creating an account.");
        }
    }
    public function find($user = NULL)
    {
        if ($user) {
            $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ = is_numeric($user) ? "id" : "username";
            $data = $this->_db->get("vncp_users", [$_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_, "=", $user]);
            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }
    public function login($username = NULL, $password = NULL, $remember = false)
    {
        if (!$username && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()->id);
        } else {
            $user = $this->find($username);
            if ($user && $this->data()->password === Hash::make($password, $this->data()->salt)) {
                Session::put($this->_sessionName, $this->data()->id);
                if ($remember) {
                    $hash = Hash::unique();
                    $_obfuscated_0D2A3932040612073B1D0C01042F342E173C0D1E3F0711_ = $this->_db->get("vncp_users_session", ["user_id", "=", $this->data()->id]);
                    if (!$_obfuscated_0D2A3932040612073B1D0C01042F342E173C0D1E3F0711_->count()) {
                        $this->_db->insert("vncp_users_session", ["user_id" => $this->data()->id, "hash" => $hash]);
                    } else {
                        $hash = $_obfuscated_0D2A3932040612073B1D0C01042F342E173C0D1E3F0711_->first()->hash;
                    }
                    Cookie::put($this->_cookieName, $hash, Config::get("remember/cookie_expiry"));
                }
                return true;
            }
        }
        return false;
    }
    public function admLogin($username = NULL)
    {
        if (!$username) {
            return false;
        }
        $user = $this->find($username);
        if ($user) {
            Session::put($this->_sessionName, $this->data()->id);
            return true;
        }
        return false;
    }
    public function loginHash($username = NULL, $password = NULL)
    {
        if (!$username && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()->id);
        } else {
            $user = $this->find($username);
            if ($user && $this->data()->password === $password) {
                Session::put($this->_sessionName, $this->data()->id);
                return true;
            }
        }
        return false;
    }
    public function hasPermission($key)
    {
        $group = $this->_db->get("vncp_groups", ["id", "=", $this->data()->group]);
        if ($group->count()) {
            $_obfuscated_0D3C080B070A2432281F270B1025143F0C23312C031022_ = json_decode($group->first()->permissions, true);
            if ($permissions[$key]) {
                return true;
            }
        }
        return false;
    }
    public function exists()
    {
        return !empty($this->_data) ? true : false;
    }
    public function logout()
    {
        $this->_db->delete("vncp_users_session", ["user_id", "=", $this->data()->id]);
        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }
    public function data()
    {
        return $this->_data;
    }
    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }
}

?>
