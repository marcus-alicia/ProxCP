(function ($) {
    $("#fwoptionssave")["click"](function (c) {
        c["preventDefault"]();
        $(this)["prop"]("disabled", true);
        var b = {
            aid: $("#kvminfo")["val"](),
            enable: $("#enableopts")["val"](),
            policy_in: $("#policyinopts")["val"](),
            policy_out: $("#policyoutopts")["val"](),
            log_level_in: $("#levelinopts")["val"](),
            log_level_out: $("#leveloutopts")["val"]()
        };
        socket["emit"]("KVMFirewallOptionsReq", b);
    });
    socket["on"]("KVMFirewallOptionsRes", function (d) {
        if (d == "ok") {
            $("#fwoptionssave")["prop"]("disabled", false);
            $("#fwoptions")["modal"]("hide");
            window["location"]["reload"]();
        } else {
            $("#fwoptionssave")["html"]("An unexpected error has occurred.");
        }
    });
    $("#fwrulessave")["click"](function (c) {
        c["preventDefault"]();
        $(this)["prop"]("disabled", true);
        var b = {
            aid: $("#kvminfo")["val"](),
            enable: $("#a")["val"](),
            iface: $("#iface")["val"](),
            type: $("#b")["val"](),
            action: $("#c")["val"](),
            source: $("#d")["val"](),
            sport: $("#e")["val"](),
            dest: $("#f")["val"](),
            dport: $("#g")["val"](),
            proto: $("#h")["val"](),
            comment: $("#i")["val"]()
        };
        socket["emit"]("KVMFirewallRuleReq", b);
    });
    socket["on"]("KVMFirewallRuleRes", function (d) {
        if (d == "ok") {
            $("#fwrulessave")["prop"]("disabled", true);
            $("#addfwrule")["modal"]("hide");
            window["location"]["reload"]();
        } else {
            $("#fwrulessave")["html"]("An unexpected error has occurred.");
        }
    });
    $("[id^=fwredit]")["click"](function (c) {
        c["preventDefault"]();
        $(this)["prop"]("disabled", true);
        var f = $(this)["attr"]("role");
        var b = {
            aid: $("#kvminfo")["val"](),
            pos: f,
            enable: $("#a" + f)["val"](),
            iface: $("#iface" + f)["val"](),
            type: $("#b" + f)["val"](),
            action: $("#c" + f)["val"](),
            source: $("#d" + f)["val"](),
            sport: $("#e" + f)["val"](),
            dest: $("#f" + f)["val"](),
            dport: $("#g" + f)["val"](),
            proto: $("#h" + f)["val"](),
            comment: $("#i" + f)["val"]()
        };
        socket["emit"]("KVMFirewallEditReq", b);
    });
    socket["on"]("KVMFirewallEditRes", function (d) {
        if (d == "ok") {
            window["location"]["reload"]();
        }
    });
    $("[id^=fwremove]")["click"](function (c) {
        c["preventDefault"]();
        $(this)["prop"]("disabled", true);
        var b = {
            aid: $("#kvminfo")["val"](),
            pos: $(this)["attr"]("role")
        };
        socket["emit"]("KVMFirewallRemoveReq", b);
    });
    socket["on"]("KVMFirewallRemoveRes", function (d) {
        if (d == "ok") {
            window["location"]["reload"]();
        }
    });
    $("#fwifacepub")["click"](function (c) {
        c["preventDefault"]();
        $(this)["prop"]("disabled", true);
        var b = {
            aid: $("#kvminfo")["val"](),
            action: $(this)["attr"]("role")
        };
        socket["emit"]("KVMIfaceNet0Req", b);
    });
    socket["on"]("KVMIfaceNet0Res", function (d) {
        if (d == "ok") {
            window["location"]["reload"]();
        }
    });
    $("#fwifacepriv")["click"](function (c) {
        c["preventDefault"]();
        $(this)["prop"]("disabled", true);
        var b = {
            aid: $("#kvminfo")["val"](),
            action: $(this)["attr"]("role")
        };
        socket["emit"]("KVMIfaceNet1Req", b);
    });
    socket["on"]("KVMIfaceNet1Res", function (d) {
        if (d == "ok") {
            window["location"]["reload"]();
        }
    });
})(jQuery);
