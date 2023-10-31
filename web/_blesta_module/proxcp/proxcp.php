<?php

class Proxcp extends Module
{
    private static $authors = [["name" => "ProxCP", "url" => "https://proxcp.com"]];
    const VERSION = "1.0.1";
    public function __construct()
    {
        $this->loadConfig(dirname(__FILE__) . DS . "config.json");
        Loader::loadComponents($this, ["Input"]);
    }
    public function getName()
    {
        return "proxcp";
    }
    public function getVersion()
    {
        return "1.0.1";
    }
    public function getAuthors()
    {
        return self::$authors;
    }
    public function getServiceName($service)
    {
        $key = "proxcp_name";
        foreach ($service->fields as $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_) {
            if ($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_->key == $key) {
                return $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_->value;
            }
        }
        return NULL;
    }
    public function moduleRowName()
    {
        return "Server";
    }
    public function moduleRowNamePlural()
    {
        return "Servers";
    }
    public function moduleGroupName()
    {
        return "ProxCP Group";
    }
    public function moduleRowMetaKey()
    {
        return "proxcp_name";
    }
    public function getLogo()
    {
        return "views/default/images/logo.png";
    }
    public function manageModule($module, &$vars)
    {
        $this->view = new View("manage", "default");
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView("components" . DS . "modules" . DS . "proxcp" . DS);
        Loader::loadHelpers($this, ["Form", "Html", "Widget"]);
        $this->view->set("module", $module);
        return $this->view->fetch();
    }
    public function manageAddRow(&$vars)
    {
        $this->view = new View("add_row", "default");
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView("components" . DS . "modules" . DS . "proxcp" . DS);
        Loader::loadHelpers($this, ["Form", "Html", "Widget"]);
        $this->view->set("vars", (object) $vars);
        return $this->view->fetch();
    }
    public function manageEditRow($module_row, &$vars)
    {
        $this->view = new View("edit_row", "default");
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView("components" . DS . "modules" . DS . "proxcp" . DS);
        Loader::loadHelpers($this, ["Form", "Html", "Widget"]);
        if (empty($vars)) {
            $vars = $module_row->meta;
        }
        $this->view->set("vars", (object) $vars);
        return $this->view->fetch();
    }
    public function addModuleRow(&$vars)
    {
        $_obfuscated_0D220F283D252C12163C312A3B0E081732102924055C11_ = ["server_name", "hostname", "user", "password"];
        $_obfuscated_0D18241A140518281C40120B2B123135052F2D3D060701_ = ["user", "password"];
        $this->Input->setRules($this->getRowRules($vars));
        if ($this->Input->validates($vars)) {
            $meta = [];
            foreach ($vars as $key => $value) {
                if (in_array($key, $_obfuscated_0D220F283D252C12163C312A3B0E081732102924055C11_)) {
                    $meta[] = ["key" => $key, "value" => $value, "encrypted" => in_array($key, $_obfuscated_0D18241A140518281C40120B2B123135052F2D3D060701_) ? 1 : 0];
                }
            }
            return $meta;
        }
    }
    public function editModuleRow($module_row, &$vars)
    {
        return $this->addModuleRow($vars);
    }
    public function deleteModuleRow($module_row)
    {
    }
    public function getPackageFields($vars = NULL)
    {
        Loader::loadHelpers($this, ["Html"]);
        $fields = new ModuleFields();
        $fields->setHtml("\r\n      <script type=\"text/javascript\">\r\n        \$(document).ready(function() {\r\n          \$('#proxcp_servicetype, #proxcp_isnat').change(function() {\r\n            fetchModuleOptions();\r\n          });\r\n        });\r\n      </script>\r\n    ");
        $module_row = NULL;
        if (isset($vars->module_group) && $vars->module_group == "") {
            if (isset($vars->module_row) && 0 < $vars->module_row) {
                $module_row = $this->getModuleRow($vars->module_row);
            } else {
                $_obfuscated_0D0814133302301A333E2B1A0516071A2812282C290D11_ = $this->getModuleRows();
                if (isset($_obfuscated_0D0814133302301A333E2B1A0516071A2812282C290D11_[0])) {
                    $module_row = $_obfuscated_0D0814133302301A333E2B1A0516071A2812282C290D11_[0];
                }
                unset($_obfuscated_0D0814133302301A333E2B1A0516071A2812282C290D11_);
            }
        } else {
            $_obfuscated_0D0814133302301A333E2B1A0516071A2812282C290D11_ = $this->getModuleRows($vars->module_group);
            if (isset($_obfuscated_0D0814133302301A333E2B1A0516071A2812282C290D11_[0])) {
                $module_row = $_obfuscated_0D0814133302301A333E2B1A0516071A2812282C290D11_[0];
            }
            unset($_obfuscated_0D0814133302301A333E2B1A0516071A2812282C290D11_);
        }
        $_obfuscated_0D3D1E25042A5C122F350D2C051A070E1E090A2D0A1711_ = ["kvm" => "KVM VPS", "pc" => "KVM Public Cloud", "lxc" => "LXC VPS"];
        $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ = $fields->label("ProxCP Service Type", "proxcp_servicetype");
        $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_->attach($fields->fieldSelect("meta[servicetype]", $_obfuscated_0D3D1E25042A5C122F350D2C051A070E1E090A2D0A1711_, $this->Html->ifSet($vars->meta["servicetype"]), ["id" => "proxcp_servicetype"]));
        $fields->setField($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
        unset($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
        unset($_obfuscated_0D3D1E25042A5C122F350D2C051A070E1E090A2D0A1711_);
        if (isset($vars->meta["servicetype"])) {
            $_obfuscated_0D3D1E25042A5C122F350D2C051A070E1E090A2D0A1711_ = $this->getNodes($module_row);
            $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ = $fields->label("Proxmox Node", "proxcp_node");
            $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_->attach($fields->fieldSelect("meta[node]", $_obfuscated_0D3D1E25042A5C122F350D2C051A070E1E090A2D0A1711_, $this->Html->ifSet($vars->meta["node"]), ["id" => "proxcp_node"]));
            $fields->setField($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
            unset($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
            unset($_obfuscated_0D3D1E25042A5C122F350D2C051A070E1E090A2D0A1711_);
        }
        if ($vars->meta["servicetype"] != "pc") {
            $_obfuscated_0D3D1E25042A5C122F350D2C051A070E1E090A2D0A1711_ = ["off" => "False", "on" => "True"];
            $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ = $fields->label("NAT VPS?");
            $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_->attach($fields->fieldSelect("meta[isnat]", $_obfuscated_0D3D1E25042A5C122F350D2C051A070E1E090A2D0A1711_, $this->Html->ifSet($vars->meta["isnat"]), ["id" => "proxcp_isnat"]));
            $fields->setField($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
            unset($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
            unset($_obfuscated_0D3D1E25042A5C122F350D2C051A070E1E090A2D0A1711_);
        }
        if ($vars->meta["isnat"] == "on" && $vars->meta["servicetype"] != "pc") {
            $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ = $fields->label("NAT Port Limit", "proxcp_natports");
            $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_->attach($fields->fieldText("meta[natports]", $this->Html->ifSet($vars->meta["natports"]), ["id" => "proxcp_natports", "class" => "inline"]));
            $fields->setField($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
            unset($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
            $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ = $fields->label("NAT Domain Limit", "proxcp_natdomains");
            $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_->attach($fields->fieldText("meta[natdomains]", $this->Html->ifSet($vars->meta["natdomains"]), ["id" => "proxcp_natdomains", "class" => "inline"]));
            $fields->setField($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
            unset($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
        }
        $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ = $fields->label("Storage Size (GB)", "proxcp_storagesize");
        $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_->attach($fields->fieldText("meta[storagesize]", $this->Html->ifSet($vars->meta["storagesize"]), ["id" => "proxcp_storagesize", "class" => "inline"]));
        $fields->setField($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
        unset($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
        $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ = $fields->label("CPU Cores", "proxcp_cpucores");
        $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_->attach($fields->fieldText("meta[cpucores]", $this->Html->ifSet($vars->meta["cpucores"]), ["id" => "proxcp_cpucores", "class" => "inline"]));
        $fields->setField($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
        unset($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
        $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ = $fields->label("RAM (MB)", "proxcp_ram");
        $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_->attach($fields->fieldText("meta[ram]", $this->Html->ifSet($vars->meta["ram"]), ["id" => "proxcp_ram", "class" => "inline"]));
        $fields->setField($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
        unset($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
        $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ = $fields->label("Bandwidth Limit (GB)", "proxcp_bwlimit");
        $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_->attach($fields->fieldText("meta[bwlimit]", $this->Html->ifSet($vars->meta["bwlimit"]), ["id" => "proxcp_bwlimit", "class" => "inline"]));
        $fields->setField($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
        unset($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
        $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ = $fields->label("VLAN Tag", "proxcp_vlantag");
        $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_->attach($fields->fieldText("meta[vlantag]", $this->Html->ifSet($vars->meta["vlantag"]), ["id" => "proxcp_vlantag", "class" => "inline"]));
        $fields->setField($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
        unset($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
        $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ = $fields->label("Port Speed", "proxcp_portspeed");
        $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_->attach($fields->fieldText("meta[portspeed]", $this->Html->ifSet($vars->meta["portspeed"]), ["id" => "proxcp_portspeed", "class" => "inline"]));
        $fields->setFields($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
        unset($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
        $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ = $fields->label("Backup Limit", "proxcp_backuplimit");
        $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_->attach($fields->fieldText("meta[backuplimit]", $this->Html->ifSet($vars->meta["backuplimit"]), ["id" => "proxcp_backuplimit", "class" => "inline"]));
        $fields->setFields($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
        unset($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
        if ($vars->meta["servicetype"] == "pc") {
            $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ = $fields->label("IP Limit", "proxcp_iplimit");
            $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_->attach($fields->fieldText("meta[iplimit]", $this->Html->ifSet($vars->meta["iplimit"]), ["id" => "proxcp_iplimit", "class" => "inline"]));
            $fields->setField($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
            unset($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
        }
        if ($vars->meta["servicetype"] == "kvm") {
            $_obfuscated_0D3D1E25042A5C122F350D2C051A070E1E090A2D0A1711_ = ["e1000" => "Intel E1000", "virtio" => "VirtIO"];
            $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ = $fields->label("NIC Driver", "proxcp_nicdriver");
            $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_->attach($fields->fieldSelect("meta[nicdriver]", $_obfuscated_0D3D1E25042A5C122F350D2C051A070E1E090A2D0A1711_, $this->Html->ifSet($vars->meta["nicdriver"]), ["id" => "proxcp_nicdriver"]));
            $fields->setField($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
            unset($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
            unset($_obfuscated_0D3D1E25042A5C122F350D2C051A070E1E090A2D0A1711_);
            $_obfuscated_0D3D1E25042A5C122F350D2C051A070E1E090A2D0A1711_ = ["kvm64" => "KVM64", "qemu64" => "QEMU64", "host" => "Host passthrough"];
            $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ = $fields->label("CPU Type", "proxcp_cputype");
            $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_->attach($fields->fieldSelect("meta[cputype]", $_obfuscated_0D3D1E25042A5C122F350D2C051A070E1E090A2D0A1711_, $this->Html->ifSet($vars->meta["cputype"]), ["id" => "proxcp_cputype"]));
            $fields->setField($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
            unset($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
            unset($_obfuscated_0D3D1E25042A5C122F350D2C051A070E1E090A2D0A1711_);
            $_obfuscated_0D3D1E25042A5C122F350D2C051A070E1E090A2D0A1711_ = ["ide" => "IDE", "virtio" => "VirtIO"];
            $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ = $fields->label("Storage Driver", "proxcp_storagedriver");
            $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_->attach($fields->fieldSelect("meta[storagedriver]", $_obfuscated_0D3D1E25042A5C122F350D2C051A070E1E090A2D0A1711_, $this->Html->ifSet($vars->meta["storagedriver"]), ["id" => "proxcp_storagedriver"]));
            $fields->setField($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
            unset($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
            unset($_obfuscated_0D3D1E25042A5C122F350D2C051A070E1E090A2D0A1711_);
            $_obfuscated_0D3D1E25042A5C122F350D2C051A070E1E090A2D0A1711_ = ["iso" => "Manual ISO file", "template" => "Automatic template"];
            $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_ = $fields->label("Default OS Installation Type", "proxcp_osinstalltype");
            $_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_->attach($fields->fieldSelect("meta[osinstalltype]", $_obfuscated_0D3D1E25042A5C122F350D2C051A070E1E090A2D0A1711_, $this->Html->ifSet($vars->meta["osinstalltype"]), ["id" => "proxcp_osinstalltype"]));
            $fields->setField($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
            unset($_obfuscated_0D1D225C31233615082D15212C2D32080B36340B312A11_);
            unset($_obfuscated_0D3D1E25042A5C122F350D2C051A070E1E090A2D0A1711_);
        }
        return $fields;
    }
    public function validateService($package, $vars = NULL, $edit = false)
    {
        $_obfuscated_0D27311B29292B1C050E3F3E120C2F3E1D2B0940212301_ = ["proxcp_hostname" => ["format" => ["rule" => [[$this, "validateHostname"]], "message" => "The hostname appears to be invalid. It must include a TLD."]], "proxcp_os" => ["empty" => ["rule" => "isEmpty", "negate" => true, "message" => "You must select a valid Operating System."]]];
        if ($edit) {
            $_obfuscated_0D27311B29292B1C050E3F3E120C2F3E1D2B0940212301_["proxcp_hostname"]["format"]["if_set"] = true;
            unset($_obfuscated_0D27311B29292B1C050E3F3E120C2F3E1D2B0940212301_["proxcp_os"]);
        }
        $this->Input->setRules($_obfuscated_0D27311B29292B1C050E3F3E120C2F3E1D2B0940212301_);
        return $this->Input->validates($vars["configoptions"]);
    }
    public function validateHostname($host_name)
    {
        if (255 < strlen($host_name)) {
            return false;
        }
        return $this->Input->matches($host_name, "/^([a-z0-9]|[a-z0-9][a-z0-9\\-]{0,61}[a-z0-9])(\\.([a-z0-9]|[a-z0-9][a-z0-9\\-]{0,61}[a-z0-9]))+\$/");
    }
    public function addService($package, $vars = NULL, $parent_package = NULL, $parent_service = NULL, $status = "pending")
    {
        $row = $this->getModuleRow();
        $_obfuscated_0D2205023C27334038152E081B3D375C0721141E052532_ = ["proxcp_hostname" => $vars["configoptions"]["proxcp_hostname"], "proxcp_os" => $vars["configoptions"]["proxcp_os"], "proxcp_password" => $this->generatePassword()];
        $this->validateService($package, $vars);
        if ($this->Input->errors()) {
            return NULL;
        }
        if (isset($vars["use_module"]) && $vars["use_module"] == "true") {
            Loader::loadModels($this, ["Clients"]);
            $_obfuscated_0D1B3B3E081F062F3501373E3825352317300A0D103C32_ = $this->Clients->get(isset($vars["client_id"]) ? $vars["client_id"] : 0, false);
            try {
                $type = $package->meta->servicetype;
                $_obfuscated_0D26335C2F1A1D1A3B2609150F150E3F5C050D1C281232_ = $_obfuscated_0D1B3B3E081F062F3501373E3825352317300A0D103C32_->email;
                $_obfuscated_0D3127011610283517215C261811062C310A350E232711_ = $_obfuscated_0D2205023C27334038152E081B3D375C0721141E052532_["proxcp_password"];
                $_obfuscated_0D083B141D2F16332E0D0D08373C2D1915371B1F1C3601_ = $vars["client_id"];
                $_obfuscated_0D181C28271F3C3C303F0A02382A1712250E2E2C242F01_ = $package->meta->node;
                $_obfuscated_0D032610073C0F30382C291B2D373E1B0736130B330D11_ = $vars["configoptions"]["proxcp_os"];
                $_obfuscated_0D243C322F0916090A312B3505051F3401065B0A340522_ = $vars["configoptions"]["proxcp_os"];
                $_obfuscated_0D1A2F231307123F3B2A133310191A1E081A40180B1422_ = $vars["service_id"];
                $_obfuscated_0D393D140607051517020E305C1101233D010213074001_ = "client_" . $_obfuscated_0D083B141D2F16332E0D0D08373C2D1915371B1F1C3601_ . "_" . $_obfuscated_0D1A2F231307123F3B2A133310191A1E081A40180B1422_;
                $_obfuscated_0D2A252D2B3C1809053505092219372D310D3130030F01_ = $vars["configoptions"]["proxcp_hostname"];
                $_obfuscated_0D360C262A0D1723113727283936265B3203402A2C3022_ = $package->meta->storagesize;
                $_obfuscated_0D253B382F01041B0C1F013016130D29401A5C27191311_ = $package->meta->cpucores;
                $_obfuscated_0D022217163D09333F3525252C40223717373206380822_ = $package->meta->ram;
                $_obfuscated_0D09100C3F1A16072E5C3B221F013B3B3817193F2E3001_ = $package->meta->nicdriver;
                $_obfuscated_0D29173E371F12023039390B1B222C1E2D0B2E36373D01_ = $package->meta->cputype;
                $_obfuscated_0D232D1D222110071E3B233331240A2A11121B10170522_ = $package->meta->storagedriver;
                $_obfuscated_0D0922121F3B4038340C28323F1C5C060315215C373501_ = $package->meta->osinstalltype;
                $_obfuscated_0D5B121B3D071C3B371B1B03310905190E2D1424041B11_ = $vars["configoptions"]["proxcp_os"];
                $_obfuscated_0D343F240E2C34093109262C2238182F1B321B25331C32_ = $package->meta->bwlimit;
                $_obfuscated_0D3435020624171E1B270406152E07242F023709133311_ = isset($package->meta->iplimit) ? $package->meta->iplimit : 0;
                $_obfuscated_0D330E2E2D0D222E0B3D261E2108312838305C04271322_ = $package->meta->isnat;
                $_obfuscated_0D0C0E322B045C1636260F3E403B265B243E2A1B5C3311_ = $package->meta->natports;
                $_obfuscated_0D1C5B3B2B2C2D1E13082D213E09164027371F04092E01_ = $package->meta->natdomains;
                $_obfuscated_0D2C070E335B0F070F10344025353C372E3E165C1E0F32_ = $package->meta->vlantag;
                $_obfuscated_0D020D0A35402427260B173E17100F0736240C26233201_ = $package->meta->portspeed;
                $_obfuscated_0D1E153F292506013908065B3C03060414042F2F393732_ = $package->meta->backuplimit;
                if ($_obfuscated_0D330E2E2D0D222E0B3D261E2108312838305C04271322_ != "on") {
                    $_obfuscated_0D330E2E2D0D222E0B3D261E2108312838305C04271322_ = "off";
                }
                if (empty($vlantag)) {
                    $vlantag = "0";
                }
                if (empty($portspeed)) {
                    $portspeed = "0";
                }
                if (empty($backuplimit)) {
                    $backuplimit = "0";
                }
                if ($type == "kvm") {
                    $_obfuscated_0D24092434293814362F2A333D0F0B1E013B221D385B11_ = "api_id=" . $row->meta->user . "&api_key=" . $row->meta->password . "&action=createkvm&userid=" . $_obfuscated_0D083B141D2F16332E0D0D08373C2D1915371B1F1C3601_ . "&node=" . $node . "&osfriendly=" . $_obfuscated_0D032610073C0F30382C291B2D373E1B0736130B330D11_ . "&ostype=" . $_obfuscated_0D243C322F0916090A312B3505051F3401065B0A340522_ . "&hbid=" . $_obfuscated_0D1A2F231307123F3B2A133310191A1E081A40180B1422_ . "&poolid=" . $_obfuscated_0D393D140607051517020E305C1101233D010213074001_ . "&hostname=" . $_obfuscated_0D2A252D2B3C1809053505092219372D310D3130030F01_ . "&storage=" . $_obfuscated_0D360C262A0D1723113727283936265B3203402A2C3022_ . "&cpu=" . $cpucores . "&ram=" . $ram . "&nicdriver=" . $nicdriver . "&cputype=" . $cputype . "&strdriver=" . $_obfuscated_0D232D1D222110071E3B233331240A2A11121B10170522_ . "&osinstalltype=" . $_obfuscated_0D0922121F3B4038340C28323F1C5C060315215C373501_ . "&ostemp=" . $_obfuscated_0D5B121B3D071C3B371B1B03310905190E2D1424041B11_ . "&bwlimit=" . $_obfuscated_0D343F240E2C34093109262C2238182F1B321B25331C32_ . "&email=" . base64_encode($email) . "&pw=" . base64_encode($password) . "&nat=" . $_obfuscated_0D330E2E2D0D222E0B3D261E2108312838305C04271322_ . "&natp=" . $natports . "&natd=" . $natdomains . "&vlantag=" . $vlantag . "&portspeed=" . $portspeed . "&backuplimit=" . $backuplimit;
                } else {
                    if ($type == "pc") {
                        $_obfuscated_0D24092434293814362F2A333D0F0B1E013B221D385B11_ = "api_id=" . $row->meta->user . "&api_key=" . $row->meta->password . "&action=createcloud&userid=" . $_obfuscated_0D083B141D2F16332E0D0D08373C2D1915371B1F1C3601_ . "&hbid=" . $_obfuscated_0D1A2F231307123F3B2A133310191A1E081A40180B1422_ . "&poolid=" . $_obfuscated_0D393D140607051517020E305C1101233D010213074001_ . "&node=" . $node . "&howmanyips=" . $_obfuscated_0D3435020624171E1B270406152E07242F023709133311_ . "&cpu=" . $cpucores . "&cputype=" . $cputype . "&ram=" . $ram . "&storage=" . $_obfuscated_0D360C262A0D1723113727283936265B3203402A2C3022_ . "&email=" . base64_encode($email) . "&pw=" . base64_encode($password);
                    } else {
                        if ($type == "lxc") {
                            $_obfuscated_0D24092434293814362F2A333D0F0B1E013B221D385B11_ = "api_id=" . $row->meta->user . "&api_key=" . $row->meta->password . "&action=createlxc&userid=" . $_obfuscated_0D083B141D2F16332E0D0D08373C2D1915371B1F1C3601_ . "&node=" . $node . "&osfriendly=" . $_obfuscated_0D032610073C0F30382C291B2D373E1B0736130B330D11_ . "&ostype=" . $_obfuscated_0D243C322F0916090A312B3505051F3401065B0A340522_ . "&hbid=" . $_obfuscated_0D1A2F231307123F3B2A133310191A1E081A40180B1422_ . "&poolid=" . $_obfuscated_0D393D140607051517020E305C1101233D010213074001_ . "&hostname=" . $_obfuscated_0D2A252D2B3C1809053505092219372D310D3130030F01_ . "&storage=" . $_obfuscated_0D360C262A0D1723113727283936265B3203402A2C3022_ . "&cpu=" . $cpucores . "&ram=" . $ram . "&bwlimit=" . $bwlimit . "&email=" . base64_encode($email) . "&pw=" . base64_encode($password) . "&nat=" . $_obfuscated_0D330E2E2D0D222E0B3D261E2108312838305C04271322_ . "&natp=" . $natports . "&natd=" . $natdomains . "&vlantag=" . $vlantag . "&portspeed=" . $portspeed . "&backuplimit=" . $backuplimit;
                        } else {
                            $this->log("Invalid ProxCP Service Type", "input", true);
                        }
                    }
                }
                $_obfuscated_0D103D3105063F2802305B291F2F0C145B301A0B3B2301_ = "https://" . $row->meta->hostname . "/api.php";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $_obfuscated_0D103D3105063F2802305B291F2F0C145B301A0B3B2301_);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $_obfuscated_0D24092434293814362F2A333D0F0B1E013B221D385B11_);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_USERAGENT, "ProxCP Blesta Module");
                $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_ = curl_exec($ch);
                if (curl_error($ch)) {
                    $this->log("Unable to connect: " . curl_errno($ch), curl_error($ch), "input", true);
                } else {
                    if (empty($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_)) {
                        $this->log("Empty response", "input", true);
                    }
                }
                curl_close($ch);
                $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_ = json_decode($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_, true);
                if (is_null($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_)) {
                    $this->log("Invalid response format", "input", true);
                }
                if ($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_["success"] == 0) {
                    $this->log($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_["message"], "input", true);
                }
                if (array_key_exists("data", $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_) && count($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_["data"] == 2) && $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_["data"][1] == -1) {
                    $_obfuscated_0D2205023C27334038152E081B3D375C0721141E052532_["proxcp_password"] = "N/A - you already have an account.";
                }
            } catch (Exception $_obfuscated_0D22060E2108273730182D2E1202232603100B38063801_) {
                $this->log($_obfuscated_0D2205023C27334038152E081B3D375C0721141E052532_, $_obfuscated_0D22060E2108273730182D2E1202232603100B38063801_->getMessage(), "input", true);
            }
        }
        return [["key" => "proxcp_hostname", "value" => $_obfuscated_0D2205023C27334038152E081B3D375C0721141E052532_["proxcp_hostname"], "encrypted" => 0], ["key" => "proxcp_os", "value" => $_obfuscated_0D2205023C27334038152E081B3D375C0721141E052532_["proxcp_os"], "encrypted" => 0], ["key" => "proxcp_password", "value" => $_obfuscated_0D2205023C27334038152E081B3D375C0721141E052532_["proxcp_password"], "encrypted" => 0], ["key" => "proxcp_username", "value" => isset($email) ? $email : "", "encrypted" => 0]];
    }
    public function cancelService($package, $service, $parent_package = NULL, $parent_service = NULL)
    {
        if ($row = $this->getModuleRow()) {
            $_obfuscated_0D3240062F1C072509380B2D34140E17231B1F0C141C01_ = $this->serviceFieldsToObject($service->fields);
            try {
                $type = $package->meta->servicetype;
                $_obfuscated_0D0E18333B10142F353E1714251E1B3D2F3F28265C3D11_ = $service->id;
                $_obfuscated_0D083B141D2F16332E0D0D08373C2D1915371B1F1C3601_ = $service->client_id;
                $_obfuscated_0D393D140607051517020E305C1101233D010213074001_ = "client_" . $_obfuscated_0D083B141D2F16332E0D0D08373C2D1915371B1F1C3601_ . "_" . $_obfuscated_0D0E18333B10142F353E1714251E1B3D2F3F28265C3D11_;
                $_obfuscated_0D103D3105063F2802305B291F2F0C145B301A0B3B2301_ = "https://" . $row->meta->hostname . "/api.php";
                $_obfuscated_0D24092434293814362F2A333D0F0B1E013B221D385B11_ = "api_id=" . $row->meta->user . "&api_key=" . $row->meta->password . "&action=terminate&type=" . $type . "&hbid=" . $_obfuscated_0D0E18333B10142F353E1714251E1B3D2F3F28265C3D11_ . "&poolid=" . $_obfuscated_0D393D140607051517020E305C1101233D010213074001_ . "&userid=" . $_obfuscated_0D083B141D2F16332E0D0D08373C2D1915371B1F1C3601_;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $_obfuscated_0D103D3105063F2802305B291F2F0C145B301A0B3B2301_);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $_obfuscated_0D24092434293814362F2A333D0F0B1E013B221D385B11_);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_USERAGENT, "ProxCP Blesta Module");
                $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_ = curl_exec($ch);
                if (curl_error($ch)) {
                    $this->log("Unable to connect: " . curl_errno($ch), curl_error($ch), "input", true);
                } else {
                    if (empty($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_)) {
                        $this->log("Empty response", "input", true);
                    }
                }
                curl_close($ch);
                $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_ = json_decode($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_, true);
                if (is_null($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_)) {
                    $this->log("Invalid response format", "input", true);
                }
                if ($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_["success"] == 0) {
                    $this->log($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_["message"], "input", true);
                }
            } catch (Exception $_obfuscated_0D22060E2108273730182D2E1202232603100B38063801_) {
                $this->log($_obfuscated_0D3240062F1C072509380B2D34140E17231B1F0C141C01_, $_obfuscated_0D22060E2108273730182D2E1202232603100B38063801_->getMessage(), "input", true);
            }
        }
    }
    public function suspendService($package, $service, $parent_package = NULL, $parent_service = NULL)
    {
        if ($row = $this->getModuleRow()) {
            $_obfuscated_0D3240062F1C072509380B2D34140E17231B1F0C141C01_ = $this->serviceFieldsToObject($service->fields);
            try {
                $type = $package->meta->servicetype;
                $_obfuscated_0D0E18333B10142F353E1714251E1B3D2F3F28265C3D11_ = $service->id;
                $_obfuscated_0D083B141D2F16332E0D0D08373C2D1915371B1F1C3601_ = $service->client_id;
                $_obfuscated_0D393D140607051517020E305C1101233D010213074001_ = "client_" . $_obfuscated_0D083B141D2F16332E0D0D08373C2D1915371B1F1C3601_ . "_" . $_obfuscated_0D0E18333B10142F353E1714251E1B3D2F3F28265C3D11_;
                $_obfuscated_0D103D3105063F2802305B291F2F0C145B301A0B3B2301_ = "https://" . $row->meta->hostname . "/api.php";
                $_obfuscated_0D24092434293814362F2A333D0F0B1E013B221D385B11_ = "api_id=" . $row->meta->user . "&api_key=" . $row->meta->password . "&action=suspend&type=" . $type . "&hbid=" . $_obfuscated_0D0E18333B10142F353E1714251E1B3D2F3F28265C3D11_ . "&poolid=" . $_obfuscated_0D393D140607051517020E305C1101233D010213074001_;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $_obfuscated_0D103D3105063F2802305B291F2F0C145B301A0B3B2301_);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $_obfuscated_0D24092434293814362F2A333D0F0B1E013B221D385B11_);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_USERAGENT, "ProxCP Blesta Module");
                $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_ = curl_exec($ch);
                if (curl_error($ch)) {
                    $this->log("Unable to connect: " . curl_errno($ch), curl_error($ch), "input", true);
                } else {
                    if (empty($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_)) {
                        $this->log("Empty response", "input", true);
                    }
                }
                curl_close($ch);
                $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_ = json_decode($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_, true);
                if (is_null($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_)) {
                    $this->log("Invalid response format", "input", true);
                }
                if ($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_["success"] == 0) {
                    $this->log($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_["message"], "input", true);
                }
            } catch (Exception $_obfuscated_0D22060E2108273730182D2E1202232603100B38063801_) {
                $this->log($_obfuscated_0D3240062F1C072509380B2D34140E17231B1F0C141C01_, $_obfuscated_0D22060E2108273730182D2E1202232603100B38063801_->getMessage(), "input", true);
            }
        }
        return [["key" => "proxcp_hostname", "value" => $_obfuscated_0D3240062F1C072509380B2D34140E17231B1F0C141C01_->proxcp_hostname, "encrypted" => 0], ["key" => "proxcp_os", "value" => $_obfuscated_0D3240062F1C072509380B2D34140E17231B1F0C141C01_->proxcp_os, "encrypted" => 0]];
    }
    public function unsuspendService($package, $service, $parent_package = NULL, $parent_service = NULL)
    {
        if ($row = $this->getModuleRow()) {
            $_obfuscated_0D3240062F1C072509380B2D34140E17231B1F0C141C01_ = $this->serviceFieldsToObject($service->fields);
            try {
                $type = $package->meta->servicetype;
                $_obfuscated_0D0E18333B10142F353E1714251E1B3D2F3F28265C3D11_ = $service->id;
                $_obfuscated_0D083B141D2F16332E0D0D08373C2D1915371B1F1C3601_ = $service->client_id;
                $_obfuscated_0D393D140607051517020E305C1101233D010213074001_ = "client_" . $_obfuscated_0D083B141D2F16332E0D0D08373C2D1915371B1F1C3601_ . "_" . $_obfuscated_0D0E18333B10142F353E1714251E1B3D2F3F28265C3D11_;
                $_obfuscated_0D103D3105063F2802305B291F2F0C145B301A0B3B2301_ = "https://" . $row->meta->hostname . "/api.php";
                $_obfuscated_0D24092434293814362F2A333D0F0B1E013B221D385B11_ = "api_id=" . $row->meta->user . "&api_key=" . $row->meta->password . "&action=unsuspend&type=" . $type . "&hbid=" . $_obfuscated_0D0E18333B10142F353E1714251E1B3D2F3F28265C3D11_ . "&poolid=" . $_obfuscated_0D393D140607051517020E305C1101233D010213074001_;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $_obfuscated_0D103D3105063F2802305B291F2F0C145B301A0B3B2301_);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $_obfuscated_0D24092434293814362F2A333D0F0B1E013B221D385B11_);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_USERAGENT, "ProxCP Blesta Module");
                $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_ = curl_exec($ch);
                if (curl_error($ch)) {
                    $this->log("Unable to connect: " . curl_errno($ch), curl_error($ch), "input", true);
                } else {
                    if (empty($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_)) {
                        $this->log("Empty response", "input", true);
                    }
                }
                curl_close($ch);
                $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_ = json_decode($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_, true);
                if (is_null($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_)) {
                    $this->log("Invalid response format", "input", true);
                }
                if ($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_["success"] == 0) {
                    $this->log($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_["message"], "input", true);
                }
            } catch (Exception $_obfuscated_0D22060E2108273730182D2E1202232603100B38063801_) {
                $this->log($_obfuscated_0D3240062F1C072509380B2D34140E17231B1F0C141C01_, $_obfuscated_0D22060E2108273730182D2E1202232603100B38063801_->getMessage(), "input", true);
            }
        }
        return [["key" => "proxcp_hostname", "value" => $_obfuscated_0D3240062F1C072509380B2D34140E17231B1F0C141C01_->proxcp_hostname, "encrypted" => 0], ["key" => "proxcp_os", "value" => $_obfuscated_0D3240062F1C072509380B2D34140E17231B1F0C141C01_->proxcp_os, "encrypted" => 0]];
    }
    public function getEmailTags()
    {
        return ["module" => ["server_name", "hostname"], "service" => ["proxcp_password", "proxcp_hostname", "proxcp_username"]];
    }
    public function getAdminServiceInfo($service, $package)
    {
        $row = $this->getModuleRow();
        $this->view = new View("admin_service_info", "default");
        $this->view->basea_uri = $this->base_uri;
        $this->view->setDefaultView("components" . DS . "modules" . DS . "proxcp" . DS);
        Loader::loadHelpers($this, ["Form", "Html"]);
        $this->view->set("module_row", $row);
        $this->view->set("package", $package);
        $this->view->set("service", $service);
        $this->view->set("service_fields", $this->serviceFieldsToObject($service->fields));
        $_obfuscated_0D0E291E3B111A382B1726112D2D3022362A141D091C32_ = "";
        if ($service->status == "active") {
            $_obfuscated_0D0E291E3B111A382B1726112D2D3022362A141D091C32_ = $this->getServiceDetails($service, $package, $row);
        } else {
            $_obfuscated_0D0E291E3B111A382B1726112D2D3022362A141D091C32_ = ["node" => "N/A", "ip" => "N/A", "status" => "N/A", "os" => "N/A"];
        }
        $this->view->set("service_node", $_obfuscated_0D0E291E3B111A382B1726112D2D3022362A141D091C32_["node"]);
        $this->view->set("service_ip", $_obfuscated_0D0E291E3B111A382B1726112D2D3022362A141D091C32_["ip"]);
        $this->view->set("service_status", $_obfuscated_0D0E291E3B111A382B1726112D2D3022362A141D091C32_["status"]);
        $this->view->set("service_os", $_obfuscated_0D0E291E3B111A382B1726112D2D3022362A141D091C32_["os"]);
        return $this->view->fetch();
    }
    public function getClientServiceInfo($service, $package)
    {
        $row = $this->getModuleRow();
        $this->view = new View("client_service_info", "default");
        $this->view->basea_uri = $this->base_uri;
        $this->view->setDefaultView("components" . DS . "modules" . DS . "proxcp" . DS);
        Loader::loadHelpers($this, ["Form", "Html"]);
        $this->view->set("module_row", $row);
        $this->view->set("package", $package);
        $this->view->set("service", $service);
        $this->view->set("service_fields", $this->serviceFieldsToObject($service->fields));
        $_obfuscated_0D0E291E3B111A382B1726112D2D3022362A141D091C32_ = "";
        if ($service->status == "active") {
            $_obfuscated_0D0E291E3B111A382B1726112D2D3022362A141D091C32_ = $this->getServiceDetails($service, $package, $row);
        } else {
            $_obfuscated_0D0E291E3B111A382B1726112D2D3022362A141D091C32_ = ["node" => "N/A", "ip" => "N/A", "status" => "N/A", "os" => "N/A"];
        }
        $this->view->set("service_node", $_obfuscated_0D0E291E3B111A382B1726112D2D3022362A141D091C32_["node"]);
        $this->view->set("service_ip", $_obfuscated_0D0E291E3B111A382B1726112D2D3022362A141D091C32_["ip"]);
        $this->view->set("service_status", $_obfuscated_0D0E291E3B111A382B1726112D2D3022362A141D091C32_["status"]);
        $this->view->set("service_os", $_obfuscated_0D0E291E3B111A382B1726112D2D3022362A141D091C32_["os"]);
        return $this->view->fetch();
    }
    private function getServiceDetails($service, $package, $row)
    {
        $node = "";
        $ip = "";
        try {
            $type = $package->meta->servicetype;
            $_obfuscated_0D0E18333B10142F353E1714251E1B3D2F3F28265C3D11_ = $service->id;
            $_obfuscated_0D103D3105063F2802305B291F2F0C145B301A0B3B2301_ = "https://" . $row->meta->hostname . "/api.php";
            $_obfuscated_0D24092434293814362F2A333D0F0B1E013B221D385B11_ = "api_id=" . $row->meta->user . "&api_key=" . $row->meta->password . "&action=getdetails&type=" . $type . "&hbid=" . $_obfuscated_0D0E18333B10142F353E1714251E1B3D2F3F28265C3D11_;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $_obfuscated_0D103D3105063F2802305B291F2F0C145B301A0B3B2301_);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $_obfuscated_0D24092434293814362F2A333D0F0B1E013B221D385B11_);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, "ProxCP Blesta Module");
            $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_ = curl_exec($ch);
            if (curl_error($ch)) {
                $this->log("Unable to connect: " . curl_errno($ch) . " - " . curl_error($ch), "input", true);
            } else {
                if (empty($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_)) {
                    $this->log("Empty response", "input", true);
                }
            }
            curl_close($ch);
            $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_ = json_decode($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_, true);
            if (is_null($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_)) {
                $this->log("Invalid response format", "input", true);
            }
            if ($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_["success"] == 0) {
                $this->log($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_["message"], "input", true);
            }
            $node = $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_["data"][0];
            $ip = $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_["data"][1];
            $status = $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_["data"][3];
            $_obfuscated_0D3633071A1A1F052D033602091C5C0D21265B35243132_ = $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_["data"][2];
        } catch (Exception $_obfuscated_0D22060E2108273730182D2E1202232603100B38063801_) {
            $this->log($_obfuscated_0D22060E2108273730182D2E1202232603100B38063801_->getMessage(), "input", true);
            return [];
        }
        return ["node" => $node, "ip" => $ip, "status" => $status, "os" => $_obfuscated_0D3633071A1A1F052D033602091C5C0D21265B35243132_];
    }
    public function getClientTabs($package)
    {
        return ["cplogin" => ["name" => "Control Panel Login", "icon" => "glyphicon glyphicon-cog"], "start" => ["name" => "Start Server", "icon" => "glyphicon glyphicon-play"], "stop" => ["name" => "Stop Server", "icon" => "glyphicon glyphicon-stop"]];
    }
    public function cplogin($package, $service, $get = NULL, $post = NULL, $files = NULL)
    {
        $row = $this->getModuleRow();
        Loader::loadModels($this, ["Clients"]);
        $_obfuscated_0D1B3B3E081F062F3501373E3825352317300A0D103C32_ = $this->Clients->get($service->client_id);
        $this->view = new View("cplogin", "default");
        Loader::loadHelpers($this, ["Form", "Html"]);
        $this->view->setDefaultView("components" . DS . "modules" . DS . "proxcp" . DS);
        $this->view->set("hostname", $row->meta->hostname);
        $this->view->set("email", urlencode(base64_encode($_obfuscated_0D1B3B3E081F062F3501373E3825352317300A0D103C32_->email)));
        return $this->view->fetch();
    }
    public function start($package, $service, $get = NULL, $post = NULL, $files = NULL)
    {
        try {
            $row = $this->getModuleRow();
            $type = $package->meta->servicetype;
            $_obfuscated_0D0E18333B10142F353E1714251E1B3D2F3F28265C3D11_ = $service->id;
            $_obfuscated_0D083B141D2F16332E0D0D08373C2D1915371B1F1C3601_ = $service->client_id;
            $_obfuscated_0D103D3105063F2802305B291F2F0C145B301A0B3B2301_ = "https://" . $row->meta->hostname . "/api.php";
            $_obfuscated_0D24092434293814362F2A333D0F0B1E013B221D385B11_ = "api_id=" . $row->meta->user . "&api_key=" . $row->meta->password . "&action=startserver&type=" . $type . "&hbid=" . $_obfuscated_0D0E18333B10142F353E1714251E1B3D2F3F28265C3D11_ . "&userid=" . $_obfuscated_0D083B141D2F16332E0D0D08373C2D1915371B1F1C3601_;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $_obfuscated_0D103D3105063F2802305B291F2F0C145B301A0B3B2301_);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $_obfuscated_0D24092434293814362F2A333D0F0B1E013B221D385B11_);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, "ProxCP Blesta Module");
            $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_ = curl_exec($ch);
            if (curl_error($ch)) {
                $this->log("Unable to connect: " . curl_errno($ch) . " - " . curl_error($ch), "input", true);
                return NULL;
            }
            if (empty($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_)) {
                $this->log("Empty response", "input", true);
                return NULL;
            }
            curl_close($ch);
            $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_ = json_decode($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_, true);
            if (is_null($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_)) {
                $this->log("Invalid response format", "input", true);
                return NULL;
            }
            if ($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_["success"] == 0) {
                $this->log($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_["message"], "input", true);
                return NULL;
            }
        } catch (Exception $_obfuscated_0D22060E2108273730182D2E1202232603100B38063801_) {
            $this->log($_obfuscated_0D22060E2108273730182D2E1202232603100B38063801_->getMessage(), "input", true);
            return NULL;
        }
        return "<div class=\"alert alert-success\">Success! Your server has been started.</div>";
    }
    public function stop($package, $service, $get = NULL, $post = NULL, $files = NULL)
    {
        try {
            $row = $this->getModuleRow();
            $type = $package->meta->servicetype;
            $_obfuscated_0D0E18333B10142F353E1714251E1B3D2F3F28265C3D11_ = $service->id;
            $_obfuscated_0D083B141D2F16332E0D0D08373C2D1915371B1F1C3601_ = $service->client_id;
            $_obfuscated_0D103D3105063F2802305B291F2F0C145B301A0B3B2301_ = "https://" . $row->meta->hostname . "/api.php";
            $_obfuscated_0D24092434293814362F2A333D0F0B1E013B221D385B11_ = "api_id=" . $row->meta->user . "&api_key=" . $row->meta->password . "&action=stopserver&type=" . $type . "&hbid=" . $_obfuscated_0D0E18333B10142F353E1714251E1B3D2F3F28265C3D11_ . "&userid=" . $_obfuscated_0D083B141D2F16332E0D0D08373C2D1915371B1F1C3601_;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $_obfuscated_0D103D3105063F2802305B291F2F0C145B301A0B3B2301_);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $_obfuscated_0D24092434293814362F2A333D0F0B1E013B221D385B11_);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, "ProxCP Blesta Module");
            $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_ = curl_exec($ch);
            if (curl_error($ch)) {
                $this->log("Unable to connect: " . curl_errno($ch) . " - " . curl_error($ch), "input", true);
                return NULL;
            }
            if (empty($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_)) {
                $this->log("Empty response", "input", true);
                return NULL;
            }
            curl_close($ch);
            $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_ = json_decode($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_, true);
            if (is_null($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_)) {
                $this->log("Invalid response format", "input", true);
                return NULL;
            }
            if ($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_["success"] == 0) {
                $this->log($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_["message"], "input", true);
                return NULL;
            }
        } catch (Exception $_obfuscated_0D22060E2108273730182D2E1202232603100B38063801_) {
            $this->log($_obfuscated_0D22060E2108273730182D2E1202232603100B38063801_->getMessage(), "input", true);
            return NULL;
        }
        return "<div class=\"alert alert-success\">Success! Your server has been stopped.</div>";
    }
    private function getNodes($module_row)
    {
        $_obfuscated_0D24092434293814362F2A333D0F0B1E013B221D385B11_ = "api_id=" . $module_row->meta->user . "&api_key=" . $module_row->meta->password . "&action=getnodes";
        $_obfuscated_0D103D3105063F2802305B291F2F0C145B301A0B3B2301_ = "https://" . $module_row->meta->hostname . "/api.php";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $_obfuscated_0D103D3105063F2802305B291F2F0C145B301A0B3B2301_);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_obfuscated_0D24092434293814362F2A333D0F0B1E013B221D385B11_);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "ProxCP Blesta Module");
        $_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_ = curl_exec($ch);
        if (curl_error($ch)) {
            $this->log("Unable to connect: " . curl_errno($ch), curl_error($ch), "input", true);
        } else {
            if (empty($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_)) {
                $this->log("Empty response", "input", true);
            }
        }
        curl_close($ch);
        $_obfuscated_0D13142A303E1F181F3E1C041E233E1512180F29232A22_ = json_decode($_obfuscated_0D30251018010E0C21051931223C33330A0E1735111601_, true);
        if (is_null($_obfuscated_0D13142A303E1F181F3E1C041E233E1512180F29232A22_) || $_obfuscated_0D13142A303E1F181F3E1C041E233E1512180F29232A22_["success"] == 0) {
            $this->log("Invalid response format", "input", true);
        }
        $_obfuscated_0D34163F2203361928181B3219070935221F1A121F3E22_ = [];
        foreach ($_obfuscated_0D13142A303E1F181F3E1C041E233E1512180F29232A22_["data"] as $node) {
            $_obfuscated_0D34163F2203361928181B3219070935221F1A121F3E22_[$node] = $node;
        }
        return $_obfuscated_0D34163F2203361928181B3219070935221F1A121F3E22_;
    }
    private function getRowRules(&$vars)
    {
        $_obfuscated_0D27311B29292B1C050E3F3E120C2F3E1D2B0940212301_ = ["server_name" => ["valid" => ["rule" => "isEmpty", "negate" => true, "message" => "You must enter a Server Label."]], "hostname" => ["valid" => ["rule" => "isEmpty", "negate" => true, "message" => "You must enter a Hostname."]], "user" => ["valid" => ["rule" => "isEmpty", "negate" => true, "message" => "You must enter a Username."]], "password" => ["valid" => ["last" => true, "rule" => "isEmpty", "negate" => true, "message" => "You must enter a Password."]]];
        return $_obfuscated_0D27311B29292B1C050E3F3E120C2F3E1D2B0940212301_;
    }
    private function generatePassword($min_chars = 12, $max_chars = 12)
    {
        $password = "";
        $_obfuscated_0D1F3F073B3D3F1C1D1F023E25090E211F1D2A18323211_ = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "!", "@", "#", "\$", "%", "^", "&", "*", "(", ")"];
        $count = count($_obfuscated_0D1F3F073B3D3F1C1D1F023E25090E211F1D2A18323211_) - 1;
        $_obfuscated_0D14153B22271D351F01121F0B322C1114133F172B3401_ = (int) abs($min_chars == $max_chars ? $min_chars : mt_rand($min_chars, $max_chars));
        for ($i = 0; $i < $_obfuscated_0D14153B22271D351F01121F0B322C1114133F172B3401_; $i++) {
            $password = $_obfuscated_0D1F3F073B3D3F1C1D1F023E25090E211F1D2A18323211_[mt_rand(0, $count)] . $password;
        }
        return $password;
    }
}

?>
