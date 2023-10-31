$(document)["ready"](function () {
    $("#geoipmap")["vectorMap"]({
        map: "world_mill",
        scaleColors: ["#C8EEFF", "#0071A4"],
        normalizeFunction: "polynomial",
        hoverOpacity: 0.7,
        hoverColor: false,
        markerStyle: {
            initial: {
                fill: "#F8E23B",
                stroke: "#383f47"
            }
        },
        backgroundColor: "#383f47",
        zoomButtons: false,
        onRegionTipShow: function (c, d, b) {
            c["preventDefault"]();
        }
    });
    var a = $("#geoipmap")["vectorMap"]("get", "mapObject");
    $(".geoipdata")["each"](function (h, j) {
        var k = $(this)["val"]();
        var f = k["split"]("#");
        var g = f[3]["split"](" ");
        var j = {
            latLng: [parseFloat(g[0]), parseFloat(g[1])]
        };
        a["addMarker"](f[1], j);
    });
});
