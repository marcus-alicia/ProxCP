<?php

require_once "vendor/autoload.php";
require_once "core/autoload.php";
require_once "core/init.php";
require_once "core/session.php";
$server = new TusPhp\Tus\Server("file");
$server->setMaxUploadSize(0);
$server->event()->addListener("tus-server.upload.complete", function (TusPhp\Events\TusEvent $event) {
    $db = DB::getInstance();
    $log = new Logger();
    $fileMeta = $event->getFile()->details();
    $request = $event->getRequest();
    $response = $event->getResponse();
    $file_path = $fileMeta["file_path"];
    $extension = substr($file_path, -4);
    $finfo = _obfuscated_0D1B281F1204280F1B2F1E3211233F352C24172D273811_(FILEINFO_MIME_TYPE);
    $magic = _obfuscated_0D27243F5C1931193526221A32271C122D070B5C083932_($finfo, $file_path);
    _obfuscated_0D1B3B085C153D36121D09192D263D292E022613210F11_($finfo);
    list($uploadKey) = explode("/", $fileMeta["location"]);
    $downloadKey = $uploadKey;
    if ($extension != ".iso" || $magic != "application/octet-stream" && $magic != "application/x-iso9660-image") {
        unlink($file_path);
        $db->delete("vncp_kvm_isos_custom", ["upload_key", "=", $uploadKey]);
        $log->log("isodel " . substr($uploadKey, 0, 32) . ": not an ISO", "general", 1, "system", "127.0.0.1");
    } else {
        $db->update_iso("vncp_kvm_isos_custom", (string) $uploadKey, ["status" => "uploaded", "download_key" => $downloadKey]);
        $log->log("isoup " . substr($uploadKey, 0, 32) . " completed", "general", 0, "system", "127.0.0.1");
        $nodes = $db->get("vncp_nodes", ["id", "!=", 0])->first();
        $creds = $db->get("vncp_tuntap", ["node", "=", $nodes->name])->first();
        $isos = $db->get("vncp_kvm_isos", ["id", "!=", 0])->first();
        if (0 < count($creds) && 0 < count($nodes) && 0 < count($isos)) {
            $db->update_iso("vncp_kvm_isos_custom", (string) $uploadKey, ["status" => "copying"]);
            $noLogin = false;
            $pxAPI = new PVE2_API($nodes->hostname, $nodes->username, $nodes->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($nodes->password));
            if (!$pxAPI->login()) {
                $noLogin = true;
            }
            if ($noLogin) {
                $db->update_iso("vncp_kvm_isos_custom", (string) $uploadKey, ["status" => "uploaded"]);
                $log->log("isocp fail " . substr($uploadKey, 0, 32) . ": no pxlogin " . $nodes->name . ".", "error", 1, "system", "127.0.0.1");
            } else {
                list($storage_location) = explode(":", $isos->volid);
                $pxpath = $pxAPI->get("/storage/" . $storage_location);
                $pxpath = $pxpath["path"] . "/template/iso";
                $ssh = new phpseclib\Net\SSH2($nodes->hostname, (int) $creds->port);
                if (!$ssh->login("root", _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($creds->password))) {
                    $db->update_iso("vncp_kvm_isos_custom", (string) $uploadKey, ["status" => "uploaded"]);
                    $log->log("isocp fail " . substr($uploadKey, 0, 32) . ": no sshlogin " . $nodes->name . ".", "error", 1, "system", "127.0.0.1");
                } else {
                    $ssh->exec("wget -bqc -O " . $pxpath . "/" . $uploadKey . ".iso " . Config::get("instance/base") . "/files/" . $downloadKey . "/get");
                    $ssh->disconnect();
                    unlink($file_path);
                    unlink("vendor/ankitpokhrel/tus-php/.cache/tus_php.client.cache");
                    unlink("vendor/ankitpokhrel/tus-php/.cache/tus_php.server.cache");
                    $db->update_iso("vncp_kvm_isos_custom", (string) $uploadKey, ["status" => "active"]);
                    $log->log("ISO " . substr($uploadKey, 0, 32) . " copied and active.", "general", 0, "system", "127.0.0.1");
                }
            }
        } else {
            $log->log("isocp fail " . substr($uploadKey, 0, 32) . "; no SSH " . $nodes->name . ".", "error", 1, "system", "127.0.0.1");
        }
    }
});
$response = $server->serve();
$response->send();
exit(0);

?>
