<?php
parse_str(urldecode($_SERVER['BODY']), $BODY);
$LICENSE_CODE=$BODY['license_code'];
$APL_PRODUCT_ID=$BODY['product_id'];
$CLIENT_EMAIL=$BODY['client_url'];
$ROOT_URL=$BODY['root_url'];
$iparray = gethostbynamel("license.proxcp.free-tools.club");

$server = hash("sha256", implode("", $iparray) . $APL_PRODUCT_ID . $LICENSE_CODE . $CLIENT_EMAIL . $ROOT_URL . gmdate("Y-m-d"));

$license_code = hash("sha256", gmdate("Y-m-d") . $ROOT_URL . $CLIENT_EMAIL . $LICENSE_CODE . $APL_PRODUCT_ID . implode("", $iparray));
header("notification_server_signature: $server");
header("notification_case: notification_license_ok");
header("notification_data: null");
header("notification_text: Cracked by the Red Queen | discord.gg/free-tools");
echo("Your calculated license_signature should be: $license_code\nThe Server signature is: $server\n\n\nDebug:\n");

echo(var_dump($BODY));
?>
