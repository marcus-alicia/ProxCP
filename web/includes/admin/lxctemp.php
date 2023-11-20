<?php

if (count(get_included_files()) == 1) {
    exit("You just broke everything.");
}
require_once "menu_main.php";
echo "<div class=\"row\">\r\n    <div class=\"col-md-12\">\r\n        <div class=\"panel panel-default\">\r\n            <div class=\"panel-body\">\r\n              <div class=\"clearfix\"><a class=\"btn btn-info btn-sm pull-right\" href=\"https://docs.proxcp.com/index.php?title=ProxCP_Admin_-_Manage_LXC_Templates\" role=\"button\" target=\"_blank\"><i class=\"fa fa-book\"></i> Related Documentation</a></div>\r\n                <h2 align=\"center\">Manage LXC Templates</h2><br />\r\n                ";
if ($lxcTempSuccess) {
    echo "<div id=\"adm_message\"><div class=\"alert alert-success\" role=\"alert\"><strong>Success:</strong> template added to database! Make sure the template is in proxmox too.</div></div>";
} else {
    if ($lxcImportSuccess && 0 < $lxcImportCount) {
        echo "<div id=\"adm_message\"><div class=\"alert alert-success\" role=\"alert\"><strong>Success:</strong> imported " . (string) $lxcImportCount . " LXC templates!</div></div>";
    } else {
        echo "<div id=\"adm_message\"></div>";
    }
}
echo "                ";
$node_results = $db->get("vncp_nodes", ["id", "!=", 0]);
if (0 < count($node_results->all())) {
    $node_results = $node_results->first();
    $pxAPI = new PVE2_API($node_results->hostname, $node_results->username, $node_results->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($node_results->password));
    $noLogin = false;
    if (!$pxAPI->login()) {
        $noLogin = true;
    }
    if (!$noLogin) {
        $currentIsosDB = $db->get("vncp_lxc_templates", ["id", "!=", 0])->all();
        $currentIsos = [];
        for ($i = 0; $i < count($currentIsosDB); $i++) {
            array_push($currentIsos, $currentIsosDB[$i]->volid);
        }
        $activeStorages = $pxAPI->get("/nodes/" . $node_results->name . "/storage", ["enabled", 1]);
        $isoStorages = [];
        for ($i = 0; $i < count($activeStorages); $i++) {
            if (strpos($activeStorages[$i]["content"], "vztmpl") !== false) {
                $isoStorages[] = $activeStorages[$i];
            }
        }
        $missingContent = [];
        $isos = [];
        for ($i = 0; $i < count($isoStorages); $i++) {
            $allIsos = $pxAPI->get("/nodes/" . $node_results->name . "/storage/" . $isoStorages[$i]["storage"] . "/content");
            for ($j = 0; $j < count($allIsos); $j++) {
                if ($allIsos[$j]["content"] == "vztmpl") {
                    $isos[] = $allIsos[$j];
                }
            }
        }
        for ($i = 0; $i < count($isos); $i++) {
            if (!in_array($isos[$i]["volid"], $currentIsos)) {
                $missingContent[] = $isos[$i]["volid"];
            }
        }
        if (0 < count($missingContent)) {
            echo "                      <div class=\"alert alert-info\" role=\"alert\">Found new template files. Would you like to import them?</div>\r\n                      <form role=\"form\" action=\"\" method=\"POST\">\r\n                        ";
            for ($i = 0; $i < count($missingContent); $i++) {
                echo "<div class=\"input-group\">\r\n                            <span class=\"input-group-addon\">Friendly Name</span>\r\n                            <input type=\"text\" class=\"form-control\" name=\"field[" . (string) $i . "][fname]\" value=\"" . explode("/", $missingContent[$i])[1] . "\" />\r\n                            <span class=\"input-group-addon\">Volume ID</span>\r\n                            <input type=\"text\" class=\"form-control\" name=\"field[" . (string) $i . "][volid]\" value=\"" . $missingContent[$i] . "\" readonly=\"readonly\" />\r\n                          </div><br />";
            }
            echo "                        <input type=\"hidden\" name=\"form_name\" value=\"import_lxc_template\" />\r\n                        <input type=\"submit\" value=\"Import\" class=\"btn btn-success btn-lg btn-block\" />\r\n                      </form><br /><br />\r\n                      ";
        }
    } else {
        echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Error:</strong> Could not connect to Proxmox node to check for new templates.</div>";
    }
}
echo "                <div class=\"table-responsive\">\r\n                    <table class=\"table table-hover\" id=\"admin_lxctemptable\">\r\n                        <thead>\r\n                            <tr>\r\n                                <th>Friendly Name</th>\r\n                                <th>Volume ID</th>\r\n                                <th>Delete</th>\r\n                            </tr>\r\n                        </thead>\r\n                        <tbody>\r\n                            ";
$admin_datalxctemp = $db->get("vncp_lxc_templates", ["id", "!=", 0]);
$admin_datalxctemp = $admin_datalxctemp->all();
for ($k = 0; $k < count($admin_datalxctemp); $k++) {
    echo "<tr>";
    echo "<td>" . $admin_datalxctemp[$k]->friendly_name . "</td>";
    echo "<td>" . $admin_datalxctemp[$k]->volid . "</td>";
    echo "<td><button id=\"admin_lxctempdelete" . $admin_datalxctemp[$k]->id . "\" class=\"btn btn-sm btn-danger\" role=\"" . $admin_datalxctemp[$k]->id . "\">Delete</button></td>";
    echo "</tr>";
}
echo "                        </tbody>\r\n                    </table>\r\n                </div>\r\n                <h2 align=\"center\">Manually Add New LXC Template</h2>\r\n                <form role=\"form\" action=\"\" method=\"POST\">\r\n                    <div class=\"form-group\">\r\n                        <label>Friendly Name</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"fname\" placeholder=\"CentOS 7\" />\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label>Volume ID</label>\r\n                        <input class=\"form-control\" type=\"text\" name=\"volid\" placeholder=\"store:vztmpl/file.tar.gz\" />\r\n                    </div>\r\n                    <input type=\"hidden\" name=\"token\" value=\"";
echo Token::generate();
echo "\" />\r\n                    <input type=\"hidden\" name=\"form_name\" value=\"new_lxc_template\" />\r\n                    <input type=\"submit\" value=\"Submit\" class=\"btn btn-success\" />\r\n                </form>\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n";

?>
