var pendingbackups = [];
(function ($) {
    function i(bc) {
        var Z = Math.floor(bc / 86400);
        var ba = Math.floor(bc % 86400 / 3600);
        var bb = Math.floor(bc % 86400 % 3600 / 60);
        var bd = bc % 86400 % 3600 % 60;
        return Z + " days " + ba + " hours " + bb + " minutes " + bd + " seconds";
    }
    String.prototype.isEmpty = function () {
        return this.length === 0 || !this.trim();
    };

    function f(X) {
        if (X.match(/^[0-9a-z\-\.]+$/)) {
            return true;
        } else {
            return false;
        }
    }

    function d(X) {
        if (X.match(/^[0-9a-z]+$/)) {
            return true;
        } else {
            return false;
        }
    }
    var c = {
        hb_account_id: $("#kvminfo").val(),
        respond: true
    };
    setInterval(function () {
        if (pendingbackups.length > 0) {
            socket.emit("KVMBackupStatusReq", pendingbackups);
        }
    }, 11e3);
    socket.on("KVMBackupStatusRes", function (l) {
        if (l.status == "ok") {
            if (l.tasks.length > 0) {
                $("#backup_info").html("");
                for (var j = 0; j < l.tasks.length; j++) {
                    var p = l.tasks[j].status;
                    var q = l.tasks[j].upid;
                    var m = q.split(":");
                    m = m[2] + ":" + m[3] + ":" + m[4];
                    var o = l.tasks[j].log;
                    if (p == "stopped") {
                        var n = pendingbackups.indexOf(q);
                        if (n !== -1) {
                            pendingbackups.splice(n, 1);
                        };
                        if (typeof Storage !== "undefined") {
                            localStorage.setItem("proxcp_user_backup_progress", btoa(JSON.stringify(pendingbackups)));
                        };
                        $("#backup_info").append('<div class="panel panel-success"><div class="panel-heading"><div class="panel-title">Backup Job Status</div></div><div class="panel-body">Backup job ' + m + " completed!</div></div><br />");
                        if (j == l.tasks.length - 1) {
                            setTimeout(function () {
                                window.location.reload();
                            }, 2500);
                        }
                    } else {
                        o.reverse();
                        var n = o.findIndex(r => {
                            return r.includes("%");
                        });
                        if (n !== null && n >= 0) {
                            var k = o[n].split("%")[0].split(":")[1];
                            $("#backup_info").append('<div class="panel panel-info"><div class="panel-heading"><div class="panel-title">Backup Job Status</div></div><div class="panel-body">Backup job ' + m + " still running. Last percentage: " + k + "%</div></div><br />");
                        } else {
                            $("#backup_info").append('<div class="panel panel-info"><div class="panel-heading"><div class="panel-title">Backup Job Status</div></div><div class="panel-body">Backup job ' + m + " still running. Last percentage: unknown</div></div><br />");
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
    socket.emit("KVMStatusCheckReq", c);
    setInterval(function () {
        socket.emit("KVMStatusCheckReq", c);
    }, 1e4);
    socket.on("KVMStatusCheckRes", function (l) {
        if (l.status == "running") {
            $("#status_2").removeClass("label-danger");
            $("#status_2").addClass("label-success");
            $("#status_2").html('<i class="fa fa-check"></i> Online');
            $("#start_server").prop("disabled", true);
            $("#shutdown_server").prop("disabled", false);
            $("#restart_server").prop("disabled", false);
            $("#kill_server").prop("disabled", false);
        } else {
            if (l.status == "stopped") {
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
        var s = Math.round(l.cpu * 100);
        var t = Math.round(l.mem / l.maxmem * 100);
        $("#cpu_usage_1").attr("aria-valuenow", s);
        $("#cpu_usage_1").css("width", s + "%");
        $("#cpu_usage_2").html(s + "%");
        if (s <= 33) {
            $("#cpu_usage_1").removeClass("progress-bar-info");
            $("#cpu_usage_1").removeClass("progress-bar-warning");
            $("#cpu_usage_1").removeClass("progress-bar-danger");
            $("#cpu_usage_1").addClass("progress-bar-success");
        } else {
            if (s >= 34 && s <= 66) {
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
        $("#uptime").html(i(l.uptime));
    });
    $("#start_server").click(function () {
        $("#start_server, #shutdown_server, #restart_server, #kill_server").prop("disabled", true);
        c.respond = false;
        socket.emit("KVMStartReq", c);
    });
    socket.on("KVMStartRes", function (l) {
        if (l == "ok") {
            c.respond = true;
        } else {
            c.respond = false;
            $("#func_error").html("Error: could not start VM.");
        }
    });
    $("#shutdown_server").click(function () {
        $("#start_server, #shutdown_server, #restart_server, #kill_server").prop("disabled", true);
        c.respond = false;
        socket.emit("KVMShutdownReq", c);
    });
    socket.on("KVMShutdownRes", function (l) {
        if (l == "ok") {
            c.respond = true;
        } else {
            c.respond = false;
            $("#func_error").html("Error: could not shutdown VM.");
        }
    });
    $("#restart_server").click(function () {
        $("#start_server, #shutdown_server, #restart_server, #kill_server").prop("disabled", true);
        c.respond = false;
        socket.emit("KVMRestartReq", c);
    });
    socket.on("KVMRestartRes", function (l) {
        if (l == "ok") {
            c.respond = true;
        } else {
            c.respond = false;
            $("#func_error").html("Error: could not restart VM.");
        }
    });
    $("#kill_server").click(function () {
        $("#start_server, #shutdown_server, #restart_server, #kill_server").prop("disabled", true);
        c.respond = false;
        socket.emit("KVMKillReq", c);
    });
    socket.on("KVMKillRes", function (l) {
        if (l == "ok") {
            c.respond = true;
        } else {
            c.respond = false;
            $("#func_error").html("Error: could not kill VM.");
        }
    });
    $("form#scheduled_form").submit(function (v) {
        v.preventDefault();
        var u = $("#scheduled_dow").val();
        var x = $("#scheduled_time").val();
        if (!(!u || 0 === u.length) && !(!x || 0 === x.length)) {
            var w = {
                aid: $("#kvminfo").val(),
                dow: u,
                time: x
            };
            $(this).find(":input[type=submit]").prop("disabled", true).html('<i class="fa fa-cog fa-spin"></i> Please wait...scheduling');
            socket.emit("KVMScheduleBackupReq", w);
            $("#scheduled_dow").val("");
            $("#scheduled_time").val("");
        } else {
            alert("Invalid schedule selections. Please select a valid day of week and time.");
        }
    });
    socket.on("KVMScheduleBackupRes", function (l) {
        if (l.status == "ok") {
            window.location.reload();
        } else {
            $("#scheduled_submit").prop("disabled", true).html("Error scheduling backup. Contact your vendor for assistance.");
        }
    });
    $("form#schdelete_form").submit(function (v) {
        v.preventDefault();
        var w = {
            aid: $("#kvminfo").val(),
            schid: $("#schid").val()
        };
        $(this).find(":input[type=submit]").prop("disabled", true).html('<i class="fa fa-cog fa-spin"></i> Please wait...deleting');
        socket.emit("KVMScheduledBackupDelReq", w);
    });
    socket.on("KVMScheduleBackupDelRes", function (l) {
        if (l.status == "ok") {
            window.location.reload();
        } else {
            $("#schdelete_submit").prop("disabled", true).html("Error deleting scheduled backup. Contact your vendor for assistance.");
        }
    });
    $("#create_backup").click(function () {
        if (!$("#notification").val() || 0 === $("#notification").val().length || !$("#notification").val() || /^\s*$/.test($("#notification").val()) || $("#notification").val().isEmpty()) {
            $("#backup_message").html("Invalid notification value.");
        } else {
            $(this).prop("disabled", true);
            var y = {
                aid: $("#backup_aid").val(),
                notify: $("#notification").val()
            };
            c.respond = false;
            socket.emit("KVMCreateBackupReq", y);
            $("#backup_message").html("Backup job tasked successfully!");
            $("#cancel_backup").html("Close");
        }
    });
    socket.on("KVMCreateBackupRes", function (l) {
        if (l.status == "ok") {
            c.respond = true;
            $("#countsection > button").prop("disabled", true);
            var B = parseInt($("#currentbackupcount").html());
            var A = B + 1;
            $("#currentbackupcount").html("" + A);
            $("#backup_modal").modal("toggle");
            var z = parseInt($("#maxbackupcount").html());
            if (A >= z) {
                $("#countsection").html("").html('<button type="button" class="btn btn-md btn-warning btn-block" disabled="disabled" id="backup-warning">Create backup</button><span id="backup-warning-2">&nbsp;&nbsp;&nbsp;&nbsp;<small><em>Backup limit reached. Remove some old backups to create more.</em></small></span><br /><br />');
            };
            $("#backuplist > tbody:last-child").append('<tr><td><i class="fa fa-cog fa-spin"></i> Pending...</td><td>Unknown</td><td>N/A</td><td>N/A</td><td>N/A</td></tr>');
            pendingbackups.push(l.upid);
            if (typeof Storage !== "undefined") {
                localStorage.setItem("proxcp_user_backup_progress", btoa(JSON.stringify(pendingbackups)));
            }
        } else {
            window.location.reload();
        }
    });
    $("[id^=remove_backup_]").click(function () {
        var C = confirm("Are you sure you want to delete this backup?");
        if (C === true) {
            $(this).prop("disabled", true);
            var E = $(this).attr("id").split("_");
            E = E[E.length - 1];
            $("#restore_backup_" + E).prop("disabled", true);
            var D = {
                aid: $("#kvminfo").val(),
                snapname: $(this).attr("content")
            };
            socket.emit("KVMRemoveBackupReq", D);
            $(this).closest("tr").remove();
        }
    });
    socket.on("KVMRemoveBackupRes", function (l) {
        if (l == "ok") {
            var A = parseInt($("#currentbackupcount").html()) - 1;
            $("#currentbackupcount").html("" + A);
            $("#backup-warning-2").remove();
            $("#backup-warning").removeClass("btn-warning").addClass("btn-success").prop("disabled", false).attr("data-toggle", "modal").attr("data-target", "#backup_modal");
        } else {
            window.location.reload();
        }
    });
    $("[id^=get_backup_config_]").click(function () {
        $(this).prop("disabled", true);
        $("#config_modal").modal("toggle");
        var F = {
            aid: $("#kvminfo").val(),
            volid: $(this).attr("content")
        };
        $("#confheader").html("");
        $("#confheader").html($(this).attr("content").split("/")[1]);
        socket.emit("KVMGetBackupConfReq", F);
    });
    socket.on("KVMGetBackupConfRes", function (l) {
        if (l.status == "ok") {
            $("#confwell").html("");
            $("#confwell").html(l.conf);
        } else {
            $("#confwell").html("Error: could not get backup configuration.");
        }
    });
    $("#config_modal").on("hidden.bs.modal", function (v) {
        $("[id^=get_backup_config_]").prop("disabled", false);
    });
    $("#restore_modal").modal({
        backdrop: "static",
        keyboard: false,
        show: false
    });
    $("[id^=restore_backup_]").click(function () {
        var C = confirm("Are you sure you want to restore this backup? If yes, all current data will be deleted and your VPS will be restored to a previous state.");
        if (C === true) {
            c.respond = false;
            $(this).prop("disabled", true);
            var H = $(this).attr("id").split("_");
            H = H[H.length - 1];
            $("#remove_backup_" + H).prop("disabled", true);
            $("#get_backup_config_" + H).prop("disabled", true);
            $("#countsection > button").prop("disabled", true);
            var G = {
                aid: $("#kvminfo").val(),
                snapname: $(this).attr("content")
            };
            socket.emit("KVMRestoreBackupReq", G);
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
    socket.on("KVMRestoreBackupRes", function (l) {
        if (l == "ok") {
            c.respond = true;
            $("#restore_output").html("Almost done. Saving configuration and cleaning up...");
        } else {
            c.respond = false;
            $("#restore_output").html("An unexpected error occurred.");
        }
    });
    $("#man_rebuild_modal").modal({
        backdrop: "static",
        keyboard: false,
        show: false
    });
    $("#man_rebuild_btn").click(function (v) {
        c.respond = false;
        v.preventDefault();
        $("#man_os_error, #man_hostname_error").html("");
        $(this).prop("disabled", true);
        var L = $("#man_os").val();
        var J = $("#man_hostname").val();
        var I = $("#man_aid").val();
        if (L == "default") {
            $("#man_os_error").html("Error: no operating system was chosen.");
            $(this).prop("disabled", false);
        } else {
            if (!J || 0 === J.length || !J || /^\s*$/.test(J) || J.isEmpty() || !f(J) || !d(J[J.length - 1])) {
                $("#man_hostname_error").html("Error: invalid hostname.");
                $(this).prop("disabled", false);
            } else {
                var K = {
                    os: L,
                    hostname: J,
                    aid: I
                };
                $("#hostname").val("");
                $("#man_os_error, #man_hostname_error").html("");
                var C = confirm("WARNING: Rebuilding your VPS will delete ALL data it currently stores. Do you want to proceed?");
                if (C === true) {
                    socket.emit("KVMRebuildReq", K);
                    $("#man_rebuild_modal").modal("show");
                    $(".man_rebuild_progress").animate({
                        width: "100%"
                    }, 6e4, "swing", function () {
                        $(".man_rebuild_progress").removeClass("progress-bar-info active");
                        $(".man_rebuild_progress").addClass("progress-bar-success");
                        $(".man_rebuild_progress").html("Complete!");
                        setTimeout(function () {
                            $("#man_rebuild_modal").modal("hide");
                            window.location.href = "/index";
                        }, 2500);
                    });
                } else {
                    $("#man_rebuild_btn").prop("disabled", false);
                }
            }
        }
    });
    socket.on("KVMRebuildRes", function (l) {
        if (l == "ok") {
            c.respond = true;
            $("#man_rebuild_output").html("Almost done. Saving configuration and cleaning up...");
        } else {
            c.respond = false;
            $("#man_rebuild_output").html("An unexpected error occurred.");
        }
    });
    $("#man_rebuild_modal").modal({
        backdrop: "static",
        keyboard: false,
        show: false
    });
    $("#temp_rebuild_btn").click(function (v) {
        c.respond = false;
        v.preventDefault();
        $("#temp_os_error, #temp_hostname_error").html("");
        $(this).prop("disabled", true);
        var L = $("#temp_os").val();
        var J = $("#temp_hostname").val();
        var I = $("#temp_aid").val();
        if (L == "default") {
            $("#temp_os_error").html("Error: no operating system was chosen.");
            $(this).prop("disabled", false);
        } else {
            if (!J || 0 === J.length || !J || /^\s*$/.test(J) || J.isEmpty() || !f(J) || !d(J[J.length - 1])) {
                $("#temp_hostname_error").html("Error: invalid hostname.");
                $(this).prop("disabled", false);
            } else {
                var K = {
                    os: L,
                    hostname: J,
                    aid: I
                };
                $("#hostname").val("");
                $("#temp_os_error, #temp_hostname_error").html("");
                var C = confirm("WARNING: Rebuilding your VPS will delete ALL data it currently stores. Do you want to proceed?");
                if (C === true) {
                    socket.emit("KVMRebuildTemplateReq", K);
                    $("#man_rebuild_modal").modal("show");
                    $(".man_rebuild_progress").animate({
                        width: "100%"
                    }, 6e4, "swing", function () {
                        $(".man_rebuild_progress").removeClass("progress-bar-info active");
                        $(".man_rebuild_progress").addClass("progress-bar-success");
                        $(".man_rebuild_progress").html("Complete!");
                        setTimeout(function () {
                            $("#man_rebuild_modal").modal("hide");
                            window.location.href = "/index";
                        }, 2500);
                    });
                } else {
                    $("#temp_rebuild_btn").prop("disabled", false);
                }
            }
        }
    });
    socket.on("KVMRebuildTemplateRes", function (l) {
        if (l == "ok") {
            c.respond = true;
            $("#man_rebuild_output").html("Almost done. Saving configuration and cleaning up...");
        } else {
            c.respond = false;
            $("#man_rebuild_output").html("An unexpected error occurred.");
        }
    });
    $("#kvmconsole").click(function (v) {
        v.preventDefault();
        var M = $(this).attr("role");
        window.open("/console?id=" + M + "&virt=kvm", "_blank", "height=580,width=820,status=yes,toolbar=no,menubar=no,location=no,addressbar=no");
    });
    $("#day, #week, #month, #year").hide();
    var b = "#hour";
    $("#graphtime").change(function () {
        var N = $(this).val();
        $("" + b).hide();
        $("#" + N).show();
        b = "#" + N;
    });
    $("#enableonboot").click(function (v) {
        v.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            aid: $("#kvminfo").val()
        };
        socket.emit("KVMEnableOnbootReq", c);
    });
    socket.on("KVMEnableOnbootRes", function (l) {
        if (l == "ok") {
            window.location.reload();
        }
    });
    $("#disableonboot").click(function (v) {
        v.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            aid: $("#kvminfo").val()
        };
        socket.emit("KVMDisableOnbootReq", c);
    });
    socket.on("KVMDisableOnbootRes", function (l) {
        if (l == "ok") {
            window.location.reload();
        }
    });
    $("#disablerng").click(function (v) {
        v.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            aid: $("#kvminfo").val()
        };
        socket.emit("KVMDisableRNGReq", c);
    });
    socket.on("KVMDisableRNGRes", function (l) {
        if (l == "ok") {
            window.location.reload();
        }
    });
    $("#enablerng").click(function (v) {
        v.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            aid: $("#kvminfo").val()
        };
        socket.emit("KVMEnableRNGReq", c);
    });
    socket.on("KVMEnableRNGRes", function (l) {
        if (l == "ok") {
            window.location.reload();
        }
    });
    $("#enableprivatenet").click(function (v) {
        v.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            aid: $("#kvminfo").val()
        };
        socket.emit("KVMEnablePrivateNetworkReq", c);
    });
    socket.on("KVMEnablePrivateNetworkRes", function (l) {
        window.location.reload();
    });
    $("#disableprivatenet").click(function (v) {
        v.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            aid: $("#kvminfo").val()
        };
        socket.emit("KVMDisablePrivateNetworkReq", c);
    });
    socket.on("KVMDisablePrivateNetworkRes", function (l) {
        if (l == "ok") {
            window.location.reload();
        }
    });
    $("#assignipv6").click(function (v) {
        v.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            aid: $("#kvminfo").val()
        };
        socket.emit("KVMAssignIPv6Req", c);
    });
    socket.on("KVMAssignIPv6Res", function (l) {
        window.location.reload();
    });
    $("#cloudassignip").click(function (v) {
        v.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            aid: $("#kvminfo").val()
        };
        socket.emit("PubCloudAssignIPReq", c);
    });
    socket.on("PubCloudAssignIPRes", function (l) {
        if (l == "ok") {
            window.location.reload();
        }
    });
    $("#cloudremoveip").click(function (v) {
        v.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            aid: $("#kvminfo").val(),
            ip: $(this).attr("role")
        };
        socket.emit("PubCloudRemoveIPReq", c);
    });
    socket.on("PubCloudRemoveIPRes", function (l) {
        if (l == "ok") {
            window.location.reload();
        }
    });
    $("#cldeletevmconfirm").modal({
        backdrop: "static",
        keyboard: false,
        show: false
    });
    $("#cldeletevm").click(function (v) {
        v.preventDefault();
        $(this).prop("disabled", true);
        var O = {
            hb_account_id: $("#kvminfo").val(),
            clpoolid: $(this).attr("role")
        };
        socket.emit("PubCloudDeleteReq", O);
        $("#cldeletevmconfirm").modal("show");
    });
    socket.on("PubCloudDeleteRes", function (l) {
        if (l == "ok") {
            $("#cldeletevmres").html('An email was sent to you containing a confirmation code for this deletion request.<br /><br /><input type="text" placeholder="Enter confirmation code" id="cldeletevmcode" class="form-control" /><br /><br /><button type="button" class="btn btn-md btn-success" id="clconfirmdelete">Confirm deletion request</button><br /><br /><br /><br /><button type="button" class="btn btn-md btn-danger" id="clcanceldelete">Cancel deletion request</button>');
        } else {
            $("#cldeletevmres").html("An error occurred while attempting to process your deletion request.");
        }
    });
    $("#cldeletevmres").on("click", "#clconfirmdelete", function (v) {
        c.respond = false;
        v.preventDefault();
        $(this).prop("disabled", true);
        $("#clcanceldelete").prop("disabled", true);
        $("#cldeletevmcode").prop("disabled", true);
        var O = {
            hb_account_id: $("#kvminfo").val(),
            clpoolid: $("#cldeletevm").attr("role"),
            delcode: $("#cldeletevmcode").val()
        };
        socket.emit("PubCloudConfirmDeleteReq", O);
        $("#cldeletevmres").append("<br /><br />Please wait...");
    });
    socket.on("PubCloudConfirmDeleteRes", function (l) {
        if (l == "ok") {
            window.location.href = "/index";
        } else {
            $("#cldeletevmres").append("<br /><br />An error has occurred.");
            $("#clcanceldelete").prop("disabled", false);
        }
    });
    $("#cldeletevmres").on("click", "#clcanceldelete", function (v) {
        v.preventDefault();
        $(this).prop("disabled", true);
        var O = {
            hb_account_id: $("#kvminfo").val()
        };
        socket.emit("PubCloudCancelDeleteReq", O);
    });
    socket.on("PubCloudCancelDeleteRes", function (l) {
        if (l == "ok") {
            $("#cldeletevmconfirm").modal("hide");
            $("#cldeletevm").prop("disabled", false);
        } else {
            $("#cldeletevmres").html("An error occurred while attempting to cancel this request.");
        }
    });
    $("#changeiso").change(function () {
        if ($(this).find(":selected").attr("role") != "first") {
            $("#changeisosubmit").prop("disabled", false);
        } else {
            $("#changeisosubmit").prop("disabled", true);
        }
    });
    $("#changeisosubmit").click(function (v) {
        v.preventDefault();
        $(this).prop("disabled", true);
        var O = {
            hb_account_id: $("#kvminfo").val(),
            iso: $("#changeiso").val()
        };
        socket.emit("KVMChangeISOReq", O);
    });
    socket.on("KVMChangeISORes", function (l) {
        if (l == "ok") {
            window.location.reload();
        } else {
            $("#changeisosubmit").html("Error");
        }
    });
    $("#bosubmit").click(function (v) {
        v.preventDefault();
        $(this).prop("disabled", true);
        var O = {
            hb_account_id: $("#kvminfo").val(),
            bo1: $("#bo1").val(),
            bo2: $("#bo2").val(),
            bo3: $("#bo3").val()
        };
        socket.emit("KVMBootOrderReq", O);
    });
    socket.on("KVMBootOrderRes", function (l) {
        if (l == "ok") {
            window.location.reload();
        } else {
            $("#bosubmit").html("Error");
        }
    });
    $("#chgrootpw_kvm").click(function (v) {
        v.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            aid: $("#kvminfo").val()
        };
        socket.emit("KVMChangePWReq", c);
    });
    socket.on("KVMChangePWRes", function (l) {
        if (l.status == "ok") {
            $("#kvm_pwsuccess").html('<div class="alert alert-success" role="alert"><strong>New Root Password:</strong> ' + l.password + "</div>");
        } else {
            $("#chgrootpw_kvm").html("Error");
            $("#kvm_pwsuccess").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> reset root password failed.</div>');
        }
    });
    $("#getvmlog").click(function (v) {
        v.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            aid: $("#kvminfo").val()
        };
        socket.emit("KVMGetLogReq", c);
    });
    $("#clearvmlog").click(function (v) {
        v.preventDefault();
        $("#vmlog").html("null");
    });
    socket.on("KVMGetLogRes", function (l) {
        if (l.status == "ok") {
            $("#vmlog").html(l.log);
            $("#getvmlog").prop("disabled", false);
        } else {
            $("#vmlog").html("Error fetching log");
        }
    });
    if ($("#template_setup_div").length) {
        location.reload(true);
    };
    $("#natport_btn").click(function (v) {
        v.preventDefault();
        $("#natport_error, #natdesc_error").html("");
        $(this).prop("disabled", true);
        var Q = $("#chosennatport").val();
        var P = $("#natportdesc").val();
        var I = $("#aid").val();
        if (Q < 1 || Q > 65535) {
            $("#natport_error").html("Error: invalid NAT port.");
            $(this).prop("disabled", false);
        } else {
            if (!P || 0 === P.length || !P || /^\s*$/.test(P) || P.isEmpty()) {
                $("#natdesc_error").html("Error: invalid description.");
                $(this).prop("disabled", false);
            } else {
                var R = {
                    natport: Q,
                    natdesc: P,
                    aid: I
                };
                $("#chosennatport, #natportdesc").val("");
                $("#natport_error, #natdesc_error").html("");
                socket.emit("KVMAddNATPortReq", R);
            }
        }
    });
    socket.on("KVMAddNATPortRes", function (l) {
        if (l == "ok") {
            window.location.reload();
        } else {
            $("#natport_error").html("Unable to add new NAT port forward.");
        }
    });
    $("#user_porttable").on("click", "[id^=user_natportdelete]", function (v) {
        v.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            id: $(this).attr("role"),
            aid: $("#kvminfo").val()
        };
        socket.emit("KVMDelNATPortReq", c);
    });
    socket.on("KVMDelNATPortRes", function (l) {
        window.location.reload();
    });
    $("#natdomain_btn").click(function (v) {
        v.preventDefault();
        $("#natdomain_error").html("");
        $(this).prop("disabled", true);
        var S = $("#chosendomain").val().trim();
        var T = $("#nat_sslcert").val().trim();
        var U = $("#nat_sslkey").val().trim();
        var I = $("#aid").val();
        if (!S || 0 === S.length || !S || /^\s*$/.test(S) || S.isEmpty() || !f(S) || !d(S[S.length - 1])) {
            $("#natdomain_error").html("Error: invalid NAT domain.");
            $(this).prop("disabled", false);
        } else {
            var V = {
                natdomain: S,
                natsslcert: T,
                natsslkey: U,
                aid: I
            };
            $("#chosendomain").val("");
            $("#nat_sslcert").val("");
            $("#nat_sslkey").val("");
            $("#natdomain_error").html("");
            socket.emit("KVMAddNATDomainReq", V);
        }
    });
    socket.on("KVMAddNATDomainRes", function (l) {
        if (l == "ok") {
            window.location.reload();
        } else {
            $("#natdomain_error").html("Unable to add new NAT domain forward.");
            $("#natdomain_btn").prop("disabled", false);
        }
    });
    $("#user_domaintable").on("click", "[id^=user_natdomaindelete]", function (v) {
        v.preventDefault();
        $(this).prop("disabled", true);
        var c = {
            id: $(this).attr("role"),
            aid: $("#kvminfo").val()
        };
        socket.emit("KVMDelNATDomainReq", c);
    });
    socket.on("KVMDelNATDomainRes", function (l) {
        window.location.reload();
    });
}(jQuery));
$(document).ready(function () {
    if (typeof Storage !== "undefined" && typeof localStorage.getItem("proxcp_user_backup_progress") == "string") {
        pendingbackups = JSON.parse(atob(localStorage.getItem("proxcp_user_backup_progress")));
    };
    if (pendingbackups.length > 0) {
        $("#countsection").html('<button type="button" class="btn btn-md btn-warning btn-block" disabled="disabled" id="backup-warning">Create backup</button><span id="backup-warning-2">&nbsp;&nbsp;&nbsp;&nbsp;<small><em>Please wait...checking status of last backup.</em></small></span><br /><br />');
    }
});
