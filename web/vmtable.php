<?php

if (!defined("constant")) {
    header("Location: index");
}
echo "<div class=\"col-md-12\">\r\n\t<h2 align=\"center\">Virtual Machines</h2>\r\n\t<div class=\"table-responsive\">\r\n\t\t<table class=\"table table-hover\">\r\n\t\t\t<tr>\r\n\t\t\t\t<th>Status</th>\r\n\t\t\t\t<th>Hostname</th>\r\n\t\t\t\t<th>Type</th>\r\n\t\t\t\t<th>Main IP</th>\r\n\t\t\t\t<th>Operating System</th>\r\n\t\t\t\t<th>RAM</th>\r\n\t\t\t\t<th>Storage</th>\r\n\t\t\t\t<th></th>\r\n\t\t\t</tr>\r\n\t\t\t";
$noLogin = false;
$results = $db->get("vncp_lxc_ct", ["user_id", "=", $user->data()->id]);
$data = $results->all();
if (0 < count($data)) {
    $firstNode = $data[0]->node;
    $node_results = $db->get("vncp_nodes", ["name", "=", $firstNode]);
    $node_data = $node_results->first();
    $pxAPI = new PVE2_API($node_data->hostname, $node_data->username, $node_data->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($node_data->password));
    if (!$pxAPI->login()) {
        $noLogin = true;
    }
}
for ($i = 0; $i < count($data); $i++) {
    $noLogin = false;
    if ($data[$i]->node != $firstNode) {
        $firstNode = $data[$i]->node;
        $node_results = $db->get("vncp_nodes", ["name", "=", $firstNode]);
        $node_data = $node_results->first();
        $pxAPI = new PVE2_API($node_data->hostname, $node_data->username, $node_data->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($node_data->password));
        if (!$pxAPI->login()) {
            $noLogin = true;
        }
    }
    if ($noLogin) {
        echo "<tr><td>Uh oh! We can't reach your node.</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
    } else {
        if ($data[$i]->suspended == 0 && !$noLogin) {
            $vminfo = $pxAPI->get("/pools/" . $data[$i]->pool_id);
            $info = $pxAPI->get("/nodes/" . $data[$i]->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/status/current");
            echo "<tr>";
            if ($info["status"] == "running") {
                echo "<td><img src=\"img/online.png\" /></td>";
            } else {
                echo "<td><img src=\"img/offline.png\" /></td>";
            }
            echo "<td>" . $info["name"] . "</td>";
            echo "<td><img src=\"img/lxc.png\" style=\"padding-right:5px;\" />LXC</td>";
            echo "<td>" . _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->ip) . "</td>";
            echo "<td>" . _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->os) . "</td>";
            echo "<td>" . _obfuscated_0D1E19192D05223B341C2E3609382F143730271D391232_($info["maxmem"], 0) . "</td>";
            echo "<td>" . _obfuscated_0D1E19192D05223B341C2E3609382F143730271D391232_($info["maxdisk"], 0) . "</td>";
            echo "<td><a href=\"manage?id=" . _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->hb_account_id) . "&virt=lxc\" class=\"btn btn-sm btn-info\">Manage</a></td>";
            echo "</tr>";
        } else {
            if ($data[$i]->suspended == 1) {
                $vminfo = $pxAPI->get("/pools/" . $data[$i]->pool_id);
                $info = $pxAPI->get("/nodes/" . $data[$i]->node . "/lxc/" . $vminfo["members"][0]["vmid"] . "/status/current");
                echo "<tr>";
                if ($info["status"] == "running") {
                    echo "<td><img src=\"img/online.png\" /></td>";
                } else {
                    echo "<td><img src=\"img/offline.png\" /></td>";
                }
                echo "<td>" . $info["name"] . "</td>";
                echo "<td><img src=\"img/lxc.png\" style=\"padding-right:5px;\" />LXC</td>";
                echo "<td>" . _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->ip) . "</td>";
                echo "<td>" . _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->os) . "</td>";
                echo "<td>" . _obfuscated_0D1E19192D05223B341C2E3609382F143730271D391232_($info["maxmem"], 0) . "</td>";
                echo "<td>" . _obfuscated_0D1E19192D05223B341C2E3609382F143730271D391232_($info["maxdisk"], 0) . "</td>";
                echo "<td><div class=\"tooltip-wrapper disabled\" data-title=\"Suspended\" data-placement=\"right\"><button class=\"btn btn-sm btn-info\" disabled>Manage</button></td></tr>";
            }
        }
    }
}
echo "\t\t\t";
$results = $db->get("vncp_kvm_ct", ["user_id", "=", $user->data()->id]);
$data = $results->all();
if (0 < count($data)) {
    $firstNode = $data[0]->node;
    $node_results = $db->get("vncp_nodes", ["name", "=", $firstNode]);
    $node_data = $node_results->first();
    $pxAPI = new PVE2_API($node_data->hostname, $node_data->username, $node_data->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($node_data->password));
    if (!$pxAPI->login()) {
        $noLogin = true;
    }
}
for ($i = 0; $i < count($data); $i++) {
    if ($data[$i]->node != $firstNode) {
        $firstNode = $data[$i]->node;
        $node_results = $db->get("vncp_nodes", ["name", "=", $firstNode]);
        $node_data = $node_results->first();
        $pxAPI = new PVE2_API($node_data->hostname, $node_data->username, $node_data->realm, _obfuscated_0D3C343005103213271D5C5B292F3D1D3D113836105B11_($node_data->password));
        if (!$pxAPI->login()) {
            $noLogin = true;
        }
    }
    if ($noLogin) {
        echo "<tr><td>Uh oh! We can't reach your node.</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
    } else {
        if ($data[$i]->suspended == 0 && !$noLogin) {
            $vminfo = $pxAPI->get("/pools/" . $data[$i]->pool_id);
            if (count($vminfo["members"]) == 1) {
                $info = $pxAPI->get("/nodes/" . $data[$i]->node . "/qemu/" . $vminfo["members"][0]["vmid"] . "/status/current");
                echo "<tr>";
                if ($info["status"] == "running") {
                    echo "<td><img src=\"img/online.png\" /></td>";
                } else {
                    echo "<td><img src=\"img/offline.png\" /></td>";
                }
                echo "<td>" . $info["name"] . "</td>";
                echo "<td><img src=\"img/kvm.png\" style=\"padding-right:5px;\" />KVM</td>";
                echo "<td>" . _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->ip) . "</td>";
                echo "<td>" . _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->os) . "</td>";
                echo "<td>" . _obfuscated_0D1E19192D05223B341C2E3609382F143730271D391232_($info["maxmem"], 0) . "</td>";
                echo "<td>" . _obfuscated_0D1E19192D05223B341C2E3609382F143730271D391232_($info["maxdisk"], 0) . "</td>";
                if ($data[$i]->from_template == 1) {
                    echo "<td><a href=\"manage?id=" . _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->hb_account_id) . "&virt=kvm\" class=\"btn btn-sm btn-warning template_setup_btn\">Setup</a></td>";
                } else {
                    echo "<td><a href=\"manage?id=" . _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->hb_account_id) . "&virt=kvm\" class=\"btn btn-sm btn-info\">Manage</a></td>";
                }
                echo "</tr>";
            } else {
                for ($j = 0; $j < count($vminfo["members"]); $j++) {
                    if ($vminfo["members"][$j]["name"] == $data[$i]->cloud_hostname) {
                        $info = $pxAPI->get("/nodes/" . $data[$i]->node . "/qemu/" . $vminfo["members"][$j]["vmid"] . "/status/current");
                        echo "<tr>";
                        if ($info["status"] == "running") {
                            echo "<td><img src=\"img/online.png\" /></td>";
                        } else {
                            echo "<td><img src=\"img/offline.png\" /></td>";
                        }
                        echo "<td>" . $info["name"] . "</td>";
                        echo "<td><img src=\"img/kvm.png\" style=\"padding-right:5px;\" />KVM</td>";
                        echo "<td>" . _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->ip) . "</td>";
                        echo "<td>" . _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->os) . "</td>";
                        echo "<td>" . _obfuscated_0D1E19192D05223B341C2E3609382F143730271D391232_($info["maxmem"], 0) . "</td>";
                        echo "<td>" . _obfuscated_0D1E19192D05223B341C2E3609382F143730271D391232_($info["maxdisk"], 0) . "</td>";
                        if ($data[$i]->from_template == 1) {
                            echo "<td><a href=\"manage?id=" . _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->hb_account_id) . "&virt=kvm\" class=\"btn btn-sm btn-warning template_setup_btn\">Setup</a></td>";
                        } else {
                            echo "<td><a href=\"manage?id=" . _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->hb_account_id) . "&virt=kvm\" class=\"btn btn-sm btn-info\">Manage</a></td>";
                        }
                        echo "</tr>";
                    }
                }
            }
        } else {
            if ($data[$i]->suspended == 1) {
                $vminfo = $pxAPI->get("/pools/" . $data[$i]->pool_id);
                $info = $pxAPI->get("/nodes/" . $data[$i]->node . "/qemu/" . $vminfo["members"][0]["vmid"] . "/status/current");
                echo "<tr>";
                if ($info["status"] == "running") {
                    echo "<td><img src=\"img/online.png\" /></td>";
                } else {
                    echo "<td><img src=\"img/offline.png\" /></td>";
                }
                echo "<td>" . $info["name"] . "</td>";
                echo "<td><img src=\"img/kvm.png\" style=\"padding-right:5px;\" />KVM</td>";
                echo "<td>" . _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->ip) . "</td>";
                echo "<td>" . _obfuscated_0D272F243C163F30393C2D05363D2D2B39015C40260C32_($data[$i]->os) . "</td>";
                echo "<td>" . _obfuscated_0D1E19192D05223B341C2E3609382F143730271D391232_($info["maxmem"], 0) . "</td>";
                echo "<td>" . _obfuscated_0D1E19192D05223B341C2E3609382F143730271D391232_($info["maxdisk"], 0) . "</td>";
                echo "<td><div class=\"tooltip-wrapper disabled\" data-title=\"Suspended\" data-placement=\"right\"><button class=\"btn btn-sm btn-info\" disabled>Manage</button></td></tr>";
            }
        }
    }
}
echo "\t\t</table>\r\n\t</div>\r\n</div>\r\n";

?>
