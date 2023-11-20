<?php

if (!defined("constant-fw")) {
    header("Location: index");
}
$results = $db->get("vncp_kvm_ct", ["hb_account_id", "=", $_GET["id"]]);
$data = $results->first();
$noLogin = false;
if ($data->from_template == 1) {
    exit("Go to Dashboard first to complete setup.");
}
if ($data->suspended == 0 && $data->user_id == $user->data()->id) {
    $nodename = $data->node;
    $node_results = $db->get("vncp_nodes", ["name", "=", $nodename]);
    $node_data = $node_results->first();
    $pxAPI = new PVE2_API($node_data->hostname, $node_data->username, $node_data->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($node_data->password));
    if (!$pxAPI->login()) {
        $noLogin = true;
    }
    if (!$noLogin) {
        $vminfo = $pxAPI->get("/pools/" . $data->pool_id);
    }
}
echo "<div class=\"col-md-12\">\r\n    <!-- Feature with tabs -->\r\n    <section class=\"feature-tabs\">\r\n     <div class=\"first-sliding\">\r\n     </div>\r\n     <div class=\"wrap\">\r\n      <div class=\"tabs\">\r\n       <div class=\"tabs_container\">\r\n       <!-- TAB -->\r\n        <li class=\"btnLink smallBtnLink green_tab\">\r\n            <a class=\"tab-action active\" data-tab-cnt=\"tab1\">\r\n                <span>Options</span>\r\n            </a></li>\r\n        <!-- TAB END -->\r\n        <!-- TAB -->\r\n        <li class=\"btnLink smallBtnLink green_tab\">\r\n            <a class=\"tab-action\" data-tab-cnt=\"tab2\">\r\n                <span>Rules</span>\r\n            </a></li>\r\n        <!-- TAB END -->\r\n        <!-- TAB -->\r\n        <li class=\"btnLink smallBtnLink green_tab\">\r\n            <a class=\"tab-action\" data-tab-cnt=\"tab4\">\r\n                <span>Log</span>\r\n            </a></li>\r\n        <!-- TAB END -->\r\n       </div>\r\n       <br>\r\n       <div class=\"clr\"></div>\r\n       <!-- TAB CONTENT -->\r\n       <div id=\"tab1\" class=\"tab-single tab-cnt active\">\r\n            <div class=\"datacenters\">\r\n                <div class=\"col-md-12\">\r\n                    ";
if (count($vminfo["members"]) == 1) {
    $options = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $vminfo["members"][0]["vmid"] . "/firewall/options");
} else {
    for ($j = 0; $j < count($vminfo["members"]); $j++) {
        if ($vminfo["members"][$j]["name"] == $data->cloud_hostname) {
            $options = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $vminfo["members"][$j]["vmid"] . "/firewall/options");
        }
    }
}
echo "                    <button type=\"button\" class=\"btn btn-success btn-md pull-left\" data-toggle=\"modal\" data-target=\"#fwoptions\">Edit Options</button>\r\n                    ";
if ($data->has_net1 == 1) {
    if ($data->fw_enabled_net1 == 0) {
        echo "<button type=\"button\" class=\"btn btn-info btn-md pull-right\" id=\"fwifacepriv\" role=\"enable\">Enable Firewall Interface (private)</button>";
    } else {
        echo "<button type=\"button\" class=\"btn btn-info btn-md pull-right\" id=\"fwifacepriv\" role=\"disable\">Disable Firewall Interface (private)</button>";
    }
}
if ($data->fw_enabled_net0 == 0) {
    echo "<button type=\"button\" class=\"btn btn-info btn-md pull-right\" id=\"fwifacepub\" role=\"enable\" style=\"margin-right:10px;\">Enable Firewall Interface (public)</button>";
} else {
    echo "<button type=\"button\" class=\"btn btn-info btn-md pull-right\" id=\"fwifacepub\" role=\"disable\" style=\"margin-right:10px;\">Disable Firewall Interface (public)</button>";
}
echo "                    <div class=\"clearfix\"></div>\r\n                    <br />\r\n                    <br />\r\n                    <div class=\"table-responsive\">\r\n                        <table class=\"table table-hover\">\r\n                            <tr>\r\n                                <th>Option</th>\r\n                                <th>Value</th>\r\n                                <th>Description</th>\r\n                            </tr>\r\n                            <tr>\r\n                                <td>Enable firewall</td>\r\n                                <td>\r\n                                    ";
if ($options["enable"] == 1) {
    echo "<div id=\"enable\">Yes</div>";
} else {
    echo "<div id=\"enable\">No</div>";
}
echo "                                </td>\r\n                                <td>Enable or disable the firewall for this VM</td>\r\n                            </tr>\r\n                            <tr>\r\n                                <td>Global inbound policy</td>\r\n                                <td>\r\n                                    ";
if (isset($options["policy_in"])) {
    echo "<div id=\"policyin\">" . $options["policy_in"] . "</div>";
} else {
    echo "<div id=\"policyin\">DROP</div>";
}
echo "                                </td>\r\n                                <td>Accept, drop, or reject inbound traffic by default</td>\r\n                            </tr>\r\n                            <tr>\r\n                                <td>Global outbound policy</td>\r\n                                <td>\r\n                                    ";
if (isset($options["policy_out"])) {
    echo "<div id=\"policyout\">" . $options["policy_out"] . "</div>";
} else {
    echo "<div id=\"policyout\">ACCEPT</div>";
}
echo "                                </td>\r\n                                <td>Accept, drop, or reject outbound traffic by default</td>\r\n                            </tr>\r\n                            <tr>\r\n                                <td>Inbound log level</td>\r\n                                <td>\r\n                                    ";
if (isset($options["log_level_in"])) {
    echo "<div id=\"levelin\">" . $options["log_level_in"] . "</div>";
} else {
    echo "<div id=\"levelin\">nolog</div>";
}
echo "                                </td>\r\n                                <td>Set the logging level of inbound traffic</td>\r\n                            </tr>\r\n                            <tr>\r\n                                <td>Outbound log level</td>\r\n                                <td>\r\n                                    ";
if (isset($options["log_level_out"])) {
    echo "<div id=\"levelout\">" . $options["log_level_out"] . "</div>";
} else {
    echo "<div id=\"levelout\">nolog</div>";
}
echo "                                </td>\r\n                                <td>Set the logging level of outbound traffic</td>\r\n                            </tr>\r\n                        </table>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n       </div>\r\n       <!-- TAB CONTENT END -->\r\n       <!-- TAB CONTENT -->\r\n       <div id=\"tab2\" class=\"tab-single tab-cnt\">\r\n             <div class=\"datacenters\">\r\n                <div class=\"col-md-12\">\r\n                    ";
if (count($vminfo["members"]) == 1) {
    $rules = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $vminfo["members"][0]["vmid"] . "/firewall/rules");
} else {
    for ($j = 0; $j < count($vminfo["members"]); $j++) {
        if ($vminfo["members"][$j]["name"] == $data->cloud_hostname) {
            $rules = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $vminfo["members"][$j]["vmid"] . "/firewall/rules");
        }
    }
}
echo "                    <button type=\"button\" class=\"btn btn-success btn-md\" data-toggle=\"modal\" data-target=\"#addfwrule\">Add Rule</button>\r\n                    <br />\r\n                    <br />\r\n                    <div class=\"table-responsive\">\r\n                        <table class=\"table table-hover\">\r\n                            <tr>\r\n                                <th>ID</th>\r\n                                <th>Enable</th>\r\n                                <th>Interface</th>\r\n                                <th>Direction</th>\r\n                                <th>Action</th>\r\n                                <th>Source IP</th>\r\n                                <th>Source port</th>\r\n                                <th>Destination IP</th>\r\n                                <th>Destination port</th>\r\n                                <th>Protocol</th>\r\n                                <th>Comment</th>\r\n                                <th>Remove</th>\r\n                            </tr>\r\n                                ";
for ($i = 0; $i < count($rules); $i++) {
    $p = $rules[$i]["pos"];
    echo "<tr><td>";
    echo "<a data-toggle=\"modal\" data-target=\"#fwr" . (string) $p . "\" style=\"cursor:pointer;\">" . (string) ((int) $p + 1) . "</a>";
    echo "</td><td>";
    if ($rules[$i]["enable"] == 0) {
        echo "<div id=\"renable" . $i . "\" role=\"" . $i . "\">false</div>";
    } else {
        echo "<div id=\"renable" . $i . "\" role=\"" . $i . "\">true</div>";
    }
    echo "</td><td>";
    if ($rules[$i]["iface"] == "net0") {
        echo "<div id=\"type" . $i . "\" role=\"" . $i . "\">net0 (public)</div>";
    } else {
        echo "<div id=\"type" . $i . "\" role=\"" . $i . "\">net1 (private)</div>";
    }
    echo "</td><td>";
    echo "<div id=\"type" . $i . "\" role=\"" . $i . "\">" . $rules[$i]["type"] . "</div>";
    echo "</td><td>";
    echo "<div id=\"action" . $i . "\" role=\"" . $i . "\">" . $rules[$i]["action"] . "</div>";
    echo "</td><td>";
    if (isset($rules[$i]["source"])) {
        echo $rules[$i]["source"];
    } else {
        echo "0.0.0.0";
    }
    echo "</td><td>";
    if (isset($rules[$i]["sport"])) {
        echo "<div id=\"sport" . $i . "\" role=\"" . $i . "\">" . $rules[$i]["sport"] . "</div>";
    } else {
        echo "<div id=\"sport" . $i . "\" role=\"" . $i . "\"><em>null</em></div>";
    }
    echo "</td><td>";
    if (isset($rules[$i]["dest"])) {
        echo $rules[$i]["dest"];
    } else {
        echo "0.0.0.0";
    }
    echo "</td><td>";
    if (isset($rules[$i]["dport"])) {
        echo "<div id=\"dport" . $i . "\" role=\"" . $i . "\">" . $rules[$i]["dport"] . "</div>";
    } else {
        echo "<div id=\"dport" . $i . "\" role=\"" . $i . "\"><em>null</em></div>";
    }
    echo "</td><td>";
    echo "<div id=\"proto" . $i . "\" role=\"" . $i . "\">" . $rules[$i]["proto"] . "</div>";
    echo "</td><td>";
    if (isset($rules[$i]["comment"])) {
        echo "<div id=\"comment" . $i . "\" role=\"" . $i . "\">" . $rules[$i]["comment"] . "</div>";
    } else {
        echo "<div id=\"comment" . $i . "\" role=\"" . $i . "\">none</div>";
    }
    echo "</td><td>";
    echo "<button id=\"fwremove" . $i . "\" class=\"btn btn-sm btn-danger\" role=\"" . $i . "\">Delete</button>";
    echo "</td></tr>";
    echo "<div class=\"modal fade\" id=\"fwr" . (string) $p . "\" tabindex=\"-1\" role=\"dialog\"><div class=\"modal-dialog\" role=\"document\"><div class=\"modal-content\"><div class=\"modal-header\"><button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span>&times;</span></button><h4 class=\"modal-title\">Edit Firewall Rule</h4></div><div class=\"modal-body\">\r\n<form role=\"form\">\r\n    <div class=\"form-group\">\r\n        <label for=\"a" . (string) $p . "\">Enable?</label>\r\n        <select id=\"a" . (string) $p . "\" class=\"form-control\">";
    if ($rules[$i]["enable"] == 0) {
        echo "<option value=\"0\">false</option><option value=\"1\">true</option>";
    } else {
        echo "<option value=\"1\">true</option><option value=\"0\">false</option>";
    }
    echo "</select>\r\n    </div>\r\n    <div class=\"form-group\">\r\n        <label for=\"iface" . (string) $p . "\">Network Interface</label>\r\n        <select id=\"iface" . (string) $p . "\" class=\"form-control\">";
    if ($rules[$i]["iface"] == "net0") {
        echo "<option value=\"net0\">net0 (public)</option><option value=\"net1\">net1 (private)</option>";
    } else {
        echo "<option value=\"net1\">net1 (private)</option><option value=\"net0\">net0 (public)</option>";
    }
    echo "</select>\r\n    </div>\r\n    <div class=\"form-group\">\r\n        <label for=\"b" . (string) $p . "\">Direction</label>\r\n        <select id=\"b" . (string) $p . "\" class=\"form-control\">";
    if ($rules[$i]["type"] == "in") {
        echo "<option value=\"in\">in</option><option value=\"out\">out</option>";
    } else {
        echo "<option value=\"out\">out</option><option value=\"in\">in</option>";
    }
    echo "</select>\r\n    </div>\r\n    <div class=\"form-group\">\r\n        <label for=\"c" . (string) $p . "\">Action</label>\r\n        <select id=\"c" . (string) $p . "\" class=\"form-control\">";
    if ($rules[$i]["action"] == "DROP") {
        echo "<option value=\"DROP\">DROP</option><option value=\"REJECT\">REJECT</option><option value=\"ACCEPT\">ACCEPT</option>";
    } else {
        if ($rules[$i]["action"] == "REJECT") {
            echo "<option value=\"REJECT\">REJECT</option><option value=\"DROP\">DROP</option><option value=\"ACCEPT\">ACCEPT</option>";
        } else {
            echo "<option value=\"ACCEPT\">ACCEPT</option><option value=\"DROP\">DROP</option><option value=\"REJECT\">REJECT</option>";
        }
    }
    echo "</select>\r\n    </div>\r\n    <div class=\"form-group\">\r\n        <label for=\"d" . (string) $p . "\">Source IP</label>\r\n        <input id=\"d" . (string) $p . "\" class=\"form-control\" type=\"text\" value=\"" . $rules[$i]["source"] . "\" />\r\n        <p class=\"help-block\">Required. Must be IP address. Any IP = 0.0.0.0</p>\r\n    </div>\r\n    <div class=\"form-group\">\r\n        <label for=\"e" . (string) $p . "\">Source port</label>\r\n        <input id=\"e" . (string) $p . "\" class=\"form-control\" type=\"number\" min=\"1\" max=\"65535\" value=\"" . ($rules[$i]["sport"] ?? "") . "\" />\r\n        <p class=\"help-block\">Not required. Valid ports are between 1 - 65535.</p>\r\n    </div>\r\n    <div class=\"form-group\">\r\n        <label for=\"f" . (string) $p . "\">Destination IP</label>\r\n        <input id=\"f" . (string) $p . "\" class=\"form-control\" type=\"text\" value=\"" . $rules[$i]["dest"] . "\" />\r\n        <p class=\"help-block\">Required. Must be IP address or alias. Any IP = 0.0.0.0</p>\r\n    </div>\r\n    <div class=\"form-group\">\r\n        <label for=\"g" . (string) $p . "\">Destination port</label>\r\n        <input id=\"g" . (string) $p . "\" class=\"form-control\" type=\"number\" min=\"1\" max=\"65535\" value=\"" . ($rules[$i]["dport"] ?? "") . "\" />\r\n        <p class=\"help-block\">Not required. Valid ports are between 1 - 65535.</p>\r\n    </div>\r\n    <div class=\"form-group\">\r\n        <label for=\"h" . (string) $p . "\">Protocol</label>\r\n        <select id=\"h" . (string) $p . "\" class=\"form-control\">";
    if ($rules[$i]["proto"] == "tcp") {
        echo "<option value=\"tcp\">TCP</option><option value=\"udp\">UDP</option><option value=\"icmp\">ICMP</option><option value=\"ipv6\">IPv6</option><option value=\"gre\">GRE</option><option value=\"l2tp\">L2TP</option>";
    } else {
        if ($rules[$i]["proto"] == "udp") {
            echo "<option value=\"udp\">UDP</option><option value=\"tcp\">TCP</option><option value=\"icmp\">ICMP</option><option value=\"ipv6\">IPv6</option><option value=\"gre\">GRE</option><option value=\"l2tp\">L2TP</option>";
        } else {
            if ($rules[$i]["proto"] == "icmp") {
                echo "<option value=\"icmp\">ICMP</option><option value=\"tcp\">TCP</option><option value=\"udp\">UDP</option><option value=\"ipv6\">IPv6</option><option value=\"gre\">GRE</option><option value=\"l2tp\">L2TP</option>";
            } else {
                if ($rules[$i]["proto"] == "ipv6") {
                    echo "<option value=\"ipv6\">IPv6</option><option value=\"tcp\">TCP</option><option value=\"udp\">UDP</option><option value=\"icmp\">ICMP</option><option value=\"gre\">GRE</option><option value=\"l2tp\">L2TP</option>";
                } else {
                    if ($rules[$i]["proto"] == "gre") {
                        echo "<option value=\"gre\">GRE</option><option value=\"tcp\">TCP</option><option value=\"udp\">UDP</option><option value=\"icmp\">ICMP</option><option value=\"ipv6\">IPv6</option><option value=\"l2tp\">L2TP</option>";
                    } else {
                        echo "<option value=\"l2tp\">L2TP</option><option value=\"tcp\">TCP</option><option value=\"udp\">UDP</option><option value=\"icmp\">ICMP</option><option value=\"ipv6\">IPv6</option><option value=\"gre\">GRE</option>";
                    }
                }
            }
        }
    }
    echo "</select>\r\n    </div>\r\n    <div class=\"form-group\">\r\n        <label for=\"i" . (string) $p . "\">Comment</label>\r\n        <input id=\"i" . (string) $p . "\" type=\"text\" class=\"form-control\" value=\"" . $rules[$i]["comment"] . "\" />\r\n        <p class=\"help-block\">Not required.</p>\r\n    </div>\r\n    <input type=\"submit\" value=\"Save\" class=\"btn btn-md btn-success\" id=\"fwredit" . (string) $p . "\" role=\"" . (string) $p . "\" />\r\n</form>\r\n                                    </div></div></div></div>";
}
echo "                        </table>\r\n                    </div>\r\n                </div>\r\n            </div>\r\n       </div>\r\n       <!-- TAB CONTENT END -->\r\n       <!-- TAB CONTENT -->\r\n       <div id=\"tab4\" class=\"tab-single tab-cnt\">\r\n             <div class=\"datacenters\">\r\n                <div class=\"col-md-12\">\r\n                    ";
if (count($vminfo["members"]) == 1) {
    $log = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $vminfo["members"][0]["vmid"] . "/firewall/log");
} else {
    for ($j = 0; $j < count($vminfo["members"]); $j++) {
        if ($vminfo["members"][$j]["name"] == $data->cloud_hostname) {
            $log = $pxAPI->get("/nodes/" . $data->node . "/qemu/" . $vminfo["members"][$j]["vmid"] . "/firewall/log");
        }
    }
}
echo "                    <textarea class=\"form-control\" rows=\"15\" disabled>\r\n";
for ($i = 0; $i < count($log); $i++) {
    if ($log[$i]["t"] == "no content") {
        echo "No log";
    } else {
        echo $log[$i]["t"];
    }
}
echo "                    </textarea><br /><br />\r\n                </div>\r\n            </div>\r\n       </div>\r\n       <!-- TAB CONTENT END -->\r\n      </div>\r\n     </div>\r\n    </section>\r\n    <!-- /Feature with tabs -->\r\n    <div class=\"modal fade\" id=\"fwoptions\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"fwoptionslabel\" aria-hidden=\"true\">\r\n        <div class=\"modal-dialog\">\r\n            <div class=\"modal-content\">\r\n                <div class=\"modal-header\">\r\n                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">&times;</span></button>\r\n                    <h4 class=\"modal-title\" id=\"fwoptionslabel\">Edit Firewall Options</h4>\r\n                </div>\r\n                <div class=\"modal-body\">\r\n                    <form role=\"form\">\r\n                        <div class=\"form-group\">\r\n                            <label>Enable firewall</label>\r\n                            <select class=\"form-control\" id=\"enableopts\">\r\n                                ";
if ($options["enable"] == 1) {
    echo "<option value=\"1\">Yes</option><option value=\"0\">No</option>";
} else {
    echo "<option value=\"0\">No</option><option value=\"1\">Yes</option>";
}
echo "                            </select>\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>Global inbound policy</label>\r\n                            <select class=\"form-control\" id=\"policyinopts\">\r\n                                ";
if (isset($options["policy_in"])) {
    if ($options["policy_in"] == "ACCEPT") {
        echo "<option value=\"ACCEPT\">ACCEPT</option><option value=\"DROP\">DROP</option><option value=\"REJECT\">REJECT</option>";
    } else {
        if ($options["policy_in"] == "DROP") {
            echo "<option value=\"DROP\">DROP</option><option value=\"ACCEPT\">ACCEPT</option><option value=\"REJECT\">REJECT</option>";
        } else {
            echo "<option value=\"REJECT\">REJECT</option><option value=\"ACCEPT\">ACCEPT</option><option value=\"DROP\">DROP</option>";
        }
    }
} else {
    echo "<option value=\"DROP\">DROP</option><option value=\"ACCEPT\">ACCEPT</option><option value=\"REJECT\">REJECT</option>";
}
echo "                            </select>\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>Global outbound policy</label>\r\n                            <select class=\"form-control\" id=\"policyoutopts\">\r\n                                ";
if (isset($options["policy_out"])) {
    if ($options["policy_out"] == "ACCEPT") {
        echo "<option value=\"ACCEPT\">ACCEPT</option><option value=\"DROP\">DROP</option><option value=\"REJECT\">REJECT</option>";
    } else {
        if ($options["policy_out"] == "DROP") {
            echo "<option value=\"DROP\">DROP</option><option value=\"ACCEPT\">ACCEPT</option><option value=\"REJECT\">REJECT</option>";
        } else {
            echo "<option value=\"REJECT\">REJECT</option><option value=\"ACCEPT\">ACCEPT</option><option value=\"DROP\">DROP</option>";
        }
    }
} else {
    echo "<option value=\"ACCEPT\">ACCEPT</option><option value=\"DROP\">DROP</option><option value=\"REJECT\">REJECT</option>";
}
echo "                            </select>\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>Inbound log level</label>\r\n                            <select class=\"form-control\" id=\"levelinopts\">\r\n                                ";
if (isset($options["log_level_in"])) {
    if ($options["log_level_in"] == "nolog") {
        echo "<option value=\"nolog\">nolog</option><option value=\"warning\">warning</option><option value=\"alert\">alert</option>";
    } else {
        if ($options["log_level_in"] == "warning") {
            echo "<option value=\"warning\">warning</option><option value=\"nolog\">nolog</option><option value=\"alert\">alert</option>";
        } else {
            echo "<option value=\"alert\">alert</option><option value=\"nolog\">nolog</option><option value=\"warning\">warning</option>";
        }
    }
} else {
    echo "<option value=\"nolog\">nolog</option><option value=\"warning\">warning</option><option value=\"alert\">alert</option>";
}
echo "                            </select>\r\n                        </div>\r\n                        <div class=\"form-group\">\r\n                            <label>Outbound log level</label>\r\n                            <select class=\"form-control\" id=\"leveloutopts\">\r\n                                ";
if (isset($options["log_level_out"])) {
    if ($options["log_level_out"] == "nolog") {
        echo "<option value=\"nolog\">nolog</option><option value=\"warning\">warning</option><option value=\"alert\">alert</option>";
    } else {
        if ($options["log_level_out"] == "warning") {
            echo "<option value=\"warning\">warning</option><option value=\"nolog\">nolog</option><option value=\"alert\">alert</option>";
        } else {
            echo "<option value=\"alert\">alert</option><option value=\"nolog\">nolog</option><option value=\"warning\">warning</option>";
        }
    }
} else {
    echo "<option value=\"nolog\">nolog</option><option value=\"warning\">warning</option><option value=\"alert\">alert</option>";
}
echo "                            </select>\r\n                        </div>\r\n                        <input type=\"submit\" value=\"Save\" class=\"btn btn-md btn-success\" id=\"fwoptionssave\" />\r\n                    </form>\r\n                </div>\r\n                <div class=\"modal-footer\">\r\n                    <button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Cancel</button>\r\n                </div>\r\n            </div>\r\n        </div>\r\n    </div>\r\n    <div class=\"modal fade\" id=\"addfwrule\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"addfwrulelabel\" aria-hidden=\"true\">\r\n    <div class=\"modal-dialog\">\r\n        <div class=\"modal-content\">\r\n            <div class=\"modal-header\">\r\n                <button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span aria-hidden=\"true\">&times;</span></button>\r\n                <h4 class=\"modal-title\" id=\"addfwrulelabel\">Add New Firewall Rule</h4>\r\n            </div>\r\n            <div class=\"modal-body\">\r\n                <form role=\"form\">\r\n                    <div class=\"form-group\">\r\n                        <label for=\"a\">Enable?</label>\r\n                        <select id=\"a\" class=\"form-control\">\r\n                            <option value=\"0\">false</option>\r\n                            <option value=\"1\">true</option>\r\n                        </select>\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label for=\"iface\">Network Interface</label>\r\n                        <select id=\"iface\" class=\"form-control\">\r\n                            <option value=\"net0\">net0 (public)</option>\r\n                            <option value=\"net1\">net1 (private)</option>\r\n                        </select>\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label for=\"b\">Direction</label>\r\n                        <select id=\"b\" class=\"form-control\">\r\n                            <option value=\"in\">in</option>\r\n                            <option value=\"out\">out</option>\r\n                        </select>\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label for=\"c\">Action</label>\r\n                        <select id=\"c\" class=\"form-control\">\r\n                            <option value=\"DROP\">DROP</option>\r\n                            <option value=\"REJECT\">REJECT</option>\r\n                            <option value=\"ACCEPT\">ACCEPT</option>\r\n                        </select>\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label for=\"d\">Source IP</label>\r\n                        <input id=\"d\" class=\"form-control\" type=\"text\" />\r\n                        <p class=\"help-block\">Required. Must be IP address. Any IP = 0.0.0.0</p>\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label for=\"e\">Source port</label>\r\n                        <input id=\"e\" class=\"form-control\" type=\"number\" min=\"1\" max=\"65535\" />\r\n                        <p class=\"help-block\">Not required. Valid ports are between 1 - 65535.</p>\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label for=\"f\">Destination IP</label>\r\n                        <input id=\"f\" class=\"form-control\" type=\"text\" />\r\n                        <p class=\"help-block\">Required. Must be IP address or alias. Any IP = 0.0.0.0</p>\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label for=\"g\">Destination port</label>\r\n                        <input id=\"g\" class=\"form-control\" type=\"number\" min=\"1\" max=\"65535\" />\r\n                        <p class=\"help-block\">Not required. Valid ports are between 1 - 65535.</p>\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label for=\"h\">Protocol</label>\r\n                        <select id=\"h\" class=\"form-control\">\r\n                            <option value=\"tcp\">TCP</option>\r\n                            <option value=\"udp\">UDP</option>\r\n                            <option value=\"icmp\">ICMP</option>\r\n                            <option value=\"ipv6\">IPv6</option>\r\n                            <option value=\"gre\">GRE</option>\r\n                            <option value=\"l2tp\">L2TP</option>\r\n                        </select>\r\n                    </div>\r\n                    <div class=\"form-group\">\r\n                        <label for=\"i\">Comment</label>\r\n                        <input id=\"i\" type=\"text\" class=\"form-control\" />\r\n                        <p class=\"help-block\">Not required.</p>\r\n                    </div>\r\n                    <input type=\"submit\" value=\"Save\" class=\"btn btn-md btn-success\" id=\"fwrulessave\" />\r\n                </form>\r\n            </div>\r\n            <div class=\"modal-footer\">\r\n                <button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Cancel</button>\r\n            </div>\r\n        </div>\r\n    </div>\r\n</div>\r\n</div>\r\n<input type=\"hidden\" value=\"";
echo $data->hb_account_id;
echo "\" id=\"kvminfo\" />\r\n";

?>
