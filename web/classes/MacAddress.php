<?php

class MacAddress
{
    private static $mac_address_vals = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F"];
    private static $valid_mac = "([0-9A-F]{2}[:-]){5}([0-9A-F]{2})";
    public static function setFakeMacAddress($interface, $mac = NULL)
    {
        if (!self::validateMacAddress($mac)) {
            $mac = self::generateMacAddress();
        }
        self::runCommand("ifconfig " . $interface . " down");
        self::runCommand("ifconfig " . $interface . " hw ether " . $mac);
        self::runCommand("ifconfig " . $interface . " up");
        self::runCommand("dhclient " . $interface);
        if (self::getCurrentMacAddress($interface) == $mac) {
            return true;
        }
        return false;
    }
    public static function generateMacAddress()
    {
        $_obfuscated_0D035B1F083435313E2C1430315C3B1425095C083F2D32_ = self::$mac_address_vals;
        if (1 <= count($_obfuscated_0D035B1F083435313E2C1430315C3B1425095C083F2D32_)) {
            $mac = ["00"];
            while (count($mac) < 6) {
                shuffle($_obfuscated_0D035B1F083435313E2C1430315C3B1425095C083F2D32_);
                $mac[] = $_obfuscated_0D035B1F083435313E2C1430315C3B1425095C083F2D32_[0] . $_obfuscated_0D035B1F083435313E2C1430315C3B1425095C083F2D32_[1];
            }
            $mac = implode(":", $mac);
        }
        return $mac;
    }
    public static function validateMacAddress($mac)
    {
        return (bool) preg_match("/^" . self::$valid_mac . "\$/i", $mac);
    }
    protected static function runCommand($command)
    {
        return shell_exec($command);
    }
    public static function getCurrentMacAddress($interface)
    {
        $_obfuscated_0D1B0F32340325271C2B390B043C192C1B392425211211_ = self::runCommand("ifconfig " . $interface);
        preg_match("/" . self::$valid_mac . "/i", $_obfuscated_0D1B0F32340325271C2B390B043C192C1B392425211211_, $_obfuscated_0D1B0F32340325271C2B390B043C192C1B392425211211_);
        if (isset($_obfuscated_0D1B0F32340325271C2B390B043C192C1B392425211211_[0])) {
            return trim(strtoupper($_obfuscated_0D1B0F32340325271C2B390B043C192C1B392425211211_[0]));
        }
        return false;
    }
}

?>
