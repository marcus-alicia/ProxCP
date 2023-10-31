(function ($) {
    String["prototype"]["isEmpty"] = function () {
        return this["length"] === 0 || !this["trim"]();
    };

    function d(s) {
        if (s["match"](/^[0-9a-z\-\.]+$/)) {
            return true;
        } else {
            return false;
        }
    }

    function b(s) {
        if (s["match"](/^[0-9a-z]+$/)) {
            return true;
        } else {
            return false;
        }
    }
    $("#clcreatevmsaved")["click"](function (m) {
        m["preventDefault"]();
        $("#clcreate_error")["html"]("");
        $(this)["prop"]("disabled", true);
        var j = $("#clpoolid")["val"]();
        var g = $("#clhostname")["val"]();
        var i = $("#clos")["val"]();
        var o = $("#ramslider")["val"]();
        var k = $("#cpuslider")["val"]();
        var l = $("#diskslider")["val"]();
        var f = $("#clddevice")["val"]();
        var h = $("#clnetdevice")["val"]();
        if (i == "default") {
            $("#clcreate_error")["html"]("<div class=\"alert alert-danger\" role=\"alert\"><strong>Error:</strong> no operating system was chosen.</div>");
            $(this)["prop"]("disabled", false);
        } else {
            if (j == "default") {
                $("#clcreate_error")["html"]("<div class=\"alert alert-danger\" role=\"alert\"><strong>Error:</strong> no pool was chosen.</div>");
                $(this)["prop"]("disabled", false);
            } else {
                if (f == "default") {
                    $("#clcreate_error")["html"]("<div class=\"alert alert-danger\" role=\"alert\"><strong>Error:</strong> no disk device was chosen.</div>");
                    $(this)["prop"]("disabled", false);
                } else {
                    if (h == "default") {
                        $("#clcreate_error")["html"]("<div class=\"alert alert-danger\" role=\"alert\"><strong>Error:</strong> no network device was chosen.</div>");
                        $(this)["prop"]("disabled", false);
                    } else {
                        if (!g || 0 === g["length"] || !g || /^\s*$/["test"](g) || g["isEmpty"]() || !d(g) || !b(g[g["length"] - 1])) {
                            $("#clcreate_error")["html"]("<div class=\"alert alert-danger\" role=\"alert\"><strong>Error:</strong> invalid hostname.</div>");
                            $(this)["prop"]("disabled", false);
                        } else {
                            var n = {
                                clpoolid: j,
                                clhostname: g,
                                clos: i,
                                ram: o,
                                cpu: k,
                                disk: l,
                                clddevice: f,
                                clnetdevice: h
                            };
                            $("#clhostname")["val"]("");
                            $("#clcreate_error")["html"]("");
                            socket["emit"]("PubCloudCreateReq", n);
                            $("#clcreate_error")["html"]("<div class=\"alert alert-success\" role=\"alert\"><strong>Success:</strong> VM has been scheduled for creation.</div>");
                        }
                    }
                }
            }
        }
    });
    socket["on"]("PubCloudCreateRes", function (p) {
        if (p == "ok") {
            $("#clcreatevm")["modal"]("hide");
            $("#clcreation")["html"]("<div class=\"alert alert-success\" role=\"alert\">Success! Your new VM has been created.</div>");
            setTimeout(function () {
                window["location"]["reload"]();
            }, 2500);
        } else {
            $("#clcreatevm")["modal"]("hide");
            $("#clcreation")["html"]("<div class=\"alert alert-danger\" role=\"alert\">Oops! An unexpected error has occurred. Ensure your hostname is unique.</div>");
        }
    });
    $("#clpoolid")["change"](function () {
        if ($(this)["val"]() != "default") {
            socket["emit"]("PubCloudQueryPoolReq", {
                clpoolid: $(this)["val"]()
            });
        } else {
            $("#clcreatevmsaved")["prop"]("disabled", true);
            $("#ramslider, #cpuslider, #diskslider")["slider"]("disable");
        }
    });
    socket["on"]("PubCloudQueryPoolRes", function (p) {
        if (p["status"] == "ok") {
            $("#ramslider")["slider"]("setAttribute", "max", p["retdata"]["avail_memory"]);
            $("#cpuslider")["slider"]("setAttribute", "max", p["retdata"]["avail_cpu_cores"]);
            $("#diskslider")["slider"]("setAttribute", "max", p["retdata"]["avail_disk_size"]);
            if (p["retdata"]["suspended"] == 1) {
                $("#clcreatevmsaved")["prop"]("disabled", true);
                return;
            };
            if (p["retdata"]["avail_ip_limit"] > 0 && p["retdata"]["avail_memory"] >= 32 && p["retdata"]["avail_cpu_cores"] >= 1 && p["retdata"]["avail_disk_size"] >= 1) {
                $("#clcreatevmsaved")["prop"]("disabled", false);
                $("#ramslider, #cpuslider, #diskslider")["slider"]("enable");
            };
            if (p["retdata"]["templates"]["length"] > 0) {
                for (var q = 0; q < p["retdata"]["templates"]["length"]; q++) {
                    $("#clos")["append"]($("<option></option>")["attr"]("value", p["retdata"]["templates"][q]["vmid"])["text"](p["retdata"]["templates"][q]["friendly_name"] + " (automatic template)"));
                }
            }
        } else {
            $("#clcreatevmsaved")["val"]("Error retrieving pool details");
        }
    });
})(jQuery);
