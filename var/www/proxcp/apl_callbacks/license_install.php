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
header("notification_data: null");
header("notification_text: Cracked by the Red Queen | discord.gg/free-tools");
echo("Your calculated license_signature should be: $license_code\nThe Server signature is: $server")
?>
