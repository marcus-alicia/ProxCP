$(document)["ready"](function () {
    $("#ramslider")["slider"]();
    $("#ramslider")["on"]("slide", function (a) {
        $("#ramsliderVal")["text"](a["value"]);
    });
    $("#cpuslider")["slider"]();
    $("#cpuslider")["on"]("slide", function (a) {
        $("#cpusliderVal")["text"](a["value"]);
    });
    $("#diskslider")["slider"]();
    $("#diskslider")["on"]("slide", function (a) {
        $("#disksliderVal")["text"](a["value"]);
    });
});
