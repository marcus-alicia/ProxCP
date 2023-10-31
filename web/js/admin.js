(function ($) {
  $("#admin_usertable").DataTable();
  $("#admin_nodetable").DataTable();
  $("#admin_natnodetable").DataTable();
  $("#admin_nodelvl1table").DataTable();
  $("#admin_lxctable").DataTable();
  $("#admin_kvmtable").DataTable();
  $("#admin_lxctemptable").DataTable();
  $("#admin_kvmisotable").DataTable();
  $("#admin_acltable").DataTable();
  $("#admin_cloudtable").DataTable();
  $("#admin_domainstable").DataTable();
  $("#admin_recordstable").DataTable();
  $("#admin_ptrtable").DataTable();
  $("#admin_ip2table").DataTable();
  $("#admin_privatetable").DataTable();
  $("#admin_v6poolstable").DataTable();
  $("#admin_v6assigntable").DataTable();
  $("#admin_general_log").DataTable();
  $("#admin_admin_log").DataTable();
  $("#admin_error_log").DataTable();
  function c(I) {
    var F = Math.floor(I / 86400);
    var G = Math.floor(I % 86400 / 3600);
    var H = Math.floor(I % 86400 % 3600 / 60);
    var J = I % 86400 % 3600 % 60;
    return F + " days " + G + " hours " + H + " minutes " + J + " seconds";
  }
  $("#admin_usertable").on("click", "[id^=acctlock]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var d = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMUserLockReq", d);
  });
  socket.on("ADMUserLockRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    }
  });
  $("#admin_usertable").on("click", "[id^=acctunlock]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var d = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMUserUnlockReq", d);
  });
  socket.on("ADMUserUnlockRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    }
  });
  $("#admin_usertable").on("click", "[id^=acctpw]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var d = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMUserPWReq", d);
  });
  socket.on("ADMUserPWRes", function (f) {
    if (f.status == "ok") {
      $("#adm_message").html('<div class="alert alert-success" role="alert"><strong>New Password:</strong> <input type="text" class="form-control" value="' + f.pw + '" /></div>');
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> unknown error has occurred.</div>');
    }
  });
  $("#admin_usertable").on("click", "[id^=resetbw]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var d = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMResetBWReq", d);
  });
  socket.on("ADMResetBWRes", function (f) {
    window.location.reload();
  });
  $("#admin_nodetable").on("click", "[id^=admin_nodedelete]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var g = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMNodeDeleteReq", g);
  });
  socket.on("ADMNodeDeleteRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not delete node.</div>');
    }
  });
  $("#admin_nodetable").on("click", "[id^=admin_tuntapdelete]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var g = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMTunDeleteReq", g);
  });
  socket.on("ADMTunDeleteRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      if (f == "oknat") {
        $("#adm_message").html('<div class="alert alert-success" role="alert"><strong>Success:</strong> credentials have been deleted however NAT features will not work unless you add new credentials for this node.</div>');
      } else {
        $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not delete credentials.</div>');
      }
    }
  });
  $("#admin_nodetable").on("click", "[id^=admin_dhcpdelete]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var g = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMDHCPDeleteReq", g);
  });
  socket.on("ADMDHCPDeleteRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not delete DHCP server.</div>');
    }
  });
  $("#admin_usertable").on("click", "[id^=admin_userdelete]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var h = confirm("Are you sure you want to delete this user?");
    if (h === true) {
      var g = { id: $(this).attr("role"), by: $("#user").val() };
      socket.emit("ADMUserDeleteReq", g);
    } else {
      $(this).prop("disabled", false);
      return;
    }
  });
  socket.on("ADMUserDeleteRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not delete user.</div>');
    }
  });
  $("#natnode").change(function () {
    if ($(this).val() != "default") {
      socket.emit("ADMQueryNATDNSReq", { id: $("#natnode option:selected").text(), by: $("#user").val() });
    } else {
      $("#natnodeip").val("");
    }
  });
  socket.on("ADMQueryNATDNSRes", function (f) {
    if (f.status == "ok") {
      $("#natnodeip").val(f.ipv4);
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not resolve node hostname (DNS A record IPv4).</div>');
    }
  });
  $("#node").change(function () {
    $("#storage_location").html('<option value="default">Select...</option>');
    if ($(this).val() != "default") {
      socket.emit("ADMQueryStorageReq", { id: $(this).val(), by: $("#user").val() });
    } else {
      $("#storage_location").html('<option value="default">Select...</option>');
    }
  });
  socket.on("ADMQueryStorageRes", function (f) {
    if (f.status == "ok") {
      $.each(f.locs, function (i, j) {
        $("#storage_location").append($("<option>", { value: j, text: j }));
      });
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not query node storage.</div>');
    }
  });
  $("#os_installation_type").change(function () {
    if ($(this).val() == "iso") {
      $("#admin_createkvm_iso").show();
      $("#admin_createkvm_next").show();
      $("#admin_createkvm_template").hide();
    } else {
      if ($(this).val() == "template") {
        $("#admin_createkvm_template").show();
        $("#admin_createkvm_next").show();
        $("#admin_createkvm_iso").hide();
      } else {
        $("#admin_createkvm_iso").hide();
        $("#admin_createkvm_template").hide();
        $("#admin_createkvm_next").hide();
      }
    }
  });
  $("#lxcisnat").change(function () {
    if ($(this).val() == "true") {
      $("#lxcnatfields").show();
      $("#ipv4").attr("placeholder", "1.1.1.5/CIDR, must be within NAT range");
    } else {
      $("#lxcnatfields").hide();
      $("#ipv4").attr("placeholder", "1.1.1.5/CIDR");
    }
  });
  $("#kvmisnat").change(function () {
    if ($(this).val() == "true") {
      $("#kvmnatfields").show();
      $("#ipv4").attr("placeholder", "1.1.1.5, must be within NAT range");
    } else {
      $("#kvmnatfields").hide();
      $("#ipv4").attr("placeholder", "1.1.1.5");
    }
  });
  $("#admin_lxctable").on("click", "[id^=admin_lxcdelete]", function (e) {
    var k = confirm("== MANUAL DELETION ==\n\nRemoval from ProxCP only. Pools, users, and/or VMs may need to be manually removed from Proxmox too!");
    if (k == true) {
      e.preventDefault();
      $(this).prop("disabled", true);
      var g = { id: $(this).attr("role"), by: $("#user").val() };
      socket.emit("ADMLXCDeleteReq", g);
    } else {
      return false;
    }
  });
  socket.on("ADMLXCDeleteRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not delete LXC from database.</div>');
    }
  });
  $("#selectnodestats").change(function () {
    if ($(this).val() != "default") {
      socket.emit("ADMQueryNodesReq", { id: $(this).val(), by: $("#user").val() });
    }
    ;
    if (typeof Storage !== "undefined") {
      localStorage.setItem("proxcp_last_node_selection", btoa($(this).val()));
    }
  });
  socket.on("ADMQueryNodesRes", function (f) {
    if (f.status == "ok") {
      $("#admin_nodestatus2").removeClass("label-danger").addClass("label-success").html('<i class="fa fa-check"></i> Online');
      var l = Math.round(f.cpuusage * 100);
      var n = Math.round(f.ramusage.used / f.ramusage.total * 100);
      var m = Math.round(f.diskusage.used / f.diskusage.total * 100);
      var o = Math.round(f.swapusage.used / f.swapusage.total * 100) || 0;
      $("#admin_cpu_1").attr("aria-valuenow", l);
      $("#admin_cpu_1").css("width", l + "%");
      $("#admin_cpu_2").html(l + "%");
      if (l <= 33) {
        $("#admin_cpu_1").removeClass("progress-bar-info");
        $("#admin_cpu_1").removeClass("progress-bar-warning");
        $("#admin_cpu_1").removeClass("progress-bar-danger");
        $("#admin_cpu_1").addClass("progress-bar-success");
      } else {
        if (l >= 34 && l <= 66) {
          $("#admin_cpu_1").removeClass("progress-bar-info");
          $("#admin_cpu_1").removeClass("progress-bar-success");
          $("#admin_cpu_1").removeClass("progress-bar-danger");
          $("#admin_cpu_1").addClass("progress-bar-warning");
        } else {
          $("#admin_cpu_1").removeClass("progress-bar-info");
          $("#admin_cpu_1").removeClass("progress-bar-warning");
          $("#admin_cpu_1").removeClass("progress-bar-success");
          $("#admin_cpu_1").addClass("progress-bar-danger");
        }
      }
      ;
      $("#admin_ram_1").attr("aria-valuenow", n);
      $("#admin_ram_1").css("width", n + "%");
      $("#admin_ram_2").html(n + "%");
      if (n <= 33) {
        $("#admin_ram_1").removeClass("progress-bar-info");
        $("#admin_ram_1").removeClass("progress-bar-warning");
        $("#admin_ram_1").removeClass("progress-bar-danger");
        $("#admin_ram_1").addClass("progress-bar-success");
      } else {
        if (n >= 34 && n <= 66) {
          $("#admin_ram_1").removeClass("progress-bar-info");
          $("#admin_ram_1").removeClass("progress-bar-success");
          $("#admin_ram_1").removeClass("progress-bar-danger");
          $("#admin_ram_1").addClass("progress-bar-warning");
        } else {
          $("#admin_ram_1").removeClass("progress-bar-info");
          $("#admin_ram_1").removeClass("progress-bar-warning");
          $("#admin_ram_1").removeClass("progress-bar-success");
          $("#admin_ram_1").addClass("progress-bar-danger");
        }
      }
      ;
      $("#admin_disk_1").attr("aria-valuenow", m);
      $("#admin_disk_1").css("width", m + "%");
      $("#admin_disk_2").html(m + "%");
      if (m <= 33) {
        $("#admin_disk_1").removeClass("progress-bar-info");
        $("#admin_disk_1").removeClass("progress-bar-warning");
        $("#admin_disk_1").removeClass("progress-bar-danger");
        $("#admin_disk_1").addClass("progress-bar-success");
      } else {
        if (m >= 34 && m <= 66) {
          $("#admin_disk_1").removeClass("progress-bar-info");
          $("#admin_disk_1").removeClass("progress-bar-success");
          $("#admin_disk_1").removeClass("progress-bar-danger");
          $("#admin_disk_1").addClass("progress-bar-warning");
        } else {
          $("#admin_disk_1").removeClass("progress-bar-info");
          $("#admin_disk_1").removeClass("progress-bar-warning");
          $("#admin_disk_1").removeClass("progress-bar-success");
          $("#admin_disk_1").addClass("progress-bar-danger");
        }
      }
      ;
      $("#admin_swap_1").attr("aria-valuenow", o);
      $("#admin_swap_1").css("width", o + "%");
      $("#admin_swap_2").html(o + "%");
      if (o <= 33) {
        $("#admin_swap_1").removeClass("progress-bar-info");
        $("#admin_swap_1").removeClass("progress-bar-warning");
        $("#admin_swap_1").removeClass("progress-bar-danger");
        $("#admin_swap_1").addClass("progress-bar-success");
      } else {
        if (o >= 34 && o <= 66) {
          $("#admin_swap_1").removeClass("progress-bar-info");
          $("#admin_swap_1").removeClass("progress-bar-success");
          $("#admin_swap_1").removeClass("progress-bar-danger");
          $("#admin_swap_1").addClass("progress-bar-warning");
        } else {
          $("#admin_swap_1").removeClass("progress-bar-info");
          $("#admin_swap_1").removeClass("progress-bar-warning");
          $("#admin_swap_1").removeClass("progress-bar-success");
          $("#admin_swap_1").addClass("progress-bar-danger");
        }
      }
      ;
      $("#node_uptime").html(c(f.uptime));
      $("#node_loadavg").html(f.loadavg.toString());
      $("#node_kernel").html(f.kernel);
      $("#node_pve").html(f.pve);
      $("#node_cpumod").html(f.cpumod);
    } else {
      $("#admin_nodestatus2").removeClass("label-success").addClass("label-danger").html('<i class="fa fa-times"></i> Offline');
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not query node stats.</div>');
    }
  });
  $("#admin_lxctable").on("click", "[id^=lxcsuspend]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var d = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMLXCSuspendReq", d);
  });
  socket.on("ADMLXCSuspendRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    }
  });
  $("#admin_lxctable").on("click", "[id^=lxcunsuspend]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var d = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMLXCUnsuspendReq", d);
  });
  socket.on("ADMLXCUnsuspendRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    }
  });
  $("#admin_kvmtable").on("click", "[id^=kvmsuspend]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var d = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMKVMSuspendReq", d);
  });
  socket.on("ADMKVMSuspendRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    }
  });
  $("#admin_kvmtable").on("click", "[id^=kvmunsuspend]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var d = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMKVMUnsuspendReq", d);
  });
  socket.on("ADMKVMUnsuspendRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    }
  });
  $("#admin_kvmtable").on("click", "[id^=admin_kvmdelete]", function (e) {
    var k = confirm("== MANUAL DELETION ==\n\nRemoval from ProxCP only. Pools, users, and/or VMs may need to be manually removed from Proxmox too!");
    if (k == true) {
      e.preventDefault();
      $(this).prop("disabled", true);
      var g = { id: $(this).attr("role"), by: $("#user").val() };
      socket.emit("ADMKVMDeleteReq", g);
    } else {
      return false;
    }
  });
  socket.on("ADMKVMDeleteRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not delete KVM from database.</div>');
    }
  });
  $("#admin_lxctemptable").on("click", "[id^=admin_lxctempdelete]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var g = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMLXCTempDeleteReq", g);
  });
  socket.on("ADMLXCTempDeleteRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not delete LXC template from database.</div>');
    }
  });
  $("#admin_lxctemptable").on("click", "[id^=admin_apidelete]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var g = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMAPIDeleteReq", g);
  });
  socket.on("ADMAPIDeleteRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not delete API pair from database.</div>');
    }
  });
  $("#admin_lxctemptable").on("click", "[id^=admin_kvmtempdelete]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var g = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMKVMTempDeleteReq", g);
  });
  socket.on("ADMKVMTempDeleteRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not delete KVM template from database.</div>');
    }
  });
  $("#admin_kvmisotable").on("click", "[id^=admin_kvmisodelete]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var g = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMKVMISODeleteReq", g);
  });
  socket.on("ADMKVMISODeleteRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not delete KVM ISO from database.</div>');
    }
  });
  $("#admin_kvmisotable").on("click", "[id^=admin_kvmcustomisodelete]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var g = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMKVMCustomISODeleteReq", g);
  });
  socket.on("ADMKVMCustomISODeleteRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not delete KVM custom ISO.</div>');
    }
  });
  $("#admin_acltable").on("click", "[id^=admin_acldelete]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var g = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMACLDeleteReq", g);
  });
  socket.on("ADMACLDeleteRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not delete user ACL from database.</div>');
    }
  });
  $("#getcloudhbid").change(function () {
    if ($(this).val() != "default") {
      socket.emit("ADMQueryCloudReq", { id: $(this).val(), by: $("#user").val() });
    } else {
      $("#getipv4").val("");
      $("#getipv4_avail").val("");
      $("#getcpucores").val("");
      $("#getcpucores_avail").val("");
      $("#getram").val("");
      $("#getram_avail").val("");
      $("#getstorage_size").val("");
      $("#getstorage_size_avail").val("");
    }
  });
  socket.on("ADMQueryCloudRes", function (f) {
    if (f.status == "ok") {
      $("#getipv4").val(f.ipv4);
      $("#getipv4_avail").val(f.ipv4_avail);
      $("#getcpucores").val(f.cpucores);
      $("#getcpucores_avail").val(f.cpucores_avail);
      $("#getram").val(f.ram);
      $("#getram_avail").val(f.ram_avail);
      $("#getstorage_size").val(f.storage_size);
      $("#getstorage_size_avail").val(f.storage_size_avail);
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not query cloud account.</div>');
    }
  });
  $("#editcloudaccount").click(function (e) {
    var k = confirm("== MANUAL EDITING ==\n\nEditing the account in ProxCP only. Pools, users, and/or VMs may need to be manually changed within Proxmox too!");
    if (k == true) {
      e.preventDefault();
      $(this).prop("disabled", true);
      var g = { by: $("#user").val(), id: $("#getcloudhbid").val(), ipv4: $("#getipv4").val(), ipv4_avail: $("#getipv4_avail").val(), cpucores: $("#getcpucores").val(), cpucores_avail: $("#getcpucores_avail").val(), ram: $("#getram").val(), ram_avail: $("#getram_avail").val(), storage_size: $("#getstorage_size").val(), storage_size_avail: $("#getstorage_size_avail").val() };
      socket.emit("ADMEditCloudReq", g);
    } else {
      return false;
    }
  });
  socket.on("ADMEditCloudRes", function (f) {
    if (f == "ok") {
      window.location.reload();
      window.scrollTo(0, 0);
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not edit cloud account.</div>');
      window.scrollTo(0, 0);
    }
  });
  $("#admin_cloudtable").on("click", "[id^=cloudsuspend]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var d = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMCloudSuspendReq", d);
  });
  socket.on("ADMCloudSuspendRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    }
  });
  $("#admin_cloudtable").on("click", "[id^=cloudunsuspend]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var d = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMCloudUnsuspendReq", d);
  });
  socket.on("ADMCloudUnsuspendRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    }
  });
  $("#admin_cloudtable").on("click", "[id^=admin_clouddelete]", function (e) {
    var k = confirm("== MANUAL DELETION ==\n\nRemoval from ProxCP only. Pools, users, and/or VMs may need to be manually removed from Proxmox too!");
    if (k == true) {
      e.preventDefault();
      $(this).prop("disabled", true);
      var g = { id: $(this).attr("role"), by: $("#user").val() };
      socket.emit("ADMCloudDeleteReq", g);
    } else {
      window.location.reload();
    }
  });
  socket.on("ADMCloudDeleteRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not delete cloud account from database.</div>');
    }
  });
  $("#queryvmprops").change(function () {
    if ($(this).val() != "default") {
      socket.emit("ADMQueryPropsReq", { id: $(this).val(), by: $("#user").val() });
    } else {
      $("#userid").val("");
      $("#vmnode").val("");
      $("#vmos").val("");
      $("#vmip").val("");
      $("#vmip_old").val("");
      $("#vmip_gateway").val("");
      $("#vmip_netmask").val("");
      $("#vm_backups").val("");
      $("#vm_poolname").val("");
      $("#vm_poolpw").val("");
      $("#vm_backup_override").val("");
    }
  });
  socket.on("ADMQueryPropsRes", function (f) {
    if (f.status == "ok") {
      $("#userid").val(f.userid);
      $("#vmnode").val(f.vmnode);
      $("#vmos").val(f.vmos);
      $("#vmip").val(f.vmip);
      $("#vmip_old").val(f.vmip);
      $("#vmip_gateway").val(f.vmip_gateway);
      $("#vmip_netmask").val(f.vmip_netmask);
      $("#vm_backups").val(f.backups);
      $("#vm_poolname").val(f.poolname);
      $("#vm_backup_override").val(f.override);
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not query current VM properties from database.</div>');
    }
  });
  $("#admin_recordstable").on("click", "[id^=admin_recorddelete]", function (e) {
    var k = confirm("== MANUAL DELETION ==\n\nRemoval from ProxCP only. Further action may be required in WHM too!");
    if (k == true) {
      e.preventDefault();
      $(this).prop("disabled", true);
      var g = { id: $(this).attr("role"), by: $("#user").val() };
      socket.emit("ADMRecordDeleteReq", g);
    } else {
      return false;
    }
  });
  socket.on("ADMRecordDeleteRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not delete DNS record from database.</div>');
    }
  });
  $("#admin_domainstable").on("click", "[id^=admin_domaindelete]", function (e) {
    var k = confirm("== MANUAL DELETION ==\n\nRemoval from ProxCP only. Further action may be required in WHM too!");
    if (k == true) {
      e.preventDefault();
      $(this).prop("disabled", true);
      var g = { id: $(this).attr("role"), by: $("#user").val() };
      socket.emit("ADMDomainDeleteReq", g);
    } else {
      return false;
    }
  });
  socket.on("ADMDomainDeleteRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not delete domain from database.</div>');
    }
  });
  $("#admin_ptrtable").on("click", "[id^=admin_ptrdelete]", function (e) {
    var k = confirm("== MANUAL DELETION ==\n\nRemoval from ProxCP only. Further action may be required in WHM too!");
    if (k == true) {
      e.preventDefault();
      $(this).prop("disabled", true);
      var g = { id: $(this).attr("role"), by: $("#user").val() };
      socket.emit("ADMPTRDeleteReq", g);
    } else {
      return false;
    }
  });
  socket.on("ADMPTRDeleteRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not delete PTR from database.</div>');
    }
  });
  $("#admin_ip2table").on("click", "[id^=admin_ip2delete]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var g = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMIP2DeleteReq", g);
  });
  socket.on("ADMIP2DeleteRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not delete secondary IP from database.</div>');
    }
  });
  $("#admin_privatetable").on("click", "[id^=admin_privatedelete]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var g = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMPrivateDeleteReq", g);
  });
  socket.on("ADMPrivateDeleteRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not clear private assignment from database.</div>');
    }
  });
  $("#admin_privatetable").on("click", "[id^=admin_publicdelete]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var g = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMPublicDeleteReq", g);
  });
  socket.on("ADMPublicDeleteRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not clear public assignment from database.</div>');
    }
  });
  $("#admin_privatetable").on("click", "[id^=admin_publicclr]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var g = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMPublicClrReq", g);
  });
  socket.on("ADMPublicClrRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not delete IP from database.</div>');
    }
  });
  $("#admin_privatetable").on("click", "[id^=admin_setip]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    $("#admin_ipseti" + $(this).attr("role")).prop("readonly", true);
    var g = { id: $(this).attr("role"), hbid: $("#admin_ipseti" + $(this).attr("role")).val(), by: $("#user").val() };
    socket.emit("ADMSetIPReq", g);
  });
  socket.on("ADMSetIPRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not assign IP in database.</div>');
    }
  });
  $("#admin_v6assigntable").on("click", "[id^=admin_v6assigndelete]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var g = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMIPv6AssignDeleteReq", g);
  });
  socket.on("ADMIPv6AssignDeleteRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not delete IPv6 assignment from database.</div>');
    }
  });
  $("#admin_v6poolstable").on("click", "[id^=admin_v6pooldelete]", function (e) {
    e.preventDefault();
    $(this).prop("disabled", true);
    var g = { id: $(this).attr("role"), by: $("#user").val() };
    socket.emit("ADMIPv6PoolDeleteReq", g);
  });
  socket.on("ADMIPv6PoolDeleteRes", function (f) {
    if (f == "ok") {
      window.location.reload();
    } else {
      $("#adm_message").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not delete IPv6 pool from database.</div>');
    }
  });
  $("form#adm_createkvm_form").submit(function () {
    $(this).find(":input[type=submit]").prop("disabled", true).val("PLEASE WAIT...STAY ON THIS PAGE UNTIL COMPLETED");
  });
  $("form#adm_createlxc_form").submit(function () {
    $(this).find(":input[type=submit]").prop("disabled", true).val("PLEASE WAIT...STAY ON THIS PAGE UNTIL COMPLETED");
  });
  $("form#adm_natnode_form").submit(function () {
    $(this).find(":input[type=submit]").prop("disabled", true).val("PLEASE WAIT...STAY ON THIS PAGE UNTIL COMPLETED");
  });
  var b = null;
  $("#natnode_lvl1").on("show.bs.modal", function (q) {
    var p = $(q.relatedTarget);
    var t = p.data("node");
    var s = $(this);
    s.find(".modal-title").text("Detailed View - " + t);
    var g = { id: t, by: $("#user").val() };
    $("#lvl1_error").html("");
    b = null;
    $("#admin_nodelvl1table").DataTable().clear().draw();
    socket.emit("ADMQueryLvl1Req", g);
  });
  socket.on("ADMQueryLvl1Res", function (f) {
    if (f.status == "ok") {
      b = f.tbl;
      $("#lvl1_error").html("");
      for (var i = 0; i < f.tbl.length; i++) {
        var v = f.tbl[i].ports.split(";").length - 1;
        var u = f.tbl[i].domains.split(";").length - 1;
        $("#admin_nodelvl1table").DataTable().row.add([f.tbl[i].username, f.tbl[i].hb_account_id, v + " / " + f.tbl[i].avail_ports, u + " / " + f.tbl[i].avail_domains]);
      }
      ;
      $("#admin_nodelvl1table").DataTable().draw();
      $("#admin_nodelvl1table tbody tr").css("cursor", "pointer");
    } else {
      $("#admin_nodelvl1table").DataTable().clear().draw();
      b = null;
      $("#lvl1_error").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong> could not query NAT stats - level 1.</div>');
    }
  });
  $("#admin_nodelvl1table tbody").on("click", "tr", function () {
    var z = $("#admin_nodelvl1table").DataTable().row(this).data();
    var B = null;
    if (z != undefined) {
      B = z[1];
    }
    ;
    var C = null;
    for (var i = 0; i < b.length; i++) {
      if (b[i].hb_account_id == B) {
        C = b[i];
        break;
      }
    }
    ;
    if (C == null && B != null) {
      $.confirm({
        title: '<span style="cursor:move;"><strong>Error</strong></span>', content: "Could not find NAT information for the selected virtual machine.", draggable: true, buttons: {
          close: {
            text: "Close", btnClass: "btn btn-info", keys: [], isHidden: false, isDisabled: false, action: function (E) {
              return true;
            }
          }
        }, type: "red", icon: "fa fa-info-circle", containerFluid: true, columnClass: "large"
      });
    } else {
      if (B == null) {
        $.confirm({
          title: '<span style="cursor:move;"><strong>Info</strong></span>', content: "No information available.", draggable: true, buttons: {
            close: {
              text: "Close", btnClass: "btn btn-info", keys: [], isHidden: false, isDisabled: false, action: function (E) {
                return true;
              }
            }
          }, type: "blue", icon: "fa fa-info-circle", containerFluid: true, columnClass: "large"
        });
      } else {
        var w = C.domains.split(";");
        var x = C.ports.split(";");
        var A = "<ul>";
        for (var i = 0; i < w.length - 1; i++) {
          A += "<li>" + w[i] + "</li>";
        }
        ;
        A += "</ul>";
        var D = "<ul>";
        for (var i = 0; i < x.length - 1; i++) {
          var y = x[i].split(":");
          D += "<li>" + y[1] + ' <i class="fa fa-arrow-right"></i> ' + y[2] + " : " + y[3] + "</li>";
        }
        ;
        D += "</ul>";
        $.confirm({
          title: '<span style="cursor:move;"><strong>Billing ID ' + B + ": NAT Domains and Ports</strong></span>", content: '<div class="col-md-6"><div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">NAT Domains</h3></div><div class="panel-body">' + A + '</div></div></div><div class="col-md-6"><div class="panel panel-default"><div class="panel-heading"><h3 class="panel-title">NAT Ports</h3></div><div class="panel-body">' + D + "</div></div></div>", draggable: true, buttons: {
            close: {
              text: "Close", btnClass: "btn btn-info", keys: [], isHidden: false, isDisabled: false, action: function (E) {
                return true;
              }
            }
          }, type: "blue", icon: "fa fa-info-circle", containerFluid: true, columnClass: "large"
        });
      }
    }
  });
}(jQuery));
$(document).ready(function () {
  if (typeof Storage !== "undefined" && (typeof localStorage.getItem("proxcp_last_node_selection") == "string" && (atob(localStorage.getItem("proxcp_last_node_selection")) != "undefined" || atob(localStorage.getItem("proxcp_last_node_selection")) != "default"))) {
    $("#selectnodestats").val(atob(localStorage.getItem("proxcp_last_node_selection"))).change();
  }
});
