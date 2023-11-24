<?php
$LICENSE_CODE=$_SERVER['HTTP_LICENSE_CODE'];
$APL_PRODUCT_ID=$_SERVER['HTTP_APL_PRODUCT_ID'];
$CLIENT_EMAIL=$_SERVER['HTTP_CLIENT_EMAIL'];
$ROOT_URL=$_SERVER['HTTP_ROOT_URL'];
$iparray = gethostbynamel("license.proxcp.free-tools.club");

$server = hash("sha256", implode("", $iparray) . $APL_PRODUCT_ID . $LICENSE_CODE . $CLIENT_EMAIL . $ROOT_URL . gmdate("Y-m-d"));

$license_code = hash("sha256", gmdate("Y-m-d") . $ROOT_URL . $CLIENT_EMAIL . $LICENSE_CODE . $APL_PRODUCT_ID . implode("", $iparray));
header("notification_server_signature: $server");
header("notification_case: notification_license_ok");
header('notification_data: {"scheme_id":1,"scheme_query":"CREATE TABLE `%APL_DATABASE_TABLE%` (\r\n    `SETTING_ID` TINYINT(1) NOT NULL AUTO_INCREMENT,\r\n    `ROOT_URL` VARCHAR(250) NOT NULL,\r\n    `CLIENT_EMAIL` VARCHAR(250) NOT NULL,\r\n    `LICENSE_CODE` VARCHAR(250) NOT NULL,\r\n    `LCD` VARCHAR(250) NOT NULL,\r\n    `LRD` VARCHAR(250) NOT NULL,\r\n    `INSTALLATION_KEY` VARCHAR(250) NOT NULL,\r\n    `INSTALLATION_HASH` VARCHAR(250) NOT NULL,\r\n    PRIMARY KEY (`SETTING_ID`)\r\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\r\n\r\nINSERT INTO `%APL_DATABASE_TABLE%` (`SETTING_ID`, `ROOT_URL`, `CLIENT_EMAIL`, `LICENSE_CODE`, `LCD`, `LRD`, `INSTALLATION_KEY`, `INSTALLATION_HASH`) VALUES (\'1\', \'%ROOT_URL%\', \'%CLIENT_EMAIL%\', \'%LICENSE_CODE%\', \'%LCD%\', \'%LRD%\', \'%INSTALLATION_KEY%\', \'%INSTALLATION_HASH%\');","scheme_status":1}');
header("notification_text: Cracked by the Red Queen | discord.gg/free-tools");
echo("Your calculated license_signature should be: $license_code\nThe Server signature is: $server");
echo("\n\n");
echo('The specific data this endpoint returns:\n{"scheme_id":1,"scheme_query":"CREATE TABLE `%APL_DATABASE_TABLE%` (\r\n    `SETTING_ID` TINYINT(1) NOT NULL AUTO_INCREMENT,\r\n    `ROOT_URL` VARCHAR(250) NOT NULL,\r\n    `CLIENT_EMAIL` VARCHAR(250) NOT NULL,\r\n    `LICENSE_CODE` VARCHAR(250) NOT NULL,\r\n    `LCD` VARCHAR(250) NOT NULL,\r\n    `LRD` VARCHAR(250) NOT NULL,\r\n    `INSTALLATION_KEY` VARCHAR(250) NOT NULL,\r\n    `INSTALLATION_HASH` VARCHAR(250) NOT NULL,\r\n    PRIMARY KEY (`SETTING_ID`)\r\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\r\n\r\nINSERT INTO `%APL_DATABASE_TABLE%` (`SETTING_ID`, `ROOT_URL`, `CLIENT_EMAIL`, `LICENSE_CODE`, `LCD`, `LRD`, `INSTALLATION_KEY`, `INSTALLATION_HASH`) VALUES (\'1\', \'%ROOT_URL%\', \'%CLIENT_EMAIL%\', \'%LICENSE_CODE%\', \'%LCD%\', \'%LRD%\', \'%INSTALLATION_KEY%\', \'%INSTALLATION_HASH%\');","scheme_status":1}');
?>
