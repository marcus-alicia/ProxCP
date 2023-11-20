<?php

if (!defined("constant")) {
    header("Location: index");
}
echo "<div class=\"col-md-12\">\r\n\t<!-- Feature with tabs -->\r\n    <section class=\"feature-tabs\">\r\n     <div class=\"first-sliding\">\r\n     </div>\r\n     <div class=\"wrap\">\r\n      <div class=\"tabs\">\r\n       <div class=\"tabs_container\">\r\n       <!-- TAB -->\r\n        <li class=\"btnLink smallBtnLink green_tab\">\r\n            <a class=\"tab-action active\" data-tab-cnt=\"tab1\">\r\n                <span><i class=\"fa fa-wrench\"></i> General</span>\r\n            </a></li>\r\n        <!-- TAB END -->\r\n        <!-- TAB -->\r\n        <li class=\"btnLink smallBtnLink green_tab\">\r\n            <a class=\"tab-action\" data-tab-cnt=\"tab4\">\r\n                <span><i class=\"fa fa-desktop\"></i> Device Manager</span>\r\n            </a></li>\r\n        <!-- TAB END -->\r\n        <!-- TAB -->\r\n        <li class=\"btnLink smallBtnLink green_tab\">\r\n            <a class=\"tab-action\" data-tab-cnt=\"tab7\">\r\n                <span><i class=\"fa fa-bolt\"></i> Network Manager</span>\r\n            </a></li>\r\n        <!-- TAB END -->\r\n        <!-- TAB -->\r\n        <li class=\"btnLink smallBtnLink green_tab\">\r\n            <a class=\"tab-action\" data-tab-cnt=\"tab2\">\r\n                <span><i class=\"fa fa-area-chart\"></i> Resource Graphs</span>\r\n            </a></li>\r\n        <!-- TAB END -->\r\n        <!-- TAB -->\r\n        <li class=\"btnLink smallBtnLink green_tab\">\r\n            <a class=\"tab-action\" data-tab-cnt=\"tab3\">\r\n                <span><i class=\"fa fa-cubes\"></i> Rebuild</span>\r\n            </a></li>\r\n        <!-- TAB END -->\r\n        <!-- TAB -->\r\n        <li class=\"btnLink smallBtnLink green_tab\">\r\n            <a class=\"tab-action\" data-tab-cnt=\"tab5\">\r\n                <span><i class=\"fa fa-hdd-o\"></i> Backups</span>\r\n            </a></li>\r\n        <!-- TAB END -->\r\n        <!-- TAB -->\r\n        <li class=\"btnLink smallBtnLink green_tab\">\r\n            <a class=\"tab-action\" data-tab-cnt=\"tab6\">\r\n                <span><i class=\"fa fa-terminal\"></i> Console</span>\r\n            </a></li>\r\n        <!-- TAB END -->\r\n       </div>\r\n       <br>\r\n       <div class=\"clr\"></div>\r\n       <!-- TAB CONTENT -->\r\n       <div id=\"tab1\" class=\"tab-single tab-cnt active\">\r\n            <div class=\"datacenters\">\r\n                <div class=\"col-md-12\">\r\n\t\t            <div id=\"func_error\"></div>\r\n\t\t            <h1 align=\"center\" id=\"status_1\">Server Status: <span class=\"label\" id=\"status_2\"><img src=\"img/loader.GIF\" id=\"loader\" /></span></h1>\r\n\t\t            ";
$results = $db->get("vncp_lxc_ct", ["hb_account_id", "=", parse_input($_GET["id"])]);
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
    $vmdetails = $pxAPI->get("/nodes/" . $data->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/status/current");
}
echo "\t\t            <br />\r\n\t\t            <div class=\"row\">\r\n\t\t            \t<div class=\"col-md-12\">\r\n\t\t            \t\t<div class=\"panel panel-default\">\r\n\t\t            \t\t\t<div class=\"panel-body\">\r\n\t\t            \t\t\t\t<div class=\"col-md-2\"><p><em>CPU Usage</em></p></div>\r\n\t\t            \t\t\t\t<div class=\"progress\">\r\n\t\t            \t\t\t\t\t<div class=\"progress-bar progress-bar-info progress-bar-striped\" role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"min-width: 2em;width: 100%;\" id=\"cpu_usage_1\"><div id=\"cpu_usage_2\"></div></div>\r\n\t\t            \t\t\t\t</div>\r\n\t\t            \t\t\t\t<div class=\"col-md-2\"><p><em>RAM Usage</em></p></div>\r\n\t\t            \t\t\t\t<div class=\"progress\">\r\n\t\t            \t\t\t\t\t<div class=\"progress-bar progress-bar-info progress-bar-striped\" role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"min-width: 2em;width: 100%;\" id=\"ram_usage_1\"><div id=\"ram_usage_2\"></div></div>\r\n\t\t            \t\t\t\t</div>\r\n\t\t            \t\t\t\t<div class=\"col-md-2\"><p><em>Disk Usage</em></p></div>\r\n\t\t            \t\t\t\t<div class=\"progress\">\r\n\t\t            \t\t\t\t\t<div class=\"progress-bar progress-bar-info progress-bar-striped\" role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"min-width: 2em;width: 100%;\" id=\"disk_usage_1\"><div id=\"disk_usage_2\"></div></div>\r\n\t\t            \t\t\t\t</div>\r\n\t\t            \t\t\t\t<div class=\"col-md-2\"><p><em>Swap Usage</em></p></div>\r\n\t\t            \t\t\t\t<div class=\"progress\">\r\n\t\t            \t\t\t\t\t<div class=\"progress-bar progress-bar-info progress-bar-striped\" role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"min-width: 2em;width: 100%;\" id=\"swap_usage_1\"><div id=\"swap_usage_2\"></div></div>\r\n\t\t            \t\t\t\t</div>\r\n                        ";
$bwstats = $db->get("vncp_bandwidth_monitor", ["hb_account_id", "=", parse_input($_GET["id"])])->first();
$bwperc = round((double) $bwstats->current / (double) $bwstats->max * 100, 2);
$bwperc_single = round($bwperc, 0);
echo "                        <div class=\"col-md-2\"><p><em>Bandwidth Usage</em></p></div>\r\n\t\t            \t\t\t\t<div class=\"progress\">\r\n\t\t            \t\t\t\t\t<div class=\"progress-bar progress-bar-info progress-bar-striped\" role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"min-width: 2em;width: ";
echo $bwperc_single;
echo "%;\"><div>";
echo $bwperc;
echo "%</div></div>\r\n\t\t            \t\t\t\t</div>\r\n                        <div class=\"col-md-2\"><p><em>Total Storage</em></p></div>\r\n                        <div>\r\n                          ";
echo ceil((int) $vmdetails["maxdisk"] / 1073741824);
echo "GB\r\n                        </div>\r\n\t\t            \t\t\t</div>\r\n\t\t            \t\t</div>\r\n\t\t                </div>\r\n\t\t            </div>\r\n\t\t            <div class=\"row\">\r\n\t\t            \t<div class=\"col-md-3\">\r\n\t\t            \t\t";
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
echo "\t\t            \t</div>\r\n\t\t            \t\t<div class=\"col-md-3\">\r\n\t\t            \t\t\t";
if ($data->suspended == 0) {
    echo "<button class=\"btn btn-block btn-lg btn-warning\" disabled=\"disabled\" id=\"restart_server\">\r\n\t\t            \t            <center>\r\n\t\t            \t                <i class=\"fa fa-refresh fa-4x\"></i>\r\n\t\t            \t                <div>Restart Server</div>\r\n\t\t            \t            </center>\r\n\t\t            \t        </button>";
} else {
    echo "<button class=\"btn btn-block btn-lg btn-warning\" disabled=\"disabled\">\r\n\t\t            \t            <center>\r\n\t\t            \t                <i class=\"fa fa-refresh fa-4x\"></i>\r\n\t\t            \t                <div>Restart Server</div>\r\n\t\t            \t            </center>\r\n\t\t            \t        </button>";
}
echo "\t\t            \t\t</div>\r\n\t\t            \t<div class=\"col-md-3\">\r\n\t\t            \t\t";
if ($data->suspended == 0) {
    echo "<button class=\"btn btn-block btn-lg btn-info\" disabled=\"disabled\" id=\"kill_server\">\r\n\t\t                        <center>\r\n\t\t                            <i class=\"fa fa-times fa-4x\"></i>\r\n\t\t                            <div>Kill Server</div>\r\n\t\t                        </center>\r\n\t\t                    </button>";
} else {
    echo "<button class=\"btn btn-block btn-lg btn-info\" disabled=\"disabled\">\r\n\t\t                        <center>\r\n\t\t                            <i class=\"fa fa-times fa-4x\"></i>\r\n\t\t                            <div>Kill Server</div>\r\n\t\t                        </center>\r\n\t\t                    </button>";
}
echo "\t\t            \t</div>\r\n\t\t            </div>\r\n\t\t            <br />\r\n                ";
$LXCNAT = count($db->get("vncp_natforwarding", ["hb_account_id", "=", $data->hb_account_id])->all());
echo "\t\t            <div class=\"row\">\r\n\t\t            \t<div class=\"col-md-6 col-md-offset-3\">\r\n\t\t                    <div class=\"table-responsive\">\r\n\t\t                        <table class=\"table table-striped\">\r\n\t\t                            <tr>\r\n\t\t                                <td>Uptime</td>\r\n\t\t                                <td id=\"uptime\">0 seconds</td>\r\n\t\t                            </tr>\r\n\t\t                            <tr>\r\n\t\t                                <td>Hostname</td>\r\n\t\t                                <td>";
echo $vmdetails["name"];
echo " ( LXC )</td>\r\n\t\t                            </tr>\r\n\t\t                            <tr>\r\n\t\t                                <td>Primary IP</td>\r\n\t\t                                <td>";
echo $data->ip;
echo "</td>\r\n\t\t                            </tr>\r\n                                ";
if ($LXCNAT == 1) {
    echo "                                <tr>\r\n                                  <td>Public IP</td>\r\n                                  <td>";
    $natnode = $db->get("vncp_nat", ["node", "=", $data->node])->all();
    if (count($natnode) == 1) {
        echo $natnode[0]->publicip;
    } else {
        echo "Error";
    }
    echo "</td>\r\n                                </tr>\r\n                                ";
}
echo "\t\t                            <tr>\r\n\t\t                                <td>Operating System</td>\r\n\t\t                                <td>";
echo $data->os;
echo "</td>\r\n\t\t                            </tr>\r\n\t\t                            <tr>\r\n\t\t                            \t<td>VPS Node</td>\r\n\t\t                            \t<td>";
echo $data->node;
echo "</td>\r\n\t\t                            </tr>\r\n\t\t                        </table>\r\n\t\t                    </div>\r\n\t\t                </div>\r\n\t\t            </div>\r\n                </div>\r\n            </div>\r\n       </div>\r\n       <!-- TAB CONTENT END -->\r\n        <!-- TAB CONTENT -->\r\n        <div id=\"tab4\" class=\"tab-single tab-cnt\">\r\n              <div class=\"datacenters\">\r\n                 <div class=\"col-md-12\">\r\n                    <div id=\"lxc_pwsuccess\"></div>\r\n                     <div class=\"table-responsive\">\r\n                       <table class=\"table table-striped\">\r\n                         <tr>\r\n                           <th>Option</th>\r\n                           <th>Description</th>\r\n                           <th>Manage</th>\r\n                         </tr>\r\n                         <tr>\r\n                           <td>TUN/TAP</td>\r\n                           <td>Enable or disable the tun/tap device on your VPS. Useful for some VPN software.</td>\r\n                           ";
if ($data->tuntap == 0 && $data->suspended == 0) {
    echo "<td><button class=\"btn btn-success btn-sm\" id=\"enabletap\">Enable</button></td>";
} else {
    if ($data->tuntap == 1 && $data->suspended == 0) {
        echo "<td><button class=\"btn btn-danger btn-sm\" id=\"disabletap\">Disable</button></td>";
    } else {
        echo "<td><button class=\"btn btn-warning btn-sm\" disabled>Service suspended</button></td>";
    }
}
echo "                         </tr>\r\n                         <tr>\r\n                           <td>Start at boot</td>\r\n                           <td>Enable or disable starting your VPS at node boot.</td>\r\n                           ";
if ($data->onboot == 0 && $data->suspended == 0) {
    echo "<td><button class=\"btn btn-success btn-sm\" id=\"enableonboot\">Enable</button></td>";
} else {
    if ($data->onboot == 1 && $data->suspended == 0) {
        echo "<td><button class=\"btn btn-danger btn-sm\" id=\"disableonboot\">Disable</button></td>";
    } else {
        echo "<td><button class=\"btn btn-warning btn-sm\" disabled>Service suspended</button></td>";
    }
}
echo "                         </tr>\r\n                         <tr>\r\n                           <td>Quotas</td>\r\n                           <td>Enable or disable disk quotas on your VPS.</td>\r\n                           ";
if ($data->quotas == 0 && $data->suspended == 0) {
    echo "<td><button class=\"btn btn-success btn-sm\" id=\"enablequotas\">Enable</button></td>";
} else {
    if ($data->quotas == 1 && $data->suspended == 0) {
        echo "<td><button class=\"btn btn-danger btn-sm\" id=\"disablequotas\">Disable</button></td>";
    } else {
        echo "<td><button class=\"btn btn-warning btn-sm\" disabled>Service suspended</button></td>";
    }
}
echo "                         </tr>\r\n                         <tr>\r\n                           <td>VPS Task Log</td>\r\n                           <td><textarea class=\"form-control\" id=\"vmlog\" rows=\"10\" style=\"resize:none;font-family: Menlo,Monaco,Consolas,monospace;font-size:13px;\" disabled>null</textarea></td>\r\n                           <td><button class=\"btn btn-info btn-sm\" id=\"getvmlog\">Fetch Log</button><br /><br /><button class=\"btn btn-info btn-sm\" id=\"clearvmlog\">Clear Log</button></td>\r\n                         </tr>\r\n                         <tr>\r\n                           <td>Change Root Password</td>\r\n                           <td>Forgot your root password? Change it here.</td>\r\n                           ";
if ($data->suspended == 0) {
    echo "<td><button class=\"btn btn-success btn-sm\" id=\"chgrootpw_lxc\">Change Password</button></td>";
} else {
    echo "<td><button class=\"btn btn-warning btn-sm\" disabled>Service suspended</button></td>";
}
echo "                         </tr>\r\n                       </table>\r\n                     </div>\r\n                 </div>\r\n             </div>\r\n        </div>\r\n        <!-- TAB CONTENT END -->\r\n        <!-- TAB CONTENT -->\r\n        ";
$portforwards = $db->get("vncp_natforwarding", ["hb_account_id", "=", $data->hb_account_id])->all();
if (count($portforwards) == 1) {
    $portlimit = $portforwards[0]->avail_ports;
    $domainlimit = $portforwards[0]->avail_domains;
    $ports = (string) (count(explode(";", $portforwards[0]->ports)) - 1);
    $domains = (string) (count(explode(";", $portforwards[0]->domains)) - 1);
}
echo "        <div id=\"tab7\" class=\"tab-single tab-cnt\">\r\n              <div class=\"datacenters\">\r\n                <div class=\"col-md-3\">\r\n                \t<div class=\"tabpanel\">\r\n\t                    <ul class=\"nav nav-pills nav-stacked\" role=\"tablist\">\r\n\t                    \t<li role=\"presentation\" class=\"active\"><a href=\"#ipam\" aria-controls=\"ipam\" role=\"tab\" data-toggle=\"pill\"><i class=\"fa fa-location-arrow\"></i> IPAM</a></li>\r\n                        ";
if ($LXCNAT == 1) {
    echo "                        <li role=\"presentation\"><a href=\"#natports\" aria-controls=\"natports\" role=\"tab\" data-toggle=\"pill\"><i class=\"fa fa-arrows-h\"></i> NAT Ports (";
    echo $ports;
    echo " / ";
    echo $portlimit;
    echo ")</a></li>\r\n                        <li role=\"presentation\"><a href=\"#natdomains\" aria-controls=\"natdomains\" role=\"tab\" data-toggle=\"pill\"><i class=\"fa fa-globe\"></i> NAT Domains (";
    echo $domains;
    echo " / ";
    echo $domainlimit;
    echo ")</a></li>\r\n                        ";
}
echo "\t                    </ul>\r\n\t                </div>\r\n\t               </div>\r\n                 <div class=\"col-md-9\">\r\n                   <div class=\"tabpanel\">\r\n                     <div class=\"tab-content\">\r\n                       <div role=\"tabpanel\" class=\"tab-pane active\" id=\"ipam\">\r\n                         ";
$ip_values = $db->get("vncp_dhcp", ["ip", "=", $data->ip])->first();
echo "                           <h2 align=\"center\">Public Network</h2>\r\n                           <div class=\"table-responsive\">\r\n                            <table class=\"table table-striped\">\r\n                              <tr>\r\n                                <th>IPv4 Address</th>\r\n                                <th>Gateway</th>\r\n                                <th>Netmask</th>\r\n                                <th>Assignment</th>\r\n                              </tr>\r\n                              <tr>\r\n                                <td>";
echo $data->ip;
echo "</td>\r\n                                <td>";
echo $ip_values->gateway;
echo "</td>\r\n                                <td>";
echo $ip_values->netmask;
echo "</td>\r\n                                <td>";
if ($ip_values->type == 0) {
    echo "primary";
} else {
    echo "secondary";
}
echo "</td>\r\n                              </tr>\r\n                            </table>\r\n                           </div>\r\n                           <br />\r\n                           ";
$vm_ipv6 = parse_input($db->get("vncp_settings", ["item", "=", "vm_ipv6"])->first()->value);
$v6mode = parse_input($db->get("vncp_settings", ["item", "=", "ipv6_mode"])->first()->value);
$private_networking = parse_input($db->get("vncp_settings", ["item", "=", "private_networking"])->first()->value);
if ($vm_ipv6 == "true") {
    echo "                           <div class=\"table-responsive\">\r\n                            <table class=\"table table-striped\">\r\n                              <tr>\r\n                                <th>IPv6 Address</th>\r\n                                <th>Gateway</th>\r\n                                <th>Netmask</th>\r\n                              </tr>\r\n                              ";
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
    echo "                            </table>\r\n                           </div>\r\n                           ";
    if (count($v6results) < 1) {
        echo "<button type=\"button\" class=\"btn btn-success btn-block\" id=\"assignipv6\">Assign IPv6 Address (";
        echo count($v6results);
        echo " of 1)</button>";
    }
}
echo "                           ";
if ($private_networking == "true") {
    echo "                           <h2 align=\"center\">Private Network</h2>\r\n                           ";
    if ($data->has_net1 == 1) {
        echo "                           <div class=\"table-responsive\">\r\n                            <table class=\"table table-striped\">\r\n                              <tr>\r\n                                <th>IPv4 Address</th>\r\n                                <th>Netmask</th>\r\n                                <th></th>\r\n                              </tr>\r\n                              <tr>\r\n                                ";
        $getprivateip = $db->get("vncp_private_pool", ["hb_account_id", "=", parse_input($_GET["id"])]);
        $ipresults = $getprivateip->first();
        echo "<td>" . $ipresults->address . "</td>";
        echo "                                <td>";
        echo $ipresults->netmask;
        echo "</td>\r\n                                <td><button type=\"button\" class=\"btn btn-danger btn-md\" id=\"disableprivatenet\">Disable Private Networking</button></td>\r\n                              </tr>\r\n                            </table>\r\n                           </div>\r\n                           ";
    } else {
        echo "                           <button type=\"button\" class=\"btn btn-success btn-block\" id=\"enableprivatenet\">Enable Private Networking</button><br /><br />\r\n                         ";
    }
}
echo "                       </div>\r\n                       ";
if ($LXCNAT == 1) {
    echo "                       <div role=\"tabpanel\" class=\"tab-pane\" id=\"natports\">\r\n                         <form role=\"form\">\r\n \t\t                        <div class=\"form-group\">\r\n \t\t                            <label>NAT Port</label>\r\n \t\t                            <input class=\"form-control\" type=\"number\" id=\"chosennatport\" autocomplete=\"off\" min=\"1\" max=\"65535\" />\r\n \t\t                            <p class=\"help-block\" style=\"color:red;\" id=\"natport_error\"></p>\r\n \t\t                        </div>\r\n \t\t                        <div class=\"form-group\">\r\n \t\t                            <label>Description <small>max 20 characters</small></label>\r\n \t\t                            <input class=\"form-control\" type=\"text\" id=\"natportdesc\" autocomplete=\"off\" />\r\n \t\t                            <p class=\"help-block\" style=\"color:red;\" id=\"natdesc_error\"></p>\r\n \t\t                        </div>\r\n \t\t                        <input type=\"hidden\" id=\"aid\" value=\"";
    echo parse_input($data->hb_account_id);
    echo "\" />\r\n \t\t                        ";
    if ($data->suspended == 0) {
        echo "<input type=\"submit\" id=\"natport_btn\" value=\"Create Port Forward\" class=\"btn btn-info btn-lg\" />";
    } else {
        echo "<div class=\"btn btn-info btn-lg\" disabled>Create Port Forward</div>";
    }
    echo " \t\t                    </form><br /><br />\r\n                         <div class=\"table-responsive\">\r\n                           <table class=\"table table-striped table-condensed\" id=\"user_porttable\">\r\n                             <tr>\r\n                               <th>Public Side</th>\r\n                               <th></th>\r\n                               <th>NAT Side</th>\r\n                               <th>Description</th>\r\n                               <th></th>\r\n                             </tr>\r\n                             ";
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
    echo "                           </table>\r\n                         </div>\r\n                       </div>\r\n                       <div role=\"tabpanel\" class=\"tab-pane\" id=\"natdomains\">\r\n                         <form role=\"form\">\r\n \t\t                        <div class=\"form-group\">\r\n \t\t                            <label>Domain</label>\r\n \t\t                            <input class=\"form-control\" type=\"text\" id=\"chosendomain\" autocomplete=\"off\" placeholder=\"domain.com\" />\r\n                                <p class=\"help-block\">Wildcard forwarding. All requests to domain.com and *.domain.com will be forwarded to this virtual machine.</p>\r\n \t\t                            <p class=\"help-block\" style=\"color:red;\" id=\"natdomain_error\"></p>\r\n \t\t                        </div>\r\n                            <p>NAT domains will forward HTTP traffic by default. To enable HTTPS support, paste your SSL certificate details below.</p>\r\n                            <div class=\"form-group\">\r\n \t\t                            <label>SSL Certificate</label>\r\n                                <textarea class=\"form-control\" rows=\"5\" id=\"nat_sslcert\" placeholder=\"*Optional*: paste SSL certificate in PEM format\"></textarea>\r\n \t\t                        </div>\r\n                            <div class=\"form-group\">\r\n \t\t                            <label>SSL Private Key</label>\r\n                                <textarea class=\"form-control\" rows=\"5\" id=\"nat_sslkey\" placeholder=\"*Optional*: paste SSL private key in PEM format\"></textarea>\r\n \t\t                        </div>\r\n \t\t                        <input type=\"hidden\" id=\"aid\" value=\"";
    echo parse_input($data->hb_account_id);
    echo "\" />\r\n \t\t                        ";
    if ($data->suspended == 0) {
        echo "<input type=\"submit\" id=\"natdomain_btn\" value=\"Create Domain Forward\" class=\"btn btn-info btn-lg\" />";
    } else {
        echo "<div class=\"btn btn-info btn-lg\" disabled>Create Domain Forward</div>";
    }
    echo " \t\t                    </form><br /><br />\r\n                        <div class=\"table-responsive\">\r\n                          <table class=\"table table-striped table-condensed\" id=\"user_domaintable\">\r\n                            <tr>\r\n                              <th>Domain</th>\r\n                              <th>Forwarding</th>\r\n                              <th></th>\r\n                            </tr>\r\n                            ";
    $allddata = explode(";", $portforwards[0]->domains);
    for ($i = 0; $i < $domains; $i++) {
        echo "<tr>";
        echo "<td>" . $allddata[$i] . "</td>";
        echo "<td>" . $allddata[$i] . " <i class=\"fa fa-arrow-right\"></i> " . $natnode[0]->publicip . " <i class=\"fa fa-arrow-right\"></i> " . $data->ip . "</td>";
        echo "<td><button id=\"user_natdomaindelete" . $allddata[$i] . "\" class=\"btn btn-sm btn-danger\" role=\"" . $allddata[$i] . "\">Delete</button></td>";
        echo "</tr>";
    }
    echo "                          </table>\r\n                        </div>\r\n                       </div>\r\n                      ";
}
echo "                     </div>\r\n                   </div>\r\n                 </div>\r\n                 <br /><br />\r\n             </div>\r\n        </div>\r\n        <!-- TAB CONTENT END -->\r\n      <!-- TAB CONTENT -->\r\n      <div id=\"tab2\" class=\"tab-single tab-cnt\">\r\n            <div class=\"datacenters\">\r\n               <div class=\"row\">\r\n\t                <div class=\"col-md-4 col-md-offset-2\">\r\n\t                \t<p>Scale:</p>\r\n\t                \t<select class=\"form-control\" id=\"graphtime\">\r\n\t                \t\t<option value=\"hour\">Hour</option>\r\n\t                \t\t<option value=\"day\">Day</option>\r\n\t                \t\t<option value=\"week\">Week</option>\r\n\t                \t\t<option value=\"month\">Month</option>\r\n\t                \t\t<option value=\"year\">Year</option>\r\n\t                \t</select>\r\n\t                </div>\r\n               </div>\r\n               <div id=\"hour\">\r\n               \t\t<div class=\"row\">\r\n               \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n               \t\t\t\t<h4>CPU Usage</h4>\r\n               \t\t\t\t";
$rrd = $pxAPI->get("/nodes/" . $data->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/rrd?ds=cpu&timeframe=hour&cf=AVERAGE");
echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
echo "               \t\t\t</div>\r\n               \t\t</div>\r\n               \t\t<div class=\"row\">\r\n               \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n               \t\t\t\t<h4>RAM Usage</h4>\r\n               \t\t\t\t";
$rrd = $pxAPI->get("/nodes/" . $data->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/rrd?ds=mem,maxmem&timeframe=hour&cf=AVERAGE");
echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
echo "               \t\t\t</div>\r\n               \t\t</div>\r\n               \t\t<div class=\"row\">\r\n               \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n               \t\t\t\t<h4>Network Usage</h4>\r\n               \t\t\t\t";
$rrd = $pxAPI->get("/nodes/" . $data->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/rrd?ds=netin,netout&timeframe=hour&cf=AVERAGE");
echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
echo "               \t\t\t</div>\r\n               \t\t</div>\r\n               \t\t<div class=\"row\">\r\n               \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n               \t\t\t\t<h4>Disk I/O</h4>\r\n               \t\t\t\t";
$rrd = $pxAPI->get("/nodes/" . $data->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/rrd?ds=diskread,diskwrite&timeframe=hour&cf=AVERAGE");
echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
echo "               \t\t\t</div>\r\n               \t\t</div>\r\n               </div>\r\n               <div id=\"day\">\r\n               \t\t<div class=\"row\">\r\n               \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n               \t\t\t\t<h4>CPU Usage</h4>\r\n               \t\t\t\t";
$rrd = $pxAPI->get("/nodes/" . $data->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/rrd?ds=cpu&timeframe=day&cf=AVERAGE");
echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
echo "               \t\t\t</div>\r\n               \t\t</div>\r\n               \t\t<div class=\"row\">\r\n               \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n               \t\t\t\t<h4>RAM Usage</h4>\r\n               \t\t\t\t";
$rrd = $pxAPI->get("/nodes/" . $data->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/rrd?ds=mem,maxmem&timeframe=day&cf=AVERAGE");
echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
echo "               \t\t\t</div>\r\n               \t\t</div>\r\n               \t\t<div class=\"row\">\r\n               \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n               \t\t\t\t<h4>Network Usage</h4>\r\n               \t\t\t\t";
$rrd = $pxAPI->get("/nodes/" . $data->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/rrd?ds=netin,netout&timeframe=day&cf=AVERAGE");
echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
echo "               \t\t\t</div>\r\n               \t\t</div>\r\n               \t\t<div class=\"row\">\r\n               \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n               \t\t\t\t<h4>Disk I/O</h4>\r\n               \t\t\t\t";
$rrd = $pxAPI->get("/nodes/" . $data->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/rrd?ds=diskread,diskwrite&timeframe=day&cf=AVERAGE");
echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
echo "               \t\t\t</div>\r\n               \t\t</div>\r\n               </div>\r\n               <div id=\"week\">\r\n               \t\t<div class=\"row\">\r\n               \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n               \t\t\t\t<h4>CPU Usage</h4>\r\n               \t\t\t\t";
$rrd = $pxAPI->get("/nodes/" . $data->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/rrd?ds=cpu&timeframe=week&cf=AVERAGE");
echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
echo "               \t\t\t</div>\r\n               \t\t</div>\r\n               \t\t<div class=\"row\">\r\n               \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n               \t\t\t\t<h4>RAM Usage</h4>\r\n               \t\t\t\t";
$rrd = $pxAPI->get("/nodes/" . $data->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/rrd?ds=mem,maxmem&timeframe=week&cf=AVERAGE");
echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
echo "               \t\t\t</div>\r\n               \t\t</div>\r\n               \t\t<div class=\"row\">\r\n               \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n               \t\t\t\t<h4>Network Usage</h4>\r\n               \t\t\t\t";
$rrd = $pxAPI->get("/nodes/" . $data->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/rrd?ds=netin,netout&timeframe=week&cf=AVERAGE");
echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
echo "               \t\t\t</div>\r\n               \t\t</div>\r\n               \t\t<div class=\"row\">\r\n               \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n               \t\t\t\t<h4>Disk I/O</h4>\r\n               \t\t\t\t";
$rrd = $pxAPI->get("/nodes/" . $data->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/rrd?ds=diskread,diskwrite&timeframe=week&cf=AVERAGE");
echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
echo "               \t\t\t</div>\r\n               \t\t</div>\r\n               </div>\r\n               <div id=\"month\">\r\n               \t\t<div class=\"row\">\r\n               \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n               \t\t\t\t<h4>CPU Usage</h4>\r\n               \t\t\t\t";
$rrd = $pxAPI->get("/nodes/" . $data->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/rrd?ds=cpu&timeframe=month&cf=AVERAGE");
echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
echo "               \t\t\t</div>\r\n               \t\t</div>\r\n               \t\t<div class=\"row\">\r\n               \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n               \t\t\t\t<h4>RAM Usage</h4>\r\n               \t\t\t\t";
$rrd = $pxAPI->get("/nodes/" . $data->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/rrd?ds=mem,maxmem&timeframe=month&cf=AVERAGE");
echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
echo "               \t\t\t</div>\r\n               \t\t</div>\r\n               \t\t<div class=\"row\">\r\n               \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n               \t\t\t\t<h4>Network Usage</h4>\r\n               \t\t\t\t";
$rrd = $pxAPI->get("/nodes/" . $data->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/rrd?ds=netin,netout&timeframe=month&cf=AVERAGE");
echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
echo "               \t\t\t</div>\r\n               \t\t</div>\r\n               \t\t<div class=\"row\">\r\n               \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n               \t\t\t\t<h4>Disk I/O</h4>\r\n               \t\t\t\t";
$rrd = $pxAPI->get("/nodes/" . $data->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/rrd?ds=diskread,diskwrite&timeframe=month&cf=AVERAGE");
echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
echo "               \t\t\t</div>\r\n               \t\t</div>\r\n               </div>\r\n               <div id=\"year\">\r\n               \t\t<div class=\"row\">\r\n               \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n               \t\t\t\t<h4>CPU Usage</h4>\r\n               \t\t\t\t";
$rrd = $pxAPI->get("/nodes/" . $data->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/rrd?ds=cpu&timeframe=year&cf=AVERAGE");
echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
echo "               \t\t\t</div>\r\n               \t\t</div>\r\n               \t\t<div class=\"row\">\r\n               \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n               \t\t\t\t<h4>RAM Usage</h4>\r\n               \t\t\t\t";
$rrd = $pxAPI->get("/nodes/" . $data->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/rrd?ds=mem,maxmem&timeframe=year&cf=AVERAGE");
echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
echo "               \t\t\t</div>\r\n               \t\t</div>\r\n               \t\t<div class=\"row\">\r\n               \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n               \t\t\t\t<h4>Network Usage</h4>\r\n               \t\t\t\t";
$rrd = $pxAPI->get("/nodes/" . $data->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/rrd?ds=netin,netout&timeframe=year&cf=AVERAGE");
echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
echo "               \t\t\t</div>\r\n               \t\t</div>\r\n               \t\t<div class=\"row\">\r\n               \t\t\t<div class=\"col-md-12 col-md-offset-2\">\r\n               \t\t\t\t<h4>Disk I/O</h4>\r\n               \t\t\t\t";
$rrd = $pxAPI->get("/nodes/" . $data->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/rrd?ds=diskread,diskwrite&timeframe=year&cf=AVERAGE");
echo "<img src=\"data:image/png;base64," . base64_encode(utf8_decode($rrd["image"])) . "\" />";
echo "               \t\t\t</div>\r\n               \t\t</div>\r\n               </div>\r\n           </div>\r\n      </div>\r\n      <!-- TAB CONTENT END -->\r\n       <!-- TAB CONTENT -->\r\n       <div id=\"tab3\" class=\"tab-single tab-cnt\">\r\n             <div class=\"datacenters\">\r\n                <div class=\"col-md-12\">\r\n                    <div class=\"row\">\r\n\t\t                <div class=\"col-md-6\">\r\n\t\t                    <div id=\"info_box\"></div>\r\n\t\t                    <form role=\"form\">\r\n\t\t                        <div class=\"form-group\">\r\n\t\t                            <label>Operating System Template</label>\r\n\t\t                            <select class=\"form-control\" id=\"os\">\r\n\t\t                            \t<option value=\"default\">Select...</option>\r\n\t\t                                ";
$content = $db->get("vncp_lxc_templates", ["content", "=", "vztmpl"]);
$contentr = $content->all();
for ($i = 0; $i < count($contentr); $i++) {
    echo "<option value=\"" . $contentr[$i]->volid . "\">" . $contentr[$i]->friendly_name . "</option>";
}
echo "\t\t                            </select>\r\n\t\t                            <p class=\"help-block\" style=\"color:red;\" id=\"os_error\"></p>\r\n\t\t                        </div>\r\n\t\t                        <div class=\"form-group\">\r\n\t\t                            <label>Hostname</label>\r\n\t\t                            <input class=\"form-control\" type=\"text\" id=\"hostname\" autocomplete=\"off\" />\r\n\t\t                            <p class=\"help-block\" style=\"color:red;\" id=\"hostname_error\"></p>\r\n\t\t                        </div>\r\n\t\t                        <div class=\"form-group\">\r\n\t\t                            <label>Root Password</label>\r\n\t\t                            <input class=\"form-control\" type=\"password\" id=\"password\" autocomplete=\"off\" />\r\n\t\t                            <p class=\"help-block\" style=\"color:red;\" id=\"password_error\"></p>\r\n\t\t                            <p class=\"help-block\">This password is not stored in our database. Remember it!</p>\r\n\t\t                        </div>\r\n\t\t                        <input type=\"hidden\" id=\"aid\" value=\"";
echo parse_input($data->hb_account_id);
echo "\" />\r\n\t\t                        ";
if ($data->suspended == 0) {
    echo "<input type=\"submit\" id=\"rebuild_btn\" value=\"Rebuild\" class=\"btn btn-info btn-lg\" />";
} else {
    echo "<div class=\"btn btn-info btn-lg\" disabled>Rebuild</div>";
}
echo "\t\t                    </form>\r\n\t\t                </div>\r\n\t\t                <div class=\"col-md-6\">\r\n\t\t                    ";
$result_blog = $db->limit_get_desc("vncp_users_rebuild_log", ["client_id", "=", $user->data()->id], "1");
$data_blog = $result_blog->first();
echo "\t\t                    <div class=\"alert alert-info\"><strong>Last recorded rebuild: </strong>";
if (!empty($data_blog) && $data_blog->vmid == $vminfo["members"][0]["vmid"]) {
    echo parse_input($data_blog->date);
} else {
    echo "none";
}
echo "<br /><br /></div>\r\n\t\t                </div>\r\n\t\t            </div>\r\n                </div>\r\n            </div>\r\n            <div class=\"modal fade\" id=\"rebuild_modal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"rebuild_modalLabel\" aria-hidden=\"true\">\r\n            \t<div class=\"modal-dialog modal-lg\">\r\n            \t\t<div class=\"modal-content\">\r\n            \t\t\t<div class=\"modal-header\">\r\n            \t\t\t\t<h4 class=\"modal-title\" id=\"rebuild_modalLabel\">Rebuild in progress...</h4>\r\n            \t\t\t</div>\r\n            \t\t\t<div class=\"modal-body\">\r\n            \t\t\t\t<div class=\"progress\">\r\n            \t\t\t\t\t<div class=\"progress-bar progress-bar-info progress-bar-striped active rebuild_progress\" role=\"progressbar\" style=\"width:0%\"></div>\r\n            \t\t\t\t\t<br />\r\n            \t\t\t\t\t<br />\r\n            \t\t\t\t\t<div id=\"rebuild_output\"></div>\r\n            \t\t\t\t</div>\r\n            \t\t\t</div>\r\n            \t\t</div>\r\n            \t</div>\r\n            </div>\r\n       </div>\r\n       <!-- TAB CONTENT END -->\r\n       <!-- TAB CONTENT -->\r\n       <div id=\"tab5\" class=\"tab-single tab-cnt\">\r\n            <div class=\"datacenters\">\r\n                <div class=\"col-md-12\">\r\n                    <div class=\"row\">\r\n\t\t                <div class=\"col-md-12\">\r\n                      ";
$enable_backups = parse_input($db->get("vncp_settings", ["item", "=", "enable_backups"])->first()->value);
if ($enable_backups == "true") {
    $maxbackups = (int) parse_input($db->get("vncp_settings", ["item", "=", "backup_limit"])->first()->value);
    $override = (int) parse_input($db->get("vncp_ct_backups", ["hb_account_id", "=", $data->hb_account_id])->first()->backuplimit);
    if (0 <= $override) {
        $maxbackups = $override;
    }
    if ($data->allow_backups == 1) {
        echo "\t\t                    <div id=\"backup_info\"></div>\r\n\t\t                    ";
        $backups = $pxAPI->get("/nodes/" . $data->node . "/storage/" . $node_data->backup_store . "/content");
        $scheduled = $pxAPI->get("/cluster/backup");
        $backupcount = 0;
        for ($i = 0; $i < count($backups); $i++) {
            $volid = explode("-", $backups[$i]["volid"]);
            if ($volid[1] == "lxc" && $volid[2] == (string) $vminfo["members"][0]["vmid"] && $backups[$i]["content"] == "backup") {
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
        echo "\t\t                    <h4>Backup Usage: <span id=\"currentbackupcount\">";
        echo $backupcount . " used";
        echo "</span> of <span id=\"maxbackupcount\">";
        echo $maxbackups . " available";
        echo "</span></h4>\r\n                        <br />\r\n\t\t                    <div class=\"table-responsive\">\r\n\t\t                        <table class=\"table table-hover\" id=\"backuplist\">\r\n                                <tbody>\r\n\t\t                            <tr>\r\n\t\t                                <th>Name</th>\r\n\t\t                                <th>Size</th>\r\n                                    <th></th>\r\n\t\t                                <th></th>\r\n\t\t                                <th></th>\r\n\t\t                            </tr>\r\n\t\t                            ";
        for ($i = 0; $i < count($backups); $i++) {
            $volid = explode("-", $backups[$i]["volid"]);
            if ($volid[1] == "lxc" && $volid[2] == (string) $vminfo["members"][0]["vmid"] && $backups[$i]["content"] == "backup") {
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
        echo "                              </tbody>\r\n\t\t                        </table>\r\n\t\t                    </div>\r\n                        <span id=\"countsection\">\r\n\t\t                    ";
        if ($backupcount < $maxbackups && $data->suspended == 0) {
            echo "<button type=\"button\" class=\"btn btn-md btn-success btn-block\" data-toggle=\"modal\" data-target=\"#backup_modal\">Create backup</button><br /><br />";
        } else {
            echo "<button type=\"button\" class=\"btn btn-md btn-warning btn-block\" disabled=\"disabled\">Create backup</button>&nbsp;&nbsp;&nbsp;&nbsp;<small><em>Backup limit reached. Remove some old backups to create more.</em></small><br /><br />";
        }
        echo "                        </span>\r\n                        <br />\r\n                        ";
        $schtime = "";
        if ($schedule != NULL) {
            $schtime = $schedule["dow"] . " @ " . $schedule["starttime"];
        }
        echo "                        <h4>Scheduled Backup: ";
        if ($schtime != "") {
            echo $schtime;
        } else {
            echo "None";
        }
        echo "</h4>\r\n                        <br />\r\n                        ";
        if ($schedule == NULL) {
            echo "                        <form class=\"form-inline\" id=\"scheduled_form\">\r\n                          <div class=\"form-group\">\r\n                            <label>Day(s) of week: </label>\r\n                            <select multiple class=\"form-control\" id=\"scheduled_dow\">\r\n                              <option value=\"mon\">Monday</option>\r\n                              <option value=\"tue\">Tuesday</option>\r\n                              <option value=\"wed\">Wednesday</option>\r\n                              <option value=\"thu\">Thursday</option>\r\n                              <option value=\"fri\">Friday</option>\r\n                              <option value=\"sat\">Saturday</option>\r\n                              <option value=\"sun\">Sunday</option>\r\n                            </select>\r\n                          </div>\r\n                          <div class=\"form-group\">\r\n                            <label>Time: </label>\r\n                            <input type=\"time\" class=\"form-control\" id=\"scheduled_time\" />\r\n                          </div>\r\n                          <button type=\"submit\" class=\"btn btn-md btn-success\" id=\"scheduled_submit\">Create scheduled backup</button><br /><br />\r\n                        </form>\r\n                      ";
        } else {
            echo "                        <form class=\"form-inline\" id=\"schdelete_form\">\r\n                          <input type=\"hidden\" value=\"";
            echo $schedule["id"];
            echo "\" id=\"schid\" />\r\n                          <button type=\"submit\" class=\"btn btn-md btn-danger\" id=\"schdelete_submit\">Delete scheduled backup</button><br /><br />\r\n                        </form>\r\n                      ";
        }
        echo "                        <div class=\"modal fade\" id=\"backup_modal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"backup_modalLabel\" aria-hidden=\"true\">\r\n                        \t<div class=\"modal-dialog\">\r\n                        \t\t<div class=\"modal-content\">\r\n                        \t\t\t<div class=\"modal-header\">\r\n                        \t\t\t\t<button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">&times;</span></button>\r\n                        \t\t\t\t<h4 class=\"modal-title\" id=\"backup_modalLabel\">Backup Confirmation</h4>\r\n                        \t\t\t</div>\r\n                        \t\t\t<div class=\"modal-body\">\r\n                        \t\t\t\t<p>Taking a backup on your LXC VPS will put your VPS in a suspended state and compress all VPS data into a backup file. This is a fast process that will cause minimal downtime, but <strong>your VPS will not be accessible while suspended.</strong> Once the backup finishes, your VPS will be available again.</p>\r\n                        \t\t\t\t<br />\r\n                        \t\t\t\t<form role=\"form\">\r\n                        \t\t\t\t    <div class=\"form-group\">\r\n                        \t\t\t\t        <label>Email Notification</label>\r\n                        \t\t\t\t        <select class=\"form-control\" id=\"notification\">\r\n                        \t\t\t\t        \t<option value=\"no\">No</option>\r\n                        \t\t\t\t        \t<option value=\"yes\">Yes</option>\r\n                        \t\t\t\t        </select>\r\n                        \t\t\t\t        <p class=\"help-block\">Do you want to receive an email notification when the backup job finishes? The email will be sent to the email registered with your <a href=\"profile\">";
        echo $appname;
        echo " account</a>.</p>\r\n                        \t\t\t\t    </div>\r\n                        \t\t\t\t    <input type=\"hidden\" id=\"backup_aid\" value=\"";
        echo parse_input($data->hb_account_id);
        echo "\" />\r\n                        \t\t\t\t</form>\r\n                        \t\t\t\t<div id=\"backup_message\" style=\"color: green;\"></div>\r\n                        \t\t\t</div>\r\n                        \t\t\t<div class=\"modal-footer\">\r\n                        \t\t\t\t<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\" id=\"cancel_backup\">Cancel</button>\r\n                \t\t\t\t        <button type=\"button\" class=\"btn btn-primary\" id=\"create_backup\">Confirm Backup</button>\r\n                        \t\t\t</div>\r\n                        \t\t</div>\r\n                        \t</div>\r\n                        </div>\r\n                        <div class=\"modal fade\" id=\"config_modal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"config_modalLabel\" aria-hidden=\"true\">\r\n                        \t<div class=\"modal-dialog\">\r\n                        \t\t<div class=\"modal-content\">\r\n                        \t\t\t<div class=\"modal-header\">\r\n                        \t\t\t\t<button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">&times;</span></button>\r\n                        \t\t\t\t<h4 class=\"modal-title\" id=\"config_modalLabel\">Stored Configuration</h4>\r\n                        \t\t\t</div>\r\n                        \t\t\t<div class=\"modal-body\">\r\n                                <h5 id=\"confheader\"></h5>\r\n                        \t\t\t\t<div class=\"well well-sm\" id=\"confwell\">\r\n                                  <i class=\"fa fa-cog fa-spin\"></i> Pulling configuration, please wait...\r\n                                </div>\r\n                        \t\t\t</div>\r\n                        \t\t\t<div class=\"modal-footer\">\r\n                        \t\t\t\t<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>\r\n                              </div>\r\n                        \t\t</div>\r\n                        \t</div>\r\n                        </div>\r\n                        <div class=\"modal fade\" id=\"restore_modal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"restore_modalLabel\" aria-hidden=\"true\">\r\n                        \t<div class=\"modal-dialog modal-lg\">\r\n                        \t\t<div class=\"modal-content\">\r\n                        \t\t\t<div class=\"modal-header\">\r\n                        \t\t\t\t<h4 class=\"modal-title\" id=\"restore_modalLabel\">Restore in progress...</h4>\r\n                        \t\t\t</div>\r\n                        \t\t\t<div class=\"modal-body\">\r\n                        \t\t\t\t<div class=\"progress\">\r\n                        \t\t\t\t\t<div class=\"progress-bar progress-bar-info progress-bar-striped active restore_progress\" role=\"progressbar\" style=\"width:0%\"></div>\r\n                        \t\t\t\t\t<br />\r\n                        \t\t\t\t\t<br />\r\n                        \t\t\t\t\t<div id=\"restore_output\"></div>\r\n                        \t\t\t\t</div>\r\n                        \t\t\t</div>\r\n                        \t\t</div>\r\n                        \t</div>\r\n                        </div>\r\n                        ";
    } else {
        echo "Backups are not enabled on this VPS.";
    }
} else {
    echo "Backups are not enabled here.";
}
echo "\t\t                </div>\r\n\t\t            </div>\r\n                </div>\r\n            </div>\r\n       </div>\r\n       <!-- TAB CONTENT END -->\r\n      <!-- TAB CONTENT -->\r\n      <div id=\"tab6\" class=\"tab-single tab-cnt\">\r\n            <div class=\"datacenters\">\r\n            \t<div class=\"row\">\r\n\t               <div class=\"col-md-12\">\r\n\t                   <h4>Click the button below to open a new console session for this VM. This console should only be used if you are unable to access your VM from the Internet. This console is not designed to be used for regular usage.</h4>\r\n\t                   <br />\r\n\t\t               \t";
if ($data->suspended == 0) {
    echo "<button class=\"btn btn-info btn-lg\" id=\"lxcconsole\" role=\"" . parse_input($_GET["id"]) . "\">Open VNC console</button>";
} else {
    echo "<button class=\"btn btn-info btn-lg\" disabled>Open VNC console</button>";
}
echo "\t               </div>\r\n               </div>\r\n           </div>\r\n      </div>\r\n      <!-- TAB CONTENT END -->\r\n      </div>\r\n     </div>\r\n    </section>\r\n    <!-- /Feature with tabs -->\r\n</div>\r\n<input type=\"hidden\" value=\"";
echo $data->hb_account_id;
echo "\" id=\"lxcinfo\" />\r\n";

?>
