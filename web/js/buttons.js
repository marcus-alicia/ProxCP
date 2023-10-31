$(function () {
    $(".button-checkbox")["each"](function () {
        var _0xf91fx1 = $(this);
        var _0xf91fx2 = _0xf91fx1["find"]("button");
        var _0xf91fx3 = _0xf91fx1["find"]("input:checkbox");
        var _0xf91fx4 = _0xf91fx2["data"]("color");
        var _0xf91fx5 = {
            on: {
                icon: "glyphicon glyphicon-check"
            },
            off: {
                icon: "glyphicon glyphicon-unchecked"
            }
        };
        _0xf91fx2["on"]("click", function () {
            _0xf91fx3["prop"]("checked", !_0xf91fx3["is"](":checked"));
            _0xf91fx3["triggerHandler"]("change");
            _0xf91fx6();
        });
        _0xf91fx3["on"]("change", function () {
            _0xf91fx6();
        });

        function _0xf91fx6() {
            var _0xf91fx7 = _0xf91fx3["is"](":checked");
            _0xf91fx2["data"]("state", _0xf91fx7 ? "on" : "off");
            _0xf91fx2["find"](".state-icon")["removeClass"]()["addClass"]("state-icon " + _0xf91fx5[_0xf91fx2["data"]("state")]["icon"]);
            if (_0xf91fx7) {
                _0xf91fx2["removeClass"]("btn-default")["addClass"]("btn-" + _0xf91fx4 + " active");
            } else {
                _0xf91fx2["removeClass"]("btn-" + _0xf91fx4 + " active")["addClass"]("btn-default");
            };
        };

        function _0xf91fx8() {
            _0xf91fx6();
            if (_0xf91fx2["find"](".state-icon")["length"] == 0) {
                _0xf91fx2["prepend"]("<i class=\"state-icon " + _0xf91fx5[_0xf91fx2["data"]("state")]["icon"] + "\"></i>\xA0");
            };
        };
        _0xf91fx8();
    });
});
