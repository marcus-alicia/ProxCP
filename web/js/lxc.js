var pendingbackups = [];
(function ($) {
    function h(bc) {
        var Z = Math.floor(bc / 86400);
        var ba = Math.floor(bc % 86400 / 3600);
        var bb = Math.floor(bc % 86400 % 3600 / 60);
        var bd = bc % 86400 % 3600 % 60;
        return Z + " days " + ba + " hours " + bb + " minutes " + bd + " seconds";
    }
    String.prototype.isEmpty = function () {
        return this.length === 0 || !this.trim();
    };

    function f(Y) {
        if (Y.match(/^[0-9a-z\-\.]+$/)) {
            return true;
        } else {
            return false;
        }
    }

    function d(Y) {
        if (Y.match(/^[0-9a-z]+$/)) {
            return true;
        } else {
            return false;
        }
    }
    var c = {
        hb_account_id: $("#lxcinfo").val(),
        respond: true
    };
    setInterval(function () {
        if (pendingbackups.length > 0) {
            socket.emit("LXCBackupStatusReq", pendingbackups);
        }
    }, 11e3);
    socket.on("LXCBackupStatusRes", function (k) {
        if (k.status == "ok") {
            if (k.tasks.length > 0) {
                $("#backup_info").html("");
                for (var i = 0; i < k.tasks.length; i++) {
                    var o = k.tasks[i].status;
                    var p = k.tasks[i].upid;
                    var l = p.split(":");
                    l = l[2] + ":" + l[3] + ":" + l[4];
                    var n = k.tasks[i].log;
                    if (o == "stopped") {
                        var m = pendingbackups.indexOf(p);
                        if (m !== -1) {
                            pendingbackups.splice(m, 1);
                        };
                        if (typeof Storage !== "undefined") {
                            localStorage.setItem("proxcp_user_backup_progress2", btoa(JSON.stringify(pendingbackups)));
                        };
                        $("#backup_info").append('<div class="panel panel-success"><div class="panel-heading"><div class="panel-title">Backup Job Status</div></div><div class="panel-body">Backup job ' + l + " completed!</div></div><br />");
                        if (i == k.tasks.length - 1) {
                            setTimeout(function () {
                                window.location.reload();
                            }, 2500);
                        }
                    } else {
                        n.reverse();
                        var m = n.findIndex(q => {
                            return q.includes("%");
                        });
                        if (m !== null && m >= 0) {
                            var j = n[m].split("%")[0].split(":")[1];
                            $("#backup_info").append('<div class="panel panel-info"><div class="panel-heading"><div class="panel-title">Backup Job Status</div></div><div class="panel-body">Backup job ' + l + " still running. Last percentage: " + j + "%</div></div><br />");
                        } else {
                            $("#backup_info").append('<div class="panel panel-info"><div class="panel-heading"><div class="panel-title">Backup Job Status</div></div><div class="panel-body">Backup job ' + l + " still running. Last percentage: unknown</div></div><br />");
                        }
                    }
                }
            } else {
                pendingbackups = [];
            }
        } else {
            pendingbackups = [];
        }
    });
    socket.emit("LXCStatusCheckReq", c);
    setInterval(function () {
        socket.emit("LXCStatusCheckReq", c);
    }, 1e4);
    socket.on("LXCStatusCheckRes", function (k) {
        if (k.status == "running") {
            $("#status_2").removeClass("label-danger");
            $("#status_2").addClass("label-success");
            $("#status_2").html('<i class="fa fa-check"></i> Online');
            $("#start_server").prop("disabled", true);
            $("#shutdown_server").prop("disabled", false);
            $("#restart_server").prop("disabled", false);
            $("#kill_server").prop("disabled", false);
        } else {
            if (k.status == "stopped") {
                $("#status_2").removeClass("label-success");
                $("#status_2").addClass("label-danger");
                $("#status_2").html('<i class="fa fa-times"></i> Offline');
                $("#start_server").prop("disabled", false);
                $("#shutdown_server").prop("disabled", true);
                $("#restart_server").prop("disabled", true);
                $("#kill_server").prop("disabled", true);
            } else {
                $("#status_2").removeClass("label-success");
                $("#status_2").addClass("label-danger");
                $("#status_2").html('<i class="fa fa-times"></i> Offline');
                $("#func_error").html("Error: could not get server status.");
                $("#start_server").prop("disabled", false);
                $("#shutdown_server").prop("disabled", false);
                $("#restart_server").prop("disabled", false);
                $("#kill_server").prop("disabled", false);
            }
        };
        var r = Math.round(k.cpu * 100);
        var t = Math.round(k.mem / k.maxmem * 100);
        var s = Math.round(k.disk / k.maxdisk * 100);
        var u = Math.round(k.swap / k.maxswap * 100);
        $("#cpu_usage_1").attr("aria-valuenow", r);
        $("#cpu_usage_1").css("width", r + "%");
        $("#cpu_usage_2").html(r + "%");
        if (r <= 33) {
            $("#cpu_usage_1").removeClass("progress-bar-info");
            $("#cpu_usage_1").removeClass("progress-bar-warning");
            $("#cpu_usage_1").removeClass("progress-bar-danger");
            $("#cpu_usage_1").addClass("progress-bar-success");
        } else {
            if (r >= 34 && r <= 66) {
                $("#cpu_usage_1").removeClass("progress-bar-info");
                $("#cpu_usage_1").removeClass("progress-bar-success");
                $("#cpu_usage_1").removeClass("progress-bar-danger");
                $("#cpu_usage_1").addClass("progress-bar-warning");
            } else {
                $("#cpu_usage_1").removeClass("progress-bar-info");
                $("#cpu_usage_1").removeClass("progress-bar-warning");
                $("#cpu_usage_1").removeClass("progress-bar-success");
                $("#cpu_usage_1").addClass("progress-bar-danger");
            }
        };
        $("#ram_usage_1").attr("aria-valuenow", t);
        $("#ram_usage_1").css("width", t + "%");
        $("#ram_usage_2").html(t + "%");
        if (t <= 33) {
            $("#ram_usage_1").removeClass("progress-bar-info");
            $("#ram_usage_1").removeClass("progress-bar-warning");
            $("#ram_usage_1").removeClass("progress-bar-danger");
            $("#ram_usage_1").addClass("progress-bar-success");
        } else {
            if (t >= 34 && t <= 66) {
                $("#ram_usage_1").removeClass("progress-bar-info");
                $("#ram_usage_1").removeClass("progress-bar-success");
                $("#ram_usage_1").removeClass("progress-bar-danger");
                $("#ram_usage_1").addClass("progress-bar-warning");
            } else {
                $("#ram_usage_1").removeClass("progress-bar-info");
                $("#ram_usage_1").removeClass("progress-bar-warning");
                $("#ram_usage_1").removeClass("progress-bar-success");
                $("#ram_usage_1").addClass("progress-bar-danger");
            }
        };
        $("#disk_usage_1").attr("aria-valuenow", s);
        $("#disk_usage_1").css("width", s + "%");
        $("#disk_usage_2").html(s + "%");
        if (s <= 33) {
            $("#disk_usage_1").removeClass("progress-bar-info");
            $("#disk_usage_1").removeClass("progress-bar-warning");
            $("#disk_usage_1").removeClass("progress-bar-danger");
            $("#disk_usage_1").addClass("progress-bar-success");
        } else {
            if (s >= 34 && s <= 66) {
                $("#disk_usage_1").removeClass("progress-bar-info");
                $("#disk_usage_1").removeClass("progress-bar-success");
                $("#disk_usage_1").removeClass("progress-bar-danger");
                $("#disk_usage_1").addClass("progress-bar-warning");
            } else {
                $("#disk_usage_1").removeClass("progress-bar-info");
                $("#disk_usage_1").removeClass("progress-bar-warning");
                $("#disk_usage_1").removeClass("progress-bar-success");
                $("#disk_usage_1").addClass("progress-bar-danger");
            }
        };
        $("#swap_usage_1").attr("aria-valuenow", u);
        $("#swap_usage_1").css("width", u + "%");
        $("#swap_usage_2").html(u + "%");
        if (u <= 33) {
            $("#swap_usage_1").removeClass("progress-bar-info");
            $("#swap_usage_1").removeClass("progress-bar-warning");
            $("#swap_usage_1").removeClass("progress-bar-danger");
            $("#swap_usage_1").addClass("progress-bar-success");
        } else {
            if (u >= 34 && u <= 66) {
                $("#swap_usage_1").removeClass("progress-bar-info");
                $("#swap_usage_1").removeClass("progress-bar-success");
                $("#swap_usage_1").removeClass("progress-bar-danger");
                $("#swap_usage_1").addClass("progress-bar-warning");
            } else {
                $("#swap_usage_1").removeClass("progress-bar-info");
                $("#swap_usage_1").removeClass("progress-bar-warning");
                $("#swap_usage_1").removeClass("progress-bar-success");
                $("#swap_usage_1").addClass("progress-bar-danger");
            }
        };
        $("#uptime").html(h(k.uptime));
    });
    $("#start_server").click(function () {
        $("#start_server, #shutdown_server, #restart_server, #kill_server").prop("disabled", true);
        c.respond = false;
        socket.emit("LXCStartReq", c);
    });
    socket.on("LXCStartRes", function (k) {
        if (k == "ok") {
            c.respond = true;
        } else {
            c.respond = false;
            $("#func_error").html("Error: could not start VM.");
        }
    });
    $("#shutdown_server").click(function () {
        $("#start_server, #shutdown_server, #restart_server, #kill_server").prop("disabled", true);
        c.respond = false;
        socket.emit("LXCShutdownReq", c);
    });
    socket.on("LXCShutdownRes", function (k) {
        if (k == "ok") {
            c.respond = true;
        } else {
            c.respond = false;
            $("#func_error").html("Error: could not shutdown VM.");
        }
    });
    $("#restart_server").click(function () {
        $("#start_server, #shutdown_server, #restart_server, #kill_server").prop("disabled", true);
        c.respond = false;
        socket.emit("LXCRestartReq", c);
    });
    socket.on("LXCRestartRes", function (k) {
        if (k == "ok") {
            c.respond = true;
        } else {
            c.respond = false;
            $("#func_error").html("Error: could not restart VM.");
        }
    });
    $("#kill_server").click(function () {
        $("#start_server, #shutdown_server, #restart_server, #kill_server").prop("disabled", true);
        c.respond = false;
        socket.emit("LXCKillReq", c);
    });
    socket.on("LXCKillRes", function (k) {
        if (k == "ok") {
            c.respond = true;
        } else {
            c.respond = false;
            $("#func_error").html("Error: could not kill VM.");
        }
    });
    $("form#scheduled_form").submit(function (w) {
        w.preventDefault();
        var v = $("#scheduled_dow").val();
        var y = $("#scheduled_time").val();
        if (!(!v || 0 === v.length) && !(!y || 0 === y.length)) {
            var x = {
                aid: $("#lxcinfo").val(),
                dow: v,
                time: y
            };
            $(this).find(":input[type=submit]").prop("disabled", true).html('<i class="fa fa-cog fa-spin"></i> Please wait...scheduling');
            socket.emit("LXCScheduleBackupReq", x);
            $("#scheduled_dow").val("");
            $("#scheduled_time").val("");
        } else {
            alert("Invalid schedule selections. Please select a valid day of week and time.");
        }
    });
    socket.on("LXCScheduleBackupRes", function (k) {
        if (k.status == "ok") {
            window.location.reload();
        } else {
            $("#scheduled_submit").prop("disabled", true).html("Error scheduling backup. Contact your vendor for assistance.");
        }
    });
    $("form#schdelete_form").submit(function (w) {
        w.preventDefault();
        var x = {
            aid: $("#lxcinfo").val(),
            schid: $("#schid").val()
        };
        $(this).find(":input[type=submit]").prop("disabled", true).html('<i class="fa fa-cog fa-spin"></i> Please wait...deleting');
        socket.emit("LXCScheduledBackupDelReq", x);
    });
    socket.on("LXCScheduleBackupDelRes", function (k) {
        if (k.status == "ok") {
            window.location.reload();
        } else {
            $("#schdelete_submit").prop("disabled", true).html("Error deleting scheduled backup. Contact your vendor for assistance.");
        }
    });
    $("#create_backup").click(function () {
        $(this).prop("disabled", true);
        var z = {
            aid: $("#backup_aid").val(),
            notification: $("#notification").val()
        };
        c.respond = false;
        socket.emit("LXCCreateBackupReq", z);
        $("#backup_message").html("Backup job tasked successfully!");
        $("#cancel_backup").html("Close");
    });
    socket.on("LXCCreateBackupRes", function (k) {
        if (k.status == "ok") {
            c.respond = true;
            $("#countsection > button").prop("disabled", true);
            var C = parseInt($("#currentbackupcount").html());
            var B = C + 1;
            $("#currentbackupcount").html("" + B);
            $("#backup_modal").modal("toggle");
            var A = parseInt($("#maxbackupcount").html());
            if (B >= A) {
                $("#countsection").html("").html('<button type="button" class="btn btn-md btn-warning btn-block" disabled="disabled" id="backup-warning">Create backup</button><span id="backup-warning-2">&nbsp;&nbsp;&nbsp;&nbsp;<small><em>Backup limit reached. Remove some old backups to create more.</em></small></span><br /><br />');
            };
            $("#backuplist > tbody:last-child").append('<tr><td><i class="fa fa-cog fa-spin"></i> Pending...</td><td>Unknown</td><td>N/A</td><td>N/A</td><td>N/A</td></tr>');
            pendingbackups.push(k.upid);
            if (typeof Storage !== "undefined") {
                localStorage.setItem("proxcp_user_backup_progress2", btoa(JSON.stringify(pendingbackups)));
            }
        } else {
            window.location.reload();
        }
    });
    $("[id^=remove_backup_]").click(function () {
        var D = confirm("Are you sure you want to delete this backup?");
        if (D === true) {
            $(this).prop("disabled", true);
            var F = $(this).attr("id").split("_");
            F = F[F.length - 1];
            $("#restore_backup_" + F).prop("disabled", true);
            var E = {
                aid: $("#lxcinfo").val(),
                volid: $(this).attr("content")
            };
            socket.emit("LXCRemoveBackupReq", E);
            $(this).closest("tr").remove();
        }
    });
    socket.on("LXCRemoveBackupRes", function (k) {
        if (k == "ok") {
            var B = parseInt($("#currentbackupcount").html()) - 1;
            $("#currentbackupcount").html("" + B);
            $("#backup-warning-2").remove();
            $("#backup-warning").removeClass("btn-warning").addClass("btn-success").prop("disabled", false).attr("data-toggle", "modal").attr("data-target", "#backup_modal");
        } else {
            window.location.reload();
        }
    });
    $("[id^=get_backup_config_]").click(function () {
        $(this).prop("disabled", true);
        $("#config_modal").modal("toggle");
        var G = {
            aid: $("#lxcinfo").val(),
            volid: $(this).attr("content")
        };
        $("#confheader").html("");
        $("#confheader").html($(this).attr("content").split("/")[1]);
        socket.emit("LXCGetBackupConfReq", G);
    });
    socket.on("LXCGetBackupConfRes", function (k) {
        if (k.status == "ok") {
            $("#confwell").html("");
            $("#confwell").html(k.conf);
        } else {
            $("#confwell").html("Error: could not get backup configuration.");
        }
    });
    $("#config_modal").on("hidden.bs.modal", function (w) {
        $("[id^=get_backup_config_]").prop("disabled", false);
    });
    $("#restore_modal").modal({
        backdrop: "static",
        keyboard: false,
        show: false
    });
    $("[id^=restore_backup_]").click(function () {
        var D = confirm("Are you sure you want to restore this backup? If yes, all current data will be deleted and your VPS will be restored to a previous state.");
        if (D === true) {
            c.respond = false;
            $(this).prop("disabled", true);
            var I = $(this).attr("id").split("_");
            I = I[I.length - 1];
            $("#remove_backup_" + I).prop("disabled", true);
            $("#get_backup_config_" + I).prop("disabled", true);
            $("#countsection > button").prop("disabled", true);
            var H = {
                aid: $("#lxcinfo").val(),
                volid: $(this).attr("content")
            };
            socket.emit("LXCRestoreBackupReq", H);
            $("#restore_modal").modal("show");
            $(".restore_progress").animate({
                width: "100%"
            }, 6e4, "swing", function () {
                $(".restore_progress").removeClass("progress-bar-info active");
                $(".restore_progress").addClass("progress-bar-success");
                $(".restore_progress").html("Complete!");
                setTimeout(function () {
                    $("#restore_modal").modal("hide");
                    window.location.href = "/index";
                }, 2500);
            });
        }
    });
    socket.on("LXCRestoreBackupRes", function (k) {
        if (k == "ok") {
            c.respond = true;
            $("#restore_output").html("Almost done. Saving configuration and cleaning up...");
        } else {
            c.respond = false;
            $("#restore_output").html("An unexpected error occurred.");
        }
    });
    $("#rebuild_modal").modal({
        backdrop: "static",
        keyboard: false,
        show: false
    });
    $("#rebuild_btn").click(function (w) {
        c.respond = false;
        w.preventDefault();
        $("#os_error, #hostname_error, #password_error").html("");
        $(this).prop("disabled", true);
        var M = $("#os").val();
        var K = $("#hostname").val();
        var N = $("#password").val();
        var J = $("#aid").val();
        if (M == "default") {
            $("#os_error").html("Error: no operating system was chosen.");
            $(this).prop("disabled", false);
        } else {
            if (!K || 0 === K.length || !K || /^\s*$/.test(K) || K.isEmpty() || !f(K) || !d(K[K.length - 1])) {
                $("#hostname_error").html("Error: invalid hostname.");
                $(this).prop("disabled", false);
            } else {
                if (!N || 0 === N.length || !N || /^\s*$/.test(N) || N.isEmpty()) {
                    $("#password_error").html("Error: invalid password.");
                    $(this).prop("disabled", false);
                } else {
                    var L = {
                        os: M,
                        hostname: K,
                        password: N,
                        aid: J
                    };
                    $("#hostname, #password").val("");
                    $("#os_error, #hostname_error, #password_error").html("");
                    var D = confirm("WARNING: Rebuilding your VPS will delete ALL data it currently stores. Do you want to proceed?");
                    if (D === true) {
                        socket.emit("LXCRebuildReq", L);
                        $("#rebuild_modal").modal("show");
                        $(".rebuild_progress").animate({
                            width: "100%"
                        }, 6e4, "swing", function () {
                            $(".rebuild_progress").removeClass("progress-bar-info active");
                            $(".rebuild_progress").addClass("progress-bar-success");
                            $(".rebuild_progress").html("Complete!");
                            setTimeout(function () {
                                $("#rebuild_modal").modal("hide");
                                window.location.href = "/index";
                            }, 2500);
                        });
                    } else {
                        $("#rebuild_btn").prop("disabled", false);
                    }
                }
            }
        }
    });
    socket.on("LXCRebuildRes", function (k) {
        if (k == "ok") {
            c.respond = true;
            $("#rebuild_output").html("Almost done. Saving configuration and cleaning up...");
        } else {
            c.respond = false;
            $("#rebuild_output").html("An unexpected error occurred.");
        }
    });
    $("#lxcconsole").click(function (w) {
        w.preventDefault();
        var O = $(this).attr("role");
        window.open("/console?id=" + O + "&virt=lxc", "_blank", "height=580,width=820,status=yes,toolbar=no,menubar=no,location=no,addressbar=no");
    });
    $("#day, #week, #month, #year").hide();
    var b = "#hour";
    $("#graphtime").change(function () {
        var P = $(this).val();
        $("" + b).hide();
        $("#" + P).show();
        b = "#" + P;
    });
    $("#enabletap").click(function (w) {
        w.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            aid: $("#lxcinfo").val()
        };
        socket.emit("LXCEnableTAPReq", c);
    });
    socket.on("LXCEnableTAPRes", function (k) {
        if (k == "ok") {
            window.location.reload();
        } else {
            $("#enabletap").html("Error");
        }
    });
    $("#disabletap").click(function (w) {
        w.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            aid: $("#lxcinfo").val()
        };
        socket.emit("LXCDisableTAPReq", c);
    });
    socket.on("LXCDisableTAPRes", function (k) {
        if (k == "ok") {
            window.location.reload();
        } else {
            $("#disabletap").html("Error");
        }
    });
    $("#chgrootpw_lxc").click(function (w) {
        w.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            aid: $("#lxcinfo").val()
        };
        socket.emit("LXCChangePWReq", c);
    });
    socket.on("LXCChangePWRes", function (k) {
        if (k.status == "ok") {
            $("#lxc_pwsuccess").html('<div class="alert alert-success" role="alert"><strong>New Root Password:</strong> ' + k.password + "</div>");
        } else {
            $("#chgrootpw_lxc").html("Error");
            $("#lxc_pwsuccess").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> reset root password failed.</div>');
        }
    });
    $("#enableonboot").click(function (w) {
        w.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            aid: $("#lxcinfo").val()
        };
        socket.emit("LXCEnableOnbootReq", c);
    });
    socket.on("LXCEnableOnbootRes", function (k) {
        if (k == "ok") {
            window.location.reload();
        } else {
            $("#enableonboot").html("Error");
        }
    });
    $("#disableonboot").click(function (w) {
        w.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            aid: $("#lxcinfo").val()
        };
        socket.emit("LXCDisableOnbootReq", c);
    });
    socket.on("LXCDisableOnbootRes", function (k) {
        if (k == "ok") {
            window.location.reload();
        } else {
            $("#disableonboot").html("Error");
        }
    });
    $("#enablequotas").click(function (w) {
        var D = confirm("Enabling quotas requires your VPS to be shutdown. Do you want to proceed now?");
        if (D === true) {
            w.preventDefault();
            $(this).prop("disabled", true);
            var c = {
                aid: $("#lxcinfo").val()
            };
            socket.emit("LXCEnableQuotasReq", c);
        }
    });
    socket.on("LXCEnableQuotasRes", function (k) {
        if (k == "ok") {
            window.location.reload();
        } else {
            $("#enablequotas").html("Error");
        }
    });
    $("#disablequotas").click(function (w) {
        var D = confirm("Disabling quotas requires your VPS to be shutdown. Do you want to proceed now?");
        if (D === true) {
            w.preventDefault();
            $(this).prop("disabled", true);
            var c = {
                aid: $("#lxcinfo").val()
            };
            socket.emit("LXCDisableQuotasReq", c);
        }
    });
    socket.on("LXCDisableQuotasRes", function (k) {
        if (k == "ok") {
            window.location.reload();
        } else {
            $("#disablequotas").html("Error");
        }
    });
    $("#enableprivatenet").click(function (w) {
        w.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            aid: $("#lxcinfo").val()
        };
        socket.emit("LXCEnablePrivateNetworkReq", c);
    });
    socket.on("LXCEnablePrivateNetworkRes", function (k) {
        window.location.reload();
    });
    $("#disableprivatenet").click(function (w) {
        w.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            aid: $("#lxcinfo").val()
        };
        socket.emit("LXCDisablePrivateNetworkReq", c);
    });
    socket.on("LXCDisablePrivateNetworkRes", function (k) {
        if (k == "ok") {
            window.location.reload();
        }
    });
    $("#assignipv6").click(function (w) {
        w.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            aid: $("#lxcinfo").val()
        };
        socket.emit("LXCAssignIPv6Req", c);
    });
    socket.on("LXCAssignIPv6Res", function (k) {
        window.location.reload();
    });
    $("#getvmlog").click(function (w) {
        w.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            aid: $("#lxcinfo").val()
        };
        socket.emit("LXCGetLogReq", c);
    });
    $("#clearvmlog").click(function (w) {
        w.preventDefault();
        $("#vmlog").html("null");
    });
    socket.on("LXCGetLogRes", function (k) {
        if (k.status == "ok") {
            $("#vmlog").html(k.log);
            $("#getvmlog").prop("disabled", false);
        } else {
            $("#vmlog").html("Error fetching log");
        }
    });
    $("#natport_btn").click(function (w) {
        w.preventDefault();
        $("#natport_error, #natdesc_error").html("");
        $(this).prop("disabled", true);
        var R = $("#chosennatport").val();
        var Q = $("#natportdesc").val();
        var J = $("#aid").val();
        if (R < 1 || R > 65535) {
            $("#natport_error").html("Error: invalid NAT port.");
            $(this).prop("disabled", false);
        } else {
            if (!Q || 0 === Q.length || !Q || /^\s*$/.test(Q) || Q.isEmpty()) {
                $("#natdesc_error").html("Error: invalid description.");
                $(this).prop("disabled", false);
            } else {
                var S = {
                    natport: R,
                    natdesc: Q,
                    aid: J
                };
                $("#chosennatport, #natportdesc").val("");
                $("#natport_error, #natdesc_error").html("");
                socket.emit("LXCAddNATPortReq", S);
            }
        }
    });
    socket.on("LXCAddNATPortRes", function (k) {
        if (k == "ok") {
            window.location.reload();
        } else {
            $("#natport_error").html("Unable to add new NAT port forward.");
        }
    });
    $("#user_porttable").on("click", "[id^=user_natportdelete]", function (w) {
        w.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            id: $(this).attr("role"),
            aid: $("#lxcinfo").val()
        };
        socket.emit("LXCDelNATPortReq", c);
    });
    socket.on("LXCDelNATPortRes", function (k) {
        window.location.reload();
    });
    $("#natdomain_btn").click(function (w) {
        w.preventDefault();
        $("#natdomain_error").html("");
        $(this).prop("disabled", true);
        var T = $("#chosendomain").val().trim();
        var U = $("#nat_sslcert").val().trim();
        var V = $("#nat_sslkey").val().trim();
        var J = $("#aid").val();
        if (!T || 0 === T.length || !T || /^\s*$/.test(T) || T.isEmpty() || !f(T) || !d(T[T.length - 1])) {
            $("#natdomain_error").html("Error: invalid NAT domain.");
            $(this).prop("disabled", false);
        } else {
            var W = {
                natdomain: T,
                natsslcert: U,
                natsslkey: V,
                aid: J
            };
            $("#chosendomain").val("");
            $("#nat_sslcert").val("");
            $("#nat_sslkey").val("");
            $("#natdomain_error").html("");
            socket.emit("LXCAddNATDomainReq", W);
        }
    });
    socket.on("LXCAddNATDomainRes", function (k) {
        if (k == "ok") {
            window.location.reload();
        } else {
            $("#natdomain_error").html("Unable to add new NAT domain forward.");
            $("#natdomain_btn").prop("disabled", false);
        }
    });
    $("#user_domaintable").on("click", "[id^=user_natdomaindelete]", function (w) {
        w.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            id: $(this).attr("role"),
            aid: $("#lxcinfo").val()
        };
        socket.emit("LXCDelNATDomainReq", c);
    });
    socket.on("LXCDelNATDomainRes", function (k) {
        window.location.reload();
    });
}(jQuery));
$(document).ready(function () {
    if (typeof Storage !== "undefined" && typeof localStorage.getItem("proxcp_user_backup_progress2") == "string") {
        pendingbackups = JSON.parse(atob(localStorage.getItem("proxcp_user_backup_progress2")));
    };
    if (pendingbackups.length > 0) {
        $("#countsection").html('<button type="button" class="btn btn-md btn-warning btn-block" disabled="disabled" id="backup-warning">Create backup</button><span id="backup-warning-2"><p><center><em>Please wait...checking status of last backup.</em></center></p></span><br /><br />');
    }
});
