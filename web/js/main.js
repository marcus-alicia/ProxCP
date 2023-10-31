$(document)["ready"](function () {
    $(".tab-action")["click"](function () {
        var a = $(this)["data"]("tab-cnt");
        $(".tab-cnt")["removeClass"]("active");
        $("#" + a)["css"]("display", "none")["toggleClass"]("active");
        $(".tab-action")["removeClass"]("active");
        $(this)["toggleClass"]("active");
    });
    if ($("#username")["val"]() == "") {
        $("#username")["focus"]();
    } else {
        $("#password")["focus"]();
    };
    $(".tooltip-wrapper")["tooltip"]();
    $("#manquick")["change"](function () {
        $("#manquickgo")["attr"]("href", $(this)["val"]());
    });
    $("#fwquick")["change"](function () {
        $("#fwquickgo")["attr"]("href", $(this)["val"]());
    });
    $(".template_setup_btn")["click"](function (b) {
        $(this)["addClass"]("disabled");
        $(this)["html"]("Please wait...");
    });
});
