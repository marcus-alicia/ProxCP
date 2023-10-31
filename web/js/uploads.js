jQuery(document)["ready"](function ($) {
    var c = $("#upload");
    var b = $("#tus-file");
    $(".file-input")["on"]("change", function (d) {
        var g = d["target"]["value"]["split"]("\\")["reverse"]()[0];
        var f = g["substr"](g["length"] - 4);
        if (g && f == ".iso") {
            c["attr"]("disabled", false);
        } else {
            c["attr"]("disabled", true);
        }
    });
    c["on"]("click", function (d) {
        var k = new FormData();
        var i = b[0]["files"][0];
        var j = i["size"];
        var h = 0;
        k["append"]("tus_file", i);
        k["append"]("useriso_fname", $("#useriso_fname")["val"]());
        k["append"]("useriso_type", $("#useriso_type")["val"]());
        k["append"]("useriso_who", $("#user")["val"]());
        b["attr"]("disabled", true);
        $("#useriso_fname")["attr"]("disabled", true);
        $("#useriso_type")["attr"]("disabled", true);
        $("[id^=useriso_delete]")["attr"]("disabled", true);
        c["attr"]("disabled", true)["text"]("Calculating...");
        initiateUpload(k, i, function () {
            upload(k, j, function (l) {
                h = l;
                renderProgressBar(h, j);
            }, function (m) {
                cleanUp();
                listUploadedFiles(i, m);
            });
        });
    });
    $("[id^=useriso_delete]")["click"](function (d) {
        d["preventDefault"]();
        $(this)["prop"]("disabled", true);
        var l = {
            id: $(this)["attr"]("role")
        };
        socket["emit"]("UserISODeleteReq", l);
    });
    socket["on"]("UserISODeleteRes", function (n) {
        window["location"]["reload"]();
    });
});

function initiateUpload(k, i, q) {
    $["ajax"]({
        type: "POST",
        url: "verify.php",
        data: k,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function (r) {
            if ("error" === r["status"]) {
                $("#error")["html"](r["error"])["fadeIn"](200);
                cleanUp();
                return;
            };
            renderProgressBar(r["bytes_uploaded"], i["size"]);
            if ("uploaded" === r["status"]) {
                cleanUp();
                listUploadedFiles(i, r["upload_key"]);
            } else {
                if ("error" !== r["status"]) {
                    q();
                }
            }
        },
        error: function (s) {
            $("#error")["fadeIn"](200);
        }
    });
}

function upload(k, j, q, t) {
    $("#upload")["text"]("Uploading...");
    $["ajax"]({
        type: "POST",
        url: "upload.php",
        data: k,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function (r) {
            if ("error" === r["status"]) {
                $("#error")["html"](r["error"])["fadeIn"](200);
                cleanUp();
                return;
            };
            var h = r["bytes_uploaded"];
            q(h);
            if (h < j) {
                upload(k, j, q, t);
            } else {
                t(r["upload_key"]);
            }
        },
        error: function (s) {
            $("#error")["fadeIn"](200);
        }
    });
}
var cleanUp = function () {
    $(".progress")["hide"](100, function () {
        $(".progress-bar")["attr"]("style", "width: 0%")["attr"]("aria-valuenow", "0");
    });
};
var listUploadedFiles = function (i, m) {
    var o = $("div.completed-uploads");
    o["find"]("p.info")["remove"]();
    $("#upload")["text"]("Done!");
    o["append"]("<div class=\"panel panel-success\"><div class=\"panel-body\">Upload successful! Refresh the page to view ISO status.</div></div>");
};
var renderProgressBar = function (h, j) {
    var p = (h / j * 100)["toFixed"](2);
    $(".progress-bar")["attr"]("style", "width: " + p + "%")["attr"]("aria-valuenow", p)["find"]("span")["html"](p + "%");
    $(".progress")["show"]();
};
