<?php

define("APL_SALT", $GLOBALS["config"]["instance"]["l_salt"]);
define("APL_ROOT_URL", "https://licensing.proxcp.com");
define("APL_PRODUCT_ID", 4);
define("APL_DAYS", 7);
define("APL_STORAGE", "DATABASE");
define("APL_DATABASE_TABLE", "vncp_data");
define("APL_LICENSE_FILE_LOCATION", "a");
define("APL_NOTIFICATION_NO_CONNECTION", "Can't connect to licensing server.");
define("APL_NOTIFICATION_INVALID_RESPONSE", "Invalid server response.");
define("APL_NOTIFICATION_DATABASE_WRITE_ERROR", "Can't write to database.");
define("APL_NOTIFICATION_LICENSE_FILE_WRITE_ERROR", "Can't write to license file.");
define("APL_NOTIFICATION_SCRIPT_ALREADY_INSTALLED", "Script is already installed (or database not empty).");
define("APL_NOTIFICATION_LICENSE_CORRUPTED", "License is not installed yet or corrupted.");
define("APL_NOTIFICATION_BYPASS_VERIFICATION", "No need to verify");
define("PROXCP_APL_KEY", $GLOBALS["config"]["instance"]["l_salt"]);
define("APL_ROOT_IP", "68.183.130.42");
define("APL_DELETE_CANCELLED", "");
define("APL_DELETE_CRACKED", "");
define("APL_GOD_MODE", "");
define("APL_CORE_NOTIFICATION_INVALID_SALT", "Configuration error: invalid or default encryption salt");
define("APL_CORE_NOTIFICATION_INVALID_ROOT_URL", "Configuration error: invalid root URL of LM installation");
define("APL_CORE_NOTIFICATION_INVALID_PRODUCT_ID", "Configuration error: invalid product ID");
define("APL_CORE_NOTIFICATION_INVALID_VERIFICATION_PERIOD", "Configuration error: invalid license verification period");
define("APL_CORE_NOTIFICATION_INVALID_STORAGE", "Configuration error: invalid license storage option");
define("APL_CORE_NOTIFICATION_INVALID_TABLE", "Configuration error: invalid MySQL table name to store license signature");
define("APL_CORE_NOTIFICATION_INVALID_LICENSE_FILE", "Configuration error: invalid license file location (or file not writable)");
define("APL_CORE_NOTIFICATION_INVALID_ROOT_IP", "Configuration error: invalid IP address of your LM installation");
define("APL_CORE_NOTIFICATION_INVALID_ROOT_NAMESERVERS", "Configuration error: invalid nameservers of your LM installation");
define("APL_CORE_NOTIFICATION_INVALID_DNS", "License error: actual IP address and/or nameservers of your LM installation don't match specified IP address and/or nameservers");
define("APL_DIRECTORY", __DIR__);

?>
