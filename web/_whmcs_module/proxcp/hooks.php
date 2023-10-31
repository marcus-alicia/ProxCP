<?php

add_hook("ClientAreaPrimarySidebar", 10, function (WHMCS\View\Menu\Item $primaryNavbar) {
    $url = "";
    $shostname = "";
    foreach (WHMCS\Database\Capsule::table("tblservers")->get() as $server) {
        if ($server->type == "proxcp") {
            $url = "https://" . $server->hostname;
            $shostname = $server->hostname;
            if (!is_null($primaryNavbar->getChild("Service Details Actions"))) {
                $command = "GetClientsProducts";
                $postData = ["limitnum" => 1, "serviceid" => $_GET["id"]];
                $results = localAPI($command, $postData, "");
                $loggedInID = $results["products"]["product"][0]["clientid"];
                $servicestatus = $results["products"]["product"][0]["status"];
                $servicemodhostname = $results["products"]["product"][0]["serverhostname"];
                $command = "GetClientsDetails";
                $postData = ["clientid" => $loggedInID];
                $results = localAPI($command, $postData, "");
                $loggedInEmail = $results["email"];
                if ($servicestatus == "Active" && $servicemodhostname == $shostname) {
                    $primaryNavbar->getChild("Service Details Actions")->addChild("proxcpLogin", ["label" => "Control Panel", "uri" => $url . "/login?u=" . urlencode(base64_encode($loggedInEmail)) . "&from=whmcs", "order" => "1"])->setAttribute("target", "_blank");
                }
            }
        }
    }
});

?>
