<?php

class DB
{
    private $_pdo = NULL;
    private $_query = NULL;
    private $_error = false;
    private $_results = NULL;
    private $_count = 0;
    private static $_instance = NULL;
    private function __construct()
    {
        try {
            $this->_pdo = new PDO(Config::get("database/type") . ":host=" . Config::get("database/host") . ";dbname=" . Config::get("database/db"), Config::get("database/username"), Config::get("database/password"));
            $this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }
    public function query($sql, $params = [])
    {
        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if (count($params)) {
                foreach ($params as $_obfuscated_0D2F053F110303181D19132D01034013182E0E1C0C3D11_) {
                    $this->_query->bindValue($x, $_obfuscated_0D2F053F110303181D19132D01034013182E0E1C0C3D11_);
                    $x++;
                }
            }
            if ($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }
        return $this;
    }
    public function action($action, $table, $where = [])
    {
        if (count($where) === 3) {
            $_obfuscated_0D2B1B240C3834352C2A0A370827091B082F345C151332_ = ["=", ">", "<", ">=", "<=", "!="];
            list($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_, $_obfuscated_0D1C2D1B251E232A3C175C1004142F0C323C5B0F091401_, $value) = $where;
            if (in_array($_obfuscated_0D1C2D1B251E232A3C175C1004142F0C323C5B0F091401_, $_obfuscated_0D2B1B240C3834352C2A0A370827091B082F345C151332_)) {
                $sql = $action . " FROM " . $table . " WHERE " . $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ . " " . $_obfuscated_0D1C2D1B251E232A3C175C1004142F0C323C5B0F091401_ . " ?";
                if (!$this->query($sql, [$value])->error()) {
                    return $this;
                }
            }
        }
        return false;
    }
    public function limit_action($action, $table, $where = [], $limit)
    {
        if (count($where) === 3) {
            $_obfuscated_0D2B1B240C3834352C2A0A370827091B082F345C151332_ = ["=", ">", "<", ">=", "<=", "!="];
            list($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_, $_obfuscated_0D1C2D1B251E232A3C175C1004142F0C323C5B0F091401_, $value) = $where;
            if (in_array($_obfuscated_0D1C2D1B251E232A3C175C1004142F0C323C5B0F091401_, $_obfuscated_0D2B1B240C3834352C2A0A370827091B082F345C151332_)) {
                $sql = $action . " FROM " . $table . " WHERE " . $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ . " " . $_obfuscated_0D1C2D1B251E232A3C175C1004142F0C323C5B0F091401_ . " ? LIMIT " . $limit;
                if (!$this->query($sql, [$value])->error()) {
                    return $this;
                }
            }
        }
        return false;
    }
    public function desc_limit_action($action, $table, $where = [], $limit)
    {
        if (count($where) === 3) {
            $_obfuscated_0D2B1B240C3834352C2A0A370827091B082F345C151332_ = ["=", ">", "<", ">=", "<=", "!="];
            list($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_, $_obfuscated_0D1C2D1B251E232A3C175C1004142F0C323C5B0F091401_, $value) = $where;
            if (in_array($_obfuscated_0D1C2D1B251E232A3C175C1004142F0C323C5B0F091401_, $_obfuscated_0D2B1B240C3834352C2A0A370827091B082F345C151332_)) {
                $sql = $action . " FROM " . $table . " WHERE " . $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ . " " . $_obfuscated_0D1C2D1B251E232A3C175C1004142F0C323C5B0F091401_ . " ? ORDER BY `date` DESC LIMIT " . $limit;
                if (!$this->query($sql, [$value])->error()) {
                    return $this;
                }
            }
        }
        return false;
    }
    public function asc_user_action($action, $table, $where = [])
    {
        if (count($where) === 3) {
            $_obfuscated_0D2B1B240C3834352C2A0A370827091B082F345C151332_ = ["=", ">", "<", ">=", "<=", "!="];
            list($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_, $_obfuscated_0D1C2D1B251E232A3C175C1004142F0C323C5B0F091401_, $value) = $where;
            if (in_array($_obfuscated_0D1C2D1B251E232A3C175C1004142F0C323C5B0F091401_, $_obfuscated_0D2B1B240C3834352C2A0A370827091B082F345C151332_)) {
                $sql = $action . " FROM " . $table . " WHERE " . $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ . " " . $_obfuscated_0D1C2D1B251E232A3C175C1004142F0C323C5B0F091401_ . " ? ORDER BY `username` ASC";
                if (!$this->query($sql, [$value])->error()) {
                    return $this;
                }
            }
        }
        return false;
    }
    public function get($table, $where)
    {
        return $this->action("SELECT *", $table, $where);
    }
    public function get_users_asc($table, $where)
    {
        return $this->asc_user_action("SELECT *", $table, $where);
    }
    public function get_unique_network($table, $where)
    {
        return $this->action("SELECT DISTINCT network", $table, $where);
    }
    public function limit_get($table, $where, $limit)
    {
        return $this->limit_action("SELECT *", $table, $where, $limit);
    }
    public function limit_get_desc($table, $where, $limit)
    {
        return $this->desc_limit_action("SELECT *", $table, $where, $limit);
    }
    public function delete($table, $where)
    {
        return $this->action("DELETE", $table, $where);
    }
    public function insert($table, $fields = [])
    {
        $_obfuscated_0D17330536323D311A261B0A242F1D3F31101E0C061422_ = array_keys($fields);
        $_obfuscated_0D2B1F5B371B1415133B0A3D3134102840392F01243D32_ = "";
        $x = 1;
        foreach ($fields as $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_) {
            $_obfuscated_0D2B1F5B371B1415133B0A3D3134102840392F01243D32_ .= "?";
            if ($x < count($fields)) {
                $_obfuscated_0D2B1F5B371B1415133B0A3D3134102840392F01243D32_ .= ", ";
            }
            $x++;
        }
        $sql = "INSERT INTO " . $table . " (`" . implode("`, `", $_obfuscated_0D17330536323D311A261B0A242F1D3F31101E0C061422_) . "`) VALUES (" . $_obfuscated_0D2B1F5B371B1415133B0A3D3134102840392F01243D32_ . ")";
        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }
    public function update($table, $id, $fields)
    {
        $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ = "";
        $x = 1;
        foreach ($fields as $_obfuscated_0D2C2712102A3C2F2B2C33322A053D342A5C342C082601_ => $value) {
            $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ .= $_obfuscated_0D2C2712102A3C2F2B2C33322A053D342A5C342C082601_ . " = ?";
            if ($x < count($fields)) {
                $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ .= ", ";
            }
            $x++;
        }
        $sql = "UPDATE " . $table . " SET " . $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ . " WHERE id = " . $id;
        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }
    public function update_iso($table, $id, $fields)
    {
        $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ = "";
        $x = 1;
        foreach ($fields as $_obfuscated_0D2C2712102A3C2F2B2C33322A053D342A5C342C082601_ => $value) {
            $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ .= $_obfuscated_0D2C2712102A3C2F2B2C33322A053D342A5C342C082601_ . " = ?";
            if ($x < count($fields)) {
                $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ .= ", ";
            }
            $x++;
        }
        $sql = "UPDATE " . $table . " SET " . $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ . " WHERE upload_key = '" . $id . "'";
        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }
    public function update_dhcp($table, $id, $fields)
    {
        $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ = "";
        $x = 1;
        foreach ($fields as $_obfuscated_0D2C2712102A3C2F2B2C33322A053D342A5C342C082601_ => $value) {
            $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ .= $_obfuscated_0D2C2712102A3C2F2B2C33322A053D342A5C342C082601_ . " = ?";
            if ($x < count($fields)) {
                $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ .= ", ";
            }
            $x++;
        }
        $sql = "UPDATE " . $table . " SET " . $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ . " WHERE ip = '" . $id . "'";
        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }
    public function updatevm($table, $id, $fields)
    {
        $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ = "";
        $x = 1;
        foreach ($fields as $_obfuscated_0D2C2712102A3C2F2B2C33322A053D342A5C342C082601_ => $value) {
            $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ .= $_obfuscated_0D2C2712102A3C2F2B2C33322A053D342A5C342C082601_ . " = ?";
            if ($x < count($fields)) {
                $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ .= ", ";
            }
            $x++;
        }
        $sql = "UPDATE " . $table . " SET " . $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ . " WHERE user_id = " . $id;
        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }
    public function updatevm_aid($table, $aid, $fields)
    {
        $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ = "";
        $x = 1;
        foreach ($fields as $_obfuscated_0D2C2712102A3C2F2B2C33322A053D342A5C342C082601_ => $value) {
            $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ .= $_obfuscated_0D2C2712102A3C2F2B2C33322A053D342A5C342C082601_ . " = ?";
            if ($x < count($fields)) {
                $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ .= ", ";
            }
            $x++;
        }
        $sql = "UPDATE " . $table . " SET " . $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ . " WHERE hb_account_id = " . $aid;
        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }
    public function update_address($table, $aid, $fields)
    {
        $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ = "";
        $x = 1;
        foreach ($fields as $_obfuscated_0D2C2712102A3C2F2B2C33322A053D342A5C342C082601_ => $value) {
            $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ .= $_obfuscated_0D2C2712102A3C2F2B2C33322A053D342A5C342C082601_ . " = ?";
            if ($x < count($fields)) {
                $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ .= ", ";
            }
            $x++;
        }
        $sql = "UPDATE " . $table . " SET " . $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ . " WHERE address = '" . $aid . "'";
        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }
    public function updatevm_clid($table, $aid, $fields)
    {
        $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ = "";
        $x = 1;
        foreach ($fields as $_obfuscated_0D2C2712102A3C2F2B2C33322A053D342A5C342C082601_ => $value) {
            $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ .= $_obfuscated_0D2C2712102A3C2F2B2C33322A053D342A5C342C082601_ . " = ?";
            if ($x < count($fields)) {
                $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ .= ", ";
            }
            $x++;
        }
        $sql = "UPDATE " . $table . " SET " . $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ . " WHERE cloud_account_id = " . $aid;
        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }
    public function updatevnc($table, $id, $fields)
    {
        $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ = "";
        $x = 1;
        foreach ($fields as $_obfuscated_0D2C2712102A3C2F2B2C33322A053D342A5C342C082601_ => $value) {
            $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ .= $_obfuscated_0D2C2712102A3C2F2B2C33322A053D342A5C342C082601_ . " = ?";
            if ($x < count($fields)) {
                $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ .= ", ";
            }
            $x++;
        }
        $sql = "UPDATE " . $table . " SET " . $_obfuscated_0D1A0833281613051F1E3B09071E1C5B12364012221611_ . " WHERE hb_account_id = " . $id;
        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }
    public function results()
    {
        return $this->_results;
    }
    public function first()
    {
        return $this->results()[0];
    }
    public function all()
    {
        return $this->results();
    }
    public function error()
    {
        return $this->_error;
    }
    public function count()
    {
        return $this->_count;
    }
}

?>
