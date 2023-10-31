<?php

class Language
{
    private $_langCode = NULL;
    public function __construct($langCode)
    {
        $this->_langCode = $langCode;
    }
    public function load()
    {
        $_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_ = file_get_contents("lang/" . $this->_langCode . ".json");
        if ($_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_ === false) {
            return false;
        }
        $array = json_decode($_obfuscated_0D071D2B1914025B0C223B0808172F0B0F07290B2E1032_, true);
        if (!$array) {
            return false;
        }
        return $array;
    }
}

?>
