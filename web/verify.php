<?php

require_once "vendor/autoload.php";
require_once "core/autoload.php";
require_once "core/init.php";
require_once "core/session.php";
$client = new TusPhp\Tus\Client(Config::get("instance/base"));
$db = DB::getInstance();
$log = new Logger();
$validate = new Validate();
$validation = $validate->check($_POST, ["useriso_who" => ["required" => true, "numonly" => true], "useriso_fname" => ["required" => true, "max" => 50, "min" => 3], "useriso_type" => ["required" => true, "max" => 7, "min" => 5]]);
if (!empty($_FILES) && $validation->passed() && $_POST["useriso_type"] != "default") {
    $status = "new";
    $fileMeta = $_FILES["tus_file"];
    $uploadKey = hash_file("md5", $fileMeta["tmp_name"]);
    $uploadKey = md5($_POST["useriso_who"] . $_POST["useriso_fname"]) . $uploadKey;
    try {
        $offset = $client->setKey($uploadKey)->file($fileMeta["tmp_name"])->getOffset();
        if (false !== $offset) {
            $status = $fileMeta["size"] <= $offset ? "uploaded" : "resume";
        } else {
            $offset = 0;
        }
        if ($status == "new") {
            $today = new DateTime();
            $db->insert("vncp_kvm_isos_custom", ["user_id" => (int) $_POST["useriso_who"], "fname" => $_POST["useriso_fname"], "type" => $_POST["useriso_type"], "rname" => $fileMeta["name"], "upload_date" => $today->format("Y-m-d 00:00:00"), "status" => "created", "upload_key" => $uploadKey, "download_key" => ""]);
            $log->log("User " . $_POST["useriso_who"] . " created new ISO upload " . $uploadKey, "general", 0, "system", "127.0.0.1");
        }
        echo json_encode(["status" => $status, "bytes_uploaded" => $offset, "upload_key" => $uploadKey]);
    } catch (GuzzleHttp\Exception\ConnectException $e) {
        echo json_encode(["status" => "error", "bytes_uploaded" => -1]);
    } catch (TusPhp\Exception\FileException $e) {
        echo json_encode(["status" => "resume", "bytes_uploaded" => 0, "upload_key" => ""]);
    }
} else {
    echo json_encode(["status" => "error", "bytes_uploaded" => -1]);
}

?>
