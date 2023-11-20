<?php

if (!defined("constant")) {
    header("Location: index");
}
$clone_pending = $db->get("vncp_pending_clone", ["hb_account_id", "=", parse_input($_GET["id"])])->first();
if (count($clone_pending) == 0) {
    echo "<div class=\"col-md-12\">\r\n\t<!-- Feature with tabs -->\r\n    <section class=\"feature-tabs\">\r\n     <div class=\"first-sliding\">\r\n     </div>\r\n     <div class=\"wrap\">\r\n      <div class=\"tabs\">\r\n       <div class=\"tabs_container\">\r\n       <!-- TAB -->\r\n        <li class=\"btnLink smallBtnLink green_tab\">\r\n            <a class=\"tab-action active\" data-tab-cnt=\"tab1\">\r\n                <span><i class=\"fa fa-wrench\"></i> General</span>\r\n            </a></li>\r\n        <!-- TAB END -->\r\n        <!-- TAB -->\r\n        <li class=\"btnLink smallBtnLink green_tab\">\r\n            <a class=\"tab-action\" data-tab-cnt=\"tab3\">\r\n                <span><i class=\"fa fa-desktop\"></i> Device Manager</span>\r\n            </a></li>\r\n        <!-- TAB END -->\r\n        <!-- TAB -->\r\n        <li class=\"btnLink smallBtnLink green_tab\">\r\n            <a class=\"tab-action\" data-tab-cnt=\"tab5\">\r\n                <span><i class=\"fa fa-bolt\"></i> Network Manager</span>\r\n            </a></li>\r\n        <!-- TAB END -->\r\n        <!-- TAB -->\r\n        <li class=\"btnLink smallBtnLink green_tab\">\r\n            <a class=\"tab-action\" data-tab-cnt=\"tab2\">\r\n                <span><i class=\"fa fa-area-chart\"></i> Resource Graphs</span>\r\n            </a></li>\r\n        <!-- TAB END -->\r\n        <!-- TAB -->\r\n        <li class=\"btnLink smallBtnLink green_tab\">\r\n            <a class=\"tab-action\" data-tab-cnt=\"tab4\">\r\n                <span><i class=\"fa fa-cubes\"></i> Rebuild</span>\r\n            </a></li>\r\n        <!-- TAB END -->\r\n        <!-- TAB -->\r\n        <li class=\"btnLink smallBtnLink green_tab\">\r\n            <a class=\"tab-action\" data-tab-cnt=\"tab7\">\r\n                <span><i class=\"fa fa-hdd-o\"></i> Backups</span>\r\n            </a></li>\r\n        <!-- TAB END -->\r\n        <!-- TAB -->\r\n        <li class=\"btnLink smallBtnLink green_tab\">\r\n            <a class=\"tab-action\" data-tab-cnt=\"tab8\">\r\n                <span><i class=\"fa fa-terminal\"></i> Console</span>\r\n            </a></li>\r\n        <!-- TAB END -->\r\n       </div>\r\n       <br>\r\n       <div class=\"clr\"></div>\r\n       <!-- TAB CONTENT -->\r\n       <div id=\"tab1\" class=\"tab-single tab-cnt active\">\r\n            <div class=\"datacenters\">\r\n                <div class=\"col-md-12\">\r\n\t\t            <div id=\"func_error\"></div>\r\n\t\t            <h1 align=\"center\" id=\"status_1\">Server Status: <span class=\"label\" id=\"status_2\"><img src=\"img/loader.GIF\" id=\"loader\" /></span></h1>\r\n\t\t            ";
    $results = $db->get("vncp_kvm_ct", ["hb_account_id", "=", parse_input($_GET["id"])]);
    $data = $results->first();
    $node_results = $db->get("vncp_nodes", ["name", "=", $data->node]);
    $node_data = $node_results->first();
    $pxAPI = new PVE2_API($node_data->hostname, $node_data->username, $node_data->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($node_data->password));
    $noLogin = false;
    if (!$pxAPI->login()) {
        $noLogin = true;
    }
    if (!$noLogin) {
        $vminfo = $pxAPI->get("/pools/" . $data->pool_id);
        if (count($vminfo["members"]) == 1) {
            $clvmid = $vminfo["members"][0]["vmid"];
            $vmdetails = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/status/current");
        } else {
            for ($j = 0; $j < count($vminfo["members"]); $j++) {
                if ($vminfo["members"][$j]["name"] == $data->cloud_hostname) {
                    $vmdetails = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $vminfo["members"][$j]["vmid"] . "/status/current");
                    $clvmid = $vminfo["members"][$j]["vmid"];
                }
            }
        }
    }
    echo "\t\t            <br />\r\n\t\t            <div class=\"row\">\r\n\t\t            \t<div class=\"col-md-12\">\r\n\t\t            \t\t<div class=\"panel panel-default\">\r\n\t\t            \t\t\t<div class=\"panel-body\">\r\n\t\t            \t\t\t\t<div class=\"col-md-2\"><p><em>CPU Usage</em></p></div>\r\n\t\t            \t\t\t\t<div class=\"progress\">\r\n\t\t            \t\t\t\t\t<div class=\"progress-bar progress-bar-info progress-bar-striped\" role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"min-width: 2em;width: 100%;\" id=\"cpu_usage_1\"><div id=\"cpu_usage_2\"></div></div>\r\n\t\t            \t\t\t\t</div>\r\n\t\t            \t\t\t\t<div class=\"col-md-2\"><p><em>RAM Usage</em></p></div>\r\n\t\t            \t\t\t\t<div class=\"progress\">\r\n\t\t            \t\t\t\t\t<div class=\"progress-bar progress-bar-info progress-bar-striped\" role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"min-width: 2em;width: 100%;\" id=\"ram_usage_1\"><div id=\"ram_usage_2\"></div></div>\r\n\t\t            \t\t\t\t</div>\r\n                        ";
    if ($data->cloud_account_id == 0) {
        $bwstats = $db->get("vncp_bandwidth_monitor", ["hb_account_id", "=", parse_input($_GET["id"])])->first();
        $bwperc = round((double) $bwstats->current / (double) $bwstats->max * 100, 2);
        $bwperc_single = round($bwperc, 0);
        echo "                        <div class=\"col-md-2\"><p><em>Bandwidth Usage</em></p></div>\r\n\t\t            \t\t\t\t<div class=\"progress\">\r\n\t\t            \t\t\t\t\t<div class=\"progress-bar progress-bar-info progress-bar-striped\" role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"min-width: 2em;width: ";
        echo $bwperc_single;
        echo "%;\"><div>";
        echo $bwperc;
        echo "%</div></div>\r\n\t\t            \t\t\t\t</div>\r\n                      ";
    }
    echo "                      <div class=\"col-md-2\"><p><em>Total Storage</em></p></div>\r\n                      <div>\r\n                        ";
    echo (int) $vmdetails["maxdisk"] / 1073741824;
    echo "GB\r\n                      </div>\r\n\t\t            \t\t\t</div>\r\n\t\t            \t\t</div>\r\n\t\t                </div>\r\n\t\t            </div>\r\n\t\t            <div class=\"row\">\r\n\t\t            \t<div class=\"col-md-3\">\r\n\t\t            \t\t";
    if ($data->suspended == 0) {
        echo "<button class=\"btn btn-block btn-lg btn-success\" disabled=\"disabled\" id=\"start_server\">\r\n\t\t                        <center>\r\n\t\t                            <i class=\"fa fa-play fa-4x\"></i>\r\n\t\t                            <div>Start Server</div>\r\n\t\t                        </center>\r\n\t\t                    </button>";
    } else {
        echo "<button class=\"btn btn-block btn-lg btn-success\" disabled=\"disabled\">\r\n\t\t                        <center>\r\n\t\t                            <i class=\"fa fa-play fa-4x\"></i>\r\n\t\t                            <div>Start Server</div>\r\n\t\t                        </center>\r\n\t\t                    </button>";
    }
    echo "\t\t            \t</div>\r\n\t\t            \t<div class=\"col-md-3\">\r\n\t\t            \t\t";
    if ($data->suspended == 0) {
        echo "<button class=\"btn btn-block btn-lg btn-danger\" disabled=\"disabled\" id=\"shutdown_server\">\r\n\t\t                        <center>\r\n\t\t                            <i class=\"fa fa-stop fa-4x\"></i>\r\n\t\t                            <div>Shutdown Server</div>\r\n\t\t                        </center>\r\n\t\t                    </button>";
    } else {
        echo "<button class=\"btn btn-block btn-lg btn-danger\" disabled=\"disabled\">\r\n\t\t                        <center>\r\n\t\t                            <i class=\"fa fa-stop fa-4x\"></i>\r\n\t\t                            <div>Shutdown Server</div>\r\n\t\t                        </center>\r\n\t\t                    </button>";
    }
    echo "\t\t            \t</div>\r\n\t\t            \t<div class=\"col-md-3\">\r\n\t\t            \t\t";
    if ($data->suspended == 0) {
        echo "<button class=\"btn btn-block btn-lg btn-warning\" disabled=\"disabled\" id=\"restart_server\">\r\n\t\t                        <center>\r\n\t\t                            <i class=\"fa fa-refresh fa-4x\"></i>\r\n\t\t                            <div>Restart Server</div>\r\n\t\t                        </center>\r\n\t\t                    </button>";
    } else {
        echo "<button class=\"btn btn-block btn-lg btn-warning\" disabled=\"disabled\">\r\n\t\t                        <center>\r\n\t\t                            <i class=\"fa fa-refresh fa-4x\"></i>\r\n\t\t                            <div>Restart Server</div>\r\n\t\t                        </center>\r\n\t\t                    </button>";
    }
    echo "\t\t            \t</div>\r\n\t\t            \t<div class=\"col-md-3\">\r\n\t\t            \t\t";
    if ($data->suspended == 0) {
        echo "<button class=\"btn btn-block btn-lg btn-info\" disabled=\"disabled\" id=\"kill_server\">\r\n\t\t                        <center>\r\n\t\t                            <i class=\"fa fa-times fa-4x\"></i>\r\n\t\t                            <div>Kill Server</div>\r\n\t\t                        </center>\r\n\t\t                    </button>";
    } else {
        echo "<button class=\"btn btn-block btn-lg btn-info\" disabled=\"disabled\">\r\n\t\t                        <center>\r\n\t\t                            <i class=\"fa fa-times fa-4x\"></i>\r\n\t\t                            <div>Kill Server</div>\r\n\t\t                        </center>\r\n\t\t                    </button>";
    }
    echo "\t\t            \t</div>\r\n\t\t            </div>\r\n\t\t            <br />\r\n                ";
    $KVMNAT = count($db->get("vncp_natforwarding", ["hb_account_id", "=", $data->hb_account_id])->all());
    echo "\t\t            <div class=\"row\">\r\n\t\t            \t<div class=\"col-md-6 col-md-offset-3\">\r\n\t\t                    <div class=\"table-responsive\">\r\n\t\t                        <table class=\"table table-striped\">\r\n\t\t                            <tr>\r\n\t\t                                <td>Uptime</td>\r\n\t\t                                <td id=\"uptime\">0 seconds</td>\r\n\t\t                            </tr>\r\n\t\t                            <tr>\r\n\t\t                                <td>Hostname</td>\r\n\t\t                                <td>";
    echo $vmdetails["name"];
    echo " ( KVM )</td>\r\n\t\t                            </tr>\r\n\t\t                            <tr>\r\n\t\t                                <td>Primary IP</td>\r\n\t\t                                <td>";
    echo $data->ip;
    echo "</td>\r\n\t\t                            </tr>\r\n                                ";
    if ($KVMNAT == 1) {
        echo "                                  <tr>\r\n                                    <td>Public IP</td>\r\n                                    <td>";
        $natnode = $db->get("vncp_nat", ["node", "=", $data->node])->all();
        if (count($natnode) == 1) {
            echo $natnode[0]->publicip;
        } else {
            echo "Error";
        }
        echo "</td>\r\n                                  </tr>\r\n                                ";
    }
    echo "\t\t                            <tr>\r\n\t\t                                <td>Operating System</td>\r\n\t\t                                <td>";
    echo $data->os;
    echo "</td>\r\n\t\t                            </tr>\r\n\t\t                            <tr>\r\n\t\t                            \t<td>VPS Node</td>\r\n\t\t                            \t<td>";
    echo $data->node;
    echo "</td>\r\n\t\t                            </tr>\r\n\t\t                            ";
    if ($data->cloud_account_id != 0) {
        echo "<tr><td>Delete VM</td>";
        $pend = $db->get("vncp_pending_deletion", ["hb_account_id", "=", $data->hb_account_id]);
        $pending = $pend->all();
        if (count($pending) == 0) {
            echo "<td><button type=\"button\" class=\"btn btn-sm btn-danger\" id=\"cldeletevm\" role=\"" . $data->cloud_account_id . "\">Delete VM from cloud</button></td>";
        } else {
            echo "<td><button type=\"button\" class=\"btn btn-sm btn-danger\" disabled>A deletion request already exists</button></td>";
        }
        echo "</tr><div class=\"modal fade\" id=\"cldeletevmconfirm\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"cldeletevmconfirmlabel\" aria-hidden=\"true\">\r\n\t\t                            \t        <div class=\"modal-dialog\">\r\n\t\t                            \t            <div class=\"modal-content\">\r\n\t\t                            \t                <div class=\"modal-header\">\r\n\t\t                            \t                    <h4 class=\"modal-title\" id=\"cldeletevmconfirmlabel\">Confirm - delete VM</h4>\r\n\t\t                            \t                </div>\r\n\t\t                            \t                <div class=\"modal-body\">\r\n\t\t                            \t                    <div id=\"cldeletevmres\">Please wait...</div>\r\n\t\t                            \t                </div>\r\n\t\t                            \t            </div>\r\n\t\t                            \t        </div>\r\n\t\t                            \t    </div>";
    }
    echo "\t\t                        </table>\r\n\t\t                    </div>\r\n\t\t                </div>\r\n\t\t            </div>\r\n                </div>\r\n            </div>\r\n       </div>\r\n       <!-- TAB CONTENT END -->\r\n       <!-- TAB CONTENT -->\r\n       <div id=\"tab3\" class=\"tab-single tab-cnt\">\r\n             <div class=\"datacenters\">\r\n                <div class=\"col-md-12\">\r\n                  <div id=\"kvm_pwsuccess\"></div>\r\n                    <div class=\"table-responsive\">\r\n                    ";
    $device = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/config");
    echo "                      <table class=\"table table-striped\">\r\n                        <tr>\r\n                          <th>Option</th>\r\n                          <th>Description</th>\r\n                          <th>Manage</th>\r\n                        </tr>\r\n                        <tr>\r\n                          <td>Start at boot</td>\r\n                          <td>Enable or disable starting your VPS at node boot.</td>\r\n                          ";
    if ($data->onboot == 0 && $data->suspended == 0) {
        echo "<td><button class=\"btn btn-success btn-sm\" id=\"enableonboot\">Enable</button></td>";
    } else {
        if ($data->onboot == 1 && $data->suspended == 0) {
            echo "<td><button class=\"btn btn-danger btn-sm\" id=\"disableonboot\">Disable</button></td>";
        } else {
            echo "<td><button class=\"btn btn-warning btn-sm\" disabled>Service suspended</button></td>";
        }
    }
    echo "                        </tr>\r\n                        <tr>\r\n                          <td>Attached ISO</td>\r\n                          <td>Change the ISO attached to your VPS.\r\n                            ";
    if ($data->from_template == 0) {
        echo "                          \t<select id=\"changeiso\" class=\"form-control\">\r\n                          \t\t";
        $iso = explode(",", $device["ide2"]);
        $firstiso = $db->get("vncp_kvm_isos", ["volid", "=", $iso[0]])->all();
        $firstiso_custom = $db->get("vncp_kvm_isos_custom", ["upload_key", "=", explode(".", $iso[0])[0]])->all();
        $location = $db->get("vncp_kvm_isos", ["id", "!=", 0])->first();
        list($location) = explode(":", $location->volid);
        if (0 < count($firstiso)) {
            echo "<option value=\"" . $firstiso[0]->volid . "\" role=\"first\">" . $firstiso[0]->friendly_name . "</option>";
        } else {
            echo "<option value=\"" . $location . ":iso/" . $firstiso_custom[0]->upload_key . ".iso\" role=\"first\">" . $firstiso_custom[0]->fname . "</option>";
        }
        $content_custom = $db->get("vncp_kvm_isos_custom", ["upload_key", "!=", explode(".", $iso[0])[0]])->all();
        $content = $db->get("vncp_kvm_isos", ["volid", "!=", $iso[0]])->all();
        for ($i = 0; $i < count($content_custom); $i++) {
            if ($content_custom[$i]->user_id == $user->data()->id && $content_custom[$i]->status == "active") {
                echo "<option value=\"" . $location . ":iso/" . $content_custom[$i]->upload_key . ".iso\">" . $content_custom[$i]->fname . "</option>";
            }
        }
        for ($i = 0; $i < count($content); $i++) {
            echo "<option value=\"" . $content[$i]->volid . "\">" . $content[$i]->friendly_name . "</option>";
        }
        echo "                          \t</select>\r\n                          ";
    } else {
        echo "<br />Not available on this VM.";
    }
    echo "                          </td>\r\n                          <td><button class=\"btn btn-success btn-sm\" id=\"changeisosubmit\" disabled>Change</button></td>\r\n                        </tr>\r\n                        <tr>\r\n                          <td>Boot Order</td>\r\n                          <td>Change the boot order of your VPS.\r\n                            ";
    if ($data->from_template == 0) {
        echo "                          <div class=\"row\">\r\n                          \t";
        if (array_key_exists("boot", $device)) {
            $boot1 = $device["boot"][0];
            $boot2 = $device["boot"][1];
            $boot3 = $device["boot"][2];
            echo "<div class=\"col-md-4\"><select id=\"bo1\" class=\"form-control\">";
            if ($boot1 == "c") {
                echo "<option value=\"c\">Disk</option><option value=\"d\">CD-ROM</option><option value=\"n\">Network</option>";
            } else {
                if ($boot1 == "d") {
                    echo "<option value=\"d\">CD-ROM</option><option value=\"c\">Disk</option><option value=\"n\">Network</option>";
                } else {
                    echo "<option value=\"n\">Network</option><option value=\"c\">Disk</option><option value=\"d\">CD-ROM</option>";
                }
            }
            echo "</select></div><div class=\"col-md-4\"><select id=\"bo2\" class=\"form-control\">";
            if ($boot2 == "c") {
                echo "<option value=\"c\">Disk</option><option value=\"d\">CD-ROM</option><option value=\"n\">Network</option>";
            } else {
                if ($boot2 == "d") {
                    echo "<option value=\"d\">CD-ROM</option><option value=\"c\">Disk</option><option value=\"n\">Network</option>";
                } else {
                    echo "<option value=\"n\">Network</option><option value=\"c\">Disk</option><option value=\"d\">CD-ROM</option>";
                }
            }
            echo "</select></div><div class=\"col-md-4\"><select id=\"bo3\" class=\"form-control\">";
            if ($boot3 == "c") {
                echo "<option value=\"c\">Disk</option><option value=\"d\">CD-ROM</option><option value=\"n\">Network</option>";
            } else {
                if ($boot3 == "d") {
                    echo "<option value=\"d\">CD-ROM</option><option value=\"c\">Disk</option><option value=\"n\">Network</option>";
                } else {
                    echo "<option value=\"n\">Network</option><option value=\"c\">Disk</option><option value=\"d\">CD-ROM</option>";
                }
            }
            echo "</select></div>";
        } else {
            echo "<div class=\"col-md-4\">\r\n                          \t\t<select id=\"bo1\" class=\"form-control\">\r\n                          \t\t\t<option value=\"c\">Disk</option>\r\n                          \t\t\t<option value=\"d\">CD-ROM</option>\r\n                          \t\t\t<option value=\"n\">Network</option>\r\n                          \t\t</select>\r\n                          \t</div>\r\n                          \t<div class=\"col-md-4\">\r\n                          \t\t<select id=\"bo2\" class=\"form-control\">\r\n                          \t\t\t<option value=\"d\">CD-ROM</option>\r\n                          \t\t\t<option value=\"c\">Disk</option>\r\n                          \t\t\t<option value=\"n\">Network</option>\r\n                          \t\t</select>\r\n                          \t</div>\r\n                          \t<div class=\"col-md-4\">\r\n                          \t\t<select id=\"bo3\" class=\"form-control\">\r\n                          \t\t\t<option value=\"n\">Network</option>\r\n                          \t\t\t<option value=\"c\">Disk</option>\r\n                          \t\t\t<option value=\"d\">CD-ROM</option>\r\n                          \t\t</select>\r\n                          \t</div>";
        }
        echo "                          </div>\r\n                          ";
    } else {
        echo "<br />Not available on this VM.";
    }
    echo "                          </td>\r\n                          ";
    if ($data->from_template == 0) {
        echo "                          <td><button class=\"btn btn-success btn-sm\" id=\"bosubmit\">Change</button></td>\r\n                          ";
    } else {
        echo "<td><button class=\"btn btn-success btn-sm\" disabled>Change</button></td>";
    }
    echo "                        </tr>\r\n                        <tr>\r\n                          <td>VirtIO RNG</td>\r\n                          <td>Enable or disable the VirtIO RNG emulator (reboot required, provides better entropy)</td>\r\n                          ";
    if ($data->suspended == 1) {
        echo "<td><button class=\"btn btn-warning btn-sm\" disabled>Service suspended</button></td>";
    } else {
        if (array_key_exists("rng0", $device) && $data->suspended == 0) {
            echo "<td><button class=\"btn btn-danger btn-sm\" id=\"disablerng\">Disable</button></td>";
        } else {
            echo "<td><button class=\"btn btn-success btn-sm\" id=\"enablerng\">Enable</button></td>";
        }
    }
    echo "                        </tr>\r\n                        <tr>\r\n                          <td>VPS Task Log</td>\r\n                          <td><textarea class=\"form-control\" id=\"vmlog\" rows=\"10\" style=\"resize:none;font-family: Menlo,Monaco,Consolas,monospace;font-size:13px;\" disabled>null</textarea></td>\r\n                          <td><button class=\"btn btn-info btn-sm\" id=\"getvmlog\">Fetch Log</button><br /><br /><button class=\"btn btn-info btn-sm\" id=\"clearvmlog\">Clear Log</button></td>\r\n                        </tr>\r\n                        ";
    if ($data->from_template == 2 && (strpos(strtolower($data->os), "linux") !== false || strpos(strtolower($data->os), "centos") !== false || strpos(strtolower($data->os), "ubuntu") !== false || strpos(strtolower($data->os), "debian") !== false || strpos(strtolower($data->os), "turnkey") !== false)) {
        echo "                        <tr>\r\n                          <td>Change Root Password</td>\r\n                          <td>Forgot your root password? Change it here (REQUIRES VM REBOOT).</td>\r\n                          <td><button class=\"btn btn-success btn-sm\" id=\"chgrootpw_kvm\">Change Password</button></td>\r\n                        </tr>\r\n                        ";
    }
    echo "                      </table>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n       </div>\r\n       <!-- TAB CONTENT END -->\r\n     <!-- TAB CONTENT -->\r\n     ";
    $portforwards = $db->get("vncp_natforwarding", ["hb_account_id", "=", $data->hb_account_id])->all();
    if (count($portforwards) == 1) {
        $portlimit = $portforwards[0]->avail_ports;
        $domainlimit = $portforwards[0]->avail_domains;
        $ports = (string) (count(explode(";", $portforwards[0]->ports)) - 1);
        $domains = (string) (count(explode(";", $portforwards[0]->domains)) - 1);
    }
    echo "     <div id=\"tab5\" class=\"tab-single tab-cnt\">\r\n           <div class=\"datacenters\">\r\n             <div class=\"col-md-3\">\r\n               <div class=\"tabpanel\">\r\n                   <ul class=\"nav nav-pills nav-stacked\" role=\"tablist\">\r\n                     <li role=\"presentation\" class=\"active\"><a href=\"#ipam\" aria-controls=\"ipam\" role=\"tab\" data-toggle=\"pill\"><i class=\"fa fa-location-arrow\"></i> IPAM</a></li>\r\n                     ";
    if ($KVMNAT == 1) {
        echo "                     <li role=\"presentation\"><a href=\"#natports\" aria-controls=\"natports\" role=\"tab\" data-toggle=\"pill\"><i class=\"fa fa-arrows-h\"></i> NAT Ports (";
        echo $ports;
        echo " / ";
        echo $portlimit;
        echo ")</a></li>\r\n                     <li role=\"presentation\"><a href=\"#natdomains\" aria-controls=\"natdomains\" role=\"tab\" data-toggle=\"pill\"><i class=\"fa fa-globe\"></i> NAT Domains (";
        echo $domains;
        echo " / ";
        echo $domainlimit;
        echo ")</a></li>\r\n                     ";
    }
    echo "                   </ul>\r\n               </div>\r\n              </div>\r\n              <div class=\"col-md-9\">\r\n                <div class=\"tabpanel\">\r\n                  <div class=\"tab-content\">\r\n                    <div role=\"tabpanel\" class=\"tab-pane active\" id=\"ipam\">\r\n                        ";
    $ip_values = $db->get("vncp_dhcp", ["ip", "=", $data->ip]);
    $ip_values = $ip_values->first();
    echo "                          <h2 align=\"center\">Public Network</h2>\r\n                          <div class=\"table-responsive\">\r\n                          \t<table class=\"table table-striped\">\r\n                          \t\t<tr>\r\n                          \t\t\t<th>IPv4 Address</th>\r\n                          \t\t\t<th>Gateway</th>\r\n                          \t\t\t<th>Netmask</th>\r\n                          \t\t\t<th>Assignment</th>\r\n                          \t\t\t";
    if ($data->cloud_account_id != 0) {
        echo "<th></th>";
    }
    echo "                          \t\t</tr>\r\n                          \t\t<tr>\r\n                          \t\t\t<td>";
    echo $data->ip;
    echo "</td>\r\n                          \t\t\t<td>";
    echo $ip_values->gateway;
    echo "</td>\r\n                          \t\t\t<td>";
    echo $ip_values->netmask;
    echo "</td>\r\n                          \t\t\t<td>";
    if ($ip_values->type == 0) {
        echo "primary";
    } else {
        echo "secondary";
    }
    echo "</td>\r\n                                ";
    if ($data->cloud_account_id != 0) {
        echo "<td></td>";
    }
    echo "                          \t\t</tr>\r\n                          \t\t";
    $getsips = $db->get("vncp_secondary_ips", ["hb_account_id", "=", parse_input($_GET["id"])]);
    $gotsips = $getsips->all();
    for ($i = 0; $i < count($gotsips); $i++) {
        echo "<tr>";
        echo "<td>" . $gotsips[$i]->address . "</td>";
        echo "<td>null</td><td>null</td><td>secondary</td>";
        if ($data->cloud_account_id != 0) {
            echo "<td><button type=\"button\" class=\"btn btn-sm btn-danger\" id=\"cloudremoveip\" role=\"" . $gotsips[$i]->address . "\">Delete</button></td>";
        }
        echo "</tr>";
    }
    echo "                          \t</table>\r\n                          </div>\r\n                          ";
    if ($data->cloud_account_id != 0) {
        $ipcheck = $db->get("vncp_kvm_cloud", ["hb_account_id", "=", $data->cloud_account_id]);
        $ipr = $ipcheck->first();
        if ($ipr->avail_ip_limit != 0) {
            echo "<button type=\"button\" class=\"btn btn-success btn-block\" id=\"cloudassignip\">Assign IPv4 Address From Pool (" . $ipr->avail_ip_limit . " remaining)</button>";
        }
    }
    echo "                          <br />\r\n                          ";
    $vm_ipv6 = parse_input($db->get("vncp_settings", ["item", "=", "vm_ipv6"])->first()->value);
    $v6mode = parse_input($db->get("vncp_settings", ["item", "=", "ipv6_mode"])->first()->value);
    $private_networking = parse_input($db->get("vncp_settings", ["item", "=", "private_networking"])->first()->value);
    if ($vm_ipv6 == "true") {
        if ($v6mode == "single") {
            $ipv6_limit = (int) parse_input($db->get("vncp_settings", ["item", "=", "ipv6_limit"])->first()->value);
        } else {
            $ipv6_limit = (int) parse_input($db->get("vncp_settings", ["item", "=", "ipv6_limit_subnet"])->first()->value);
        }
        echo "                          <div class=\"table-responsive\">\r\n                          \t<table class=\"table table-striped\">\r\n                          \t\t<tr>\r\n                          \t\t\t<th>IPv6 Address</th>\r\n                          \t\t\t<th>Gateway</th>\r\n                          \t\t\t<th>Netmask</th>\r\n                          \t\t</tr>\r\n                          \t\t";
        $v6data = $db->get("vncp_ipv6_assignment", ["hb_account_id", "=", parse_input($_GET["id"])]);
        $v6results = $db->all();
        if (0 < count($v6results)) {
            for ($i = 0; $i < count($v6results); $i++) {
                $v6pool = $db->get("vncp_ipv6_pool", ["id", "=", $v6results[$i]->ipv6_pool_id])->first();
                echo "<tr>";
                echo "<td>" . $v6results[$i]->address . "</td>";
                echo "<td>" . explode("/", $v6pool->subnet)[0] . "1</td>";
                echo "<td>" . explode("/", $v6pool->subnet)[1] . "</td>";
                echo "</tr>";
            }
        }
        echo "                          \t</table>\r\n                          </div>\r\n                          ";
        if (count($v6results) < $ipv6_limit) {
            echo "<button type=\"button\" class=\"btn btn-success btn-block\" id=\"assignipv6\">Assign IPv6 Address (";
            echo count($v6results);
            echo " of ";
            echo $ipv6_limit;
            echo ")</button>";
        }
    }
    echo "                          ";
    if ($private_networking == "true") {
        echo "                          <h2 align=\"center\">Private Network</h2>\r\n                          ";
        if ($data->has_net1 == 1) {
            echo "                          <div class=\"table-responsive\">\r\n                          \t<table class=\"table table-striped\">\r\n                          \t\t<tr>\r\n                          \t\t\t<th>IPv4 Address</th>\r\n                          \t\t\t<th>Netmask</th>\r\n                          \t\t\t<th></th>\r\n                          \t\t</tr>\r\n                          \t\t<tr>\r\n                          \t\t\t";
            $getprivateip = $db->get("vncp_private_pool", ["hb_account_id", "=", parse_input($_GET["id"])]);
            $ipresults = $getprivateip->first();
            echo "<td>" . $ipresults->address . "</td>";
            echo "                          \t\t\t<td>";
            echo $ipresults->netmask;
            echo "</td>\r\n                          \t\t\t<td><button type=\"button\" class=\"btn btn-danger btn-md\" id=\"disableprivatenet\">Disable Private Networking</button></td>\r\n                          \t\t</tr>\r\n                          \t</table>\r\n                          </div>\r\n                          ";
        } else {
            echo "                          <button type=\"button\" class=\"btn btn-success btn-block\" id=\"enableprivatenet\">Enable Private Networking</button><br /><br />\r\n                        ";
        }
    }
    echo "                    </div>\r\n                    ";
    if ($KVMNAT == 1) {
        echo "                    <div role=\"tabpanel\" class=\"tab-pane\" id=\"natports\">\r\n                      <form role=\"form\">\r\n                         <div class=\"form-group\">\r\n                             <label>NAT Port</label>\r\n                             <input class=\"form-control\" type=\"number\" id=\"chosennatport\" autocomplete=\"off\" min=\"1\" max=\"65535\" />\r\n                             <p class=\"help-block\" style=\"color:red;\" id=\"natport_error\"></p>\r\n                         </div>\r\n                         <div class=\"form-group\">\r\n                             <label>Description <small>max 20 characters</small></label>\r\n                             <input class=\"form-control\" type=\"text\" id=\"natportdesc\" autocomplete=\"off\" />\r\n                             <p class=\"help-block\" style=\"color:red;\" id=\"natdesc_error\"></p>\r\n                         </div>\r\n                         <input type=\"hidden\" id=\"aid\" value=\"";
        echo parse_input($data->hb_account_id);
        echo "\" />\r\n                         ";
        if ($data->suspended == 0) {
            echo "<input type=\"submit\" id=\"natport_btn\" value=\"Create Port Forward\" class=\"btn btn-info btn-lg\" />";
        } else {
            echo "<div class=\"btn btn-info btn-lg\" disabled>Create Port Forward</div>";
        }
        echo "                     </form><br /><br />\r\n                      <div class=\"table-responsive\">\r\n                        <table class=\"table table-striped table-condensed\" id=\"user_porttable\">\r\n                          <tr>\r\n                            <th>Public Side</th>\r\n                            <th></th>\r\n                            <th>NAT Side</th>\r\n                            <th>Description</th>\r\n                            <th></th>\r\n                          </tr>\r\n                          ";
        $allpdata = explode(";", $portforwards[0]->ports);
        for ($i = 0; $i < $ports; $i++) {
            $pdata = explode(":", $allpdata[$i]);
            echo "<tr>";
            echo "<td>" . $natnode[0]->publicip . ":" . $pdata[1] . "</td>";
            echo "<td><i class=\"fa fa-arrow-right\"></i></td>";
            echo "<td>" . $data->ip . ":" . $pdata[2] . "</td>";
            echo "<td>" . $pdata[3] . "</td>";
            echo "<td><button id=\"user_natportdelete" . $pdata[0] . "\" class=\"btn btn-sm btn-danger\" role=\"" . $pdata[0] . "\">Delete</button></td>";
            echo "</tr>";
        }
        echo "                        </table>\r\n                      </div>\r\n                    </div>\r\n                    <div role=\"tabpanel\" class=\"tab-pane\" id=\"natdomains\">\r\n                      <form role=\"form\">\r\n                         <div class=\"form-group\">\r\n                             <label>Domain</label>\r\n                             <input class=\"form-control\" type=\"text\" id=\"chosendomain\" autocomplete=\"off\" placeholder=\"domain.com\" />\r\n                             <p class=\"help-block\">Wildcard forwarding. All requests to domain.com and *.domain.com will be forwarded to this virtual machine.</p>\r\n                             <p class=\"help-block\" style=\"color:red;\" id=\"natdomain_error\"></p>\r\n                         </div>\r\n                         <p>NAT domains will forward HTTP traffic by default. To enable HTTPS support, paste your SSL certificate details below.</p>\r\n                         <div class=\"form-group\">\r\n                             <label>SSL Certificate</label>\r\n                             <textarea class=\"form-control\" rows=\"5\" id=\"nat_sslcert\" placeholder=\"*Optional*: paste SSL certificate in PEM format\"></textarea>\r\n                         </div>\r\n                         <div class=\"form-group\">\r\n                             <label>SSL Private Key</label>\r\n                             <textarea class=\"form-control\" rows=\"5\" id=\"nat_sslkey\" placeholder=\"*Optional*: paste SSL private key in PEM format\"></textarea>\r\n                         </div>\r\n                         <input type=\"hidden\" id=\"aid\" value=\"";
        echo parse_input($data->hb_account_id);
        echo "\" />\r\n                         ";
        if ($data->suspended == 0) {
            echo "<input type=\"submit\" id=\"natdomain_btn\" value=\"Create Domain Forward\" class=\"btn btn-info btn-lg\" />";
        } else {
            echo "<div class=\"btn btn-info btn-lg\" disabled>Create Domain Forward</div>";
        }
        echo "                     </form><br /><br />\r\n                     <div class=\"table-responsive\">\r\n                       <table class=\"table table-striped table-condensed\" id=\"user_domaintable\">\r\n                         <tr>\r\n                           <th>Domain</th>\r\n                           <th>Forwarding</th>\r\n                           <th></th>\r\n                         </tr>\r\n                         ";
        $allddata = explode(";", $portforwards[0]->domains);
        for ($i = 0; $i < $domains; $i++) {
            echo "<tr>";
            echo "<td>" . $allddata[$i] . "</td>";
            echo "<td>" . $allddata[$i] . " <i class=\"fa fa-arrow-right\"></i> " . $natnode[0]->publicip . " <i class=\"fa fa-arrow-right\"></i> " . $data->ip . "</td>";
            echo "<td><button id=\"user_natdomaindelete" . $allddata[$i] . "\" class=\"btn btn-sm btn-danger\" role=\"" . $allddata[$i] . "\">Delete</button></td>";
            echo "</tr>";
        }
        echo "                       </table>\r\n                     </div>\r\n                    </div>\r\n                   ";
    }
    echo "                  </div>\r\n                </div>\r\n              </div>\r\n              <br /><br />\r\n          </div>\r\n     </div>\r\n     <!-- TAB CONTENT END -->\r\n     <!-- TAB CONTENT -->\r\n     <div id=\"tab2\" class=\"tab-single tab-cnt\">\r\n           <div class=\"datacenters\">\r\n\t             <div class=\"row\">\r\n\t\t                <div class=\"col-md-4 col-md-offset-2\">\r\n\t\t                \t<p>Scale:</p>\r\n\t\t                \t<select class=\"form-control\" id=\"graphtime\">\r\n\t\t                \t\t<option value=\"hour\">Hour</option>\r\n\t\t                \t\t<option value=\"day\">Day</option>\r\n\t\t                \t\t<option value=\"week\">Week</option>\r\n\t\t                \t\t<option value=\"month\">Month</option>\r\n\t\t                \t\t<option value=\"year\">Year</option>\r\n\t\t                \t</select>\r\n\t\t                </div>\r\n\t             </div>\r\n\t             <div id=\"hour\">\r\n\t             \t\t<div class=\"row\">\r\n\t             \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n\t             \t\t\t\t<h4>CPU Usage</h4>\r\n\t             \t\t\t\t";
    $rrd = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/rrd?ds=cpu&timeframe=hour&cf=AVERAGE");
    echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
    echo "\t             \t\t\t</div>\r\n\t             \t\t</div>\r\n\t             \t\t<div class=\"row\">\r\n\t             \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n\t             \t\t\t\t<h4>RAM Usage</h4>\r\n\t             \t\t\t\t";
    $rrd = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/rrd?ds=mem,maxmem&timeframe=hour&cf=AVERAGE");
    echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
    echo "\t             \t\t\t</div>\r\n\t             \t\t</div>\r\n\t             \t\t<div class=\"row\">\r\n\t             \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n\t             \t\t\t\t<h4>Network Usage</h4>\r\n\t             \t\t\t\t";
    $rrd = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/rrd?ds=netin,netout&timeframe=hour&cf=AVERAGE");
    echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
    echo "\t             \t\t\t</div>\r\n\t             \t\t</div>\r\n\t             \t\t<div class=\"row\">\r\n\t             \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n\t             \t\t\t\t<h4>Disk I/O</h4>\r\n\t             \t\t\t\t";
    $rrd = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/rrd?ds=diskread,diskwrite&timeframe=hour&cf=AVERAGE");
    echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
    echo "\t             \t\t\t</div>\r\n\t             \t\t</div>\r\n\t             </div>\r\n\t             <div id=\"day\">\r\n\t             \t\t<div class=\"row\">\r\n\t             \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n\t             \t\t\t\t<h4>CPU Usage</h4>\r\n\t             \t\t\t\t";
    $rrd = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/rrd?ds=cpu&timeframe=day&cf=AVERAGE");
    echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
    echo "\t             \t\t\t</div>\r\n\t             \t\t</div>\r\n\t             \t\t<div class=\"row\">\r\n\t             \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n\t             \t\t\t\t<h4>RAM Usage</h4>\r\n\t             \t\t\t\t";
    $rrd = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/rrd?ds=mem,maxmem&timeframe=day&cf=AVERAGE");
    echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
    echo "\t             \t\t\t</div>\r\n\t             \t\t</div>\r\n\t             \t\t<div class=\"row\">\r\n\t             \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n\t             \t\t\t\t<h4>Network Usage</h4>\r\n\t             \t\t\t\t";
    $rrd = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/rrd?ds=netin,netout&timeframe=day&cf=AVERAGE");
    echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
    echo "\t             \t\t\t</div>\r\n\t             \t\t</div>\r\n\t             \t\t<div class=\"row\">\r\n\t             \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n\t             \t\t\t\t<h4>Disk I/O</h4>\r\n\t             \t\t\t\t";
    $rrd = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/rrd?ds=diskread,diskwrite&timeframe=day&cf=AVERAGE");
    echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
    echo "\t             \t\t\t</div>\r\n\t             \t\t</div>\r\n\t             </div>\r\n\t             <div id=\"week\">\r\n\t             \t\t<div class=\"row\">\r\n\t             \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n\t             \t\t\t\t<h4>CPU Usage</h4>\r\n\t             \t\t\t\t";
    $rrd = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/rrd?ds=cpu&timeframe=week&cf=AVERAGE");
    echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
    echo "\t             \t\t\t</div>\r\n\t             \t\t</div>\r\n\t             \t\t<div class=\"row\">\r\n\t             \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n\t             \t\t\t\t<h4>RAM Usage</h4>\r\n\t             \t\t\t\t";
    $rrd = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/rrd?ds=mem,maxmem&timeframe=week&cf=AVERAGE");
    echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
    echo "\t             \t\t\t</div>\r\n\t             \t\t</div>\r\n\t             \t\t<div class=\"row\">\r\n\t             \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n\t             \t\t\t\t<h4>Network Usage</h4>\r\n\t             \t\t\t\t";
    $rrd = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/rrd?ds=netin,netout&timeframe=week&cf=AVERAGE");
    echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
    echo "\t             \t\t\t</div>\r\n\t             \t\t</div>\r\n\t             \t\t<div class=\"row\">\r\n\t             \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n\t             \t\t\t\t<h4>Disk I/O</h4>\r\n\t             \t\t\t\t";
    $rrd = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/rrd?ds=diskread,diskwrite&timeframe=week&cf=AVERAGE");
    echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
    echo "\t             \t\t\t</div>\r\n\t             \t\t</div>\r\n\t             </div>\r\n\t             <div id=\"month\">\r\n\t             \t\t<div class=\"row\">\r\n\t             \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n\t             \t\t\t\t<h4>CPU Usage</h4>\r\n\t             \t\t\t\t";
    $rrd = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/rrd?ds=cpu&timeframe=month&cf=AVERAGE");
    echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
    echo "\t             \t\t\t</div>\r\n\t             \t\t</div>\r\n\t             \t\t<div class=\"row\">\r\n\t             \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n\t             \t\t\t\t<h4>RAM Usage</h4>\r\n\t             \t\t\t\t";
    $rrd = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/rrd?ds=mem,maxmem&timeframe=month&cf=AVERAGE");
    echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
    echo "\t             \t\t\t</div>\r\n\t             \t\t</div>\r\n\t             \t\t<div class=\"row\">\r\n\t             \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n\t             \t\t\t\t<h4>Network Usage</h4>\r\n\t             \t\t\t\t";
    $rrd = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/rrd?ds=netin,netout&timeframe=month&cf=AVERAGE");
    echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
    echo "\t             \t\t\t</div>\r\n\t             \t\t</div>\r\n\t             \t\t<div class=\"row\">\r\n\t             \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n\t             \t\t\t\t<h4>Disk I/O</h4>\r\n\t             \t\t\t\t";
    $rrd = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/rrd?ds=diskread,diskwrite&timeframe=month&cf=AVERAGE");
    echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
    echo "\t             \t\t\t</div>\r\n\t             \t\t</div>\r\n\t             </div>\r\n\t             <div id=\"year\">\r\n\t             \t\t<div class=\"row\">\r\n\t             \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n\t             \t\t\t\t<h4>CPU Usage</h4>\r\n\t             \t\t\t\t";
    $rrd = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/rrd?ds=cpu&timeframe=year&cf=AVERAGE");
    echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
    echo "\t             \t\t\t</div>\r\n\t             \t\t</div>\r\n\t             \t\t<div class=\"row\">\r\n\t             \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n\t             \t\t\t\t<h4>RAM Usage</h4>\r\n\t             \t\t\t\t";
    $rrd = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/rrd?ds=mem,maxmem&timeframe=year&cf=AVERAGE");
    echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
    echo "\t             \t\t\t</div>\r\n\t             \t\t</div>\r\n\t             \t\t<div class=\"row\">\r\n\t             \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n\t             \t\t\t\t<h4>Network Usage</h4>\r\n\t             \t\t\t\t";
    $rrd = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/rrd?ds=netin,netout&timeframe=year&cf=AVERAGE");
    echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
    echo "\t             \t\t\t</div>\r\n\t             \t\t</div>\r\n\t             \t\t<div class=\"row\">\r\n\t             \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n\t             \t\t\t\t<h4>Disk I/O</h4>\r\n\t             \t\t\t\t";
    $rrd = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clvmid . "/rrd?ds=diskread,diskwrite&timeframe=year&cf=AVERAGE");
    echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
    echo "\t             \t\t\t</div>\r\n\t             \t\t</div>\r\n\t             </div>\r\n          </div>\r\n     </div>\r\n     <!-- TAB CONTENT END -->\r\n       <!-- TAB CONTENT -->\r\n       <div id=\"tab4\" class=\"tab-single tab-cnt\">\r\n             <div class=\"datacenters\">\r\n                <div class=\"col-md-3\">\r\n                \t<div class=\"tabpanel\">\r\n                \t\t<strong>Rebuild Method</strong>\r\n\t                    <ul class=\"nav nav-pills nav-stacked\" role=\"tablist\">\r\n\t                    \t<li role=\"presentation\" class=\"active\"><a href=\"#manual\" aria-controls=\"manual\" role=\"tab\" data-toggle=\"pill\"><i class=\"fa fa-gears\"></i> Manual ISO</a></li>\r\n                        <li role=\"presentation\"><a href=\"#template\" aria-controls=\"template\" role=\"tab\" data-toggle=\"pill\"><i class=\"fa fa-copy\"></i> Automatic Template</a></li>\r\n\t                    </ul>\r\n\t                </div>\r\n\t            </div>\r\n\t            <div class=\"col-md-9\">\r\n\t            \t<div class=\"tabpanel\">\r\n\t            \t\t<div class=\"tab-content\">\r\n\t            \t\t\t<div role=\"tabpanel\" class=\"tab-pane active\" id=\"manual\">\r\n\t            \t\t\t\t<div class=\"row\">\r\n\t\t\t                        <div class=\"col-md-6\">\r\n\t\t\t                            <div id=\"man_info_box\"></div>\r\n\t\t\t                            <form role=\"form\">\r\n\t\t\t                                <div class=\"form-group\">\r\n\t\t\t                                    <label>Operating System ISO</label>\r\n\t\t\t                                    <select class=\"form-control\" id=\"man_os\">\r\n\t\t\t                                    \t<option value=\"default\">Select...</option>\r\n\t\t\t                                        ";
    $content = $db->get("vncp_kvm_isos", ["content", "=", "iso"])->all();
    $content_custom = $db->get("vncp_kvm_isos_custom", ["user_id", "=", $user->data()->id])->all();
    $location = $db->get("vncp_kvm_isos", ["id", "!=", 0])->first();
    list($location) = explode(":", $location->volid);
    for ($i = 0; $i < count($content_custom); $i++) {
        if ($content_custom[$i]->status == "active") {
            echo "<option value=\"" . $location . ":iso/" . $content_custom[$i]->upload_key . ".iso\">" . $content_custom[$i]->fname . "</option>";
        }
    }
    for ($i = 0; $i < count($content); $i++) {
        echo "<option value=\"" . $content[$i]->volid . "\">" . $content[$i]->friendly_name . "</option>";
    }
    echo "\t\t\t                                    </select>\r\n\t\t\t                                    <p class=\"help-block\" style=\"color:red;\" id=\"man_os_error\"></p>\r\n\t\t\t                                </div>\r\n\t\t\t                                <div class=\"form-group\">\r\n\t\t\t                                    <label>Hostname</label>\r\n\t\t\t                                    <input class=\"form-control\" type=\"text\" id=\"man_hostname\" autocomplete=\"off\" />\r\n\t\t\t                                    <p class=\"help-block\" style=\"color:red;\" id=\"man_hostname_error\"></p>\r\n\t\t\t                                </div>\r\n\t\t\t                                <input type=\"hidden\" id=\"man_aid\" value=\"";
    echo parse_input($data->hb_account_id);
    echo "\" />\r\n\t\t\t                                ";
    if ($data->suspended == 0) {
        echo "<input type=\"submit\" id=\"man_rebuild_btn\" value=\"Rebuild\" class=\"btn btn-info btn-lg\" />";
    } else {
        echo "<div class=\"btn btn-info btn-lg\" disabled>Rebuild</div>";
    }
    echo "\t\t\t                            </form>\r\n\t\t\t                        </div>\r\n\t\t\t                        <div class=\"col-md-6\">\r\n\t\t\t                            ";
    $result_blog = $db->limit_get_desc("vncp_users_rebuild_log", ["client_id", "=", $user->data()->id], "1");
    $data_blog = $result_blog->first();
    echo "\t\t\t                            <div class=\"alert alert-info\"><strong>Last recorded rebuild: </strong>";
    if (!empty($data_blog) && $data_blog->vmid == $clvmid) {
        echo parse_input($data_blog->date);
    } else {
        echo "none";
    }
    echo "<br /><br /></div>\r\n\t\t\t                        </div>\r\n\t\t\t                    </div>\r\n\t            \t\t\t</div>\r\n                    <div role=\"tabpanel\" class=\"tab-pane\" id=\"template\">\r\n\t            \t\t\t\t<div class=\"row\">\r\n\t\t\t                        <div class=\"col-md-6\">\r\n\t\t\t                            <div id=\"temp_info_box\"></div>\r\n\t\t\t                            <form role=\"form\">\r\n\t\t\t                                <div class=\"form-group\">\r\n\t\t\t                                    <label>Operating System Template</label>\r\n\t\t\t                                    <select class=\"form-control\" id=\"temp_os\">\r\n\t\t\t                                    \t<option value=\"default\">Select...</option>\r\n\t\t\t                                        ";
    $content = $db->get("vncp_kvm_templates", ["id", "!=", 0]);
    $contentr = $content->all();
    for ($i = 0; $i < count($contentr); $i++) {
        if ($contentr[$i]->node == $data->node) {
            echo "<option value=\"" . $contentr[$i]->id . "\">" . $contentr[$i]->friendly_name . "</option>";
        }
    }
    echo "\t\t\t                                    </select>\r\n\t\t\t                                    <p class=\"help-block\" style=\"color:red;\" id=\"temp_os_error\"></p>\r\n\t\t\t                                </div>\r\n\t\t\t                                <div class=\"form-group\">\r\n\t\t\t                                    <label>Hostname</label>\r\n\t\t\t                                    <input class=\"form-control\" type=\"text\" id=\"temp_hostname\" autocomplete=\"off\" />\r\n\t\t\t                                    <p class=\"help-block\" style=\"color:red;\" id=\"temp_hostname_error\"></p>\r\n\t\t\t                                </div>\r\n\t\t\t                                <input type=\"hidden\" id=\"temp_aid\" value=\"";
    echo parse_input($data->hb_account_id);
    echo "\" />\r\n\t\t\t                                ";
    if ($data->suspended == 0) {
        echo "<input type=\"submit\" id=\"temp_rebuild_btn\" value=\"Rebuild\" class=\"btn btn-info btn-lg\" />";
    } else {
        echo "<div class=\"btn btn-info btn-lg\" disabled>Rebuild</div>";
    }
    echo "\t\t\t                            </form>\r\n\t\t\t                        </div>\r\n\t\t\t                        <div class=\"col-md-6\">\r\n\t\t\t                            <div class=\"alert alert-info\"><strong>Last recorded rebuild: </strong>";
    if (!empty($data_blog) && $data_blog->vmid == $clvmid) {
        echo parse_input($data_blog->date);
    } else {
        echo "none";
    }
    echo "<br /><br /></div>\r\n\t\t\t                        </div>\r\n\t\t\t                    </div>\r\n\t            \t\t\t</div>\r\n\t            \t\t</div>\r\n\t            \t</div>\r\n\t            </div>\r\n            </div>\r\n            <div class=\"modal fade\" id=\"man_rebuild_modal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"man_rebuild_modalLabel\" aria-hidden=\"true\">\r\n            \t<div class=\"modal-dialog modal-lg\">\r\n            \t\t<div class=\"modal-content\">\r\n            \t\t\t<div class=\"modal-header\">\r\n            \t\t\t\t<h4 class=\"modal-title\" id=\"man_rebuild_modalLabel\">Rebuild in progress...</h4>\r\n            \t\t\t</div>\r\n            \t\t\t<div class=\"modal-body\">\r\n            \t\t\t\t<div class=\"progress\">\r\n            \t\t\t\t\t<div class=\"progress-bar progress-bar-info progress-bar-striped active man_rebuild_progress\" role=\"progressbar\" style=\"width:0%\"></div>\r\n            \t\t\t\t\t<br />\r\n            \t\t\t\t\t<br />\r\n            \t\t\t\t\t<div id=\"man_rebuild_output\"></div>\r\n            \t\t\t\t</div>\r\n            \t\t\t</div>\r\n            \t\t</div>\r\n            \t</div>\r\n            </div>\r\n       </div>\r\n       <!-- TAB CONTENT END -->\r\n       <!-- TAB CONTENT -->\r\n       <div id=\"tab7\" class=\"tab-single tab-cnt\">\r\n            <div class=\"datacenters\">\r\n                <div class=\"col-md-12\">\r\n                \t";
    $enable_backups = parse_input($db->get("vncp_settings", ["item", "=", "enable_backups"])->first()->value);
    if ($enable_backups == "true") {
        $maxbackups = (int) parse_input($db->get("vncp_settings", ["item", "=", "backup_limit"])->first()->value);
        $override = (int) parse_input($db->get("vncp_ct_backups", ["hb_account_id", "=", $data->hb_account_id])->first()->backuplimit);
        if (0 <= $override) {
            $maxbackups = $override;
        }
        if ($data->allow_backups == 1) {
            echo "                    <div id=\"backup_info\"></div>\r\n                    ";
            $backups = $pxAPI->get("/nodes/" . $data->node . "/storage/" . $node_data->backup_store . "/content");
            $scheduled = $pxAPI->get("/cluster/backup");
            $backupcount = 0;
            for ($i = 0; $i < count($backups); $i++) {
                $volid = explode("-", $backups[$i]["volid"]);
                if ($volid[1] == "qemu" && $volid[2] == (string) $vminfo["members"][0]["vmid"] && $backups[$i]["content"] == "backup") {
                    $backupcount++;
                }
            }
            $schedule = NULL;
            $i = 0;
            while ($i < count($scheduled)) {
                if ($scheduled[$i]["vmid"] == $vminfo["members"][0]["vmid"]) {
                    $schedule = $scheduled[$i];
                } else {
                    $i++;
                }
            }
            echo "                    <h4>Backup Usage: <span id=\"currentbackupcount\">";
            echo $backupcount . " used";
            echo "</span> of <span id=\"maxbackupcount\">";
            echo $maxbackups . " available";
            echo "</span></h4>\r\n                    <br />\r\n                    <div class=\"table-responsive\">\r\n                        <table class=\"table table-hover\" id=\"backuplist\">\r\n                          <tbody>\r\n                            <tr>\r\n                                <th>Name</th>\r\n                                <th>Size</th>\r\n                                <th></th>\r\n                                <th></th>\r\n                                <th></th>\r\n                            </tr>\r\n                            ";
            for ($i = 0; $i < count($backups); $i++) {
                $volid = explode("-", $backups[$i]["volid"]);
                if ($volid[1] == "qemu" && $volid[2] == (string) $vminfo["members"][0]["vmid"] && $backups[$i]["content"] == "backup") {
                    $bname = explode("/", $backups[$i]["volid"]);
                    echo "<tr>";
                    echo "<td>" . $bname[1] . "</td>";
                    echo "<td>" . get_size($backups[$i]["size"]) . "</td>";
                    echo "<td><button id=\"get_backup_config_" . ($i + 1) . "\" class=\"btn btn-sm btn-default\" content=\"" . $backups[$i]["volid"] . "\" data-toggle=\"modal\" data-target=\"#config_modal\">View Config</button></td>";
                    echo "<td><button id=\"restore_backup_" . ($i + 1) . "\" class=\"btn btn-sm btn-info\" content=\"" . $backups[$i]["volid"] . "\">Restore</button></td>";
                    echo "<td><button id=\"remove_backup_" . ($i + 1) . "\" class=\"btn btn-sm btn-danger\" content=\"" . $backups[$i]["volid"] . "\">Remove</button></td>";
                    echo "</tr>";
                }
            }
            echo "                          </tbody>\r\n                        </table>\r\n                    </div>\r\n                    <span id=\"countsection\">\r\n                    ";
            if ($backupcount < $maxbackups && $data->suspended == 0) {
                echo "<button type=\"button\" class=\"btn btn-md btn-success btn-block\" data-toggle=\"modal\" data-target=\"#backup_modal\">Create backup</button><br /><br />";
            } else {
                echo "<button type=\"button\" class=\"btn btn-md btn-warning btn-block\" disabled=\"disabled\" id=\"backup-warning\">Create backup</button><span id=\"backup-warning-2\">&nbsp;&nbsp;&nbsp;&nbsp;<small><em>Backup limit reached. Remove some old backups to create more.</em></small></span><br /><br />";
            }
            echo "                    </span>\r\n                    <br />\r\n                    ";
            $schtime = "";
            if ($schedule != NULL) {
                $schtime = $schedule["dow"] . " @ " . $schedule["starttime"];
            }
            echo "                    <h4>Scheduled Backup: ";
            if ($schtime != "") {
                echo $schtime;
            } else {
                echo "None";
            }
            echo "</h4>\r\n                    <br />\r\n                    ";
            if ($schedule == NULL) {
                echo "                    <form class=\"form-inline\" id=\"scheduled_form\">\r\n                      <div class=\"form-group\">\r\n                        <label>Day(s) of week: </label>\r\n                        <select multiple class=\"form-control\" id=\"scheduled_dow\">\r\n                          <option value=\"mon\">Monday</option>\r\n                          <option value=\"tue\">Tuesday</option>\r\n                          <option value=\"wed\">Wednesday</option>\r\n                          <option value=\"thu\">Thursday</option>\r\n                          <option value=\"fri\">Friday</option>\r\n                          <option value=\"sat\">Saturday</option>\r\n                          <option value=\"sun\">Sunday</option>\r\n                        </select>\r\n                      </div>\r\n                      <div class=\"form-group\">\r\n                        <label>Time: </label>\r\n                        <input type=\"time\" class=\"form-control\" id=\"scheduled_time\" />\r\n                      </div>\r\n                      <button type=\"submit\" class=\"btn btn-md btn-success\" id=\"scheduled_submit\">Create scheduled backup</button><br /><br />\r\n                    </form>\r\n                  ";
            } else {
                echo "                    <form class=\"form-inline\" id=\"schdelete_form\">\r\n                      <input type=\"hidden\" value=\"";
                echo $schedule["id"];
                echo "\" id=\"schid\" />\r\n                      <button type=\"submit\" class=\"btn btn-md btn-danger\" id=\"schdelete_submit\">Delete scheduled backup</button><br /><br />\r\n                    </form>\r\n                  ";
            }
            echo "                    <div class=\"modal fade\" id=\"backup_modal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"backup_modalLabel\" aria-hidden=\"true\">\r\n                    \t<div class=\"modal-dialog\">\r\n                    \t\t<div class=\"modal-content\">\r\n                    \t\t\t<div class=\"modal-header\">\r\n                    \t\t\t\t<button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">&times;</span></button>\r\n                    \t\t\t\t<h4 class=\"modal-title\" id=\"backup_modalLabel\">Backup Confirmation</h4>\r\n                    \t\t\t</div>\r\n                    \t\t\t<div class=\"modal-body\">\r\n                    \t\t\t\t<p>Taking a backup on your KVM VPS will put your VPS in a suspended state and compress all VPS data into a backup file. This is a fast process that will cause minimal interruption, but <strong>your VPS will not be accessible while suspended.</strong> Once the backup finishes, your VPS will be available again.</p>\r\n                    \t\t\t\t<br />\r\n                            <form role=\"form\">\r\n            \t\t\t\t    <div class=\"form-group\">\r\n            \t\t\t\t        <label>Email Notification</label>\r\n            \t\t\t\t        <select class=\"form-control\" id=\"notification\">\r\n            \t\t\t\t        \t<option value=\"no\">No</option>\r\n            \t\t\t\t        \t<option value=\"yes\">Yes</option>\r\n            \t\t\t\t        </select>\r\n            \t\t\t\t        <p class=\"help-block\">Do you want to receive an email notification when the backup job finishes? The email will be sent to the email registered with your <a href=\"profile\">";
            echo $appname;
            echo " account</a>.</p>\r\n            \t\t\t\t    </div>\r\n            \t\t\t\t    <input type=\"hidden\" id=\"backup_aid\" value=\"";
            echo parse_input($data->hb_account_id);
            echo "\" />\r\n            \t\t\t\t</form>\r\n                    \t\t\t\t<div id=\"backup_message\" style=\"color: green;\"></div>\r\n                    \t\t\t</div>\r\n                    \t\t\t<div class=\"modal-footer\">\r\n                    \t\t\t\t<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\" id=\"cancel_backup\">Cancel</button>\r\n            \t\t\t\t        <button type=\"button\" class=\"btn btn-primary\" id=\"create_backup\">Confirm Backup</button>\r\n                    \t\t\t</div>\r\n                    \t\t</div>\r\n                    \t</div>\r\n                    </div>\r\n                    <div class=\"modal fade\" id=\"config_modal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"config_modalLabel\" aria-hidden=\"true\">\r\n                      <div class=\"modal-dialog\">\r\n                        <div class=\"modal-content\">\r\n                          <div class=\"modal-header\">\r\n                            <button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">&times;</span></button>\r\n                            <h4 class=\"modal-title\" id=\"config_modalLabel\">Stored Configuration</h4>\r\n                          </div>\r\n                          <div class=\"modal-body\">\r\n                            <h5 id=\"confheader\"></h5>\r\n                            <div class=\"well well-sm\" id=\"confwell\">\r\n                              <i class=\"fa fa-cog fa-spin\"></i> Pulling configuration, please wait...\r\n                            </div>\r\n                          </div>\r\n                          <div class=\"modal-footer\">\r\n                            <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>\r\n                          </div>\r\n                        </div>\r\n                      </div>\r\n                    </div>\r\n                    <div class=\"modal fade\" id=\"restore_modal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"restore_modalLabel\" aria-hidden=\"true\">\r\n                    \t<div class=\"modal-dialog modal-lg\">\r\n                    \t\t<div class=\"modal-content\">\r\n                    \t\t\t<div class=\"modal-header\">\r\n                    \t\t\t\t<h4 class=\"modal-title\" id=\"restore_modalLabel\">Restore in progress...</h4>\r\n                    \t\t\t</div>\r\n                    \t\t\t<div class=\"modal-body\">\r\n                    \t\t\t\t<div class=\"progress\">\r\n                    \t\t\t\t\t<div class=\"progress-bar progress-bar-info progress-bar-striped active restore_progress\" role=\"progressbar\" style=\"width:0%\"></div>\r\n                    \t\t\t\t\t<br />\r\n                    \t\t\t\t\t<br />\r\n                    \t\t\t\t\t<div id=\"restore_output\"></div>\r\n                    \t\t\t\t</div>\r\n                    \t\t\t</div>\r\n                    \t\t</div>\r\n                    \t</div>\r\n                    </div>\r\n                    ";
        } else {
            echo "Backups are not enabled on this VPS.";
        }
    } else {
        echo "Backups are not enabled here.";
    }
    echo "                </div>\r\n            </div>\r\n       </div>\r\n       <!-- TAB CONTENT END -->\r\n\t     <!-- TAB CONTENT -->\r\n\t     <div id=\"tab8\" class=\"tab-single tab-cnt\">\r\n\t           <div class=\"datacenters\">\r\n\t           \t<div class=\"row\">\r\n\t\t               <div class=\"col-md-12\">\r\n\t\t                   <h4>Click the button below to open a new console session for this VM. This console should only be used if you are unable to access your VM from the Internet. This console is not designed to be used for regular usage.</h4>\r\n\t\t                   <br />\r\n\t\t\t               \t";
    if ($data->suspended == 0) {
        echo "<button class=\"btn btn-info btn-lg\" id=\"kvmconsole\" role=\"" . parse_input($_GET["id"]) . "\">Open VNC console</button>";
    } else {
        echo "<button class=\"btn btn-info btn-lg\" disabled>Open VNC console</button>";
    }
    echo "\t\t               </div>\r\n\t              </div>\r\n\t          </div>\r\n\t     </div>\r\n\t     <!-- TAB CONTENT END -->\r\n      </div>\r\n     </div>\r\n    </section>\r\n    <!-- /Feature with tabs -->\r\n</div>\r\n";
} else {
    $clone_data = json_decode($clone_pending->data);
    if (is_array($clone_data->gateway)) {
        $upid = $clone_pending->upid;
        $results = $db->get("vncp_kvm_ct", ["hb_account_id", "=", $clone_pending->hb_account_id]);
        $data = $results->first();
        $node_results = $db->get("vncp_nodes", ["name", "=", $data->node]);
        $node_data = $node_results->first();
        $pxAPI = new PVE2_API($node_data->hostname, $node_data->username, $node_data->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($node_data->password));
        $noLogin = false;
        if (!$pxAPI->login()) {
            $noLogin = true;
        }
        if (!$noLogin) {
            $task_status = $pxAPI->get("/nodes/" . $data->node . "/tasks/" . $upid . "/status");
            if ($task_status["exitstatus"] == "OK" && $task_status["status"] == "stopped") {
                $getcloneconfig = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clone_data->vmid . "/config");
                list($net0) = explode("=", $getcloneconfig["net0"]);
                $getGW = $db->get("vncp_dhcp", ["ip", "=", $clone_data->ip])->all();
                $getCIDR = (string) _obfuscated_0D3C06053E050D19121536140D3207060A0D5C032E1701_($getGW[0]->netmask);
                $newvm = ["cores" => (int) $clone_data->cores, "cpu" => $clone_data->cpu, "memory" => (int) $clone_data->memory, "cipassword" => _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($clone_data->cipassword), "ipconfig0" => "gw=" . $getGW[0]->gateway . ",ip=" . $getGW[0]->ip . "/" . $getCIDR];
                $cloneBridge = "vmbr0";
                $isNATClone = $db->get("vncp_natforwarding", ["hb_account_id", "=", $clone_pending->hb_account_id])->all();
                if (count($isNATClone) == 1) {
                    $cloneBridge = "vmbr10";
                }
                if (isset($clone_data->portspeed) && 0 < (int) $clone_data->portspeed) {
                    if (array_key_exists("setmacaddress", $clone_data) && !empty($clone_data->setmacaddress)) {
                        $newvm["net0"] = $net0 . "=" . $clone_data->setmacaddress[0] . ",bridge=" . $cloneBridge . ",rate=" . (string) parse_input($clone_data->portspeed);
                    } else {
                        $newvm["net0"] = $net0 . "=" . $clone_data->gateway[0] . ",bridge=" . $cloneBridge . ",rate=" . (string) parse_input($clone_data->portspeed);
                    }
                } else {
                    if (array_key_exists("setmacaddress", $clone_data) && !empty($clone_data->setmacaddress)) {
                        $newvm["net0"] = $net0 . "=" . $clone_data->setmacaddress[0] . ",bridge=" . $cloneBridge;
                    } else {
                        $newvm["net0"] = $net0 . "=" . $clone_data->gateway[0] . ",bridge=" . $cloneBridge;
                    }
                }
                if (array_key_exists("vlantag", $clone_data) && isset($clone_data->vlantag) && 0 < (int) $clone_data->vlantag) {
                    $newvm["net0"] = $newvm["net0"] . ",tag=" . (string) $clone_data->vlantag;
                }
                if (is_array($clone_data->netmask)) {
                    $newvm["net1"] = "e1000=" . $clone_data->netmask[0] . ",bridge=vmbr1";
                }
                if (array_key_exists("net10", $clone_data) && is_array($clone_data->net10)) {
                    $newvm["net10"] = "e1000=" . $clone_data->net10[0] . ",bridge=vmbr0";
                }
                $setnewparams = $pxAPI->post("/nodes/" . $data->node . "/qemu/" . $clone_data->vmid . "/config", $newvm);
                sleep(1);
                if ($clone_data->cvmtype == "linux" && 8 < (int) $clone_data->storage_size) {
                    $newdisk = ["size" => (int) $clone_data->storage_size . "G", "disk" => "scsi0"];
                    $setnewdisk = $pxAPI->put("/nodes/" . $data->node . "/qemu/" . $clone_data->vmid . "/resize", $newdisk);
                    sleep(1);
                } else {
                    if ($clone_data->cvmtype == "windows" && 15 < (int) $clone_data->storage_size) {
                        $newdisk = ["size" => (int) $clone_data->storage_size . "G", "disk" => "ide0"];
                        $setnewdisk = $pxAPI->put("/nodes/" . $data->node . "/qemu/" . $clone_data->vmid . "/resize", $newdisk);
                        sleep(1);
                    }
                }
                $db->delete("vncp_pending_clone", ["hb_account_id", "=", $clone_pending->hb_account_id]);
                $db->updatevm_aid("vncp_kvm_ct", $clone_pending->hb_account_id, ["from_template" => 2]);
                $log->log("User completed setup of cloned VM " . $clone_pending->hb_account_id, "general", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                echo "<div style=\"visibility:hidden;\" id=\"template_setup_div\"></div>";
            } else {
                echo "Please check back in a few minutes. This virtual machine is still being setup.";
            }
        }
    } else {
        $upid = $clone_pending->upid;
        $results = $db->get("vncp_kvm_ct", ["hb_account_id", "=", $clone_pending->hb_account_id]);
        $data = $results->first();
        $node_results = $db->get("vncp_nodes", ["name", "=", $data->node]);
        $node_data = $node_results->first();
        $pxAPI = new PVE2_API($node_data->hostname, $node_data->username, $node_data->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($node_data->password));
        $noLogin = false;
        if (!$pxAPI->login()) {
            $noLogin = true;
        }
        if (!$noLogin) {
            $task_status = $pxAPI->get("/nodes/" . $data->node . "/tasks/" . $upid . "/status");
            if ($task_status["exitstatus"] == "OK" && $task_status["status"] == "stopped") {
                $getcloneconfig = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $clone_data->vmid . "/config");
                list($saved_macaddr) = explode("=", $getcloneconfig["net0"]);
                list($saved_macaddr) = explode(",", $saved_macaddr);
                $getCIDR = (string) _obfuscated_0D3C06053E050D19121536140D3207060A0D5C032E1701_($clone_data->netmask);
                $newconf = ["cores" => (int) $clone_data->cores, "cpu" => $clone_data->cpu, "memory" => (int) $clone_data->memory, "cipassword" => _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($clone_data->cipassword), "ipconfig0" => "gw=" . $clone_data->gateway . ",ip=" . $clone_data->ip . "/" . $getCIDR];
                $cloneBridge = "vmbr0";
                $isNATClone = $db->get("vncp_natforwarding", ["hb_account_id", "=", $clone_pending->hb_account_id])->all();
                if (count($isNATClone) == 1) {
                    $cloneBridge = "vmbr10";
                }
                list($netdriver) = explode("=", $getcloneconfig["net0"]);
                if (isset($clone_data->portspeed) && 0 < (int) $clone_data->portspeed) {
                    if (array_key_exists("setmacaddress", $clone_data) && !empty($clone_data->setmacaddress)) {
                        $newconf["net0"] = $netdriver . "=" . $clone_data->setmacaddress . ",bridge=" . $cloneBridge . ",rate=" . (string) parse_input($clone_data->portspeed);
                    } else {
                        $newconf["net0"] = $netdriver . "=" . $saved_macaddr . ",bridge=" . $cloneBridge . ",rate=" . (string) parse_input($clone_data->portspeed);
                    }
                } else {
                    if (array_key_exists("setmacaddress", $clone_data) && !empty($clone_data->setmacaddress)) {
                        $newconf["net0"] = $netdriver . "=" . $clone_data->setmacaddress . ",bridge=" . $cloneBridge;
                    } else {
                        $newconf["net0"] = $netdriver . "=" . $saved_macaddr . ",bridge=" . $cloneBridge;
                    }
                }
                if (array_key_exists("vlantag", $clone_data) && isset($clone_data->vlantag) && 0 < (int) $clone_data->vlantag) {
                    $newconf["net0"] = $newconf["net0"] . ",tag=" . (string) $clone_data->vlantag;
                }
                if (array_key_exists("net10", $clone_data) && is_array($clone_data->net10)) {
                    $newconf["net10"] = "e1000=" . $clone_data->net10[0] . ",bridge=vmbr0";
                }
                $setnewparams = $pxAPI->post("/nodes/" . $data->node . "/qemu/" . $clone_data->vmid . "/config", $newconf);
                sleep(1);
                if ($clone_data->cvmtype == "linux" && 8 < (int) $clone_data->storage_size) {
                    $newdisk = ["size" => (int) $clone_data->storage_size . "G", "disk" => "scsi0"];
                    $setnewdisk = $pxAPI->put("/nodes/" . $data->node . "/qemu/" . $clone_data->vmid . "/resize", $newdisk);
                    sleep(1);
                } else {
                    if ($clone_data->cvmtype == "windows" && 15 < (int) $clone_data->storage_size) {
                        $newdisk = ["size" => (int) $clone_data->storage_size . "G", "disk" => "ide0"];
                        $setnewdisk = $pxAPI->put("/nodes/" . $data->node . "/qemu/" . $clone_data->vmid . "/resize", $newdisk);
                        sleep(1);
                    }
                }
                $saved_network = explode(".", $clone_data->gateway);
                $saved_dhcp = $saved_network[0] . "." . $saved_network[1] . "." . $saved_network[2] . "." . (string) ((int) $saved_network[3] - 1);
                $db->insert("vncp_dhcp", ["mac_address" => $saved_macaddr, "ip" => $clone_data->ip, "gateway" => $clone_data->gateway, "netmask" => $clone_data->netmask, "network" => $saved_dhcp, "type" => 0]);
                $fulldhcp = $db->get("vncp_dhcp", ["network", "=", $saved_dhcp])->all();
                if ($dhcp_server = $db->get("vncp_dhcp_servers", ["dhcp_network", "=", $saved_dhcp])->first()) {
                    $ssh = new SSH2($dhcp_server->hostname, (int) $dhcp_server->port);
                    if (!$ssh->login("root", _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($dhcp_server->password))) {
                        $log->log("Could not SSH to DHCP server " . $dhcp_server->hostname, "error", 1, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                    } else {
                        $ssh->exec("printf 'ddns-update-style none;\n\n' > /root/dhcpd.test");
                        $ssh->exec("printf 'option domain-name-servers 8.8.8.8, 8.8.4.4;\n\n' >> /root/dhcpd.test");
                        $ssh->exec("printf 'default-lease-time 7200;\n' >> /root/dhcpd.test");
                        $ssh->exec("printf 'max-lease-time 86400;\n\n' >> /root/dhcpd.test");
                        $ssh->exec("printf 'log-facility local7;\n\n' >> /root/dhcpd.test");
                        $ssh->exec("printf 'subnet " . $saved_dhcp . " netmask " . $fulldhcp[0]->netmask . " {}\n\n' >> /root/dhcpd.test");
                        for ($i = 0; $i < count($fulldhcp); $i++) {
                            $ssh->exec("printf 'host " . $fulldhcp[$i]->id . " {hardware ethernet " . $fulldhcp[$i]->mac_address . ";fixed-address " . $fulldhcp[$i]->ip . ";option routers " . $fulldhcp[$i]->gateway . ";}\n' >> /root/dhcpd.test");
                        }
                        $ssh->exec("mv /root/dhcpd.test /etc/dhcp/dhcpd.conf && rm /root/dhcpd.test");
                        $ssh->exec("service isc-dhcp-server restart");
                        $ssh->disconnect();
                    }
                } else {
                    $log->log("No DHCP server exists for " . $saved_dhcp, "error", 1, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                }
                $db->delete("vncp_pending_clone", ["hb_account_id", "=", $clone_pending->hb_account_id]);
                $db->updatevm_aid("vncp_kvm_ct", $clone_pending->hb_account_id, ["from_template" => 2]);
                $log->log("User completed setup of cloned VM " . $clone_pending->hb_account_id, "general", 0, $user->data()->username, $_SERVER["REMOTE_ADDR"]);
                echo "<div style=\"visibility:hidden;\" id=\"template_setup_div\"></div>";
            } else {
                echo "Please check back in a few minutes. This virtual machine is still being setup.";
            }
        }
    }
}
echo "<input type=\"hidden\" value=\"";
echo $data->hb_account_id;
echo "\" id=\"kvminfo\" />\r\n";

?>
